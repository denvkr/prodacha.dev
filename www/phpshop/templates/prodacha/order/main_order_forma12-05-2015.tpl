<p><br />
</p>
<form method="post" name="forma_order" action="/done/">
  <table  cellpadding="5" cellspacing="0" width=100% >
    <tr>
      <td align="right"><b>Заказ №</b> </td>
      <td>
      	<input type="text" name=ouid style="width:50px; height:18px; font-family:tahoma; font-size:11px; color:#9e0b0e; background-color:#f2f2f2;" value="@orderNum@"  readonly="1">
      	<b>/</b>
      	<input type="text" style="width:50px; height:18px; font-family:tahoma; font-size:11px; color:#9e0b0e; background-color:#f2f2f2;" value="@orderDate@"  readonly="1">
      </td>
    </tr>
    <tr>
      <td align="right">Доставка</td>
      <td> @orderDelivery@ </td>
    </tr>
    <tr>
      <td id="delivery_warning" align="left" colspan="2" style="font-size: 10px; padding-left: 170px; display:none">* Доставка по Москве производится для заказов от 5000 руб. Заказ стоимостью<br /> менее 5000 руб. Вы можете забрать самовывозом из наших магазинов.</td>
    </tr>
    <tr valign="top">
      <td align="right"> E-mail: </td>
      <td>
        <input type="email" id="mail" name="mail" style="width:300px; height:18px; font-family:tahoma; font-size:11px; color:#4F4F4F;" maxlength="40" title="Введите email в формате: [my@mail.ru]" value="@UserMail@" @formaLock@>
        <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="middle"/>
      </td>
    </tr>
    <tr>
      <td align="right" class=tah12>Контактное лицо: </td>
      <td>
          <input type="text" id="name_person" name="name_person" style="width:300px; height:18px; font-family:tahoma; font-size:11px; color:#4F4F4F;" maxlength="40" title="Введите свою фамилию и имя в формате: [ФАМИЛИЯ ИМЯ]" value="@UserName@" @formaLock@>
          <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="middle"/> 
      </td>
    </tr>
    <tr>
      <td align="right">Телефон: </td>
      <td>
      	  <input type="hidden" name="tel_code" style="width:50px; height:18px; font-family:tahoma; font-size:11px; color:#4F4F4F;" value="@php require($_SERVER['DOCUMENT_ROOT'].'/net_pather.php');php@">
 	      <input type="text" id="tel_name" name="tel_name" style="width:150px; height:18px; font-family:tahoma; font-size:11px; color:#4F4F4F;" maxlength="11" title="Введите 10 цифр телефона без восьмерки в формате: [9011234567]" value="@UserTel@"> <!--Введите телефон в формате: [xxx xxxxxxx] код города тел. номер-->
          <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="middle"/>
      </td>
    </tr>
    <tr>
      <td align="right" id="address_and_info" class=tah12>@address_and_info_text@</td>
      <td>
      	  <textarea style="width:300px; height:100px; font-family:tahoma; font-size:11px ; color:#4F4F4F;" name="adr_name" >@UserAdres@</textarea>
          <!--<img id="address_and_info_flag" src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="middle"/>-->
      </td>
    </tr>
    <tr>
        <td align="right"><div name="delivery_time_info" style="display:none;"> Время доставки: </div></td>
        <td>
      	  <div name="delivery_time_info" style="display:none;">от
          	<input type="text" name="dos_ot" style="width:50px; height:18px; font-family:tahoma; font-size:11px; color:#4F4F4F;" maxlength="5">
	        ч.&nbsp;&nbsp;&nbsp;
    	    до
			<input type="text" name="dos_do" style="width:50px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F;" maxlength="5">
        	ч.&nbsp;
          </div>
        </td>
    </tr>
    <tr>
      <td align="right">Тип оплаты <br />
        покупки</td>
      <!-- <td> @orderOplata@ </td> -->
	  <td>
		  <input type="radio" name="order_metod" checked value="3" onClick="document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';" >Наличная оплата<br />
		  <input type="radio" @creditdisabled@  name="order_metod" value="25" onClick="document.getElementById('bic').style.display='block';document.getElementById('bin').style.display='none';"  >Оформить кредит<br />
		  <div id="order_metod_div" style="display:@order_metod_div_display@;"><input type="radio" name="order_metod" value="26" onClick="document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';" >Оплата в магазине картой VISA, Mastercard<br /></div>
	  </td>
    </tr>
    <tr>
      <td align="left" colspan="2" style="font-size: 10px; padding-left: 170px; text-decoration: underline;"><a href="http://prodacha.ru/page/credit.html" target="_blank">Ответы на часто задаваемые вопросы о покупке в кредит</a></td>
    </tr>
    <tr>
		<td align="right" style="color: gray;" ></td>
		<td>
			<input type="radio" name="legal_form" checked value="legal_form_phys" onclick="javascript:set_legal_form();" >Физ. лицо<br />
			<input type="radio" name="legal_form" value="legal_form_jur" onclick="javascript:set_legal_form();"  >Юр. лицо<br />
	    </td>
    </tr>
    <tr>
       <td align="right" style="color: gray;" >
       		<div id="org_name_info" style="display:none;">Компания: </div>
       </td>
       <td>
           <div id="org_name_value" style="display:none;"><input type="text" name="org_name" style="width:300px; height:18px; font-family:tahoma; font-size:11px; color:#4F4F4F;" maxlength="100" value="@UserComp@" @formaLock@></div>
       </td>
    </tr>
    <tr>
      <td align="right" style="color: gray;" ><div id="org_inn_info" style="display:none;">ИНН: </div></td>
      <td>
          <div id="org_inn_value" style="display:none;"><input type="text" name="org_inn" style="width:150px; height:18px; font-family:tahoma; font-size:11px; color:#4F4F4F;" maxlength="50" value="@UserInn@" @formaLock@></div>
      </td>
    </tr>
    <tr>
        <td align="right" style="color: gray;" ><div id="org_kpp_info" style="display:none;">КПП: </div></td>
        <td>
            <div id="org_kpp_value" style="display:none;"><input type="text" name="org_kpp" style="width:150px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="50" value="@UserKpp@" @formaLock@></div>
        </td>
    </tr>
    <tr>
		<td></td>
    	<td><br />
    		<div style="width: 400px;">
				<span style="color: red; font-weight: bold;">Внимание!</span> При заказе через корзину указывается <span style="font-weight: bold;">предварительная стоимость</span> для выбранного вами типа доставки. Окончательную стоимость доставки Вам сообщит менеджер при подтверждении заказа (она зависит от расстояния и суммы Вашего заказа). Точную стоимость доставки можно узнать <a target="_blank" href="http://prodacha.ru/page/delivery.html" style="text-decoration: underline;">по ссылке</a>.
    		</div>
    	</td>
    </tr>
	<tr>
	    <td></td>
    	<td> 
	    	<p><br />
	        </p>
	 		<span id="bin" class="need2hide" ><a href="javascript:OrderChek();" class=link style="font-size: 18px; font-weight: bold;"><img src="images/makeorder.png"></a></span>
			<!-- <span id="bic" class="need2hide" style="display:none;" ><a href="javascript:;" onclick='yescreditmodul([@credititems@],367328,"@orderNum@");'  class=link style="font-size: 18px; font-weight: bold;"><img src="images/buyincredit.png"></a></span>  -->
	        <!--<span id="bic_credit" class="need2hide" style="display:none;" >@credititems@</span>	-->
			<!-- <input type="hidden" name="bic_code" value='yescreditmodul([@credititems@],367328,"@orderNum@");' > -->
			<input type="hidden" name="bic_code" value="@credititems@">
			<span id="bic" class="need2hide" style="display:none;" ><a href="javascript:OrderChek();"  class=link style="font-size: 18px; font-weight: bold;"><img src="images/buyincredit.png"></a></span>
			<input type="hidden" name="send_to_order" value="ok">
	        <input type="hidden" name="d" id="d" value="@deliveryId@">
	        <input type="hidden" name="nav" value="done">
	    </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <div id=allspecwhite><img src="images/shop/comment.gif" alt="" width="16" height="16" border="0" hspace="5" align="middle" />Данные, отмеченные <b>флажками</b> обязательны для заполнения.<br />
            </div>
        </td>
    </tr>
	<tr>
        <td></td>
        <td>
            <img src="images/shop/brick_error.gif" border="0" style="vertical-align: middle;  display: none;" />&nbsp;&nbsp;<a href="javascript:forma_order.reset();" class=link style="font-size: 18px; font-weight: bold; display: none;">Очистить форму</a>
        </td>	
    </tr>
  </table>
</form>
<!--delivery @deliveryId@-->