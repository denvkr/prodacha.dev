<?php
/**
 * Обработчик оплаты заказа через Yandex
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopPayment
 */

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

// регистрационная информация
$scid = $SysValue['yandex']['scid'];   
$ShopID = $SysValue['yandex']['ShopID'];  

// параметры магазина
$mrh_ouid = explode("-", $_POST['ouid']);
$inv_id = $mrh_ouid[0]."".$mrh_ouid[1];     //номер счета

// описание покупки
$inv_desc  = "PHPShopPaymentService";
$out_summ  = $GLOBALS['SysValue']['other']['total']; //сумма покупки

// библиотека корзины
$PHPShopCart = new PHPShopCart();

/**
 * Шаблон вывода таблицы корзины
 */
function cartpaymentdetails($val) {
     $dis=$val['uid']."  ".$val['name']." (".$val['num']." шт. * ".$val['price'].") -- ".$val['total']."
";

    return $dis;
}

// вывод HTML страницы с кнопкой для оплаты
$disp= '
<div align="center">
<p>
<b>Яндекс.Деньги</b> — платежная система, которая позволяет безопасно и быстро оплачивать товары и услуги в интернете.
</p>
 <p><br></p>
 
<form method="POST" name="pay" id="pay" action="https://money.yandex.ru/eshop.xml">
<input class="wide" name="scid" value="'.$scid.'" type="hidden">
<input type="hidden" name="ShopID" value="'.$ShopID.'">
<input type=hidden name="CustomerNumber" size="20" value="Заказ N'.$inv_id.'">
<input type=hidden name="Sum" size="10" value="'.$out_summ.'">
<input type=hidden name="CustName" value="'.$_POST['name_person'].'">
<input type=hidden name="CustAddr" value="'.$_POST['adr_name'].'">
<input type=hidden name="CustEMail" value="'.$_POST['mail'].'">
<input type=hidden name="OrderDetails" value="'.$PHPShopCart->display('cartpaymentdetails').'">
	  <table>
<tr><td><img src="images/shop/icon-setup.gif" width="16" height="16" border="0"></td>
	<td align="center"><a href=\"javascript:history.back(1)"><u>
	Вернуться к оформлению<br>
	покупки</u></a></td>
	<td width="20"></td>
	<td><img src="images/shop/icon-client-new.gif"  width="16" height="16" border="0" align="left">
	<a href="javascript:pay.submit();">Оплатить через платежную систему</a></td>
</tr>
</table>
      </form>
</div>';

?>