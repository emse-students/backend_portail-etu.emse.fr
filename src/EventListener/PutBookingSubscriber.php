<?php

namespace App\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Repository\BookingRepository;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;


final class PutBookingSubscriber implements EventSubscriberInterface
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
            KernelEvents::REQUEST => ['putBooking', EventPriorities::PRE_DESERIALIZE],
        ];
    }

    public function putBooking(GetResponseEvent $event)
    {

        $request = $event->getRequest();


        if ('api_bookings_put_item' !== $request->attributes->get('_route')) {
            return;
        }
//        $this->logger->info('hola');
        //$this->logger->info(var_export($request->attributes));
//        $this->logger->info($request->getContent());
        $data = json_decode($request->getContent(), true);
        if (isset($data['operation']) and !array_key_exists('id',$data['operation'])) {
            $oldBooking = $this->repository->find($data['id']);
//            $this->logger->info('OldBookingId = '.$oldBooking->getId());
//            $this->logger->info('OldBookingOperation = '.$oldBooking->getOperation()->getId());
//            $this->logger->info('OldBookingOperation = '.$oldBooking->getOperation()->getAmount());
            $oldOperation = $oldBooking->getOperation();
            $em = $this->doctrine->getManager();
            if (!is_null($oldOperation)) {
//                $this->logger->info('OldOperationId = '.$oldOperation->getId());
                $em->remove($oldOperation);
//            $em->flush();
                $oldOperation->getUser()->setBalance($oldOperation->getUser()->getBalance()+$data['operation']['amount']);
                $em->flush();
            } else {
                //$data['operation']['user'] = 'http://portail-test-api.emse.fr/index.php/api/users/1';
                $array = explode('/', $data['operation']['user']);
//                $this->logger->info('array[2] : '.$array[2]);
                $userId = end($array);
//                $this->logger->info('userId : '.$userId);
                $user = $this->userRepository->find($userId);
                $user->setBalance($user->getBalance()+$data['operation']['amount']);
                $em->flush();
            }
        }
        return;
    }
}