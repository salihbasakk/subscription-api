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

class RegisterController extends AbstractController
{
    const CACHE_KEY_PREFIX = 'device_and_app_';
    const FIND_FOR_TTL = 60 * 60 * 24;

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
