<?php

namespace App\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Repository\BookingRepository;
use App\Repository\UserRepository;
use GuzzleHttp\Client;
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
        $this->logger->info($request->getContent());
        $data = json_decode($request->getContent(), true);
        $oldBooking = $this->repository->find($data['id']);
        $em = $this->doctrine->getManager();
        if (isset($data['operation']) and !array_key_exists('id',$data['operation'])) {
//            $this->logger->info('OldBookingId = '.$oldBooking->getId());
//            $this->logger->info('OldBookingOperation = '.$oldBooking->getOperation()->getId());
            $matches = [];
            preg_match('/([0-9]+)/', $data['operation']['paymentMeans'], $matches);
            if ($matches[0] == '1') {
                $oldOperation = $oldBooking->getOperation();
                $em = $this->doctrine->getManager();
                if (!is_null($oldOperation)) {
//                $this->logger->info('OldOperationId = '.$oldOperation->getId());
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
        } elseif (array_key_exists('operation',$data) and is_null($data['operation'])) {
//            $this->logger->info('data operation= '.$data['operation']);
            $oldOperation = $oldBooking->getOperation();
            $em = $this->doctrine->getManager();
            if (!is_null($oldOperation)) {
                $em->remove($oldOperation);
            }
        }

        $operationId = $oldBooking->getCercleOperationId();
        $amount = $oldBooking->getCercleOperationAmount();

        $client = new Client([
            'base_uri' => $_ENV['CERCLE_API_URL'],
        ]);

        $headers = [
            'LOGIN' => $_ENV['CERCLE_API_LOGIN'],
            'PWD' => $_ENV['CERCLE_API_PWD'],
        ];

        if (!is_null($operationId) and array_key_exists('cercleOperationAmount',$data) and ( $data['cercleOperationAmount'] != $amount or is_null($data['cercleOperationAmount']))){
            $body = json_encode(["id"=>$operationId]);

            $client->delete('delete_transaction.php', ['headers' => $headers, 'body' => $body]);
            $oldBooking->setCercleOperationId(null);
            $em->flush();
        }
        if(isset($data['cercleOperationAmount']) and $data['cercleOperationAmount'] != $amount) {
            $body=[];
            $body["amount"]=$data['cercleOperationAmount'];
            $body["eventName"]=$oldBooking->getEvent()->getName();
            $body["login"]=$oldBooking->getUser()->getLogin();
            $body = json_encode($body);

            $response = $client->get('create_transaction.php', ['headers' => $headers, 'body' => $body]);
            $body = $response->getBody();
            $data = json_decode($body, true);
            $oldBooking->setCercleOperationId($data["id"]);
            $em->flush();
        }
        return;
    }
}