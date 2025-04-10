<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\User;

class UserRepository extends EntityRepository {
    public function getAll(): array {
        $query = $this->getEntityManager()->createQuery(
            "SELECT u FROM ".$this->getEntityName()." u"
        );
        return $query->getResult();
    }
    
    public function findById(int $id): ?User {
        $query = $this->getEntityManager()->createQuery(
            "SELECT u FROM ".$this->getEntityName()." u WHERE u.id = :id"
        );
        $query->setParameter('id', $id);
        return $query->getResult()[0];
    }
    
    public function findByEmail(string $email): ?User {
        $query = $this->getEntityManager()->createQuery(
            "SELECT u FROM ".$this->getEntityName()." u WHERE u.email = :email"
        );
        $query->setParameter('email', $email);
        return $query->getResult()[0];
    }
}