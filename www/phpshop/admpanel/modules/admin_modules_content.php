<?php
$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("xml");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopInterface = new PHPShopInterface();

// Информация по модулю
function GetModuleInfo($name) {
    $path = "../../modules/" . $name . "/install/module.xml";
    if (function_exists("xml_parser_create")) {
        if (@$db = readDatabase($path, "module"))
            return $db[0];
    }
}

function ChekInstallModule($path) {
    $return = array();
    $sql = 'SELECT a.*, b.key FROM ' . $GLOBALS['SysValue']['base']['modules'] . ' AS a LEFT OUTER JOIN ' . $GLOBALS['SysValue']['base']['modules_key'] . ' AS b ON a.path = b.path where a.path="' . $path . '"';

    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    if (mysql_num_rows($result) > 0) {
        $return[0] = "#C0D2EC";
        $return[1] = "
<BUTTON style=\"width: 10em; height: 2.2em; margin-left:5\"  onclick=\"DoUpdateModules('off','$path','" . $_GET['pid'] . "');return false;\">
<img src=\"../img/icon-deactivate.gif\" border=\"0\" align=\"absmiddle\">
Отключить
</BUTTON>
                ";

        $return[2] = $row['date'];
        $return[3] = $row['key'];
    } else {

        $return[0] = "white";
        $return[1] = "
<BUTTON style=\"width: 10em; height: 2.2em; margin-left:5\"  onclick=\"DoUpdateModules('on','$path','" . $_GET['pid'] . "');return false;\">
<img src=\"../img/icon-activate.gif\"  border=\"0\" align=\"absmiddle\">
Установить
</BUTTON>
                ";
        $return[2] = null;
        $return[3] = $row['key'];
    }
    return $return;
}

function actionStart() {
    global $PHPShopInterface, $UserStatus;
    $PHPShopInterface->razmer = "height:560px;";
    $PHPShopInterface->imgPath = '../img/';

    $PHPShopInterface->setCaption(array("Управление", "10%"), array("Название", "20%"), array("Описание", "50%"), array("Установлено", "15%"));

    $path = "../../modules/";
    $i = 1;
    if (@$dh = opendir($path)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != "..") {

                if (is_dir($path . $file)) {

                    // Информация по модулю
                    $Info = GetModuleInfo($file);

                    if (!empty($Info['status']))
                        $new = '<sup>'.strtoupper($Info['status']).'</sup>';
                    else
                        $new = null;

                    // Если выбрана категория
                    if (isset($_GET['pid']) and @strstr($Info['category'], $_GET['pid']) and empty($Info['hidden'])) {

                        $ChekInstallModule = ChekInstallModule($file);

                        // Дата установки
                        if (!empty($ChekInstallModule[2]))
                            $InstallDate = date("d-m-y H:s", $ChekInstallModule[2]);
                        else
                            $InstallDate = "";

                        if (!empty($Info['trial']) and empty($ChekInstallModule[3])) {
                            $trial = ' (Trial 30 дней)';
                        }
                        else
                            $trial = null;

                        $ModuleHomePage = '<img src="' . $path . $file . '/install/' . $Info['icon'] . '" align="absmiddle">
<a href="http://wiki.phpshop.ru/index.php/Modules#' . str_replace(' ', '_', $Info['name']) . '" target="_blank" title="Описание модуля" class="blue">' . $Info['name'] . ' ' . $Info['version'] . $trial . '</a> '. $new;

                        if (CheckedRules($UserStatus["module"], 0)) {
                            $ChekInstallModule[1] = "<BUTTON style=\"width: 10em; height: 2.2em; margin-left:5\" >
<img src=\"../img/errormessage.gif\"  border=\"0\" align=\"absmiddle\">
Нет прав
</BUTTON>";
                        }


                        $PHPShopInterface->setRow($i, $ChekInstallModule[1], $ModuleHomePage, $Info['description'], $InstallDate);
                        $i++;
                    }
                    // Вывод всех модулей
                    elseif (empty($_GET['pid']) and empty($Info['hidden'])) {
                        $ChekInstallModule = ChekInstallModule($file);

                        // Дата установки
                        if (!empty($ChekInstallModule[2]))
                            $InstallDate = date("d-m-y H:s", $ChekInstallModule[2]);
                        else
                            $InstallDate = "";

                        if (!empty($Info['trial']) and empty($ChekInstallModule[3])) {
                            $trial = ' (Trial 30 дней)';
                        }
                        else
                            $trial = null;


                        $ModuleHomePage = '<img src="' . $path . $file . '/install/' . $Info['icon'] . '" align="absmiddle">
<a href="http://wiki.phpshop.ru/index.php/Modules#' . str_replace(' ', '_', $Info['name']) . '" target="_blank" title="Описание модуля" class="blue">' . $Info['name'] . ' ' . $Info['version'] . $trial . '</a> ' . $new;


                        if (CheckedRules($UserStatus["module"], 0)) {
                            $ChekInstallModule[1] = "<BUTTON style=\"width: 10em; height: 2.2em; margin-left:5\" >
<img src=\"../img/errormessage.gif\"  border=\"0\" align=\"absmiddle\">
Нет прав
</BUTTON>";
                        }


                        $PHPShopInterface->setRow($i, $ChekInstallModule[1], $ModuleHomePage, $Info['description'], $InstallDate);
                        $i++;
                    }
                }
            }
        }
        closedir($dh);
    }
    $PHPShopInterface->Compile();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "xhtml11.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= $GLOBALS['PHPShopLangCharset'] ?>">
            <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
                <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
                <script type="text/javascript" language="JavaScript1.2" src="../java/sorttable.js"></script>
                <SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
                </head>
                <body bottommargin="0" rightmargin="0" topmargin="0" leftmargin="0" bgcolor="ffffff">

                    <?
                    // Вывод формы при старте
                    $PHPShopInterface->setLoader(false, 'actionStart');
                    ?>
                </body>
                </html>