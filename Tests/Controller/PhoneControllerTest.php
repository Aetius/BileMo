<?php


namespace App\Tests\Controller;


use App\DataFixtures\BrandsFixtures;
use App\DataFixtures\PhonesFixtures;
use App\Tests\Services\Manager;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhoneControllerTest extends WebTestCase
{
    use FixturesTrait;
    use Manager;

    protected $client;


    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([BrandsFixtures::class, PhonesFixtures::class]);
    }

    public function testTargetShowPhones()
    {

        $this->client->request('GET', '/api/phones');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $deserialized = $this->deserialize($this->client);
        $this->assertCount(10, $deserialized);
    }

    public function testTargetShowOnePhone()
    {
        $this->client->request('GET', '/api/phones/2');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $deserialized = $this->deserialize($this->client);
        $this->assertTrue(2 === $deserialized['id']);
    }


}