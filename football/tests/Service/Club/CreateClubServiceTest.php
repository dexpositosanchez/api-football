<?php

namespace App\Tests\Services\Club;

use App\Entity\Club;
use App\Service\Club\CreateClubService;
use App\DTO\CreateClubDTO;
use App\Tests\Mother\ClubMother;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test del servicio CreateClubService
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class CreateClubServiceTest extends TestCase
{
    private $service;
    private $em;

    protected function setUp(): void
    {
        $this->em = Mockery::mock(EntityManagerInterface::class);
        $this->service = new CreateClubService($this->em);
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
    public function testCreateClubErrorDB()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Nombre',
            'budget' => '100'
        ]));
        $dto = new CreateClubDTO(json_decode($request->getContent(), true));
        $this->em->shouldReceive('persist')->once();
        $this->em->shouldReceive('flush')->once()->andThrow(new \Exception());

        $this->expectException(\Exception::class);
        $res = $this->service->execute($dto);
    }

    /**
     * Condiciones:
     * Todo OK
     * Se espera: Un Club con los datos pasados
     */
    public function testCreateClubOk()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Nombre',
            'budget' => '100'
        ]));
        $dto = new CreateClubDTO(json_decode($request->getContent(), true));
        $this->em->shouldReceive('persist')->once();
        $this->em->shouldReceive('flush')->once();
        $res = $this->service->execute($dto);
        $clubExpected = ClubMother::create('Nombre', '100');
        $this->assertInstanceOf(Club::class, $res);
        $this->assertEquals($clubExpected->getName(), $res->getName());
        $this->assertEquals($clubExpected->getBudget(), $res->getBudget());
    }
}