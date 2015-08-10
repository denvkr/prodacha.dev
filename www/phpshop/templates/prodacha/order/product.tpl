<tr>
    <td>
        <form name="forma_cart" method="post" id="forma_cart">
            <a href="/shop/UID_@cart_id@.html" title="@cart_name@"><img src='@cart_image@' width="50" align="middle" border="0"/></a>
            <a href="/shop/UID_@cart_id@.html" title="@cart_name@">@cart_name@</a>
    </td>
    <td>
        <input type="text" value="@cart_num@" size="3" maxlength="5" name="num_new" onchange="this.form.submit()">
        <input type=hidden name="id_edit" value="@cart_xid@">
    </form>
</td>
<td align="center">

    <form name="forma_cart_del" method="post" id="forma_cart_del">
        <input type="image" name="edit_del" src="images/shop/cart_delete.gif" value="delet" alt="Удалить">
        <input type=hidden name="id_delete" value="@cart_xid@">
    </form>

</td>
<td align="right" class="red">@cart_price@ @currency@</td>
</tr>