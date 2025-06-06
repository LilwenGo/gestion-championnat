<?php
namespace App\Repository;

use App\Entity\Championship;
use Doctrine\ORM\EntityRepository;

class ChampionshipRepository extends EntityRepository {
    public function getAll(): array {
        $query = $this->getEntityManager()->createQuery(
            "SELECT c FROM ".$this->getEntityName()." c"
        );
        return $query->getResult();
    }
    
    public function findById(int $id): ?Championship {
        $query = $this->getEntityManager()->createQuery(
            "SELECT c FROM ".$this->getEntityName()." c WHERE c.id = :id"
        );
        $query->setParameter('id', $id);
        return $query->getResult()[0];
    }
    
    public function findByName(string $name): ?Championship {
        $query = $this->getEntityManager()->createQuery(
            "SELECT c FROM ".$this->getEntityName()." c WHERE c.name = :name"
        );
        $query->setParameter('name', $name);
        return $query->getResult()[0];
    }
}