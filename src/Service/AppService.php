<?php

namespace App\Service;

use App\Entity\App;
use App\Exception\ExceptionMessages;
use App\Repository\AppRepository;
use Exception;

class AppService
{
    public AppRepository $appRepository;

    public function __construct(AppRepository $appRepository)
    {
        $this->appRepository = $appRepository;
    }

    /**
     * @throws Exception
     */
    public function findAppById(int $appId): App
    {
        $app = $this->appRepository->find($appId);

        if (!$app) {
            throw new Exception(ExceptionMessages::APP_NOT_FOUND);
        }

        return $app;
    }
}