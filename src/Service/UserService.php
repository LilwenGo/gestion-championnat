<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UserService {
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getUserById(int $id): ?User {
        return $this->em->getRepository("App\Entity\User")->findById($id);
    }

    public function getUserByEmail(string $email): ?User {
        return $this->em->getRepository("App\Entity\User")->findByEmail($email);
    }

    public function saveUser(User $user): void {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function deleteUser(User $user): void {
        $this->em->remove($user);
        $this->em->flush();
    }
}