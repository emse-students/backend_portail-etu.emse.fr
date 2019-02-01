<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\FormOutputRepository")
 */
class FormOutput
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Answer;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Option", inversedBy="formOutputs")
     */
    private $options;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FormInput", inversedBy="formOutputs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formInput;

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
        return $this->Answer;
    }

    public function setAnswer(?string $Answer): self
    {
        $this->Answer = $Answer;

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
}
