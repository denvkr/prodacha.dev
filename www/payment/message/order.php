<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  ������ OrderFunction �������� ���. |
+-------------------------------------+
*/

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

$sql="select message,message_header  from ".$SysValue['base']['table_name48']." where id=".$_POST['order_metod'];
$result=mysql_query(@$sql);
$row = mysql_fetch_array(@$result);

$message=$row['message'];
$message_header=$row['message_header'];


// ���������� ��������� nah~

	if ($_POST['order_metod']==25 || $_POST['order_metod']==11) {
		$message.="<div class='bictext'>��� ����� ������� ������! ��� ���������� �������, ����������, ��������� ������ �����</div><div class='vkredit-button'><img src='images/white-logo.png' onclick='javascript:ask_credit_form();'/></div><script type='text/javascript'>function ask_credit_form(){VVC.onBuy(".$_POST['bic_code'].",2);};</script>"; //style='visibility:hidden;'
		$SysValue['other']['mesageText']= "<font style='font-size:14px;color:red'><b>".$message_header."</b></font><br>".$message;

		$this->add('mesageText',$SysValue['other']['mesageText']);
		$disp = ParseTemplateReturn($this->getValue('templates.order_forma_mesage'));

		$disp.='<script type="text/javascript">
		if(window.document.getElementById("num")){
		window.document.getElementById("num").innerHTML="0";
		window.document.getElementById("sum").innerHTML="0";
		}
		</script>';	
	} else {
		$this->set('mesageText', $this->message('<span style="font-size:18px">'.$this->lang('good_order_mesage_1').'</span><BR>','<span style="font-size:14px"><b> �� ���� �����: '.$_POST['mail'].' ���������� ������ � �������������� ������ � '.$_POST['ouid'].'</b><BR><BR> � ��������� ����� � ���� �������� ��� �������� ��� ��������� ������� ������.</span>'));
		$disp = ParseTemplateReturn($this->getValue('templates.order_forma_mesage'));
		$this->set('orderMesage', $disp);	
	}

// ������� �������
unset($_SESSION['cart']);
?>