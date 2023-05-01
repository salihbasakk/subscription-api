<?php

namespace App\Tests\Service;

use App\Entity\OperatingSystem;
use App\Exception\ExceptionMessages;
use App\Repository\OperatingSystemRepository;
use App\Service\OperatingSystemService;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class OperatingSystemServiceTest extends TestCase
{
    private OperatingSystemService $operatingSystemService;
    private OperatingSystemRepository $operatingSystemRepository;

    protected function setUp(): void
    {
        $this->operatingSystemRepository = $this->createMock(OperatingSystemRepository::class);
        $this->operatingSystemService = new OperatingSystemService($this->operatingSystemRepository);
    }

    public function testFindOperatingSystemByName(): void
    {
        $operatingSystemName = 'ios';
        $operatingSystem = (new OperatingSystem())->setName($operatingSystemName);

        $this->operatingSystemRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => $operatingSystemName])
            ->willReturn($operatingSystem);

        $result = $this->operatingSystemService->findOperatingSystemByName($operatingSystemName);

        $this->assertSame($operatingSystem, $result);
    }

    public function testFindOperatingSystemByNameNotFound(): void
    {
        $operatingSystemName = 'google';

        $this->operatingSystemRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => $operatingSystemName])
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(ExceptionMessages::OPERATING_SYSTEM_NOT_FOUND);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $this->operatingSystemService->findOperatingSystemByName($operatingSystemName);
    }
}