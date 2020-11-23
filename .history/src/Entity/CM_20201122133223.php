<?php

namespace App\Entity;

use App\Repository\CMRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *  routePrefix="/admin",
 *  collectionOperations={
 *      "GET",
 *      "add_cm" = {
 *          "method"="POST",
 *          "path"="/cms",
 *          "route_name"="add_cm"
 *      }
 *  },
 *  itemOperations={
 *      "PUT",
 *      "show_cm"={
 *          "method"="GET",
 *          "path"="/cms/{id}",
 *          "security"="is_granted('CM_VIEW', object)",
 *          "security_message"="Vous n'avez pas accès à ces informations."
 *      },
 *      "put_cm" = {
 *          "method"="POST",
 *          "path"="/cms/{id}",
 *          "route_name"="put_cm",
 *          "security"="is_granted('ADMIN_EDIT', object)",
 *          "security_message"="Vous n'avez pas le droit de modifier ces informations."
 *      }
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
