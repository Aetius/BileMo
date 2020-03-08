<?php


namespace App\Tests\Repository;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait UserRepositoryTest
{

    public function findLastUser(KernelBrowser $client)
    {
        $kernel = $client->getKernel();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        $user = $entityManager
            ->getRepository(User::class)
            ->findLast();
        return $user;
    }

}