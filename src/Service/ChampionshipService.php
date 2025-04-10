<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Championship;

class ChampionshipService {
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getAll(): array {
        return $this->em->getRepository("App\Entity\Championship")->getAll();
    }

    public function getChampionshipById(int $id): ?Championship {
        return $this->em->getRepository("App\Entity\Championship")->findById($id);
    }

    public function getChampionshipByName(string $name): ?Championship {
        return $this->em->getRepository("App\Entity\Championship")->findByName($name);
    }

    public function insertChampionship(Championship $championship): void {
        $this->em->persist($championship);
        $this->em->flush();
    }

    public function updateChampionship(Championship $championship): void {
        $this->em->persist($championship);
        $this->em->flush();
    }

    public function deleteChampionship(Championship $championship): void {
        $this->em->remove($championship);
        $this->em->flush();
    }
}