<?php


namespace App\Tests\Controller;


use App\DataFixtures\UsersFixtures;
use App\Tests\Repository\UserRepositoryTest;
use App\Tests\Services\Manager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class CustomerControllerTest extends WebTestCase
{
    use Manager;
    use UserRepositoryTest;
    use FixturesTrait;

    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([UsersFixtures::class]);
    }



    public function testTest()
    {
        $user = $this->findLastUser($this->client);
       $this->client->request('POST', '/api/test', [
            'auth'=> [$user->getLastname()]
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }



}