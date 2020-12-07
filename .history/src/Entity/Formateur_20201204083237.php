<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  routePrefix="/admin",
 *  collectionOperations={
 *     "show_all_formateur"={
 *          "method"="GET",
 *          "path"="/formateurs",
 *          "security"="is_granted('FORMATEUR_ALL_VIEW', object)",
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
     * @Groups({"promotion:read","promotion:read_formateur","groupe:read","groupe_write"})
     */
    
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity=Promotion::class, mappedBy="formateurs")
     */
    private $promotions;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="formateurs")
     */
    private $groupes;

    /**
     * @ORM\OneToMany(targetEntity=Brief::class, mappedBy="formateurs")
     */
    private $briefs;

    public function __construct()
    {
        $this->promotions = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->briefs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Promotion[]
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions[] = $promotion;
            $promotion->addFormateur($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->removeElement($promotion)) {
            $promotion->removeFormateur($this);
        }

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
            $groupe->addFormateur($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeFormateur($this);
        }

        return $this;
    }

    /**
     * @return Collection|Brief[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->setFormateurs($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->removeElement($brief)) {
            // set the owning side to null (unless already changed)
            if ($brief->getFormateurs() === $this) {
                $brief->setFormateurs(null);
            }
        }

        return $this;
    }
}
