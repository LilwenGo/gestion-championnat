<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table("user")]
class User {
    #[ORM\Column(name: "id", type: Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(name: "firstName", type: Types::STRING, length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(name: "lastName", type: Types::STRING, length: 255)]
    private ?string $lastName = null;
    
    #[ORM\Column(name: "email", type: Types::STRING, length: 255)]
    private ?string $email = null;
    
    #[ORM\Column(name: "password", type: Types::STRING, length: 255)]
    private ?string $password = null;
    
    #[ORM\Column(name: "creationDate", type: Types::DATETIME_IMMUTABLE)]
    private ?string $creationDate = null;

    /**
     * Get the value of id
     */ 
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of firstName
     */ 
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */ 
    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     */ 
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */ 
    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail(): ?string 
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of creationDate
     */ 
    public function getCreationDate(): ?string
    {
        return $this->creationDate;
    }

    /**
     * Set the value of creationDate
     *
     * @return  self
     */ 
    public function setCreationDate( ?string $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }
}