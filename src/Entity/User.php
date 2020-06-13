<?php

namespace App\Entity;

use App\Entity\Client;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Accessor;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * 
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository") 
 * 
 * @ExclusionPolicy("all")
 * 
 * 
 * @Hateoas\Relation(
 *     "create",
 *     href=@Hateoas\Route(
 *         "client_users_create",
 *         absolute=true
 *     )
 * )
 * 
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "client_users_delete",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 * 
 * @Hateoas\Relation(
 *      "self",
 *      href=@Hateoas\Route(
 *          "client_users_details",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 * 
 */
class User implements UserInterface
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80, unique=true)
     * @Expose
     * @Groups({"users_details"})
     * @Assert\Length(
     *    min = 3,
     *    max = 25
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     * @Expose
     * @Groups({"users_details"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=10)
     * @Expose
     * @Groups({"users_details"})
     */
    private $phone;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Accessor(getter="getRoles", setter="setRoles")
     */
    private $roles = '';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="users")
     * @Expose
     */
    private $client;

    /**
     * @Assert\Date
     * @var string A "Y-m-d H:i:s" formatted value
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $dateAdd;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return null;
    }
  
    public function eraseCredentials() {}

    public function getRoles()
    {
        return json_decode($this->roles);
    }

    /**
     * @return  self
     */ 
    public function setRoles($roles)
    {
        $this->roles = json_encode($roles);

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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
}