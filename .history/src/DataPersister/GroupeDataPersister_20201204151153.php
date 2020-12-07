<?php

namespace App\DataPersister;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Groupe;

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
        return $data instanceof Groupe;
    }   

    public function persist($data)
    {
        dd($data->getApprenants()[2]);
        /*for ($i=0; $i < count($data->getApprenants()) ; $i++) { 
           dd($data->getApprenants()[0]);
        }*/
        //dd(is_array($data->getApprenants()));

        $this->entityManager->persist($data);
        $this->entityManager->flush();
        // TODO: Implement persist() method.
    }

    public function remove($data)
    {
        $apprenant = $data->getApprenants()[0];
       // dd($apprenant);
        $data->removeApprenant($apprenant);
        $this->entityManager->flush();
    }
}
