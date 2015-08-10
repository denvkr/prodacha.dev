<?php

if(empty($GLOBALS['SysValue']['class']['guard']))
    include_once('./phpshop/modules/guard/class/guard.class.php');
else include_once($GLOBALS['SysValue']['class']['guard']);

$Guard = &new Guard("./");
$Guard->start();
?>
