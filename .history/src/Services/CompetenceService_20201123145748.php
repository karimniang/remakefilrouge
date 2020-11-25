<?php

namespace App\Services;



class CompetenceService {
    private $manager;
    private $serializer;

    public function __construct(UserRepository $repoUser,UserProfilRepository $repoProfils, EntityManagerInterface $manager, SerializerInterface $serializer, UserPasswordEncoderInterface $encoder)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
    }
}