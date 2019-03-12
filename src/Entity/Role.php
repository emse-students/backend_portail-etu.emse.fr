<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 *     collectionOperations={
 *         "get"={"normalization_context"={"groups"={"get_role"}}},
 *         "post"={
 *              "normalization_context"={"groups"={"get_role"}},
 *              "access_control"="is_granted('ROLE_R0_A1')"
 *          }
 *     },
 *     itemOperations={
 *          "get"={"normalization_context"={"groups"={"get_role"}}},
 *          "delete"={"access_control"="is_granted('ROLE_R0_A1')"},
 *          "put"={
 *              "normalization_context"={"groups"={"get_role"}},
 *              "access_control"="is_granted('ROLE_R0_A1')"
 *          }
 *     },
 *     attributes={"pagination_enabled"=false}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get_role", "get_full_asso"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role", "get_full_asso", "position_post"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"get_role", "get_full_asso", "position_post"})
     */
    private $hierarchy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\UserRight")
     * @Groups({"get_role", "get_full_asso", "position_post"})
     */
    private $rights;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Position", mappedBy="role", orphanRemoval=true)
     */
    private $positions;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->rights = new ArrayCollection();
        $this->positions = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \Datetime());
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


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHierarchy(): ?int
    {
        return $this->hierarchy;
    }

    public function setHierarchy(int $hierarchy): self
    {
        $this->hierarchy = $hierarchy;

        return $this;
    }

    /**
     * @return Collection|UserRight[]
     */
    public function getRights(): Collection
    {
        return $this->rights;
    }

    public function addRight(UserRight $right): self
    {
        if (!$this->rights->contains($right)) {
            $this->rights[] = $right;
        }

        return $this;
    }

    public function removeRight(UserRight $right): self
    {
        if ($this->rights->contains($right)) {
            $this->rights->removeElement($right);
        }

        return $this;
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
            $position->setRole($this);
        }

        return $this;
    }

    public function removePosition(Position $position): self
    {
        if ($this->positions->contains($position)) {
            $this->positions->removeElement($position);
            // set the owning side to null (unless already changed)
            if ($position->getRole() === $this) {
                $position->setRole(null);
            }
        }

        return $this;
    }
}
