<?php

function subcatalog_countcat_hook_get_count($cat){
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $PHPShopOrm->debug=false;
    $action = $PHPShopOrm->select(array('COUNT(id) as count'),array('category' => '=' . intval($cat),'enabled'=>"='1'"),false,array('limit'=>1));
    return $action['count'];
}

function subcatalog_countcat_hook_set_count($cat,$count){
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
    $PHPShopOrm->debug=false;
    $PHPShopOrm->update(array('count_new'=>$count),array('id'=>'='.intval($cat)));
}

function subcatalog_countcat_hook($obj, $row) {
        if(empty($row['count'])){
            $row['count']=subcatalog_countcat_hook_get_count($row['id']);
            subcatalog_countcat_hook_set_count($row['id'],$row['count']);
        }
        
        $obj->set('catalogCount', $row['count']);
}

$addHandler = array
    (
    'subcatalog'=>'subcatalog_countcat_hook'
);
?>