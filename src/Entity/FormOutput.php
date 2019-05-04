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
 *         "get"
 *     },
 *     itemOperations={
 *          "get"
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\FormOutputRepository")
 */
class FormOutput
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"event_get", "get_booking", "put_booking", "get_event_bookings"})
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"event_get", "post_booking", "get_booking", "put_booking", "get_event_bookings"})
     */
    private $answer;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Option", inversedBy="formOutputs")
     * @Groups({"event_get", "post_booking", "get_booking", "put_booking", "get_event_bookings"})
     */
    private $options;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FormInput", inversedBy="formOutputs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post_booking", "put_booking", "get_booking", "get_event_bookings"})
     */
    private $formInput;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Booking", inversedBy="formOutputs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post_booking", "event_get"})
     */
    private $booking;

    public function __construct()
    {
        $this->options = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
        }

        return $this;
    }

    public function getFormInput(): ?FormInput
    {
        return $this->formInput;
    }

    public function setFormInput(?FormInput $formInput): self
    {
        $this->formInput = $formInput;

        return $this;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(?Booking $booking): self
    {
        $this->booking = $booking;

        return $this;
    }
}
