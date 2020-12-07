<?php

namespace App\DataPersister;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Groupe;
use Symfony\Component\HttpFoundation\Request;

class GroupeDataPersister implements DataPersisterInterface
{

    private $entityManager;
    private $request;
    public function __construct(EntityManagerInterface $entityManager, Request $request)
    {
        $this->entityManager = $entityManager;
        $this->request = $request;
    }
    public function supports($data): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof Groupe;
    }   

    public function persist($data)
    {
        //dd($data->getPromotion());
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        // TODO: Implement persist() method.
    }

    public function remove($data)
    {
        dd($this->request);
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
