<?php
include_once($GLOBALS['SysValue']['class']['cron']);
$PHPShopCron = &new PHPShopCron();
$PHPShopCron->start();
?>