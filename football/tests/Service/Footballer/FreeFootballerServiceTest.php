<?php

namespace App\Tests\Services\Footballer;

use App\Entity\Footballer;
use App\Repository\FootballerRepository;
use App\Service\Footballer\FreeFootballerService;
use App\Tests\Mother\FootballerMother;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test del servicio FreeFootballerService
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class FreeFootballerServiceTest extends TestCase
{
    private $service;
    private $footballerRepository;

    protected function setUp(): void
    {
        $this->footballerRepository = Mockery::mock(FootballerRepository::class);
        $this->service = new FreeFootballerService($this->footballerRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * Condiciones:
     * Error en DB
     * Se espera: Una excepción
     */
    public function testFreeFootballerErrorDB()
    {
        $this->footballerRepository->shouldReceive('findByClub')->once()->andThrow(new \Exception());

        $this->expectException(\Exception::class);
        $res = $this->service->execute([]);
    }

    /**
     * Condiciones:
     * Todo Ok
     * Se espera: Un listado de Footballer
     */
    public function testFreeFootballerOk()
    {
        $list = [
            FootballerMother::create(null, null, $club),
            FootballerMother::create(null, null, $club),
            FootballerMother::create(null, null, $club)
        ];
        $this->footballerRepository->shouldReceive('findByClub')->once()->andReturn($list);
        $res = $this->service->execute([]);
        $this->assertEquals(count($list), count($res));
        foreach ($res as $i => $footballer) {
            $this->assertInstanceOf(Footballer::class, $footballer);
            $footballerExpected = $list[$i];
            $this->assertEquals($footballerExpected->getName(), $footballer->getName());
            $this->assertEquals($footballerExpected->getSurname(), $footballer->getSurname());
        }
    }
}