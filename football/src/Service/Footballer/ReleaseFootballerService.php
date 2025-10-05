<?php

namespace App\Service\Footballer;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\NotificationService;
use App\Repository\FootballerRepository;
use App\DTO\ReleaseFootballerDTO;
use App\Exception\NotFoundException;
use App\Exception\FreeFootballerException;

/**
 * Servicio para intentar liberar a un futbolista de su club
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ReleaseFootballerService
{
    private $em;
    private $notificationService;
    private $footballerRepository;

    public function __construct(EntityManagerInterface $em, FootballerRepository $footballerRepository, NotificationService $notificationService)
    {
        $this->em = $em;
        $this->footballerRepository = $footballerRepository;
        $this->notificationService = $notificationService;
    }

    /**
     * @param ReleaseFootballerDTO $dto: datos recibidos en la petición
     * @return array
     */
    public function execute(ReleaseFootballerDTO $dto): array
    {
        $footballer = $this->footballerRepository->find((int)$dto->getFootballer());
        if (!$footballer) {
            throw new NotFoundException("Futbolista no encontrado");
        }
        if ($footballer->isFree()) {
            throw new FreeFootballerException();
        }
        $message = "El futbolista ".$footballer->getName()." ".$footballer->getSurname()." ha sido liberado del equipo ".$footballer->getClub()->getName();
        $footballer->setClub(null);
        $footballer->setSalary(null);
        $this->em->persist($footballer);
        $this->em->flush();
        $notification = $this->notificationService->sendNotification($message);
        return [$message, $notification];
            
    }
}

