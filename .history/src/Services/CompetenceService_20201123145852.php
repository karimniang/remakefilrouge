<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;



class CompetenceService {
    private $manager;
    private $serializer;

    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer, GroupeC)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
    }
}