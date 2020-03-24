<?php


namespace App\Tests\Controller;


use App\Tests\Repository\CustomerRepositoryTest;
use App\Tests\Services\Manager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest  extends WebTestCase
{
    use Manager;
    use CustomerRepositoryTest;

    /**
     *@var  KernelBrowser
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testLogin()
    {
        $customer = $this->findLastCustomer($this->client);
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username'=>$customer->getName(),
                'password'=>'demo'
            ])
        );
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $deserialized = $this->deserialize($this->client);
        $this->assertTrue(array_key_exists("token", $deserialized));
        $this->assertJson($this->client->getResponse()->getContent());
    }


    public function testLoginNOK()
    {
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username'=>"false",
                'password'=>'demo'
            ])
        );
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

}