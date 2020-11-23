<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *  collectionOperations={
 *      "post_admin"={
 *         "method"="POST",
 *         "path"="/admins",
 *         "controller"=UserController::class,
 *         "access_control"="(is_granted('ROLE_ADMIN'))",
 *         "route_name"="add_admin"
 *     }
 *  }
 * )
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 */
class Admin extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
   
}
