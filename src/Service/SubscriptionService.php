<?php

namespace App\Service;

use App\Entity\App;
use App\Entity\Device;
use App\Entity\Subscription;
use App\Helper\ClientTokenGenerator;
use App\Repository\SubscriptionRepository;
use DateTime;
use DateInterval;

class SubscriptionService
{
    public SubscriptionRepository $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function subscribeDeviceAndApp(Device $device, App $app): Subscription
    {
        $subscription = $this->subscriptionRepository->findOneBy(['device' => $device, 'app' => $app]);

        if ($subscription) {
            return $subscription;
        }

        $expireDate = new DateTime();
        $expireDate->add(new DateInterval('P2Y'));

        return (new Subscription())
            ->setDevice($device)
            ->setApp($app)
            ->setStatus(Subscription::STATUS_STARTED)
            ->setClientToken(ClientTokenGenerator::generate())
            ->setExpireDate($expireDate);
    }
}