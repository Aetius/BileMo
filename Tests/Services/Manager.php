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

    public function loadHautelookFixtures(KernelBrowser $client, Array $data)
    {
        $kernel = $client->getKernel();
        /** @var SerializerInterface $serializer*/
        $loader = $kernel->getContainer()
            ->get('fidry_alice_data_fixtures.loader.doctrine');
        $objects = $loader->load($data);

    }

}