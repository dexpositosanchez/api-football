<?php

namespace App\Service\Club;

use App\Entity\Person;
use App\Repository\ClubRepository;
use App\Repository\PersonRepository;
use App\DTO\AddPersonClubDTO;
use App\Service\NotificationService;
use App\Exception\NotFoundException;
use App\Exception\PersonNotFreeException;
use App\Exception\AddPersonException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Servicio para añadir un Person a un Club
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class AddPersonClubService
{
    private $em;
    private $personRepository;
    private $clubRepository;
    private $notificationService;

    public function __construct(EntityManagerInterface $em, PersonRepository $personRepository, ClubRepository $clubRepository, NotificationService $notificationService)
    {
        $this->em = $em;
        $this->personRepository = $personRepository;
        $this->clubRepository = $clubRepository;
        $this->notificationService = $notificationService;
    }

    /**
     * @param Person           $person: objeto de la persona
     * @param AddPersonClubDTO $dto   : datos de la peticiñon
     * @return array
     */
    public function execute(Person $person, AddPersonClubDTO $dto): array
    {
        if (!$person->isFree()) {
            throw new PersonNotFreeException();
        }
        $club = $this->clubRepository->find($dto->getClub());
        if (!$club) {
            throw new NotFoundException("Club no encontrado");
        }
        $salaries = $this->personRepository->getTotalSalariesByClub($club);
        if (($salaries + $dto->getSalary()) > $club->getBudget()) {
            throw new AddPersonException('Inserción rechazada, al añadir esta persona, la suma de los salarios sería mayor que el presupuesto: '.$salaries.' + '.$dto->getSalary().' > '.$club->getBudget());
        }
        $person->setClub($club);
        $person->setSalary($dto->getSalary());
        $this->em->persist($person);
        $this->em->flush();
        $message = $person->getName()." ".$person->getSurname()." ha sido inscrito en el equipo ".$club->getName();
        $notification = $this->notificationService->sendNotification($message);
        return [$message, $notification];
    }
}

