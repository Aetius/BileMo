<?php


namespace App\Controller;


use App\Repository\CustomerRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{

    /**
     *@Route("test", methods={"POST"})
     */
    public function newToken(CustomerRepository $repository, JWTEncoderInterface $encoder)
    {

        //$user = $repository->findOneBy(['name'=> 'demo']);
        $token = $encoder->encode([
            'name'=> "demo",
            'exp'=> time() + 3600
        ]);
        return new JsonResponse(
            [],
            200,
            ['Authorization' => 'Bearer '.$token]);
    }

    /**
     *@Route("test2", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function test()
    {
        dd('ici1; ');
    }

}