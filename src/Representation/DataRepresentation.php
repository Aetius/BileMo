<?php


namespace App\Representation;


use App\Entity\User;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\VndErrorRepresentation;
use Knp\Component\Pager\Pagination\PaginationInterface;

class DataRepresentation
{

    private $representation;

    private $meta;

/*    public function create(PaginationInterface $data)
    {

        $this->setMeta('limit', $data->getItemNumberPerPage());
        $this->setMeta('current_page', $data->getCurrentPageNumber());
        $this->setMeta('total_items', $data->getTotalItemCount());

        $this->representation['data'] = $data->getItems();
        $this->representation['meta'] = $this->meta;
        return $this->representation;
    }

    protected function setMeta($name, $value)
    {
        if (isset($this->meta[$name]))
        {
            throw new \LogicException(sprintf('This meta already exists. '));
        }
        $this->meta[$name]=$value;
    }*/


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