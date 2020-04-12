<?php


namespace App\Tests\Repository;


use App\Entity\Customer;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait UserRepositoryTest
{

    /**
     * @param KernelBrowser $client
     * @param Customer $customer
     * @return User|null
     */
    public function findLastUser(KernelBrowser $client, Customer $customer)
    {
        $kernel = $client->getKernel();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        $user = $entityManager
            ->getRepository(User::class)
            ->findLast($customer);
        return $user;
    }

    public function findAllUser(KernelBrowser $client, Customer $customer)
    {
        $kernel = $client->getKernel();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        $users = $entityManager
            ->getRepository(User::class)
            ->findAllByCustomer($customer);
        return $users;
    }

}