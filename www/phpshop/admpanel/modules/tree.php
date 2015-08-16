<?php

$_classPath="../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("admgui");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
$PHPShopBase->chekAdmin();

PHPShopObj::loadClass("system");
$PHPShopSystem = new PHPShopSystem();

class MainCatalogTree extends CatalogTree {

    function MainCatalogTree($base) {
        if($_COOKIE['winOpenType'] == 'highslide') $this->dot="./catalog/";
        else $this->dot="";
        parent::CatalogTree($base);
    }

    function addcat($n,$id,$name,$link=false,$icon=false) {
        $name=__($name);
        $this->dis.="d.add($n,$id,'$name','$link','','','','$icon');";
    }

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "xhtml11.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?=$GLOBALS['PHPShopLangCharset']?>">
        <LINK href="../css/dtree.css" type=text/css rel=stylesheet>
        <SCRIPT language=JavaScript1.2 src="../java/dtree.js" type=text/javascript></SCRIPT>
        <script language="JavaScript1.2" src="../java/phpshop.js" type="text/javascript"></script>
    </head>
    <body bottommargin="0" rightmargin="0" topmargin="0" leftmargin="0" bgcolor="#ffffff">
        <div style="padding:7px">
            <?
            // Дерево каталогов
            $CatalogTree = new MainCatalogTree(null);
            $CatalogTree->addcat(0,-1,'<b>Модули</b>','');
            $CatalogTree->addcat(1,0,'Дизайн','admin_modules_content.php?pid=template','../img/imgfolder.gif');
            $CatalogTree->addcat(2,0,'Юзабилити','admin_modules_content.php?pid=form','../img/imgfolder.gif');
            $CatalogTree->addcat(3,0,'Социальные сети','admin_modules_content.php?pid=soc','../img/imgfolder.gif');
            $CatalogTree->addcat(4,0,'SEO','admin_modules_content.php?pid=seo','../img/imgfolder.gif');
            $CatalogTree->addcat(5,0,'Продажи','admin_modules_content.php?pid=sale','../img/imgfolder.gif');
            $CatalogTree->addcat(6,0,'Повышение конверсии','admin_modules_content.php?pid=user','../img/imgfolder.gif');
            $CatalogTree->addcat(7,0,'Разработчикам','admin_modules_content.php?pid=develop','../img/imgfolder.gif');
            $CatalogTree->disp();
            ?>
        </div>
    </body>
</html>

