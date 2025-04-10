<?php
namespace App\Controller;

use App\Entity\Country;
use App\Service\CountryService;
use App\Form\CountryFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/country")]
class CountryController extends AbstractController {
    #[Route('/', name: "countries")]
    public function index(CountryService $service) {
        $countries = $service->getAll();
        return $this->render('country/index.html.twig', [
            "countries" => $countries
        ]);
    }
        
    #[Route("/{id}", name: "country")]
    public function showCountry(CountryService $service, int $id): Response {
        $country = $service->getCountryById($id);
        return $this->render("country/show.html.twig", [
            "country" => $country,
        ]);
    }

    #[Route("/new/insert", name: "add_country")]
    public function insert(Request $request, CountryService $service): RedirectResponse|Response {
        $country = new Country();
        $form = $this->createForm(CountryFormType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('logo')->getData();

            if($file) {
                $fileName = uniqid('country_logo_').'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('images_dir'),
                    $fileName
                );
                $country->setLogo($fileName);
            }

            $service->insertCountry($country);

            return $this->redirectToRoute('countries');
        }

        return $this->render('country/new.html.twig', [
            'countryForm' => $form,
        ]);
    }
}