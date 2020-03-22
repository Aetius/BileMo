<?php


namespace App\Tests\Security;


use App\Tests\FixturesDirectories\FixtureDirectory;
use App\Tests\Repository\CustomerRepositoryTest;
use App\Tests\Services\Manager;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Connexion
{

    use Manager;
    use CustomerRepositoryTest;

    //use FixturesTrait;
    //use ReloadDatabaseTrait;



    public function login(KernelBrowser $client)
    {

        $customer = $this->findLastCustomer($client);
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username'=>$customer->getName(),
                'password'=>'toto'
            ])
        );
        $token = $this->deserialize($client);
        //$client->setServerParameter('Authorization', sprintf('Bearer %s', $deserialize['token']));
        return $token;

    }

}