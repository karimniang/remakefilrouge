<?php

namespace App\Entity;

use App\Repository\CMRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *  routePrefix="/admin",
 *  collectionOperations={
 *      "add_cm" = {
 *          "method"="POST",
 *          "path"="/cms",
 *          "route_name"="add_cm"
 *      }
 *  },
 *  itemOperations={
 *      "put_cm" = {
 *          "method"="POST",
 *          "path"="/cms/{id}",
 *          "route_name"="put_cm",
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
