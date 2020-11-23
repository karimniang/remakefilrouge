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
 *         "path"="/competences",
 *         "controller"=AddCompetence::class,
 *         "access_control"="(is_granted('ROLE_ADMIN'))",
 *         "route_name"="add_competence",
 *         "denormalization_context"={"groups"={"competence:write"}}
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
