<?php


namespace App\Tests\Controller;


use App\DataFixtures\BrandsFixtures;
use App\DataFixtures\PhonesFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhoneControllerTest extends WebTestCase
{
    use FixturesTrait;
    protected $client;


    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([BrandsFixtures::class, PhonesFixtures::class]);
    }

    /**
     * @dataProvider urlProviderPhone
     */
    public function testTargetPhonePage($url)
    {
        $this->client->request('GET', $url);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function urlProviderPhone()
    {
        yield ['/show'];
        yield ['/show/2'];
    }

}