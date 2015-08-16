<?

function Opros()// Вывод опроса
{
global $table_name6,$systems;
$sql="select * from $table_name6 order by id desc";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$dir=$row['dir'];
	$flag=$row['flag'];
	if(@$flag!=0)
	 {
	 $imgchek="<img src=\"img/icon-activate.gif\" width=\"16\" height=\"16\" border=\"0\">";
	 }
	 else
	    {
		$imgchek="<img src=\"img/icon-deactivate.gif\" width=\"16\" height=\"16\" border=\"0\">";
		}
	@$display.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('opros/adm_oprosID.php?id=$id',650,550)\">
    <td align=\"center\" class=forma>
	$imgchek
	</td>
	<td class=forma>
	$name
	</td>
	<td class=forma>
	$dir
	</td>
    </tr>
	";
	@$i++;
	}
if($i>30)$razmer="height:600;";
	$_Return="
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 
<table width=\"100%\"  cellpadding=\"0\" cellspacing=\"0\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" class=\"sortable\" id=\"sort\">
<tr>
	<td width=\"10%\" id=pane align=center>+/-</td>
	<td width=\"60%\" id=pane align=><span name=txtLang id=txtLang>Заголовок</span></td>
	<td width=\"30%\" id=pane align=center><span name=txtLang id=txtLang>Привязка</span></td>
</tr>
	".$display."
    </table>
</table>
<div align=\"right\" style=\"padding:10\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('opros/adm_opros_new.php',630,330)\">
<img src=\"icon/page_new.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>

	";
return $_Return;
}
?>
