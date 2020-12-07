<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  routePrefix="/admin",
 *  collectionOperations={
 *      "GET"={
 *          "normalization_context"={"groups"={"groupe:read"}}
 *      },
 *      "get_apprenants"={
 *          "method"="GET",
 *          "path"="/groupes/apprenants",
 *          "normalization_context"={"groups"={"apprenant_groupe:read"}}
 *      },
 *      "POST"={
 *          "denormalization_context"={"groups"={"groupe_write"}}
 *      }
 *  },
 *  itemOperations={
 *      "GET"={
 *          "normalization_context"={"groups"={"groupe:read"}}
 *      },
 *      "PUT"={
 *          "denormalization_context"={"groups"={"groupe_write"}}
 *      },
 *      "DELETE"={
 *          "path"="/groupes/{id_groupe}"
 *      }
 *  }
 * )
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"promotion:read","promotion:read_all","promotion:read_formateur","promo_groupe_apprenants:read","apprenant_groupe:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promotion:read","promotion:read_all","promotion:read_formateur","promo_groupe_apprenants:read","apprenant_groupe:read","groupe_write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promotion:read","promotion:read_all","groupe_write"})
     */
    private $dateCreation;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     * @Groups({"promotion:read_all","promo_groupe_apprenants:read","groupe:read","apprenant_groupe:read","groupe_write"})
     */
    private $apprenants;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     * @Groups({"groupe:read","groupe_write"})
     */
    private $formateurs;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="groupes")
     * @Groups({"promo_groupe_apprenants:read","groupe:read","groupe_write"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $promotion;

    /**
     * @ORM\OneToMany(targetEntity=EtatBriefGroupe::class, mappedBy="groupe")
     */
    private $etatBriefGroupes;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
        $this->etatBriefGroupes = new ArrayCollection();
    }

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        $this->apprenants->removeElement($apprenant);

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        $this->formateurs->removeElement($formateur);

        return $this;
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
     * @return Collection|EtatBriefGroupe[]
     */
    public function getEtatBriefGroupes(): Collection
    {
        return $this->etatBriefGroupes;
    }

    public function addEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if (!$this->etatBriefGroupes->contains($etatBriefGroupe)) {
            $this->etatBriefGroupes[] = $etatBriefGroupe;
            $etatBriefGroupe->setGroupe($this);
        }

        return $this;
    }

    public function removeEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if ($this->etatBriefGroupes->removeElement($etatBriefGroupe)) {
            // set the owning side to null (unless already changed)
            if ($etatBriefGroupe->getGroupe() === $this) {
                $etatBriefGroupe->setGroupe(null);
            }
        }

        return $this;
    }
}
