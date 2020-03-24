<?php


namespace App\DTO\Customer;


use JMS\Serializer\Annotation as Serializer;

class CustomerDTO
{

    /**
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * @Serializer\Type("string")
     */
    public $password;

    /**
     * @Serializer\Type("string")
     */
    public $email;
}