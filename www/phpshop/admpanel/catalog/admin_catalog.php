<?php

function Catalog() {
    global $table_name, $categoryID, $systems, $pid;

    $GetSystems = GetSystems();
    $systems = $GetSystems;
    $option = unserialize($GetSystems['admoption']);

    if ($option['prevpanel_enabled'] == 1)
        $prevpanel_enabled = "checked";




    return "

<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\">
<tr>
	<td id=pane align=center width=\"295\"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5 ><span name=txtLang id=txtLang>Каталоги</span></td>
    <td rowspan=2 valign=top width=\"100%\">
<iframe src=\"catalog/admin_cat_content.php\" width=\"100%\" height=\"400\"  name=\"frame2\" id=\"frame2\" frameborder=\"0\" scrolling=\"Auto\"></iframe>

<div style=\"padding:5px\">
<input type=\"hidden\" value=\"\" id=\"prevpanel_mem\">
<FIELDSET>
	  <LEGEND><input type=\"checkbox\" id=\"prevpanel_act\" value=\"1\" onclick=\"ClosePanelProductDisp()\" $prevpanel_enabled> Отображать иконку, описание и характеристики товара</LEGEND>
<div id='prevpanel' style=\"padding:5px;width:100%\">

</div>
	 </FIELDSET>
	</td>
</tr>
</div>


<tr valign=\"top\">
	<td ><iframe src=\"catalog/tree.php\" width=\"300\"  height=\"520\" frameborder=\"0\" scrolling=\"Auto\" name=\"frame1\" id=\"frame1\"></iframe>

<div align=\"center\" style=\"padding:5\">
<table cellpadding=\"0\" cellspacing=\"0\">
  <tr>
  <td id=\"but381\" class=\"butoff\" onmouseover=\"PHPShopJS.button_on(this)\" onmouseout=\"PHPShopJS.button_off(this)\"><img name=imgLang src=\"icon/chart_organisation_add.gif\" alt=\"Открыть все\" width=\"16\" height=\"16\" border=\"0\"  onclick=\"window.frame1.d.openAll()\">
    </td>
   	<td width=\"10\"></td>
	<td width=\"1\" bgcolor=\"#ffffff\"></td>
	<td width=\"1\" bgcolor=\"#808080\"></td>
   <td width=\"5\"></td>
	<td  id=\"but382\" class=\"butoff\" onmouseover=\"PHPShopJS.button_on(this)\" onmouseout=\"PHPShopJS.button_off(this)\"><img name=imgLang src=\"icon/chart_organisation_delete.gif\" alt=\"Закрыть все\" width=\"16\" height=\"16\" border=\"0\"  onclick=\"window.frame1.d.closeAll()\"></td>
  </tr>
</table>
</div>

</td>
</tr>
</table>
            ";
}

function Disp_cat_new($category) {// вывод каталогов в заглавии
    global $table_name;
    $sql = "select name from $table_name where id='$category'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $name = $row['name'];
    return $name;
}

function Cat_prod_disp_new() {// выборка каталогов
    global $table_name;
    $sql = "select * from $table_name where parent_to='0' order by num";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $name = $row['name'];
        @$dis_cat.="$name/pid=$id" . Cat_prod_disp_pod_new($id) . "#";
    }
    $leng = strlen($dis_cat);
    $dis_cat = substr($dis_cat, 0, $leng - 1);
    $dis_cat = eregi_replace("t", "т", $dis_cat);
    return @$dis_cat;
}

function Cat_prod_disp_pod_new($parent) {// выборка подкаталогов
    global $table_name;
    $sql = "select * from $table_name where parent_to='$parent' order by num";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $name = $row['name'];
        @$name_cat.="&$name (" . Cat_prod_disp_num_new($id) . ")/pid=$id";
    }
    return @$name_cat;
}

function Cat_prod_disp_num_new($n) {// выбор кол-ва товаров из данного подкатолога
    global $table_name2;
    $sql = "select id from $table_name2 where category='$n'";
    $result = mysql_query($sql);
    $num = mysql_num_rows($result);
    return $num;
}

function CategoryID($categoryID) {// выборка каталогов и вывод в правое поле
    global $SysValue;

    if ($categoryID == "all")
        $sql = "select * from " . $SysValue['base']['products'] . " order by datas desc";
    else
        $sql = "select * from " . $SysValue['base']['products'] . " where category=" . intval($categoryID) . " order by num desc";

    $result = mysql_query($sql);
    $num = 0;
    $numRows = 0;
    $dis = null;
    while (@$row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $name = $row['name'];
        $price = $row['price'];
        $parent_enabled = $row['parent_enabled'];
        if (($row['enabled']) == "1") {
            $checked = "<img src=../img/icon-activate.gif name=imgLang  title=\"В наличии\">";
        } else {
            $checked = "<img src=../img/icon-deactivate.gif name=imgLang  title=\"Отсутствует\">";
        };
        if (($row['spec']) == "1") {
            $checked.="&nbsp;&nbsp;<img name=imgLang src=../img/icon-duplicate-acl.gif  title=\"Спецпредложение\">";
        }
        if (($row['yml']) == "1") {
            $checked.="&nbsp;&nbsp;<img name=imgLang src=../img/icon-duplicate-banner.gif   title=\"YML прайс\">";
        }

        if (!empty($row['pic_small'])) {
            $checked.="&nbsp;&nbsp;<img name=imgLang src=../img/icon-filetype-jpg.gif   title=\"Изображение\">";
        }

        if (!empty($row['description'])) {
            $checked.="&nbsp;&nbsp;<img name=imgLang src=../icon/page_code.gif   title=\"Описание\">";
        }

        if (($row['newtip']) == "1")
            $checked.="&nbsp;&nbsp;<img name=imgLang src=../img/icon-move-banner.gif   title=\"Новинка\">";
        if (($row['sklad']) == "1")
            $checked.="&nbsp;&nbsp;<img name=imgLang src=../icon/cart_error.gif   title=\"Уведомление, под заказ\">";
        if ($parent_enabled == 1)
            $checked.="&nbsp;&nbsp;<img name=imgLang src=../icon/plugin.gif   title=\"Подтип товара\" >";
        $uid = $row['uid'];
        $items = $row['items'];
        $ed_izm = $row['ed_izm'];

        if (empty($ed_izm))
            $ed_izm = "шт.";

        $baseinputvaluta = $row['baseinputvaluta'];

        $vsql = "select dengi from " . $SysValue['base']['table_name3'];
        $vresult = mysql_query($vsql);
        $vrow = mysql_fetch_array($vresult);
        $defaultvaluta = $vrow['dengi'];

        if ($defaultvaluta == $baseinputvaluta) {
            $baseinputvaluta = '';
        }


        //Если захочется выводить валюту всегда раскоментить блок ниже и закоментить if выше)
        /*
          if (!$baseinputvaluta) {
          $vsql="select dengi from ".$SysValue['base']['table_name3'];
          $vresult=mysql_query($vsql);
          $vrow = mysql_fetch_array($vresult);
          $baseinputvaluta=$vrow['dengi'];

          }
         */
        if ($baseinputvaluta) {
            $sqlv = "select * from " . $SysValue['base']['table_name24'] . " WHERE id=\"" . $baseinputvaluta . "\"";
            $resultv = mysql_query($sqlv);
            $vrow = mysql_fetch_array($resultv);
            $viso = ' ' . $vrow['code'];
        } else {
            $viso = '';
        }

        // Выделение четных строк
        $numRows++;
        if ($numRows % 2 == 0) {
            $style_r = ' line2';
        } else {
            $style_r = null;
        }

        $dis.="
	<tr class='row $style_r' id=\"r" . $id . "\" onmouseover=\"PHPShopJS.rowshow_on(this)\" onmouseout=\"PHPShopJS.rowshow_out(this,'" . $style_r . "')\">
	  <td align=center   align=\"left\" onclick=\"miniWin('../product/adm_productID.php?productID=$id',700,670)\">
                $checked
	  </td>
	  <td width=\"55\"  align=\"center\"  onclick=\"miniWin('../product/adm_productID.php?productID=$id',700,670)\">
                $id
	  </td>
	  <td width=\"500\"  onmouseover=\"DoUpdateProductDisp(" . $id . ");\"  onclick=\"miniWin('../product/adm_productID.php?productID=$id',700,650)\">
	  &nbsp;$name
	  </td>
	  <td width=\"100\"  onclick=\"miniWin('../product/adm_productID.php?productID=$id',700,650)\">
	  &nbsp;" . $items . " " . $ed_izm . "
	  </td>
	  <td width=\"100\"  onclick=\"miniWin('../product/adm_productID.php?productID=$id',700,650)\">
	  &nbsp;" . ($price * 1) . $viso . "
	  </td>
	  <td align=center>
	  <input type=\"checkbox\" value=\"" . $id . "\">
	  </td>
	</tr>
	";
        $num++;
    }


    $disp = "
<form name=\"form_flag\">
<table cellpadding=0 cellspacing=1 width=100% class=\"sortable\" id=\"sort\">
<tr valign=\"top\">
	<td width=\"100\" id=pane align=center><img  src=\"../icon/blank.gif\"  width=\"1\" height=\"1\" border=\"0\" ><span name=txtLang  id=txtLang>Вывод</span></td>
    <td width=\"55\" id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5>ID</td>
	<td width=\"540\" id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5>Наименование</td>
   <td width=\"100\" id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5>Склад</td>
    <td width=\"100\" id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5>Цена</td>
	<td width=\"25\" id=pane align=center>&plusmn;</td>
</tr>
" . $dis . "



</table>
</form>
<input type=\"hidden\" value=\"" . $_GET['pid'] . "\" id=\"catalog_products\" name=\"catalog_products\">
<input type=\"hidden\" value=\"" . $_GET['pid'] . "\" id=\"catal\" name=\"catal\">
<input type=\"hidden\" value=\"" . $_GET['pid'] . "\" id=\"catal_chek\" name=\"catal_chek\">
";
    return $disp;
}

function CategorySearch($words) { //поиск
    global $SysValue;
    $sql = "select * from " . $SysValue['base']['products'] . " where name LIKE '%$words%' or id='$words' or uid='$words' order by num";
    $result = mysql_query($sql);
    $num = 0;
    $numRows = 0;
    while (@$row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $name = $row['name'];
        $price = $row['price'];
        $items = $row['items'];
        $ed_izm = $row['ed_izm'];

        if (empty($ed_izm))
            $ed_izm = "шт.";
        $scategory = $row['category'];
        $low = 0;
        /*
          while ($scategory!=="0") {
          $sqlcat="select parent_to,secure_groups from $table_name where id=$scategory";
          $resultcat=mysql_query($sqlcat);
          $rowcat = mysql_fetch_array($resultcat);
          $secure_groups=$rowcat['secure_groups'];
          $scategory=$rowcat['parent_to'];
          if (strlen($secure_groups)) {
          $ider=trim($_SESSION['idPHPSHOP']);
          $string='i'.$ider.'-1i';
          if (strpos($secure_groups,$string) ===false) {
          $low=1; break;
          }
          }
          }
          if ($low) {continue;}
         */

        if (($row['enabled']) == "1") {
            $checked = "<img src=../img/icon-activate.gif  title=\"В наличии\">";
        } else {
            $checked = "<img src=../img/icon-deactivate.gif   title=\"Отсутствует\">";
        };
        if (($row['spec']) == "1") {
            $checked.="&nbsp;&nbsp;<img src=../img/icon-duplicate-acl.gif  title=\"Спецпредложение\">";
        }
        if (($row['yml']) == "1") {
            $checked.="&nbsp;&nbsp;<img src=../img/icon-duplicate-banner.gif   title=\"YML прайс\">";
        }
        if (($row['newtip']) == "1")
            $checked.="&nbsp;&nbsp;<img src=../img/icon-move-banner.gif   title=\"Новинка\">";

        if (!empty($row['pic_small'])) {
            $checked.="&nbsp;&nbsp;<img name=imgLang src=../img/icon-filetype-jpg.gif   title=\"Изображение\">";
        }

        if (!empty($row['description'])) {
            $checked.="&nbsp;&nbsp;<img name=imgLang src=../icon/page_code.gif   title=\"Описание\">";
        }
        $uid = $row['uid'];
        // Выделение четных строк
        $numRows++;
        if ($numRows % 2 == 0) {
            $style_r = ' line2';
        } else {
            $style_r = null;
        }

        $dis.="
	<tr class='row $style_r' id=\"r" . $id . "\" onmouseover=\"PHPShopJS.rowshow_on(this)\" onmouseout=\"PHPShopJS.rowshow_out(this,'" . $style_r . "')\">
	  <td align=center   align=\"left\" onclick=\"miniWin('../product/adm_productID.php?productID=$id',700,670)\">
                $checked
	  </td>
	  <td width=\"55\"  align=\"center\"  onclick=\"miniWin('../product/adm_productID.php?productID=$id',700,670)\">
                $id
	  </td>
	  <td width=\"500\"  onmouseover=\"DoUpdateProductDisp(" . $id . ");\"  onclick=\"miniWin('../product/adm_productID.php?productID=$id',700,650)\">
	  &nbsp;$name
	  </td>
	  <td width=\"100\"  onclick=\"miniWin('../product/adm_productID.php?productID=$id',700,650)\">
	  &nbsp;" . $items . " " . $ed_izm . "
	  </td>
	  <td width=\"100\"  onclick=\"miniWin('../product/adm_productID.php?productID=$id',700,650)\">
	  &nbsp;" . ($price * 1) . $viso . "
	  </td>
	  <td align=center>
	  <input type=\"checkbox\" value=\"" . $id . "\">
	  </td>
	</tr>
	";
        $num++;
    }




    $disp = "
<form name=\"form_flag\">
<table cellpadding=0 cellspacing=1 width=100% class=\"sortable\" id=\"sort\">
<tr valign=\"top\">
	<td width=\"100\" id=pane align=center><img  src=\"../icon/blank.gif\"  width=\"1\" height=\"1\" border=\"0\" onLoad=\"starter('product');\"><span name=txtLang  id=txtLang>Вывод</span></td>
    <td width=\"55\" id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5>ID</td>
	<td width=\"540\" id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5>Наименование</td>
   <td width=\"100\" id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5>Склад</td>
    <td width=\"100\" id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5>Цена</td>
	<td width=\"25\" id=pane align=center>&plusmn;</td>
</tr>
" . $dis . "

</form>

</table>
";
    return $disp;
}

?>