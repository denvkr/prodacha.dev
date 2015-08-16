<?
function SiteCatalog()
{
$_Return="
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
<tr>
	<td id=pane align=center><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Каталоги</span></td>
<td rowspan=2 valign=\"top\">
<!--вывод основной части-->
<iframe id=interfacesWin1 src=\"page/admin_cat_content.php\" width=\"100%\" height=\"580\" name=\"frame2\" frameborder=\"0\" scrolling=\"Auto\" ></iframe>
<!--вывод основной части-->
</td>
</tr>
<tr valign=\"top\">
	<td width=\"300\">
<!--вывод каталога-->
<iframe id=interfacesWin2 src=\"page/tree.php\" width=\"300\" height=\"550\" scrolling=\"Auto\" name=\"frame1\"></iframe>
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