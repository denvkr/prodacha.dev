<?php

$_classPath="../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
$PHPShopBase->chekAdmin();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopInterface = new PHPShopInterface();

function actionStart() {
    global $PHPShopInterface,$PHPShopBase;
    $PHPShopInterface->size="650,600";
    $PHPShopInterface->imgPath="../img/";

    if($_COOKIE['winOpenType'] == 'highslide')
        $PHPShopInterface->link="page/adm_pagesID.php";
    else $PHPShopInterface->link="../page/adm_pagesID.php";

    $PHPShopInterface->setCaption(array("&plusmn;","5%"),array("Ссылка","20%"),array("Название","40%"),
            array("Реальное размещение","30%"), array(' ',"3%"));

    if(!PHPShopSecurity::true_num($_GET['pid'])) $_GET['pid']=0;


    if(!empty($_REQUEST['words']))
        $sql="select * from ".$GLOBALS['SysValue']['base']['table_name11']." where name REGEXP'".$_REQUEST['words']."' or link REGEXP'".$_REQUEST['words']."'";
    elseif($_GET['pid']=="all") $sql="select * from ".$GLOBALS['SysValue']['base']['table_name11']." order by name";
    else $sql="select * from ".$GLOBALS['SysValue']['base']['table_name11']." where category=".$_GET['pid'].' order by num';
    $PHPShopOrm = new PHPShopOrm();
    $result=$PHPShopOrm->query($sql);
    while (@$row = mysql_fetch_array($result)) {
        $id=$row['id'];
        $name=$row['name'];
        $link=$row['link'];
       
        switch ($row['enabled']) {
            case '0': $checked=$PHPShopInterface->setImage('../img/icon-deactivate.gif',false,false,false,false,false,false,'Блокировка');break;
            case '1': $checked=$PHPShopInterface->setImage('../img/icon-activate.gif',false,false,false,false,false,false,'Показывать');break;
            case '2': $checked=$PHPShopInterface->setImage('../img/icon-move-banner.gif',false,false,false,false,false,false,'Внутренняя');break;
        }
        
        $PHPShopInterface->setRow($id,$checked,$link,$name,array($PHPShopInterface->setLink("http://".$_SERVER['SERVER_NAME'].$PHPShopBase->getParam('dir.dir')."/page/".$link.".html", "http://".$_SERVER['SERVER_NAME'].$PHPShopBase->getParam('dir.dir')."/page/".$link.".html"),$link=false),array($PHPShopInterface->setCheckbox($id, $id,false,false),$link=false));
    }
    $PHPShopInterface->_CODE_ADD_BUTTON=$PHPShopInterface->setInput("hidden","catal",$_GET['pid'],"none",100);
    $PHPShopInterface->Compile('interfaces','flag_form',null);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "xhtml11.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?=$GLOBALS['PHPShopLangCharset']?>">
        <link href="../skins/<?=$_SESSION['theme']?>/texts.css" type=text/css rel=stylesheet>
        <script type="text/javascript" language="JavaScript1.2" src="../java/sorttable.js"></script>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
    </head>
    <body bottommargin="0" rightmargin="0" topmargin="0" leftmargin="0" bgcolor="ffffff">

        <?
        // Вывод формы при старте
        $PHPShopInterface->setLoader(false,'actionStart');
        ?>
    </body>
</html>