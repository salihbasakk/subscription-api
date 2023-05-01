<?php

namespace App\Tests\Service;

use App\Entity\Device;
use App\Entity\Language;
use App\Entity\OperatingSystem;
use App\Repository\DeviceRepository;
use App\Service\DeviceService;
use App\Service\LanguageService;
use App\Service\OperatingSystemService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeviceServiceTest extends TestCase
{
    private MockObject $deviceRepositoryMock;
    private MockObject $languageServiceMock;
    private MockObject $operatingSystemServiceMock;
    private DeviceService $deviceService;

    protected function setUp(): void
    {
        $this->deviceRepositoryMock = $this->getMockBuilder(DeviceRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->languageServiceMock = $this->getMockBuilder(LanguageService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->operatingSystemServiceMock = $this->getMockBuilder(OperatingSystemService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->deviceService = new DeviceService(
            $this->deviceRepositoryMock,
            $this->languageServiceMock,
            $this->operatingSystemServiceMock
        );
    }

    public function testCheckDeviceExistReturnsNullIfDeviceNotFound()
    {
        $uid = 'test-device-uid';
        $this->deviceRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['uid' => $uid])
            ->willReturn(null);

        $result = $this->deviceService->checkDeviceExist($uid);

        $this->assertNull($result);
    }

    public function testCreateDevice()
    {
        $uid = 'test-device-uid';
        $osName = 'ios';
        $languageCode = 'en';

        $os = new OperatingSystem();
        $os->setName($osName);

        $language = new Language();
        $language->setCode($languageCode);

        $this->languageServiceMock->expects($this->once())
            ->method('findLanguageByCode')
            ->with($languageCode)
            ->willReturn($language);

        $this->operatingSystemServiceMock->expects($this->once())
            ->method('findOperatingSystemByName')
            ->with($osName)
            ->willReturn($os);

        $device = new Device();
        $device->setUid($uid);
        $device->setOs($os);
        $device->setLanguage($language);

        $this->deviceRepositoryMock->expects($this->once())
            ->method('save')
            ->with($device, true);

        $this->assertInstanceOf(Device::class, $this->deviceService->createDevice($uid, $osName, $languageCode));
    }
}