<?php

namespace App\Helper;

class ClientTokenGenerator
{
    const CHAR_COUNT = 100;

    public static function generate(): string
    {
        //It is now a random string. Using encryption algorithm or jwt token can be created.
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < self::CHAR_COUNT; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}