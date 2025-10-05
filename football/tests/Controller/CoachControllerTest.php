<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class CoachControllerTest extends WebTestCase
{   
    protected function setUp(): void {
        self::ensureKernelShutdown();
    }

    protected function tearDown(): void {}

    /**
     * Método: createCoach
     * Condiciones:
     * No existe el campo name
     * Se espera: Un código de estado 400
     */
    public function testCreateCoachNameNoExist()
    {
        $client = static::createClient();
        $client->request('POST', '/coach/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'surname' => 'Apellidos'
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['name']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['name']);
    }

    /**
     * Método: createCoach
     * Condiciones:
     * Existe el campo name, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testCreateCoachNameEmpty()
    {
        $client = static::createClient();
        $client->request('POST', '/coach/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => '',
            'surname' => 'Apellidos'
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['name']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['name']);
    }

    /**
     * Método: createCoach
     * Condiciones:
     * No existe el campo surname
     * Se espera: Un código de estado 400
     */
    public function testCreateCoachSurnameNoExist()
    {
        $client = static::createClient();
        $client->request('POST', '/coach/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Nombre'
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['surname']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['surname']);
    }

    /**
     * Método: createCoach
     * Condiciones:
     * Existe el campo surname, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testCreateCoachSurnameEmpty()
    {
        $client = static::createClient();
        $client->request('POST', '/coach/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Nombre',
            'surname' => ''
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['surname']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['surname']);
    }

    /**
     * Método: releaseCoach
     * Condiciones:
     * No existe el campo coach
     * Se espera: Un código de estado 400
     */
    public function testReleaseCoachCoachNoExist()
    {
        $client = static::createClient();
        $client->request('PATCH', '/coach/release', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['coach']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['coach']);
    }

    /**
     * Método: releaseCoach
     * Condiciones:
     * Existe el campo coach, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testReleaseCoachCoachEmpty()
    {
        $client = static::createClient();
        $client->request('PATCH', '/coach/release', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'coach' => ''
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['coach']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['coach']);
    }
}

