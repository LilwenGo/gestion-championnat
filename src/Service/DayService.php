<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Day;

class DayService {
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getDayById(int $id): ?Day {
        return $this->em->getRepository("App:Day")->findById($id);
    }

    public function insertDay(Day $day): void {
        $this->em->persist($day);
        $this->em->flush();
    }

    public function updateDay(Day $day): void {
        $this->em->persist($day);
        $this->em->flush();
    }

    public function deleteDay(Day $day): void {
        $this->em->remove($day);
        $this->em->flush();
    }
}