<?php

namespace App\Tests\Service;

use App\Entity\Language;
use App\Entity\OperatingSystem;
use App\Service\RegisterService;
use App\Service\DeviceService;
use App\Service\AppService;
use App\Service\SubscriptionService;
use App\Entity\Device;
use App\Entity\App;
use App\Entity\Subscription;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RegisterServiceTest extends TestCase
{
    private MockObject $deviceServiceMock;
    private MockObject $appServiceMock;
    private MockObject $subscriptionServiceMock;
    private RegisterService $registerService;

    public function setUp(): void
    {
        $this->deviceServiceMock = $this->createMock(DeviceService::class);
        $this->appServiceMock = $this->createMock(AppService::class);
        $this->subscriptionServiceMock = $this->createMock(SubscriptionService::class);

        $this->registerService = new RegisterService(
            $this->deviceServiceMock,
            $this->appServiceMock,
            $this->subscriptionServiceMock
        );
    }

    public function testProcessRegistrationReturnsToken()
    {
        $uid = 'test-device-uid';
        $appId = 1;
        $osName = 'ios';
        $languageCode = 'en';

        $this->deviceServiceMock->expects($this->once())
            ->method('checkDeviceExist')
            ->with($uid)
            ->willReturn(null);

        $os = new OperatingSystem();
        $os->setName($osName);

        $language = new Language();
        $language->setCode($languageCode);

        $device = new Device();
        $device->setUid($uid);
        $device->setOs($os);
        $device->setLanguage($language);

        $this->deviceServiceMock->expects($this->once())
            ->method('createDevice')
            ->with($uid, $osName, $languageCode)
            ->willReturn($device);

        $app = new App();

        $this->appServiceMock->expects($this->once())
            ->method('findAppById')
            ->with($appId)
            ->willReturn($app);

        $subscription = new Subscription();
        $subscription->setClientToken('test-client-token');

        $this->subscriptionServiceMock->expects($this->once())
            ->method('subscribeDeviceAndApp')
            ->with($device, $app)
            ->willReturn($subscription);

        $result = $this->registerService->processRegistration($uid, $appId, $osName, $languageCode);

        $this->assertEquals($subscription->getClientToken(), $result);
    }

    public function testProcessRegistrationWithExistingDevice()
    {
        $uid = 'test-device-uid';
        $appId = 1;
        $osName = 'ios';
        $languageCode = 'en';

        $os = new OperatingSystem();
        $os->setName($osName);

        $language = new Language();
        $language->setCode($languageCode);

        $device = new Device();
        $device->setUid($uid);
        $device->setOs($os);
        $device->setLanguage($language);

        $this->deviceServiceMock->expects($this->once())
            ->method('checkDeviceExist')
            ->with($uid)
            ->willReturn($device);

        $app = new App();

        $this->appServiceMock->expects($this->once())
            ->method('findAppById')
            ->with($appId)
            ->willReturn($app);

        $subscription = new Subscription();
        $subscription->setClientToken('test-client-token');

        $this->subscriptionServiceMock->expects($this->once())
            ->method('subscribeDeviceAndApp')
            ->with($device, $app)
            ->willReturn($subscription);

        $result = $this->registerService->processRegistration($uid, $appId, $osName, $languageCode);

        $this->assertEquals($subscription->getClientToken(), $result);
    }
}