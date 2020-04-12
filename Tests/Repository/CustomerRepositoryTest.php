<?php


namespace App\Tests\Repository;


use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait CustomerRepositoryTest
{
    /**
     * @param KernelBrowser $client
     * @return Customer|null
     */
    public function findLastCustomer(KernelBrowser $client)
    {
        $kernel = $client->getKernel();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        $customer = $entityManager
            ->getRepository(Customer::class)
            ->findLast();
        return $customer;
    }

    /**
     * @param KernelBrowser $client
     * @return Customer[]|object[]
     */
    public function findAll(KernelBrowser $client)
    {
        $kernel = $client->getKernel();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        $customer = $entityManager
            ->getRepository(Customer::class)
            ->findAll();
        return $customer;
    }

    public function findDemoCustomer(KernelBrowser $client)
    {
        $kernel = $client->getKernel();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $customer = $entityManager
            ->getRepository(Customer::class)
            ->findDemoCustomer();
        return $customer;



    }

}