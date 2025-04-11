<?php
namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: "App\Repository\TeamRepository")]
#[ORM\Table("team")]
class Team {
    #[ORM\Column(name: "id", type: Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(name: "name", type: Types::STRING, length: 255)]
    private ?string $name = null;
    
    #[ORM\Column(name: "creationDate", type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $creationDate = null;
    
    #[ORM\Column(name: "stade", type: Types::STRING, length: 255)]
    private ?string $stade = null;
    
    #[ORM\Column(name: "logo", type: Types::STRING, length: 255)]
    private ?string $logo = null;
    
    #[ORM\Column(name: "president", type: Types::STRING, length: 255)]
    private ?string $president = null;
    
    #[ORM\Column(name: "coach", type: Types::STRING, length: 255)]
    private ?string $coach = null;
    
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'team1', cascade: ['persist', 'remove'])]
    private Collection $gamesAsTeam1;
    
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'team2', cascade: ['persist', 'remove'])]
    private Collection $gamesAsTeam2;

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: "teams")]
    #[ORM\JoinColumn(name: 'country', referencedColumnName: 'id')]
    private ?Country $country = null;
    
    #[ORM\ManyToMany(targetEntity: Championship::class, inversedBy: "teams")]
    #[ORM\JoinTable(name: "team_championship")]
    private Collection $championships;

    public function __construct() {
        $this->gamesAsTeam1 = new ArrayCollection();
        $this->gamesAsTeam2 = new ArrayCollection();
        $this->championships = new ArrayCollection();
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
     * Get the value of creationDate
     */ 
    public function getCreationDate(): ?DateTimeImmutable
    {
        return $this->creationDate;
    }

    /**
     * Set the value of creationDate
     *
     * @return  self
     */ 
    public function setCreationDate(?DateTimeImmutable $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get the value of stade
     */ 
    public function getStade(): ?string
    {
        return $this->stade;
    }

    /**
     * Set the value of stade
     *
     * @return  self
     */ 
    public function setStade(?string $stade): static
    {
        $this->stade = $stade;

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
     * Get the value of president
     */ 
    public function getPresident(): ?string
    {
        return $this->president;
    }

    /**
     * Set the value of president
     *
     * @return  self
     */ 
    public function setPresident(?string $president): static
    {
        $this->president = $president;

        return $this;
    }

    /**
     * Get the value of coach
     */ 
    public function getCoach(): ?string
    {
        return $this->coach;
    }

    /**
     * Set the value of coach
     *
     * @return  self
     */ 
    public function setCoach(?string $coach): static
    {
        $this->coach = $coach;

        return $this;
    }

    /**
     * Get the value of country
     */ 
    public function getCountry(): ?Country
    {
        return $this->country;
    }

    /**
     * Set the value of country
     *
     * @return  self
     */ 
    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get the value of championships
     */ 
    public function getChampionships(): Collection
    {
        return $this->championships;
    }

    /**
     * Set the value of championships
     *
     * @return  self
     */ 
    public function setChampionships(Collection $championships): static
    {
        $this->championships = $championships;

        return $this;
    }

    public function addChampionship(Championship $championship): self
    {
        if (!$this->championships->contains($championship)) {
            $this->championships->add($championship);
            $championship->addTeam($this); // synchronise aussi dans l’autre sens
        }

        return $this;
    }

    public function removeChampionship(Championship $championship): self
    {
        if ($this->championships->removeElement($championship)) {
            $championship->removeTeam($this);
        }

        return $this;
    }

    /**
     * Get the value of gamesAsTeam1
     */ 
    public function getGamesAsTeam1(): Collection
    {
        return $this->gamesAsTeam1;
    }

    /**
     * Set the value of gamesAsTeam1
     *
     * @return  self
     */ 
    public function setGamesAsTeam1(Collection $gamesAsTeam1): static
    {
        $this->gamesAsTeam1 = $gamesAsTeam1;

        return $this;
    }

    /**
     * Get the value of gamesAsTeam2
     */ 
    public function getGamesAsTeam2(): Collection
    {
        return $this->gamesAsTeam2;
    }

    /**
     * Set the value of gamesAsTeam2
     *
     * @return  self
     */ 
    public function setGamesAsTeam2(Collection $gamesAsTeam2): static
    {
        $this->gamesAsTeam2 = $gamesAsTeam2;

        return $this;
    }
}