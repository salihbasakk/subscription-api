<?php

namespace App\Service;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use Exception;

class DeviceService
{
    public DeviceRepository $deviceRepository;
    public LanguageService $languageService;
    public OperatingSystemService $operatingSystemService;

    public function __construct(
        DeviceRepository $deviceRepository,
        LanguageService $languageService,
        OperatingSystemService $operatingSystemService
    ) {
        $this->deviceRepository = $deviceRepository;
        $this->languageService = $languageService;
        $this->operatingSystemService = $operatingSystemService;
    }

    public function checkDeviceExist(string $uid): ?Device
    {
        return $this->deviceRepository->findOneBy(['uid' => $uid]);
    }

    /**
     * @throws Exception
     */
    public function createDevice(string $uid, string $os, string $language): Device
    {
        $device = (new Device)
            ->setUid($uid)
            ->setOs($this->operatingSystemService->findOperatingSystemByName($os))
            ->setLanguage($this->languageService->findLanguageByCode($language));

        $this->deviceRepository->save($device, true);

        return $device;
    }
}