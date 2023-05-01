<?php

namespace App\Tests\Service;

use App\Entity\Language;
use App\Exception\ExceptionMessages;
use App\Repository\LanguageRepository;
use App\Service\LanguageService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class LanguageServiceTest extends TestCase
{
    private LanguageRepository $languageRepository;
    private LanguageService $languageService;

    protected function setUp(): void
    {
        $this->languageRepository = $this->getMockBuilder(LanguageRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->languageService = new LanguageService($this->languageRepository);
    }

    public function testFindLanguageByCode(): void
    {
        $languageCode = 'en';
        $language = new Language();
        $language->setCode($languageCode);

        $this->languageRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['code' => $languageCode])
            ->willReturn($language);

        $result = $this->languageService->findLanguageByCode($languageCode);

        $this->assertSame($language, $result);
    }

    public function testFindLanguageByCodeWithInvalidCode(): void
    {
        $languageCode = 'invalid_code';

        $this->languageRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['code' => $languageCode])
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(ExceptionMessages::LANGUAGE_NOT_FOUND);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $this->languageService->findLanguageByCode($languageCode);
    }
}