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
 *          "method"="GET",
 *          "path"="/admins",
 *          "route_name"="add_admin",
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Va la bassss"
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
   
}
