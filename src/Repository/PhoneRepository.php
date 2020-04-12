<?php

namespace App\Repository;

use App\Entity\Phone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Phone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phone[]    findAll()
 * @method Phone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phone::class);
    }


    public function findAllQuery(?string $keyword = null, ?int $brand = null)
    {
        $query = $this->createQueryBuilder('p')
            ->innerJoin('p.brand', 'b')
            ->orderBy('p.id', 'DESC');

        if ($keyword !== null){
            $query->where("b.name LIKE :keyword");
            $query->orWhere("p.name LIKE :keyword");
            $query->setParameter('keyword', "%".$keyword."%");
        }
        if ($brand !== null){
            $query->andWhere("b.id = :brand");
            $query->setParameter('brand', $brand);
        }

            return $query->getQuery();
    }



}
