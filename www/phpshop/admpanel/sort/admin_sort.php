<?

function SortsGroup() {// Вывод сортировки
    global $SysValue;


    $numRows = 0;
    $display = null;

    $sql = "select * from " . $SysValue['base']['table_name20'] . " where category=0 order by name";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $name = $row['name'];
        $description = $row['description'];

        // Выделение четных строк
        $numRows++;
        if ($numRows % 2 == 0) {
            $style_r = ' line2';
        } else {
            $style_r = null;
        }

        $display.='<tr title="' . $description . '" class="row ' . $style_r . '" id="r' . $id . '" onmouseover="PHPShopJS.rowshow_on(this)" onmouseout="PHPShopJS.rowshow_out(this,\'' . $style_r . '\')"  onclick="miniWin(\'sort/adm_sortcategoryID.php?id=' . $id . '\',500,550)">';


        $display.="
	<td  style=\"padding-left: 20px\">
	$name
	</td>
    </tr>
	";
        @$i++;
    }
    if ($i > 25)
        $razmer = "height:600;";
    return "
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;" . @$razmer . ";overflow:auto\"> 
<table width=\"50%\"  cellpadding=\"0\" cellspacing=\"0\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" class=\"sortable\" id=\"sort\">
<tr>
	<td width=\"90%\" id=pane><span name=txtLang id=txtLang>Наименование</span></td>
</tr>
	" . $display . "
    </table>
</table>
<div align=\"right\" style=\"width:50%;padding:10\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('sort/adm_sortcategory_new.php',500,400)\">
<img src=\"icon/page_add.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>

	";
}

function Sorts() {// Вывод сортировки
    global $SysValue;

    $numRows = 0;
    $display = null;

    $sql = "select * from " . $SysValue['base']['table_name20'] . " where category!=0 order by name";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {


        // Выделение четных строк
        $numRows++;
        if ($numRows % 2 == 0) {
            $style_r = ' line2';
        } else {
            $style_r = null;
        }

        $id = $row['id'];
        $name = $row['name'];
        $description = $row['description'];
        if ($row['filtr'] == 1) {
            $fl = "<img src=\"img/icon-duplicate-acl.gif\" alt=\"Фильтр\">";
        } else {
            $fl = "";
        }

        if ($row['goodoption'] == 1) {
            $gl = "<img src=\"img/icon-duplicate-banner.gif\" alt=\"Опция\">";
        } else {
            $gl = "";
        }

        $display.='<tr class="row ' . $style_r . '" id="r' . $id . '" onmouseover="PHPShopJS.rowshow_on(this)" onmouseout="PHPShopJS.rowshow_out(this,\'' . $style_r . '\')" onclick="miniWin(\'sort/adm_sortID.php?id=' . $id . '\',500,600)" >';


        $display.="
<td align=\"center\">$fl $gl</td>
	<td>
	$name
	</td>
    </tr>
	";
        @$i++;
    }
    if ($i > 25)
        $razmer = "height:600;";
    return "
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;" . @$razmer . ";overflow:auto\"> 
<table cellpadding=\"0\" cellspacing=\"1\"  width=\"50%\">
<tr>
	<td valign=\"top\" >
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"10%\" id=pane>+/-</td>
	<td width=\"90%\" id=pane><span name=txtLang id=txtLang>Наименование</span></td>
</tr>
	" . $display . "
    </table>
	</td>
	</tr>
</table>
<div align=\"right\" style=\"width:50%;padding:10\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('sort/adm_sort_new.php',500,450)\">
<img src=\"icon/page_add.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>

	";
}

?>
