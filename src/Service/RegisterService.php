<?php

namespace App\Service;

use Exception;

class RegisterService
{
    private DeviceService $deviceService;
    private AppService $appService;
    private SubscriptionService $subscriptionService;

    public function __construct(
        DeviceService $deviceService,
        AppService $appService,
        SubscriptionService $subscriptionService
    ) {
        $this->deviceService = $deviceService;
        $this->appService = $appService;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * @param string $uid
     * @param int $appId
     * @param string $os
     * @param string $language
     * @return string
     * @throws Exception
     */
    public function processRegistration(string $uid, int $appId, string $os, string $language): string
    {
        $device = $this->deviceService->checkDeviceExist($uid);

        if (!$device) {
            $device = $this->deviceService->createDevice($uid, $os, $language);
        }

        $app = $this->appService->findAppById($appId);

        $subscription = $this->subscriptionService->subscribeDeviceAndApp($device, $app);

        return $subscription->getClientToken();
    }
}