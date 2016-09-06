<?php
/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 8/12/2016
 * Time: 11:00 AM
 */
// www/routing.php
if (preg_match('/\.(?:png|jpg|jpeg|gif)$/', $_SERVER["REQUEST_URI"])) {
    return false;
} else {
    $_GET['_url'] = $_SERVER['REQUEST_URI'];
    include __DIR__ . '/public/index.php';
}