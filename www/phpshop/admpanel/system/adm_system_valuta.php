<?

function Valuta()// Вывод валют
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name24']." order by num";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$code=$row['code'];
	$iso=$row['iso'];
	$kurs=$row['kurs'];
	$enabled=$row['enabled'];
	if(($enabled)=="1"){$checked="<img src=img/icon-activate.gif  >";}else{$checked="<img src=img/icon-deactivate.gif>";};
	@$display.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('system/adm_valutaID.php?id=$id',400,270)\">
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
if($i>30)$razmer="height:600;";
	return "
<div align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 
<table width=\"50%\"  cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style: inset;\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\" class=\"sortable\" id=\"sort\">
<tr>
 <td width=\"50\" id=pane align=>+/-</td>
    <td  id=pane align=><span name=txtLang id=txtLang>Название валюты</span></td>
	<td  id=pane align=><span name=txtLang id=txtLang>Обозначение в магазине</span></td>
	<td  id=pane align=><span name=txtLang id=txtLang>Код валюты</span> ISO</td>
	<td  id=pane align=><span name=txtLang id=txtLang>Курс</span></td>
</tr>
	".$display."
    </table>
</table>

<div align=\"right\" style=\"width:50%;padding:10\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('system/adm_valuta_new.php',400,350)\">
<img src=\"icon/page_add.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div></div>
	";
}
?>
