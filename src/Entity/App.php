<?php

namespace App\Entity;

use App\Repository\AppRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppRepository::class)]
#[ORM\HasLifecycleCallbacks]
class App extends BaseEntity
{
    #[ORM\Column(length: 50, nullable: false)]
    private string $name;

    #[ORM\Column(length: 100, nullable: false)]
    private string $username;

    #[ORM\Column(length: 100, nullable: false)]
    private string $password;

    #[ORM\ManyToOne(inversedBy: 'app')]
    #[ORM\JoinColumn(name: 'deviceId', nullable: true)]
    private ?Device $device = null;

    #[ORM\OneToMany(mappedBy: 'subscription', targetEntity: Subscription::class)]
    private Collection $subscriptions;

    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return App
     */
    public function setName(string $name): App
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return App
     */
    public function setUsername(string $username): App
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return App
     */
    public function setPassword(string $password): App
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return Device|null
     */
    public function getDevice(): ?Device
    {
        return $this->device;
    }

    /**
     * @param Device|null $device
     * @return App
     */
    public function setDevice(?Device $device): App
    {
        $this->device = $device;
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
            $subscription->setApp($this);
        }

        return $this;
    }
}
