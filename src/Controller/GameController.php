<?php
namespace App\Controller;

use App\Entity\Game;
use App\Service\GameService;
use App\Form\GameFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController {
    #[Route("/admin/game/{id}/update", name: "update_game")]
    public function update(Request $request, GameService $service, int $id): RedirectResponse|Response {
        $game = $service->getGameById($id);
        if(!$game) {
            return $this->redirectToRoute('championships');
        }
        $championship = $game->getDay()->getChampionship();
        $form = $this->createForm(GameFormType::class, $game, [
            'days' => $championship->getDays(),
            'teams' => $championship->getTeams(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->saveGame($game);

            return $this->redirectToRoute('championship', ['id' => $championship->getId()]);
        }

        return $this->render('game/update.html.twig', [
            'gameForm' => $form,
            'championship' => $championship
        ]);
    }

    #[Route("/admin/game/{id}/delete", name: "delete_game")]
    public function delete(GameService $service, int $id): RedirectResponse {
        $game = $service->getGameById($id);
        $championship_id = null;
        if(!$game) {
            $this->addFlash('error', 'La journée n\'a pas pu être suprimmée.');
        } else {
            $championship_id = $game->getDay()->getChampionship()->getId();
            $service->deleteGame($game);
        }
        if($championship_id) {
            return $this->redirectToRoute('championship', ['id' => $championship_id]);
        } else {
            return $this->redirectToRoute('root');
        }
    }
}