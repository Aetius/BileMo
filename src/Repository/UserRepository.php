<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function findLast(Customer $customer)
    {
        return $this->findOneBy(["customer"=>$customer], ['id'=> 'DESC']);
    }

    public function findAllByCustomer(Customer $customer)
    {
        return $this->findBy(["customer"=>$customer], ['id'=> 'DESC']);
    }

    public function findAllByEmail(string $email){
        return $this->findBy(["email"=>$email]);
    }

    public function findAllQuery(Customer $customer)
    {
        return $this->createQueryBuilder('p')
            ->where("p.customer = :customer ")
            ->setParameter('customer', $customer)
            ->orderBy('p.id', 'DESC')
            ->getQuery();
    }
}
