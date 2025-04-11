<?php
namespace App\Controller;

use App\Entity\Team;
use App\Service\TeamService;
use App\Form\TeamFormType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TeamController extends AbstractController {
    #[Route('/team', name: "teams")]
    public function index(TeamService $service) {
        $teams = $service->getAll();
        return $this->render('team/index.html.twig', [
            "teams" => $teams
        ]);
    }
        
    #[Route("/team/{id}", name: "team")]
    public function showTeam(TeamService $service, int $id): Response {
        $team = $service->getTeamById($id);
        $formatedDate = $team->getCreationDate()->format("d-m-Y");
        return $this->render("team/show.html.twig", [
            "team" => $team,
            "formatedDate" => $formatedDate
        ]);
    }

    #[Route("/admin/team/insert", name: "add_team")]
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

            $service->saveTeam($team);

            return $this->redirectToRoute('teams');
        }

        return $this->render('team/new.html.twig', [
            'teamForm' => $form,
        ]);
    }

    #[Route("/admin/team/{id}/update", name: "update_team")]
    public function update(Request $request, TeamService $service, int $id): RedirectResponse|Response {
        $team = $service->getTeamById($id);
        if(!$team) {
            return $this->redirectToRoute('add_team');
        }
        $form = $this->createForm(TeamFormType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('logo')->getData();

            if($file) {
                try {
                    $fileName = uniqid('team_logo_').'.'.$file->guessExtension();
                    $file->move(
                        $this->getParameter('images_dir'),
                        $fileName
                    );

                    $oldFilename = $team->getLogo();
                    if ($oldFilename && file_exists($this->getParameter('images_dir').'/'.$oldFilename)) {
                        if (!unlink($this->getParameter('images_dir').'/'.$oldFilename)) {
                            $this->addFlash('warning', 'Impossible de supprimer l’ancien fichier.');
                        }
                    }
    
                    $team->setLogo($fileName);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Le fichier n\'a pas pu être enregistré.');
                }
            }

            $service->saveteam($team);

            return $this->redirectToRoute('team', ["id" => $id]);
        }

        return $this->render('team/update.html.twig', [
            'teamForm' => $form,
            'team' => $team
        ]);
    }

    #[Route("/admin/team/{id}/delete", name: "delete_team")]
    public function delete(TeamService $service, int $id): RedirectResponse {
        $team = $service->getTeamById($id);
        if(!$team) {
            $this->addFlash('error', 'L\'équipe n\'a pas pu être suprimmé.');
        } else {
            $filename = $team->getLogo();
            if ($filename && file_exists($this->getParameter('images_dir').'/'.$filename)) {
                if (!unlink($this->getParameter('images_dir').'/'.$filename)) {
                    $this->addFlash('warning', 'Impossible de supprimer l’ancien fichier.');
                }
            }
            $service->deleteTeam($team);
        }
        return $this->redirectToRoute('teams');
    }
}