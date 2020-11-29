<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
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
 *     }
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
 *          "path"="/referentiels/{id_referentiel}/groupe_competences/{id_groupe}"
 *          "route_name"="show_groupe_referentiel_id"
 *      },
 *      "put_referentiel"={
 *         "method"="PUT",
 *         "path"="/referentiels/{id}",
 *         "route_name"="edit_referentiel"
 *     }
 *  }
 * )
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 */
class Referentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"referentiel:read","referentiel:read_all"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read","referentiel:read_all","referentiel:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read","referentiel:read_all","referentiel:write"})
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Groups({"referentiel:read","referentiel:read_all","referentiel:write"})
     */
    private $critereAdmission;

    /**
     * @ORM\Column(type="text")
     * @Groups({"referentiel:read","referentiel:read_all","referentiel:write"})
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
     * @Groups({"referentiel:read","referentiel:read_all"})
     */
    private $groupeCompetences;

    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
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
        return $this->programme;
    }

    public function setProgramme($programme): self
    {
        $this->programme = $programme;

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
}
