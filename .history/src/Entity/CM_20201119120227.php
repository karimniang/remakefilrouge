<?php

namespace App\Entity;

use App\Repository\CMRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *  collectionOperations={
 *      "post_cm"={
 *         "method"="POST",
 *         "path"="/cms",
 *         "controller"=UserController::class,
 *         "access_control"="(is_granted('ROLE_ADMIN'))",
 *         "route_name"="add_cm"
 *     }
 *  }
 * )
 * @ORM\Entity(repositoryClass=CMRepository::class)
 */
class CM extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    
}
