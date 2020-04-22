<?php


namespace App\Tests\Controller;


use App\Controller\UserController;
use App\Tests\Config\Config;
use App\Tests\Repository\CustomerRepositoryTest;
use App\Tests\Repository\UserRepositoryTest;
use App\Tests\Security\Connexion;
use App\Tests\Services\Manager;
use App\Tests\Services\UserService;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use UserRepositoryTest;
    use CustomerRepositoryTest;
    use Manager;
    use Connexion;
    use UserService;

    /**
     *@var  KernelBrowser
     */
    protected $client;


    protected function setUp(): void
    {
        $this->client = static::createClient();
    }


/////////////// Show User Tests /////////////////////////

    public function testTargetShowUsers()
    {
        $customer = $this->setAuthorisation($this->client);
        $this->client->request('GET', Config::VERSION."/users");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $deserialized = $this->deserialize($this->client);
        $users = $this->findAllUser($this->client, $customer);
        $nbUsers = $this->defineNumberOfUsersByCustomer($users);
        $this->assertCount($nbUsers, $deserialized["_embedded"]["items"]);
    }

    public function testTargetShowOneUser()
    {
        $customer = $this->setAuthorisation($this->client);
        $userId = $this->findLastUser($this->client, $customer)->getId();

        $this->client->request('GET', Config::VERSION."/users/$userId");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $deserialized = $this->deserialize($this->client);
        $this->assertTrue($userId === $deserialized['id']);
    }

    public function testTargetShowOneUserWithoutLogin()
    {
        $this->client->request('GET', Config::VERSION."/users/2");
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

////////////Delete User Tests /////////////////////
    public function testDeleteLastUser()
    {
        $customer = $this->setAuthorisation($this->client);
        $user =$this->findLastUser($this->client, $customer);

        $url = Config::VERSION."/users/".$user->getId();
        $this->client->request('DELETE', $url);
        $newLastUser = $this->findLastUser($this->client, $customer);
        $this->assertEquals(204,  $this->client->getResponse()->getStatusCode());
        $this->assertTrue($user !== $newLastUser);
    }

    public function testDeleteLastUserWithoutLogin()
    {
        $customer = $this->findLastCustomer($this->client);
        $user =$this->findLastUser($this->client, $customer);

        $url = Config::VERSION."/users/".$user->getId();
        $this->client->request('DELETE', $url);
        $this->assertEquals(401,  $this->client->getResponse()->getStatusCode());
    }



////////////Create User Tests ////////////////////////
    public function testNewUserOk()
    {
        $customer = $this->setAuthorisation($this->client);
        $content = '{
                "lastname": "Doe",
                "firstname": "John",
                "email": "J.Doe@test.com"
                }';

        $this->client->request(
            'POST',
            Config::VERSION.'/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $content
        );

        $this->assertEquals(201,  $this->client->getResponse()->getStatusCode());
        $deserialized = $this->deserialize($this->client);
        $this->assertTrue(12 === $deserialized['id']);
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testNewUserNOK()
    {
        $this->setAuthorisation($this->client);
        $this->client->request(
            'POST',
            Config::VERSION.'/users',
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

    public function testNewUserNokEmailAlreadyUsed()
    {
        $customer = $this->findDemoCustomer($this->client);
        $this->setAuthorisation($this->client, $customer);
        $this->client->request(
            'POST',
            Config::VERSION.'/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "lastname": "Doe",
                "firstname": "John",
                "email": "John.Doe@yahoo.fr"
                }'
        );
        $this->assertEquals(400,  $this->client->getResponse()->getStatusCode());

    }

    public function testNewUserWithoutLogin()
    {
        $content = '{
                "lastname": "John",
                "firstname": "Doe",
                "email": "J.Doe@gmail.com"
                }';

        $this->client->request(
            'POST',
            Config::VERSION.'/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $content
        );
        $this->assertEquals(401,  $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

//////////// Update User Tests ////////////////
    public function testUpdateUserOk()
    {
        $customer = $this->setAuthorisation($this->client);
        $user =$this->findLastUser($this->client, $customer);

        $url = Config::VERSION."/users/".$user->getId();
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
        $customer = $this->setAuthorisation($this->client);
        $user =$this->findLastUser($this->client, $customer);

        $url = Config::VERSION."/users/".$user->getId();
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
        $this->setAuthorisation($this->client);

        $url = Config::VERSION."/users/500";
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "lastname": "John",
                "firstname": "Doe",
                "email": "J.Doe@gmail.com"
                }'
        );
        $this->assertEquals(404,  $this->client->getResponse()->getStatusCode());
    }
    public function testUpdateUserContentNOK()
    {
        $customer = $this->setAuthorisation($this->client);
        $userId =$this->findLastUser($this->client, $customer)->getId();

        $url = Config::VERSION."/users/$userId";
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );
        $this->assertEquals(500,  $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }


    public function testUpdateUserWithoutLogin()
    {
        $customer = $this->findLastCustomer($this->client);
        $user =$this->findLastUser($this->client, $customer);

        $url = Config::VERSION."/users/".$user->getId();
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
        $this->assertEquals(401,  $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

}