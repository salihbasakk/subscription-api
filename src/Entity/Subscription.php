<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
#[ORM\Index(columns: ['clientToken'], name: 'clientTokenIdx')]
#[ORM\Index(columns: ['deviceId', 'appId', 'status'], name: 'activeDeviceAppsIdx')]
#[ORM\HasLifecycleCallbacks]
class Subscription extends BaseEntity
{
    const STATUS_STARTED = 'started';
    const STATUS_RENEWED = 'renewed';
    const STATUS_CANCELLED = 'cancelled';

    #[ORM\ManyToOne(inversedBy: 'device')]
    #[ORM\JoinColumn(name: 'deviceId', nullable: false)]
    private Device $device;

    #[ORM\ManyToOne(inversedBy: 'app')]
    #[ORM\JoinColumn(name: 'appId', nullable: false)]
    private App $app;

    #[ORM\Column(name: 'clientToken', length: 150, nullable: false)]
    private string $clientToken;

    #[ORM\Column(length: 50, nullable: false)]
    private string $status;

    #[ORM\Column(name: 'expireDate', type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $expireDate;

    #[ORM\OneToMany(mappedBy: 'subscription', targetEntity: Purchase::class, orphanRemoval: true)]
    private Collection $purchases;

    public function __construct()
    {
        $this->purchases = new ArrayCollection();
    }

    /**
     * @return Device
     */
    public function getDevice(): Device
    {
        return $this->device;
    }

    /**
     * @param Device $device
     * @return Subscription
     */
    public function setDevice(Device $device): Subscription
    {
        $this->device = $device;
        return $this;
    }

    /**
     * @return App
     */
    public function getApp(): App
    {
        return $this->app;
    }

    /**
     * @param App $app
     * @return Subscription
     */
    public function setApp(App $app): Subscription
    {
        $this->app = $app;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientToken(): string
    {
        return $this->clientToken;
    }

    /**
     * @param string $clientToken
     * @return Subscription
     */
    public function setClientToken(string $clientToken): Subscription
    {
        $this->clientToken = $clientToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Subscription
     */
    public function setStatus(string $status): Subscription
    {
        $this->status = $status;
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
     * @return Subscription
     */
    public function setExpireDate(DateTimeInterface $expireDate): Subscription
    {
        $this->expireDate = $expireDate;
        return $this;
    }

    /**
     * @return Collection<int, Purchase>
     */
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    public function addPurchase(Purchase $purchase): self
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setSubscription($this);
        }

        return $this;
    }
}
