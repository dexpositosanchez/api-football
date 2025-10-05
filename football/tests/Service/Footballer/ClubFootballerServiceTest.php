<?php

namespace App\Tests\Services\Footballer;

use App\Entity\Footballer;
use App\Entity\Club;
use App\Repository\ClubRepository;
use App\Repository\FootballerRepository;
use App\Service\Footballer\ClubFootballerService;
use App\DTO\ClubFootballerDTO;
use App\Exception\NotFoundException;
use App\Tests\Mother\ClubMother;
use App\Tests\Mother\FootballerMother;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test del servicio ClubFootballerService
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ClubFootballerServiceTest extends TestCase
{
    private $service;
    private $footballerRepository;
    private $clubRepository;

    protected function setUp(): void
    {
        $this->footballerRepository = Mockery::mock(FootballerRepository::class);
        $this->clubRepository = Mockery::mock(ClubRepository::class);
        $this->service = new ClubFootballerService($this->footballerRepository, $this->clubRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * Condiciones:
     * El club no existe
     * Se espera: Una excepción NotFoundException
     */
    public function testClubFootballerClubNotFound()
    {
        $dto = new ClubFootballerDTO(1);
        $this->clubRepository->shouldReceive('find')->once()->andReturn(null);
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Club no encontrado");
        $res = $this->service->execute($dto, []);
    }

    /**
     * Condiciones:
     * Error en DB
     * Se espera: Una excepción
     */
    public function testClubFootballerErrorDB()
    {
        $dto = new ClubFootballerDTO(1);
        $club = ClubMother::create();
        $this->clubRepository->shouldReceive('find')->once()->andReturn($club);
        $this->footballerRepository->shouldReceive('findByClub')->once()->andThrow(new \Exception());

        $this->expectException(\Exception::class);
        $res = $this->service->execute($dto, []);
    }

    /**
     * Condiciones:
     * Todo Ok
     * Se espera: Un Club y un listado de Footballer
     */
    public function testClubFootballerOk()
    {
        $dto = new ClubFootballerDTO(1);
        $club = ClubMother::create();
        $list = [
            FootballerMother::create(null, null, $club),
            FootballerMother::create(null, null, $club),
            FootballerMother::create(null, null, $club)
        ];
        $this->clubRepository->shouldReceive('find')->once()->andReturn($club);
        $this->footballerRepository->shouldReceive('findByClub')->once()->andReturn($list);
        [$resClub, $resList] = $this->service->execute($dto, []);
        $this->assertInstanceOf(Club::class, $resClub);
        $this->assertEquals($club->getName(), $resClub->getName());
        $this->assertEquals($club->getBudget(), $resClub->getBudget());
        $this->assertEquals(count($list), count($resList));
        foreach ($resList as $i => $footballer) {
            $this->assertInstanceOf(Footballer::class, $footballer);
            $footballerExpected = $list[$i];
            $this->assertEquals($footballerExpected->getName(), $footballer->getName());
            $this->assertEquals($footballerExpected->getSurname(), $footballer->getSurname());
        }
    }
}