<?php

function updateAllPrice(){
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $price = ($price + (($price * $LoadItems['System']['percent']) / 100));
    $pr=$_POST['mod_sale_price']/100;
    
    if(empty($_POST['mod_sale_old'])) $_POST['mod_sale_old']='0';
    
    $PHPShopOrm->sql='update '.$GLOBALS['SysValue']['base']['products'].' set 
    price_n='.$_POST['mod_sale_old'].',
    price=price'.$_POST['mod_sale_opt'].'(price*'.$pr.') 
    where category='.intval($_POST['catalogID']);
    $PHPShopOrm->update();
}


function addFieldSale($data) {
    global $PHPShopGUI;
    
    // Добавляем значения в функцию actionStart
    $sel_value[]=array('+','+',false);
    $sel_value[]=array('-','-', 'selected');
    $sel=$PHPShopGUI->setSelect('mod_sale_opt', $sel_value, 40);
    $Tab9=$PHPShopGUI->setField('Новая цена',$PHPShopGUI->setInputText('Поменять цену у всего товара каталога на '.$sel, 'mod_sale_price', "10",'30','%'));
   
    $sel_value2[]=array('Обнулить',0, false);
    $sel_value2[]=array('Присвоить значение розничной цены до изменения','price','selected');
   
    $Tab9.=$PHPShopGUI->setField('Старая цена',$PHPShopGUI->setSelect('mod_sale_old', $sel_value2, 300));
    
    $PHPShopGUI->addTab(array("Распродажа",$Tab9,450));
}

$addHandler=array(
        'actionStart'=>'addFieldSale',
        'actionDelete'=>false,
        'actionUpdate'=>'updateAllPrice',
        'actionSave'=>false
);

?>