<?php

PHPShopObj::loadClass('string');

class AddStockGallery extends PHPShopElements {

    function AddStockGallery() {
        parent::PHPShopElements();
    }

    function stockgallery() {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['stockgallery']['stockgallery_system']);
        $option = $PHPShopOrm->select();
        $_SESSION['mod_stockgallery_enabled'] = $option['enabled'];

        $li = null;

        $this->set('stockgallery_width', $option['width']);
        $this->set('stockgallery_img_height', $option['img_height']);
        $this->set('stockgallery_img_width', $option['img_width']);
        $this->set('stockgallery_border', $option['border']);
        $this->set('stockgallery_border_color', $option['border_color']);

        $where['enabled'] = "='1'";
        $where['parent_enabled'] = "='0'";
        $where['sklad'] = "!='1'";

        // Выборка
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $option['limit']));
        if (is_array($data))
            foreach ($data as $row) {

                // Подгон по высоте
                if (empty($option['img_height'])) {
                    $height = null;
                    $this->set('stockgallery_img_height', 100);
                } else {
                    $height = ' height="' . $option['img_height'] . '"';
                }

                // Учет модуля SEOURL
                if (!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system'])) {
                    $seourl='_' . PHPShopString::toLatin($row['name']);
                }
                else $seourl=null;

                if (!empty($row['pic_small']))
                    $li.='<li><a href="/shop/UID_' . $row['id'] .$seourl.'.html"><img width="' . $option['img_width'] . '" ' . $height . ' alt="' . $row['name'] . '" title="' . $row['name'] . '" src="' . $row['pic_small'] . '" border="0"></a></li>';
            }

        $this->set('stockgallery_list', $li);
        $dis = parseTemplateReturn($GLOBALS['SysValue']['templates']['stockgallery']['stockgallery_forma'], true);
        return $dis;
    }

}

if (class_exists('phpshopelements')) {
    $StockGallery = new AddStockGallery();
    $StockGallery->init('stockgallery');
}
?>