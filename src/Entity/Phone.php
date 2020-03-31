<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneRepository")
 *
 *
 * @Hateoas\Relation(
 *     "self",
 *     href =  @Hateoas\Route(
 *         "phone_show_one",
 *         parameters={"id" = "expr(object.getId())"},
 *         absolute = true
 *     )
 * )
 *
 * @Hateoas\Relation(
*      "shwoAll",
 *     href = @Hateoas\Route(
 *          "phone_show_all",
 *          absolute=true
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "brand",
 *     embedded= @Hateoas\Embedded("expr(object.getBrand())"),
 * )
 *
 */
class Phone
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
    private $name;

    /**
     * @ORM\Column(type="text")
     *
     * @Serializer\Groups({"detail"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand", inversedBy="phone")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Serializer\Groups({"exclude"})
     */
    private $brand;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

}
