<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class ClubControllerTest extends WebTestCase
{   
    protected function setUp(): void {
        self::ensureKernelShutdown();
    }

    protected function tearDown(): void {}

    /**
     * Método: createClub
     * Condiciones:
     * No existe el campo name
     * Se espera: Un código de estado 400
     */
    public function testCreateClubNameNoExist()
    {
        $client = static::createClient();
        $client->request('POST', '/club/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'budget' => '100'
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['name']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['name']);
    }

    /**
     * Método: createClub
     * Condiciones:
     * Existe el campo name, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testCreateClubNameEmpty()
    {
        $client = static::createClient();
        $client->request('POST', '/club/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => '',
            'budget' => '100'
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['name']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['name']);
    }

    /**
     * Método: createClub
     * Condiciones:
     * No existe el campo budget
     * Se espera: Un código de estado 400
     */
    public function testCreateClubBudgetNoExist()
    {
        $client = static::createClient();
        $client->request('POST', '/club/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Nombre'
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['budget']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['budget']);
    }

    /**
     * Método: createClub
     * Condiciones:
     * Existe el campo budget, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testCreateClubBudgetEmpty()
    {
        $client = static::createClient();
        $client->request('POST', '/club/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Nombre',
            'budget' => ''
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['budget']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['budget']);
    }

    /**
     * Método: createClub
     * Condiciones:
     * Existe el campo budget, pero no es numérico
     * Se espera: Un código de estado 400
     */
    public function testCreateClubBudgetNotNumeric()
    {
        $client = static::createClient();
        $client->request('POST', '/club/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Nombre',
            'budget' => 'Presupuesto'
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['budget']));
        $this->assertEquals('Este dato debe ser un número', $res['errors']['budget']);
    }

    /**
     * Método: createClub
     * Condiciones:
     * Existe el campo budget, es numérico pero es negativo
     * Se espera: Un código de estado 400
     */
    public function testCreateClubBudgetNumericNegative()
    {
        $client = static::createClient();
        $client->request('POST', '/club/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Nombre',
            'budget' => '-50'
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['budget']));
        $this->assertEquals('Este dato no puede ser negativo', $res['errors']['budget']);
    }

    /**
     * Método: addFootballer
     * Condiciones:
     * No existe el campo footballer
     * Se espera: Un código de estado 400
     */
    public function testAddFootballerFootballerNoExist()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/footballer', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'salary' => 100
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['footballer']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['footballer']);
    }

    /**
     * Método: addFootballer
     * Condiciones:
     * Existe el campo footballer, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testAddFootballerFootballerEmpty()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/footballer', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'footballer' => '',
            'salary' => 100
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['footballer']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['footballer']);
    }

    /**
     * Método: addFootballer
     * Condiciones:
     * No existe el campo club
     * Se espera: Un código de estado 400
     */
    public function testAddFootballerClubNoExist()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/footballer', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'footballer' => 1,
            'salary' => 100
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['club']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['club']);
    }

    /**
     * Método: addFootballer
     * Condiciones:
     * Existe el campo club, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testAddFootballerClubEmpty()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/footballer', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => '',
            'footballer' => 1,
            'salary' => 100
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['club']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['club']);
    }

    /**
     * Método: addFootballer
     * Condiciones:
     * No existe el campo salary
     * Se espera: Un código de estado 400
     */
    public function testAddFootballerSalaryNoExist()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/footballer', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'footballer' => 1
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['salary']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['salary']);
    }

    /**
     * Método: addFootballer
     * Condiciones:
     * Existe el campo salary, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testAddFootballerSalaryEmpty()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/footballer', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'footballer' => 1,
            'salary' => ''
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['salary']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['salary']);
    }

    /**
     * Método: addFootballer
     * Condiciones:
     * Existe el campo salary, pero no es numérico
     * Se espera: Un código de estado 400
     */
    public function testAddFootballerSalaryNotNumeric()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/footballer', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'footballer' => 1,
            'salary' => 'Salario'
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['salary']));
        $this->assertEquals('Este dato debe ser un número', $res['errors']['salary']);
    }

    /**
     * Método: addFootballer
     * Condiciones:
     * Existe el campo salary, pero es negativo
     * Se espera: Un código de estado 400
     */
    public function testAddFootballerSalaryNumericNegative()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/footballer', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'footballer' => 1,
            'salary' => -100
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['salary']));
        $this->assertEquals('Este dato no puede ser negativo', $res['errors']['salary']);
    }

    /**
     * Método: addCoach
     * Condiciones:
     * No existe el campo coach
     * Se espera: Un código de estado 400
     */
    public function testAddCoachCoachNoExist()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/coach', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'salary' => 100
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['coach']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['coach']);
    }

    /**
     * Método: addCoach
     * Condiciones:
     * Existe el campo coach, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testAddCoachCoachEmpty()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/coach', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'coach' => '',
            'salary' => 100
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['coach']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['coach']);
    }

    /**
     * Método: addCoach
     * Condiciones:
     * No existe el campo club
     * Se espera: Un código de estado 400
     */
    public function testAddCoachClubNoExist()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/coach', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'coach' => 1,
            'salary' => 100
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['club']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['club']);
    }

    /**
     * Método: addCoach
     * Condiciones:
     * Existe el campo club, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testAddCoachClubEmpty()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/coach', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => '',
            'coach' => 1,
            'salary' => 100
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['club']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['club']);
    }

    /**
     * Método: addCoach
     * Condiciones:
     * No existe el campo salary
     * Se espera: Un código de estado 400
     */
    public function testAddCoachSalaryNoExist()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/coach', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'coach' => 1
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['salary']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['salary']);
    }

    /**
     * Método: addCoach
     * Condiciones:
     * Existe el campo salary, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testAddCoachSalaryEmpty()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/coach', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'coach' => 1,
            'salary' => ''
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['salary']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['salary']);
    }

    /**
     * Método: addCoach
     * Condiciones:
     * Existe el campo salary, pero no es numérico
     * Se espera: Un código de estado 400
     */
    public function testAddCoachSalaryNotNumeric()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/coach', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'coach' => 1,
            'salary' => 'Salario'
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['salary']));
        $this->assertEquals('Este dato debe ser un número', $res['errors']['salary']);
    }

    /**
     * Método: addCoach
     * Condiciones:
     * Existe el campo salary, pero es negativo
     * Se espera: Un código de estado 400
     */
    public function testAddCoachSalaryNumericNegative()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/add/coach', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'coach' => 1,
            'salary' => -100
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['salary']));
        $this->assertEquals('Este dato no puede ser negativo', $res['errors']['salary']);
    }

    /**
     * Método: modifyBudget
     * Condiciones:
     * No existe el campo budget
     * Se espera: Un código de estado 400
     */
    public function testModifyBudgetBudgetNoExist()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/modify/budget', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['budget']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['budget']);
    }

    /**
     * Método: modifyBudget
     * Condiciones:
     * Existe el campo budget, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testModifyBudgetBudgetEmpty()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/modify/budget', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'budget' => ''
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['budget']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['budget']);
    }

    /**
     * Método: modifyBudget
     * Condiciones:
     * Existe el campo budget, pero no es numérico
     * Se espera: Un código de estado 400
     */
    public function testModifyBudgetBudgetNotNumeric()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/modify/budget', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'budget' => 'Presupuesto'
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['budget']));
        $this->assertEquals('Este dato debe ser un número', $res['errors']['budget']);
    }

    /**
     * Método: modifyBudget
     * Condiciones:
     * Existe el campo budget, pero es negativo
     * Se espera: Un código de estado 400
     */
    public function testModifyBudgetBudgetNumericNegative()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/modify/budget', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => 1,
            'budget' => -1000
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['budget']));
        $this->assertEquals('Este dato no puede ser negativo', $res['errors']['budget']);
    }

    /**
     * Método: modifyBudget
     * Condiciones:
     * No existe el campo club
     * Se espera: Un código de estado 400
     */
    public function testModifyBudgetClubNoExist()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/modify/budget', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'budget' => 1000
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['club']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['club']);
    }

    /**
     * Método: modifyBudget
     * Condiciones:
     * Existe el campo club, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testModifyBudgetClubEmpty()
    {
        $client = static::createClient();
        $client->request('PATCH', '/club/modify/budget', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'club' => '',
            'budget' => 1000
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['club']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['club']);
    }
}

