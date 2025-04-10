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

#[Route("/day")]
class DayController extends AbstractController {
    

    #[Route("/{id}/game/add", name: "add_game")]
    public function insert(Request $request, DayService $service, GameService $gameService, int $id): RedirectResponse|Response {
        $day = $service->getDayById($id);
        $championship = $day->getChampionship();

        $game = new Game();
        $form = $this->createForm(GameFormType::class, $game, ['teams' => $championship->getTeams()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game->setDay($day);

            $gameService->saveGame($game);

            return $this->redirectToRoute('days', ['id' => $championship->getId()]);
        }

        return $this->render('game/new.html.twig', [
            'gameForm' => $form,
            'championship' => $championship
        ]);
    }
}