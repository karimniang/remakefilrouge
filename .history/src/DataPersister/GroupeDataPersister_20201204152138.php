<?php

namespace App\DataPersister;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Groupe;
use App\Repository\GroupeRepository;

class GroupeDataPersister implements DataPersisterInterface
{

    private $entityManager;
    private $repoGroupe;
    public function __construct(EntityManagerInterface $entityManager, GroupeRepository $repoGroupe)
    {
        $this->entityManager = $entityManager;
        $this->repoGroupe =$repoGroupe;
    }
    public function supports($data): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof Groupe;
    }   

    public function persist($data)
    {
        $id = $data->getId();
        $grp = $this->repoGroupe->find($id);
        //dd($grp->getApprenants());
        foreach ($data->getApprenants() as $apprenantAdded) {
            foreach ($grp->getApprenants() as $apprenantBdd) {
                if ($apprenantAdded == $apprenantBdd) {
                   dd("oki");
                }
            }
        }
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
