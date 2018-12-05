<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    private $logger;
    private $jwtEncoder;

    public function __construct(LoggerInterface $logger, JWTEncoderInterface $jwtEncoder)
    {
        $this->logger = $logger;
        $this->jwtEncoder = $jwtEncoder;
    }

    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request)
    {
        if(!isset($_SESSION)) session_start();

        $responseData = array(
            "authenticated" => false,
            "bearer" => null
        );

        $credentials = array(
            'service' => $request->query->get('service'),
            'ticket' => $request->query->get('ticket'),
        );
        $this->logger->info('got credentials');
        // ini_set('session.use_cookies', 0);
        \phpCAS::setDebug(false);
        $this->logger->info('First call to phpCAS ok');
        \phpCAS::client(CAS_VERSION_2_0, 'cas.emse.fr', 443, '');
        \phpCAS::setNoCasServerValidation();

        if (isset($credentials['service'])) {
            \phpCAS::setFixedServiceURL($credentials['service']);
            \phpCAS::setNoClearTicketsFromUrl(); // removing ticket from url uses redirect which we can't do
        }

        if (\phpCAS::isAuthenticated()) {
            $this->logger->info('Checked credential with uid : '.$_SESSION['phpCAS']['attributes']['uid']);
            $jwt_data = [];
            $jwt_data['username'] = $_SESSION['phpCAS']['attributes']['uid'];
            try {
                $jwt = $this->jwtEncoder->encode($jwt_data);
                $responseData = array(
                    "authenticated" => true,
                    "bearer" => $jwt
                );
            } catch (JWTEncodeFailureException $e) {
                $this->logger->error($e->getMessage());
                session_destroy();
                return new JsonResponse($responseData, Response::HTTP_UNAUTHORIZED);
            }
        }else{
            session_destroy();
            return new JsonResponse($responseData, Response::HTTP_UNAUTHORIZED);
        }
        session_destroy();

        return new JsonResponse($responseData, Response::HTTP_ACCEPTED);
    }
}
