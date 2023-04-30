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

    #[ORM\Column(length: 200, nullable: false)]
    private string $receipt;

    #[ORM\Column(name: 'expireDate', type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $expireDate;

    #[ORM\ManyToOne(inversedBy: 'purchases')]
    #[ORM\JoinColumn(name: 'subscriptionId', nullable: false)]
    private Subscription $subscription;

    /**
     * @return bool
     */
    public function isStatus(): bool
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
     * @return DateTimeInterface
     */
    public function getExpireDate(): DateTimeInterface
    {
        return $this->expireDate;
    }

    /**
     * @param DateTimeInterface $expireDate
     * @return Purchase
     */
    public function setExpireDate(DateTimeInterface $expireDate): Purchase
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
