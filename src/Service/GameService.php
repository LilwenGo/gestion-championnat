<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Game;

class GameService {
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getGameById(int $id): ?Game {
        return $this->em->getRepository("App\Entity\Game")->findById($id);
    }

    public function getGameByTeam(int $team_id): array {
        return $this->em->getRepository("App\Entity\Game")->findByTeam($team_id);
    }

    public function insertGame(Game $game): void {
        $this->em->persist($game);
        $this->em->flush();
    }

    public function updateGame(Game $game): void {
        $this->em->persist($game);
        $this->em->flush();
    }

    public function deleteGame(Game $game): void {
        $this->em->remove($game);
        $this->em->flush();
    }
}