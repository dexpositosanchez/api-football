<?php

namespace App\Tests\Services\Coach;

use App\Entity\Coach;
use App\Service\Coach\CreateCoachService;
use App\DTO\CreatePersonDTO;
use App\Tests\Mother\CoachMother;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test del servicio CreateCoachService
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class CreateCoachServiceTest extends TestCase
{
    private $service;
    private $em;

    protected function setUp(): void
    {
        $this->em = Mockery::mock(EntityManagerInterface::class);
        $this->service = new CreateCoachService($this->em);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * Condiciones:
     * Falla al intentar usar la BD
     * Se espera: Una excepcion
     */
    public function testCreateCoachErrorDB()
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
     * Se espera: Un Coach con los datos pasados
     */
    public function testCreateCoachOk()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Nombre',
            'surname' => 'Apellidos'
        ]));
        $dto = new CreatePersonDTO(json_decode($request->getContent(), true));
        $this->em->shouldReceive('persist')->once();
        $this->em->shouldReceive('flush')->once();
        $res = $this->service->execute($dto);
        $coachExpected = CoachMother::create('Nombre', 'Apellidos');
        $this->assertInstanceOf(Coach::class, $res);
        $this->assertEquals($coachExpected->getName(), $res->getName());
        $this->assertEquals($coachExpected->getSurname(), $res->getSurname());
    }
}