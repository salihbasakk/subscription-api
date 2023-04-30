<?php

namespace App\Controller\Api;

use App\Controller\Api\Request\RegisterRequest;
use App\Service\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(RegisterRequest $request, RegisterService $registerService): JsonResponse
    {
        try {
            $clientToken = $registerService->processRegistration($request);
        } catch (Throwable $exception) {
            return $this->json(
                [
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return $this->json(
            ['clientToken' => $clientToken, 'code' => Response::HTTP_CREATED],
            Response::HTTP_CREATED,
        );
    }
}
