<?php

namespace App\Services;

use App\Entity\Competence;
use App\Entity\NiveauEvaluation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;



class GroupeCompetenceService {
    private $manager;
    private $serializer;
    private $repoGroupeCom;

    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer, GroupeCompetenceRepository $repoGroupeCom)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->repoGroupeCom =$repoGroupeCom;
    }

    Public function addGroupeCompetence($re){
        $data = json_decode( $request->getContent(),true);
        //dd($data);
        $competences = $data->getCompetences();
        if (count($competences) < 1) {
            return new JsonResponse("Une compétence est requise.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $groupeCompetence = new GroupeCompetence();
        $groupeCompetence->setLibelle($data->getLibelle());
        $groupeCompetence->setDescription($data->getDescription());
        $tabLibelle = [];


        foreach ($competences as $value) {
            if (!empty($value->getLibelle())) {
                $competence = $this->repo->findBy(array('libelle' => $value->getLibelle()));
                if ($competence) {
                    $groupeCompetence->addCompetence($competence[0]);
                } else {
                    if (!in_array($value->getlibelle(), $tabLibelle)) {
                        $tabLibelle[] = $value->getlibelle();
                        $competence = new Competence();
                        $competence->setLibelle($value->getLibelle());
                        $groupeCompetence->addCompetence($competence);
                    }
                }
            }
        }

        if (count($groupeCompetence->getCompetences())<1) {
            return new JsonResponse("Une compétence est requise.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $this->em->persist($groupeCompetence);
        $this->em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}