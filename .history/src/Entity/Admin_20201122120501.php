<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *  routePrefix="/admin",
 *  collectionOperations={
 *     "add_admin" = {
 *          "method"="POST",
 *          "path"="/admins",
 *          "route_name"="add_admin",
 *      }
 *  },
 *  itemOperations={
 *      "PUT",
 *      "put_admin" = {
 *          "method"="POST",
 *          "path"="/admins/{id}",
 *          "route_name"="put_admin",
 *      },
 *      "edit_apprenant"={
 *          "method"="PUT",
 *          "path"="/apprenants/{id}",
 *          "security"="is_granted('APPRENANT_EDIT', object)",
 *          "security_message"="Vous n'avez pas le droit de modifier ces informations."
 *      },
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
