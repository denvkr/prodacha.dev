<?
function true_email($email) {
if(strlen($email)>100) return FALSE;
return preg_match("/^([a-z0-9_\.-]+@[a-z0-9_\.\-]+\.[a-z0-9_-]{2,6})$/i",$email);
}

function true_login($login) {
return ereg("^[a-zA-Z0-9_\.]{2,20}$",$login);
}

function true_passw($passw) {
return ereg("^[a-zA-Z0-9_]{4,20}$",$passw);
}


function TotalClean($str,$flag)// чистка 
/*
  1 - проверяет корзину;
  2 - преобразует все в код html;
  3 - проверяет мыло;
  4 - проверяет ввод с формы
  5 - прверяет цифры
*/
{
 if($flag==1)// корзина
 {
   if (!ereg ("([0-9])", $str)) 
     {
     $str="0";
     }
     return abs($str);
   }
 elseif($flag==2)// убирает бяки
      {
	  return htmlspecialchars(stripslashes($str));
	  }
 elseif($flag==3)// обработка строки на бяки в мыле
      {
	 //проверка почты
	  if(!preg_match("/^([a-z0-9_\.-]+@[a-z0-9_\.\-]+\.[a-z0-9_-]{2,6})$/i",$str))
        {
        $str="";
        }
	   return $str;
	  }
 elseif($flag==4)// обработка строки на бяки
      {
	  if (preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/",$str)) 
        {
        $str="";
         }
       return  htmlspecialchars(stripslashes($str));
	  }
 elseif($flag==5)// проверка вводимых цифр
      {
	  if (preg_match("/[^(0-9)|(\-)|(\.]/",$str)) 
       {
       $str="0";
       }
       return $str;
	  }
}

function OplataMetod($tip){
if($tip==1) return "безналичная оплата";
if($tip==2) return "квитанция Сбербанка";
if($tip==3) return "наличная оплата";
else return "электронные платежные системы";
}


function StatMoneyClient($sum){
global $SERVER_NAME;
$proxy=0;
$key="dennion";
$mdm=md5($sum.":".$SERVER_NAME.":".$key);

if($proxy==1)
$fp = fsockopen("192.168.0.1", 8080, $errno, $errstr);
else
$fp = fsockopen("www.phpshop.ru", 80, $errno, $errstr);

if (!$fp) {
   //echo "$errstr ($errno)<br/>\n";
   $error="ERROR";
} else {
   fputs($fp, "GET /statmoneyserver.php?sum=$sum&name=$SERVER_NAME&key=$mdm  HTTP/1.0\r\n"); 
   fputs($fp, "Host: www.phpshop.ru\r\n"); 
   fputs($fp, "Connection: close\r\n\r\n");
   fclose($fp); 
}
}



if(@$send_to_order and @$mail and @$name_person and @$tel_name and @$adr_name)
{
// Список товоров в корзине
if(is_array(@$cart))
foreach($cart as $j=>$i)
  {
  @$disCart.=$cart[$j]['name']." (".$cart[$j]['num']." шт. * ".ReturnSummaNal($cart[$j]['price'],0).") -- ".ReturnSummaNal($cart[$j]['price']*$cart[$j]['num'],0)." ".GetValutaOrder()."
";
  @$sum+=$cart[$j]['price']*$cart[$j]['num'];
  @$num+=$cart[$j]['num'];
//Определение и суммирование веса
 $goodid=$cart[$j]['id'];
 $goodnum=$cart[$j]['num'];
 $wsql='select weight from '.$SysValue['base']['table_name2'].' where id=\''.$goodid.'\'';
 $wresult=mysql_query($wsql);
 $wrow=mysql_fetch_array($wresult);
 $cweight=$wrow['weight']*$goodnum;
 if (!$cweight) {$zeroweight=1;} //Один из товаров имеет нулевой вес!
 $weight+=$cweight;

  }

//Обнуляем вес товаров, если хотя бы один товар был без веса
if ($zeroweight) {$weight=0;}


 @$sum=number_format($sum,"2",".","");
 $ChekDiscount=ChekDiscount($sum);
 $GetDeliveryPrice=GetDeliveryPrice($_POST['d'],$sum,$weight);
@$disCart.="
-------------------------------------------------------
Итого -- ".ReturnSummaNal($sum,0)." ".GetValutaOrder()."
Скидка -- ".$ChekDiscount[0]."%
Доставка -- ".$GetDeliveryPrice." ".GetValutaOrder()."
-------------------------------------------------------
К оплате с учетом скидки: ".(GetPriceOrder($ChekDiscount[1])+$GetDeliveryPrice)." ".GetValutaOrder();

$content="Доброго времени!
--------------------------------------------------------
Спасибо за покупку в нашем интернет-магазине '".$LoadItems['System']['name']."'
Наши менеджеры свяжутся с вами по координатам, 
оставленными в форме заказа.


Подробности заказа № ".@$ouid." от ".date("d-m-y")."
--------------------------------------------------------
Контактное лицо: ".@$name_person."
Компания: ".@$org_name."
Телефон: ".$tel_code."-".@$tel_name."
Адрес и доп. инф: ".@$adr_name."
Желаемое время доставки: ".$dos_ot." - ".$dos_do."
Грузополучатель: ".GetDeliveryBase($dostavka_metod,'city')."
E-mail: ".@$mail."
Тип оплаты: ".OplataMetod($order_metod)."

Заказанные товары
--------------------------------------------------------
".$disCart;
if(!isset($_SESSION['UsersId']))
$content.="

Вы всегда можете проверить статус заказа, распечатать платежные 
документы он-лайн через 'Мой заказ' или по ссылке http://".$SERVER_NAME.$SysValue['dir']['dir']."/clients/?mail=".@$mail."&order=".@$ouid."
E-mail: ".@$mail."
№ Заказа: ".@$ouid."";
else
$content.="

Вы всегда можете проверить статус заказа, распечатать платежные 
документы он-лайн через 'Личный кабинет' или по ссылке http://".$SERVER_NAME.$SysValue['dir']['dir']."/users/";

$content.="

---------------------------------------------------------
С уважением,
Компания ".$LoadItems['System']['company']."
".$LoadItems['System']['adminmail2']."
http://".$SERVER_NAME.$SysValue['dir']['dir'];

$codepage  = "windows-1251";              
$header  = "MIME-Version: 1.0\n";
$header .= "From:   <".$LoadItems['System']['adminmail2'].">\n";
$header .= "Content-Type: text/plain; charset=$codepage\n";
$header .= "X-Mailer: PHP/";
$zag=$LoadItems['System']['name']." - Ваш заказ ".@$ouid."/".date("d-m-y")." успешно оформлен";

  $content_adm="
Доброго времени!
--------------------------------------------------------

Поступил заказ с интернет-магазина '".$LoadItems['System']['name']."'
Для редактирования состояния заказа перейдите в панель
администрирования магазина http://$SERVER_NAME".$SysValue['dir']['dir']."/phpshop/admpanel/

Заказанные товары
---------------------------------------------------------
".$disCart."

Подробности заказа № ".@$ouid."/".date("d-m-y")."
---------------------------------------------------------
Контактное лицо: ".@$name_person."
Компания: ".@$org_name."
Телефон: ".@$tel_code."-".@$tel_name."
Адрес и доп. инф: ".@$adr_name."
Желаемое время доставки: ".$dos_ot." - ".$dos_do."
Грузополучатель: ".GetDeliveryBase($dostavka_metod,'city')."
E-mail: ".@$mail."
Тип оплаты: ".OplataMetod($order_metod)."
Дата/время: ".date("d-m-y H:i a")."
IP:".$REMOTE_ADDR."
REF: ".base64_decode(@$_COOKIE['ps_partner'])." 
---------------------------------------------------------


Powered & Developed by www.PHPShop.ru
".$SysValue['license']['product_name'];


$codepage  = "windows-1251";     
$header_adm  = "MIME-Version: 1.0\n";
$header_adm .= "From:   <".$LoadItems['System']['adminmail2'].">\n";
$header_adm .= "Content-Type: text/plain; charset=$codepage\n";
$header_adm .= "X-Mailer: PHP/";
$zag_adm=$LoadItems['System']['name']." - Поступил заказ №".@$ouid."/".date("d-m-y");

$datas=date("U");


// Новая запись в базу массива
$OrderWrite = new OrderWrite(@$cart,$_REQUEST,$LoadItems,$SysValue,$ChekDiscount[0],$_SESSION['UsersId'],
$GetDeliveryPrice);
$Content = $OrderWrite->MAS;
$NumInCart = $OrderWrite->NUM; // Кол-во товаров в корзине

if($NumInCart>0){

// Шлем мыло
$Option=unserialize($LoadItems['System']['admoption']);
mail($LoadItems['System']['adminmail2'],$zag_adm, $content_adm, $header_adm);
mail($mail,$zag,$content,$header);


// Заносим пользователя в рассылку
if(@$_POST['mail_active'] == 1){
$sql="INSERT INTO ".$SysValue['base']['table_name9']."
   VALUES ('','$datas','$mail')";
   $result=mysql_query($sql);
}

$Status=array(
"maneger"=>"",
"time"=>""
);

// Продавцы
foreach($_SESSION['cart'] as $v)
@$seller.="i".$v['user']."i";

 $sql="INSERT INTO ".$SysValue['base']['table_name1']."
   VALUES ('','$datas','$ouid','$Content','".serialize($Status)."','".$_SESSION['UsersId']."','".@$seller."','0')";
   $result=mysql_query($sql);
//session_unregister('cart');

// Участие в сборе статистике оборота
if(!getenv("COMSPEC") and $SysValue['my']['money_stat'] == "true")
$Money = StatMoneyClient($sum);

}
}
?>
