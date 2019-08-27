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
 *         "get",
 *         "post"={
 *               "access_control"="is_granted('ROLE_R0_A1')"
 *          }
 *     },
 *     itemOperations={
 *          "get",
 *          "put"={
 *               "access_control"="is_granted('ROLE_R0_A1')"
 *          },
 *          "delete"={
 *               "access_control"="is_granted('ROLE_R0_A1')"
 *          }
 *     },
 *     normalizationContext={"groups"={"payment_means"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\PaymentMeansRepository")
 */
class PaymentMeans
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"event_get", "get_booking", "get_event_bookings", "events_get", "payment_means"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event_get", "get_booking", "get_event_bookings", "events_get", "payment_means"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Event", mappedBy="paymentMeans")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="paymentMeans")
     */
    private $bookings;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->bookings = new ArrayCollection();
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
            $event->addPaymentMean($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            $event->removePaymentMean($this);
        }

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
            $booking->setPaymentMeans($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getPaymentMeans() === $this) {
                $booking->setPaymentMeans(null);
            }
        }

        return $this;
    }
}
