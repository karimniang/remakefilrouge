<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  routePrefix="/admin",
 *  collectionOperations={
 *      "show_all_apprenant"={
 *          "method"="GET",
 *          "path"="/apprenants"
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
     * @Groups({"promotion:read_all","promo_groupe_apprenants:read","groupe:read","apprenant:read"})
     */
    
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="apprenants")
     */
    private $promotion;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenants")
     */
    private $groupes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $attente = true;

    /**
     * @ORM\ManyToOne(targetEntity=Statut::class, inversedBy="apprenants")
     */
    private $statut;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeApprenant($this);
        }

        return $this;
    }

    public function getAttente(): ?bool
    {
        return $this->attente;
    }

    public function setAttente(bool $attente): self
    {
        $this->attente = $attente;

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    
}
