<?php

namespace App\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Repository\BookingRepository;
use App\Repository\UserRepository;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;


final class DeleteBookingSubscriber implements EventSubscriberInterface
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
            KernelEvents::REQUEST => ['deleteBooking', EventPriorities::PRE_DESERIALIZE],
        ];
    }

    public function deleteBooking(GetResponseEvent $event)
    {

        $request = $event->getRequest();


        if ('api_bookings_delete_item' !== $request->attributes->get('_route')) {
            return;
        }
        $path = $request->getPathInfo();
        $this->logger->info($path);
        $path = explode("/", $path);
        $bookingId = end($path);
        $this->logger->info($bookingId);

        $oldBooking = $this->repository->find($bookingId);
        $operationId = $oldBooking->getCercleOperationId();

        if (!is_null($operationId)) {
            $client = new Client([
                'base_uri' => $_ENV['CERCLE_API_URL'],
            ]);

            $headers = [
                'LOGIN' => $_ENV['CERCLE_API_LOGIN'],
                'PWD' => $_ENV['CERCLE_API_PWD'],
            ];
            $body = json_encode(["id"=>$operationId]);

            $client->delete('delete_transaction.php', ['headers' => $headers, 'body' => $body]);
        }
        return;
    }
}