<?php

namespace App\Services;

use App\Entity\Tag;
use App\Entity\GroupeTag;
use App\Repository\TagRepository;
use App\Repository\GroupeTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;



class TagService
{
    private $repoTag;
    private $validator;
    private $manager;
    private $serializer;
    private $repoGroupeTag;

    public function __construct(GroupeTagRepository $repoGroupeTag, TagRepository $repoTag, ValidatorInterface $validator, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $this->repoTag = $repoTag;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->repoGroupeTag = $repoGroupeTag;
    }

    //Ajout d'un groupe de tag

    public function addGroupeTag($request)
    {
        $data = json_decode($request->getContent(), true);
        $tags = $data["tags"];

        if (count($tags) < 1) {
            return new JsonResponse("Un tag est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $groupeTag = new GroupeTag();
        foreach ($data as $key => $value) {
            //dd($value);
            if ($value != "") {
                if (!is_array($value)) {
                    $toSet = "set" . ucfirst(strtolower($key));
                    $groupeTag->$toSet($value);
                }
            } else {
                return new JsonResponse("Veuillez remplir le (la) " . $key . ".", Response::HTTP_BAD_REQUEST, [], true);
            }
        }

        $tabLibelle = [];
        foreach ($tags as $value) {
            if (!empty($value["libelle"])) {
                $tag = $this->repoTag->findBy(array('libelle' => $value["libelle"]));
                if ($tag) {
                    $groupeTag->addTag($tag[0]);
                } else {
                    if (!in_array($value["libelle"], $tabLibelle)) {
                        $tabLibelle[] = $value["libelle"];
                        $tag = new Tag();
                        $tag->setLibelle($value["libelle"]);
                        $groupeTag->addTag($tag);
                    }
                }
            }
        }

        if (count($groupeTag->getTags()) < 1) {
            return new JsonResponse("Le libelle d'un tag est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $errors = $this->validator->validate($groupeTag);
        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }

        $this->manager->persist($groupeTag);
        $this->manager->flush();
        $groupeTagCreated = $this->serializer->serialize($groupeTag, 'json');
        return new JsonResponse($groupeTagCreated, Response::HTTP_CREATED, [], true);
    }

    public function updateGroupeTag($request, $id)
    {
        $data = json_decode($request->getContent(), true);

        $groupeTag = $this->repoGroupeTag->find($id);
        if (is_null($groupeTag)) {
            return new JsonResponse("Ce groupe de tags n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $groupeTagUpdated = $this->serializer->serialize($groupeTag, 'json');
        return new JsonResponse($groupeTagUpdated, Response::HTTP_CREATED, [], true);
    /**Archivage */
        if (isset($data['deleted']) && $data['deleted']) {
            $groupeTag->setDeleted($data['deleted']);
            $this->manager->flush();
            return new JsonResponse('Groupe de tags archivé.', Response::HTTP_NO_CONTENT, [], true);
        }

        foreach ($data as $key => $value) {
            if (isset($key) || !empty($key)) {
                if (!is_array($value)) {
                    $toSet = "set" . ucfirst(strtolower($key));
                    $groupeTag->$toSet($value);
                }
            }
        }

        $tagsBrute = $data['tags'];

    //Removed
        foreach ($tagsBrute as $key) {
            $tags[] = $key["libelle"];
        }
        foreach ($groupeTag->getTags() as $tagDB) {

            if (!in_array($tagDB->getLibelle(), $tags)) {
                //dd("in");
                $groupeTag->removeTag($tagDB);
            }
        }

    //Added
        foreach ($groupeTag->getTags() as  $value) {
            $allTags[] = $value->getLibelle();
        }
        foreach ($tags as $newtag) {
            if (!in_array($newtag, $allTags)) {
                $tagAdded = $this->repoTag->findBy(array('libelle' => $newtag));
                if ($tagAdded) {
                    $groupeTag->addTag($tagAdded[0]);
                }
            }
        }
       
            /*foreach ($tags as $val2) {
                foreach ($groupeTag as $tagDB2) {
                    if ($val2["libelle"] != $tagDB2->getLibelle()) {
                        //dd("in");
                        $tagDB = $this->repoTag->findBy(array('libelle' => $value['libelle']));
                        $groupeTag->addTag($tagDB[0]);  
                    }
                }
            }*/
            /*foreach ($tags as $value) {
                //dd($value);
                if ($value != "") {
                    if (empty($value['action'])) {
                        return new JsonResponse("Veuillez choisir une action pour le tag =>.".$value['libelle'], Response::HTTP_BAD_REQUEST, [], true);
                    }
                    //dd($action);
                    $tagDB = $this->repoTag->findBy(array('libelle' => $value['libelle']));
                    if (!empty($tagDB)) {
                        foreach ($groupeTag->getTags() as $tag) {
                           // dd($groupeCompetence->getLIbelle());
                            if ($tag->getLibelle() == $value['libelle']) {
                                if ($value['action'] == "supprimer") {
                                    $groupeTag->removeTag($tagDB[0]);
                                }
                            } else {
                                if ($value['action'] == "ajouter") {
                                    $groupeTag->addTag($tagDB[0]);
                                }
                            }
                        }
                    } else {
                        return new JsonResponse("Le groupe de compétence ´´" . $value['libelle'] . "´´ n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
                    }
                }
            }*/

        //dd($groupeTag);


        if (count($groupeTag->getTags()) < 1) {
            return new JsonResponse("Les libellés des tags sont requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        //$this->manager->persist($groupeTag);
        //$this->manager->flush();
        $groupeTagUpdated = $this->serializer->serialize($groupeTag, 'json');
        return new JsonResponse($groupeTagUpdated, Response::HTTP_CREATED, [], true);
    }
}
