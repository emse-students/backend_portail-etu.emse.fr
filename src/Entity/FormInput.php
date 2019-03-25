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
 * @ORM\Entity(repositoryClass="App\Repository\FormInputRepository")
 */
class FormInput
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"event_get", "get_booking"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"event_post", "event_get", "get_booking"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event_post", "event_get", "get_booking"})
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Option", mappedBy="formInput", orphanRemoval=true, cascade={"persist"})
     * @Groups({"event_post", "event_get", "get_booking"})
     */
    private $options;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FormOutput", mappedBy="formInput", orphanRemoval=true, cascade={"persist", "remove"})
     * @Groups({"event_get"})
     */
    private $formOutputs;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="formInputs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"event_post", "event_get", "get_booking"})
     */
    private $required;

    public function __construct()
    {
        $this->options = new ArrayCollection();
        $this->formOutputs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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
            $option->setFormInput($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
            // set the owning side to null (unless already changed)
            if ($option->getFormInput() === $this) {
                $option->setFormInput(null);
            }
        }

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
            $formOutput->setFormInput($this);
        }

        return $this;
    }

    public function removeFormOutput(FormOutput $formOutput): self
    {
        if ($this->formOutputs->contains($formOutput)) {
            $this->formOutputs->removeElement($formOutput);
            // set the owning side to null (unless already changed)
            if ($formOutput->getFormInput() === $this) {
                $formOutput->setFormInput(null);
            }
        }

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }
}
