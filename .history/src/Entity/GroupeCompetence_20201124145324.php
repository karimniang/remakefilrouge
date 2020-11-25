<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(
 *  fields={"libelle"},
 *  message="Le libelle existe déjà."
 * )
 * @ApiResource(
 *  routePrefix = "/admin",
 *  collectionOperations={
 *      "GET"={
 *          "path"="/grpecompetences",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "normalization_context"={"groups"={"groupe_competence:read_all"}}
 *      },
 *      "add_groupe_competence"={
 *          "method"="POST",
 *          "path"="/grpecompetences",
 *          "route_name"="add_groupe_competence",
 *          eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MDYyMjk1NDIsImV4cCI6MTYwNjIzMzE0Miwicm9sZXMiOlsiUk9MRV9DTSJdLCJ1c2VybmFtZSI6ImNtMCJ9.X9BVYUrEsAgpg7IPkP_PpeRfWUYW7_976e0NBgrsJdnrqsF3JZBKlWjlCPqn70s1qfrq2nrJM4E-TaMOLTV7cTgolR2vKCcKnA42hSjcstKOrTY88edWcKH26KPonBEGDhMsCpzW3Fk82qyriyKeU30bFxJP6bvyBySOaaWEpQuj7eCxnb1qc0t_n_cZh2kf5lJGl41DHoOogdYH9bjMt4KUfPeARwaoHFXBL0F6NjVLDRoxTcJxypmJDq47jaLvyAOsDjahNZkl71ktVqOUSYu0lamaF0k6HzXtW6kuTLhnrPAfuDj34psBa-WvTLPMwSanQeTaaLcx6giCYZWVupZmO7jfl_dymuXpYfc6aADmqMedJSv0Q6tMkH_tpA8POpp91CwADkfz_UhSV7ACeGnI_Aps0bGitLZfVcW72m4lJVUPYID1D5PpgN_Aeqzf5SSOxd_f2US_LtPz9wYW4JMvUGIVjmJICfCrv8acXSmpw_NwJBRYyfoUqkGhWR_nu8w-udcoTncLyZwzUvPvl9ZOCSStZSHSuoPMu0o1akXuKpGP8A6DDMKfgq0MryhF7w5kgExVdX1jzyLVMxDkUBHbazfebEk4hIxq5P8APh-OFQl-e3h8pcr0u0DFiEFuAFh0-d0WnzqI-KRh6if4vUX3R08Wm_RdseIanPgVDdY
 *     }
 *  },
 *  itemOperations={
 *      "GET"={
 *          
 *      }
 *  }
 * )
 * 
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 */
class GroupeCompetence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"groupe_competence:read_all"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe_competence:read_all"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"groupe_competence:read_all"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeCompetences", cascade={"persist"})
     * @Groups({"groupe_competence:read_all"})
     */
    private $competences;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
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
}
