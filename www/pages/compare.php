<?php

$shopdir=$SysValue['other']['ShopDir'];
$limit=4; //Максимум товаров для сравнения
if(!empty($_SESSION['compare']))
$copycompare=$_SESSION['compare']; 
else $copycompare=array();

// API 2.1
$LoadItems['Valuta']=$PHPShopValutaArray->getArray();
$LoadItems['System']=$PHPShopSystem->getArray();

/**
 * Полное имя товара для вывода сравнения
 * @package PHPShopCoreDepricated
 * @param int $id ИД товара
 * @return string 
 */
function getfullname ($id=0) {
    global $SysValue;
    
    $sql='select name,parent_to from '.$SysValue['base']['table_name'].' where id='.intval($id);
    $result=mysql_query($sql);
    @$row = mysql_fetch_array(@$result);
    if ($row['parent_to']) {
        return getfullname($row['parent_to']).' / '.$row['name'];
    } else {
        return $row['name'];
    }
}


if (!($SysValue['nav']['id']=="ALL")) {
    $SysValue['nav']['id']=intval($SysValue['nav']['id']);
}

if ($SysValue['nav']['nav']=="COMCID") { 
    if (isset($SysValue['nav']['id']) && ($SysValue['nav']['id'])) {
        $COMCID=$SysValue['nav']['id'];
    }
}

// Подготовка массивов товаров и категорий
if(is_array($copycompare))
    sort($copycompare); //Сортируем по категориям
$oldcatid=''; //Первоначальный идентификатор категории

if(is_array($copycompare))
    foreach($copycompare as $id =>$val) { 
    
        //Если идентификатор категории товара изменился, меняем категорию
        if ($oldcatid!=$val['category']) { 
            
            $catid=$val['category'];
            $oldcatid=$catid; //Меняем идентификатор.
            $cats[$catid]=getfullname($catid);
        }
        $goods[$oldcatid][$id]['name']=$val['name'];
        $goods[$oldcatid][$id]['id']=$val['id'];
    } 


$COMCID=0;
$dis="";

if(empty($cats)) $cats=0;

if(is_array($cats))
    foreach ($cats as $catid => $name) {
        if ((count($goods[$catid])>1) && (count($goods[$catid])<=$limit)) {
            if ($catid!=$COMCID) {
                $as='<img src="images/shop/icon-activate.gif" alt="Убрать товар из сравнения" width="19" height="15" border="0" hspace="0" align="absmiddle"><B style="color:green;">Сравнить в категории:</B> <A href="'.$shopdir.'/compare/COMCID_'.$catid.'.html#list" style="font-weight:bold;" title="Сравнить в '.$name.'">';
                $ae='</A>';
            } else {
                $as='<B>СЕЙЧАС СРАВНИВАЕТСЯ: ';
                $ae='</B>';
                
            }
            $dis.='
		<tr><td colspan="2" width="30"><br></td></tr>
		<TR><TD colspan="2" id=allspec >'.$as.$name.$ae.'</TD>
		<TD>&nbsp;</TD></TR>';
            $green[]=$catid; //Добавить каталог в разрешенные
        } elseif(count($goods[$catid])>$limit) {
            $dis.='
		<TR><TD><BR><B>'.$name.'</B></TD>
		<TD><BR>&ndash; <FONT style="color:red;">слишком много товаров, чтобы сравнить <B>(MAX='.$limit.')</B>. Удалите лишние!</FONT></TD></TR>';
        } else {
            $dis.='
		<tr><td  width="30"><br></td></tr>
		<TR><TD  id=allspec><B>'.$name.'</B></TD>
		<TD id=allspec><FONT style="color:red;">недостаточно товаров, чтобы сравнить <B>(MIN=2)</B>. Добавьте еще товары из этой категории!</FONT></TD></TR>';
        }
        foreach ($goods[$catid] as $id => $val) {
            $dis.='<TR><TD class=sort_table>'.$val['name'].' </TD><TD class=sort_table><A href="'.$shopdir.'/compare/DID_'.$val['id'].'.html" title="Убрать товар из сравнения"><img src="images/shop/icon-deactivate.gif" alt="Убрать товар из сравнения" width="19" height="15" border="0" hspace="0" align="absmiddle">[Убрать товар из сравнения]</A></TD></TR>';
        }
    }

// Дополнение - сравнить по всем категориям
if (count($cats)>1) { //Если больше двух каталогов
    $name='по ВСЕМ категориям';
    if ((count($compare)>1) && (count($compare)<=$limit)) {
        if ($COMCID!="ALL") {
            $as='<img src="images/shop/icon-activate.gif" alt="Убрать товар из сравнения" width="19" height="15" border="0" hspace="0" align="absmiddle"><B style="color:green;">Сравнить </B> <A href="'.$shopdir.'/compare/COMCID_ALL.html#list" style="font-weight:bold;" title="Сравнить по ВСЕМ категориям">';
            $ae='</A>';
        } else {
            $as='СЕЙЧАС СРАВНИВАЕТСЯ:<B> ';
            $ae='</B>';
        }
        $dis.='
		<tr><td colspan="2" width="30"><br></td></tr>
		<TR><TD colspan="2" id=allspec>'.$as.$name.$ae.'</TD>
		<TD>&nbsp;</TD></TR>';
        $green[]="ALL"; //Добавить каталог в разрешенные
    } elseif(count($compare)>$limit) {
        $dis.='
		<TR><TD><BR><B>'.$name.'</B></TD>
		<TD><BR>&ndash; <FONT style="color:red;">слишком много товаров, чтобы сравнить <B>(MAX='.$limit.')</B>. Удалите лишние!</FONT></TD></TR>';
    } else {
        $dis.='
		<TR><TD><BR><B>'.$name.'</B></TD>
		<TD><BR>&ndash; <FONT style="color:red;">недостаточно товаров, чтобы сравнить <B>(MIN=2)</B>. Добавьте еще товары из этой категории!</FONT></TD></TR>';
    }
}

// Вывод управляющего интерфейса
$disp='<TABLE width="95%">'.$dis.'</TABLE>
<div style="padding-top: 10px;padding-bottom: 30px" align="center">
<A href="'.$shopdir.'/compare/DID_ALL.html" title="Удалить все товары из списка"><img src="images/shop/error.gif" alt="Удалить все товары из списка"  border="0" hspace="5" align="absmiddle" >[Удалить все товары из списка]</A></div>';

// Выбор каталога для показа
if (!$COMCID) { //Если не указан каталог
    if (@count($green)>0) {//Если хоть один каталог можно показать
        krsort($green);
        foreach ($green as $c) {
            $COMCID=$c;
            break;	
        }
    } else {
        $disp.='<P>Сравнение выбранных вами товаров невозможно. Удалите или добавьте нужное количество товаров.</P>';
    }
}

// Обработка действия пользователей
if ($SysValue['nav']['nav']=="DID") {
    $id=$SysValue['nav']['id'];
    if ($id=="ALL") {
        $_SESSION['compare']=null;
        unset($_SESSION['compare']);
        echo '<SCRIPT>window.location.replace(\''.$shopdir.'/compare/\');</SCRIPT>';
    } else {
        unset($_SESSION['compare'][$id]);
        echo '<SCRIPT>window.location.replace(\''.$shopdir.'/compare/\');</SCRIPT>';
    }
}

$catid=$COMCID;

// Сравение
if(!empty($_SESSION['compare']))
if (($COMCID && (count($goods[$catid])>1) && (count($goods[$catid])<=$limit)) || 
        ((($COMCID=="ALL") && (count($_SESSION['compare'])>1) && (count($_SESSION['compare'])<=$limit)))) { //Если выбран каталог сравнения
   
    if ($COMCID=="ALL") {
        $comparing='все категории';
    } else {
        $comparing=getfullname($COMCID);
    }
    
    $disp.='<a name="list"></a><P><h5>Сравнение товаров в категории:<br> '.$comparing.'</h5></P>';

    if ($COMCID!="ALL") {
        $sql='select sort from '.$SysValue['base']['table_name'].' where id='.intval($COMCID);
        $result=mysql_query($sql);
        @$row = mysql_fetch_array(@$result);
        $sorts=unserialize($row['sort']);
    } else {
        foreach ($cats as $catid => $name) {
            $sql='select sort from '.$SysValue['base']['table_name'].' where id='.intval($catid);
            $result=mysql_query($sql);
            @$row = mysql_fetch_array(@$result);
            $tempsorts=unserialize($row['sort']);
            if(is_array($tempsorts))
                foreach ($tempsorts as $curtempsort) {
                    $sorts[]=$curtempsort;
                }
        }
    }
    if(is_array($sorts))
        $sorts=array_unique($sorts);//Оставляем только уникальные сортировки
    
    $sorts_name='';
    
    if(is_array($sorts))
        foreach ($sorts as $sort) {
            $sql='select name from '.$SysValue['base']['table_name20'].' where id='.intval($sort).' AND goodoption=0';
            $result=mysql_query($sql);
            @$row = mysql_fetch_array(@$result);
            $sorts_name[$sort]=$row['name'];
        }
        
    /*
     * Товары могут быть из разных категорий, в которых одинаковые характеристики могут иметь разное название
     * Поэтому реверсируем массив. Получаем массив Имя_характеристики = массив идентификаторов
     */
    if(is_array($sorts_name))
        foreach ($sorts_name as $sort =>$name) {
            $sql='select id from '.$SysValue['base']['table_name20'].' where name LIKE \''.$name.'\'';
            $result=mysql_query($sql);
            while ($row = mysql_fetch_array(@$result)) {
                $sorts_name2[$name][$row['id']]=1;
            }
        }
    
    if(empty($sorts_name2)) $sorts_name2=0;
    
    // Подготовка Матрицы для будущей таблицы
    $TDR[0][]='Товар';
    $TDR[0][]='Фото';
    $TDR[0][]='Цена';
    if(is_array($sorts_name2))
        foreach ($sorts_name2 as $name=>$id) {
            $TDR[0][]=$name;
        }
    $TDR[0][]='Описание';		
    $igood=0; 

    if ($COMCID!="ALL") {
        $goodstowork=$goods[$COMCID];
    } else {
        foreach ($cats as $catid => $name) {
            foreach ($goods[$catid] as $curtempgood) {
                $goodstowork[]=$curtempgood;
            }
        }
    }
    
    // Получаем умолчательню валюту
    $sql="select dengi from ".$SysValue['base']['table_name3'];
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    $defvaluta=$row['dengi'];
    // Получаем умолчательню валюту
    
    foreach ($goodstowork as $id => $val) {
        $igood++;
        $TDR[$igood][]='<A href="/shop/UID_'.$val['id'].'.html" title="'.$val['name'].'">'.$val['name'].'</A>';

        //Выбираем товар из базы
        $sql='select id,price,pic_small,vendor_array,content,baseinputvaluta from '.$SysValue['base']['table_name2'].' where id='.intval($val['id']);
        $result=mysql_query($sql);
        @$row = mysql_fetch_array(@$result);
        if (trim($row['pic_small'])) {
            $TDR[$igood][]='<IMG SRC="'.$row['pic_small'].'">';
        } else {
            $TDR[$igood][]='Изображение отсутствует';
        }
        $baseinputvaluta=$row['baseinputvaluta'];
        $price=$row['price'];
        $id=$row['id'];

        //получаем исходную цену
        if ($baseinputvaluta) { //Если прислали баз. валюту
            if ($baseinputvaluta!==$LoadItems['System']['dengi']) {//Если присланная валюта отличается от базовой
                $price=$price/$LoadItems['Valuta'][$baseinputvaluta]['kurs']; //Приводим цену в базовую валюту
            }
        } 

        if(isset($_SESSION['valuta'])) {
            $valuta=$_SESSION['valuta'];
        } else {
            $valuta=$LoadItems['System']['dengi'];
        } 
        $kurs=$LoadItems['Valuta'][$valuta]['kurs'];
        $admoption=unserialize($LoadItems['System']['admoption']);
        $format=$admoption['price_znak'];
        $price=$price*$kurs;
           
        // Если цены показывать только после аторизации
        if($admoption['user_price_activate']==1 and !$_SESSION['UsersId']) {
            $price="-";
        }
        
        $price=($price+(($price*$LoadItems['System']['percent'])/100));
        $price=number_format($price,$format,'.', ' ');
        $TDR[$igood][]=$price;
        $chars=unserialize($row['vendor_array']);
        
        if(is_array($sorts_name2))
            foreach ($sorts_name2 as $name=>$ids) {
                $curchar='';
                foreach ($ids as $id=>$true) {
                    @$ca=$chars[$id];
                    if(is_array($ca))
                        foreach($ca as $charid) {
                            $sql2='select name from '.$SysValue['base']['table_name21'].' where id='.intval($charid);
                            $result2=mysql_query($sql2);
                            @$row2 = mysql_fetch_array(@$result2);
                            $curchar.=' '.$row2['name'].'<BR>';
                        }
                }
                $TDR[$igood][]=$curchar;
            }
        $TDR[$igood][]=stripslashes($row['content']);
    }
    
    //троим таблицу по матрице
    $rows=count($TDR[0]);
    $cols=count($goodstowork)+1;
    $disp.='<TABLE class=sort_table cellpadding=3 width="95%">';
    
    for($row=0; $row<$rows; $row++) {
        $disp.='<TR>';
        for($col=0; $col<$cols; $col++) {
            $value=trim($TDR[$col][$row]);
            if (!$value) {
                $value='&nbsp;';
            }
            $disp.='<TD class=sort_table style="vertical-align:top;">'.$value.'</TD>';
        }
        $disp.='</TR>';
    }
    $disp.='</TABLE>';
}

//Если нет товаров, показать пусто. ДОЛЖНО БЫТЬ ПОСЛЕДНЕЙ СТРОКОЙ
if (count($cats)==0) {
    $disp='<P><h5>Вы не выбрали товары для сравнения!</h5></P>';
}

// Определяем переменые
$SysValue['other']['pageTitle']=$SysValue['other']['pageTitl']="Сравнение товаров";
$SysValue['other']['pageContent']= $disp;
$SysValue['other']['catalogCat']= "Сравнение товаров";
$SysValue['other']['catalogCategory']= "Выбраны товары для сравнения";
$SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['page_page_list']);

// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>