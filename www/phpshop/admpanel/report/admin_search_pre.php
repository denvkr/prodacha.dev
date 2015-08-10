<?

function SearchPre() {// Вывод
    global $SysValue;

    $numRows = 0;
    $display = null;

    $sql = "select * from " . $SysValue['base']['table_name26'] . " order by id desc";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $name = str_replace("ii", ",", $row['name']);
        $name = str_replace("i", "", $name);
        $uid = $row['uid'];
        if ($row['enabled'] == 1) {
            $fl = "<img src=\"img/icon-activate.gif\" width=\"16\" height=\"14\" border=\"0\">";
        } else {
            $fl = "<img src=\"img/icon-deactivate.gif\" width=\"16\" height=\"14\" border=\"0\">";
        }

        // Выделение четных строк
        $numRows++;
        if ($numRows % 2 == 0) {
            $style_r = ' line2';
        } else {
            $style_r = null;
        }

        $display.='<tr class="row ' . $style_r . '" id="r' . $id . '" onmouseover="PHPShopJS.rowshow_on(this)" onmouseout="PHPShopJS.rowshow_out(this,\'' . $style_r . '\')" onclick="miniWin(\'report/adm_preID.php?id=' . $id . '\',400,380)">';

        $display.="
    <td align=\"center\">$fl</td>
	<td>
	$name
	</td>
	<td>
	$uid
	</td>
	<td class=forma>
	<input type=checkbox name='c" . $id . "' value=\"" . $id . "\">
	</td>
    </tr>
	";
        @$i++;
    }
    if ($i > 20)
        $razmer = "height:600;";
    $_Return = "
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;" . @$razmer . ";overflow:auto\"> 
<form name=\"form_flag\">
<table width=\"100%\"  cellpadding=\"0\" cellspacing=\"0\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"25\" id=pane align=center><img  src=\"icon/blank.gif\"  width=\"1\" height=\"1\" border=\"0\" onLoad=\"starter('search_pre');\" align=left>&plusmn;</td>
	<td width=\"40%\" id=pane align=center><span name=txtLang id=txtLang>Запрос</span></td>
	<td width=\"50%\" id=pane align=center><span name=txtLang id=txtLang>ID товаров</span></td>
    <td width=\"25\" id=pane align=center style=\"padding:1px\"><input type=checkbox value=1 name=DoAll onclick=\"SelectAllBox(this,form_flag)\"></td>
</tr>

	" . $display . "

    </table>
	</td>
</tr>
    </table>
	</form>
</div>
	" . '
	<div class=cMenu id=cMenuNws> 
	<TABLE style="width:260px;"  border="0" cellspacing="0" cellpadding="0">
	<TR><TD id="txtLang" STYLE="background: #C0D2EC;"><B>Действия</B></TD></TR>
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews17>Удалить из базы</A></TD></TR>
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews18>Заблокировать</A></TD></TR>	
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews19>Задействовать</A></TD></TR>		
	</TABLE>
</div>';
    return $_Return;
}

?>