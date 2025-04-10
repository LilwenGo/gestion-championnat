<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Team;

class TeamRepository extends EntityRepository {
    public function getAll(): array {
        $query = $this->getEntityManager()->createQuery(
            "SELECT t FROM ".$this->getEntityName()." t"
        );
        return $query->getResult();
    }
    
    public function findById(int $id): ?Team {
        $query = $this->getEntityManager()->createQuery(
            "SELECT t FROM ".$this->getEntityName()." t WHERE t.id = :id"
        );
        $query->setParameter('id', $id);
        return $query->getResult()[0];
    }
    
    public function findByName(string $name): ?Team {
        $query = $this->getEntityManager()->createQuery(
            "SELECT t FROM ".$this->getEntityName()." t WHERE t.name = :name"
        );
        $query->setParameter('name', $name);
        return $query->getResult()[0];
    }
}