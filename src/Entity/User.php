<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity()
 * @UniqueEntity("login", message="Ce login existe déjà")
 * @ApiResource(
 *     collectionOperations={
 *         "get"={"normalization_context"={"groups"={"user_light"}}},
 *         "post"={"access_control"="is_granted('ROLE_R8_A1')"}
 *     },
 *     itemOperations={
 *          "get"={"access_control"="(is_granted('ROLE_USER') and object == user) or is_granted('ROLE_R8_A1')"},
 *          "delete"={"access_control"="is_granted('ROLE_R8_A1')"},
 *          "put"={"access_control"="(is_granted('ROLE_USER') and object == user) or is_granted('ROLE_R8_A1')"}
 *     },
 *     normalizationContext={"groups"={"read"}},
 *     attributes={"pagination_enabled"=false}
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read", "get_full_asso", "user_light"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     * @Assert\NotBlank
     * @Assert\Email()
     * @Groups({"read"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     * @Assert\NotBlank
     * @Groups({"read"})
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     * @Groups({"read", "get_full_asso", "user_light"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     * @Groups({"read", "get_full_asso", "user_light"})
     */
    private $lastname;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @Groups({"read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Groups({"read"})
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Position", mappedBy="user", orphanRemoval=true)
     */
    private $positions;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"read", "get_full_asso", "user_light"})
     */
    private $promo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read", "get_full_asso", "user_light"})
     */
    private $type;

    /**
     * @ORM\Column(type="float")
     */
    private $balance = 0.;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->positions = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \Datetime());
    }


    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return null;
    }


    public function getRoles()
    {
        $roles = array('ROLE_USER');
        foreach ($this->positions as $numPos => $position)
        {
            $rights = $position->getRole()->getRights();
            $assoId = $position->getAssociation()->getId();
            foreach ($rights as $numRight => $right) {
                $roles = array_unique(array_merge($roles, ['ROLE_R'.$right->getId().'_A'.$assoId]));
            }

        }
        return $roles;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }


    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->login;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login): void
    {
        $this->login = $login;
    }

    /**
     * @return Collection|Position[]
     */
    public function getPositions(): Collection
    {
        return $this->positions;
    }

    public function addPosition(Position $position): self
    {
        if (!$this->positions->contains($position)) {
            $this->positions[] = $position;
            $position->setUser($this);
        }

        return $this;
    }

    public function removePosition(Position $position): self
    {
        if ($this->positions->contains($position)) {
            $this->positions->removeElement($position);
            // set the owning side to null (unless already changed)
            if ($position->getUser() === $this) {
                $position->setUser(null);
            }
        }

        return $this;
    }

    public function getPromo(): ?int
    {
        return $this->promo;
    }

    public function setPromo(?int $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }
}
