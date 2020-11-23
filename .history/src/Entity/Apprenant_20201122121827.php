<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *  routePrefix="/admin",
 *  collectionOperations={
 *      "add_apprenant" = {
 *          "method"="POST",
 *          "path"="/apprenants",
 *          "route_name"="add_apprenant"
 *      }
 *  },
 *  itemOperations={
 *      "put_admin" = {
 *          "method"="POST",
 *          "path"="/apprenants/{id}",
 *          "route_name"="put_admin",
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
