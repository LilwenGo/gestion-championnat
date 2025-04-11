<?php
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: "App\Repository\ChampionshipRepository")]
#[ORM\Table("championship")]
#[ApiResource]
class Championship {
    #[ORM\Column(name: "id", type: Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private ?int $id = null;
    
    #[ORM\Column(name: "name", type: Types::STRING, length: 255)]
    private ?string $name = null;
    
    #[ORM\Column(name: "startDate", type: Types::DATETIME_MUTABLE)]
    private ?DateTime $startDate = null;
    
    #[ORM\Column(name: "endDate", type: Types::DATETIME_MUTABLE)]
    private ?DateTime $endDate = null;

    #[ORM\Column(name: "wonPoint", type: Types::INTEGER)]
    private ?int $wonPoint = null;

    #[ORM\Column(name: "lostPoint", type: Types::INTEGER)]
    private ?int $lostPoint = null;

    #[ORM\Column(name: "drawPoint", type: Types::INTEGER)]
    private ?int $drawPoint = null;

    #[ORM\Column(name: "typeRanking", type: Types::STRING, length: 255)]
    private ?string $typeRanking = null;

    #[ORM\ManyToMany(targetEntity: Team::class, mappedBy: "championships")]
    private Collection $teams;
    
    #[ORM\OneToMany(targetEntity: Day::class, mappedBy: 'championship', cascade: ['persist', 'remove'])]
    private Collection $days;

    public function __construct() {
        $this->teams = new ArrayCollection();
        $this->days = new ArrayCollection();
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
     * Get the value of startDate
     */ 
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * Set the value of startDate
     *
     * @return  self
     */ 
    public function setStartDate(?DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get the value of endDate
     */ 
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    /**
     * Set the value of endDate
     *
     * @return  self
     */ 
    public function setEndDate(?DateTime $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get the value of wonPoint
     */ 
    public function getWonPoint(): ?int
    {
        return $this->wonPoint;
    }

    /**
     * Set the value of wonPoint
     *
     * @return  self
     */ 
    public function setWonPoint(?int $wonPoint): static
    {
        $this->wonPoint = $wonPoint;

        return $this;
    }

    /**
     * Get the value of lostPoint
     */ 
    public function getLostPoint(): ?int
    {
        return $this->lostPoint;
    }

    /**
     * Set the value of lostPoint
     *
     * @return  self
     */ 
    public function setLostPoint(?int $lostPoint): static
    {
        $this->lostPoint = $lostPoint;

        return $this;
    }

    /**
     * Get the value of drawPoint
     */ 
    public function getDrawPoint(): ?int
    {
        return $this->drawPoint;
    }

    /**
     * Set the value of drawPoint
     *
     * @return  self
     */ 
    public function setDrawPoint(?int $drawPoint): static
    {
        $this->drawPoint = $drawPoint;

        return $this;
    }

    /**
     * Get the value of typeRanking
     */ 
    public function getTypeRanking(): ?string
    {
        return $this->typeRanking;
    }

    /**
     * Set the value of typeRanking
     *
     * @return  self
     */ 
    public function setTypeRanking(?string $typeRanking): static
    {
        $this->typeRanking = $typeRanking;

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
        // Supprimer les équipes non sélectionnées
        foreach ($this->teams as $existingTeam) {
            if (!$teams->contains($existingTeam)) {
                $this->removeTeam($existingTeam);
            }
        }

        // Ajouter les nouvelles équipes
        foreach ($teams as $team) {
            $this->addTeam($team);
        }

        return $this;
    }

    public function addTeam(Team $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->addChampionship($this); // synchronise l’autre côté
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        if ($this->teams->removeElement($team)) {
            $team->removeChampionship($this);
        }

        return $this;
    }

    /**
     * Get the value of days
     */ 
    public function getDays(): Collection 
    {
        return $this->days;
    }

    /**
     * Set the value of days
     *
     * @return  self
     */ 
    public function setDays(Collection $days): static
    {
        $this->days = $days;

        return $this;
    }
}