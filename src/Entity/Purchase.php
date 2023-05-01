<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
#[ORM\Index(columns: ['expireDate', 'status'], name: 'expireDateIdx')]
#[ORM\HasLifecycleCallbacks]
class Purchase extends BaseEntity
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_CANCELLED = 'cancelled';

    #[ORM\Column(nullable: false)]
    private bool $status;

    #[ORM\Column(name: 'purchaseStatus', length: 100, nullable: false)]
    private bool $purchaseStatus;

    #[ORM\Column(length: 200, nullable: false)]
    private string $receipt;

    #[ORM\Column(name: 'expireDate', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $expireDate = null;

    #[ORM\ManyToOne(inversedBy: 'purchases')]
    #[ORM\JoinColumn(name: 'subscriptionId', nullable: false)]
    private Subscription $subscription;

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     * @return Purchase
     */
    public function setStatus(bool $status): Purchase
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPurchaseStatus(): bool
    {
        return $this->purchaseStatus;
    }

    /**
     * @param bool $purchaseStatus
     * @return Purchase
     */
    public function setPurchaseStatus(bool $purchaseStatus): Purchase
    {
        $this->purchaseStatus = $purchaseStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getReceipt(): string
    {
        return $this->receipt;
    }

    /**
     * @param string $receipt
     * @return Purchase
     */
    public function setReceipt(string $receipt): Purchase
    {
        $this->receipt = $receipt;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getExpireDate(): ?DateTimeInterface
    {
        return $this->expireDate;
    }

    /**
     * @param DateTimeInterface|null $expireDate
     * @return Purchase
     */
    public function setExpireDate(?DateTimeInterface $expireDate): Purchase
    {
        $this->expireDate = $expireDate;
        return $this;
    }

    /**
     * @return Subscription
     */
    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }

    /**
     * @param Subscription $subscription
     * @return Purchase
     */
    public function setSubscription(Subscription $subscription): Purchase
    {
        $this->subscription = $subscription;
        return $this;
    }
}
