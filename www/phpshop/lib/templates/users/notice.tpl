<div id="allspec">
    <img src="images/shop/icon_info.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><b>Уточнить наличие товара</b>
</div>
<p>
<table>
    <tr>
        <td>
            <div align="center" style="padding:10px">
                    <table>
                        <tr>
                            <td><a href="/shop/UID_@productId@.html"><img src="@pic_small@" alt="@name@" border="0"></a></td>
                            <td style="padding:10px"><h1>@name@</h1></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right">

                                <img src="images/shop/icon-setup2.gif" alt="" border="0" align="absmiddle"> <a href="javascript:history.back(1)">Вернуться</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <img src="images/shop/interface_dialog.gif" alt="" width="16" height="16" border="0" align="absmiddle"> <a href="./notice.html">Архив заявок</a>
                            </td>
                        </tr>
                    </table>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div id="allspecwhite">
				Для уточнения наличия товара оставьте заявку! Наш сотрудник свяжется с вами в ближайшее время.
            </div>
            @user_message@
        </td>
    </tr>
    <tr>
        <td>
            <form method="post" name="forma_message" action="./notice.html">
                Дополнительная информация:<br>
                <textarea style="width:100%;height:100px;" name="message" id="message"></textarea>

                <div style="float: right"><img src="images/shop/date.gif" alt="" width="16" height="16" border="0" align="absmiddle" hspace="3">Не позднее: <select name="date">
                        <option value="1" SELECTED>1 месяца</option>
                        <option value="2">2 месяцев</option>
                        <option value="3">3 месяцев</option>
                        <option value="4">4 месяцев</option>
                    </select></div>
                <div>
                    <input type="submit" value="Отправить заявку" name="add_notice">
                    <input type="hidden" value="@productId@" name="productId">
                </div>
            </form>
        </td>
    </tr>
</table>
