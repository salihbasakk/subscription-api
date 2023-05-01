<?php

namespace App\Tests\Service;

use App\Entity\Purchase;
use App\Entity\Subscription;
use App\Exception\ExceptionMessages;
use App\Repository\PurchaseRepository;
use App\Repository\SubscriptionRepository;
use App\Service\PurchaseService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class PurchaseServiceTest extends TestCase
{
    private PurchaseService $purchaseService;
    private PurchaseRepository $purchaseRepository;
    private SubscriptionRepository $subscriptionRepository;

    protected function setUp(): void
    {
        $this->purchaseRepository = $this->createMock(PurchaseRepository::class);
        $this->subscriptionRepository = $this->createMock(SubscriptionRepository::class);
        $this->purchaseService = new PurchaseService($this->purchaseRepository, $this->subscriptionRepository);
    }

    public function testPurchaseWithInvalidSubscription(): void
    {
        $this->expectExceptionMessage(ExceptionMessages::SUBSCRIPTION_NOT_FOUND);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $this->subscriptionRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['clientToken' => 'invalid_token'])
            ->willReturn(null);

        $this->purchaseService->purchase('invalid_token', 'valid_receipt');
    }
}