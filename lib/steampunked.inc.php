<?php
/**
 * Created by PhpStorm.
 * User: Aasir
 * Date: 2/17/16
 * Time: 12:32 AM
 */
require __DIR__ . "/../vendor/autoload.php";

session_start();

define("STEAMPUNKED_SESSION", 'steampunked');

if(!isset($_SESSION[STEAMPUNKED_SESSION])) {
    $_SESSION[STEAMPUNKED_SESSION] = new Steampunked\SteampunkedModel();
}

$steampunked = $_SESSION[STEAMPUNKED_SESSION];