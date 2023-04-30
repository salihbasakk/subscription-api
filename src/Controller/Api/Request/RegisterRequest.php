<?php

namespace App\Controller\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterRequest extends BaseRequest
{
    #[NotBlank]
    public string $uid;

    #[NotBlank]
    public string $language;

    #[NotBlank]
    public string $os;

    #[Assert\Type(
        type: 'integer',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    public int $appId;
}