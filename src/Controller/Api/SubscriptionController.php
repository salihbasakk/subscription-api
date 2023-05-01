<?php

namespace App\Controller\Api;

use App\Controller\Api\Request\CheckSubscriptionRequest;
use App\Service\SubscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use OpenApi\Annotations as OA;

class SubscriptionController extends AbstractController
{
    /**
     * @OA\Post(
     *      path="/check-subscription",
     *      summary="Check Subscription",
     *      description="Check if the clientToken is subscribed and get the expire date of the subscription",
     *      operationId="checkSubscription",
     *      tags={"Subscription"},
     *      @OA\RequestBody(
     *          description="The clientToken",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="clientToken",
     *                  type="string",
     *                  example="abc123..."
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Returns the subscription status and expire date",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="status",
     *                  type="string",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="expireDate",
     *                  type="datetime",
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example=200
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Returns the error message and error code",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Invalid client token"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example=400
     *              ),
     *          ),
     *      ),
     * )
     */
    #[Route('/check-subscription', name: 'check-subscription', methods: ['POST'])]
    public function register(CheckSubscriptionRequest $request, SubscriptionService $subscriptionService): JsonResponse
    {
        try {
            $subscription = $subscriptionService->getSubscriptionByClientToken($request->clientToken);
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
