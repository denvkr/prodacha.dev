<p><font color="#FF0000">@usersError@ @mesageText@</font></p>
<p>
<form method="post" name="user_forma" action="@ShopDir@/partner/">
    <table cellspacing="5" cellpadding="5">
        <tr>
            <td align="right">	Логин:
            </td>
            <td>
                <input type="text"  name="plogin" value="" size="25"> * не менее 2 символов
            </td>
        </tr>
        <tr>
            <td align="right">	Пароль:
            </td>
            <td>
                <input  type="password" name="ppassword" value="" size="25"> * не менее 4 символов
            </td>
        </tr>
        <tr>
            <td align="right">	E-mail:
            </td>
            <td>
                <input type="text" name="mail" value="" size="25"> * требуется активация
            </td>
        </tr>
        <tr>
            <td align="right">	Имя:
            </td>
            <td>
                <input  type="text" name="dop_ФИО" size="25">
            </td>
        </tr>
        <tr>
            <td align="right">	Url:
            </td>
            <td>
                <textarea cols="20" rows="5" name="dop_Url"></textarea>
            </td>
        </tr>
        <tr>
            <td align="right">	Платежная<br>информация:
            </td>
            <td>
                <textarea cols="20" rows="5" name="dop_Payment"></textarea>
            </td>
        </tr>
        <tr>
            <td align="right">
            </td>
            <td >
                <input  type="submit" name="add_user" value="Регистрация">
            </td>
        </tr>
    </table>
</form>
</p>