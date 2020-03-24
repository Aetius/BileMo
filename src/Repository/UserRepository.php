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


    public function findLast($customerId)
    {
        return $this->findOneBy(["customer"=>$customerId], ['id'=> 'DESC']);
    }

    public function findAllByCustomer(int $customerId)
    {
        return $this->findBy(["customer"=>$customerId]);
    }

    public function findAllQuery($custumerId)
    {
        return $this->createQueryBuilder('p')
            ->where("p.customer = $custumerId ")
            ->orderBy('p.id', 'DESC')
            ->getQuery();
    }
}
