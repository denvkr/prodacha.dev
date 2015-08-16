<div class="login">
				<a class="login_popup" href="#">Вход</a>
				<a href="/users/register.html">Регистрация</a>
                @php					
					require($_SERVER['DOCUMENT_ROOT'].'/geo_select.php');
				php@
				<div id="errorenter">@usersError@</div>
													
				<div class="log_popup">
					<div class="arrowupline">
						<div class="arrowup">
						</div>
					</div>
                      <form method="post" name="user_forma" action="#" >
					<table>
				 
							<tr>
								<td><span class="log">Личный кабинет</span></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td><input class="loginput" type="text" name="login" value="@UserLogin@" onfocus="if(this.value=='Логин'){this.value='';}" onblur="if(this.value==''){this.value='@UserLogin@';}" /></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td><input class="password" type="password" name="password" value="@UserPassword@" onfocus="if(this.value=='Пароль'){this.value='';}" onblur="if(this.value==''){this.value='@UserPassword@';}" /></td>
								<td><a href="/users/sendpassword.html">Забыл?</a></td>
							</tr>
							<tr>
								<td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td> <input name="button" type="submit"  onclick="ChekUserForma()" value="Войти" class="button"  />
                    <input type="hidden" value="1" name="user_enter" /></td>
    <td>@facebookAuth@</td>
    <td>  @twitterAuth@</td>
  </tr>
</table>

                               
                               
                             </td>
								<td></td>
							</tr>
						
					</table>
                    </form>
				</div>
			</div>
