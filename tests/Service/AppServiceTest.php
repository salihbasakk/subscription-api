<?php

namespace App\Tests\Service;

use App\Entity\App;
use App\Exception\ExceptionMessages;
use App\Repository\AppRepository;
use App\Service\AppService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class AppServiceTest extends TestCase
{
    private MockObject $appRepositoryMock;
    private AppService $appService;

    protected function setUp(): void
    {
        $this->appRepositoryMock = $this->createMock(AppRepository::class);
        $this->appService = new AppService($this->appRepositoryMock);
    }

    public function testFindAppById(): void
    {
        $appId = 1;
        $app = new App();

        $this->appRepositoryMock->expects($this->once())
            ->method('find')
            ->with($appId)
            ->willReturn($app);

        $result = $this->appService->findAppById($appId);

        $this->assertEquals($app, $result);
    }

    public function testFindAppByIdWithInvalidId(): void
    {
        $appId = 1;

        $this->appRepositoryMock->expects($this->once())
            ->method('find')
            ->with($appId)
            ->willReturn(null);

        try {
            $this->appService->findAppById($appId);
        } catch (Exception $e) {
            $this->assertEquals(ExceptionMessages::APP_NOT_FOUND, $e->getMessage());
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getCode());

            return;
        }

        $this->fail('Expected exception was not thrown.');
    }
}