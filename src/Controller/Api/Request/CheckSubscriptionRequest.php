<?php

namespace App\Controller\Api\Request;

use Symfony\Component\Validator\Constraints\NotBlank;

class CheckSubscriptionRequest extends BaseRequest
{
    #[NotBlank]
    public string $clientToken;
}