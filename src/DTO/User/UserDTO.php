<?php


namespace App\DTO\User;


use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{

    /**
     * @Serializer\Type("string")
     * @Assert\NotNull(groups={"Create"})
     * @Assert\NotBlank(groups={"Create"})
     */
    public $lastname;

    /**
     * @Serializer\Type("string")
     * @Assert\NotNull(groups={"Create"})
     * @Assert\NotBlank(groups={"Create"})
     */
    public $firstname;

    /**
     * @Serializer\Type("string")
     * @Assert\NotNull(groups={"Create"})
     * @Assert\NotBlank(groups={"Create"})
     */
    public $email;

}