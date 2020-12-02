<?php

namespace App\Services;

use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Entity\NiveauEvaluation;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;



class GroupeCompetenceService
{
    private $manager;
    private $serializer;
    private $repoCompetence;
    private $repoGroupeComp;

    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer, CompetenceRepository $repoCompetence, GroupeCompetenceRepository $repoGroupeComp)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->repoCompetence = $repoCompetence;
        $this->repoGroupeComp = $repoGroupeComp;
    }

    public function addGroupeCompetence($request)
    {
        $data = json_decode($request->getContent(), true);
        //dd($data);
        $competences = $data['competences'];
        if (count($competences) < 1) {
            return new JsonResponse("Une compétence est requise.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $groupeCompetence = new GroupeCompetence();
        $groupeCompetence->setLibelle($data['libelle']);
        $groupeCompetence->setDescription($data['description']);
        $tabLibelle = [];


        foreach ($competences as $value) {
            if (!empty($value['libelle'])) {
                $competence = $this->repoCompetence->findBy(array('libelle' => $value["libelle"]));
                if ($competence) {
                    $groupeCompetence->addCompetence($competence[0]);
                } else {
                    return new JsonResponse("la compétence ¦¦" . $value["libelle"] . "¦¦ n'existe pas dans la base de donnée.", Response::HTTP_BAD_REQUEST, [], true);
                }
            }
        }

        if (count($groupeCompetence->getCompetences()) < 1) {
            return new JsonResponse("Une compétence est requise pour créer un groupe de compétence.", Response::HTTP_BAD_REQUEST, [], true);
        }
        //dd($groupeCompetence);
        //$this->manager->persist($groupeCompetence);
        //$this->manager->flush();
        return new JsonResponse("Groupe Competence added successfulled", Response::HTTP_CREATED, [], true);
    }

    public function updateGroupeCompetence($request, $id, $action)
    {
        //dd($action);

        $data = json_decode($request->getContent(), true);


        $groupeCompetence = $this->repoGroupeComp->find($id);
        if (is_null($groupeCompetence)) {
            return new JsonResponse("Ce groupe de compétences n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }

        if (isset($data['deleted']) && $data['deleted']) {
            $groupeCompetence->setDeleted(true);
            foreach ($groupeCompetence->getCompetences() as $value) {
                $groupeCompetence->removeCompetence($value);
            }
            $this->manager->flush();
            return new JsonResponse('Groupe de Compétences archivé.', Response::HTTP_NO_CONTENT, [], true);
        }

        foreach ($data as $key => $value) {
            if (isset($key) || !empty($key)) {
                if (!is_array($value)) {
                    $toSet = "set" . ucfirst(strtolower($key));
                    $groupeCompetence->$toSet($value);
                }
            }
        }


        
        if (count($data['competences']) < 1) {
            return new JsonResponse("Une compétence est requise.", Response::HTTP_BAD_REQUEST, [], true);
        }


        //$GroupeCompetenceJson = $this->serializer->serialize($groupeCompetence, 'json');
        //return new JsonResponse($GroupeCompetenceJson, Response::HTTP_OK, [], true);

        $competencesBrutes = $data['competences'];
        

        /*if ($action == "supprimer") {
            foreach ($tabCompetence as $value) {
                foreach ($competences as $val) {
                    if ($value->getLibelle() == $val['libelle']) {
                        $competence = $this->repoCompetence->findBy(array('libelle' => $val['libelle']));
                        if ($competence) {
                            $groupeCompetence->removeCompetence($competence[0]);
                        } else {
                            return new JsonResponse("Cette compétence n'existe pas'.", Response::HTTP_BAD_REQUEST, [], true);
                        }
                    } else {
                        return new JsonResponse("Cette compétence ne se trouve pas dans ce groupe de compétence.", Response::HTTP_BAD_REQUEST, [], true);
                    }
                }
            }
        } elseif ($action == "ajouter") {
            foreach ($tabCompetence as $value) {
                foreach ($competences as $val) {
                    if ($value->getLibelle() == $val['libelle']) {
                        return new JsonResponse("La compétence ¦¦" . $val['libelle'] . "¦¦ existe deja dans ce groupe.", Response::HTTP_BAD_REQUEST, [], true);
                    } else {
                        $competence = $this->repoCompetence->findBy(array('libelle' => $val['libelle']));
                        if ($competence) {
                            $groupeCompetence->addCompetence($competence[0]);
                        } else {
                            return new JsonResponse("Cette compétence n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
                        }
                    }
                }
            }
        } else {
            return new JsonResponse("Veuillez choisir une action.", Response::HTTP_BAD_REQUEST, [], true);
        }*/
        
        if (count($groupeCompetence->getCompetences())<1) {
            return new JsonResponse("Les libellés des compétences sont requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        //$this->manager->persist($groupeCompetence);
        //$this->manager->flush();
        $GroupeCompetenceJson = $this->serializer->serialize($groupeCompetence, 'json');
        return new JsonResponse($GroupeCompetenceJson, Response::HTTP_OK, [], true);

        /**Archivage */
    }

    public function toAdded($elementsBrutes,$mere,$element){
        $toSet = "set" . ucfirst(strtolower($element));
        $toGet = "get" . ucfirst(strtolower($element))."s";
        $allCompetences =[];

    //Removed
        foreach ($elementsBrutes as $key) {
            $elements[] = $key["libelle"];
        }
        foreach ($mere->getCompetences() as $tagDB) {

            if (!in_array($tagDB->getLibelle(), $competences)) {
                //dd("in");
                $groupeCompetence->removeCompetence($tagDB);
            }
        }

    //Added
        foreach ($groupeCompetence->getCompetences() as  $value) {
            $allCompetences[] = $value->getLibelle();
        }
        foreach ($competences as $newLibelle) {
            if (!in_array($newLibelle, $allCompetences)) {
                $competenceAdded = $this->repoCompetence->findBy(array('libelle' => $newLibelle));
                if ($competenceAdded) {
                    $groupeCompetence->addCompetence($competenceAdded[0]);
                }else {
                    $competence = new Competence();
                    $competence->setLibelle($newLibelle);
                    $groupeCompetence->addCompetence($competence);
                }
            }
        }
    }
}