<?php


namespace App\Controller;


use App\DTO\Customer\CustomerDTO;
use App\Security\CustomerAuthenticator;
use JMS\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationFailureHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class SecurityController
{
    /**
     * @Route("/login", name="app_login", methods={"POST"})
     */
    public function login(CustomerAuthenticator $authenticator,  Request $request, SerializerInterface $serializer,
                          AuthenticationSuccessHandler $successHandler, AuthenticationFailureHandler $failureHandler )
    {
       $customer = $serializer->deserialize($request->getContent(), CustomerDTO::class, 'json');
        $user = $authenticator->connexion($customer);
        if ($user){
            /*$token = $encoder->encode([
                'username'=> $user->getName(),
                'exp'=> time() + self::EXPIRATION_TOKEN
            ]);*/
            return $successHandler->handleAuthenticationSuccess($user);
          /*  return new JsonResponse(
                [],
                200,
                ['Authorization' => 'Bearer '.$token]);*/
        };
       return $failureHandler->onAuthenticationFailure(
           $request,
           new CustomUserMessageAuthenticationException('The authentication has failed.'));

    }




}