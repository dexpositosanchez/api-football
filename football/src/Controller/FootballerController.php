<?php

namespace App\Controller;

use App\Service\Footballer\CreateFootballerService;
use App\Service\Footballer\ReleaseFootballerService;
use App\Service\Footballer\ClubFootballerService;
use App\Service\Footballer\FreeFootballerService;
use App\Service\ValidationErrorsService;
use App\DTO\CreatePersonDTO;
use App\DTO\ClubFootballerDTO;
use App\DTO\ReleaseFootballerDTO;
use App\Exception\NotFoundException;
use App\Exception\FreeFootballerException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Controlador para las peticiones que gestionan los futbolistas
 * @author David Expósito <dexpositosanchez@gmail.com>
 */
class FootballerController extends AbstractController
{
    /**
     * Creación de un futbolista
     */
    #[Route('/footballer/create', name: 'create_footballer', methods: ['POST'])]
    public function createFootballer(Request $request, CreateFootballerService $service, ValidatorInterface $validator, ValidationErrorsService $validationError): JsonResponse
    {
        $dto = new CreatePersonDTO(json_decode($request->getContent(), true));
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse($validationError->format($errors), 400);
        }

        try {
            $footballer = $service->execute($dto);
            return new JsonResponse([
                'status' => 'success',
                'result' => 'El futbolista se ha creado correctamente',
                'data'   => $footballer->toArray()
            ], Response::HTTP_CREATED);
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'result' => 'Error en la creación, contacte con el administrador'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Dejar a un futbolista como libre
     */
    #[Route('/footballer/release', name: 'release_footballer', methods: ['PATCH'])]
    public function releaseFootballer(Request $request, ReleaseFootballerService $service, ValidatorInterface $validator, ValidationErrorsService $validationError): JsonResponse
    {
        $dto = new ReleaseFootballerDTO(json_decode($request->getContent(), true));
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
        } catch (FreeFootballerException $e) {
            return $e->toJsonResponse();
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'result' => 'Error en la edición, contacte con el administrador'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Obtención del listado de futbolistas de un club
     */
    #[Route('/footballer/club/{id?}', name: 'club_footballer', methods: ['GET'])]
    public function clubFootballer(Request $request, ?int $id, ClubFootballerService $service, ValidatorInterface $validator, ValidationErrorsService $validationError): JsonResponse
    {
        $dto = new ClubFootballerDTO($id);
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse($validationError->format($errors), 400);
        }
        
        try {
            [$club, $footballers] = $service->execute($dto, $request->query->all());
            return new JsonResponse([
                'status' => 'success',
                'result' => 'Futbolistas del equipo '.$club->getName(),
                'list'   => array_map(function($footballer) {
                        return $footballer->toArray();
                        }, $footballers)
            ], Response::HTTP_OK);
        } catch (NotFoundException $e) {
            return $e->toJsonResponse();
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'result' => 'Error en la gestión, contacte con el administrador'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Obtención del listado de futbolistas libres
     */
    #[Route('/footballer/free', name: 'free_footballer', methods: ['GET'])]
    public function freeFootballer(Request $request, FreeFootballerService $service): JsonResponse
    {
        try {
            $footballers = $service->execute($request->query->all());
            return new JsonResponse([
                'status' => 'success',
                'result' => 'Futbolistas libres',
                'list'   => array_map(function($footballer) {
                        return $footballer->toArray();
                        }, $footballers)
            ], Response::HTTP_OK);
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'result' => 'Error en la gestión, contacte con el administrador'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
