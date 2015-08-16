@activationNotice@
<form method="post" name="user_forma" action="@ShopDir@/partner/">
    <table cellspacing="5">
        <tr>
            <td align="right">	Логин:
            </td>
            <td>
                <input type="text" name="plogin"  size="20">
            </td>
        </tr>
        <tr>
            <td align="right">	Пароль:
            </td>
            <td>
                <input  type="password" name="ppassword" size="20">
            </td>
        </tr>
        <tr>
            <td align="right">
            </td>
            <td >
                <input  type="submit" name="send" value="Авторизация">
            </td>
        </tr>
    </table>
    <input type="hidden" value="1" name="enter_user">
</form>
<p>
    <a href="@ShopDir@/partner/register_user.html" title="Регистрация">Регистрация</a><br>
    <a href="@ShopDir@/partner/sendpassword_user.html"  title="Забыли пароль?">Забыли пароль?</a><br>
    <a href="@ShopDir@/rulepartner/"  title="Правила и условия партнёрской программы">Правила и условия партнёрской программы</a><br>
</p>
