<?php

namespace App\DataPersister;

use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\GroupeCompetence;

class GroupeCompetenceDataPersister implements DataPersisterInterface
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function supports($data): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof GroupeCompetence;
    }

    public function persist($data)
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        // TODO: Implement persist() method.
    }

    public function remove($data)
    {
        // TODO: Implement remove() method.
        $data->setDeleted(true);
        // if (!empty($data->getUsers())) {
        //     foreach ($data->getUsers() as $user) {
        //         $user->setDeleted(true);
        //     }
        // }
        
        $this->entityManager->flush();
    }
}
