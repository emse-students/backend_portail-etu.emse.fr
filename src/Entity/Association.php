<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get"={"normalization_context"={"groups"={"light"}}},
 *         "post"={
 *              "normalization_context"={"groups"={"light"}},
 *              "access_control"="is_granted('ROLE_R0_A1')"
 *          }
 *     },
 *     itemOperations={
 *          "get"={"normalization_context"={"groups"={"get_full_asso"}}},
 *          "delete"={"access_control"="is_granted('ROLE_R0_A1')"},
 *          "put"={
 *              "normalization_context"={"groups"={"get_full_asso"}},
 *              "access_control"="is_granted('ROLE_R0_A1') or ('ROLE_R1_A'~object.getId() in roles)"
 *          },
 *     },
 *     attributes={"pagination_enabled"=false}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AssociationRepository")
 */
class Association
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"light", "get_full_asso", "event_get", "get_booking", "events_get", "get_user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"light", "get_full_asso", "event_get", "get_booking", "events_get", "get_user"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"light", "get_full_asso", "event_get", "get_booking", "events_get"})
     */
    private $tag;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"get_full_asso"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_full_asso"})
     */
    private $color;

    /**
     * @var ImgObject|null
     * @ORM\OneToOne(targetEntity="App\Entity\ImgObject", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"get_full_asso"})
     */
    public $logo;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"get_full_asso"})
     */
    private $last_action_date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Position", mappedBy="association", orphanRemoval=true)
     * @Groups({"get_full_asso"})
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="association", orphanRemoval=true)
     * @Groups({"get_full_asso"})
     */
    private $events;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"light", "get_full_asso"})
     */
    private $isList;



    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->positions = new ArrayCollection();
        $this->events = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getLastActionDate(): ?\DateTimeInterface
    {
        return $this->last_action_date;
    }

    public function setLastActionDate(?\DateTimeInterface $last_action_date): self
    {
        $this->last_action_date = $last_action_date;

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
            $position->setAssociation($this);
        }

        return $this;
    }

    public function removePosition(Position $position): self
    {
        if ($this->positions->contains($position)) {
            $this->positions->removeElement($position);
            // set the owning side to null (unless already changed)
            if ($position->getAssociation() === $this) {
                $position->setAssociation(null);
            }
        }

        return $this;
    }

    /**
     * @return ImgObject|null
     */
    public function getLogo(): ?ImgObject
    {
        return $this->logo;
    }

    /**
     * @param ImgObject|null $logo
     */
    public function setLogo(?ImgObject $logo): void
    {
        $this->logo = $logo;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setAssociation($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getAssociation() === $this) {
                $event->setAssociation(null);
            }
        }

        return $this;
    }

    public function getIsList(): ?bool
    {
        return $this->isList;
    }

    public function setIsList(?bool $isList): self
    {
        $this->isList = $isList;

        return $this;
    }


}
