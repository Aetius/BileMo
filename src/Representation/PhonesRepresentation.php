<?php


namespace App\Representation;


use App\Repository\PhoneRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PhonesRepresentation
{
    use DataRepresentation;

    const LIMIT_PHONE_PER_PAGE = 2;


    /**
     * @var Request
     */
    private $request;
    /**
     * @var PhoneRepository
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

    public function __construct(RequestStack $requestStack, PhoneRepository $repository, PaginatorInterface $paginator,
                                ContainerInterface $container)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->repository = $repository;
        $this->paginator = $paginator;
        $this->container = $container;
    }

    public function showAll()
    {

        $phonesQuery = $this->paginator->paginate(
            $this->repository->findAllQuery($this->request->query->get('keyword'), $this->request->query->get('brand')),
            $this->request->query->getInt('page', 1),
            $this->request->query->getInt('limit', self::LIMIT_PHONE_PER_PAGE)
        );
        return $this->paginationCollection($phonesQuery);
    }

}