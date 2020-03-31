<?php


namespace App\DTO\User;


use App\Validator\UniqueEmail;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{

    /**
     * @Serializer\Type("string")
     * @Assert\NotNull(groups={"Create"})
     * @Assert\NotBlank(groups={"Create"})
     * @Assert\Length(min="2", max="20")
     */
    public $lastname;

    /**
     * @Serializer\Type("string")
     * @Assert\NotNull(groups={"Create"})
     * @Assert\NotBlank(groups={"Create"})
     * @Assert\Length(min="2", max="20")
     */
    public $firstname;

    /**
     * @Serializer\Type("string")
     * @Assert\NotNull(groups={"Create"})
     * @Assert\NotBlank(groups={"Create"})
     * @Assert\Email()
     * @UniqueEmail()
     */
    public $email;

    /**
     * @Serializer\Type("App\Entity\Customer")
     */
    public $customer;

}