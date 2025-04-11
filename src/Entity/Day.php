<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: "App\Repository\DayRepository")]
#[ORM\Table("day")]
#[ApiResource]
class Day {
    #[ORM\Column(name: "id", type: Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private ?int $id = null;
    
    #[ORM\Column(name: "number", type: Types::STRING, length: 255)]
    private ?string $number = null;

    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'day', cascade: ['persist', 'remove'])]
    private Collection $games;

    #[ORM\ManyToOne(targetEntity: Championship::class, inversedBy: "days")]
    #[ORM\JoinColumn(name: 'championship', referencedColumnName: 'id')]
    private ?Championship $championship = null;

    public function __construct() {
        $this->games = new ArrayCollection();
    }

    public function __tostring(): string {
        return 'Journée '.$this->number;
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
    public function getNumber(): ?string 
    {
        return $this->number;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setNumber(?string $number): static
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get the value of championship
     */ 
    public function getChampionship(): ?Championship
    {
        return $this->championship;
    }

    /**
     * Set the value of championship
     *
     * @return  self
     */ 
    public function setChampionship(?Championship $championship): static
    {
        $this->championship = $championship;

        return $this;
    }

    /**
     * Get the value of games
     */ 
    public function getGames(): Collection
    {
        return $this->games;
    }

    /**
     * Set the value of games
     *
     * @return  self
     */ 
    public function setGames(Collection $games): static
    {
        $this->games = $games;

        return $this;
    }
}