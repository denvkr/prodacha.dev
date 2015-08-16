<?php

$_classPath = "../../";
require("../connect.php");
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");

// Подключение к БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

// Подключаем библиотеку поддержки.
require_once "../../lib/Subsys/JsHttpRequest/Php.php";

$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

// Характеристики
function Sorts() {
    global $SysValue;
    $sql = "select * from " . $SysValue['base']['table_name21'];
    $result = mysql_query($sql);
    $Sorts = '';
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $name = $row['name'];
        $page = $row['page'];
        $array = array(
            "id" => $id,
            "page" => $page,
            "name" => $name
        );
        $Sorts[$id] = $array;
    }
    return $Sorts;
}

// Каталоги Характеристик
function CatalogSorts() {
    global $SysValue;
    $sql = "select * from " . $SysValue['base']['table_name20'];
    @$result = mysql_query($sql);
    $Sorts = '';
    while (@$row = mysql_fetch_array(@$result)) {
        $id = $row['id'];
        $name = $row['name'];
        $category = $row['category'];
        $filtr = $row['filtr'];
        $flag = $row['flag'];
        $goodoption = $row['goodoption'];
        $page = $row['page'];
        $array = array(
            "id" => $id,
            "name" => $name,
            "category" => $category,
            "filtr" => $filtr,
            "page" => $page,
            "flag" => $flag,
            "goodoption" => $goodoption
        );
        $Sorts[$id] = $array;
    }
    @$SysValue['sql']['num']++;
    return $Sorts;
}

function SortDisp($vendor_array) {

    $dis = null;
    $numRows = 0;

    $Sort = Sorts();
    $CatalogSort = CatalogSorts();
    $vendor_array = unserialize($vendor_array);
    foreach ($vendor_array as $key => $val)
        foreach ($val as $v) {

            // Выделение четных строк
            $numRows++;
            if ($numRows % 2 == 0) {
                $style_r = ' line2';
            } else {
                $style_r = null;
            }

            $dis.='<tr class="row ' . $style_r . '" id="r' . $numRows . '" onmouseover="PHPShopJS.rowshow_on(this)" onmouseout="PHPShopJS.rowshow_out(this,\'' . $style_r . '\')" onclick="miniWin(\'./sort/adm_sortID.php?id=' . $key . '\',500,600)">';


            $dis.='
	      <td>' . $CatalogSort[$key]['name'] . '</td>
		  <td>' . $Sort[$v]['name'] . '</td>
	   </tr>
	   ';
        }

    $disp = "<table cellpadding=0 border=0 cellspacing=1 width=100%>
	   <tr>
	      <td id=pane>Характеристика</td>
	      <td id=pane>Значение</td>
	   </tr>
$dis
</table>
";
    return $disp;
}

switch ($_REQUEST['do']) {

    case("info"):
        $sql = "select * from  " . $SysValue['base']['table_name2'] . " where id=" . intval($_REQUEST['xid']);
        $result = mysql_query(@$sql);
        $num = mysql_numrows($result);
        @$row = @mysql_fetch_array(@$result);
        $id = $row['id'];
        $parent_enabled = $row['parent_enabled'];
        if (($row['enabled']) == "1") {
            $checked = "<td width=100 align=center><img src=./img/icon-activate.gif name=imgLang  alt=\"В наличии\" align=\"absmiddle\"> В наличии</td><td width=1></td>";
        } else {
            $checked = "<td width=100><img src=./img/icon-deactivate.gif name=imgLang  alt=\"Отсутствует\" align=\"absmiddle\"> Отсутствует</td><td width=1></td>";
        };


        if (($row['spec']) == "1") {
            $checked.="<td width=100 align=center><img name=imgLang src=./img/icon-duplicate-acl.gif align=\"absmiddle\" alt=\"Спецпредложение\"> Спец</td><td width=1></td>";
        }
        else
            $checked.="<td  width=100></td><td width=1></td>";

        if (($row['yml']) == "1") {
            $checked.="<td  width=100 align=center><img name=imgLang src=./img/icon-duplicate-banner.gif align=\"absmiddle\"  alt=\"YML прайс\"> Маркет</td><td width=1></td>";
        }
        else
            $checked.="<td  width=100></td><td width=1></td>";

        if (($row['newtip']) == "1")
            $checked.="<td  width=100 align=center><img name=imgLang src=./img/icon-move-banner.gif  align=\"absmiddle\" alt=\"Новинка\"> Новинка</td><td width=1></td>";
        else
            $checked.="<td width=100></td><td width=1></td>";

        if (($row['sklad']) == "1")
            $checked.="<td width=100 align=center><img name=imgLang src=./icon/cart_error.gif align=\"absmiddle\"  alt=\"Уведомление, под заказ\"> Под заказ</td><td width=1></td>";
        else
            $checked.="<td  width=100></td><td width=1></td>";

        if ($parent_enabled == 1)
            $checked.="<td  width=100 align=center><img name=imgLang src=./icon/plugin.gif align=\"absmiddle\"   alt=\"Подтип товара\"> Подтип товара</td><td width=1></td>";
        else
            $checked.="<td  width=100></td><td width=1></td>";



        $interfaces = '
	  <table width="100%"  cellpadding="0" cellspacing="1">
    <tr class=row3>
	' . $checked . '
	   </tr>
      </table>
	  ';
        break;

    case("prev"):
        $pic_small = "img/icon_non.gif";
        $sql = "select * from  " . $SysValue['base']['table_name2'] . " where id=" . intval($_REQUEST['xid']);
        $result = mysql_query(@$sql);
        $num = mysql_numrows($result);
        @$row = @mysql_fetch_array(@$result);
        $id = $row['id'];
        if (!empty($row['pic_small']))
            $pic_small = $row['pic_small'];
        $vendor_array = $row['vendor_array'];
        $description = stripslashes($row['description']);

        if ($num > 0) {

            $interfaces = '
      <table cellpadding="0" cellspacing="1"  border="0">
	  <tr>
      <td valign="top" align="center" width="150" style="cursor: pointer;" bgcolor="#ffffff">
	  ';
            if (!empty($pic_small))
                $interfaces.='
	  <table width="100%"  cellpadding="0" cellspacing="0">
<tr>
	<td valign="top">
	  <a href="javascript:miniWin(\'./product/adm_productID.php?productID=' . $id . '\',650,630)"><iframe src="' . $pic_small . '" width="130" height="150" scrolling="No" frameborder="0"></iframe></a> </td>
	   </tr>
      </table>';

            $interfaces.='
	  </td>
	  <td valign="top" width="40%" bgcolor="#ffffff">
	  <div style="height:150;overflow:auto">
	 ' . SortDisp($vendor_array) . '
	  </div>
	  </td>
	  <td valign="top" width="40%">
	  <table cellpadding=0 border=0 cellspacing=1 width=100%>
	   <tr>
	      <td id=pane>Описание</td>
	   </tr>
      <tr class=row3>
	      <td class=Nws>
		  <div style="height:120;overflow:auto;padding:5px">
		  ' . $description . '
		  </div>
		  </td>
	   </tr>
      </table>
	  </td>
      </tr>
     </table>
	 ';
        }
        else
            $interfaces = "";
        break;

    case("del"):
        $sql = "delete from " . $SysValue['base']['table_name35'] . " where id=" . intval($_REQUEST['xid']);
        mysql_query($sql);

        // Удаляем изображение с сервера
        $name = $_REQUEST['img'];
        $s_name = str_replace(".", "s.", $name);
        unlink($_SERVER['DOCUMENT_ROOT'] . $name);
        unlink($_SERVER['DOCUMENT_ROOT'] . $s_name);

        $sql = "select * from " . $SysValue['base']['table_name35'] . " where parent=" . intval($_REQUEST['uid']) . " order by num desc";
        $result = mysql_query($sql);

        $i = 1;
        while ($row = mysql_fetch_array($result)) {
            $name = $row['name'];
            $id = $row['id'];
            @$dis.="
	<tr onmouseover=\"show_on('r" . $id . "')\" id=\"r" . $id . "\" onmouseout=\"show_out('r" . $id . "')\" class=row onclick=\"miniWin('adm_galeryID.php?id=$id',650,500)\">
	  <td align=center>$i</td>
	   <td>$name</td>
	</tr>
	";
            $i++;
        }

        $interfaces = '
<table cellpadding="0" cellspacing="1"  border="0" width="100%">
<tr>
    <td width="20" id=pane align=center>№</td>
	<td id=pane align=center>Размещение</td>
</tr>
    ' . $dis . '
    </table>
';
        break;


    case("update"):

        $sql = "select * from " . $SysValue['base']['table_name35'] . " where parent=" . intval($_REQUEST['xid']) . " order by num desc";
        $result = mysql_query($sql);
        $i = 1;
        while ($row = mysql_fetch_array($result)) {
            $name = $row['name'];
            $id = $row['id'];
            @$dis.="
	<tr onmouseover=\"show_on('r" . $id . "')\" id=\"r" . $id . "\" onmouseout=\"show_out('r" . $id . "')\" class=row onclick=\"miniWin('adm_galeryID.php?id=$id',650,500)\">
	  <td align=center>$i</td>
	   <td>$name</td>
	</tr>
	";
            $i++;
        }

        $interfaces = '
<table cellpadding="0" cellspacing="1"  border="0"  width="100%">
<tr>
    <td width="20" id=pane align=center>№</td>
	<td id=pane align=center>Размещение</td>
</tr>
    ' . $dis . '
    </table>
';
        break;


    case("num"):

        mysql_query("update " . $SysValue['base']['table_name35'] . " set num='" . intval($_REQUEST['num']) . "', info='" . $_REQUEST['info'] . "' where id=" . $_REQUEST['xid']);

        $sql = "select * from " . $SysValue['base']['table_name35'] . " where parent=" . intval($_REQUEST['uid']) . " order by num desc";

        $result = mysql_query($sql);
        $i = 1;
        while ($row = mysql_fetch_array($result)) {
            $name = $row['name'];
            $id = $row['id'];
            @$dis.="
	<tr onmouseover=\"show_on('r" . $id . "')\" id=\"r" . $id . "\" onmouseout=\"show_out('r" . $id . "')\" class=row onclick=\"miniWin('adm_galeryID.php?id=$id',650,500)\">
	  <td align=center>$i</td>
	   <td>$name</td>
	</tr>
	";
            $i++;
        }

        $interfaces = '
<table cellpadding="0" cellspacing="1"  border="0"  width="100%">
<tr>
    <td width="20" id=pane align=center>№</td>
	<td id=pane align=center>Размещение</td>
</tr>
    ' . $dis . '
    </table>
';
        break;
}




$_RESULT = array(
    "interfaces" => @$interfaces
);
?>