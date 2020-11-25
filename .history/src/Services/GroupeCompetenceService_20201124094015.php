<?php

namespace App\Services;

use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Entity\NiveauEvaluation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;



class GroupeCompetenceService {
    private $manager;
    private $serializer;
    private $repoCompetence;

    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer, CompetencepetenceRepository $repoCompetence)
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
                $competence = $this->repo->findBy(array('libelle' => $value["libelle"]));
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
        dd($groupeCompetence);
        //$this->em->persist($groupeCompetence);
        //$this->em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}