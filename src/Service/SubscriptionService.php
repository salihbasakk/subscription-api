<?php

namespace App\Service;

use App\Controller\Api\Request\CheckSubscriptionRequest;
use App\Entity\App;
use App\Entity\Device;
use App\Entity\Subscription;
use App\Exception\ExceptionMessages;
use App\Helper\ClientTokenGenerator;
use App\Repository\SubscriptionRepository;
use DateTime;
use DateInterval;
use Exception;

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

        $subscription = (new Subscription())
            ->setDevice($device)
            ->setApp($app)
            ->setStatus(Subscription::STATUS_STARTED)
            ->setClientToken(ClientTokenGenerator::generate())
            ->setExpireDate($expireDate);

        $this->subscriptionRepository->save($subscription, true);

        return $subscription;
    }

    /**
     * @throws Exception
     */
    public function getSubscriptionByClientToken(CheckSubscriptionRequest $request): Subscription
    {
        $subscription = $this->subscriptionRepository->findOneBy(['clientToken' => $request->clientToken]);

        if ($subscription) {
            return $subscription;
        }

        throw new Exception(ExceptionMessages::SUBSCRIPTION_NOT_FOUND);
    }
}