<?
function PHPSHOP_error($code,$flag)// Генерация ошибок и кодов ошибок
{
global $SysValue;
if(empty($flag)) return false;
else
return "
<style>
html,body{
height:100%;
margin:0px;
padding:0px
}
a { color:#1A79E5
}
</style>
<table width=\"100%\" height=\"100%\">
<tr>
	<td align=\"center\">

<table  height=50 cellpadding=5 cellspacing=1 bgcolor=red>
<tr bgcolor=ffffff align=center>
	<td>
	<font color=#FF0000><strong><img src=\"".$SysValue['dir']['dir']."/phpshop/admpanel/img/error.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\"> PHPSHOP WARNING <img src=\"".$SysValue['dir']['dir']."/phpshop/admpanel/img/error.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\"></strong><br><br>
	<a href=\"".$SysValue['dir']['dir']."/install/#error\" title=\"Нажмите для получения справки\"><img src=\"".$SysValue['dir']['dir']."/phpshop/admpanel/img/btn_help[1].gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\">
Код ошибки = $code</a></font><br>
<font size=\"1\">нажмите для получения справки</font>
	</td>
</tr>
</table>
</td>
</tr>
</table>
";
}
?>
