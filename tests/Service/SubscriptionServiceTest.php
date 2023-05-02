<?php

namespace App\Tests\Service;

use App\Entity\App;
use App\Entity\Device;
use App\Entity\OperatingSystem;
use App\Entity\Subscription;
use App\Repository\SubscriptionRepository;
use App\Service\SubscriptionService;
use DateInterval;
use DateTime;
use PHPUnit\Framework\TestCase;

class SubscriptionServiceTest extends TestCase
{
    private SubscriptionRepository $subscriptionRepository;
    private SubscriptionService $subscriptionService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subscriptionRepository = $this->createMock(SubscriptionRepository::class);

        $this->subscriptionService = new SubscriptionService($this->subscriptionRepository);
    }

    public function testSubscribeDeviceAndAppReturnsSubscription(): void
    {
        $device = new Device();
        $device->setOs((new OperatingSystem())->setName('google'));

        $app = new App();
        $app->setName('Test App');
        $app->setOs((new OperatingSystem())->setName('google'));

        $this->subscriptionRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['device' => $device, 'app' => $app])
            ->willReturn(null);

        $subscription = $this->subscriptionService->subscribeDeviceAndApp($device, $app);

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals($device, $subscription->getDevice());
        $this->assertEquals($app, $subscription->getApp());
        $this->assertEquals('started', $subscription->getStatus());
        $this->assertNotNull($subscription->getClientToken());

        $expireDate = new DateTime();
        $expireDate->add(new DateInterval('P2Y'));
        $this->assertEquals($expireDate->format('Y-m-d'), $subscription->getExpireDate()->format('Y-m-d'));
    }

    public function testSubscribeDeviceAndAppReturnsExistingSubscription(): void
    {
        $device = new Device();
        $device->setOs((new OperatingSystem())->setName('ios'));

        $app = new App();
        $app->setName('Test App');
        $app->setOs((new OperatingSystem())->setName('ios'));

        $subscription = new Subscription();
        $subscription->setDevice($device);
        $subscription->setApp($app);

        $this->subscriptionRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['device' => $device, 'app' => $app])
            ->willReturn($subscription);

        $result = $this->subscriptionService->subscribeDeviceAndApp($device, $app);

        $this->assertEquals($subscription, $result);
    }
}