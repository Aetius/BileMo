<?php


namespace App\DTO\User;


use App\Validator\UniqueEmailFromCustomer;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserDTO
 *
 * @UniqueEmailFromCustomer()
 */
class UserDTO
{

    /**
     * @Serializer\Type("string")
     * @Assert\NotNull(groups={"Create"})
     * @Assert\NotBlank(groups={"Create"})
     * @Assert\Length(min="1", max="155")
     */
    public $lastname;

    /**
     * @Serializer\Type("string")
     * @Assert\NotNull(groups={"Create"})
     * @Assert\NotBlank(groups={"Create"})
     * @Assert\Length(min="1", max="155")
     */
    public $firstname;

    /**
     * @Serializer\Type("string")
     * @Assert\NotNull(groups={"Create"})
     * @Assert\NotBlank(groups={"Create"})
     * @Assert\Email()
     */
    public $email;

    /**
     * @Serializer\Type("App\Entity\Customer")
     */
    public $customer;

}