<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Team;

class TeamService {
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getAll(): array {
        return $this->em->getRepository("App\Entity\Team")->getAll();
    }

    public function getTeamById(int $id): ?Team {
        return $this->em->getRepository("App\Entity\Team")->findById($id);
    }

    public function getTeamByName(string $name): ?Team {
        return $this->em->getRepository("App\Entity\Team")->findByName($name);
    }

    public function saveTeam(Team $team): void {
        $this->em->persist($team);
        $this->em->flush();
    }

    public function deleteTeam(Team $team): void {
        $this->em->remove($team);
        $this->em->flush();
    }
}