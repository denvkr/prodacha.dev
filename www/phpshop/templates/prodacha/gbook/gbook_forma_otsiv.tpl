
<div class="page_nava">
  <div> <a href="/">Главная</a> / <a href="/gbook/">Отзывы</a> </div>
</div>
<div class="pagetitle">
					<h1>Форма отзыва</h1>
				</div>

<div align="center" style="padding-bottom:10px;"> <strong  style="font-size:14px; color:#FF0000"> @Error@</strong></div>
<form method="post" name="forma_gbook">
  <table cellpadding="5" cellspacing="1" border="0" class="standart" align="center">
    <tr>
      <td align="right">Имя </td>
      <td align="left"><input type="text" name="name_new" maxlength="45" style="width:300px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F ">
        <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="middle"> </td>
    </tr>
    <tr >
      <td align="right"> E-mail </td>
      <td align="left"><input type="text" name="mail_new" maxlength="45" style="width:300px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F ">
      </td>
    </tr>
    <tr>
      <td align="right">Тема сообщения </td>
      <td><textarea style="width:300px; height:50px; font-family:tahoma; font-size:11px ; color:#4F4F4F " name="tema_new" maxlength="60"></textarea>
        <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="middle"/> </td>
    </tr>
    <tr bgcolor="ffffff">
      <td align="right">Отзыв </td>
      <td valign="top"><textarea style="width:300px; height:150px; font-family:tahoma; font-size:11px ; color:#4F4F4F " name="otsiv_new" maxlength="100" ></textarea>
        <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="middle"/> </td>
    </tr>
  </table>
  <table cellpadding="5" cellspacing="1" border="0" class="standart" width="100%">
    <tr>
      <td colspan="2" align="center"><DIV class="gbook_otvet"><img height=16 alt="" hspace=5 src="images/shop/comment.gif" width=16 align=absMiddle border=0/>Данные, отмеченные <B>флажками</B> обязательны для заполнения </font> </DIV>
        <p><br>
        </p>
        <table>
          <tr>
            <td><img src="phpshop/captcha.php" alt="" border="0"/></td>
            <td>Введите код, указанный на картинке<br>
              <input type="text" name="key" style="width:220px;">
              <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="middle"/></td>
          </tr>
        </table>
        <p><br>
        </p>
        <input type="Hidden" name="send_gb">
        <table align="center">
          <tr>
            <td><img src="images/shop/brick_error.gif" alt="" width="16" height="16" border="0"/><a href="javascript:forma_gbook.reset();" class="standart"><u class=style1>Очистить форму</u></a></td>
            <td width="20"></td>
            <td><img src="images/shop/brick_go.gif" alt="" width="16" height="16" border="0"/> <a href="javascript:Fchek();" class="standart"><u class=style1>Добавить отзыв</u></a></td>
          </tr>
        </table>
        <input type="hidden" name="send_gb" value="ok" >
      </td>
    </tr>
  </table>
</form>
