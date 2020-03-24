<?php


namespace App\Tests\Services;


use JMS\Serializer\SerializerInterface;
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

}