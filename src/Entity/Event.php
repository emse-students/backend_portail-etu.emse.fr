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
 *         "get"={"normalization_context"={"groups"={"events_get"}}},
 *         "post"={
 *              "normalization_context"={"groups"={"event_get"}},
 *              "denormalization_context"={"groups"={"event_post"}}
 *          }
 *     },
 *     itemOperations={
 *          "get"={"normalization_context"={"groups"={"event_get"}}},
 *          "delete"={"access_control"="is_granted('ROLE_R8_A1')"},
 *          "put"={
 *              "normalization_context"={"groups"={"event_get"}},
 *              "denormalization_context"={"groups"={"event_post"}}
 *          },
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"event_get", "events_get", "get_full_asso"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event_post", "event_get", "events_get", "get_full_asso"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"event_post", "event_get", "events_get", "get_full_asso"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"event_post", "event_get", "events_get", "get_full_asso"})
     */
    private $date;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"event_post", "event_get", "events_get", "get_full_asso"})
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"event_post", "event_get", "events_get", "get_full_asso"})
     */
    private $place;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PaymentMeans", inversedBy="events")
     * @Groups({"event_post", "event_get"})
     */
    private $paymentMeans;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"event_post", "event_get", "events_get", "get_full_asso"})
     */
    private $shotgunListLength;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"event_post", "event_get"})
     */
    private $shotgunWaitingList;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"event_post", "event_get", "events_get", "get_full_asso"})
     */
    private $shotgunStartingDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"event_post", "event_get", "events_get", "get_full_asso"})
     */
    private $closingDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Association", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"event_post", "event_get", "events_get"})
     */
    private $association;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"event_post", "event_get"})
     */
    private $duration;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FormInput", mappedBy="event", orphanRemoval=true, cascade={"persist"})
     * @Groups({"event_post", "event_get"})
     */
    private $formInputs;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event_post", "event_get", "events_get", "get_full_asso"})
     */
    private $status;

    public function __construct()
    {
        $this->paymentMeans = new ArrayCollection();
        $this->formInputs = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
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

    public function getShotgunStartingDate(): ?\DateTimeInterface
    {
        return $this->shotgunStartingDate;
    }

    public function setShotgunStartingDate(?\DateTimeInterface $shotgunStartingDate): self
    {
        $this->shotgunStartingDate = $shotgunStartingDate;

        return $this;
    }

    public function getClosingDate(): ?\DateTimeInterface
    {
        return $this->closingDate;
    }

    public function setClosingDate(?\DateTimeInterface $closingDate): self
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
}
