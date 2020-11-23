<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilSortieRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(
 *  fields={"libelle"},
 *  message="Le libelle existe déjà."
 * )
 * @ApiResource(
 *  routePrefix = "/admin",
 *  collectionOperations={
 *      "get_profils_sorti"={
 *          "method":"GET",
 *          "path":"/profilsorties"
 *      },
 *      "add_profils_sorti"={
 *          "method":"POST",
 *          "path":"/profilsorties"
 *      }
 *  },
 *  itemOperations={
 *      "get_profils_sorti_by_apprenant"={
 *          "method":"GET",
 *          "path":"/profilsorties/{id}"
 *      },
 *      "get_profils_sorti_by"={
 *          "method":"GET",
 *          "path":"/promo/{id_promo}/profilsorties/{id}"
 *      },
 *      "PUT"
 *  }
 * )
 * @ORM\Entity(repositoryClass=ProfilSortieRepository::class)
 */
class ProfilSortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libelle est obligatoire.")
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
