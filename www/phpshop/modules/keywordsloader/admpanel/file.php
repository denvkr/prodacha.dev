<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("file");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");


// SQL
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
$data = $PHPShopOrm->select(array('id', 'keywords'));

$csv = null;

if (is_array($data)) {
    foreach ($data as $row) {
        $csv.=$row['id'] . ';' . $row['keywords'] . '
';
    }

    $dir = "../../../../UserFiles/Files/";
    $file = PHPShopDate::dataV(false, false) . '-' . substr(md5(time()), 0, 5) . '.csv';
    PHPShopFile::write($dir . $file, $csv);
    header("Location: " . $dir . $file);
}
?>