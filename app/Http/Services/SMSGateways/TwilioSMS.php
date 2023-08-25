<?php

namespace App\Http\Services\SMSGateways;

use App\Traits\responseTrait;
use Twilio\Rest\Client;

class TwilioSMS
{
    use responseTrait;

    protected $client;

    public function __construct()
    {
        $this->client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function sendSMS($to, $message)
    {
        $this->client->messages->create(
            '+2'.$to,
            [
                'from' => env('TWILIO_PHONE_NUMBER'),
                'body' => $message,
            ]
        );
    }
}
