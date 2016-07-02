<?php
define('DEFAULT_OFFSET', 0);
define('DEFAULT_COUNT', 20);
define('MIN_PASSWORD_LENGTH', 6);


define('OWNER_TYPE_CUSTOMER', 1);
define('OWNER_TYPE_COMPANY', 2);

function dd($data, $message = ''){
    var_dump($data);
    die($message);
}