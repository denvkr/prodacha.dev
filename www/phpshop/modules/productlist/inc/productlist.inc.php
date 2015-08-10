<?php
/**
 * Версия для 3.1 - 3.5 
 */
PHPShopObj::loadClass('nav');
PHPShopObj::loadClass('orm');


$PHPShopNav = new PHPShopNav();

class PHPShopProductListElement {

    function PHPShopProductListElement() {
	global $PHPShopNav;
        $this->debug = false;
        $this->objBase = $GLOBALS['SysValue']['base']['productlist']['productlist_system'];
        $this->PHPShopNav=$PHPShopNav;
	$this->option();
        $this->element();
    }

    function option() {
		$PHPShopOrm = new PHPShopOrm($this->objBase);
        $this->data = $PHPShopOrm->select();
        if ($this->data['num'] < 1)
            $this->data['num'] = 1;
    }
    
    function getCategoryId(){
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name2']);
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('category'),array('id'=>'='.$this->PHPShopNav->getId()));
        return $data['category'];
    }

    // Вывод корзины
    function element() {
        $dis = null;
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name2']);
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('*'), array('category' => '=' . $this->getCategoryId()), array('order' => 'datas'), array('limit' => $this->data['num']));
        if (is_array($data)) {
            foreach ($data as $row) {

                $dis.='<div>
        <a href="/shop/UID_'.$row['id'].'.html" title="'.$row['name'].'">
           '.$row['name'].'
        </a>
</div>';
            }

            $GLOBALS[SysValue][other][leftMenuName]=$this->data['title'];
            $GLOBALS[SysValue][other][leftMenuContent]= $dis;
            $menu=parseTemplateReturn("main/left_menu.tpl");

            $GLOBALS[SysValue][other][leftMenuProduct]= $menu;
        }
    }

}

if ($PHPShopNav->getNav() == 'UID') {
    new PHPShopProductListElement();
}
?>