<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *  collectionOperations={
 *      "post_formateur"={
 *         "method"="POST",
 *         "path"="/formateurs",
 *         "controller"=UserController::class,
 *         "access_control"="(is_granted('ROLE_ADMIN'))",
 *         "route_name"="add_formateur"
 *     }
 *  }
 * )
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 */
class Formateur extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    
}
