<?

function Valuta() {// Вывод валют
    global $SysValue;


    $numRows = 0;
    $display = null;

    $sql = "select * from " . $SysValue['base']['table_name24'] . " order by num";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $name = $row['name'];
        $code = $row['code'];
        $iso = $row['iso'];
        $kurs = $row['kurs'];
        $enabled = $row['enabled'];
        if (($enabled) == "1") {
            $checked = "<img src=img/icon-activate.gif  >";
        } else {
            $checked = "<img src=img/icon-deactivate.gif>";
        }

        // Выделение четных строк
        $numRows++;
        if ($numRows % 2 == 0) {
            $style_r = ' line2';
        } else {
            $style_r = null;
        }


        $display.='<tr class="row ' . $style_r . '" id="r' . $id . '" onmouseover="PHPShopJS.rowshow_on(this)" onmouseout="PHPShopJS.rowshow_out(this,\'' . $style_r . '\')" onclick="miniWin(\'valuta/adm_valutaID.php?id=' . $id . '\',400,360)">';


        $display.="
<td align=\"center\">$checked</td>
<td class=forma>
	$name
	</td>
	<td class=forma>
	$code
	</td>
	<td class=forma>
	$iso
	</td>
	<td class=forma>$kurs</td>
    </tr>
	";
        @$i++;
    }
    if ($i > 30)
        $razmer = "height:600;";
    return "
<div align=\"left\" style=\"width:100%;" . @$razmer . ";overflow:auto\"> 
<table width=\"50%\"  cellpadding=\"0\" cellspacing=\"0\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" class=\"sortable\" id=\"sort\">
<tr>
 <td width=\"50\" id=pane align=>+/-</td>
    <td  id=pane align=><span name=txtLang id=txtLang>Название валюты</span></td>
	<td  id=pane align=><span name=txtLang id=txtLang>Обозначение в магазине</span></td>
	<td  id=pane align=><span name=txtLang id=txtLang>Код валюты</span> ISO</td>
	<td  id=pane align=><span name=txtLang id=txtLang>Курс</span></td>
</tr>
	" . $display . "
    </table>
</table>

<div align=\"right\" style=\"width:50%;padding:10\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('valuta/adm_valuta_new.php',400,360)\">
<img src=\"icon/page_add.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div></div>
	";
}

?>
