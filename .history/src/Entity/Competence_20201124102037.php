<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MDYyMTI5OTAsImV4cCI6MTYwNjIxNjU5MCwicm9sZXMiOlsiUk9MRV9DTSJdLCJ1c2VybmFtZSI6ImNtMSJ9.ZFiGZZKmIY3qQxwjpmqN55AjZDK2VwDIZb63gYEMWHFROiiStHjdUKWTlxJQu0qNdiJbCD-bfnv6cZwlCBrv3OrSZp6RL_6XFt89h9wfKpXFrpluGnEbFbQic3S7aRl8XX63RM8U8TCcmOyuF_HV69OGUxngR_BzctsHcx8V6RfJW1Do00NahcaD_6XIM6rODcMtP4rxmeTk4_j76YeAS9Dn2hKdJiJZ5qJsJ25oT7Ciov6k6hMJxPVNfm8eDTZgWrIEswvxBSUZJEB-sgzScJYyyWTQ9OaZrZHvKqJzy4lyhIhuiRIVsA_PNKJPRVo1EQ1h_c19qXKUpJ8krwiHWG2ZpAg2-Cwoc32KDbMbNMURE8xXv_WSWWINn3ZMkamaNbyr_lpPLlTOf_u_pzs8oI_G88UFegp-hsmngf8w-LU9FFSOw7jsaS5XlxVUDAtUDTk2dL4nDAf2zBRBrew0kn_XpQsCjlbAuzFORUA1gjaNfe34DHeB0_GScwtYRMQkKfB5p5g5MBkik6cYLlMhBbeZmeFoHduOXIIGurZmPHG2L4OVnI6kYmzJFDRv9BGtGQkWGmtWlblGRfA3dtC103JP8lyyqNRgQscStNhP5lqyXCNg7FFXYOcdnJDe1EIB2yBVXyAFXB4LOmp3avNLrGIEuRncF2EP6A4P3ZlLrvY
 * @ApiResource(
 *  routePrefix = "/admin",
 *  collectionOperations={
 *      "GET"={
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "normalization_context"={"groups"={"competence:read_all"}}
 *      },
 *      "add_competence"={
 *          "method"="POST",
 *          "path"="/competences",
 *          "route_name"="add_competence",
 *          "security_post_denormalize"="is_granted('POST_COMPETENCE', object)",
 *          "security_message"="Vous ne pouvez pas ajouter de competences",
 *     }
 *  },
 *  itemOperations={
 *      "GET"
 *  }
 * )
 * 
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 */
class Competence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"competence:read_all"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competence:read_all"})
     * @Assert\NotBlank(message="Le libelle est obligatoire.")
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, mappedBy="competences")
     */
    private $groupeCompetences;

    /**
     * @ORM\OneToMany(targetEntity=NiveauEvaluation::class, mappedBy="competence", cascade={"persist"})
     * @Groups({"competence:read_all"})
     */
    private $niveaux;

    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
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
            $groupeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if ($this->groupeCompetences->removeElement($groupeCompetence)) {
            $groupeCompetence->removeCompetence($this);
        }

        return $this;
    }

    /**
     * @return Collection|NiveauEvaluation[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(NiveauEvaluation $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setCompetence($this);
        }

        return $this;
    }

    public function removeNiveau(NiveauEvaluation $niveau): self
    {
        if ($this->niveaux->removeElement($niveau)) {
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetence() === $this) {
                $niveau->setCompetence(null);
            }
        }

        return $this;
    }
}
