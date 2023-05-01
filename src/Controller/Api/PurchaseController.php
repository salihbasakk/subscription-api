<?php

namespace App\Controller\Api;

use App\Controller\Api\Request\PurchaseRequest;

use App\Service\PurchaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use OpenApi\Annotations as OA;

class PurchaseController extends AbstractController
{
    /**
     * @OA\Post(
     *      path="/purchase",
     *      summary="Purchase",
     *      tags={"Purchase"},
     *      description="Purchase a product by providing the client token and receipt",
     *      @OA\RequestBody(
     *          description="Purchase data",
     *          @OA\JsonContent(
     *              required={"clientToken", "receipt"},
     *              @OA\Property(property="clientToken", type="string", example="abc123"),
     *              @OA\Property(property="receipt", type="string", example="xyz456"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful purchase",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="bool", example=true),
     *              @OA\Property(property="purchaseStatus", type="string", example="approved"),
     *              @OA\Property(property="expireDate", type="datetime"),
     *              @OA\Property(property="code", type="integer", example=200),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Invalid client token"),
     *              @OA\Property(property="code", type="integer", example=400),
     *          )
     *      )
     * )
     */
    #[Route('/purchase', name: 'purchase', methods: ['POST'])]
    public function purchase(PurchaseRequest $request, PurchaseService $purchaseService): JsonResponse
    {
        try {
            $purchase = $purchaseService->purchase($request->clientToken, $request->receipt);
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
