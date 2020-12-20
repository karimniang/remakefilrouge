<?php

namespace App\Entity;


use App\Filter\UserFilter;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;



/**
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "admin" = "Admin", "apprenant" = "Apprenant", "formateur" = "Formateur", "cm" = "CM"})
 * @ApiResource(
 *  routePrefix="/admin",
 *  attributes={"security"="is_granted('ROLE_ADMIN')"},
 *  collectionOperations={
 *      "get",
 *      "get_user_connected"={
 *          "method"="GET",
 *          "path"="/user/connected",
 *          "route_name"="get_user"
 *      }
 *  },
 *  itemOperations={
 *      "GET"={
 *          "normalization_context"={"groups"={"profil:read"}}
 *      },
 *      "DELETE"
 *  }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"deleted"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profil:read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Le username est obligatoire.")
     * @Groups({"profil:read"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Groups({"profil:read"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le password est obligatoire.")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le prenom est obligatoire.")
     * @Groups({"profil:read","user:write","promotion:read","promotion:read_all","promotion:read_formateur","promo_groupe_apprenants:read","groupe:read","apprenant_groupe:read"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom est obligatoire.")
     * @Groups({"profil:read","user:write","promotion:read","promotion:read_all","promotion:read_formateur","promo_groupe_apprenants:read","groupe:read","apprenant_groupe:read"})
     */
    private $lastname;

    /**
     * @ORM\ManyToOne(targetEntity=UserProfil::class, inversedBy="users")
     * @Assert\NotBlank(message="Le profil est obligatoire.")
     * @Groups({"profil:read"})
     */
    private $profil;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:write","profil:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"profil:read"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"user:write","profil:read"})
     */
    private $telephone;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_' . strtoupper($this->profil->getLibelle());

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getProfil(): ?UserProfil
    {
        return $this->profil;
    }

    public function setProfil(?UserProfil $profil): self
    {
        $this->profil = $profil;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar!=null?stream_get_contents($this->avatar):null;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = base64_encode($avatar);

        return $this;
    }

    public function sendEmail($mailer,$password){
       
        $msg = (new \Swift_Message('Sonatel Academy'))
        ->setFrom('dioufbadaraalioune7@gmail.com')
        ->setTo($this->email)
        ->setBody("Bonjour votre password est : " . $password . " Et votre username " . $this->username);
        $mailer->send($msg);
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(?int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
}
