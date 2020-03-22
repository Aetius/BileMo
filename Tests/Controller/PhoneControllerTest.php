<?php


namespace App\Tests\Controller;


use App\DataFixtures\BrandsFixtures;
use App\DataFixtures\CustomerFixtures;
use App\DataFixtures\PhonesFixtures;
use App\Entity\User;
use App\Tests\FixturesDirectories\FixtureDirectory;
use App\Tests\Repository\CustomerRepositoryTest;
use App\Tests\Security\Connexion;
use App\Tests\Services\Manager;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhoneControllerTest extends WebTestCase
{
    //use FixturesTrait;
    use Manager;
    use CustomerRepositoryTest;
    //use RecreateDatabaseTrait;
    //use RefreshDatabaseTrait;
    //use ReloadDatabaseTrait;

    protected $client;


    protected function setUp(): void
    {

        $this->client = static::createClient();
        //$this->loadHautelookFixtures($this->client, [FixtureDirectory::COSTUMERS8FIXTURES,  FixtureDirectory::PHONESFIXTURES, FixtureDirectory::BRANDSFIXTURES]);
        $login = new Connexion();
        $token = $login->login($this->client);
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token['token']));
        //$this->loadFixtures([BrandsFixtures::class, PhonesFixtures::class]);

    }

   /* protected function loadClient()
    {
        if (!$this->client instanceof KernelBrowser){ dump($this->client);
            $this->client = static::createClient();
        }
        return $this->client;
    }

    protected function login()
    {
        $login = new Connexion();
        $token = $login->login($this->client);
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token['token']));
    }*/

    public function testTargetShowPhones()
    {
        //$this->loadClient();
        //$this->login();
        //$this->client->setServerParameter('Authorization', sprintf('Bearer %s', $token['token'])); dd($this->client);

        //$this->client = $login->login($this->client);
        $this->client->request('GET', '/api/phones');
        //dd($this->client->getResponse()->getContent());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $deserialized = $this->deserialize($this->client);
        $this->assertCount(10, $deserialized);
    }

    public function testTargetShowOnePhone()
    {
        //$this->loadClient();
        $this->client->request('GET', '/api/phones/2');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $deserialized = $this->deserialize($this->client);
        $this->assertTrue(2 === $deserialized['id']);
    }


}