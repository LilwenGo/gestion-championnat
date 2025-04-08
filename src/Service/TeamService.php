<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Team;

class TeamService {
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getTeamById(int $id): ?Team {
        return $this->em->getRepository("App:Team")->findById($id);
    }

    public function getTeamByName(string $name): ?Team {
        return $this->em->getRepository("App:Team")->findByName($name);
    }

    public function insertTeam(Team $team): void {
        $this->em->persist($team);
        $this->em->flush();
    }

    public function updateTeam(Team $team): void {
        $this->em->persist($team);
        $this->em->flush();
    }

    public function deleteTeam(Team $team): void {
        $this->em->remove($team);
        $this->em->flush();
    }
}