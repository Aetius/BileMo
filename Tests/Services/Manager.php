<?php


namespace App\Tests\Services;


use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

Trait Manager
{


    /**
     * @param KernelBrowser $client
     * @return array
     */
    public function deserialize(KernelBrowser $client)
    {
        $kernel = $client->getKernel();
        /** @var SerializerInterface $serializer*/
       $serializer = $kernel->getContainer()
           ->get('jms_serializer');

        return $serializer->deserialize($client->getResponse()->getContent(), 'array', 'json');
    }

    public function serializeAPIKey(KernelBrowser $client)
    {
        $kernel = $client->getKernel();
        /** @var SerializerInterface $serializer*/
        $encoder = $kernel->getContainer()
            ->get('lexik_jwt_authentication.encoder');
        $api = $encoder->encode(['name'=>'demo']);
        return $api;
    }
}