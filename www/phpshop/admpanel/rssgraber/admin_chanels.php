<?

function RSSchanels() {// Вывод 
    global $SysValue;

    $numRows = 0;
    $display = null;

    $sql = "select * from " . $SysValue['base']['table_name38'] . " order by id";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $link = $row['link'];
        $day_num = $row['day_num'];
        $news_num = $row['news_num'];
        if ($row['start_date'] == 0) {
            $start_date = '';
        }
        else
            $start_date = date("d-m-Y", $row['start_date']);

        if ($row['end_date'] == 0) {
            $end_date = '';
        }
        else
            $end_date = date("d-m-Y", $row['end_date']);

        if (($row['enabled']) == "1") {
            $checked = "<img src=img/icon-activate.gif  width=\"16\" height=\"16\" alt=\"В наличии\">";
        } else {
            $checked = "<img src=img/icon-deactivate.gif  width=\"16\" height=\"16\" alt=\"Отсутствует\">";
        };

        // Выделение четных строк
        $numRows++;
        if ($numRows % 2 == 0) {
            $style_r = ' line2';
        } else {
            $style_r = null;
        }


        $display.='<tr class="row ' . $style_r . '" id="r' . $id . '" onmouseover="PHPShopJS.rowshow_on(this)" onmouseout="PHPShopJS.rowshow_out(this,\'' . $style_r . '\')">';


        $display.='
    <td align=center onclick="miniWin(\'rssgraber/adm_chanelsID.php?id=' . $id . '\',400,480)">
    ' . $checked . '
    </td>
    <td  onclick="miniWin(\'rssgraber/adm_chanelsID.php?id=' . $id . '\',400,480)">
	' . $link . '
	</td>
	<td  onclick="miniWin(\'rssgraber/adm_chanelsID.php?id=' . $id . '\',400,480)">
	' . $day_num . '
	</td>
	<td  onclick="miniWin(\'rssgraber/adm_chanelsID.php?id=' . $id . '\',400,480)">
	' . $news_num . '
	</td>
	<td  onclick="miniWin(\'rssgraber/adm_chanelsID.php?id=' . $id . '\',400,480)">
	' . $start_date . '
	</td>
	<td  onclick="miniWin(\'rssgraber/adm_chanelsID.php?id=' . $id . '\',400,480)">
	' . $end_date . '
	</td>
	<td class=forma style=\"padding:1px\" align=\"center\">
	<input type=checkbox name="c' . $id . '" value="' . $id . '">
	</td>
    </tr>
	';
        @$i++;
    }
    if ($i > 20)
        $razmer = "height:600;";
    return "
	
<div  id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;" . @$razmer . ";overflow:auto\"> 

<table width=\"100%\"  cellpadding=\"0\" cellspacing=\"0\">
<tr>
	<td valign=\"top\">
	<form name=\"form_flag\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"50\" id=pane align=center><img  src=\"icon/blank.gif\"  width=\"1\" height=\"1\" border=\"0\" onLoad=\"starter('chanels');\" align=left><span name=txtLang id=txtLang>Статус</span></td>
	<td id=pane align=center><span name=txtLang id=txtLang>Адрес ленты</span></td>
	<td width=\"100\" id=pane align=center><span name=txtLang id=txtLang>Заборов в день</span></td>
    <td width=\"100\" id=pane align=center><span name=txtLang id=txtLang></span>Кол-во  новостей</td>
    <td width=\"100\" id=pane align=center><span name=txtLang id=txtLang>Дата начала</span></td>
    <td width=\"100\" id=pane align=center><span name=txtLang id=txtLang>Дата конца</span></td>
    <td width=\"25\" id=pane align=center style=\"padding:0px\"><input type=checkbox value=1 name=DoAll onclick=\"SelectAllBox(this,form_flag)\"></td>
</tr>

	" . $display . "

    </table>
    </form>
	</td>
</tr>
    </table>


<div align=\"right\" style=\"padding:10;width:98%\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('rssgraber/adm_chanels_new.php',400, 480); return false;\">
<img src=\"icon/page_add.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая прозиция</span>
</BUTTON></div>
</div>";
}

?>
