<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?
if (isset($_GET['cheap'])) {
	echo "<script>alert('��� ������ ������. � ��������� ����� ��������� ��������-�������� PRO���� ��� �������.');</script>";
} elseif (isset($_REQUEST['fast_order']) || isset($_REQUEST['discount'])) {
	$scrmsg= "<script>
                    alert('��� ����� ������. � ��������� ����� ��������� ��������-�������� PRO���� � ���� ��������.');
                    </script>";
	echo $scrmsg;
} else {
	echo "<script>alert('��� ������ ������. � ��������� ����� ��������� ��������-�������� PRO���� ��� �������.');</script>";
}
?> 


<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta http-equiv='refresh' content='0; url=<?=$_GET['url'];?>'>
<title>���� ��������� �������. </title>
</head>

<body>
</body>
</html>
