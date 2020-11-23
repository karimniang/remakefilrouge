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
 *      "PUT",
 *      "show_admin"={
 *          "method"="GET",
 *          "path"="/admins/{id}",
 *          "security"="is_granted('ADMIN_VIEW', object)",
 *          "security_message"="Vous n'avez pas accès à ces informations."
 *      },
 *      "put_apprenant" = {
 *          "method"="POST",
 *          "path"="/apprenants/{id}",
 *          "route_name"="put_apprenant",
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
