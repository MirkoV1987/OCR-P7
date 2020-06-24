<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Accessor;
use Doctrine\Common\Collections\ArrayCollection;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="products")
 * 
 * @UniqueEntity(fields={"name"}, message="Ce produit existe déjà !") 
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * 
 * @Hateoas\Relation(
 *     "self",
 *     href=@Hateoas\Route(
 *          "product_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true,
 *     )
 * )
 * 
 */
class Product 
{
    /**
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @Groups({"details"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=125)
     * @Assert\NotBlank
     * @Groups({"list", "details"})
     * 
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Groups({"list", "details"})
     * 
     */
    private $brand;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank 
     * @Groups({"details"})
     */
    private $description;

    /**
     * @Assert\Date
     * @var string A "Y-m-d H:i:s" formatted value
     * @ORM\Column(type="datetime", nullable = true)
     * @Groups({"details"})
     * 
     */
    private $dateAdd;

    /**
     * @var json Describe the product
     * @ORM\Column(type="json", nullable = true)
     * @Groups({"details"})
     * @Accessor(getter="getProperties", setter="setProperties")
     */
    private $properties;

    /**
     * @ORM\Column(type="decimal", precision=65, scale=2)
     * @Assert\NotBlank
     * @Groups({"list", "details"})
     */
    private $price;


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

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

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

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->dateAdd;
    }

    public function setDateAdd(\DateTimeInterface $dateAdd): self
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    public function getProperties()
    {
        //print_r(json_decode($this->properties)); exit;
        return json_decode($this->properties, 1);
    }

    /**
     * @return  self
     */ 
    public function setProperties($properties)
    {
        $this->properties = json_encode($properties);

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }
}