<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *  routePrefix="/admin",
 *  collectionOperations={
 *      "add_formateur" = {
 *          "method"="POST",
 *          "path"="/formateurs",
 *          "route_name"="add_formateur"
 * 
 *      }
 *  },
 *  itemOperations={
 *      "put_admin" = {
 *          "method"="POST",
 *          "path"="/admins/{id}",
 *          "route_name"="put_admin",
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
