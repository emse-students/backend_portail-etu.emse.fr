<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Datetime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Filters\GetEventsDateFilter;
use App\Filters\GetEventsStatusFilter;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get"={"normalization_context"={"groups"={"events_get"}}},
 *         "post"={
 *              "normalization_context"={"groups"={"event_get"}},
 *              "denormalization_context"={"groups"={"event_post"}},
 *              "access_control"="is_granted('ROLE_R0_A1') or ('ROLE_R3_A'~object.getAssociation().getId() in roles)"
 *          }
 *     },
 *     itemOperations={
 *          "get"={"normalization_context"={"groups"={"event_get"}}},
 *          "get_bookings"={
 *              "method"="GET",
 *              "path"="/events/{id}/bookings",
 *              "normalization_context"={"groups"={"get_event_bookings"}}
 *          },
 *          "delete"={"access_control"="is_granted('ROLE_R0_A1') or ('ROLE_R3_A'~object.getAssociation().getId() in roles)"},
 *          "put"={
 *              "normalization_context"={"groups"={"event_get"}},
 *              "denormalization_context"={"groups"={"event_post"}},
 *              "access_control"="is_granted('ROLE_R0_A1') or ('ROLE_R3_A'~object.getAssociation().getId() in roles)"
 *          },
 *     },
 *     attributes={"pagination_enabled"=false}
 * )
 * @ApiFilter(GetEventsDateFilter::class)
 * @ApiFilter(GetEventsStatusFilter::class)
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"event_get", "events_get", "get_full_asso", "user_info", "get_user", "get_booking"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event_post", "event_get", "get_booking", "events_get", "get_full_asso", "get_user"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"event_post", "event_get", "get_booking", "events_get", "get_full_asso"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"event_post", "event_get", "get_booking", "events_get", "get_full_asso", "get_user"})
     */
    private $date;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"event_post", "event_get", "get_booking", "events_get", "get_full_asso", "get_user"})
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"event_post", "event_get", "get_booking", "events_get", "get_full_asso"})
     */
    private $place;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PaymentMeans", inversedBy="events")
     * @Groups({"event_post", "event_get", "get_booking", "events_get"})
     */
    private $paymentMeans;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"event_post", "event_get", "get_booking", "events_get", "get_full_asso"})
     */
    private $shotgunListLength;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"event_post", "event_get", "get_booking", "events_get"})
     */
    private $shotgunWaitingList;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"event_post", "event_get", "get_booking", "events_get", "get_full_asso"})
     */
    private $shotgunStartingDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"event_post", "event_get", "get_booking", "events_get", "get_full_asso", "get_user"})
     */
    private $closingDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Association", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"event_post", "event_get", "get_booking", "events_get", "get_user"})
     */
    private $association;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"event_post", "event_get", "get_booking", "events_get"})
     */
    private $duration;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FormInput", mappedBy="event", orphanRemoval=true, cascade={"persist", "remove"})
     * @Groups({"event_post", "event_get"})
     */
    private $formInputs;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event_post", "event_get", "events_get", "get_booking", "get_full_asso"})
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="event", orphanRemoval=true)
     * @Groups({"event_post", "get_event_bookings"})
     */
    private $bookings;

    /**
     * @ORM\Column(name="event_open", type="boolean")
     * @Groups({"event_post", "event_get", "get_booking", "events_get", "get_full_asso"})
     */
    private $open;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @Groups({"event_get", "get_booking", "events_get", "get_full_asso"})
     */
    private $countBookings;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ImgObject", cascade={"persist", "remove"})
     * @Groups({"event_post", "event_get", "get_booking", "events_get", "get_full_asso"})
     */
    private $img;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"event_post", "event_get", "get_booking", "events_get", "get_full_asso"})
     */
    private $collectLink;

    /**
     * @ORM\Column(type="boolean", options={"default" : true})
     * @Groups({"event_post", "event_get", "get_booking", "events_get", "get_full_asso", "get_user"})
     */
    private $isBookable;

    public function __construct()
    {
        $this->paymentMeans = new ArrayCollection();
        $this->formInputs = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->createdAt = new DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new Datetime());
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
    public function getCountBookings()
    {
        return count($this->getBookings());
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

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): self
    {
        $this->place = $place;

        return $this;
    }

    /**
     * @return Collection|PaymentMeans[]
     */
    public function getPaymentMeans(): Collection
    {
        return $this->paymentMeans;
    }

    public function addPaymentMean(PaymentMeans $paymentMean): self
    {
        if (!$this->paymentMeans->contains($paymentMean)) {
            $this->paymentMeans[] = $paymentMean;
        }

        return $this;
    }

    public function removePaymentMean(PaymentMeans $paymentMean): self
    {
        if ($this->paymentMeans->contains($paymentMean)) {
            $this->paymentMeans->removeElement($paymentMean);
        }

        return $this;
    }
    
    public function getShotgunListLength(): ?int
    {
        return $this->shotgunListLength;
    }

    public function setShotgunListLength(?int $shotgunListLength): self
    {
        $this->shotgunListLength = $shotgunListLength;

        return $this;
    }

    public function getShotgunWaitingList(): ?bool
    {
        return $this->shotgunWaitingList;
    }

    public function setShotgunWaitingList(?bool $shotgunWaitingList): self
    {
        $this->shotgunWaitingList = $shotgunWaitingList;

        return $this;
    }

    public function getShotgunStartingDate(): ?DateTimeInterface
    {
        return $this->shotgunStartingDate;
    }

    public function setShotgunStartingDate(?DateTimeInterface $shotgunStartingDate): self
    {
        $this->shotgunStartingDate = $shotgunStartingDate;

        return $this;
    }

    public function getClosingDate(): ?DateTimeInterface
    {
        return $this->closingDate;
    }

    public function setClosingDate(?DateTimeInterface $closingDate): self
    {
        $this->closingDate = $closingDate;

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

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection|FormInput[]
     */
    public function getFormInputs(): Collection
    {
        return $this->formInputs;
    }

    public function addFormInput(FormInput $formInput): self
    {
        if (!$this->formInputs->contains($formInput)) {
            $this->formInputs[] = $formInput;
            $formInput->setEvent($this);
        }

        return $this;
    }

    public function removeFormInput(FormInput $formInput): self
    {
        if ($this->formInputs->contains($formInput)) {
            $this->formInputs->removeElement($formInput);
            // set the owning side to null (unless already changed)
            if ($formInput->getEvent() === $this) {
                $formInput->setEvent(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setEvent($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getEvent() === $this) {
                $booking->setEvent(null);
            }
        }

        return $this;
    }

    public function getOpen(): ?bool
    {
        return $this->open;
    }

    public function setOpen(bool $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getImg(): ?ImgObject
    {
        return $this->img;
    }

    public function setImg(?ImgObject $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getCollectLink(): ?string
    {
        return $this->collectLink;
    }

    public function setCollectLink(?string $collectLink): self
    {
        $this->collectLink = $collectLink;

        return $this;
    }

    public function getIsBookable(): ?bool
    {
        return $this->isBookable;
    }

    public function setIsBookable(bool $isBookable): self
    {
        $this->isBookable = $isBookable;

        return $this;
    }
}
