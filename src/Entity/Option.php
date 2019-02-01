<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\OptionRepository")
 * @ORM\Table(name="form_option")
 */
class Option
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"event_get"})
     */
    private $id;

    /**
     * @ORM\Column(name="o_value", type="string", length=255)
     * @Groups({"event_post", "event_get"})
     */
    private $value;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"event_post", "event_get"})
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FormInput", inversedBy="options")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formInput;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\FormOutput", mappedBy="options")
     */
    private $formOutputs;

    public function __construct()
    {
        $this->formOutputs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

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

    public function getFormInput(): ?FormInput
    {
        return $this->formInput;
    }

    public function setFormInput(?FormInput $formInput): self
    {
        $this->formInput = $formInput;

        return $this;
    }

    /**
     * @return Collection|FormOutput[]
     */
    public function getFormOutputs(): Collection
    {
        return $this->formOutputs;
    }

    public function addFormOutput(FormOutput $formOutput): self
    {
        if (!$this->formOutputs->contains($formOutput)) {
            $this->formOutputs[] = $formOutput;
            $formOutput->addOption($this);
        }

        return $this;
    }

    public function removeFormOutput(FormOutput $formOutput): self
    {
        if ($this->formOutputs->contains($formOutput)) {
            $this->formOutputs->removeElement($formOutput);
            $formOutput->removeOption($this);
        }

        return $this;
    }
}
