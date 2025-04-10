<?php
namespace App\Controller;

use DateTime;
use App\Entity\Championship;
use App\Service\ChampionshipService;
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

    }

    #[Route("/{id}/days", name: "championship_days")]
    public function showDays(ChampionshipService $service, int $id) {

    }

    #[Route("/new/insert", "add_championship")]
    public function insert(Request $request, ChampionshipService $service): RedirectResponse|Response {
        $championship = new Championship();
        $form = $this->createForm(ChampionshipFormType::class, $championship);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->insertChampionship($championship);

            return $this->redirectToRoute('championships');
        }

        return $this->render('championship/new.html.twig', [
            'championshipForm' => $form,
        ]);
    }
}