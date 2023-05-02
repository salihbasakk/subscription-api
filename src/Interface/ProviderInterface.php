<?php

namespace App\Interface;

use Symfony\Component\HttpFoundation\Response;

interface ProviderInterface
{
    public function verifyReceipt(string $username, string $password, string $receipt): Response;
}