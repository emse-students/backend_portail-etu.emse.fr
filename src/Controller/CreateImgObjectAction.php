<?php
// src/Controller/CreateMediaObjectAction.php

namespace App\Controller;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use App\Entity\ImgObject;
use App\Form\ImgObjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateImgObjectAction
{
    private $validator;
    private $doctrine;
    private $factory;

    public function __construct(ManagerRegistry $doctrine, FormFactoryInterface $factory, ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->factory = $factory;
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function __invoke(Request $request): ImgObject
    {
        $imgObject = new ImgObject();

        $form = $this->factory->create(ImgObjectType::class, $imgObject);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($imgObject);
            $em->flush();

            // Prevent the serialization of the file property
            $imgObject->file = null;

            return $imgObject;
        }

        // This will be handled by API Platform and returns a validation error.
        throw new ValidationException($this->validator->validate($imgObject));
    }
}