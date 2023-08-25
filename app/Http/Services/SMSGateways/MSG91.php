<?php

namespace App\Http\Services\SMSGateways;

use App\Traits\responseTrait;
use Twilio\Rest\Client;

class MSG91
{
    use responseTrait;

    protected $client;

    public function __construct()
    {
        $this->client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function sendSMS($to, $message)
    {


        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'https://control.msg91.com/api/v5/otp?mobile=&template_id=', [
            'body' => '{"Param1":"value1","Param2":"value2","Param3":"value3"}',
            'headers' => [
                'accept' => 'application/json',
                'authkey' => 'Enter your MSG91 authkey',
                'content-type' => 'application/json',
            ],
        ]);

        echo $response->getBody();
    }
}
