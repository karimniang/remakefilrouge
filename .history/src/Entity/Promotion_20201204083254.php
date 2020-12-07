<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 *  routePrefix= "/admin",
 *  collectionOperations={
 *      "add_promotion" = {
 *          "method"="POST",
 *          "path"="/promotions",
 *          "route_name"="add_promotion"
 *      },
 *      "GET"={
 *          "normalization_context"={"groups"={"promotion:read"}},
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *      },
 *      "get_promo_principal"={
 *          "method"="GET",
 *          "path"="/promotions/principal",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "normalization_context"={"groups"={"promotion:read_all"}}
 *      },
 *      "get_apprenants_attente"={
 *          "method"="GET",
 *          "path"="/promotions/apprenants/attente",
 *          "route_name"="show_apprenants_attente"
 *      },
 *  },
 *  itemOperations={
 *      "get"={
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *      }, 
 *      "get_promo_principal_id"={
 *          "method"="GET",
 *          "path"="/promotions/{id}/principal",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "normalization_context"={"groups"={"promotion:read_all"}}
 *      },
 *       "get_promo_id_referentiel"={
 *          "method"="GET",
 *          "path"="/promotions/{id}/referentiels",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "normalization_context"={"groups"={"promotion:read_all_ref"}}
 *      },
 *      "get_promo_id_formateurs"={
 *          "method"="GET",
 *          "path"="/promotions/{id}/formateurs",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "normalization_context"={"groups"={"promotion:read_formateur"}}
 *      },    
 *      "get_apprenants_id_attente"={
 *         "method"="GET",
 *         "path"="/promotions/{id}/apprenants/attente",
 *         "route_name"="show_apprenants_id_attente",
 *         "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *      },
 *      "get_promo_id_groupes_id_apprenants"={
 *          "method"="GET",
 *          "path"="/promotions/{id_promo}/groupes/{id_groupe}/apprenants",
 *          "requirements"={"id_promo"="\d+"},
 *          "route_name"="show_promo_id_groupes_id_apprenants",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 *      "put_promotion_referentiles" = {
 *          "method"="PUT",
 *          "path"="/promotion/{id}/referentiels",
 *          "route_name"="edit_promotion_referentiles"
 *      },
 *      "put_promotion_formateurs" = {
 *          "method"="PUT",
 *          "path"="/promotion/{id}/formateurs",
 *          "route_name"="edit_promotion_formateurs"
 *      },
 *      "put_promotion_apprenants" = {
 *          "method"="PUT",
 *          "path"="/promotion/{id}/apprenants",
 *          "route_name"="edit_promotion_apprenants"
 *      }
 *  }
 * )
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 * @UniqueEntity(
 *  fields={"titre"},
 *  message="Ce titre existe déjà"
 * )
 */
class Promotion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"promotion:read","promotion:read_all","promotion:read_all_ref","promotion:read_formateur","promo_groupe_apprenants:read","groupe:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promotion:write","promotion:read","promotion:read_all","promotion:read_all_ref","promotion:read_formateur","promo_groupe_apprenants:read","groupe:read","groupe_write"})
     * @Assert\NotBlank(message="Le titre est requis.")
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promotion:write","promotion:read","promotion:read_all","promotion:read_all_ref","promotion:read_formateur","promo_groupe_apprenants:read","groupe:read"})
     * @Assert\NotBlank(message="La description est requise.")
     * 
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promotion:write","promotion:read","promotion:read_all","promotion:read_all_ref","promotion:read_formateur","promo_groupe_apprenants:read"})
     * @Assert\NotBlank(message="Le lieu est requis.")
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promotion:write","promotion:read","promotion:read_all","promotion:read_all_ref","promotion:read_formateur","promo_groupe_apprenants:read"})
     * @Assert\NotBlank(message="Le reference est requis.")
     */
    private $referenceAgate;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promotion:write","promotion:read","promotion:read_all","promotion:read_all_ref","promotion:read_formateur","promo_groupe_apprenants:read"})
     * @Assert\NotBlank(message="La date de debut est requise.")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promotion:write","promotion:read","promotion:read_all","promotion:read_all_ref","promotion:read_formateur","promo_groupe_apprenants:read"})
     * @Assert\NotBlank(message="Le date de fin est requise.")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promotion:write","promotion:read","promotion:read_all","promotion:read_all_ref","promotion:read_formateur","promo_groupe_apprenants:read"})
     * @Assert\NotBlank(message="La langue est requise.")
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promotion:write","promotion:read","promotion:read_all","promotion:read_all_ref","promotion:read_formateur","promo_groupe_apprenants:read"})
     * @Assert\NotBlank(message="La fabrique est requise.")
     */
    private $fabrique;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="promotion",cascade={"persist"})
     */
    private $apprenants;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="promotions")
     * @Groups({"promotion:read","promotion:read_all","promotion:read_formateur"})
     */
    private $formateurs;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, inversedBy="promotions")
     * @Groups({"promotion:read_all_ref","promotion:read_formateur","promo_groupe_apprenants:read","groupe:read"})
     */
    private $referentiels;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promotion",cascade={"persist"})
     * @Groups({"promotion:read","promotion:read_all","promotion:read_formateur"})
     */
    private $groupes;

    /**
     * @ORM\OneToMany(targetEntity=BriefMaPromo::class, mappedBy="promotion")
     */
    private $briefMaPromos;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->briefMaPromos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getReferenceAgate(): ?string
    {
        return $this->referenceAgate;
    }

    public function setReferenceAgate(string $referenceAgate): self
    {
        $this->referenceAgate = $referenceAgate;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getImage()
    {
        return $this->image!=null?stream_get_contents($this->image):null;
    }

    public function setImage($image): self
    {
        $this->image = base64_encode($image);

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

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
            $apprenant->setPromotion($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getPromotion() === $this) {
                $apprenant->setPromotion(null);
            }
        }

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
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        $this->referentiels->removeElement($referentiel);

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
            $groupe->setPromotion($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getPromotion() === $this) {
                $groupe->setPromotion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BriefMaPromo[]
     */
    public function getBriefMaPromos(): Collection
    {
        return $this->briefMaPromos;
    }

    public function addBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if (!$this->briefMaPromos->contains($briefMaPromo)) {
            $this->briefMaPromos[] = $briefMaPromo;
            $briefMaPromo->setPromotion($this);
        }

        return $this;
    }

    public function removeBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if ($this->briefMaPromos->removeElement($briefMaPromo)) {
            // set the owning side to null (unless already changed)
            if ($briefMaPromo->getPromotion() === $this) {
                $briefMaPromo->setPromotion(null);
            }
        }

        return $this;
    }
}
