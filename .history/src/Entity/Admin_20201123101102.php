<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *  routePrefix="/admin",
 *  collectionOperations={
 *     "GET",
 *     "add_admin" = {
 *          "method"="POST",
 *          "path"="/admins",
 *          "route_name"="add_admin",
 *      }
 *  },
 *  itemOperations={
 *      "PUT",
 *      "show_admin"={
 *          "method"="GET",
 *          "path"="/admins/{id}",
 *          "security"="is_granted('ADMIN_VIEW', object)",
 *          "security_message"="Vous n'avez pas accès à ces informations."
 *      },
 *      "put_admin" = {
 *          "method"="POST",
 *          "path"="/admins/{id}",
 *          "route_name"="put_admin",
 *          "security"="is_granted('ADMIN_EDIT', object)",
 *          "security_message"="Vous n'avez pas le droit de modifier ces informations."
 *      }
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
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }
   
}
