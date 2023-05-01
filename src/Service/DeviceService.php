<?php

namespace App\Service;

use App\Controller\Api\Request\RegisterRequest;
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
    public function createDevice(RegisterRequest $request): Device
    {
        $device = (new Device)
            ->setUid($request->uid)
            ->setLanguage($this->languageService->findLanguageByCode($request->language))
            ->setOs($this->operatingSystemService->findOperatingSystemByName($request->os));

        $this->deviceRepository->save($device, true);

        return $device;
    }
}