<?php


namespace App\Tests\Controller;


use App\Controller\PhoneController;
use App\Tests\Repository\CustomerRepositoryTest;
use App\Tests\Security\Connexion;
use App\Tests\Services\Manager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhoneControllerTest extends WebTestCase
{
    use Manager;
    use CustomerRepositoryTest;
    use Connexion;

    /**
     *@var  KernelBrowser
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testTargetShowPhones()
    {
        $this->setAuthorisation($this->client);
        $this->client->request('GET', '/api/phones');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $deserialized = $this->deserialize($this->client);
        $this->assertCount(PhoneController::LIMIT_PHONE_PER_PAGE, $deserialized);
    }

    public function testTargetShowOnePhone()
    {
        //$this->connexion();
        $this->setAuthorisation($this->client);
        $this->client->request('GET', '/api/phones/2');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $deserialized = $this->deserialize($this->client);
        $this->assertTrue(2 === $deserialized['id']);
    }

    public function testTargetShowPhonesWithoutLogin()
    {
        $this->client->request('GET', '/api/phones');
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    /*protected function connexion()
    {
        $login = new Connexion();
        $token = $login->login($this->client);
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token['token']));
    }*/

}