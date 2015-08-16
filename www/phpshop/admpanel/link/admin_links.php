<?

function Links()// Вывод
{
global $table_name17,$PHP_SELF,$systems,$page;
$sql="select * from $table_name17 order by num desc";
$result=mysql_query($sql);
while (@$row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$image=$row['image'];
	$opis=$row['opis'];
	$link=$row['link'];
	$num=$row['num'];
	$enabled=$row['enabled'];
	if($enabled==1)
	 {
	 $imgchek="<img src=\"img/icon-activate.gif\" width=\"16\" height=\"16\" border=\"0\">";
	 }
	 else
	    {
		$imgchek="<img src=\"img/icon-deactivate.gif\" width=\"16\" height=\"16\" border=\"0\">";
		}
	@$display.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('link/adm_linksID.php?id=$id',630,550)\">
<td align=\"center\" class=forma>
	$imgchek
	</td>
	<td class=forma>
	$name
	</td>
	<td class=forma>
	$opis
	</td>
	<td class=forma>
	$image
	</td>
    </tr>
	";
	@$i++;
	}
	if($i>10)$razmer="height:600;";
	$_Return="
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 
<table width=\"100%\"  cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style: inset;\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\" class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"5%\" id=pane align=center>+/-</td>
	<td width=\"30%\" id=pane align=center><span name=txtLang id=txtLang>Название</span></td>
	<td width=\"50%\" id=pane align=center><span name=txtLang id=txtLang>Описание</span></td>
	<td width=\"10%\" id=pane align=center><span name=txtLang id=txtLang>Кнопка</span></td>
</tr>
	".@$display."
    </table>
	</td>
</tr>
</table>
<div align=\"right\" style=\"padding:10\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('link/adm_links_new.php',630,550)\">
<img src=\"icon/page_new.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>

	";
return $_Return;
}
?>
