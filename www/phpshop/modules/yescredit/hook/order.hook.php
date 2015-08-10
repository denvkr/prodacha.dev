<?php



function payment_yescredit_hook($obj,$value) {
    global $PHPShopModules;
    $js='<script>
function yescredit_check(v){
if(v==25)
  document.getElementById(\'yescredit_option\').style.display="block";
  else
document.getElementById(\'yescredit_option\').style.display="none";
}
</script>';
    $term_value=array();
    $term_value[]=array('6',6,'selected');
    $term_value[]=array('10',10,'');
    $term_value[]=array('12',12,'');
    $term_value[]=array('24',24,'');
    $term_value[]=array('30',30,'');
    $term_value[]=array('36',36,'');

    $region_value=array();
    $region_value[]=array('Москва','Москва','selected');
    $region_value[]=array('Московская область','Московская область','');
    $region_value[]=array('Санкт-Петербург','Санкт-Петербург','');
    $region_value[]=array('Ленинградская область','Ленинградская область','');
    //$region_value[]=array('Казань','Казань','');
    //$region_value[]=array('Татарстан','Татарстан','');

    $start_summ_value=array();
    $start_summ_value[]=array('10%',10,'selected');
    $start_summ_value[]=array('20%',20,'');

    $dop=PHPShopText::p(PHPShopText::select('yescredit_term',$term_value,50,"none",'Срок кредита:'));
    $dop.=PHPShopText::p(PHPShopText::select('yescredit_region',$region_value,200,"none",'Регион регистрации:'));
    $dop.=PHPShopText::p(PHPShopText::select('yescredit_start_summ',$start_summ_value,50,"none",'Первоначальный взнос:'));

    if($PHPShopModules->checkKeyBase('yescredit'))
        return false;
    
    // Настройки модуля
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['yescredit']['yescredit_system']);
    $option=$PHPShopOrm->select();

    $yescredit_option_display=null;

    // Если кредит первый в списке
    if(is_array($value)) {
        if($value[0][1] == $option['payment_id'])
            $yescredit_option_display='block';
        else $yescredit_option_display='none';
    }

    $dop=PHPShopText::div($dop,"left",'padding:5px;display:'.$yescredit_option_display,'yescredit_option');
    $obj->set('orderOplata',PHPShopText::select('order_metod',$value,250,"none",false,'yescredit_check(this.value)').$js.$dop);

    return true;

}

$addHandler=array
        (
        'payment'=>'payment_yescredit_hook'
);

// Запрещаем функции повторное выполнение
$setHandlerDoneMemory = array
    (
    'payment_yescredit_hook' => 'true'
);

?>