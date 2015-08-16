<?
function Baner()// Вывод новостей
{
global $table_name15,$PHP_SELF,$systems,$page;
$sql="select * from $table_name15 order by id desc";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$count_all=$row['count_all'];
	$count_today=$row['count_today'];
	$flag=$row['flag'];
	if($row['flag']==1){
	$fl="<img src=\"img/icon-activate.gif\">";
	}else{
	$fl="<img src=\"img/icon-deactivate.gif\">";}
	$limit_all=$row['limit_all'];
	@$display.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('baner/adm_banerID.php?id=$id',650,600)\">
	<td align=\"center\" class=forma>
	$fl
	</td>
	<td class=forma>
	$name
	</td>
	<td class=forma>
	$count_today
	</td>
	<td>
	$count_all
	</td>
	<td class=forma>
	$limit_all
	</td>
    </tr>
	";
	@$i++;
	}
	if($i>25)$razmer="height:600;";
	$_Return="
<div align=\"left\" id=interfacesWin name=interfacesWin style=\"width:100%;".@$razmer.";overflow:auto\"> 
<table width=\"100%\"  cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style: inset;\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\" class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"5%\" id=pane align=center>+/-</td>
	<td width=\"50%\" id=pane align=center><span name=txtLang id=txtLang>Название</span></td>
	<td width=\"10%\" id=pane align=center><span name=txtLang id=txtLang>Показов сегодня</span></td>
<td width=\"10%\" id=pane align=center><span name=txtLang id=txtLang>Всего показов</span></td>
<td width=\"10%\" id=pane align=center><span name=txtLang id=txtLang>Лимит показов</span></td>
</tr>
	".@$display."
</table>
</td>
</tr>
</table>
<div align=\"right\" style=\"padding:10\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('baner/adm_baner_new.php',630,600)\">
<img src=\"icon/page_add.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>

	";
return $_Return;
}
?>
