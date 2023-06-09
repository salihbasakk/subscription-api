<?php

namespace App\Service;

use App\Entity\App;
use App\Entity\Device;
use App\Entity\Subscription;
use App\Exception\ExceptionMessages;
use App\Helper\ClientTokenGenerator;
use App\Repository\SubscriptionRepository;
use DateTime;
use DateInterval;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionService
{
    public SubscriptionRepository $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * @throws Exception
     */
    public function subscribeDeviceAndApp(Device $device, App $app): Subscription
    {
        $subscription = $this->subscriptionRepository->findOneBy(['device' => $device, 'app' => $app]);

        if ($subscription) {
            return $subscription;
        }

        if ($device->getOs()->getName() !== $app->getOs()->getName()) {
            throw new Exception(ExceptionMessages::DEVICE_APP_OS_DO_NOT_MATCH, Response::HTTP_BAD_REQUEST);
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
    public function getSubscriptionByClientToken(string $clientToken): Subscription
    {
        $subscription = $this->subscriptionRepository->findOneBy(['clientToken' => $clientToken]);

        if ($subscription) {
            return $subscription;
        }

        throw new Exception(ExceptionMessages::SUBSCRIPTION_NOT_FOUND, Response::HTTP_BAD_REQUEST);
    }
}