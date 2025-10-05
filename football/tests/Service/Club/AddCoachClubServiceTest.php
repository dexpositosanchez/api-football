<?php

namespace App\Tests\Services;

use App\Entity\Club;
use App\Repository\CoachRepository;
use App\Service\Club\AddCoachClubService;
use App\Service\Club\AddPersonClubService;
use App\DTO\AddCoachClubDTO;
use App\Exception\NotFoundException;
use App\Tests\Mother\CoachMother;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test del servicio AddCoachClubService
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class AddCoachClubServiceTest extends TestCase
{
    private $service;
    private $coachRepository;
    private $addPersonClubService;

    protected function setUp(): void
    {
        $this->coachRepository = Mockery::mock(CoachRepository::class);
        $this->addPersonClubService = Mockery::mock(AddPersonClubService::class);
        $this->service = new AddCoachClubService($this->coachRepository, $this->addPersonClubService);
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
    public function testAddCoachNotFound()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'coach' => '1'
        ]));
        $dto = new AddCoachClubDTO(json_decode($request->getContent(), true));
        $this->coachRepository->shouldReceive('find')->once()->andReturn(null);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Entrenador no encontrado");
        $res = $this->service->execute($dto);
    }

    /**
     * Método: addCoach
     * Condiciones:
     * Existe el entrenador
     * Se espera: Un array
     */
    public function testAddCoachOk()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'coach' => '1',
        ]));
        $dto = new AddCoachClubDTO(json_decode($request->getContent(), true));
        $coach = CoachMother::create();
        $this->coachRepository->shouldReceive('find')->once()->andReturn($coach);
        $this->addPersonClubService->shouldReceive('execute')->once()->andReturn([]);
        $res = $this->service->execute($dto);
        $this->assertIsArray($res);
    }
}