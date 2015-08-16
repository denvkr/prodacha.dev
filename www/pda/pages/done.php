<?
/*
+-------------------------------------+
|  PHPShop Enterprise 2.1             |
|  Оформление Заказа                  |
+-------------------------------------+
*/

$cart=$_SESSION['cart'];

function Summa_cart() {
    global $cart,$LoadItems,$SysValue;
    $cid=array_keys($cart);
    for ($i=0; $i<count($cid); $i++) {
        $j=$cid[$i];
        @$in_cart.="
".$cart[$j]['name']." (".TotalClean($cart[$j]['num'],1)." шт.), ";
        @$sum+=$cart[$j]['price']*$cart[$j]['num'];
    }
    $dis=array(@$in_cart,@$sum);
    return @$dis;
}

function Order() {
    global $SysValue,$LoadItems,$_POST,$SERVER_NAME,$sid,$cart;
    if(isset($_POST['send_to_order'])) {

        // Наличная оплата
        if(@$_POST['mail'] and @$_POST['name_person'
                ] and @$_POST['tel_name'] and @$_POST['adr_name'] and @$cart) {

            // Определяем переменые
            $SysValue['other']['mesageText']= "<FONT style=\"font-size:14px;color:red\">
<B>".$SysValue['lang']['good_order_mesage_1']."</B></FONT><BR>".$SysValue['lang']['good_order_mesage_2'];

            // Подключаем шаблон
            @$disp=ParseTemplateReturn($SysValue['templates']['order_forma_mesage']);
            $disp.="
<script language=\"JavaScript1.2\">
if(window.document.getElementById('num')){
window.document.getElementById('num').innerHTML='0';
window.document.getElementById('sum').innerHTML='0';
}
</script>";
            unset($_SESSION['cart']);

        }
        else {
            // Определяем переменые
            $SysValue['other']['mesageText']= "<FONT style=\"font-size:14px;color:red\">
<B>".$SysValue['lang']['bad_order_mesage_1']."</B></FONT><BR>".$SysValue['lang']['bad_order_mesage_2'];

            // Подключаем шаблон
            @$disp=ParseTemplateReturn($SysValue['templates']['order_forma_mesage']);
            $disp.="
	  <table>
<tr>
	<td><img src=\"images/shop/icon-setup.gif\" width=\"16\" height=\"16\" border=\"0\"></td>
	<td align=\"center\"><a href=\"javascript:history.back(1)\"><u>
	Вернуться к оформлению<br>
	покупки</u></a></td>
</tr>
</table>
	   ";
        }
    }
    elseif(!@$cart) {
        // Определяем переменые
        $SysValue['other']['mesageText']= "<FONT style=\"font-size:14px;color:red\">
<B>".$SysValue['lang']['bad_cart_1']."</B></FONT><BR>".$SysValue['lang']['good_order_mesage_2'];

        // Подключаем шаблон
        @$disp=ParseTemplateReturn($SysValue['templates']['order_forma_mesage']);
    }
    return @$disp;
}

// Определяем переменые
$SysValue['other']['orderMesage']=Order();
$SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['order_forma_mesage_main']);
$SysValue['other']['catalogCat']= "Оформление заказа";
$SysValue['other']['catalogCategory']= "Заказ оформлен";

// Подключаем шаблон 
@ParseTemplate($SysValue['templates']['index']);
?>

