<?php
namespace App\Controller;

use App\Entity\Team;
use App\Service\TeamService;
use App\Form\TeamFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/team")]
class TeamController extends AbstractController {
    #[Route('/', name: "teams")]
    public function index(TeamService $service) {
        $teams = $service->getAll();
        return $this->render('team/index.html.twig', [
            "teams" => $teams
        ]);
    }
        
    #[Route("/{id}", name: "team")]
    public function showTeam(TeamService $service, int $id): Response {
        $team = $service->getTeamById($id);
        $formatedDate = $team->getCreationDate()->format("d-m-Y");
        return $this->render("team/show.html.twig", [
            "team" => $team,
            "formatedDate" => $formatedDate
        ]);
    }

    #[Route("/new/insert", name: "add_team")]
    public function insert(Request $request, TeamService $service): RedirectResponse|Response {
        $team = new Team();
        $form = $this->createForm(TeamFormType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('logo')->getData();

            if($file) {
                $fileName = uniqid('team_logo_').'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('images_dir'),
                    $fileName
                );
                $team->setLogo($fileName);
            }

            $service->insertTeam($team);

            return $this->redirectToRoute('teams');
        }

        return $this->render('team/new.html.twig', [
            'teamForm' => $form,
        ]);
    }
}