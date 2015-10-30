<?php
//setlocale(LC_ALL, "Russian_Russia.1251");
/**
 * Обработчик карты сайта
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopMap
 * @version 1.6
 * @package PHPShopCore
 */
class PHPShopCatalog extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopCatalog() {

        $this->debug = false;
        $this->memory = true;

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['products'];
        parent::PHPShopCore();
    }

    /**
     * Вид ссылки
     * @param int $val ИД каталога
     * @param string $dir параметр назначения [category|category_page]
     * @return string 
     */
    function seourl($val, $dir, $name = false) {

        // Перехват модуля, занесение в память наличия модуля для оптимизации цикличности
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $hook = $this->setHook(__CLASS__, __FUNCTION__, array('val' => $val, 'dir' => $dir, 'name' => $name));
            if ($hook) {
                return $hook;
            } else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }

        switch ($dir) {
            case 'category':
                $link = '/shop/CID_' . $val . '.html';
                break;

            case 'category_page':
                $link = '/page/CID_' . $val . '.html';
                break;
        }

        return $link;
    }

    /**
     * Подкатегории товаров
     * @param int $cat ID категории
     * @return string
     */
    function subcategory($cat) {
        $dis = null;

        if (!empty($this->ParentArray[$cat]) and is_array($this->ParentArray[$cat])) {

            foreach ($this->ParentArray[$cat] as $val) {
                $sup = $this->subcategory($val);
                $name = $this->PHPShopCategoryArray->getParam($val . '.name');

                if (empty($sup)) {
                    $dis.=PHPShopText::li($name, $this->seourl($val, 'category', $name));
                } else {
                    $dis.=PHPShopText::li($name, $this->seourl($val, 'category', $name));//PHPShopText::b($name)
                    $dis.=$sup;
                }
            }
            return PHPShopText::ul($dis);
        }
    }

    /**
     * Категории товаров
     */
    function category() {

        // Мультибаза
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
        }
        else $where=null;

        $this->PHPShopCategoryArray = new PHPShopCategoryArray($where);
        $this->ParentArray = $this->PHPShopCategoryArray->getKey('parent_to.id', true);
        $dis = $sup = null;
        if (is_array($this->ParentArray[0])) {
            foreach ($this->ParentArray[0] as $val) {
                $vid = $this->PHPShopCategoryArray->getParam($val . '.skin_enabled');
                if (empty($vid)) {
                    $name = $this->PHPShopCategoryArray->getParam($val . '.name');
                    $sup = $this->subcategory($val);
                    if (!empty($sup)){

                        $dis.=PHPShopText::p(PHPShopText::a($this->seourl($val, 'category', $name), PHPShopText::b($name), $name) . $sup);//PHPShopText::b($name)
                    }
                    else
                        $dis.=PHPShopText::p(PHPShopText::a($this->seourl($val, 'category', $name), PHPShopText::b($name), $name));
                }
            }
        }
        $this->add($dis, true);
    }

    /**
     * Подкатегории страниц
     * @param int $cat ID категории
     * @return string
     */
    function subcategory_page($cat) {
        $dis = null;
        if (!empty($this->ParentPageArray[$cat]) and is_array($this->ParentPageArray[$cat])) {
            foreach ($this->ParentPageArray[$cat] as $val) {
                $vid = $this->PHPShopCategoryArray->getParam($val . '.vid');
                                //if (strtolower($vid)!='новости') {
                                //echo '---'.strtolower($vid).'---';    
                               //}
                if (empty($vid)) {
                    $sup = $this->subcategory_page($val);
                    $name = $this->PHPShopPageCategoryArray->getParam($val . '.name');
                    if (empty($sup)) {
                        $dis.=PHPShopText::li($name, $this->seourl($val, 'category_page', $name));
                    } else {
                        $dis.=PHPShopText::li(PHPShopText::b($name));
                        $dis.=$sup;
                    }
                }
            }
            return PHPShopText::ul($dis);
        }
    }

    /**
     * Категории страниц
     */
    function category_page() {
        PHPShopObj::loadClass('page');
        $this->PHPShopPageCategoryArray = new PHPShopPageCategoryArray();
        $this->ParentPageArray = $this->PHPShopPageCategoryArray->getKey('parent_to.id', true);
        $dis = null;
        if (is_array($this->ParentPageArray[0])) {
            foreach ($this->ParentPageArray[0] as $val) {
                $sup = $this->subcategory_page($val);
                $name = $this->PHPShopPageCategoryArray->getParam($val . '.name');
                //echo strtolower($name).' '.iconv("windows-1251", "windows-1251//IGNORE", 'подсказки :)');
                if (strtolower($name)!='Подсказки :)' && strtolower($name)!='Новости') {
                    if (empty($sup)) {
                        $dis.=PHPShopText::p(PHPShopText::a($this->seourl($val, 'category_page', $name), PHPShopText::b($name), $name));
                    } else {
                        $dis.=PHPShopText::b($name);
                        $dis.=$sup;
                    }                   
                } 
            }
        }
        $this->add(PHPShopText::ul($dis), true);
    }

    /**
     * Акции
     */
    function special() {
        //$special = PHPShopText::ul(PHPShopText::li(__('Новинки'), '/newtip/') . PHPShopText::li(__('Спецпредложение'), '/spec/') .
        //                PHPShopText::li(__('Распродажа'), '/newprice/'));
        //$special = PHPShopText::li(__('Спецпредложение'), '/spec/');
        $special = PHPShopText::p(PHPShopText::a('/spec/', PHPShopText::b('Спецпредложение'), 'Спецпредложение'));
        //$this->add(PHPShopText::b(__('Акции')) . $special, true);
        $this->add($special, true);        
    }

    /**
     * Новости
     */
    function news() {
        $this->add(PHPShopText::b(PHPShopText::a('/news/', __('Новости'))), true);
    }

    /**
     * Кол-во товаров
     * @return int
     */
    function product() {
        $data = $this->PHPShopOrm->select(array('COUNT(id) as total'));
        if (is_array($data))
            $total = $data['total'];
        else
            $total = 0;

        return $total;
    }

    /**
     * Экшен
     */
    function index() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;


        // Категории товаров
        $this->category();

        // Страницы
        $this->category_page();

        // Акции
        $this->special();

        // Новости
        //$this->news();

        // Мета
        $this->title = $this->lang('catalogprod') . " - " . $this->PHPShopSystem->getParam('title');
        $this->set('catalogCategory', $this->lang('catalogprod'));
        $this->set('catalFound', $this->lang('found_of_catalogs'));
        $this->set('catalNum', $this->PHPShopCategoryArray->getNum());
        $this->set('producFound', $this->lang('found_of_products'));
        $this->set('productNum', $this->product());

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, false, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.catalog_page_list'));
    }

}

?>