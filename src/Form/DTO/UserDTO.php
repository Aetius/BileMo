<?php


namespace App\Form\DTO;


use JMS\Serializer\Annotation as Serializer;

class UserDTO
{

    /**
     * @var  string
     * @Serializer\Type("string")
     */
    public $lastname;

    /**
     * @var  string
     * @Serializer\Type("string")
     */
    public $firstname;

    /**
     * @var  string
     * @Serializer\Type("string")
     */
    public $email;

}