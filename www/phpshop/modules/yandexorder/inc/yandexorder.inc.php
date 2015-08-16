<?php

/**
 * Учет реферала
 */

$url=parse_url($_SERVER['HTTP_REFERER']);
$referal=$url["host"];

if(isset($_COOKIE['ps_referal'])) $partner = base64_encode(base64_decode($_COOKIE['ps_referal']).",".$referal);
else $partner = base64_encode($referal);

if(strlen($_SERVER['HTTP_REFERER'])>5 and !strpos($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME']))
    setcookie("ps_referal", $partner, time()+60*60*24*90, "/", $_SERVER['SERVER_NAME'], 0);

?>