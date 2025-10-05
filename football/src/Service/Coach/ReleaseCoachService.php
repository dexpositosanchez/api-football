<?php

namespace App\Service\Coach;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\NotificationService;
use App\Repository\CoachRepository;
use App\DTO\ReleaseCoachDTO;
use App\Exception\NotFoundException;
use App\Exception\FreeCoachException;


/**
 * Servicio para intentar liberar a un entrenador de su club
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ReleaseCoachService
{
    private $em;
    private $coachRepository;
    private $notificationService;

    public function __construct(EntityManagerInterface $em, CoachRepository $coachRepository, NotificationService $notificationService)
    {
        $this->em = $em;
        $this->coachRepository = $coachRepository;
        $this->notificationService = $notificationService;
    }

    /**
     * @param ReleaseCoachDTO $dto: datos recibidos en la petición
     * @return array
     */
    public function execute(ReleaseCoachDTO $dto): array
    {
        $coach = $this->coachRepository->find((int)$dto->getCoach());
        if (!$coach) {
            throw new NotFoundException("Entrenador no encontrado");
        }
        if ($coach->isFree()) {
            throw new FreeCoachException();
        }
        $message = "El entrenador ".$coach->getName()." ".$coach->getSurname()." ha sido liberado del equipo ".$coach->getClub()->getName();
        $coach->setClub(null);
        $coach->setSalary(null);
        $this->em->persist($coach);
        $this->em->flush();
        $notification = $this->notificationService->sendNotification($message);
        return [$message, $notification];
    }
}

