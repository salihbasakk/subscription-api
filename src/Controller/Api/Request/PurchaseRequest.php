<?php

namespace App\Controller\Api\Request;

use Symfony\Component\Validator\Constraints\NotBlank;

class PurchaseRequest extends BaseRequest
{
    #[NotBlank]
    public string $clientToken;

    #[NotBlank]
    public string $receipt;
}