<?php

namespace App\Controller;

use App\Service\Club\CreateClubService;
use App\Service\Club\AddFootballerClubService;
use App\Service\Club\AddCoachClubService;
use App\Service\Club\ModifyBudgetClubService;
use App\Service\ValidationErrorsService;
use App\DTO\CreateClubDTO;
use App\DTO\ModifyBudgetClubDTO;
use App\DTO\AddFootballerClubDTO;
use App\DTO\AddCoachClubDTO;
use App\Exception\NotFoundException;
use App\Exception\ModifyBudgetException;
use App\Exception\PersonNotFreeException;
use App\Exception\AddPersonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Controlador para las peticiones que gestionan los clubes
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class ClubController extends AbstractController
{
    /**
     * Creación de un club
     */
    #[Route('/club/create', name: 'create_club', methods: ['POST'])]
    public function createClub(Request $request, CreateClubService $service, ValidatorInterface $validator, ValidationErrorsService $validationError): JsonResponse
    {
        $dto = new CreateClubDTO(json_decode($request->getContent(), true));
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse($validationError->format($errors), 400);
        }
        try {
            $club = $service->execute($dto);
            return new JsonResponse([
                'status' => 'success',
                'result' => 'El club se ha creado correctamente',
                'data'   => $club->toArray()
            ], Response::HTTP_CREATED);
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'result' => 'Error en la creación, contacte con el administrador'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Añadir un futbolista a un club
     */
    #[Route('/club/add/footballer', name: 'add_footballer', methods: ['PATCH'])]
    public function addFootballer(Request $request, AddFootballerClubService $service, ValidatorInterface $validator, ValidationErrorsService $validationError): JsonResponse
    {
        $dto = new AddFootballerClubDTO(json_decode($request->getContent(), true));
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse($validationError->format($errors), 400);
        }
        try {
            [$message, $notification] = $service->execute($dto);
            return new JsonResponse([
                'status' => 'success',
                'result' => $message,
                'notification' => $notification ? 'Enviada correctamente' : 'Error al enviar'
            ], Response::HTTP_OK);
        } catch (NotFoundException $e) {
            return $e->toJsonResponse();
        } catch (PersonNotFreeException $e) {
            return $e->toJsonResponse();
        } catch (AddPersonException $e) {
            return $e->toJsonResponse();
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'result' => 'Error en la inserción, contacte con el administrador'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Añadir un entrenador a un club
     */
    #[Route('/club/add/coach', name: 'add_coach', methods: ['PATCH'])]
    public function addCoach(Request $request, AddCoachClubService $service, ValidatorInterface $validator, ValidationErrorsService $validationError): JsonResponse
    {
        $dto = new AddCoachClubDTO(json_decode($request->getContent(), true));
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse($validationError->format($errors), 400);
        }
        try {
            [$message, $notification] = $service->execute($dto);
            return new JsonResponse([
                'status' => 'success',
                'result' => $message,
                'notification' => $notification ? 'Enviada correctamente' : 'Error al enviar'
            ], Response::HTTP_OK);
        } catch (NotFoundException $e) {
            return $e->toJsonResponse();
        } catch (PersonNotFreeException $e) {
            return $e->toJsonResponse();
        } catch (AddPersonException $e) {
            return $e->toJsonResponse();
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'result' => 'Error en la inserción, contacte con el administrador'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Modificación del presupuesto
     */
    #[Route('/club/modify/budget', name: 'modify_budget', methods: ['PATCH'])]
    public function modifyBudget(Request $request, ModifyBudgetClubService $service, ValidatorInterface $validator, ValidationErrorsService $validationError): JsonResponse
    {
        $dto = new ModifyBudgetClubDTO(json_decode($request->getContent(), true));
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse($validationError->format($errors), 400);
        }
        try {
            $club = $service->execute($dto);
            return new JsonResponse([
                'status' => 'success',
                'result' => 'El presupuesto del club se ha modificado correctamente',
                'data'   => $club->toArray()
            ], Response::HTTP_OK);
        } catch (NotFoundException $e) {
            return $e->toJsonResponse();
        } catch (ModifyBudgetException $e) {
            return $e->toJsonResponse();
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'result' => 'Error en la modificación, contacte con el administrador'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
