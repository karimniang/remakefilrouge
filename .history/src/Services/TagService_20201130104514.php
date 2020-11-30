<?php

namespace App\Services;

use App\Entity\Tag;
use App\Entity\GroupeTag;
use App\Repository\TagRepository;
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

    public function __construct(TagRepository $repoTag, ValidatorInterface $validator, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $this->repoTag = $repoTag;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->serializer = $serializer;
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
                if ($key != "tags") {
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

        //$this->manager->persist($groupeTag);
        //$this->manager->flush();
        $groupeTagCreated = $this->serializer->serialize($groupeTag, 'json');
        return new JsonResponse($groupeTagCreated, Response::HTTP_CREATED, [], true);
    }
}
