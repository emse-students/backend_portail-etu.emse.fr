<?php

namespace App\Controller;

use App\Entity\User;
use GuzzleHttp\Client;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CercleApiController extends AbstractController
{
    private $logger;
    private $jwtEncoder;

    public function __construct(LoggerInterface $logger, JWTEncoderInterface $jwtEncoder)
    {
        $this->logger = $logger;
        $this->jwtEncoder = $jwtEncoder;
    }

    /**
     * @Route("/api/get-cercle-info", name="get_cerle_info")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return Response
     */
    public function getBalance(Request $request)
    {
        $client = new Client([
            'base_uri' => $_ENV['CERCLE_API_URL'],
        ]);

        $headers = [
            'LOGIN' => $_ENV['CERCLE_API_LOGIN'],
            'PWD' => $_ENV['CERCLE_API_PWD'],
        ];
        $body = $request->getContent();

        $response = $client->get('get_solde.php', ['headers' => $headers, 'body' => $body]);
        $code = $response->getStatusCode();
        $body = $response->getBody();
        $data = json_decode($body, true);
        if ($code == 200) {
            return new JsonResponse($data, 200);
        } else {
            return new Response($data, $code);
        }
    }

    /**
     * @Route("/api/get-cercle-infos", name="get_cerle_infos")
     * @param Request $request
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function getBalances(Request $request)
    {
        $client = new Client([
            'base_uri' => $_ENV['CERCLE_API_URL'],
        ]);

        $headers = [
            'LOGIN' => $_ENV['CERCLE_API_LOGIN'],
            'PWD' => $_ENV['CERCLE_API_PWD'],
        ];
        $body = $request->getContent();

        $response = $client->get('get_soldes.php', ['headers' => $headers, 'body' => $body]);
        $code = $response->getStatusCode();
        $body = $response->getBody();
        $data = json_decode($body, true);
        if ($code == 200) {
            return new JsonResponse($data, 200);
        } else {
            return new Response($data, $code);
        }
    }
}
