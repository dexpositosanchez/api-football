<?php

namespace App\Tests\Services\Club;

use App\Entity\Club;
use App\Repository\FootballerRepository;
use App\Service\Club\AddFootballerClubService;
use App\Service\Club\AddPersonClubService;
use App\DTO\AddFootballerClubDTO;
use App\Exception\NotFoundException;
use App\Tests\Mother\FootballerMother;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test del servicio AddFootballerClubService
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class AddFootballerClubServiceTest extends TestCase
{
    private $service;
    private $footballerRepository;
    private $addPersonClubService;

    protected function setUp(): void
    {
        $this->footballerRepository = Mockery::mock(FootballerRepository::class); 
        $this->addPersonClubService = Mockery::mock(AddPersonClubService::class);
        $this->service = new AddFootballerClubService($this->footballerRepository, $this->addPersonClubService);
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
    public function testAddFootballerNotFound()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'footballer' => '1'
        ]));
        $dto = new AddFootballerClubDTO(json_decode($request->getContent(), true));
        $this->footballerRepository->shouldReceive('find')->once()->andReturn(null);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Futbolista no encontrado");
        $res = $this->service->execute($dto);
    }

    /**
     * Condiciones:
     * Existe el futbolista
     * Se espera: Un array
     */
    public function testAddFootballerOk()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'footballer' => '1'
        ]));
        $dto = new AddFootballerClubDTO(json_decode($request->getContent(), true));
        $footballer = FootballerMother::create();
        $this->footballerRepository->shouldReceive('find')->once()->andReturn($footballer);
        $this->addPersonClubService->shouldReceive('execute')->once()->andReturn([]);
        $res = $this->service->execute($dto);
        $this->assertIsArray($res);
    }
}