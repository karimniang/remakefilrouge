<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *  collectionOperations={
 *      "post_apprenant"={
 *         "method"="POST",
 *         "path"="/apprenants",
 *         "controller"=UserController::class,
 *         "access_control"="(is_granted('ROLE_ADMIN'))",
 *         "route_name"="add_apprenant"
 *     }
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
