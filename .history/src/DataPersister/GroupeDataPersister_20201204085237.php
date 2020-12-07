<?php

namespace App\DataPersister;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Groupe;
use App\Entity\Tag;

class GroupeDataPersister implements DataPersisterInterface
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function supports($data): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof Tag;
    }

    public function persist($data)
    {
        //dd($data);
        // TODO: Implement persist() method.
    }

    public function remove($data)
    {
        // TODO: Implement remove() method.
        /*$data->setDeleted(true);
        if (!empty($data->getUsers())) {
            foreach ($data->getUsers() as $user) {
                $user->setDeleted(true);
            }
        }
        
        $this->entityManager->flush();*/
    }
}
