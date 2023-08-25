<?php

namespace App\Http\Services\SMSGateways;

use App\Traits\responseTrait;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

class VonageSMS
{
    use responseTrait;

    public function sendSMS($phone, $message, $brand_name)
    {
        $basic = new Basic("89691da2", "kE0LwsYRp2Nuxtnh");
        $client = new Client($basic);
        $response = $client->sms()->send(
            new SMS(
                '+2'.$phone,
                $brand_name,
                $message)
        );

        $message = $response->current();

        if ($message->getStatus() == 0) {
            return true;
        } else {
            return false;
        }
    }

}
