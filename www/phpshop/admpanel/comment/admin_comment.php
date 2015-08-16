<?
function Comment($pole1,$pole2,$words)
{
global $SysValue;

if(empty($pole1)) $pole1=date("U")-86400;
 else $pole1=GetUnicTime($pole1)-86400;
if(empty($pole2)) $pole2=date("U");
 else $pole2=GetUnicTime($pole2)+86400;
 
 
if(!empty($words)){
if(is_numeric($words)) $sql="select * from ".$SysValue['base']['table_name36']." where  parent_id=".$words;
else $sql="select * from ".$SysValue['base']['table_name36']." where name REGEXP '".$words."' or  name REGEXP '".ucfirst($words)."'";
}
  else 
$sql="select * from ".$SysValue['base']['table_name36']." where datas<'$pole2' and datas>'$pole1' order by id desc";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$data=$row['datas'];
	$name=$row['name'];
	$content=$row['content'];
	$len=strlen($content);
	if($row['enabled']==1){
	$fl="<img src=\"img/icon-activate.gif\">";
	}else{
	$fl="<img src=\"img/icon-deactivate.gif\">";}
	
	@$display.="
	<tr id=\"r".$id."\" class=row>
    <td align=\"center\" id=Nws class=Nws onmouseover=\"show_on('r".$id."')\" onmouseout=\"show_out('r".$id."')\" onclick=\"miniWin('comment/adm_commentID.php?id=$id',650,580)\">
	$fl
	</td>
	<td align=\"center\" id=Nws class=Nws onmouseover=\"show_on('r".$id."')\" onmouseout=\"show_out('r".$id."')\" onclick=\"miniWin('comment/adm_commentID.php?id=$id',650,580)\">
	".dataV($data,false)."
	</td>
	<td id=Nws class=Nws onmouseover=\"show_on('r".$id."')\" onmouseout=\"show_out('r".$id."')\" onclick=\"miniWin('comment/adm_commentID.php?id=$id',650,580)\">
	$name
	</td>
	<td id=Nws class=Nws onmouseover=\"show_on('r".$id."')\" onmouseout=\"show_out('r".$id."')\" onclick=\"miniWin('comment/adm_commentID.php?id=$id',650,580)\">
	".mySubstr($content,200)."...
	</td>
	<td>
	<input type=checkbox name=\"c".$id."\" value=\"$id\">
	</td>
    </tr>
	";
	@$i++;
	}
	if($i>15)$razmer="height:600;";
	$_Return="
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 

<form name=\"form_flag\">
<table width=\"100%\"  cellpadding=\"0\" cellspacing=\"0\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\"  class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"5%\" id=pane align=center>+/-</td>
	<td width=\"12%\" id=pane align=center><img  src=\"icon/blank.gif\"  width=\"1\" height=\"1\" border=\"0\" onLoad=\"starter('comment');\" align=left><span name=txtLang id=txtLang>Дата</span></td>
	<td width=\"20%\" id=pane align=center><span name=txtLang id=txtLang>Автор</span></td>
	<td width=\"65%\" id=pane align=center><span name=txtLang id=txtLang>Комментарий</span></td>
	<td width=\"20\" id=pane align=center style=\"padding:0px\"><input type=checkbox value=1 name=DoAll onclick=\"SelectAllBox(this,form_flag)\"></td>
</tr>
	".$display."
    </table>
	</td>
</tr>
</table>
</form>
</div>
	".'
<div class=cMenu id=cMenuNws> 
	<TABLE style="width:260px;"  border="0" cellspacing="0" cellpadding="0">
	<TR><TD id="txtLang" STYLE="background: #C0D2EC;"><B>Действия</B></TD></TR>
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews43>Разблокировать вывод</A></TD></TR>
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews44>Заблокировать вывод</A></TD></TR>	
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews41>Удалить из базы</A></TD></TR>	
	</TABLE>
</div>
';

return $_Return;
}
?>
