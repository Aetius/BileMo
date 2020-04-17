<?php


namespace App\Representation;


use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\RouteAwareRepresentation;
use Knp\Component\Pager\Pagination\PaginationInterface;

trait DataRepresentation
{

    public function paginationCollection(PaginationInterface $data)
    {
        $paginatedCollection = new PaginatedRepresentation(
            new CollectionRepresentation( $data),
            $data->getRoute(),
            $data->getParams(),
            $data->getCurrentPageNumber(),
            $data->getItemNumberPerPage(),
            $data->getTotalItemCount(),
            'page',
            'limit',
            true,
            null
        );
        return $paginatedCollection;
    }

    public function paginationEntity($data)
    {
        $paginatedCollection = new RouteAwareRepresentation(
            $data->ressources,
            $data->route,
            $data->parameters,
            true
        );

        return $paginatedCollection;
    }

}