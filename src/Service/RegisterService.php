<?php

namespace App\Service;

use App\Controller\Api\Request\RegisterRequest;
use App\Entity\Subscription;
use Exception;
use Symfony\Contracts\Cache\CacheInterface;

class RegisterService
{
    const CACHE_KEY_PREFIX = 'device_and_app_';
    const FIND_FOR_TTL = 60 * 60 * 24;

    private CacheInterface $cache;
    private DeviceService $deviceService;
    private AppService $appService;
    private SubscriptionService $subscriptionService;

    public function __construct(
        CacheInterface $cache,
        DeviceService $deviceService,
        AppService $appService,
        SubscriptionService $subscriptionService
    ) {
        $this->cache = $cache;
        $this->deviceService = $deviceService;
        $this->appService = $appService;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * @param RegisterRequest $request
     * @return string
     * @throws Exception
     */
    public function processRegistration(RegisterRequest $request): string
    {
        $uid = $request->uid;
        $appId = $request->appId;

        $cacheKey = self::CACHE_KEY_PREFIX . $uid . '_' . $appId;

        $clientToken = $this->getCachedClientToken($cacheKey);

        if ($clientToken !== null) {
            return $clientToken;
        }

        $subscription = $this->subscribeDeviceAndApp($request);

        $clientToken = $subscription->getClientToken();

        $this->cacheClientToken($cacheKey, $clientToken);

        return $clientToken;
    }

    /**
     * @param string $cacheKey
     * @return string|null
     */
    private function getCachedClientToken(string $cacheKey): ?string
    {
        $item = $this->cache->getItem($cacheKey);
        if ($item->isHit()) {
            return $item->get();
        }
        return null;
    }

    /**
     * @param RegisterRequest $request
     * @return Subscription
     * @throws Exception
     */
    private function subscribeDeviceAndApp(RegisterRequest $request): Subscription
    {
        $uid = $request->uid;
        $appId = $request->appId;

        $device = $this->deviceService->checkDeviceExist($uid);

        if (!$device) {
            $device = $this->deviceService->createDevice($request);
        }

        $app = $this->appService->findAppById($appId);

        return $this->subscriptionService->subscribeDeviceAndApp($device, $app);
    }

    /**
     * @param string $cacheKey
     * @param string $clientToken
     */
    private function cacheClientToken(string $cacheKey, string $clientToken): void
    {
        $item = $this->cache->getItem($cacheKey);

        $item->set($clientToken);
        $item->expiresAfter(self::FIND_FOR_TTL);

        $this->cache->save($item);
    }
}