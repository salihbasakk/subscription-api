<?php

namespace App\Interface;

use App\Entity\App;
use Symfony\Component\HttpFoundation\Response;

interface ProviderInterface
{
    public function verifyReceipt(App $app, string $receipt): Response;
}