<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
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
 *         "get"={"access_control"="is_granted('ROLE_ADMIN')"},
 *         "post"={"access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"access_control"="(is_granted('ROLE_USER') and object == user) or is_granted('ROLE_ADMIN')"},
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN')"},
 *          "put"={"access_control"="(is_granted('ROLE_USER') and object == user) or is_granted('ROLE_ADMIN')"}
 *     },
 *     normalizationContext={"groups"={"read"}}
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read"})
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
     * @Groups({"read"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     * @Groups({"read"})
     */
    private $lastname;


    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"read"})
     */
    private $role;


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


    public function __construct($login, $email, $lastname = "", $firstname = "", $role = null)
    {
        $this->login = $login;
        $this->email = $email;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->role = $role;
        $this->createdAt = new \DateTime();
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

        if ($this->role != Null)
        {
            return array_unique(array_merge(['ROLE_USER'], [$this->role]));
        }else{
            return array('ROLE_USER');
        }

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
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
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
}
