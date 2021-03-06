<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(
 *  fields={"libelle"},
 *  message="Ce libelle existe déjà"
 * )
 * @ApiResource(
 *  routePrefix="/admin",
 *  collectionOperations={
 *      "get"={
 *          "normalization_context"={"groups"={"referentiel:read"}}
 *      },
 *      "getByCompetences"={
 *          "method"="GET",
 *          "path"="/referentiels/grpecompetences",
 *          "normalization_context"={"groups"={"referentiel:read_all"}}
 *      },
 *      "post_referentiel"={
 *          "method"="POST",
 *          "path"="/referentiels",
 *          "route_name"="add_referentiel",
 *          "denormalization_context"={"groups"={"referentiel:write"}}
 *      }
 *  },
 *  itemOperations={
 *      "get_referentiel_id"={
 *          "method"="GET",
 *          "path"="/referentiels/{id}",
 *          "defaults"={"id"=null},
 *          "normalization_context"={"groups"={"referentiel:read"}}
 *      },
 *      "get_groupe_referentiel_id"={
 *          "method"="GET",
 *          "path"="/referentiels/{id_referentiel}/groupe_competences/{id_groupe}",
 *          "route_name"="show_groupe_referentiel_id"
 *      },
 *      "put_referentiel"={
 *          "method"="PUT",
 *          "path"="/referentiels/{id}",
 *          "route_name"="edit_referentiel"
 *      }
 *  }
 * )
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * 
 * @ApiFilter(BooleanFilter::class, properties={"deleted"})
 */
class Referentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"promotion:read","referentiel:read","referentiel:read_all","promotion:read_all_ref","promotion:read_formateur","promo_groupe_apprenants:read","groupe:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promotion:read","referentiel:read","referentiel:read_all","referentiel:write","promotion:read_all_ref","promotion:read_formateur","promo_groupe_apprenants:read","groupe:read"})
     * @Assert\NotBlank(message="Le libelle est requis.")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read","referentiel:read_all","referentiel:write","promotion:read_all_ref","promo_groupe_apprenants:read"})
     * @Assert\NotBlank(message="La présentation est requise.")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Groups({"referentiel:read","referentiel:read_all","referentiel:write","promotion:read_all_ref"})
     * @Assert\NotBlank(message="Les critéres d'admissions sont requis.")
     */
    private $critereAdmission;

    /**
     * @ORM\Column(type="text")
     * @Groups({"referentiel:read","referentiel:read_all","referentiel:write","promotion:read_all_ref"})
     * @Assert\NotBlank(message="Les critéres d'evaluation sont requis.")
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="blob")
     * @Groups({"referentiel:read"})
     */
    private $programme;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="referentiels")
     * @Groups({"referentiel:read","referentiel:read_all","promotion:read_all_ref"})
     */
    private $groupeCompetences;

    /**
     * @ORM\ManyToMany(targetEntity=Promotion::class, mappedBy="referentiels")
     */
    private $promotions;

    /**
     * @ORM\OneToMany(targetEntity=Brief::class, mappedBy="referentiel")
     */
    private $briefs;

    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->promotions = new ArrayCollection();
        $this->briefs = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCritereAdmission(): ?string
    {
        return $this->critereAdmission;
    }

    public function setCritereAdmission(string $critereAdmission): self
    {
        $this->critereAdmission = $critereAdmission;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getProgramme()
    {
        return $this->programme!=null?stream_get_contents($this->programme):null;
    }

    public function setProgramme($programme): self
    {
        $this->programme = base64_encode($programme);

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

    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        $this->groupeCompetences->removeElement($groupeCompetence);

        return $this;
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
            $promotion->addReferentiel($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->removeElement($promotion)) {
            $promotion->removeReferentiel($this);
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
            $brief->setReferentiel($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->removeElement($brief)) {
            // set the owning side to null (unless already changed)
            if ($brief->getReferentiel() === $this) {
                $brief->setReferentiel(null);
            }
        }

        return $this;
    }
}
