<?php

$username = "yml";
$password = "As6t9RiI";
$host_api = "https://prodacha.ru/yml/gmerchant.php";
 
// авторизация
$curl = curl_init($host_api);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);       
curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);       
// get запрос
curl_setopt($curl, CURLOPT_URL, "$host_api");       
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($curl);
// вывести результат
var_dump($result);
