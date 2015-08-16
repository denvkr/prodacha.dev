<?

function Delivery()// Вывод доставки
{
global $SysValue;

$_Return='
<!--Управляющий интерфейс-->
	 <form method="post" name="search">
	 <table width="100%" cellpadding="0" cellpadding="0" class="iconpane">
<tr>
<td>

<table cellpadding="0" cellspacing="0">
<tr>
   <td width="10"></td>
    <td id="but23"  class="butoff"><img name="imgLang" src="icon/blank.gif" alt="" width="1" height="1" border="0"><img name="imgLang" src="icon/page_new.gif" alt="Новая доставка" width="16" height="16" border="0" onmouseover="ButOn(23)" onmouseout="ButOff(23)" onclick="NewDelivery()">
    </td>
    <td width="3"></td>
	<td id="but1"  class="butoff"><img name="imgLang" src="icon/folder_add.gif" alt="Новый каталог" width="16" height="16" border="0" onmouseover="ButOn(1)" onmouseout="ButOff(1)" onclick="NewDeliveryCatalog();return false;"></td>
<td width="5"></td>
	<td width="1" bgcolor="#ffffff"></td>
	<td width="1" bgcolor="#808080"></td>
   <td width="5"></td>
	<td id="but37" class="butoff"><img name="imgLang" src="icon/folder_edit.gif" alt="Редактировать каталог" width="16" height="16" border="0" onmouseover="ButOn(37)" onmouseout="ButOff(37)" onclick="EditCatalogDelivery();return false;"></td>
   <td width="5"></td>

   <td>
<!--   
   <select name="actionSelect" size="1" id="actionSelect" onchange="DoWithSelect(this.value,window.frame2.document.form_flag,1000)">
			<option SELECTED id=txtLang value=0>С отмеченными</option>
			<option value="30" id=txtLang>Включить вывод</option>
			<option value="31" id=txtLang>Отключить вывод</option>
			<option value="39" id=txtLang>Удалить из базы</option>
   </select>
-->
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
	<td id=pane align=center><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Каталоги</span></td>
<td rowspan=2 valign=\"top\">
<!--вывод основной части-->
<iframe id=interfacesWin1 src=\"delivery/admin_delivery_content.php\" width=\"100%\" height=\"580\" name=\"frame2\" frameborder=\"0\" scrolling=\"Auto\" ></iframe>
<!--вывод основной части-->
</td>
</tr>
<tr valign=\"top\">
	<td width=\"300px\">
<!--вывод каталога-->
<iframe id=interfacesWin2 src=\"delivery/tree.php\" width=\"300px\" height=\"520\" scrolling=\"Auto\" name=\"frame1\" frameborder=\"0\"></iframe>
<!--вывод каталога-->
<!--табличка с управлением каталогом-->
<div align=\"center\" style=\"padding:5\">
<table cellpadding=\"0\" cellspacing=\"0\">
  <tr>
  <td id=\"but50\"  class=\"butoff\"><img  name=imgLang src=\"icon/chart_organisation_add.gif\" alt=\"Открыть все\" width=\"16\" height=\"16\" border=\"0\"  onclick=\"window.frame1.d4.openAll()\">
    </td>
   	<td width=\"10\"></td>
	<td width=\"1\" bgcolor=\"#ffffff\"></td>
	<td width=\"1\" bgcolor=\"#808080\"></td>
   <td width=\"5\"></td>
	<td id=\"but51\"  class=\"butoff\"><img name=imgLang src=\"icon/chart_organisation_delete.gif\" alt=\"Закрыть все\" width=\"16\" height=\"16\" border=\"0\" onclick=\"window.frame1.d4.closeAll()\"></td>
  </tr>
</table>
</div>
<!--табличка с управлением каталогом-->
   </td>
</tr>
</table>
";
return $_Return;
}
?>