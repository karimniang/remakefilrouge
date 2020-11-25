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



class GroupeCompetenceService {
    private $manager;
    private $serializer;
    private $repoCompetence;

    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer, CompetenceRepository $repoCompetence)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->repoCompetence =$repoCompetence;
    }

    Public function addGroupeCompetence($request){
        $data = json_decode( $request->getContent(),true);
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
                    if (!in_array($value["libelle"], $tabLibelle)) {
                        $tabLibelle[] = $value["libelle"];
                        $competence = new Competence();
                        $competence->setLibelle($value["libelle"]);
                        $groupeCompetence->addCompetence($competence);
                    }
                }
            }
        }

        if (count($groupeCompetence->getCompetences())<1) {
            return new JsonResponse("Une compétence est requise pour créer un groupe de compétence.", Response::HTTP_BAD_REQUEST, [], true);
        }
        //dd($groupeCompetence);
        //$this->manager->persist($groupeCompetence);
        //$this->manager->flush();
        return new JsonResponse("Groupe Competence added successfulled", Response::HTTP_CREATED, [], true);
    }

    public function updateGroupeCompetence($request){
        
        $data=json_decode($request->getContent(),true);
        dd-$da
        /*
        $groupeCompetence = $repoGroupeComp->find($id);
        if(is_null($groupeCompetence)) {
            return new JsonResponse("Ce groupe de compétences n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }

        if(isset($data['deleted']) && $data['deleted']) {
            $groupeCompetence->setDeleted(true);
            $em->flush();
            return new JsonResponse('Groupe de Compétences archivé.', Response::HTTP_NO_CONTENT, [], true);
        }

        if (empty($data['libelle'])) {
            return new JsonResponse('Le libelle est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }

        $competences = $data['competences'];
        if (count($competences) < 1) {
            return new JsonResponse("Une compétence est requise.", Response::HTTP_BAD_REQUEST, [], true);
        }
        
       
        $tabCompetence = $groupeCompetence->getCompetences();
        
        foreach ($tabCompetence as $value) {
            $groupeCompetence->removeCompetence($value);
        }

        $groupeCompetence->setLibelle($data['libelle']);
        $groupeCompetence->setDescription($data['description']);
        
        $tabLibelle = [];
        foreach ($data['competences'] as $value){ 
            if (!empty($value['libelle'])){
                $competence = $repoComp->findBy(array('libelle' => $value['libelle']));
                if ($competence) {
                    $groupeCompetence->addCompetence($competence[0]);
                } else {
                    if (!in_array($value['libelle'], $tabLibelle)) {
                        $tabLibelle[] = $value['libelle'];
                        $competence = new Competence();
                        $competence->setLibelle($value['libelle']);
                        $groupeCompetence->addCompetence($competence);
                    }
                }
            }
        }

        if (count($groupeCompetence->getCompetences())<1) {
            return new JsonResponse("Les libellés des compétences sont requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($groupeCompetence);
        $em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
        */
        /**Archivage */
    }
}