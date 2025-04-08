<?php
namespace App\Repository;

use App\Entity\Game;
use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository {
    public function getAll(): array {
        $query = $this->getEntityManager()->createQuery(
            "SELECT g FROM ".$this->getEntityName()." g"
        );
        return $query->getResult();
    }
    
    public function findById(int $id): ?Game {
        $query = $this->getEntityManager()->createQuery(
            "SELECT g FROM ".$this->getEntityName()." g WHERE g.id = :id"
        );
        $query->setParameter('id', $id);
        return $query->getResult();
    }
    
    public function findByTeam(int $team_id): array {
        $query = $this->getEntityManager()->createQuery(
            "SELECT g FROM ".$this->getEntityName()." g WHERE g.team1 = :team1 OR g.team2 = :team2"
        );
        $query->setParameter('team1', $team_id);
        $query->setParameter('team2', $team_id);
        return $query->getResult();
    }
}