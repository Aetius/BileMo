<?php


namespace App\Tests\Controller;


use App\DataFixtures\UsersFixtures;
use App\Entity\User;
use App\Tests\Repository\UserRepositoryTest;
use App\Tests\Services\Manager;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use FixturesTrait;
    use UserRepositoryTest;
    use Manager;


    protected $client;


    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([UsersFixtures::class]);
    }


/////////////// Path User Tests /////////////////////////

    public function testTargetShowUsers()
    {
        $this->client->request('GET', "/users");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $deserialized = $this->deserialize($this->client);
        $this->assertCount(10, $deserialized);
    }

    public function testTargetShowOneUser()
    {
        $this->client->request('GET', "/users/2");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $deserialized = $this->deserialize($this->client);
        $this->assertTrue(2 === $deserialized['id']);
    }



////////////Delete User Tests /////////////////////
    public function testDeleteLastUser()
    {
        $user =$this->findLastUser($this->client);
        /** @var User $user */
        $url = "/users/".$user->getId();
        $this->client->request('DELETE', $url);
        $newLastUser = $this->findLastUser($this->client);
        $this->assertEquals(204,  $this->client->getResponse()->getStatusCode());
        $this->assertTrue($user !== $newLastUser);
    }

////////////Create User Tests ////////////////////////
    public function testNewUserOk()
    {
        $content = '{
                "lastname": "John",
                "firstname": "Doe",
                "email": "J.Doe@gmail.com"
                }';

        $this->client->request(
            'POST',
            '/users/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $content
        );
        $this->assertEquals(201,  $this->client->getResponse()->getStatusCode());
        $deserialized = $this->deserialize($this->client);
        $this->assertTrue(11 === $deserialized['id']);
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testNewUserNOK()
    {
        $this->client->request(
            'POST',
            '/users/create',
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
        $url = "/users/".$user->getId();
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "lastname": "Bruno",
                "firstname": "Doe",
                "email": "Bruno.Doe@gmail.com"
                }'
        );
        $this->assertEquals(200,  $this->client->getResponse()->getStatusCode());
        $this->assertContains("Bruno.Doe@gmail.com", $this->client->getResponse()->getContent());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testUpdateUserNOK()
    {
        $user =$this->findLastUser($this->client);
        /** @var User $user */
        $url = "/users/".$user->getId();
        $this->client->request(
            'PUT',
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
        $this->assertEquals(400,  $this->client->getResponse()->getStatusCode());
    }



    public function testUpdateUserPathNOK()
    {
        $user =$this->findLastUser($this->client);
        /** @var User $user */
        $url = "/users/500";
        $this->client->request(
            'PUT',
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