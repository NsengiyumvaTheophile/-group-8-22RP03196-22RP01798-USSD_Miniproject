<?php
require 'vendor/autoload.php';
use AfricasTalking\SDK\AfricasTalking;

class Sms {
    protected $phone;
    protected $AT;

    function __construct($phone = '') {
        $this->phone = $phone;
        $this->AT = new AfricasTalking("sandbox", "atsk_08351c8e295057d88765484516b4ff384f224fd4b59033a02f67d918bdc73df2c9f4b57c");
    }
    // send sms

    public function sendSMS($message, $recipients) {
        $sms = $this->AT->sms();
        $result = $sms->send([
            'username' => "sandbox",
            'to' => $recipients,
            'message' => $message,
            'from' => "TRACKLOST Ltd"
        ]);

        return $result;
    }
}
?>
