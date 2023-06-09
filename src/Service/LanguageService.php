<?php

namespace App\Service;

use App\Entity\Language;
use App\Exception\ExceptionMessages;
use App\Repository\LanguageRepository;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class LanguageService
{
    public LanguageRepository $languageRepository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * @throws Exception
     */
    public function findLanguageByCode(string $languageCode): Language
    {
        $language = $this->languageRepository->findOneBy(['code' => $languageCode]);

        if (!$language) {
            throw new Exception(ExceptionMessages::LANGUAGE_NOT_FOUND, Response::HTTP_BAD_REQUEST);
        }

        return $language;
    }
}