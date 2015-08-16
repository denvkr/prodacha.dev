<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru">
<head>
<title>Тестирование Open Flash Chart</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
</head>
<body>
<?php
include_once 'open_flash_chart_object.php';
$baseURL = "http://".$_SERVER['HTTP_HOST'].$_SERVER[REQUEST_URI];
echo open_flash_chart_object( 400, 250, $baseURL.'/chart-data.php', false, $baseURL );
?>
</body>
</html>