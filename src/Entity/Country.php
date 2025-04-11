<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: "App\Repository\CountryRepository")]
#[ORM\Table("country")]
class Country {
    #[ORM\Column(name: "id", type: Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private ?int $id = null;
    
    #[ORM\Column(name: "name", type: Types::STRING, length: 255)]
    private ?string $name = null;
    
    #[ORM\Column(name: "logo", type: Types::STRING, length: 255)]
    private ?string $logo = null;

    #[ORM\OneToMany(targetEntity: Team::class, mappedBy: 'country', cascade: ['persist', 'remove'])]
    private Collection $teams;

    public function __construct() {
        $this->teams = new ArrayCollection();
    }

    public function __tostring(): string {
        return $this->name;
    }

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
     * Get the value of name
     */ 
    public function getName(): ?string 
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of logo
     */ 
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * Set the value of logo
     *
     * @return  self
     */ 
    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get the value of teams
     */ 
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    /**
     * Set the value of teams
     *
     * @return  self
     */ 
    public function setTeams(Collection $teams): static
    {
        $this->teams = $teams;

        return $this;
    }
}