<?php


namespace App\Representation\DTO;


class EntityDto
{
    /**
     *@var array
     */
    public $ressources;

    /**
     *@var string
     */
    public $route;

   /**
    *@var object
    */
    public $parameters;

    public function __construct($route, $parameters, $ressources)
    {
        $this->route = $route;
        $this->parameters = $parameters;
        $this->ressources = $ressources;
    }

}