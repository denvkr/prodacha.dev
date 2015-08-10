<?

function Shopusers_messages() {
global $SysValue;

$_Return='
<!--Управляющий интерфейс-->
	 <form method="post" name="search">
	 <table width="100%" cellpadding="0" cellpadding="0">
<tr>
<td>

<table cellpadding="0" cellspacing="0">
<tr>
   <td width="5"></td>
   <td id="but31"  class="butoff"><img name="imgLang" src="icon/blank.gif" alt="" width="1" height="1" border="0"><img name="imgLang" src="icon/folder_key.gif" alt="Обзор статусов" title="Обзор статусов" width="16" height="16" border="0" onmouseover="ButOn(31)" onmouseout="ButOff(31)" onclick="DoReload(\'shopusers_status\')">
    </td>
   <td width="5"></td>
   <td id="but32"  class="butoff"><img name="imgLang" src="icon/folder_bell.gif" alt="Обзор уведомлений" title="Обзор уведомлений" width="16" height="16" border="0" onmouseover="ButOn(32)" onmouseout="ButOff(32)" onclick="DoReload(\'shopusers_notice\')">
    </td>
   <td width="5"></td>
	<td width="1" bgcolor="#ffffff"></td>
	<td width="1" bgcolor="#808080"></td>
   <td width="5"></td>
    <td id="but33"  class="butoff"><img name="imgLang" src="icon/page_new.gif" alt="Новое сообщение" title="Новое сообщение" width="16" height="16" border="0" onmouseover="ButOn(33)" onmouseout="ButOff(33)" onclick="NewUMessage()">
    </td>
    <td width="5"></td>
	<td id="but34"  class="butoff"><img name="imgLang" src="icon/layout_delete.gif" alt="Удалить сообщения" title="Удалить сообщения" width="16" height="16" border="0" onmouseover="ButOn(34)" onmouseout="ButOff(34)" onclick="DeleteUMessages()"></td>
<td width="5"></td>
   <td width="10"></td>
   <td>
   </td>
</tr>
</table>
</form>
</td>
</td>
</tr>
</table>
'."

<!--основное окно-->
<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">
<tr>
	<td id=pane align=center><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Пользователи	</span></td>
<td rowspan=2 valign=\"top\">
<!--вывод основной части-->
<iframe id=interfacesWin1 src=\"shopusers/adm_messages_content.php\" width=\"100%\" height=\"580\" name=\"frame2\" frameborder=\"0\" scrolling=\"Auto\" ></iframe>
<!--вывод основной части-->
</td>
</tr>
<tr valign=\"top\">
	<td width=\"300px\">
<!--вывод каталога пользователей-->
<iframe id=interfacesWin2 src=\"shopusers/tree.php\" width=\"300px\" height=\"520\" scrolling=\"Auto\" frameborder=\"0\" name=\"frame1\"></iframe>
<!--вывод каталога пользователей-->

   </td>
</tr>
</table>
";
return $_Return;
}

?>