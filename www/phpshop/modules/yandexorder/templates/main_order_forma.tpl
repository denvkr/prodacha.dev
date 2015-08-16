
@ComStartReg@
<div  id=allspecwhite style="margin-bottom:20px">
<img src="images/shop/icon_key.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
<a href="/users/register.html" class="b">Зарегистрируйтесь</a> и получите дополнительные возможности и <b>скидки</b>.
</div>
@ComEndReg@
<p><br></p>
<form method="post" name="forma_order" action="/done/">
<table  cellpadding="5" cellspacing="0" width=100% >
<tr>
	<td align="right">
	<b>Заказ №</b>
	</td>
	<td>
	<input type="text" name=ouid style="width:50px; height:18px; font-family:tahoma; font-size:11px ; color:#9e0b0e; background-color:#f2f2f2;" value="@orderNum@"  readonly="1"> <b>/</b>
<input type="text" style="width:50px; height:18px; font-family:tahoma; font-size:11px ; color:#9e0b0e; background-color:#f2f2f2;" value="@orderDate@"  readonly="1">
	</td>	
</tr>
<tr>
   <td align="right">Доставка</td>
   <td>
@orderDelivery@
   </td>
</tr>
<tr valign="top">
    <td align="right">
	E-mail:
	</td>
	<td>
	<input type="text" name="mail" style="width:300px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="30" value="@UserMail@" @formaLock@><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
	</td>
</tr>
<tr>
	<td align="right" class=tah12>
    Контактное лицо:
	</td>
	<td>
	<input type="text" name="name_person" style="width:300px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="30" value="@UserName@" @formaLock@><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
	</td>
</tr>
<tr>
	<td align="right" >
	Компания:
	</td>
	<td>
	<input type="text" name="org_name" style="width:300px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="100" value="@UserComp@" @formaLock@>
	</td>
</tr>
<tr>
	<td align="right" >
	ИНН:
	</td>
	<td>
	<input type="text" name="org_inn" style="width:150px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="50" value="@UserInn@" @formaLock@>
	</td>
</tr> 
<tr>
	<td align="right" >
	КПП:
	</td>
	<td>
	<input type="text" name="org_kpp" style="width:150px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="50" value="@UserKpp@" @formaLock@>
	</td>
</tr> 
<tr>
	<td align="right">
	Телефон:
	</td>
	<td>
	<input type="text" name="tel_code" style="width:50px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="5" value="@UserTelCode@"> -
	<input type="text" name="tel_name" style="width:150px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="30" value="@UserTel@"><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
	</td>
</tr>
<tr>
	<td align="right">
	Время доставки:
	</td>
	<td>
	от <input type="text" name="dos_ot" style="width:50px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="5">ч.&nbsp;&nbsp;&nbsp;
    до
<input type="text" name="dos_do" style="width:50px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="5">ч. 
	</td>
</tr>
<tr>
	<td align="right" class=tah12>
	Адрес и <br>
	дополнительная<br>
	информация:
	</td>
	<td>
	<textarea style="width:300px; height:100px; font-family:tahoma; font-size:11px ; color:#4F4F4F " name="adr_name" >@UserAdres@</textarea><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
	@yandexorder@</td>
</tr>
<tr>
   <td align="right">Тип оплаты <br>покупки</td>
   <td>
   @orderOplata@
   </td>
</tr>
<tr>
  <td></td>
  <td>
  <div  id=allspecwhite><img src="images/shop/comment.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">Данные, отмеченные <b>флажками</b> обязательны для заполнения.<br>
</div>

  </td>
</tr>
<tr>
    <td colspan="2" align="center">
	<p><br></p>
	<table align="center">
<tr>
<td>
	<img src="images/shop/brick_error.gif" border="0" align="absmiddle">
	<a href="javascript:forma_order.reset();" class=link>Очистить форму</a></td>
	<td width="20"></td>
	<td><img src="images/shop/brick_go.gif"  border="0" align="absmiddle">
	<a href="javascript:OrderChek();" class=link>Оформить заявку</a></td>
	
	
</tr>
</table>
	<input type="hidden" name="send_to_order" value="ok" >
	<input type="hidden" name="d" id="d" value="@deliveryId@">
	<input type="hidden" name="nav" value="done">
    </td>
</tr>
</table>
</form>
