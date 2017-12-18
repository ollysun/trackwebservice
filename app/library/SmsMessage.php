<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 7/11/2016
 * Time: 2:16 PM
 */
class SmsMessage
{
    const API_USERNAME = 'demo';
    const API_PASSWORD = 'demo';

    public static function send($phone, $sender_id, $message){
        $api_username = self::API_USERNAME;
        $api_password = self::API_PASSWORD;
        $message = urlencode($message);
        $httpClient = new HttpClient();
        $url = "http://www.openbulksms.com/api/send?sender=$sender_id&message=$message&recipient=$phone&loginId=$api_username&password=$api_password&sendOnDate=@sendOnDate@";
        $data = $httpClient->get($url);
        return stripos($data, 'Message sent') > -1;
    }

}