<?php

namespace App\Services;



class CompetenceService {
    private $manager;
    private $serializer;

    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
    }
}