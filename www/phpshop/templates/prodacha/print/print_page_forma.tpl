<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<TITLE>@pageTitl@</TITLE>
<META http-equiv="Content-Type" content="text-html; charset=windows-1251">
<META name="description" content="@pageDesc@">
<META name="keywords" content="@pageKeyw@">
<META name="copyright" content="@pageReg@">
<style type="text/css">
<!--
body {
	font-family: Tahoma;
}
P {
	font: normal 11px Verdana, Arial, Helvetica, sans-serif;
	word-spacing: normal;
	white-space: normal;
	margin: 5px 5px 5px 5px;
	letter-spacing : normal;
}
TABLE {
	font: normal 11px Verdana, Arial, Helvetica, sans-serif;
}
.sort_name_bg {
	background-color: #F0F1F1;
}
.sort_table {
	margin-top: 10px;
	background-color: White;
	BORDER-RIGHT: #d3d3d3 1px dashed;
	PADDING-RIGHT: 5px;
	BORDER-TOP: #d3d3d3 1px dashed;
	PADDING-LEFT: 5px;
	PADDING-BOTTOM: 5px;
	BORDER-LEFT: #d3d3d3 1px dashed;
	PADDING-TOP: 5px;
	BORDER-BOTTOM: #d3d3d3 1px dashed;
}
-->
</style>
<STYLE media="print" type="text/css">
<!--
.nonprint {
	display: none;
}
-->
</STYLE>
</HEAD>
<BODY>
<table border="0" cellpadding="5" cellspacing="5" width="100%">
  <TR>
    <TD><a href="http://@serverShop@"><IMG src="http://@serverShop@@logoShop@" alt="@nameShop@" border="0"></a> </TD>
    <TD><H4>@nameShop@</H4>
      @descripShop@ </TD>
  </TR>
</table>
<TABLE border="0" cellpadding="5" cellspacing="5" width="100%">
  <TR>
    <TD colspan="2"><HR>
    </TD>
  </TR>
  <TR>
    <TD><div align="center" class="nonprint"><a href="#" onClick="window.print();return false;" style="color: #0078BD;"><img border=0 align=absmiddle hspace=3 vspace=3 src="http://@serverShop@images/shop/action_print.gif" />Распечатать</a> | <a href="#" onClick="document.execCommand('SaveAs');return false;" style="color: #0078BD;">Сохранить на диск<img border=0 align=absmiddle hspace=3 vspace=3 src="http://@serverShop@images/shop/action_save.gif"/></a><br>
        <br>
      </div>
      <a href="http://@serverShop@/shop/UID_@productId@.html"><IMG src="http://@serverShop@@productImg@" alt="@productName@" title="@productName@" border="0" hspace="10"/></a> </TD>
    <TD valign="top"><a href="http://@serverShop@/shop/UID_@productId@.html" style="text-decoration:none;color:black" title="@productName@">
      <H4>@productName@ / @productPrice@ @productValutaName@</H4>
      </a><br>
      <div class="nonprint"> Ссылка: <a href="http://@serverShop@/shop/UID_@productId@.html" title="Перейти по ссылке: @productName@">http://@serverShop@/shop/UID_@productId@.html</a> </div>
      @vendorDisp@ </TD>
  </TR>
  <TR>
    <TD style="TEXT-ALIGN: justify" colspan="2"><BR>
      <BR>
      <B>Дополнительно:</B>
      <P>@productDes@</P>
      <P><BR>
      </P></TD>
  </TR>
</TABLE>
<HR>
</BODY>
</HTML>
