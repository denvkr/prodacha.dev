<?php
/**
 * Родительский класс ядра вывода товаров
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopClass
 */
class PHPShopShopCore extends PHPShopCore {

    /**
     * Фото-заглушка при отсутствии фото товара
     * @var string
     */
    var $no_photo='images/shop/no_photo.gif';
    /**
     * Отладка
     * @var bool
     */
    var $debug=false;
    /**
     * Кэширование, рекомендуется [true]
     * @var bool
     */
    var $cache=true;
    /**
     * Форматирование элементов кэша
     * @var array
     */
    var $cache_format=array('content','yml_bid_array');
    /**
     * Генерация рамок в сетки товаров
     * @var bool
     */
    var $grid=true;
    /**
     * Лимит вывода записей на 1 странице, рекомендуется 100-300
     * @var int
     */
    var $max_item=100;
    /**
     * Память параметов выполнения функций и модулей. При проектировании модулей и хуков следует отключить память [false]
     * @var bool
     */
    var $memory=true;


    /**
     * Конструктор
     */
    function PHPShopShopCore() {
        global $PHPShopValutaArray;

        // Имя Бд
        $this->objBase=$GLOBALS['SysValue']['base']['products'];

        // Массив валют
        $this->Valuta=$PHPShopValutaArray->getArray();

        PHPShopObj::loadClass('product');
        parent::PHPShopCore();

        // Валюта товара
        $this->dengi=$this->PHPShopSystem->getParam('dengi');
    }


    /**
     * Генерация SQL запроса для выборки
     * @param string $where параметр отбора
     * @return mixed
     */
    function query_filter($where=false) {
        global $SysValue;

        if(!empty($where)) $where.=' and ';

        $sort=null;

        $this->set('productRriceOT',0);
        $this->set('productRriceDO',0);

        $v=$SysValue['nav']['query']['v'];
        $s=PHPShopSecurity::TotalClean($SysValue['nav']['query']['s'],1);
        $f=PHPShopSecurity::TotalClean($SysValue['nav']['query']['f'],1);

        if($this->PHPShopNav->isPageAll())
            $p=PHPShopSecurity::TotalClean($p,1);

        // Сортировка по характеристикам
        if(is_array($v)) {
            foreach($v as $key=>$value) {
                if(PHPShopSecurity::true_num($key) and PHPShopSecurity::true_num($value)) {
                    $hash=$key."-".$value;
                    $sort.=" and vendor REGEXP 'i".$hash."i' ";
                }
            }
        }

        
        // Накрутка
        $percent=$this->PHPShopSystem->getValue('percent');

        // Сортировка принудительная пользователем
        switch($f) {
            case(1): $order_direction.="";
                $this->set('productSortNext',2);
                $this->set('productSortImg',1);
                $this->set('productSortT',1);
                break;
            case(2): $order_direction.=" desc";
                $this->set('productSortNext',1);
                $this->set('productSortImg',2);
                $this->set('productSortT',2);
                break;
            default: $order_direction.="";
                $this->set('productSortNext',2);
                $this->set('productSortImg',1);
                $this->set('productSortT',1);
                break;
        }
        switch($s) {
            case(1): $order=array('order'=>'name'.$order_direction);
                $this->set('productSortA','sortActiv');
                break;
            case(2): $order=array('order'=>'price'.$order_direction);
                $this->set('productSortB','sortActiv');
                break;
            case(3): $order=array('order'=>'num'.$order_direction);
                $this->set('productSortC','sortActiv');
                break;
            default:
                $order=array('order'=>'num'.$order_direction);
                $this->set('productSortC','sortActiv');
                break;
        }

        // Преобразуем массив условия сортировки в строку
        foreach($order as $key=>$val)
            $string=$key.' by '.$val;

        // Все страницы
        if($this->PHPShopNav->isPageAll()) {
            $sql="select * from ".$this->getValue('base.products')." where (".$where." enabled='1' and parent_enabled='0') ".$sort." ".$string.' limit '.$obj->max_item;
        }

        // Поиск по цене
        elseif(isset($_POST['priceSearch']) or !empty($sort)) {

            if(!empty($_POST['priceOT']) or !empty($_POST['priceDO'])) {
                $priceOT=TotalClean($_POST['priceOT'],1);
                $priceDO=TotalClean($_POST['priceDO'],1);

                $this->set('productRriceOT',$priceOT);
                $this->set('productRriceDO',$priceDO);

                // Бесконечность
                if($priceDO==0) $priceDO=1000000000;

                if(empty($priceOT)) $priceOT=0;

                // Цена с учетом выбранной валюты
                $priceOT/=$this->currency('kurs');
                $priceDO/=$this->currency('kurs');

                // Условие поиска по цене
                $price_sort="and price >= ".($priceOT/(100+$percent)*100)." AND price <= ".($priceDO/(100+$percent)*100);
            }

            $sql="select * from ".$this->getValue('base.products')." where ".$where." enabled='1' and parent_enabled='0' ".$price_sort." ".$sort.$string.' limit 0,'.$this->max_item;
        }
        else {
            // Возвращаем массив параметра сортировки результата
            return $order;
        }

        // Возвращаем SQL сложный запрос
        return $sql;
    }

    /**
     * Валюта
     * @return string
     */
    function currency($name='code') {

        if(isset($_SESSION['valuta'])) $currency=$_SESSION['valuta'];
        else $currency=$this->dengi;

        $row=$this->select(array($name), array('id'=>'='.$currency),false,array('limit'=>1),
                __FUNCTION__,array('base'=>$this->getValue('base.currency'),'cache'=>'true'));

        return $row[$name];
    }


    /**
     * Выборка из БД
     * @param array $select массив условий выборки
     * @param array $where массив условий выборки
     * @param array $order массив условий выборки
     * @param array $option массив условий выборки
     * @param string $function_name имя функции для отладки
     * @param array $from массив опций
     * @return array
     */
    function select($select, $where, $order=false, $option=array('limit'=>1),$function_name=false,$from=false) {

        if(is_array($from)) {
            $base=$from['base'];
            $cache=$from['cache'];
            $cache_format=$from['cache_format'];
        }
        else {
            $base=$this->objBase;
            $cache=$this->cache;
            $cache_format=$this->cache_format;
        }

        $PHPShopOrm = new PHPShopOrm($base);
        $PHPShopOrm->objBase=$base;
        $PHPShopOrm->debug=$this->debug;
        $PHPShopOrm->cache=$cache;
        $PHPShopOrm->cache_format=$cache_format;
        $result=$PHPShopOrm->select($select, $where, $order, $option,__CLASS__,$function_name);

        return $result;
    }


    /**
     * Стоимость товара
     * @param array $row массив данных товара
     * @param bool $newprice изменилась цена
     * @return float
     */
    function price($row,$newprice=false) {

        // Перехват модуля, занесение в память наличия модуля для оптимизации
        if($this->memory_get(__CLASS__.'.'.__FUNCTION__,true)) {
            $hook = $this->setHook(__CLASS__,__FUNCTION__,$row);
            if($hook) {
                return $hook;
            } else $this->memory_set(__CLASS__.'.'.__FUNCTION__,0);
        }

        // Если есть новая цена
        if(empty($newprice)) $price=$row['price'];
        else $price=$row['price_n'];

        return PHPShopProductFunction::GetPriceValuta($row['id'],array($price,$row['price2'],$row['price3'],$row['price4'],$row['price5']),$row['baseinputvaluta']);
    }


    /**
     * Генерация пагинатора
     * @param int $count количество товаров на странице
     * @param string $sql SQL запрос в виде строки для сложных выборок (применение AND и OR в одном условии, начмная от WHERE)
     */
    function setPaginator($count,$sql=null) {

        // Перехват модуля в начале функции
        if($this->setHook(__CLASS__,__FUNCTION__, $count,'START'))
            return true;

        // Кол-во данных
        $this->count=$count;
        $SQL=null;
        $delim=' | ';

        // Выборка по параметрам WHERE
        $nWhere=1;
        if(is_array($this->where)) {
            foreach($this->where as $pole=>$value) {
                $SQL.=$pole.$value;
                if($nWhere<count($this->where)) $SQL.=$this->PHPShopOrm->Option['where'];
                $nWhere++;
            }
        }
        else $SQL=$sql;

        // Сортировка
        $sort='?';
        if(is_numeric($_GET['s'])) $sort.='s='.$_GET['s'].'&';
        if(is_numeric($_GET['f'])) $sort.='f='.$_GET['f'].'&';


        // Фильтры
        if(is_array($_GET['v']))
            foreach($_GET['v'] as $key=>$val) {
                if(is_numeric($key) and is_numeric($val))
                    $sort.='v['.$key.']='.$val.'&';
            }

        $sort=substr($sort,0,strlen($sort)-1);

        // Всего страниц
        $this->PHPShopOrm->comment=__CLASS__.'.'.__FUNCTION__;
        $result=$this->PHPShopOrm->query("select COUNT('id') as count from ".$this->objBase. ' where '.$SQL);
        $row = mysql_fetch_array($result);
        $this->num_page=$row['count'];

        $i=1;
        $navigat=$delim;
        $num=round(($this->num_page/$this->num_row)+0.4);


        if($num>1) {
            if($this->page>=$num) {
                $p_to=$i-1;
                $p_do=$this->page-1;
            }else {
                $p_to=$this->page+1;
                $p_do=1;
            }

            while ($i<=$num) {
                if($i>1) {
                    $p_start=$this->num_row*($i-1);
                    $p_end=$p_start+$this->num_row;
                }
                else {
                    $p_start=$i;
                    $p_end=$this->num_row;
                }
                if($i!=$this->page) {
                    if($i==1)
                        $navigat.=PHPShopText::a(substr($this->objPath,0,strlen($this->objPath)-1).'.html'.$sort,$p_start.'-'.$p_end).$delim;
                    else {
                        if($i>($this->page-$this->nav_len) and $i<($this->page+$this->nav_len))
                            $navigat.=PHPShopText::a($this->objPath.$i.'.html'.$sort,$p_start.'-'.$p_end).$delim;
                        else if($i-($this->page+$this->nav_len)<3 and (($this->page-$this->nav_len)-$i)<3) $navigat.=".";
                    }

                }
                else $navigat.=PHPShopText::b($p_start.'-'.$p_end).$delim;
                $i++;
            }

            $nav=$this->getValue('lang.page_now').': ';

            // Убираем дубль первой страницы CID_X_1.html
            if($p_do==1)
                $nav.=PHPShopText::a(substr($this->objPath,0,strlen($this->objPath)-1).'.html'.$sort,PHPShopText::img('images/shop/3.gif',0,'absmiddle'),'&laquo; '.$this->lang('nav_back'));
            else
                $nav.=PHPShopText::a($this->objPath.($p_do).'.html'.$sort,PHPShopText::img('images/shop/3.gif',0,'absmiddle'),'&laquo; '.$this->lang('nav_back'));

            $nav.='  '.$navigat.'  ';

            // Убираем дубль первой страницы CID_X_0.html
            if($p_to==0)
                $nav.=PHPShopText::a(substr($this->objPath,0,strlen($this->objPath)-1).'.html'.$sort,PHPShopText::img('images/shop/4.gif',0,'absmiddle'),$this->lang('nav_forw').' &raquo;');
            else
                $nav.=PHPShopText::a($this->objPath.($p_to).'.html'.$sort,PHPShopText::img('images/shop/4.gif',0,'absmiddle'),$this->lang('nav_forw').' &raquo;');

            // Добавлем ссылку показать все
            if(strtoupper($this->page) == 'ALL')
                $nav.=PHPShopText::nbsp(2).PHPShopText::b(__('Все позиции'));
            else $nav.=PHPShopText::nbsp(2).PHPShopText::a($this->objPath.'ALL.html',__('Все позиции'));

            // Назначаем переменную шаблонизатора
            $this->set('productPageNav',$nav);

            // Перехват модуля в конце функции
            $this->setHook(__CLASS__,__FUNCTION__, $nav, 'END');
        }
    }

    /**
     * Проверка режима Multibase
     */
    function checkMultibase($pic_small) {

        $base_host = $this->PHPShopSystem->getSerilizeParam('admoption.base_host');
        if($this->PHPShopSystem->getSerilizeParam('admoption.base_enabled') == 1 and !empty($base_host))
            $this->set('productImg',eregi_replace("/UserFiles/","http://". $base_host."/UserFiles/",$pic_small));
    }

    /**
     * Проверка дополнительных данных товара по складу
     * @param array $row масив данных по товару
     */
    function checkStore($row=array()) {

        // Единица измерения
        if(empty($row['ed_izm'])) $row['ed_izm']=$this->lang('product_on_sklad_i');
        $this->set('productEdIzm',$row['ed_izm']);

        // Показывать состояние склада
        if($this->PHPShopSystem->getSerilizeParam('admoption.sklad_enabled') == 1 and $row['items'] > 0)
            $this->set('productSklad',$this->lang('product_on_sklad')." ".$row['items']." ".$row['ed_izm']);
        else $this->set('productSklad','');




        // Если товар на складе
        if(empty($row['sklad'])) {

            $this->set('Notice','');
            $this->set('ComStartCart','');
            $this->set('ComEndCart','');
            $this->set('ComStartNotice','<!--');
            $this->set('ComEndNotice','-->');

            // Если нет новой цены
            if(empty($row['price_n'])) {
                $this->set('productPrice',$this->price($row));
                $this->set('productPriceRub','');
            }

            // Если есть новая цена
            else {
                $productPrice=$this->price($row);
                $productPriceNew=$this->price($row,true);
                $this->set('productPrice',$productPrice);
                $this->set('productPriceRub',PHPShopText::strike($productPriceNew));
            }
        }

        // Товар под заказ
        else {
            $this->set('productPrice',$this->price($row));
            $this->set('productPriceRub',$this->lang('sklad_mesage'));
            $this->set('ComStartNotice','');
            $this->set('ComEndNotice','');
            $this->set('ComStartCart',PHPShopText::comment('<'));
            $this->set('ComEndCart',PHPShopText::comment('>'));
            $this->set('productNotice',$this->lang('product_notice'));
        }

        // Если цены показывать только после аторизации
        if($this->PHPShopSystem->getSerilizeParam('admoption.user_price_activate') == 1 and empty($_SESSION['UsersId'])) {
            $this->set('ComStartCart',PHPShopText::comment('<'));
            $this->set('ComEndCart',PHPShopText::comment('>'));
            $this->set('productPrice',PHPShopText::comment('<'));
            $this->set('productValutaName',PHPShopText::comment('>'));
        }


        // Перехват модуля, занесение в память наличия модуля для оптимизации
        if($this->memory_get(__CLASS__.'.'.__FUNCTION__,true)) {
            $hook=$this->setHook(__CLASS__,__FUNCTION__,$row);
            if($hook) {
                return $hook;
            } else $this->memory_set(__CLASS__.'.'.__FUNCTION__,0);
        }

    }

    /**
     * Форма ячеек с товарами
     * @return string
     */
    function setCell() {

        // Оформление разделителя ячеек
        if($this->grid) $this->grid_style='class="setka"';
        else $this->grid_style='';

        $Arg=func_get_args();
        $item=1;
        $panel=array('panel_l','panel_r','panel_l','panel_r');
        $tr='<tr>';

        foreach($Arg as $key=>$value)
            if($key<$this->cell) $args[]=$value;

        $num=count($args);

        if(is_array($args))
            foreach($args as $key=>$val) {
                $tr.='<td class="'.$panel[$key].'" valign="top">'.$val.'</td>';

                if($item<$num and $num == $this->cell)
                    $tr.='<td '.$this->grid_style.'><img src="images/spacer.gif"  width="1" alt="" /></td>';

                $item++;
            }
        $tr.='</tr>';

        if(!empty($this->setka_footer))
            $tr.='<tr><td '.$this->grid_style.' colspan="'.($this->cell*2).'" height="1"><img height="1" src="images/spacer.gif" alt="" /></td></tr>';

        return $tr;
    }


    /**
     * Генератор сетки товаров
     * @param array $dataArray массив данных
     * @param int $cell разрадя сетки [1-5]
     * @return string
     */
    function product_grid($dataArray,$cell=2) {

        if(empty($cell)) $cell=2;
        $this->cell=$cell;
        $this->setka_footer=true;

        $table=null;
        $j=1;
        $item=1;
        $lastmodified=0;

        // Локализация
        $this->set('productSale',$this->lang('product_sale'));
        $this->set('productInfo',$this->lang('product_info'));
        $this->set('productPriceMoney',$this->dengi);
        $this->set('catalog',$this->lang('catalog'));
        $this->set('productPageThis',$this->PHPShopNav->getPage());

        $d1=$d2=$d3=$d4=$d5=null;
        if(is_array($dataArray)) {
            $total=count($dataArray);
            foreach($dataArray as $row) {

                // Название
                $this->set('productName',$row['name']);

                // Артикул
                $this->set('productArt',$row['uid']);

                // Краткое описание
                $this->set('productDes',$row['description']);

                // Вес
                $this->set('productWeight',$row['weight']);

                // Максимальная дата изменения
                if($row['datas'] > $lastmodified)
                    $lastmodified=$row['datas'];

                // Пустая картинка
                if(empty($row['pic_small']))
                    $this->set('productImg',$this->no_photo);
                else $this->set('productImg',$row['pic_small']);

                // Проверка режима Multibase
                $this->checkMultibase($row['pic_small']);

                // Большая картинка
                $this->set('productImgBigFoto',$row['pic_big']);

                // Ид товара
                $this->set('productUid',$row['id']);

                // Опции склада
                $this->checkStore($row);

                // Перехват модуля
                $this->setHook(__CLASS__,__FUNCTION__, $row);

                // Подключаем шаблон ячейки товара
                $dis=ParseTemplateReturn($this->getValue('templates.main_product_forma_'.$this->cell));

                // Убераем последний разделитель в сетке
                if($item == $total)
                    $this->setka_footer=false;

                $cell_name='d'.$j;
                $$cell_name=$dis;

                if($j==$this->cell) {
                    $table.=$this->setCell($d1,$d2,$d3,$d4,$d5);
                    $d1=$d2=$d3=$d4=$d5=null;
                    $j=0;

                }
                elseif($item==$total) {
                    $table.=$this->setCell($d1,$d2,$d3,$d4,$d5);
                }

                $j++;
                $item++;

            }
        }

        $this->lastmodified=$lastmodified;
        return $table;
    }
}
?>