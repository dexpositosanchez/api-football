<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class FootballerControllerTest extends WebTestCase
{   
    protected function setUp(): void {
        self::ensureKernelShutdown();
    }

    protected function tearDown(): void {}

    /**
     * Método: createFootballer
     * Condiciones:
     * No existe el campo name
     * Se espera: Un código de estado 400
     */
    public function testCreateFootballerNameNoExist()
    {
        $client = static::createClient();
        $client->request('POST', '/footballer/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
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
     * Método: createFootballer
     * Condiciones:
     * Existe el campo name, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testCreateFootballerNameEmpty()
    {
        $client = static::createClient();
        $client->request('POST', '/footballer/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
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
     * Método: createFootballer
     * Condiciones:
     * No existe el campo surname
     * Se espera: Un código de estado 400
     */
    public function testCreateFootballerSurnameNoExist()
    {
        $client = static::createClient();
        $client->request('POST', '/footballer/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
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
     * Método: createFootballer
     * Condiciones:
     * Existe el campo surname, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testCreateFootballerSurnameEmpty()
    {
        $client = static::createClient();
        $client->request('POST', '/footballer/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
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
     * Método: releaseFootballer
     * Condiciones:
     * No existe el campo footballer
     * Se espera: Un código de estado 400
     */
    public function testReleaseFootballerFootballerNoExist()
    {
        $client = static::createClient();
        $client->request('PATCH', '/footballer/release', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['footballer']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['footballer']);
    }

    /**
     * Método: releaseFootballer
     * Condiciones:
     * Existe el campo footballer, pero está vacío
     * Se espera: Un código de estado 400
     */
    public function testReleaseFootballerFootballerEmpty()
    {
        $client = static::createClient();
        $client->request('PATCH', '/footballer/release', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'footballer' => ''
        ]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['footballer']));
        $this->assertEquals('Este dato es obligatorio y no puede estar vacío', $res['errors']['footballer']);
    }

    /**
     * Método: clubFootballer
     * Condiciones:
     * En la url no se indica el club
     * Se espera: Un código de estado 400
     */
    public function testClubFootballerNoClub()
    {
        $client = static::createClient();
        $client->request('GET', '/footballer/club', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([]));
        $this->assertResponseStatusCodeSame(400);
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('error', $res['status']);
        $this->assertTrue(isset($res['errors']));
        $this->assertTrue(isset($res['errors']['id']));
        $this->assertEquals('No se ha recibido el club', $res['errors']['id']);
    }
}

