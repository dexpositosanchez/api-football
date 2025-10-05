<?php

namespace App\Tests\Services\Club;

use App\Entity\Club;
use App\Repository\ClubRepository;
use App\Repository\PersonRepository;
use App\Service\Club\ModifyBudgetClubService;
use App\DTO\ModifyBudgetClubDTO;
use App\Exception\NotFoundException;
use App\Exception\ModifyBudgetException;
use App\Tests\Mother\ClubMother;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test del servicio ModifyBudgetClubService
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ModifyBudgetClubServiceTest extends TestCase
{
    private $service;
    private $em;
    private $personRepository;
    private $clubRepository;

    protected function setUp(): void
    {
        $this->em = Mockery::mock(EntityManagerInterface::class);
        $this->clubRepository = Mockery::mock(ClubRepository::class);
        $this->personRepository = Mockery::mock(PersonRepository::class);
        $this->service = new ModifyBudgetClubService($this->em, $this->personRepository, $this->clubRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * Condiciones:
     * No existe el club
     * Se espera: Una excepción NotFoundException
     */
    public function testModifyBudgetClubNotFound()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'budget' => '100',
            'club'   => '1'
        ]));
        $dto = new ModifyBudgetClubDTO(json_decode($request->getContent(), true));
        $this->clubRepository->shouldReceive('find')->once()->andReturn(null);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Club no encontrado");
        $res = $this->service->execute($dto);
    }

    /**
     * Condiciones:
     * El nuevo presupuesto es menor
     * Se espera: Una excepción ModifyBudgetException
     */
    public function testModifyBudgetBudgetIsLess()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'budget' => '80',
            'club'   => '1'
        ]));
        $dto = new ModifyBudgetClubDTO(json_decode($request->getContent(), true));
        $club = ClubMother::create(null, 100);
        $this->clubRepository->shouldReceive('find')->once()->andReturn($club);
        $this->personRepository->shouldReceive('getTotalSalariesByClub')->once()->andReturn(95);

        $this->expectException(ModifyBudgetException::class);
        $this->expectExceptionMessage("Modificación rechazada, los salarios actuales es mayor que el nuevo presupuesto: 95 > 80");
        $res = $this->service->execute($dto);
    }

    /**
     * Condiciones:
     * Falla al intentar usar la BD
     * Se espera: Una excepción
     */
    public function testModifyBudgetErrorDB()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'budget' => '120',
            'club'   => '1'
        ]));
        $dto = new ModifyBudgetClubDTO(json_decode($request->getContent(), true));
        $club = ClubMother::create(null, 100);
        $this->clubRepository->shouldReceive('find')->once()->andReturn($club);
        $this->personRepository->shouldReceive('getTotalSalariesByClub')->once()->andReturn(95);
        $this->em->shouldReceive('persist')->once();
        $this->em->shouldReceive('flush')->once()->andThrow(new \Exception());

        $this->expectException(\Exception::class);
        $res = $this->service->execute($dto);
    }

    /**
     * Condiciones:
     * Todo Ok
     * Se espera: Un Club
     */
    public function testModifyBudgetOk()
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'budget' => '120',
            'club'   => '1'
        ]));
        $dto = new ModifyBudgetClubDTO(json_decode($request->getContent(), true));
        $club = ClubMother::create(null, 100);
        $this->clubRepository->shouldReceive('find')->once()->andReturn($club);
        $this->personRepository->shouldReceive('getTotalSalariesByClub')->once()->andReturn(95);
        $this->em->shouldReceive('persist')->once();
        $this->em->shouldReceive('flush')->once();
        $res = $this->service->execute($dto);
        $this->assertInstanceOf(Club::class, $res);
        $this->assertEquals($club->getName(), $res->getName());
        $this->assertEquals(120, $res->getBudget());
    }

}