<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Accessor;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Doctrine\Common\Collections\ArrayCollection;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="clients")
 * 
 * @UniqueEntity("name")
 * @UniqueEntity("email")
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * 
 * @ExclusionPolicy("all")
 * 
 * @Hateoas\Relation(
 *      "self",
 *      href=@Hateoas\Route(
 *          "client_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 * 
 * @Hateoas\Relation(
 *     name = "users",
 *     embedded = @Hateoas\Embedded(
 *         "expr(object.getUsers())",
 *     )
 * )
 * 
 * 
 * 
 */
class Client
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80, unique=true)
     * @Expose
     * @Groups({"show"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Accessor(getter="getRoles", setter="setRoles")
     */
    private $roles = '';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="client", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $users;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function __construct() 
    { 
        $this->users = new ArrayCollection(); 
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClient($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            if ($user->getClient() === $this) {
                $user->setClient(null);
            }
        }

        return $this;
    }
}