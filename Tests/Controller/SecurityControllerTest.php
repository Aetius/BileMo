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
    use FixturesTrait;
    use ReloadDatabaseTrait;

    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        //$this->loadFixtures([UsersFixtures::class]);
    }


    public function testLogin()
    {

        $customer = $this->findLastCustomer($this->client)->getName();

        /**@var Customer $customer*/
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username'=>"Stark Industries.10",
                'password'=>'toto'
            ])
        );
        dd($this->client->getResponse()->getContent());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $deserialized = $this->deserialize($this->client);
        $this->assertTrue(11 === $deserialized['id']);
        $this->assertJson($this->client->getResponse()->getContent());

    }




}