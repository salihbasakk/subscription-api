<?php

namespace App\Controller\Api;

use App\Controller\Api\Request\CheckSubscriptionRequest;
use App\Service\SubscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class SubscriptionController extends AbstractController
{
    #[Route('/check-subscription', name: 'check-subscription', methods: ['POST'])]
    public function register(CheckSubscriptionRequest $request, SubscriptionService $subscriptionService): JsonResponse
    {
        try {
            $subscription = $subscriptionService->getSubscriptionByClientToken($request);
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
            [
                'status' => $subscription->getStatus(),
                'expireDate' => $subscription->getExpireDate(),
                'code' => Response::HTTP_OK
            ],
            Response::HTTP_OK,
        );
    }
}
