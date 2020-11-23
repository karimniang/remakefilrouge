<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *  routePrefix="/admin",
 *  collectionOperations={
 *     "show_formateur"={
 *          "method"="GET",
 *          "path"="/formateurs/{id}",
 *          "security"="is_granted('FORMATEUR_VIEW', object)",
 *          "security_message"="Vous n'avez pas accès à ces informations."
 *      },
 *      "add_formateur" = {
 *          "method"="POST",
 *          "path"="/formateurs",
 *          "route_name"="add_formateur"
 * 
 *      }
 *  },
 *  itemOperations={
 *      "PUT",
 *      "show_formateur"={
 *          "method"="GET",
 *          "path"="/formateurs/{id}",
 *          "security"="is_granted('FORMATEUR_VIEW', object)",
 *          "security_message"="Vous n'avez pas accès à ces informations."
 *      },
 *      "put_formateur" = {
 *          "method"="POST",
 *          "path"="/formateurs/{id}",
 *          "route_name"="put_formateur",
 *          "security"="is_granted('FORMATEUR_EDIT', object)",
 *          "security_message"="Vous n'avez pas le droit de modifier ces informations."
 *      }
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
