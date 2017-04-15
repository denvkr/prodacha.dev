<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?
if (isset($_GET['cheap'])) {
	echo "<script>alert('Ваш запрос принят. В ближайшее время сотрудник интернет-магазина PROДАЧА Вам ответит.');</script>";
} elseif (isset($_REQUEST['fast_order']) || isset($_REQUEST['discount'])) {
	$scrmsg= "<script>
                    alert('Ваш заказ принят. В ближайшее время сотрудник интернет-магазина PROДАЧА с Вами свяжется.');
                    </script>";
	echo $scrmsg;
} else {
	echo "<script>alert('Ваш вопрос принят. В ближайшее время сотрудник интернет-магазина PROДАЧА Вам ответит.');</script>";
}
?> 


<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta http-equiv='refresh' content='0; url=<?=$_GET['url'];?>'>
<title>Ваше сообщение принято. </title>
</head>

<body>
</body>
</html>
