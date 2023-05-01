<?php

namespace App\Service;

use App\Controller\Api\Request\RegisterRequest;
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
     * @param RegisterRequest $request
     * @return string
     * @throws Exception
     */
    public function processRegistration(RegisterRequest $request): string
    {
        $uid = $request->uid;
        $appId = $request->appId;

        $device = $this->deviceService->checkDeviceExist($uid);

        if (!$device) {
            $device = $this->deviceService->createDevice($request);
        }

        $app = $this->appService->findAppById($appId);

        $subscription = $this->subscriptionService->subscribeDeviceAndApp($device, $app);

        return $subscription->getClientToken();
    }
}