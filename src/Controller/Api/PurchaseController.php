<?php

namespace App\Controller\Api;

use App\Controller\Api\Request\PurchaseRequest;

use App\Service\PurchaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class PurchaseController extends AbstractController
{
    #[Route('/purchase', name: 'purchase', methods: ['POST'])]
    public function purchase(PurchaseRequest $request, PurchaseService $purchaseService): JsonResponse
    {
        try {
            $purchase = $purchaseService->purchase($request->clientToken, $request->clientToken);
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
                'status' => $purchase->getStatus(),
                'purchaseStatus' => $purchase->getPurchaseStatus(),
                'expireDate' => $purchase->getExpireDate(),
                'code' => Response::HTTP_OK
            ],
            Response::HTTP_OK,
        );
    }
}
