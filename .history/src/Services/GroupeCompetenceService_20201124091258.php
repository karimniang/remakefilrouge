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

    Public function addGroupeCompetence(){
        
    }
}