

<table  border="0" cellspacing="0" cellpadding="0" class="hiddennote">
  <tr>
    <td width="150"><div id=allspec><img src="images/shop/icon_key.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><b>Авторизация</b>
</div></td>
    <td><div>  @facebookAuth@
		@twitterAuth@</div></td>
  </tr>
</table>

<form name="users_data" method="post" action="#">

    <table>
        <tr>
            <td>Логин:</td>
            <td width="10"></td>
            <td><input type="text" name="login_new" style="width:250px;" value='@php echo $_POST["login_new"]; php@'><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><br>(не менее 4 знаков)</td>
            <td rowspan="2" valign="top" style="padding-left:10px">
            </td>
        </tr>
        <tr>
            <td>Пароль:</td>
            <td width="10"></td>
            <td><input type="Password" name="password_new" style="width:250px;" value='@php echo $_POST["password_new"]; php@'><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><br>(не менее 6 знаков)</td>
        </tr>
        <tr>
            <td>Повторите пароль:</td>
            <td width="10"></td>
            <td><input type="Password" name="password_new2" style="width:250px;" value=""><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"></td>
        </tr>
    </table>

<div id=allspec>
    <img src="images/shop/icon_user.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><b>Личные данные</b>
</div>
<table width="99%" cellpadding="5">
    <tr>
        <td colspan="2"><p><br></p></td>
    </tr>
    <tr>
        <td>Контактное лицо:&nbsp;&nbsp;&nbsp;
        </td>
        <td><input type="text" name="lastname_new" style="width:150px" placeholder="Фамилия" value='@php echo $_POST["lastname_new"]; php@'><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
        <input type="text" name="name_new" style="width:115px" placeholder="Имя" value='@php echo $_POST["name_new"]; php@'><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><br>(не менее 2 знаков)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(не менее 2 знаков)</td>
    </tr>
    <tr>
        <td>E-mail:
        </td>
        <td><input type="text" name="mail_new" style="width:300px" value='@php echo $_POST["mail_new"]; php@'><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"></td>
    </tr>
    <tr>
        <td>Телефон:</td>
        <td><!--<input type="text" name="tel_code_new" style="width:50px;" value='@php echo $_POST["tel_code_new"]; php@'> --->
            <input type="text" name="tel_new" style="width:300px;" value='@php echo $_POST["tel_new"]; php@' placeholder="+7(901)123-45-67"></td>
    </tr>
    <tr>
        <td>Адрес:</td>
        <td><textarea style="width:300px; height:100px;" name="adres_new">@php echo $_POST["adres_new"]; php@</textarea>
        </td>
    </tr>
    <tr>
        <td>Компания: </td>
        <td><input type="text" name="company_new" style="width:300px;" value='@php echo $_POST["company_new"]; php@'></td>
    </tr>
    <tr>
        <td>ИНН:</td>
        <td><input type="text" name="inn_new" style="width:300px;" value='@php echo $_POST["inn_new"]; php@'></td>
    </tr>
    <tr>
        <td>КПП:</td>
        <td><input type="text" name="kpp_new" style="width:300px;" value='@php echo $_POST["kpp_new"]; php@'></td>
    </tr>

</table>
<table>
    <tr>
        <td align="center"><img src="phpshop/captcha.php" id="captcha" alt="" border="0"><br>
            <a class=b  title="Обновить картинку" href="javascript:CapReload();">Обновить картинку</a></td>
        <td>Введите код, указанный на картинке<br><input type="text" name="key" style="width:220px;"><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"></td>
    </tr>
    <tr>
        <td colspan="2">	<br>
            <div id=allspec><img src="images/shop/comment.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">Данные, отмеченные <b>флажками</b> обязательны для заполнения.<br>
                @user_error@
            </div>
            <br></td>
    </tr>
    <tr>
        <td></td>
        <td>
            <input type="hidden" value="1" name="add_user">
            <input type="button" value="Регистрация пользователя" onclick="CheckNewUserForma();"></td>
    </tr>
    <tr>
        <td></td>
        <td>
            <span class="konfident order">Нажимая кнопку «Регистрация пользователя», я соглашаюсь на получение информации от интернет-магазина и уведомлений о состоянии моих заказов, а также принимаю условия <a href="//prodacha.ru/page/politika_konfidencialnosti.html" target="_blank">политики конфиденциальности</a></span>
        </td>
    </tr>
</table>
</form>
