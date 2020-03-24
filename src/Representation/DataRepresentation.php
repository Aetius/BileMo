<?php


namespace App\Representation;


use Knp\Component\Pager\Pagination\PaginationInterface;

class DataRepresentation
{

    private $representation;

    private $meta;

    public function create(PaginationInterface $data)
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
    }

}