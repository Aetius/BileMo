<?php


namespace App\Representation;

use App\Representation\DTO\EntityDto;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;


class OneEntityRepresentation
{
    use DataRepresentation;


    /**
     * @var Request
     */
    private $request;

    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(RequestStack $requestStack, PaginatorInterface $paginator,ContainerInterface $container)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->paginator = $paginator;
        $this->container = $container;
    }

    public function shwoOne(object $entity)
    {
        $represention = new EntityDto(
            $this->request->get("_route"),
            $this->request->attributes->get("_route_params"),
            $entity
        );
        return $this->paginationEntity($represention);
    }

}