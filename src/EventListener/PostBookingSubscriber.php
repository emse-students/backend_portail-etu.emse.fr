<?php

namespace App\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Repository\BookingRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;


final class PostBookingSubscriber implements EventSubscriberInterface
{
    private $doctrine;
    private $logger;
    private $repository;
    private $userRepository;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger, BookingRepository $repository, UserRepository $userRepository)
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

    public function postBooking(ViewEvent $event)
    {

        $request = $event->getRequest();


        if ('api_bookings_post_collection' !== $request->attributes->get('_route')) {
            return;
        }

        $booking = $event->getControllerResult();
//        $booking = new Booking();

        $this->logger->info($booking->getEvent()->getShotgunListLength());
        // cercleOperationAmount

        if (!is_null($booking->getEvent()->getShotgunListLength())) {
            $now = new \DateTime();
            if ($booking->getEvent()->getShotgunStartingDate() > $now) {
                throw new HttpException(400, 'La date de début de shotgun n\'est pas encore passée');
            }
            if (!$booking->getEvent()->getShotgunWaitingList() AND count($booking->getEvent()->getBookings()) >= $booking->getEvent()->getShotgunListLength()) {
                throw new HttpException(406, 'Le nombre de places maximum a été atteint');
            }
        }

        if (is_null($booking->getPaymentMeans()) or $booking->getPaymentMeans()->getId() != 2) {
            return;
        }

        $client = new Client([
            'base_uri' => $_ENV['CERCLE_API_URL'],
        ]);

        $headers = [
            'LOGIN' => $_ENV['CERCLE_API_LOGIN'],
            'PWD' => $_ENV['CERCLE_API_PWD'],
        ];

        $body["amount"]=$booking->getCercleOperationAmount();
        $body["eventName"]=$booking->getEvent()->getName();
        $body["login"]=$booking->getUser()->getLogin();
        $body = json_encode($body);

        $response = $client->get('create_transaction.php', ['headers' => $headers, 'body' => $body]);
        $body = $response->getBody();
        $data = json_decode($body, true);
        $booking->setCercleOperationId($data["id"]);
        return;

    }
}