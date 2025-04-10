<?php
namespace App\Repository;

use App\Entity\Day;
use Doctrine\ORM\EntityRepository;

class DayRepository extends EntityRepository {
    public function getAll(): array {
        $query = $this->getEntityManager()->createQuery(
            "SELECT c FROM ".$this->getEntityName()." c"
        );
        return $query->getResult();
    }
    
    public function findById(int $id): ?Day {
        $query = $this->getEntityManager()->createQuery(
            "SELECT c FROM ".$this->getEntityName()." c WHERE c.id = :id"
        );
        $query->setParameter('id', $id);
        return $query->getResult()[0];
    }
}