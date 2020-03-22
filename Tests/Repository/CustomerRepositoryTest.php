<?php


namespace App\Tests\Repository;


use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait CustomerRepositoryTest
{
    public function findLastCustomer(KernelBrowser $client)
    {
        $kernel = $client->getKernel();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        $user = $entityManager
            ->getRepository(Customer::class)
            ->findLast();
        return $user;
    }

    public function findAll(KernelBrowser $client)
    {
        $kernel = $client->getKernel();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        $user = $entityManager
            ->getRepository(Customer::class)
            ->findAll();
        return $user;
    }

}