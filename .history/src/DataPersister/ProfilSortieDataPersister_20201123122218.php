<?php

namespace App\DataPersister;

use App\Entity\UserProfil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\ProfilSortie;

class ProfilSortieDataPersister implements DataPersisterInterface
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function supports($data): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof ProfilSortie;
    }

    public function persist($data)
    {
        // TODO: Implement persist() method.
    }

    public function remove($data)
    {
        // TODO: Implement remove() method.
        $data->setDeleted(true);
        if (!empty($data->getUsers())) {
            foreach ($data->getUsers() as $user) {
                $user->setDeleted(true);
            }
        }*/
        
        $this->entityManager->flush();
    }
}
