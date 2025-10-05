<?php

namespace App\Tests\Services\Footballer;

use App\Entity\Footballer;
use App\Service\Footballer\CreateFootballerService;
use App\DTO\CreatePersonDTO;
use App\Tests\Mother\FootballerMother;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test del servicio CreateFootballerService
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class CreateFootballerServiceTest extends TestCase
{
    private $service;
    private $em;

    protected function setUp(): void
    {
        $this->em = Mockery::mock(EntityManagerInterface::class);
        $this->service = new CreateFootballerService($this->em);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * Condiciones:
     * Falla al intentar usar la BD
     * Se espera: Una excepción
     */
    public function testCreateFootballerErrorDB()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Nombre',
            'surname' => 'Apellidos'
        ]));
        $dto = new CreatePersonDTO(json_decode($request->getContent(), true));
        $this->em->shouldReceive('persist')->once();
        $this->em->shouldReceive('flush')->once()->andThrow(new \Exception());

        $this->expectException(\Exception::class);
        $res = $this->service->execute($dto);
    }

    /**
     * Condiciones:
     * Todo OK
     * Se espera: Un Footballer con los datos pasados
     */
    public function testCreateFootballerOk()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Nombre',
            'surname' => 'Apellidos'
        ]));
        $dto = new CreatePersonDTO(json_decode($request->getContent(), true));
        $this->em->shouldReceive('persist')->once();
        $this->em->shouldReceive('flush')->once();
        $res = $this->service->execute($dto);
        $footballerExpected = FootballerMother::create('Nombre', 'Apellidos');
        $this->assertInstanceOf(Footballer::class, $res);
        $this->assertEquals($footballerExpected->getName(), $res->getName());
        $this->assertEquals($footballerExpected->getSurname(), $res->getSurname());
    }
}