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
 *          "path":"/profilsorties",
 *          "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *          "security_message"="Vous n'avez pas accès à ces informations."
 *      },
 *       "get_profils_sorti_by_promo"={
 *          "method":"GET",
 *          "path":"/promo/{id_promo}/profilsorties"
 *      },
 *      "add_profils_sorti"={
 *          "method":"POST",
 *          "path":"/profilsorties",
 *          "security_post_denormalize"="is_granted('PROFIL_POST', object)",
 *          "security_message"="Vous ne pouvez ajouter de profils. CIAO !!!"
 *      }
 *  },
 *  itemOperations={
 *      "get_profils_sorti_by_apprenant"={
 *          "method":"GET",
 *          "path":"/profilsorties/{id}",
 *          "security"="is_granted('PROFIL_VIEW', object)",
 *          "security_message"="Vous n'avez pas accès à ces informations."
 *      },
 *      "get_profils_sorti_by_promo"={
 *          "method":"GET",
 *          "path":"/promo/{id_promo}/profilsorties/{id}"
 *      },
 *       "put_profils_sorti"={
 *          "method":"PUT",
 *          "path":"/profilsorties/{id}",
 *          "security"="is_granted('PROFIL_EDIT', object)",
 *          "security_message"="Vous ne pouvez pas modifier ces informations."
 *      },
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
