<?php


namespace App\Tests\Controller;


use App\DataFixtures\UsersFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use FixturesTrait;
    protected $client;


    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([UsersFixtures::class]);
    }

    /**
     * @dataProvider urlProviderUser
     */
    public function testTargetShowUsers($url)
    {
        $this->client->request('GET', $url);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function urlProviderUser()
    {
        yield ['/user/all'];
        yield ['/user/2'];
    }

}