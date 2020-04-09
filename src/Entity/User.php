<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "user_show_one",
 *          parameters = { "id" = "expr(object.getId())" }),
 *     embedded="expr(object.getCustomer())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getCustomer() == null)")
 *      )
 * )
 *
 * @Hateoas\Relation(
 *      "showAll",
 *      href = @Hateoas\Route(
 *          "user_show_all",
 *          absolute = true
 *      )
 * )
 *
 *  @Hateoas\Relation(
 *      "create",
 *      href = @Hateoas\Route(
 *          "user_create",
 *          absolute = true
 *      )
 * )
 *
 *  @Hateoas\Relation(
 *      "update",
 *      href = @Hateoas\Route(
 *          "user_update",
 *          parameters = {"id" = "expr(object.getId())"},
 *          absolute = true
 *      )
 * )
 *
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "user_delete",
 *          parameters = {"id" = "expr(object.getId())"},
 *          absolute = true
 *      )
 * )
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Serializer\Groups({"list", "detail"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Groups({"list", "detail"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Groups({"list", "detail"})
     */
    private $firstname;

    /**
     * @ORM\Column(name="email", type="string", length=255)
     *
     * @Serializer\Groups({"list", "detail"})
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Serializer\Groups({"exclude"})
     */
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
