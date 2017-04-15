<script>
    function checkModDiscountForma(){
        if(document.getElementById('discount_mod_name').value == "" || document.getElementById('discount_mod_tel').value == "")
            return false;
    }
</script>
<a href="javascript:void(0)" onclick="document.getElementById('mod_discount_forma').style.display='block';">Купить со скидкой</a>
<div style="position:relative">
    <div id="mod_discount_forma" style="display: none;width:200px;position:absolute;">
        <div>
            <div style="position:relative; border:1px solid #ccc; background:#ebf1f6 ">
                <form action="@ShopDir@/discount/" method="post" onsubmit="return checkModDiscountForma();" >
                    <table style="margin:0px 8px 0px 9px" border="0" cellpadding="2" cellspacing="0">
                        <tbody>
                            <tr>
                                <td colspan="2" style=" padding:10px;" align="right"><a style="padding:0px; font-size:11px;margin:0px 0px 0px 5px;color:#086ebd" href="javascript:void(0)" onclick="document.getElementById('mod_discount_forma').style.display='none';">закрыть</a></td>
                            </tr>
                            <tr>
                                <td><b>Имя</b>:</td>
                                <td><input type="text" name="discount_mod_name" id="discount_mod_name" size="15"></td>
                            </tr>
                            <tr>
                                <td><b>Телефон</b>:</td>
                                <td><input type="text" name="discount_mod_tel" id="discount_mod_tel" size="15"> </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="hidden" name="discount_mod_product_id" value="@productUid@">
                                    <input type="submit" name="discount_mod_send" value="Купить со скидкой"></td>
                            </tr>

                        </tbody>
                    </table>

                </form>
            </div>

        </div>

    </div>

</div>