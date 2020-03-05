<?php


namespace App\Tests\Controller;


use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhoneControllerTest extends WebTestCase
{
    protected $client;


    protected function setUp(): void
    {
        $this->client = static::createClient();
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