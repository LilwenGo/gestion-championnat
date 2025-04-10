<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Country;

class CountryService {
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getAll(): array {
        return $this->em->getRepository("App\Entity\Country")->getAll();
    }

    public function getCountryById(int $id): ?Country {
        return $this->em->getRepository("App\Entity\Country")->findById($id);
    }

    public function getCountryByName(string $name): ?Country {
        return $this->em->getRepository("App\Entity\Country")->findByName($name);
    }

    public function saveCountry(Country $country): void {
        $this->em->persist($country);
        $this->em->flush();
    }

    public function deleteCountry(Country $country): void {
        $this->em->remove($country);
        $this->em->flush();
    }
}