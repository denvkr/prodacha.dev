<?php
/**
 * Элемент побора по параметрам
 */

class AddToTemplateSortProductElement extends PHPShopElements {
    var $debug=false;

    /**
     * Конструктор
     */
    function AddToTemplateSortProductElement() {
        parent::PHPShopElements();
        $this->option();

        // Учет модуля SEOURL
        if(!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system'])) {
            PHPShopObj::loadClass('string');
            $this->option['seourl_enabled']=true;
        }
        else $this->option['seourl_enabled']=false;
    }

    /**
     * Настройки
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sortproduct']['sortproduct_system']);
        $PHPShopOrm->debug=$this->debug;
        $this->option=$PHPShopOrm->select();

    }

    /**
     * Вывод товаров
     */
    function product($where,$limit) {
        $forma=null;
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $PHPShopOrm->debug=$this->debug;
        $data = $PHPShopOrm->select(array('id,name'),$where,array('order'=>'num'),array('limit'=>$limit));
        if(is_array($data))
            foreach($data as $row) {

                // Учет модуля SEOURL
                if(!empty($this->option['seourl_enabled'])) $seo='_'.PHPShopString::toLatin($row['name']);
                else $seo=null;

                $link=PHPShopText::a($this->getValue('dir.dir').'/shop/UID_'.$row['id'].$seo.'.html',$row['name'],$row['name'],false,false,false,'sortproduct');
                $this->set('sortproduct_value',$link);
                $forma.=parseTemplateReturn($GLOBALS['SysValue']['templates']['sortproduct']['sortproduct_links'],true);
            }
        return $forma;
    }

    /**
     * Вывод списка характеристики для отбора
     */
    function display() {
        $forma=null;

        // Список характеристик для вывода
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sortproduct']['sortproduct_forms']);
        $PHPShopOrm->debug=$this->debug;
        $data = $PHPShopOrm->select(array('*'),array('enabled'=>"='1'"),array('order'=>'num'),array('limit'=>100));
        if(is_array($data))
            foreach($data as $row) {
                $hash=$row['sort'].'-'.$row['value_id'];
                $where=array('vendor'=>" REGEXP 'i".$hash."i'");

                $this->set('sortproduct_title',$row['value_name']);
                $this->set('sortproduct_value_list',$this->product($where,$row['items']));
                $forma.=parseTemplateReturn($GLOBALS['SysValue']['templates']['sortproduct']['sortproduct_forma'],true);


            }

        $this->set('leftMenuContent',$forma);
        $this->set('leftMenuName',$this->option['title']);

        // Подключаем шаблон
        $dis=$this->parseTemplate($this->getValue('templates.left_menu'));


        // Назначаем переменную шаблона
        switch($this->option['enabled']) {

            case 1:
                $this->set('leftMenu',$dis,true);
                break;

            case 2:
                $this->set('rightMenu',$dis,true);
                break;

            default: $this->set('brandproduct',$dis);
        }
    }


}


// Добавляем в шаблон элемент
$AddToTemplateSortProductElement = new AddToTemplateSortProductElement();
$AddToTemplateSortProductElement->display();
?>