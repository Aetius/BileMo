<?php

namespace App\Security;

use App\Entity\Customer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JWTTokenAuthenticator extends AbstractGuardAuthenticator
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var JWTEncoderInterface
     */
    private $JWTEncoder;

    public function __construct(EntityManagerInterface $entityManager, JWTEncoderInterface $JWTEncoder)
    {
        $this->entityManager = $entityManager;
        $this->JWTEncoder = $JWTEncoder;
    }

    public function supports(Request $request)
    {
        return $request->headers->has("Authorization");
    }

    public function getCredentials(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );
        $token = $extractor->extract($request);
        if (!$token){
            throw new CustomUserMessageAuthenticationException(
                "Authentification failed"
            );
        }
        return $token;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $data = $this->JWTEncoder->decode($credentials);

        if ($data === false) {
            // The token header was empty, authentication fails with 401
            return null;
            //todo : lancer une exception
            // ex : throw new CustomUserMessageAuthenticationException('Invalid Token');
        }
        /*
         * try {
        $data = $this->jwtEncoder->decode($credentials);
    } catch (JWTDecodeFailureException $e) {
        // if you want to, use can use $e->getReason() to find out which of the 3 possible things went wrong
        // and tweak the message accordingly
        // https://github.com/lexik/LexikJWTAuthenticationBundle/blob/05e15967f4dab94c8a75b275692d928a2fbf6d18/Exception/JWTDecodeFailureException.php

        throw new CustomUserMessageAuthenticationException('Invalid Token');
    }
        */
        $name = $data['name'];
            return $this->entityManager->getRepository(Customer::class)
                ->findOneBy(['name' => $name])
                ;
        //TODO : changer le findByOne => je ne sais pas encore si je cherche à créer un token, ou si je m'appuie sur la clé JWS, avec juste le name
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        //todo : à modifier si utilisation d'un password.
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
        // you may ant to customize or obfuscate the message first
        'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
    ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            // you might translate this message
            'message' => 'Authentication Required'
        ];
        //todo : traduction à faire.

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
