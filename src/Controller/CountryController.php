<?php
namespace App\Controller;

use App\Entity\Country;
use App\Service\CountryService;
use App\Form\CountryFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CountryController extends AbstractController {
    #[Route('/country', name: "countries")]
    public function index(CountryService $service) {
        $countries = $service->getAll();
        return $this->render('country/index.html.twig', [
            "countries" => $countries
        ]);
    }
        
    #[Route("/country/{id}", name: "country")]
    public function showCountry(CountryService $service, int $id): Response {
        $country = $service->getCountryById($id);
        return $this->render("country/show.html.twig", [
            "country" => $country,
        ]);
    }

    #[Route("/admin/country/insert", name: "add_country")]
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

            $service->saveCountry($country);

            return $this->redirectToRoute('countries');
        }

        return $this->render('country/new.html.twig', [
            'countryForm' => $form,
        ]);
    }

    #[Route("/admin/country/{id}/update", name: "update_country")]
    public function update(Request $request, CountryService $service, int $id): RedirectResponse|Response {
        $country = $service->getCountryById($id);
        if(!$country) {
            return $this->redirectToRoute('add_country');
        }
        $form = $this->createForm(CountryFormType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('logo')->getData();

            if($file) {
                try {
                    $fileName = uniqid('country_logo_').'.'.$file->guessExtension();
                    $file->move(
                        $this->getParameter('images_dir'),
                        $fileName
                    );

                    $oldFilename = $country->getLogo();
                    if ($oldFilename && file_exists($this->getParameter('images_dir').'/'.$oldFilename)) {
                        if (!unlink($this->getParameter('images_dir').'/'.$oldFilename)) {
                            $this->addFlash('warning', 'Impossible de supprimer l’ancien fichier.');
                        }
                    }
    
                    $country->setLogo($fileName);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Le fichier n\'a pas pu être enregistré.');
                }
            }

            $service->saveCountry($country);

            return $this->redirectToRoute('country', ["id" => $id]);
        }

        return $this->render('country/update.html.twig', [
            'countryForm' => $form,
            'country' => $country
        ]);
    }

    #[Route("/admin/country/{id}/delete", name: "delete_country")]
    public function delete(CountryService $service, int $id): RedirectResponse {
        $country = $service->getCountryById($id);
        if(!$country) {
            $this->addFlash('error', 'Le pays n\'a pas pu être suprimmé.');
        } else {
            $filename = $country->getLogo();
            if ($filename && file_exists($this->getParameter('images_dir').'/'.$filename)) {
                if (!unlink($this->getParameter('images_dir').'/'.$filename)) {
                    $this->addFlash('warning', 'Impossible de supprimer l’ancien fichier.');
                }
            }
            $service->deleteCountry($country);
        }
        return $this->redirectToRoute('countries');
    }
}