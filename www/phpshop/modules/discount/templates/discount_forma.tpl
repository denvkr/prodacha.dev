<script>
    function checkModDiscountForma(){
        if(document.getElementById('discount_mod_name').value == "" || document.getElementById('discount_mod_tel').value == "")
            return false;
    }
</script>
<div style="padding:5px">
    <form action="@ShopDir@/discount/" method="post" onsubmit="return checkModDiscountForma();" >
                    <table style="margin:0px 8px 0px 9px" border="0" cellpadding="2" cellspacing="0">
                        <tbody>
                            <tr>
                                <td><b>���</b>:</td>
                                <td><input type="text" name="discount_mod_name" id="discount_mod_name" size="15"></td>
                            </tr>
                            <tr>
                                <td><b>�������</b>:</td>
                                <td><input type="text" name="discount_mod_tel" id="discount_mod_tel" size="15"> </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="hidden" name="discount_mod_product_id" value="@productUid@">
                                    <input type="submit" name="discount_mod_send" value="������ �� �������"></td>
                            </tr>

                        </tbody>
                    </table>

                </form>
</div>