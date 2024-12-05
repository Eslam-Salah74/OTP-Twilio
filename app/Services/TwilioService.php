<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    /**
     * إرسال OTP إلى الرقم المطلوب
     */
    public function sendOTP($to, $otp)
    {
        $message = "Your OTP code is: $otp";

        return $this->twilio->messages->create($to, [
            'from' => config('services.twilio.phone'),
            'body' => $message,
        ]);
    }
}
