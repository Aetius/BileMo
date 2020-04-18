<?php


namespace App\Tests\Security;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait Connexion
{
//Need App\Tests\Services\Manager;
//Need App\Tests\Repository\CustomerRepositoryTest

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @param KernelBrowser $client
     */
    public function loginRequest(KernelBrowser $client)
    {
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username'=>$this->customer->getName(),
                'password'=>'demo'
            ])
        );

    }

    /**
     * @param KernelBrowser $client
     * @return Customer
     */
    public function setAuthorisation(KernelBrowser $client, $customer = null)
    {
        $this->customer = ($customer !== null) ? $customer : $this->findLastCustomer($client);

        $this->loginRequest($this->client);
        $token = $this->deserialize($client);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token['token']));
        return $this->customer;
    }

}