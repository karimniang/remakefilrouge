<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *  routePrefix="/admin",
 *  attributes={"security"="is_granted('ROLE_ADMIN')"},
 *  collectionOperations={
 *      "add_apprenant" = {
 *          "method"="POST",
 *          "path"="/apprenants",
 *          "route_name"="add_apprenant"
 *      }
 *  }
 * )
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 */
class Apprenant extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    

    
}
