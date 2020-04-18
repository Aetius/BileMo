<?php


namespace App\Representation;


use App\Entity\Customer;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class UsersRepresentation
{
    use DataRepresentation;

    const LIMIT_USER_PER_PAGE = 2;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var Security
     */
    private $security;

    public function __construct(RequestStack $requestStack, UserRepository $repository, PaginatorInterface $paginator,
                                Security $security)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->repository = $repository;
        $this->paginator = $paginator;
        $this->security = $security;
    }

    public function showAll()
    {
        $customer = $this->security->getUser();
        /** @var Customer $customer */
        $usersQuery = $this->paginator->paginate(
            $this->repository->findAllQuery($customer),
            $this->request->query->getInt('page', 1),
            $this->request->query->getInt('limit', self::LIMIT_USER_PER_PAGE)
        );

        return $this->paginationCollection($usersQuery);
    }

}