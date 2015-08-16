<?php

class PHPShopProductListElement extends PHPShopElements {

    function PHPShopProductListElement() {
        $this->debug = false;
        $this->objBase = $GLOBALS['SysValue']['base']['productlist']['productlist_system'];
        $this->option();
        parent::PHPShopElements();
    }

    function option() {
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $this->data = $PHPShopOrm->select();
        if ($this->data['num'] < 1)
            $this->data['num'] = 1;
    }

    // Вывод
    function element($category) {
        $dis = null;
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name2']);
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('*'), array('category' => '=' . intval($category), 'enabled'=>"='1'",'parent_enabled'=>"='0'"), array('order' => 'datas'), array('limit' => $this->data['num']));
        if (is_array($data)) {
            foreach ($data as $row) {

                $this->set('productlist_product_id', $row['id']);
                $this->set('productlist_product_name', $row['name']);
                $this->set('productlist_product_pic_small', $row['pic_small']);
                $this->set('productlist_product_pic_big', $row['pic_big']);
                $this->set('productlist_product_price', $row['price']);
                $this->set('productlist_product_description', $row['description']);

                // Учет модуля SEOURL
                if (!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system'])) {
                    $this->set('productlist_product_seo', '_' . PHPShopString::toLatin($row['name']));
                }else
                    $this->set('productlist_product_seo', null);

                $dis.= parseTemplateReturn($GLOBALS['SysValue']['templates']['productlist']['productlist_product'], true);
            }


            // Назначаем переменную шаблона
            switch ($this->data['enabled']) {

                case 1:
                    $this->set('leftMenuName', $this->data['title']);
                    $this->set('leftMenuContent', $dis);
                    $this->set('leftMenu', parseTemplateReturn("main/left_menu.tpl"), true);
                    break;

                case 2:
                    $this->set('rightMenuName', $this->data['title']);
                    $this->set('rightMenuContent', $dis);
                    $this->set('rightMenu', parseTemplateReturn("main/left_menu.tpl"), true);
                    break;

                default: $this->set('productlist', $dis);
                    
            }

        }
    }

}

function uid_productlist_hook($obj, $row, $rout) {
    if ($rout == 'MIDDLE') {

        $PHPShopProductListElement = new PHPShopProductListElement();
        $PHPShopProductListElement->element($row['category']);
    }
}

$addHandler = array
    (
    'UID' => 'uid_productlist_hook'
);
?>