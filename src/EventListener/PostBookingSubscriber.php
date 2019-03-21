<?php

namespace App\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Booking;
use App\Repository\BookingRepository;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;


final class PostBookingSubscriber implements EventSubscriberInterface
{
    private $doctrine;
    private $logger;
    private $repository;
    private $userRepository;

    public function __construct(RegistryInterface $doctrine, LoggerInterface $logger, BookingRepository $repository, UserRepository $userRepository)
    {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['postBooking', EventPriorities::PRE_WRITE],
        ];
    }

    public function postBooking(GetResponseForControllerResultEvent $event)
    {

        $request = $event->getRequest();


        if ('api_bookings_post_collection' !== $request->attributes->get('_route')) {
            return;
        }

        $booking = $event->getControllerResult();
//        $booking = new Booking();

        $this->logger->info($booking->getEvent()->getShotgunListLength());

        if (is_null($booking->getEvent()->getShotgunListLength())) {
            return;
        }
        $now = new \DateTime();
        if ($booking->getEvent()->getShotgunStartingDate() > $now) {
            throw new HttpException(400, 'La date de début de shotgun n\'est pas encore passée');
        }
        if (!$booking->getEvent()->getShotgunWaitingList() AND count($booking->getEvent()->getBookings()) >= $booking->getEvent()->getShotgunListLength()) {
            throw new HttpException(406, 'Le nombre de places maximum a été atteint');
        }
        return;
    }
}