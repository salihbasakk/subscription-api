<?php

namespace App\Service\Mock;

use App\Entity\App;
use App\Interface\ProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use DateTime;
use DateTimeZone;
use DateInterval;

class Google implements ProviderInterface
{
    public function verifyReceipt(string $username, string $password, string $receipt): Response
    {
        //Suppose we use $app credentials but this service mock at the moment
        $lastCharacter = substr($receipt, -1);
        $lastTwoCharacter = substr($receipt, -2);

        if (is_numeric($lastCharacter) && $lastCharacter % 2 == 1) {
            $date = new DateTime();
            $date->add(new DateInterval('P2D'));
            $date->setTimezone(new DateTimeZone('America/Bahia_Banderas'))->format('Y-m-d H:i:s');

            $response = [
                'status' => true,
                'expireDate' => $date
            ];

            return new Response(json_encode($response), Response::HTTP_OK);
        } elseif (is_numeric($lastTwoCharacter) && $lastTwoCharacter % 6 == 0) {
            $response = ['status' => false];

            return new Response(json_encode($response), Response::HTTP_TOO_MANY_REQUESTS);
        } else {
            $response = ['status' => false];

            return new Response(json_encode($response), Response::HTTP_BAD_REQUEST);
        }
    }
}