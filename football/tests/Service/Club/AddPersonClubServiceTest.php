<?php

namespace App\Tests\Services\Club;

use App\Repository\ClubRepository;
use App\Repository\PersonRepository;
use App\Service\Club\AddPersonClubService;
use App\Service\NotificationService;
use App\DTO\AddFootballerClubDTO;
use App\Exception\NotFoundException;
use App\Exception\PersonNotFreeException;
use App\Exception\AddPersonException;
use App\Tests\Mother\ClubMother;
use App\Tests\Mother\FootballerMother;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test del servicio AddPersonClubService
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class AddPersonClubServiceTest extends TestCase
{
    private $service;
    private $em;
    private $personRepository;
    private $clubRepository;
    private $notificationService;

    protected function setUp(): void
    {
        $this->em = Mockery::mock(EntityManagerInterface::class);
        $this->clubRepository = Mockery::mock(ClubRepository::class);
        $this->personRepository = Mockery::mock(PersonRepository::class);
        $this->notificationService = Mockery::mock(NotificationService::class);
        $this->service = new AddPersonClubService($this->em, $this->personRepository, $this->clubRepository, $this->notificationService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * Método: addPerson
     * Condiciones:
     * La persona no esta libre
     * Se espera: Una excepción PersonNotFreeException
     */
    public function testAddPersonNotFree()
    {
        $club = ClubMother::create();
        $person = FootballerMother::create(null, null, $club);
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => '1',
        ]));
        $dto = new AddFootballerClubDTO(json_decode($request->getContent(), true));

        $this->expectException(PersonNotFreeException::class);
        $this->expectExceptionMessage("Esta persona no está libre");
        $res = $this->service->execute($person, $dto);
    }

    /**
     * Método: addPerson
     * Condiciones:
     * No existe el club
     * Se espera: Una excepción NotFoundException
     */
    public function testAddPersonClubNotFound()
    {
        $person = FootballerMother::create();
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club'   => '1',
            'salary' => 10
        ]));
        $dto = new AddFootballerClubDTO(json_decode($request->getContent(), true));
        $this->clubRepository->shouldReceive('find')->once()->andReturn(null);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Club no encontrado");
        $res = $this->service->execute($person, $dto);
    }

    /**
     * Método: addPerson
     * Condiciones:
     * El presupuesto es menor
     * Se espera: Una excepción AddPersonException
     */
    public function testAddPersonBudgetIsLess()
    {
        $person = FootballerMother::create();
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club'   => '1',
            'salary' => 10
        ]));
        $dto = new AddFootballerClubDTO(json_decode($request->getContent(), true));
        $club = ClubMother::create(null, 100);
        $this->clubRepository->shouldReceive('find')->once()->andReturn($club);
        $this->personRepository->shouldReceive('getTotalSalariesByClub')->once()->andReturn(95);

        $this->expectException(AddPersonException::class);
        $this->expectExceptionMessage("Inserción rechazada, al añadir esta persona, la suma de los salarios sería mayor que el presupuesto: 95 + 10 > 100");
        $res = $this->service->execute($person, $dto);
    }

    /**
     * Método: addPerson
     * Condiciones:
     * Falla al intentar usar la BD
     * Se espera: Una excepción
     */
    public function testAddPersonErrorDB()
    {
        $person = FootballerMother::create();
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club'   => '1',
            'salary' => 10
        ]));
        $dto = new AddFootballerClubDTO(json_decode($request->getContent(), true));
        $club = ClubMother::create(null, 100);
        $this->clubRepository->shouldReceive('find')->once()->andReturn($club);
        $this->personRepository->shouldReceive('getTotalSalariesByClub')->once()->andReturn(90);
        $this->em->shouldReceive('persist')->once();
        $this->em->shouldReceive('flush')->once()->andThrow(new \Exception());

        $this->expectException(\Exception::class);
        $res = $this->service->execute($person, $dto);
    }

    /**
     * Método: addPerson
     * Condiciones:
     * Todo Ok
     * Se espera: 
     * Un mensaje con los datos del Person añadido y de su nuevo Club
     * Un true de la notificación
     */
    public function testAddPersonOk()
    {
        $person = FootballerMother::create();
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club'   => '1',
            'salary' => 10
        ]));
        $dto = new AddFootballerClubDTO(json_decode($request->getContent(), true));
        $club = ClubMother::create(null, 100);
        $this->clubRepository->shouldReceive('find')->once()->andReturn($club);
        $this->personRepository->shouldReceive('getTotalSalariesByClub')->once()->andReturn(90);
        $this->em->shouldReceive('persist')->once();
        $this->em->shouldReceive('flush')->once();
        $this->notificationService->shouldReceive('sendNotification')->once()->andReturn(true);
        [$resMessage, $resNotification] = $this->service->execute($person, $dto);
        $messageExpected = $person->getName()." ".$person->getSurname()." ha sido inscrito en el equipo ".$club->getName();
        $this->assertEquals($messageExpected, $resMessage);
        $this->assertTrue($resNotification);
    }

}