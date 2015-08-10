<?php

$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("admgui");


$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");

PHPShopObj::loadClass("system");
$PHPShopSystem = new PHPShopSystem();


class FileCatalogTree extends CatalogTree {
    var $i=1;

    function FileCatalogTree($base) {
        if($_COOKIE['winOpenType'] == 'highslide') $this->dot="./catalog/";
        else $this->dot="";
        parent::CatalogTree($base);
    }

    function addcat($n,$id,$name,$link=false,$icon=false) {
        $name=__($name);
        $this->dis.="d.add($n,$id,'$name','$link','','','','$icon');";
    }

    function create() {
        global $_classPath;

        $dir=$_classPath."templates";
        if (is_dir($dir)) {
            if (@$dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {

                    if($file!="." and $file!=".." and $file!="index.html") {

                        $this->dis.="
  d.add($this->i,0,'$file');
                                ".$this->add($this->i,$dir.'/'.$file)."
  ";
                        $this->i++;
                    }
                }
                closedir($dh);
            }
        }
    }

    function add($n,$dir) {
        global $_classPath;
        $this->i++;
        $disp=null;
        if (is_dir($dir)) {
            if (@$dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {

                    if($file!="." and $file!=".." and $file!="index.html" and $file!="images" and $file!="icon") {

                        $file_name=base64_encode($dir.'/'.$file);
                        $link='./admin_cat_content.php?edit='.$file_name;

                        if(is_dir($dir.'/'.$file))// если есть еще каталоги
                        {
                            $disp.="d.add($this->i,$n,'$file');
                                    ".$this->add($this->i,$dir.'/'.$file);
                        }
                        elseif(strstr($file, '.tpl') or strstr($file, '.css') or strstr($file, '.js'))// если нет каталогов
                        {
                            $disp.="d.add($this->i,$n,'$file','".$link."');";
                        }
                        
                        $this->i++;
                    }
                }
                closedir($dh);
            }
        }

        return $disp;
    }
    
    function chek($dir) {
        if (is_dir($dir)) return true;
    }


}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "xhtml11.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?=$GLOBALS['PHPShopLangCharset']?>">
        <LINK href="<?=$_classPath.'admpanel/'?>skins/classic/dtree.css" type=text/css rel=stylesheet>
        <SCRIPT language=JavaScript1.2 src="<?=$_classPath.'admpanel/'?>java/dtree.js" type=text/javascript></SCRIPT>
        <script language="JavaScript1.2" src="<?=$_classPath.'admpanel/'?>java/javaMG.js" type="text/javascript"></script>
    </head>
    <body bottommargin="0" rightmargin="0" topmargin="0" leftmargin="0" bgcolor="#ffffff">
        <div style="padding:10px">
            <?
            // Дерево каталогов
            $CatalogTree = &new FileCatalogTree($GLOBALS['SysValue']['base']['table_name']);
            $CatalogTree->addcat(0,-1,'Мои шаблоны','');
            $CatalogTree->create();
            $CatalogTree->disp();
            ?>
        </div>
    </body>
</html>

