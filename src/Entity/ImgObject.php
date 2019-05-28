<?php
// src/Entity/MediaObject.php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateImgObjectAction;
use Datetime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ApiResource(
 *     collectionOperations={
 *     "get",
 *     "post"={
 *         "method"="POST",
 *         "path"="/img_objects",
 *         "controller"=CreateImgObjectAction::class,
 *         "defaults"={"_api_receive"=false},
 *         "access_control"="is_granted('ROLE_USER')"
 *     },
 * })
 * @Vich\Uploadable
 */
class ImgObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get_full_asso", "event_get", "get_booking", "events_get"})
     */
    private $id;

    /**
     * @var File|null
     * @Assert\NotNull()
     * @Vich\UploadableField(mapping="img_object", fileNameProperty="filename")
     */
    public $file;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     * @Groups({"get_full_asso", "event_get", "get_booking", "events_get"})
     */
    public $filename;

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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|File
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param null|File $file
     */
    public function setFile(?File $file): void
    {
        $this->file = $file;
    }

    /**
     * @return null|string
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param null|string $filename
     */
    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }


}