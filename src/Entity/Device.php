<?php

namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
#[UniqueConstraint(name: 'deviceIdx', columns: ['uid'])]
#[ORM\Index(columns: ['uid'], name: 'uidIdx')]
#[ORM\HasLifecycleCallbacks]
class Device extends BaseEntity
{
    #[ORM\Column(length: 150, nullable: false)]
    private string $uid;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'languageId', nullable: false)]
    private Language $language;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'osId', nullable: false)]
    private OperatingSystem $os;

    #[ORM\OneToMany(mappedBy: 'device', targetEntity: App::class)]
    private Collection $apps;

    #[ORM\OneToMany(mappedBy: 'subscription', targetEntity: Subscription::class)]
    private Collection $subscriptions;

    public function __construct()
    {
        $this->apps = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     * @return Device
     */
    public function setUid(string $uid): Device
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @param Language $language
     * @return Device
     */
    public function setLanguage(Language $language): Device
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return OperatingSystem
     */
    public function getOs(): OperatingSystem
    {
        return $this->os;
    }

    /**
     * @param OperatingSystem $os
     * @return Device
     */
    public function setOs(OperatingSystem $os): Device
    {
        $this->os = $os;
        return $this;
    }

    /**
     * @return Collection<int, App>
     */
    public function getApp(): Collection
    {
        return $this->apps;
    }

    public function addApp(App $app): self
    {
        if (!$this->apps->contains($app)) {
            $this->apps->add($app);
            $app->setDevice($this);
        }

        return $this;
    }

    public function removeApp(App $app): self
    {
        if ($this->apps->removeElement($app)) {
            if ($app->getDevice() === $this) {
                $app->setDevice(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Subscription>
     */
    public function getSubscription(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setDevice($this);
        }

        return $this;
    }
}
