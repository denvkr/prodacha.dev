<?
/*
	Авторизация через PHP
*/
header ('Content-Type: text/html; charset=UTF-8');

require ('config.php');

if (SEOTOOLS_AUTHORIZE_THRU_PHP)
{
	session_start();
	$auth 			= false;
	$login_panel	= '';

	if (isset($_POST) && $_POST['enter'])
	{
		if ($_POST['login'] == SEOTOOLS_LOGIN && $_POST['password'] == SEOTOOLS_PASSWORD) 
		{
			$_SESSION['SEOTOOLS_USER_LOGIN'] = true;
		}
		else 
		{
			echo 'Доступ запрещен!<br>';
		}
	}
	
	if (isset($_SESSION['SEOTOOLS_USER_LOGIN']) && $_SESSION['SEOTOOLS_USER_LOGIN']) $auth = true;
	
	if ($_GET['exit']) 
	{
		unset($_SESSION['SEOTOOLS_USER_LOGIN']);
		session_destroy();
		$auth = false;
		header ('Location: http://' . $_SERVER['HTTP_HOST'] . SEOTOOLS_PATH );
	}

	if (!$auth)
	{
		echo '
		<h1>Вход в SEOTOOLS</h1>
		<form method=POST>
			<table>
				<tr><td>Логин:</td><td><input type="text" name="login" value="" /></td></tr>
				<tr><td>Пароль:</td><td><input type="password" name="password" value="" /></td></tr>
				<tr><td colspan=2><input type="submit" value="Вход" /></td></tr>
			</table>
			<input type="hidden" name="enter" value="1" />
		</form>
		';
		die;
	}
	else 
	{
		$login_panel = '<div class="login_panel"><a href="?exit=1">Выход</a></div>';
	}
}

?>