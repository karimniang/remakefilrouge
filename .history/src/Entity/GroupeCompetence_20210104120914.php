<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 *  routePrefix = "/admin",
 *  collectionOperations={
 *      "GET"={
 *          "path"="/grpecompetences",
 *          "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *          "security_message"="Vous ne pouvez pas accéder à ces informations",
 *          "normalization_context"={"groups"={"groupe_competence:read_all"}}
 *      },
 *      "get_by_competences"={
 *          "method"="GET",
 *          "path"="/grpecompetences/competences",
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez accés à ces informations."
 *      },
 *      "add_groupe_competence"={
 *          "method"="POST",
 *          "path"="/grpecompetences",
 *          "route_name"="add_groupe_competence"
 *     }
 *  },
 *  itemOperations={
 *      "GET"={
 *          "path"="/grpecompetences/{id}",
 *          "security"="is_granted('GROUPE_VIEW', object)",
 *          "security_message"="Vous n'avez accés à ces informations.",
 *          "normalization_context"={"groups"={"groupe_competence:read_all"}}
 *      },
 *      "get_by_competences_by_id"={
 *          "method"="GET",
 *          "path"="/grpecompetences/{id}/competences",
 *          "security"="is_granted('GROUPE_VIEW', object)",
 *          "security_message"="Vous n'avez pas accés à ces informations."
 *      },
 *      "put_groupe_competence"={
 *          "method"="PUT",
 *          "path"="/grpecompetences/{id}/{action}",
 *          "route_name"="put_groupe_competence"
 *     }
 *  }
 * )
 * @UniqueEntity(
 *  fields={"libelle"},
 *  message="Le libelle existe déjà."
 * )
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 * 
 * @ApiFilter(BooleanFilter::class, properties={"deleted"})
 */
class GroupeCompetence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"groupe_competence:read_all","competence:read_all" ,"referentiel:read","referentiel:read_all","promotion:read_all_ref"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe_competence:read_all","competence:read_all","referentiel:read","referentiel:read_all","promotion:read_all_ref"})
     * @Assert\NotBlank(message="Le libelle est obligatoire.")
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"groupe_competence:read_all","referentiel:read","referentiel:read_all","promotion:read_all_ref"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeCompetences", cascade={"persist"})
     * @Groups({"groupe_competence:read_all","referentiel:read_all","promotion:read_all_ref"})
     */
    private $competences;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted= false;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="groupeCompetences")
     */
    private $referentiels;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
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

    /**
     * @return Collection|Competence[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        $this->competences->removeElement($competence);

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
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
            $referentiel->addGroupeCompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->removeElement($referentiel)) {
            $referentiel->removeGroupeCompetence($this);
        }

        return $this;
    }
}
