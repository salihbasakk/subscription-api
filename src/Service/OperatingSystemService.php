<?php

namespace App\Service;

use App\Entity\OperatingSystem;
use App\Exception\ExceptionMessages;
use App\Repository\OperatingSystemRepository;
use Exception;

class OperatingSystemService
{
    public OperatingSystemRepository $operatingSystemRepository;

    public function __construct(OperatingSystemRepository $operatingSystemRepository)
    {
        $this->operatingSystemRepository = $operatingSystemRepository;
    }

    /**
     * @throws Exception
     */
    public function findOperatingSystemByName(string $operatingSystem): OperatingSystem
    {
        $operatingSystem = $this->operatingSystemRepository->findOneBy(['name' => $operatingSystem]);

        if (!$operatingSystem) {
            throw new Exception(ExceptionMessages::OPERATING_SYSTEM_NOT_FOUND);
        }

        return $operatingSystem;
    }
}