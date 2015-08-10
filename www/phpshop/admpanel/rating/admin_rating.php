<?

function Rating()// Вывод рейтинга
{
global $SysValue,$systems;
$sql="select * from ".$SysValue['base']['table_name50']." order by id_category desc";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id_category=$row['id_category'];
	$name=$row['name'];
	$ids_dir=$row['ids_dir'];
	$enabled=$row['enabled'];
	if(@$enabled!=0) {
		$imgchek="<img src=\"img/icon-activate.gif\" width=\"16\" height=\"16\" border=\"0\">";
	} else {
		$imgchek="<img src=\"img/icon-deactivate.gif\" width=\"16\" height=\"16\" border=\"0\">";
	}
	@$display.="
	<tr onmouseover=\"show_on('r".$id_category."')\" id=\"r".$id_category."\" onmouseout=\"show_out('r".$id_category."')\" class=row onclick=\"miniWin('rating/adm_ratingID.php?id=$id_category',650,550)\">
    <td align=\"center\" class=forma>
	$imgchek
	</td>
	<td class=forma>
	$name
	</td>
    </tr>
	";
	@$i++;
	}
if($i>30)$razmer="height:600;";
	$_Return="
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 
<table width=\"50%\"  cellpadding=\"0\" cellspacing=\"0\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" class=\"sortable\" id=\"sort\">
<tr>
	<td width=\"10%\" id=pane align=center>+/-</td>
	<td width=\"90%\" id=pane align=><span name=txtLang id=txtLang>Заголовок</span></td>
</tr>
	".$display."
    </table>
</table>
<div align=\"right\" style=\"width:50%;padding:10\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('rating/adm_rating_new.php',630,450)\">
<img src=\"icon/page_new.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>

	";
return $_Return;
}
?>
