<?php

namespace App\Tests\Services\Coach;

use App\Repository\CoachRepository;
use App\Service\Coach\ReleaseCoachService;
use App\Service\NotificationService;
use App\Tests\Mother\ClubMother;
use App\Tests\Mother\CoachMother;
use App\DTO\ReleaseCoachDTO;
use App\Exception\NotFoundException;
use App\Exception\FreeCoachException;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test del servicio ReleaseCoachService
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ReleaseCoachServiceTest extends TestCase
{
    private $service;
    private $em;
    private $coachRepository;
    private $notificationService;

    protected function setUp(): void
    {
        $this->em = Mockery::mock(EntityManagerInterface::class);
        $this->coachRepository = Mockery::mock(CoachRepository::class);
        $this->notificationService = Mockery::mock(NotificationService::class);
        $this->service = new ReleaseCoachService($this->em, $this->coachRepository, $this->notificationService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * Condiciones:
     * No existe el entrenador
     * Se espera: Una excepción NotFoundException
     */
    public function testReleaseCoachNotFound()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'coach'   => '1'
        ]));
        $dto = new ReleaseCoachDTO(json_decode($request->getContent(), true));
        $this->coachRepository->shouldReceive('find')->once()->andReturn(null);
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Entrenador no encontrado");
        $res = $this->service->execute($dto);
    }

    /**
     * Condiciones:
     * El entrenador esta libre
     * Se espera: Una excepción FreeCoachException
     */
    public function testReleaseCoachIsFree()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'coach'   => '1'
        ]));
        $dto = new ReleaseCoachDTO(json_decode($request->getContent(), true));
        $coach = CoachMother::create();
        $this->coachRepository->shouldReceive('find')->once()->andReturn($coach);
        $this->expectException(FreeCoachException::class);
        $this->expectExceptionMessage("Este entrenador ya está libre");
        $res = $this->service->execute($dto);
    }

    /**
     * Condiciones:
     * Falla al intentar usar la BD
     * Se espera: Una excepcion
     */
    public function testReleaseCoachErrorDB()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'coach'   => '1'
        ]));
        $dto = new ReleaseCoachDTO(json_decode($request->getContent(), true));
        $club = ClubMother::create();
        $coach = CoachMother::create(null, null, $club);
        $this->coachRepository->shouldReceive('find')->once()->andReturn($coach);
        $this->em->shouldReceive('persist')->once();
        $this->em->shouldReceive('flush')->once()->andThrow(new \Exception());

        $this->expectException(\Exception::class);
        $res = $this->service->execute($dto);
    }

    /**
     * Condiciones:
     * Todo OK
     * Se espera: 
     * Un mensaje con los datos del Coach liberado y de su exclub
     * Un true de la notificación
     */
    public function testReleaseCoachOk()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'coach'   => '1'
        ]));
        $dto = new ReleaseCoachDTO(json_decode($request->getContent(), true));
        $club = ClubMother::create();
        $coach = CoachMother::create(null, null, $club);
        $messageExpected = "El entrenador ".$coach->getName()." ".$coach->getSurname()." ha sido liberado del equipo ".$coach->getClub()->getName();

        $this->coachRepository->shouldReceive('find')->once()->andReturn($coach);
        $this->em->shouldReceive('persist')->once();
        $this->em->shouldReceive('flush')->once();
        $this->notificationService->shouldReceive('sendNotification')->once()->andReturn(true);

        [$resMessage, $resNotification] = $this->service->execute($dto);
        $this->assertEquals($messageExpected, $resMessage);
        $this->assertTrue($resNotification);
    }
}