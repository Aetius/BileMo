<?php


namespace App\Representation;


use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use Knp\Component\Pager\Pagination\PaginationInterface;

class DataRepresentation
{

    public function showAll(PaginationInterface $data, string $route)
    {
        $paginatedCollection = new PaginatedRepresentation(
            new CollectionRepresentation( $data),
            $route,
            [],
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

}