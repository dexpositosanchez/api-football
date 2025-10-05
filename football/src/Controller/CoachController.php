<?php

namespace App\Controller;

use App\Service\Coach\CreateCoachService;
use App\Service\Coach\ReleaseCoachService;
use App\Service\ValidationErrorsService;
use App\DTO\CreatePersonDTO;
use App\DTO\ReleaseCoachDTO;
use App\Exception\NotFoundException;
use App\Exception\FreeCoachException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Controlador para las peticiones que gestionan los entrenadores
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class CoachController extends AbstractController
{
    /**
     * Creación de un entrenador
     */
    #[Route('/coach/create', name: 'create_coach', methods: ['POST'])]
    public function createCoach(Request $request, CreateCoachService $service, ValidatorInterface $validator, ValidationErrorsService $validationError): JsonResponse
    {
        $dto = new CreatePersonDTO(json_decode($request->getContent(), true));
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            return new JsonResponse($validationError->format($errors), 400);
        }

        try {
            $coach = $service->execute($dto);
            return new JsonResponse([
                'status' => 'success',
                'result' => 'El entrenador se ha creado correctamente',
                'data'   => $coach->toArray()
            ], Response::HTTP_CREATED);
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'result' => 'Error en la creación, contacte con el administrador'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Dejar a un entrenador como libre
     */
    #[Route('/coach/release', name: 'release_coach', methods: ['PATCH'])]
    public function releaseCoach(Request $request, ReleaseCoachService $service, ValidatorInterface $validator, ValidationErrorsService $validationError): JsonResponse
    {
        $dto = new ReleaseCoachDTO(json_decode($request->getContent(), true));
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
        } catch (FreeCoachException $e) {
            return $e->toJsonResponse();
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'result' => 'Error en la edición, contacte con el administrador'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
