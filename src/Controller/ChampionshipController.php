<?php
namespace App\Controller;

use App\Entity\Championship;
use App\Entity\Day;
use App\Service\ChampionshipService;
use App\Service\DayService;
use App\Form\DayFormType;
use App\Form\SelectDayFormType;
use App\Form\ChampionshipFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/championship")]
class ChampionshipController extends AbstractController {
    #[Route("/", name: "championships")]
    public function showChampionships(ChampionshipService $service): Response {
        $championships = $service->getAll();
        return $this->render("championship/index.html.twig", ["championships" => $championships]);
    }
    
    #[Route("/{id}", name: "championship")]
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

    #[Route("/{id}/ranking", name: "championship_ranking")]
    public function showRanking(ChampionshipService $service, int $id) {
        $championship = $service->getChampionshipById($id);
        $ranking = [];
        foreach ($championship->getTeams() as $team) {
            $points = ['won' => 0, 'lost' => 0];
            foreach ($team->getGamesAsTeam1() as $game) {
                $points['won'] += $game->getTeam1Point();
                $points['lost'] += $game->getTeam2Point();
            }
            foreach ($team->getGamesAsTeam2() as $game) {
                $points['won'] += $game->getTeam2Point();
                $points['lost'] += $game->getTeam1Point();
            }
            $ranking[] = [
                'id' => $team->getId(),
                'name' => $team->getName(),
                'logo' => $team->getLogo(),
                'pointsWon' => $points['won'],
                'pointsLost' => $points['lost']
            ];
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

    #[Route("/{id}/days", name: "days")]
    public function showDays(Request $request, ChampionshipService $service, DayService $dayService, int $id) {
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

    #[Route("/{id}/days/add", name: "add_day")]
    public function addDay(Request $request, ChampionshipService $service, DayService $dayService, int $id): RedirectResponse|Response {
        $day = new Day();
        $form = $this->createForm(DayFormType::class, $day);
        $form->handleRequest($request);
        
        $championship = $service->getChampionshipById($id);
        if ($form->isSubmitted() && $form->isValid()) {
            $day->setChampionship($championship);

            $dayService->saveDay($day);

            return $this->redirectToRoute('days', ['id' => $id]);
        }

        return $this->render('day/new.html.twig', [
            'dayForm' => $form,
            'championship' => $championship
        ]);
    }

    #[Route("/new/insert", name: "add_championship")]
    public function insert(Request $request, ChampionshipService $service): RedirectResponse|Response {
        $championship = new Championship();
        $form = $this->createForm(ChampionshipFormType::class, $championship);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->saveChampionship($championship);

            return $this->redirectToRoute('championships');
        }

        return $this->render('championship/new.html.twig', [
            'championshipForm' => $form,
        ]);
    }
}