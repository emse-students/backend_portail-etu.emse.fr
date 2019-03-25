<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;



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
 *          "delete"={
 *               "access_control"="is_granted('ROLE_R0_A1')"
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\OperationRepository")
 * @HasLifecycleCallbacks
 */
class Operation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get_booking", "user_info"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="operations")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post_booking", "put_booking"})
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Booking", inversedBy="operation")
     */
    private $booking;

    /**
     * @ORM\Column(type="float")
     * @Groups({"post_booking", "get_booking", "put_booking", "user_info"})
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post_booking", "put_booking", "user_info"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post_booking", "put_booking", "user_info"})
     */
    private $reason;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @Groups({"user_info"})
     */
    private $createdAt;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }


    /**
     * @ORM\PreFlush
     */
    public function addOperation()
    {
        $this->getUser()->setBalance($this->getUser()->getBalance()+$this->getAmount());
    }


    /**
     * @ORM\PreRemove
     */
    public function removeOperation()
    {
        $this->getUser()->setBalance($this->getUser()->getBalance()-$this->getAmount());
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

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(?Booking $booking): self
    {
        $this->booking = $booking;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
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


}
