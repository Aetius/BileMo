<?php


namespace App\Tests\Controller;


use App\DataFixtures\UsersFixtures;
use App\Entity\Customer;
use App\Tests\Repository\CustomerRepositoryTest;
use App\Tests\Repository\UserRepositoryTest;
use App\Tests\Services\Manager;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest  extends WebTestCase
{
    use Manager;
    use CustomerRepositoryTest;
   // use FixturesTrait;
    //use RecreateDatabaseTrait;

    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        //$this->loadHautelookFixtures($this->client, ["fixtures\CustomerFixturesData.yaml"]);
        //$this->loadFixtures([UsersFixtures::class]);
    }

    public function testLogin()
    {
        //dd($this->findAll($this->client));
        //$this->loadFixtures(["App/fixtures/CustomerFixturesData"]);
        $customer = $this->findLastCustomer($this->client);
        $this->client->request(
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
                'password'=>'toto'
            ])
        );
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }






}