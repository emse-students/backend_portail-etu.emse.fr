<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get"={"normalization_context"={"groups"={"get_full_asso"}}},
 *         "post"={
 *              "normalization_context"={"groups"={"get_full_asso"}},
 *              "denormalization_context"={"groups"={"position_post"}},
 *              "access_control"="is_granted('ROLE_R0_A1') or ('ROLE_R2_A'~object.getAssociation().getId() in roles)"
 *          }
 *     },
 *     itemOperations={
 *          "get"={"normalization_context"={"groups"={"get_full_asso"}}},
 *          "delete"={"access_control"="is_granted('ROLE_R0_A1') or ('ROLE_R2_A'~object.getAssociation().getId() in roles)"}
 *     },
 *     attributes={"pagination_enabled"=false}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\PositionRepository")
 */
class Position
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get_full_asso"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="positions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_full_asso", "position_post"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Association", inversedBy="positions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"position_post"})
     */
    private $association;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="positions", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_full_asso", "position_post"})
     */
    private $role;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): self
    {
        $this->association = $association;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }
}
