<?php


namespace App\Services;


use App\Entity\Customer;
use App\Repository\PhoneRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

class Paginator
{
    const LIMIT_PHONE_PER_PAGE = 2;
    const LIMIT_USER_PER_PAGE = 2;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function paginatePhones(ParameterBag $query, PhoneRepository $repository)
    {
        $phonesQuery = $this->paginator->paginate(
            $repository->findAllQuery($query->get('keyword'), $query->get('brand')),
            $query->getInt('page', 1),
            $query->getInt('limit', self::LIMIT_PHONE_PER_PAGE)
        );
        return $phonesQuery;
    }

    public function paginateUsers(ParameterBag $query, UserRepository $repository, Customer $customer)
    {
        $usersQuery = $this->paginator->paginate(
            $repository->findAllQuery($customer),
            $query->getInt('page', 1),
            $query->getInt('limit', self::LIMIT_USER_PER_PAGE)
        );
        return $usersQuery;
    }
}