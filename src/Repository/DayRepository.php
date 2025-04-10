<?php
namespace App\Repository;

use App\Entity\Day;
use Doctrine\ORM\EntityRepository;

class DayRepository extends EntityRepository {
    public function getAll(): array {
        $query = $this->getEntityManager()->createQuery(
            "SELECT d FROM ".$this->getEntityName()." d"
        );
        return $query->getResult();
    }
    
    public function findById(int $id): ?Day {
        $query = $this->getEntityManager()->createQuery(
            "SELECT d FROM ".$this->getEntityName()." d WHERE d.id = :id"
        );
        $query->setParameter('id', $id);
        return $query->getResult()[0];
    }

    public function findByIdWithGames(int $id): ?Day {
        $query = $this->getEntityManager()->createQuery(
            "SELECT d FROM ".$this->getEntityName()." d JOIN d.games g JOIN g.team1 t1 JOIN g.team2 t2 WHERE d.id = :id"
        );
        $query->setParameter('id', $id);
        return $query->getResult()[0];
    }
}