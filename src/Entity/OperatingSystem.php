<?php

namespace App\Entity;

use App\Repository\OperatingSystemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OperatingSystemRepository::class)]
#[ORM\Index(columns: ['name'], name: 'nameIdx')]
#[ORM\HasLifecycleCallbacks]
class OperatingSystem extends BaseEntity
{
    #[ORM\Column(length: 50, nullable: false)]
    private string $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return OperatingSystem
     */
    public function setName(string $name): OperatingSystem
    {
        $this->name = $name;
        return $this;
    }
}
