<?php

namespace App\Tests\Services\Footballer;

use App\Repository\FootballerRepository;
use App\Service\Footballer\ReleaseFootballerService;
use App\Service\NotificationService;
use App\DTO\ReleaseFootballerDTO;
use App\Exception\NotFoundException;
use App\Exception\FreeFootballerException;
use App\Tests\Mother\ClubMother;
use App\Tests\Mother\FootballerMother;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test del servicio ReleaseFootballerService
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ReleaseFootballerServiceTest extends TestCase
{
    private $service;
    private $em;
    private $footballerRepository;
    private $notificationService;

    protected function setUp(): void
    {
        $this->em = Mockery::mock(EntityManagerInterface::class);
        $this->footballerRepository = Mockery::mock(FootballerRepository::class);
        $this->notificationService = Mockery::mock(NotificationService::class);
        $this->service = new ReleaseFootballerService($this->em, $this->footballerRepository, $this->notificationService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * Condiciones:
     * No existe el futbolista
     * Se espera: Una excepción NotFoundException
     */
    public function testReleaseFootballerNotFound()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'footballer'   => '1'
        ]));
        $dto = new ReleaseFootballerDTO(json_decode($request->getContent(), true));
        $this->footballerRepository->shouldReceive('find')->once()->andReturn(null);
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Futbolista no encontrado");
        $res = $this->service->execute($dto);
    }

    /**
     * Condiciones:
     * El futbolista esta libre
     * Se espera: Una excepción FreeFootballerException
     */
    public function testReleaseFootballerIsFree()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'footballer'   => '1'
        ]));
        $dto = new ReleaseFootballerDTO(json_decode($request->getContent(), true));
        $footballer = FootballerMother::create();
        $this->footballerRepository->shouldReceive('find')->once()->andReturn($footballer);
        $this->expectException(FreeFootballerException::class);
        $this->expectExceptionMessage("Este futbolista ya está libre");
        $res = $this->service->execute($dto);
    }

    /**
     * Condiciones:
     * Falla al intentar usar la BD
     * Se espera: Una excepción
     */
    public function testReleaseFootballerErrorDB()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'footballer'   => '1'
        ]));
        $dto = new ReleaseFootballerDTO(json_decode($request->getContent(), true));
        $club = ClubMother::create();
        $footballer = FootballerMother::create(null, null, $club);
        $this->footballerRepository->shouldReceive('find')->once()->andReturn($footballer);
        $this->em->shouldReceive('persist')->once();
        $this->em->shouldReceive('flush')->once()->andThrow(new \Exception());

        $this->expectException(\Exception::class);
        $res = $this->service->execute($dto);
    }

    /**
     * Condiciones:
     * Todo OK
     * Se espera: 
     * Un mensaje con los datos del Footballer liberado y de su exclub
     * Un true de la notificación
     */
    public function testReleaseFootballerOk()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'footballer'   => '1'
        ]));
        $dto = new ReleaseFootballerDTO(json_decode($request->getContent(), true));
        $club = ClubMother::create();
        $footballer = FootballerMother::create(null, null, $club);
        $messageExpected = "El futbolista ".$footballer->getName()." ".$footballer->getSurname()." ha sido liberado del equipo ".$footballer->getClub()->getName();

        $this->footballerRepository->shouldReceive('find')->once()->andReturn($footballer);
        $this->em->shouldReceive('persist')->once();
        $this->em->shouldReceive('flush')->once();
        $this->notificationService->shouldReceive('sendNotification')->once()->andReturn(true);

        [$resMessage, $resNotification] = $this->service->execute($dto);
        $this->assertEquals($messageExpected, $resMessage);
        $this->assertTrue($resNotification);
    }
}