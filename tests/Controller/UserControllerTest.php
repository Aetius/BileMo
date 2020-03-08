<?php


namespace App\Tests\Controller;


use App\DataFixtures\UsersFixtures;
use App\Entity\User;
use App\Tests\Repository\UserRepositoryTest;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use FixturesTrait;
    use UserRepositoryTest;


    protected $client;


    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([UsersFixtures::class]);
    }


/////////////// Path User Tests /////////////////////////
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

////////////Delete User Tests /////////////////////
    public function testDeleteLastUser()
    {
        $user =$this->findLastUser($this->client);
        /** @var User $user */
        $url = "/user/".$user->getId();
        $this->client->request('DELETE', $url);
        $newLastUser = $this->findLastUser($this->client);
        $this->assertEquals(204,  $this->client->getResponse()->getStatusCode());
        $this->assertTrue($user !== $newLastUser);
    }

////////////Create User Tests ////////////////////////
    public function testNewUserOk()
    {
        $this->client->request(
            'POST',
            '/user/new',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "lastname": "John",
                "firstname": "Doe",
                "email": "J.Doe@gmail.com"
                }'
        );
        $this->assertEquals(201,  $this->client->getResponse()->getStatusCode());
    }

    public function testNewUserNOK()
    {
        $this->client->request(
            'POST',
            '/user/new',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "lastname": "",
                "firstname": "Doe",
                "email": "J.Doe@gmail.com"
                }'
        );
        $this->assertEquals(400,  $this->client->getResponse()->getStatusCode());
    }

//////////// Update User Tests ////////////////
    public function testUpdateUserOk()
    {
        $user =$this->findLastUser($this->client);
        /** @var User $user */
        $url = "/user/".$user->getId();
        $this->client->request(
            'PATCH',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "lastname": "",
                "firstname": "Doe",
                "email": "J.Doe@gmail.com"
                }'
        );
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateUserPathNOK()
    {
        $user =$this->findLastUser($this->client);
        /** @var User $user */
        $url = "/user/500";
        $this->client->request(
            'PATCH',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "lastname": "",
                "firstname": "Doe",
                "email": "J.Doe@gmail.com"
                }'
        );
        $this->assertEquals(404,  $this->client->getResponse()->getStatusCode());
    }


}