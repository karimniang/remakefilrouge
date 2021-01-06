<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NiveauEvaluationRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(
 *  fields={"libelle"},
 *  message="Le libelle existe déjà."
 * )
 * @ApiResource(
 *  routePrefix="/admin"
 * )
 * @ORM\Entity(repositoryClass=NiveauEvaluationRepository::class)
 */
class NiveauEvaluation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"competence:read_all","groupe_competence:read_all"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competence:read_all","groupe_competence:read_all"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"competence:read_all","groupe_competence:read_all"})
     */
    private $CritereEvaluation;

    /**
     * @ORM\Column(type="text")
     * @Groups({"competence:read_all","groupe_competence:read_all"})
     */
    private $GroupeAction;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="niveaux")
     */
    private $competence;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted= false;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, mappedBy="niveauxCompetences")
     */
    private $briefs;

    public function __construct()
    {
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

    public function getCritereEvaluation(): ?string
    {
        return $this->CritereEvaluation;
    }

    public function setCritereEvaluation(string $CritereEvaluation): self
    {
        $this->CritereEvaluation = $CritereEvaluation;

        return $this;
    }

    public function getGroupeAction(): ?string
    {
        return $this->GroupeAction;
    }

    public function setGroupeAction(string $GroupeAction): self
    {
        $this->GroupeAction = $GroupeAction;

        return $this;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

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
            $brief->addNiveauxCompetence($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->removeElement($brief)) {
            $brief->removeNiveauxCompetence($this);
        }

        return $this;
    }
}
