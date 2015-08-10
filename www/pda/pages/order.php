<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Файл Оформление Заказа             |
+-------------------------------------+
*/

// Генерим корзину
function Chek($stroka)// проверка вводимых цифр
{
    if (!ereg ("([0-9])", $stroka)) $stroka="0";
    return abs($stroka);
}

function Chek2($stroka)// проверка вводимых цифр
{
    if (!ereg ("([0-9])", $stroka)) $stroka="0";
    return number_format(abs($stroka),"2",".","");
}


// Очистка корзины
if(@$SysValue['nav']['query']['cart']=="clean") {
    unset($_SESSION['cart']);
}


function ReturnNum($cart) {
    while (list($key, $value) = @each($cart)) @$num+=$cart[$key]['num'];
    return @$num;
}

if(isset($_POST['id_edit']))// редактирование кол-ва
{
    $_SESSION['cart'][Chek($id_edit)]['num']=abs($num_new);
}
if(isset($id_delet))// удаление товара
{
    unset($_SESSION['cart'][Chek(@$id_delet)]);
}
if(@$_SESSION['cart'][@$id_edit]['num']=="0")// удаление товара с нулевым кол-ом
{
    unset($_SESSION['cart'][$id_edit]);
}


if(isset($_GET['xid']))// запись в массив
{
    $xid=Chek($_GET['xid']);
    $sql="select * from ".$SysValue['base']['table_name2']." where id=$xid and enabled='1'";
    $result=mysql_query($sql);
    @$row = mysql_fetch_array(@$result);
    $name=$row['name'];
    $price=$row['price'];
    $price=($price+(($price*$LoadItems['System']['percent'])/100));
    $uid=$row['uid'];
    $id=$row['id'];
    $user=$row['user'];

// Выборка из базы нужной колонки цены
    if(!empty($_SESSION['UsersStatus'])) {
        $GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
        if($GetUsersStatusPrice>1) {
            $pole="price".$GetUsersStatusPrice;
            $pricePersona=$row[$pole];
            if(!empty($pricePersona))
                $price=($pricePersona+(($pricePersona*$LoadItems['System']['percent'])/100));
        }
    }

    $num=@$_SESSION['cart'][$xid]['num']+$_num;
    $cart_new=array(
            "id"=>"$id",
            "name"=>"$name",
            "price"=>$price,
            "uid"=>"$uid",
            "num"=>1,
            "user"=>$user
    );
    $_SESSION['cart'][$xid]=$cart_new;
}


$option=unserialize($LoadItems['System']['admoption']);

if(count(@$_SESSION['cart'])>0)// вывод корзины
{
    $cart=$_SESSION['cart'];
    $display_cart=null;
    if(is_array($cart))
        foreach($cart as $j=>$v) {
            $price_now=ReturnSummaNal($cart[$j]['price']*$cart[$j]['num'],0);
            $priceOrder=$cart[$j]['price']*$cart[$j]['num'];

            //$CatId=$LoadItems['Product'][$cart[$j]['id']]['category'];
            //$Catname=$LoadItems['Podcatalog'][$CatId]['name'];

            $display_cart.='
<tr>
	<td>
	<form method="post" action="./">
	<a href="/shop/UID_'.$cart[$j]['id'].'.html" title="'.$cart[$j]['name'].'">'.$cart[$j]['name'].'</a></td>
	<td><input type=text value='.$cart[$j]['num'].' size=3 maxlength=3 name="num_new"></td>
	<td>
<table cellpadding="0" cellspacing="0">
<tr>
	<td>
	<input type=hidden name="id_edit" value='.$cart[$j]['id'].'>
	<input type="submit" name="edit_num" value="обн.">
</td>
	<td>
</form>
<form method="post" action="./">
<table cellpadding="0" cellspacing="0">
<tr>
	<td>
	
	<input type="submit" name="edit_del" value="удл.">
	<input type=hidden name="id_delet" value='.$cart[$j]['id'].'>
	</td>
</tr>
</table>
</form>
	</td>
</tr>
</table>
	</td>
	<td align=right class=red>'.$price_now.' '.GetValutaOrder().'</td>
	</td>
</tr>

 ';
            $sum+=$price_now;
            $sumOrder+=$priceOrder;
            $sum=number_format($sum,"2",".","");
            $num+=$cart[$j]['num'];

//Определение и суммирование веса
            $goodid=$cart[$j]['id'];
            $goodnum=$cart[$j]['num'];
            $wsql='select weight from '.$SysValue['base']['table_name2'].' where id=\''.$goodid.'\'';
            $wresult=mysql_query($wsql);
            $wrow=mysql_fetch_array($wresult);
            $cweight=$wrow['weight']*$goodnum;
            if (!$cweight) {
                $zeroweight=1;
            } //Один из товаров имеет нулевой вес!
            $weight+=$cweight;


        }




//Обнуляем вес товаров, если хотя бы один товар был без веса
    if ($zeroweight) {
        $weight=0;
        $we=' &ndash; Не указан';
    } else {
        $we='&nbsp;гр.';
    }

    $GetDeliveryPrice=GetDeliveryPrice("",$sum,$weight);


    if(count(@$cart)>0) {
        $ChekDiscount=ChekDiscount($sumOrder);
        @$display='
<script language="JavaScript">
window.document.getElementById("num").innerHTML="'.$num.'";
window.document.getElementById("sum").innerHTML="'.$sum.'";
</script>
<table border=0 width=470 cellpadding=0 cellspacing=3 class=style1>
<tr>
	<td ><strong>Наименование</strong></td>
	<td width=50><strong>Кол-во</strong></td>
	<td width=50><strong>Операции</strong></td>
	<td width=50 align="right" colspan=""><strong>Цена</strong></td>
</tr>
<tr>
	<td colspan="4">
	<img src="images/shop/break.gif" alt="" width="100%" height="1" border="0">
	</td>
</tr>
'.$display_cart.'

<tr>
	<td colspan="4">
	<img src="images/shop/break.gif" alt="" width="100%" height="1" border="0">
	</td>
</tr>
<tr style="padding-top:10">
   <td ><b>Итого:</b></td>
   <td class=style2>
<strong>'.ReturnNum($cart).'</strong> (шт.)
	</td>
	<td></td>
   <td class=red align="right">
   '.Chek2($sum).' '.GetValutaOrder().'<br>
</td>
</tr>
<tr>
	<td colspan="4">
	<img src="images/shop/break.gif" alt="" width="100%" height="1" border="0">
	</td>
</tr>
</table>
<table border=0  width=470 cellpadding=0 cellspacing=3 class=style1 align="center">
<tr style="padding-top:0">
   <td colspan="3" valign="top">Скидка:</td>
   <td class=red align="right"><span id="SkiSumma">'.$ChekDiscount[0].'</span>&nbsp;%</td>
</tr>
<tr style="padding-top:0">
   <td colspan="3" valign="top">Вес товаров:</td>
   <td class=red align="right"><span id="WeightSumma">'.$weight.'</span>'.$we.'</td>
</tr>


<tr>
    <td>
  К оплате с учетом скидки (без доставки):
	</td>
	<td class=style2>
	</td>
	<td colspan=2 align="right" class=red>
	<b><span id="TotalSumma">'.(ReturnSummaOrder($sum,$ChekDiscount[0])+$GetDeliveryPrice).'</span></b> '.GetValutaOrder().'</td>
</tr>
</table>
<input type="hidden" id="OrderSumma" name="OrderSumma"  value="'.ReturnSummaOrder($sum,$ChekDiscount[0]).'">
';
    }

// Генерим номер заказа
    $sql="select uid from ".$SysValue['base']['table_name1']." order by uid desc LIMIT 0, 1";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $last=$row['uid'];
    $all_num=explode("-",$last);
    $ferst_num=$all_num[0];
    if($ferst_num<100) $ferst_num=100;
    $order_num = $ferst_num + 1;
    $order_num=$order_num."-".substr(abs(crc32(uniqid($sid))),0,2);


    $SysValue['other']['orderNum']= $order_num;
    $SysValue['other']['orderWeight']= ReturnNum($cart);
    $SysValue['other']['catalogCat']= "Оформление заказа";
    $SysValue['other']['catalogCategory']= "Данные";
    $SysValue['other']['orderContentCart']=@$display;
    $SysValue['other']['orderDate']=date("d-m-y");
    $SysValue['other']['orderDelivery']=GetDelivery(@$_GET['d']);
    $SysValue['other']['orderOplata']=GetOplataMetod();
    $SysValue['other']['deliveryId']= @$_GET['d'];


// Если корзина больше суммы мимального заказа
    if($option['cart_minimum'] < $sum) {

// Подключаем шаблон
        $SysValue['other']['orderContent']=ParseTemplateReturn($SysValue['templates']['main_order_forma']);

    }else {

        // Определяем переменые
        $SysValue['other']['orderContent']="<FONT style=\"font-size:12px;color:red\">
<B>".$SysValue['lang']['cart_minimum']." ".$option['cart_minimum']." </B></FONT>
";


    }
    $SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['main_order_list']);


}

else {
    // Определяем переменые
    $SysValue['other']['mesageText']= "<FONT style=\"font-size:14px;color:red\">
<B>".$SysValue['lang']['bad_cart_1']."</B></FONT><BR>".$SysValue['lang']['bad_order_mesage_2']."
<script language=\"JavaScript\">
if(window.document.getElementById('num')){
window.document.getElementById('num').innerHTML='0';
window.document.getElementById('sum').innerHTML='0';
}
</script>
";

    // Подключаем шаблон
    $SysValue['other']['orderMesage']=ParseTemplateReturn($SysValue['templates']['order_forma_mesage']);
// Определяем переменые
    $SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['order_forma_mesage_main']);

}

// Подключаем шаблон 
@ParseTemplate($SysValue['templates']['index']);
?>
