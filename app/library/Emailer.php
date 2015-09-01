<?php


class Emailer {
    const HTML_HEADERS = "
MIME-Version: 1.0\r\n
From: {{from_name}} <{{from_email}}>\r\n
To: <{{to_email}}> \r\n
Subject: {{subject}} \r\n
Content-type: text/html; charset=iso-8859-1\r\n
    ";

    public static function send($email, $subject, $message, $from_email, $from_name, $msg_params=array(), $header_template=Emailer::HTML_HEADERS){
        $headers = $header_template;
        $headers = str_replace('{{from_name}}', $from_name, $headers);
        $headers = str_replace('{{from_email}}', $from_email, $headers);
        $headers = str_replace('{{to_email}}', $email, $headers);
        $headers = str_replace('{{subject}}', $subject, $headers);

        $message = self::getActualMessage($message, $msg_params);
        return @mail($email, $subject, $message, $headers, '-f'.$from_email);
    }

    private static function getActualMessage($message, $params){
        foreach ($params as $param => $value){
            $message = str_replace('{{' . $param . '}}', $value, $message);
        }
        return $message;
    }
}