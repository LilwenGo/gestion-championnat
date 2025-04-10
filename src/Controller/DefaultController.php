<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController {
    #[Route("/", name: "root")]
    public function index(): RedirectResponse {
        return $this->redirectToRoute('championships');
    }
}