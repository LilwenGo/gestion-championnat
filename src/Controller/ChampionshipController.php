<?php
namespace App\Controller;

use App\Entity\Championship;
use App\Entity\Day;
use App\Entity\Game;
use App\Service\ChampionshipService;
use App\Service\DayService;
use App\Service\GameService;
use App\Form\DayFormType;
use App\Form\SelectDayFormType;
use App\Form\GameFormType;
use App\Form\ChampionshipFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class ChampionshipController extends AbstractController {
    #[Route("/championship", name: "championships")]
    public function showChampionships(ChampionshipService $service): Response {
        $championships = $service->getAll();
        return $this->render("championship/index.html.twig", ["championships" => $championships]);
    }
    
    #[Route("/championship/{id}", name: "championship")]
    public function showChampionship(ChampionshipService $service, int $id): Response {
        $championship = $service->getChampionshipById($id);
        $formatedStartDate = $championship->getStartDate()->format('d-m-Y');
        $formatedEndDate = $championship->getEndDate()->format('d-m-Y');
        return $this->render("championship/show.html.twig", [
            "championship" => $championship,
            "formatedStartDate" => $formatedStartDate,
            "formatedEndDate" => $formatedEndDate
        ]);
    }

    #[Route("/championship/{id}/ranking", name: "championship_ranking")]
    public function showRanking(ChampionshipService $service, DayService $dayService, int $id): Response {
        $championship = $service->getChampionshipById($id);
        $days = $dayService->getDayByChampionshipIdWithGames($id);
        $ranking = [];
        foreach($championship->getTeams() as $team) {
            $ranking[$team->getId()] = [
                'name' => $team->getName(),
                'logo' => $team->getLogo(),
                'pointsWon' => 0,
                'pointsLost' => 0
            ];
        }
        foreach ($days as $day) {
            foreach ($day->getGames() as $game) {
                $team1 = $game->getTeam1();
                $team2 = $game->getTeam2();
                $ranking[$team1->getId()]['pointsWon'] += $game->getTeam1Point();
                $ranking[$team2->getId()]['pointsWon'] += $game->getTeam2Point();
                $ranking[$team1->getId()]['pointsLost'] += $game->getTeam2Point();
                $ranking[$team2->getId()]['pointsLost'] += $game->getTeam1Point();
            }
        }
        usort($ranking, function ($a, $b) {
            // Trier par points gagnés (desc)
            if ($a['pointsWon'] !== $b['pointsWon']) {
                return $b['pointsWon'] <=> $a['pointsWon'];
            }
        
            // Sinon, trier par points perdus (asc)
            return $a['pointsLost'] <=> $b['pointsLost'];
        });
        return $this->render('championship/ranking.html.twig', [
            'championship' => $championship,
            'ranking' => $ranking
        ]);
    }

    #[Route("/championship/{id}/days", name: "days")]
    public function showDays(Request $request, ChampionshipService $service, DayService $dayService, int $id): Response {
        $championship = $service->getChampionshipById($id);
        $days = $championship->getDays();
        $form = $this->createForm(SelectDayFormType::class, null, ["days" => $days]);
        $form->handleRequest($request);

        $day = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $day = $form->get('day')->getData();
        }

        return $this->render('day/index.html.twig', [
            'daySelect' => $form,
            'days' => $days,
            'day' => $day,
            'championship' => $championship
        ]);
    }

    #[Route("/admin/championship/{id}/days/add", name: "add_day")]
    public function addDay(Request $request, ChampionshipService $service, DayService $dayService, int $id): RedirectResponse|Response {
        $day = new Day();
        $form = $this->createForm(DayFormType::class, $day);
        $form->handleRequest($request);
        
        $championship = $service->getChampionshipById($id);
        if ($form->isSubmitted() && $form->isValid()) {
            $day->setChampionship($championship);

            $dayService->saveDay($day);

            return $this->redirectToRoute('championship', ['id' => $id]);
        }

        return $this->render('day/new.html.twig', [
            'dayForm' => $form,
            'championship' => $championship
        ]);
    }

    #[Route("/admin/championship/{id}/game/add", name: "add_game_from_championship")]
    public function insertGame(Request $request, ChampionshipService $service, GameService $gameService, int $id): RedirectResponse|Response {
        $championship = $service->getChampionshipById($id);

        $game = new Game();
        $form = $this->createForm(GameFormType::class, $game, ['days' => $championship->getDays(), 'teams' => $championship->getTeams()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gameService->saveGame($game);

            return $this->redirectToRoute('championship', ['id' => $championship->getId()]);
        }

        return $this->render('game/new.html.twig', [
            'gameForm' => $form,
            'championship' => $championship,
            'teams' => $championship->getTeams()
        ]);
    }

    #[Route("/admin/championship/insert", name: "add_championship")]
    public function insert(Request $request, ChampionshipService $service): RedirectResponse|Response {
        $championship = new Championship();
        $form = $this->createForm(ChampionshipFormType::class, $championship);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($championship->getTeams() as $team) {
                $team->addChampionship($championship);
            }
            $service->saveChampionship($championship);

            return $this->redirectToRoute('championships');
        }

        return $this->render('championship/new.html.twig', [
            'championshipForm' => $form,
        ]);
    }

    #[Route("/admin/championship/{id}/update", name: "update_championship")]
    public function update(Request $request, ChampionshipService $service, int $id): RedirectResponse|Response {
        $championship = $service->getChampionshipById($id);
        if(!$championship) {
            return $this->redirectToRoute('add_championship');
        }
        $form = $this->createForm(ChampionshipFormType::class, $championship);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($championship->getTeams() as $team) {
                $team->addChampionship($championship);
            }
            $service->saveChampionship($championship);

            return $this->redirectToRoute('championship', ['id' => $id]);
        }

        return $this->render('championship/update.html.twig', [
            'championshipForm' => $form,
            'championship' => $championship
        ]);
    }

    #[Route("/admin/championship/{id}/delete", name: "delete_championship")]
    public function delete(ChampionshipService $service, int $id): RedirectResponse {
        $championship = $service->getChampionshipById($id);
        if(!$championship) {
            $this->addFlash('error', 'Le championnat n\'a pas pu être suprimmé.');
        } else {
            $service->deleteChampionship($championship);
        }
        return $this->redirectToRoute('championships');
    }
}