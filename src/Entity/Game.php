<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\GameRepository")]
#[ORM\Table("game")]
class Game
{
    #[ORM\Column(name: "id", type: Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(name: "team1Point", type: Types::INTEGER)]
    private ?int $team1Point = null;

    #[ORM\Column(name: "team2Point", type: Types::INTEGER)]
    private ?int $team2Point = null;
    
    #[ORM\ManyToOne(targetEntity: Day::class, inversedBy: "games")]
    #[ORM\JoinColumn(name: 'day', referencedColumnName: 'id')]
    private ?Day $day = null;
    
    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: "gamesAsTeam1")]
    #[ORM\JoinColumn(name: 'team1', referencedColumnName: 'id')]
    private ?Team $team1 = null;
    
    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: "gamesAsTeam2")]
    #[ORM\JoinColumn(name: 'team2', referencedColumnName: 'id')]
    private ?Team $team2 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam1Point(): ?int
    {
        return $this->team1Point;
    }

    public function setTeam1Point(?int $team1Point): static
    {
        $this->team1Point = $team1Point;

        return $this;
    }

    public function getTeam2Point(): ?int
    {
        return $this->team2Point;
    }

    public function setTeam2Point(?int $team2Point): static
    {
        $this->team2Point = $team2Point;

        return $this;
    }

    /**
     * Get the value of day
     */ 
    public function getDay(): ?Day
    {
        return $this->day;
    }

    /**
     * Set the value of day
     *
     * @return  self
     */ 
    public function setDay(?Day $day): static
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get the value of team1
     */ 
    public function getTeam1(): ?Team
    {
        return $this->team1;
    }

    /**
     * Set the value of team1
     *
     * @return  self
     */ 
    public function setTeam1(?Team $team1): static
    {
        $this->team1 = $team1;

        return $this;
    }

    /**
     * Get the value of team2
     */ 
    public function getTeam2(): ?Team
    {
        return $this->team2;
    }

    /**
     * Set the value of team2
     *
     * @return  self
     */ 
    public function setTeam2(?Team $team2): static
    {
        $this->team2 = $team2;

        return $this;
    }
}
