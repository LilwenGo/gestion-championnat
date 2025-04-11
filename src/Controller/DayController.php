<?php
namespace App\Controller;

use App\Entity\Day;
use App\Entity\Game;
use App\Service\DayService;
use App\Service\GameService;
use App\Form\GameFormType;
use App\Form\DayFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DayController extends AbstractController {
    #[Route("/admin/day/{id}/update", name: "update_day")]
    public function update(Request $request, DayService $service, int $id): RedirectResponse|Response {
        $day = $service->getDayById($id);
        if(!$day) {
            return $this->redirectToRoute('championships');
        }
        $form = $this->createForm(DayFormType::class, $day);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->saveDay($day);

            return $this->redirectToRoute('championship', ['id' => $day->getChampionship()->getId()]);
        }

        return $this->render('day/update.html.twig', [
            'dayForm' => $form,
            'championship' => $day->getChampionship()
        ]);
    }

    #[Route("/admin/day/{id}/delete", name: "delete_day")]
    public function delete(DayService $service, int $id): RedirectResponse {
        $day = $service->getDayById($id);
        $championship_id = null;
        if(!$day) {
            $this->addFlash('error', 'La journée n\'a pas pu être suprimmée.');
        } else {
            $championship_id = $day->getChampionship()->getId();
            $service->deleteDay($day);
        }
        if($championship_id) {
            return $this->redirectToRoute('championship', ['id' => $championship_id]);
        } else {
            return $this->redirectToRoute('root');
        }
    }

    #[Route("/admin/day/{id}/game/add", name: "add_game_from_day")]
    public function insertGame(Request $request, DayService $service, GameService $gameService, int $id): RedirectResponse|Response {
        $day = $service->getDayById($id);
        $championship = $day->getChampionship();

        $game = new Game();
        $game->setDay($day);
        $form = $this->createForm(GameFormType::class, $game, ['days' => $championship->getDays(), 'teams' => $championship->getTeams()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game->setDay($day);

            $gameService->saveGame($game);

            return $this->redirectToRoute('championship', ['id' => $championship->getId()]);
        }

        return $this->render('game/new.html.twig', [
            'gameForm' => $form,
            'championship' => $championship,
            'teams' => $championship->getTeams()
        ]);
    }
}