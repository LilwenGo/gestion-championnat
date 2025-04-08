<?php
namespace App\Repository;

use App\Entity\Country;
use Doctrine\ORM\EntityRepository;

class CountryRepository extends EntityRepository {
    public function getAll(): array {
        $query = $this->getEntityManager()->createQuery(
            "SELECT c FROM ".$this->getEntityName()." c"
        );
        return $query->getResult();
    }
    
    public function findById(int $id): ?Country {
        $query = $this->getEntityManager()->createQuery(
            "SELECT c FROM ".$this->getEntityName()." c WHERE c.id = :id"
        );
        $query->setParameter('id', $id);
        return $query->getResult();
    }
    
    public function findByName(string $name): ?Country {
        $query = $this->getEntityManager()->createQuery(
            "SELECT c FROM ".$this->getEntityName()." c WHERE c.name = :name"
        );
        $query->setParameter('name', $name);
        return $query->getResult();
    }
}