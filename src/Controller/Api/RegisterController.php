<?php

namespace App\Controller\Api;

use App\Controller\Api\Request\RegisterRequest;
use App\Service\CacheService;
use App\Service\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use OpenApi\Annotations as OA;

class RegisterController extends AbstractController
{
    const CACHE_KEY_PREFIX = 'device_and_app_';
    const FIND_FOR_TTL = 60 * 60 * 24;

    /**
     * @OA\Post(
     *      path="/register",
     *      summary="Register a device",
     *      description="Registers a new device and returns a client token to be used for future API calls.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Registration data",
     *          @OA\JsonContent(
     *              @OA\Property(property="uid", type="string", example="abc123", description="The device uid"),
     *              @OA\Property(property="appId", type="integer", example="1", description="The app ID"),
     *              @OA\Property(property="os", type="string", example="ios", description="The operating system"),
     *              @OA\Property(property="language", type="string", example="en", description="The language"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="201",
     *          description="Client token created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="clientToken", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9"),
     *              @OA\Property(property="code", type="integer", example=201, description="The HTTP status code"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="400",
     *          description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Bad request"),
     *              @OA\Property(property="code", type="integer", example=400, description="The HTTP status code"),
     *          )
     *      ),
     * )
     */
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(
        RegisterRequest $request,
        CacheService $cacheService,
        RegisterService $registerService
    ): JsonResponse {
        try {
            $uid = $request->uid;
            $appId = $request->appId;
            $os = $request->os;
            $language = $request->language;

            $cacheKey = self::CACHE_KEY_PREFIX . $uid . '_' . $appId;
            $clientToken = $cacheService->getFromCache($cacheKey);

            if ($clientToken === null) {
                $clientToken = $registerService->processRegistration($uid, $appId, $os, $language);
            }

            $cacheService->saveCache($cacheKey, $clientToken, self::FIND_FOR_TTL);
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
