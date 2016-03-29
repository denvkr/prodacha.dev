<table border=0 width=99% cellpadding=0 cellspacing=3>
    <tr>
        <td ><strong>Наименование</strong></td>
        <td width=50><strong>Кол-во</strong></td>
        <td width=50><strong>Операции</strong></td>
        <td width=70 align="right" colspan=""><strong>Цена</strong></td>
    </tr>
    <tr>
        <td colspan="4">
            <img src="images/shop/break.gif" width="100%" height="1" border="0">
        </td>
    </tr>
    @display_cart@
    <tr>
        <td colspan="4">
            <img src="images/shop/break.gif" width="100%" height="1" border="0">
        </td>
    </tr>
    <tr id="tr_itog" style="padding-top:10px">
        <td ><b>Итого:</b></td>
        <td>
            <strong>@cart_num@</strong> (шт.)
        </td>
        <td></td>
        <td align="right">
            @cart_sum@ @currency@<br>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <img src="images/shop/break.gif" width="100%" height="1" border="0">
        </td>
    </tr>
</table>
<table border="0" width="99%" cellpadding="0" cellspacing="3" align="center">
    <tr style="visibility:hidden;display:none;">
        <td colspan="3" valign="top">Вес товаров:</td>
        <td class="red" align="right"><span id="WeightSumma">@cart_weight@</span> гр. </td>
    </tr>
    
    <tr>
        <td colspan="3" valign="top" style="width:20%;">Доставка:</td>
        <td colspan="3" style="width:60%;"></td>
        <td class="red" colspan="3" align="right" style="width:20%;"><span id="DosSumma">@delivery_price@</span>&nbsp; @currency@</td>
    </tr>
    <tr>
        <td colspan="3" style="width:20%;">
            <strong>ИТОГО К ОПЛАТЕ</strong>:
        </td>
        <td colspan="3" class="style2" style="width:60%;">
        </td>
        <td colspan="3" align="right" class="red" style="width:20%;">
            <b><span id="TotalSumma">@total@</span></b> @currency@
        </td>
    </tr>
    <tr>
	    <td colspan="3" align="left" style="width:20%;"><div id="promo_code_info">Введите промо-код: </div></td>
    	<td colspan="3" style="width:60%;" align="left"> 
            &nbsp;<input type="text" name="promo_code_value" maxlength="10">&nbsp;&nbsp;<input type="button" name="promo_code_check_button" value="Активировать" onclick="javascript:get_promocode(document.getElementsByName('promo_code_value')[0].value);">&nbsp;&nbsp;<label id="wrong_promocode_label"></label>
        </td>
            <td colspan="3" style="width:20%;"></td>
    </tr>
</table>
<input type="hidden" id="OrderSumma" name="OrderSumma"  value="@cart_sum@">
<script>
    if(window.document.getElementById('num')){
        window.document.getElementById('num').innerHTML='@cart_num@';
        window.document.getElementById('sum').innerHTML='@cart_sum@';
    }
</script>