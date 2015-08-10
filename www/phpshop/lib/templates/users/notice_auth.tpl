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
                       
                    </table>
            </div>
        </td>
    </tr>
    <tr>
        <td>
           
					 <!--авторизация -->  
					 
	<div id=allspec  class="margin20-0">
				<img src="images/shop/icon_key.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><b>Авторизируйтесь, если вы зарегистрированы на нашем сайте</b>
			</div>		

			
		 <form method="post" name="user_forma" action="#" >	
		
		   <table>
				<tr>
					<td>Логин:</td>
					<td width="10"></td>
					<td><input class="loginput" type="text" name="login" value="@UserLogin@" onfocus="if(this.value=='Логин'){this.value='';}" onblur="if(this.value==''){this.value='@UserLogin@';}" /><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"></td>
					<td rowspan="2" valign="top" style="padding-left:10px">
					</td>
				</tr>
				<tr>
					<td>Пароль:</td>
					<td width="10"></td>
					<td><input  class="loginput" class="password" type="password" name="password" value="@UserPassword@" onfocus="if(this.value=='Пароль'){this.value='';}" onblur="if(this.value==''){this.value='@UserPassword@';}" /><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"></td>
				</tr>
			   
			<tr>
					<td></td>
					<td width="10"></td>
					<td >
					 <input name="button" type="submit"  onclick="ChekUserForma()" value="Войти" class="button"  />
					 <input type="hidden" value="1" name="notice" />
										<input type="hidden" value="1" name="user_enter" />
					
					 </td>
			</tr>
		</table>
					
								</form>
								
					
								
	<div id=allspec  class="margin20-0">
				<img src="images/shop/icon_key.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><b>Если вы не зарегистрированы на нашем сайте, пожалуйста, заполните <a style="text-decoration:underline;" href="http://prodacha.ru/users/register.html">данную форму</a>.
			</div>	
			
			
	

		   
        </td>
    </tr>
</table>
