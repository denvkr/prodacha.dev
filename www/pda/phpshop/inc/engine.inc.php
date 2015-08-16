<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль Генерации                   |
+-------------------------------------+
*/

// Отрезаем до точки
function mySubstr($str,$a) {

    if(empty($str)) return $str;

    $str = htmlspecialchars(strip_tags($str));
    for ($i = 1; $i <= $a; $i++) {
        if($str{$i} == ".") $T=$i;
    }

    if($T<1) return substr($str, 0, $a)."...";
    else return substr($str, 0, $T+1);
}


// Выборка колонки цен для статуса пользователя
function GetUsersStatusPrice($n) {
    global $SysValue;
    $sql="select price from ".$SysValue['base']['table_name28']." where id=$n";
    $result=mysql_query($sql);
    $row = mysql_fetch_array(@$result);
    return $row['price'];
}

// Выборка из базы нужной колонки цены
function ReturnTruePriceUser($n,$price) {
    global $SysValue,$LoadItems,$_SESSION;
    if(session_is_registered('UsersStatus')) {
        $GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
        $pole="price".$GetUsersStatusPrice;
        $sql="select $pole from ".$SysValue['base']['table_name2']." where id='$n'";
        $result=mysql_query($sql);
        @$row = mysql_fetch_array(@$result);
        if(!empty($row[$pole])) return $row[$pole];
        else return $price;
    } else return $price;
}


// Характеристики
function Sorts() {
    global $SysValue;
    $sql="select id,name from ".$SysValue['base']['table_name21'];
    $result=mysql_query($sql) or  die("".PHPSHOP_error(102,$SysValue['my']['error_tracer'])."");
    while ($row = mysql_fetch_array($result)) {
        $id=$row['id'];
        $name=$row['name'];
        $array=array(
                "id"=>$id,
                "name"=>$name
        );
        $Sorts[$id]=$array;
    }
    @$SysValue['sql']['num']++;
    return $Sorts;
}

// Каталоги Характеристик
function CatalogSorts() {
    global $SysValue;
    $sql="select * from ".$SysValue['base']['table_name20'];
    @$result=mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $id=$row['id'];
        $name=$row['name'];
        $category=$row['category'];
        $array=array(
                "id"=>$id,
                "name"=>$name,
                "category"=>$category
        );
        $Sorts[$id]=$array;
    }
    @$SysValue['sql']['num']++;
    return $Sorts;
}

// Каталог статей
function CatalogPage() {
    global $SysValue;
    $sql="select id,name,parent_to from ".$SysValue['base']['table_name29'];
    $result=mysql_query($sql) or  die("".PHPSHOP_error(102,$SysValue['my']['error_tracer'])."");
    while ($row = mysql_fetch_array($result)) {
        $id=$row['id'];
        $name=$row['name'];
        $parent_to=$row['parent_to'];
        $array=array(
                "id"=>$id,
                "name"=>$name,
                "parent_to"=>$parent_to
        );
        $Catalog[$id]=$array;
    }
    @$SysValue['sql']['num']++;
    return $Catalog;
}

// Вывод каталогов страниц
function  CatalogPageKeys() {
    global $SysValue;
    $sql="select id,parent_to  from ".$SysValue['base']['table_name29']." order by num";
    $result=mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $id=$row['id'];
        $parent_to=$row['parent_to'];
        $Catalog[$id]=$parent_to;
    }
    @$SysValue['sql']['num']++;
    return $Catalog;
}

// Вывод каталогов
function  CatalogKeys() {
    global $SysValue;
    $sql="select id,parent_to  from ".$SysValue['base']['table_name']." order by num";
    $result=mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $id=$row['id'];
        $parent_to=$row['parent_to'];
        $Catalog[$id]=$parent_to;
    }
    @$SysValue['sql']['num']++;
    return $Catalog;
}

// Вывод каталогов
function  Catalog() {
    global $SysValue;
    $sql="select * from ".$SysValue['base']['table_name'];
    $result=mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $id=$row['id'];
        $name=$row['name'];
        $parent_to=$row['parent_to'];
        $num_row=$row['num_row'];
        $num_cow=$row['num_cow'];
        $sort=$row['sort'];
        $servers=$row['servers'];
        $title_enabled=$row['title_enabled'];
        $descrip_enabled=$row['descrip_enabled'];
        $keywords_enabled=$row['keywords_enabled'];
        $skin_enabled=$row['skin_enabled'];
        $skin=$row['skin'];
        $array=array(
                "id"=>$id,
                "name"=>$name,
                "parent_to"=>$parent_to,
                "num_row"=>$num_row,
                "num_cow"=>$num_cow,
                "sort"=>$sort,
                "title_enabled"=>$title_enabled,
                "descrip_enabled"=>$descrip_enabled,
                "keywords_enabled"=>$keywords_enabled,
                "skin_enabled"=>$skin_enabled,
                "skin"=>$skin,
                "order_by"=>$row['order_by'],
                "order_to"=>$row['order_to'],
                "parent_len"=>$row['parent_len'],
                "vid"=>$row['vid'],
                "servers"=>$servers
        );
        $Catalog[$id]=$array;
    }
    @$SysValue['sql']['num']++;
    return $Catalog;
}


// Вывод продуктов
function  Product($str="") {
    global $SysValue,$LoadItems,$_SESSION;

    if($str != "none") {

        $System=DispSystems();
        $sql="select id,uid,name,category,price,price_n,sklad,odnotip,vendor,title_enabled,datas,page,user,descrip_enabled,keywords_enabled,pic_small,pic_big,parent,price2,price3,price4,price5  from ".$SysValue['base']['table_name2'].$str;
        $result=mysql_query($sql);
        while (@$row = mysql_fetch_array($result)) {
            $id=$row['id'];
            $uid=$row['uid'];
            $name=$row['name'];
            $category=$row['category'];
            $price=$row['price'];
            $sklad=$row['sklad'];
            $priceNew=$row['price_n'];
            $price=($price+(($price*$System['percent'])/100));
            $title_enabled=$row['title_enabled'];
            $descrip_enabled=$row['descrip_enabled'];
            $keywords_enabled=$row['keywords_enabled'];
            $datas=$row['datas'];
            $page=explode(",",$row['page']);
            $user=$row['user'];
            $pic_small=$row['pic_small'];
            $pic_big=$row['pic_big'];


            // Выборка из базы нужной колонки цены
            if(session_is_registered('UsersStatus')) {
                $GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
                if($GetUsersStatusPrice>1) {
                    $pole="price".$GetUsersStatusPrice;
                    $pricePersona=$row[$pole];
                    if(!empty($pricePersona))
                        $price=($pricePersona+(($pricePersona*$System['percent'])/100));
                }
            }

            // Если есть новая цена
            if($priceNew>0) {
                $priceNew=($priceNew+(($priceNew*$System['percent'])/100));
                $priceNew=number_format($priceNew,"2",".","");
                //$priceNew=$priceNew." ".$System['dengi'];
            }

            // Проверка на нулевую цену
            if(!is_numeric($row['price']))
                $sklad = 1;

            $uid=$row['uid'];
            $odnotip=explode(",",$row['odnotip']);
            $parent=explode(",",$row['parent']);
            $vendor=$row['vendor'];


            $array=array(
                    "category"=>$category,
                    "id"=>$id,
                    "name"=>$name,
                    "price"=>$price,
                    "priceNew"=>$priceNew,
                    "sklad"=>$sklad,
                    "odnotip"=>$odnotip,
                    "parent"=>$parent,
                    "vendor"=>$vendor,
                    "uid"=>$uid,
                    "pic_small"=>$pic_small,
                    "pic_big"=>$pic_big,
                    "title_enabled"=>$title_enabled,
                    "descrip_enabled"=>$descrip_enabled,
                    "keywords_enabled"=>$keywords_enabled,
                    "datas"=>$datas,
                    "page"=>$page,
                    "user"=>$user
            );
            $Products[$id]=$array;
        }
        @$SysValue['sql']['num']++;
        return @$Products;
    }
}




// Вывод кол-ва
function NumFrom($from_base,$query) {
    global $SysValue;
    $sql="select COUNT('id') as count from ".$SysValue['base'][$from_base]." ".$query;
    @$result=mysql_query(@$sql);
    $row = mysql_fetch_array($result);
    @$num=$row['count'];
    return @$num;
}

function DispSystems()// вывод настроек
{
    global $SysValue;
    $sql="select * from ".$SysValue['base']['table_name3'];
    $result=mysql_query($sql);
    @$row = mysql_fetch_array(@$result);
    if(is_array($row))
        foreach($row as $k=>$v)
            $array[$k]=$v;

// Валюта по умолчанию
//$array['dengi']=4;

    @$SysValue['sql']['num']++;
    return $array;
}

function DispValuta()// вывод валют
{
    global $SysValue;
    $sql="select * from ".$SysValue['base']['table_name24']." where enabled='1' order by num";
    $result=mysql_query($sql);
    while (@$row = mysql_fetch_array($result)) {
        $id=$row['id'];
        $name=$row['name'];
        $code=$row['code'];
        $iso=$row['iso'];
        $kurs=$row['kurs'];
        $array=array(
                "id"=>$id,
                "name"=>"$name",
                "code"=>"$code",
                "iso"=>"$iso",
                "kurs"=>"$kurs"
        );
        $Valuta[$id]=$array;
    }
    @$SysValue['sql']['num']++;
    return $Valuta;
}

function Open($page)// Проверка существования файла
{
    global $SysValue;
    $page=$page.".php";
    $handle=@opendir('pages');
    while ($file = readdir($handle)) {
        if($file==$page) {
            return $page;
            exit;
        }
    }
    return "404";
}


function Vivod_mini_cart()// Вывод мини корзины
{
    global $SysValue,$LoadItems,$_SESSION;
    $cart=@$_SESSION['cart'];
    if(count($cart)>0) {
        if(is_array($cart))
            foreach($cart as $j=>$v) {
                @$sum+=$cart[$j]['price']*$cart[$j]['num'];
                @$sum=number_format($sum,"2",".","");
                @$sum_r=@$sum*$LoadItems['System']['kurs'];
                @$sum_r=number_format($sum_r,"2",".","");
                @$num+=$cart[$j]['num'];
            }
        $SysValue['other']['orderEnabled']= "block";
    }
    else {
        $sum="--";
        $num="--";
        $SysValue['other']['orderEnabled']= "none";
    }

// Определяем переменые
    $SysValue['other']['tovarNow']= $SysValue['lang']['cart_tovar_now'];
    $SysValue['other']['num']= $num;
    $SysValue['other']['sum']= GetPriceValuta($sum);
    $SysValue['other']['summaNow']= $SysValue['lang']['cart_summa_now'];
    $SysValue['other']['money']= GetValuta();
    $SysValue['other']['orderNow']= $SysValue['lang']['cart_order_now'];

// Подключаем шаблон
    @$disp=ParseTemplateReturn($SysValue['templates']['menu_cart']);
    return $disp;
}





function Vivod_forma_valuta()// вывод формы валюты
{
    global $SysValue,$LoadItems,$_SESSION;

    if(isset($_SESSION['valuta'])) $valuta=$_SESSION['valuta'];
    else $valuta=$LoadItems['System']['dengi'];

    if(is_array($LoadItems['Valuta']))
        foreach($LoadItems['Valuta'] as $k=>$v) {
            if($valuta == $LoadItems['Valuta'][$k]['id']) $sel="selected";
            else $sel="";
            @$name.= '<option value="'.$LoadItems['Valuta'][$k]['id'].'" '.$sel.' >'.$LoadItems['Valuta'][$k]['iso'].'</option>';
        }
// Определяем переменые
    $SysValue['other']['leftMenuContent']="
<form name=ValutaForm method=post>
<select name=\"valuta\" onchange=\"ChangeValuta()\">
".@$name."
</select>
</form>
";

// Подключаем шаблон
    if(!ParseTemplateReturn($SysValue['templates']['valuta_forma']))
        @$dis.=ParseTemplateReturn($SysValue['templates']['left_menu']);
    else @$dis.=ParseTemplateReturn($SysValue['templates']['valuta_forma']);

    return @$dis;
}



// Вывод содержания страниц
function Open_from_base($name,$str="none") {
    global $SysValue;
    $name=TotalClean($name,2);
    if($str=="none") $sql="select * from ".$SysValue['base']['table_name11']." where link='$name'";
    else $sql="select * from ".$SysValue['base']['table_name11']." $str";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    @$SysValue['sql']['num']++;
    $names=$row['name'];
    $content=stripslashes($row['content']);
    $link=$row['link'];
    $ar=array($names,$content,$link);
    return $ar;
}


function Vivod_cat_all_num($n)// выбор кол-ва товаров из данного подкатолога
{
    global $SysValue;
    $n=TotalClean($n,1);
    $sql="select id from ".$SysValue['base']['table_name2']." where category=$n and enabled=1";
    $result=mysql_query($sql);
    $num=mysql_num_rows($result);
    return $num;
}


// Вывод городов доставки
function GetDelivery($deliveryID,$PID=0) {
    global $SysValue;
    $sql="select * from ".$SysValue['base']['table_name30']." where enabled='1' order by city";
    $result=mysql_query($sql);
    while($row = mysql_fetch_array($result)) {

        if($row['flag']==1) $chk="selected";
        else $chk="";

        if($row['is_folder'] == 1) $ArrayDelCat[$row['id']]=$row['city'];
        else @$dis.='<OPTION value='.$row['id'].' '.$chk.' >'.$ArrayDelCat[$row['PID']].' ->  '.$row['city'].'</OPTION>';
    }
    $disp='<SELECT name="dostavka_metod">'.@$dis.'</SELECT>';
    return $disp;
}

// Вывод стоимости доставки
function GetDeliveryPrice($deliveryID,$sum,$weight=0) {
    global $SysValue;

    $deliveryID=TotalClean($deliveryID,1);
    if(!empty($deliveryID)) {
        $sql="select * from ".$SysValue['base']['table_name30']." where id='$deliveryID' and enabled='1'";
        $result=mysql_query($sql);
        $num=mysql_numrows($result);
        $row = mysql_fetch_array($result);
        if($num == 0) {
            $sql="select * from ".$SysValue['base']['table_name30']." where flag='1' and enabled='1'";
            $result=mysql_query($sql);
            $row = mysql_fetch_array($result);
        }
    } else {
        $sql="select * from ".$SysValue['base']['table_name30']." where flag='1' and enabled='1'";
        $result=mysql_query($sql);
        $row = mysql_fetch_array($result);
    }

    @$SysValue['sql']['num']++;

    if($row['price_null_enabled'] == 1 and $sum>=$row['price_null']) {
        return 0;
    } else {
        if ($row['taxa']>0) {
            $addweight=$weight-500;
            if ($addweight<0) {
                $addweight=0;
                $at='';
            } else {
                $at='';
//			$at='Вес: '.$weight.' гр. Превышение: '.$addweight.' гр. Множитель:'.ceil($addweight/500).' = ';
            }
            $addweight=ceil($addweight/500)*$row['taxa'];
            $endprice=$row['price']+$addweight;
            return $at.$endprice;
        } else {
            return $row['price'];
        }
    }
}//endfunct

// Вывод доставки
function GetDeliveryBase($deliveryID,$name) {
    global $SysValue;
    $sql="select * from ".$SysValue['base']['table_name30']." where id='$deliveryID'";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    return $row[$name];
}

// Вывод методов оплаты
function GetOplataMetod() {
    global $SysValue;

    $sql="select * from ".$SysValue['base']['table_name48']." where enabled='1' order by num";
    $result=mysql_query($sql);
    while($row = mysql_fetch_array($result)) {
        $name=$row['name'];
        $id=$row['id'];
        //@$dis.='<option value="'.$id.'" >'.$name.'</option>';
			@$dis.='<input type="radio" name="order_metod" value="'.$id.'" > '.$name; 
    }

    $disp=$dis;
	
    return $disp;
}


function Vivod_menu_top()// вывод главного меню
{
    global $SysValue,$_SESSION;

// Страницы только для аторизованных
    if(isset($_SESSION['UsersId'])) $sort=" ";
    else $sort=" and secure !='1' ";
    $sql="select * from ".$SysValue['base']['table_name11']." where category='1000' and enabled='1' $sort order by num";

    $result=mysql_query($sql);
    while($row = mysql_fetch_array($result)) {
// Определяем переменые
        $SysValue['other']['topMenuName']= $row['name'];
        $SysValue['other']['topMenuLink']= $row['link'];

// Подключаем шаблон
        @$dis.=ParseTemplateReturn($SysValue['templates']['top_menu']);
    }
    @$SysValue['sql']['num']++;
    return @$dis;
}

// Вывод страниц списком
function GetSelectPage() {
    global $SysValue,$_SESSION;

// Страницы только для аторизованных
    if(isset($_SESSION['UsersId'])) $sort=" ";
    else $sort=" and secure !='1' ";
    $sql="select * from ".$SysValue['base']['table_name11']." where category='1000' and enabled='1' $sort order by num";
    $result=mysql_query($sql);
    while($row = mysql_fetch_array($result)) {
        @$dis.='
<OPTION value="/page/'.$row['link'].'.html">'.$row['name'];
    }
    $dis="
<form method=post>
<select name=\"skin\" onchange=\"ChangePage(this.value)\">
<OPTION value='/'> -- навигация -- 
<OPTION value='/pda/'>Главная
<OPTION value='/search/'>Поиск
<OPTION value='/news/'>Новости
".@$dis."
</select>
</form>
";
    return @$dis;
}
?>