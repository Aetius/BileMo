<?php


namespace App\Representation;


use App\Entity\Customer;
use App\Repository\UserRepository;
use App\Services\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

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
     * @var Paginator
     */
    private $paginator;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(RequestStack $requestStack, UserRepository $repository, PaginatorInterface $paginator,
                                ContainerInterface $container)
    {

        $this->request = $requestStack->getCurrentRequest();
        $this->repository = $repository;
        $this->paginator = $paginator;
        $this->container = $container;
    }

    public function showAll()
    {
        $customer = $this->container->get('security.token_storage')->getToken()->getUser();
        /** @var Customer $customer */
        $usersQuery = $this->paginator->paginate(
            $this->repository->findAllQuery($customer),
            $this->request->query->getInt('page', 1),
            $this->request->query->getInt('limit', self::LIMIT_USER_PER_PAGE)
        );

        return $this->paginationCollection($usersQuery, $this->request->get("_route"));
    }

}