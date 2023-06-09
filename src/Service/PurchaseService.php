<?php

namespace App\Service;

use App\Entity\Purchase;
use App\Entity\Subscription;
use App\Exception\ExceptionMessages;
use App\Helper\UTCConverter;
use App\Repository\PurchaseRepository;
use App\Repository\SubscriptionRepository;
use App\Service\Factory\ProviderFactory;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class PurchaseService
{
    public PurchaseRepository $purchaseRepository;
    public SubscriptionRepository $subscriptionRepository;

    public function __construct(PurchaseRepository $purchaseRepository, SubscriptionRepository $subscriptionRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * @throws Exception
     */
    public function purchase(string $clientToken, string $receipt): Purchase
    {
        $subscription = $this->subscriptionRepository->findOneBy(['clientToken' => $clientToken]);

        if (!$subscription) {
            throw new Exception(ExceptionMessages::SUBSCRIPTION_NOT_FOUND, Response::HTTP_BAD_REQUEST);
        }

        [$purchaseStatus, $status, $expireDate] = self::verifyFromProvider(
            $subscription->getApp()->getOs()->getName(),
            $receipt,
            $subscription->getApp()->getUsername(),
            $subscription->getApp()->getPassword(),
        );

        $existPurchase = $this->purchaseRepository->findOneBy(['receipt' => $receipt]);

        if ($existPurchase) {
            return self::update($existPurchase, $purchaseStatus, $status, $expireDate);
        }

        return $this->create(
            $subscription,
            $receipt,
            $purchaseStatus,
            $status,
            $expireDate
        );
    }

    public function update(
        Purchase $existPurchase,
        string $purchaseStatus,
        string $status,
        ?DateTime $expireDate
    ): Purchase {
        $existPurchase->setPurchaseStatus($purchaseStatus);
        $existPurchase->setExpireDate($expireDate);
        $existPurchase->setStatus($status);

        $this->purchaseRepository->save($existPurchase, true);

        return $existPurchase;
    }

    public function create(
        Subscription $subscription,
        string $receipt,
        string $purchaseStatus,
        bool $status,
        ?DateTime $expireDate
    ): Purchase {
        $purchase = (new Purchase())
            ->setSubscription($subscription)
            ->setReceipt($receipt)
            ->setPurchaseStatus($purchaseStatus)
            ->setStatus($status)
            ->setExpireDate($expireDate);

        $this->purchaseRepository->save($purchase, true);

        return $purchase;
    }

    /**
     * @throws Exception
     */
    public function verifyFromProvider(
        string $os,
        string $receipt,
        string $username,
        string $password
    ): array {
        $purchaseStatus = Purchase::STATUS_PENDING;
        $expireDate = null;
        $status = false;

        $provider = ProviderFactory::create($os);

        /** @var Response $response */
        $response = $provider->verifyReceipt($username, $password, $receipt);

        if ($response->getStatusCode() === Response::HTTP_OK) {
            $payload = json_decode($response->getContent(), true);

            $purchaseStatus = Purchase::STATUS_APPROVED;

            $status = $payload['status'];
            $timezone = $payload['expireDate']['timezone'];
            $date = $payload['expireDate']['date'];

            $expireDate = UTCConverter::convert($date, $timezone);
        }

        return [$purchaseStatus, $status, $expireDate];
    }
}