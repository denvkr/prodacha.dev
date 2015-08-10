<form name="forma_cart" method="post" id="forma_cart">
<tr>
    <td>
        <a href="/shop/UID_@cart_id@.html" title="@cart_name@"><img src="images/shop/action_forward.gif" border="0" hspace="5" align="absmiddle">@cart_name@</a></td>
    <td><input type="text" value="@cart_num@" size="3" maxlength="5" name="num_new@cart_xid@" id="num_new@cart_xid@" onchange="this.form.submit()"/></td>
	<!--onchange="this.form.submit()"-->	
    <td>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td>
					<!--<form name="forma_cart_add" method="post" id="forma_cart_add-->
                        <input type="image" name="edit_num" src="images/shop/cart_add.gif" value="edit" alt="Пересчитать" hspace="5" onclick="ChangeNumProduct('num_new@cart_xid@','+');" />
                        <input type=hidden name="id_edit" value="@cart_xid@">
                    <!--</form>-->
                </td>
                <td>
                    <!--<form name="forma_cart_del" method="post" id="forma_cart_del">-->
                        <input type="image" name="edit_del" src="images/shop/cart_delete.gif" value="delet" alt="Удалить" onclick="ChangeNumProduct('num_new@cart_xid@','-');">
                        <input type=hidden name="id_delete" value="@cart_xid@">
                    <!--</form>-->
                </td>
            </tr>
        </table>
    </td>
    <td align="right" class="red">@cart_price@ @currency@</td>
</tr>
</form>
