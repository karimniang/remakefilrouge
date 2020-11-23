<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *  routePrefix="/admin",
 *  collectionOperations={
 *      "show_apprenant"={
 *          "method"="GET",
 *          "path"="/apprenants",
 *          "security"="is_granted('APPRENANT_VIEW')",
 *          "security_message"="Vous n'avez pas accès à ces informations."
 *      },
 *      "add_apprenant" = {
 *          "method"="POST",
 *          "path"="/apprenants",
 *          "route_name"="add_apprenant"
 *      }
 *  },
 *  itemOperations={
 *      "PUT",
 *      "show_apprenant"={
 *          "method"="GET",
 *          "path"="/apprenants/{id}",
 *          "security"="is_granted('APPRENANT_VIEW', object)",
 *          "security_message"="Vous n'avez pas accès à ces informations."
 *      },
 *      "put_apprenant" = {
 *          "method"="POST",
 *          "path"="/apprenants/{id}",
 *          "route_name"="put_apprenant",
 *          "security"="is_granted('APPRENANT_EDIT', object)",
 *          "security_message"="Vous n'avez pas le droit de modifier ces informations."
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
