<?php

namespace App\Service\Factory;

use App\Exception\ExceptionMessages;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ProviderFactory
{
    public static function create(string $providerName)
    {
        $class = 'App\\Service\\Mock' . '\\' . ucfirst($providerName);

        if (!class_exists($class)) {
            throw new Exception(ExceptionMessages::PROVIDER_NOT_FOUND, Response::HTTP_BAD_REQUEST);
        }

        return new $class();
    }
}