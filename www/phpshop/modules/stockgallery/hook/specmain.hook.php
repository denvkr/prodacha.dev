<?php

function specMain_stockgallery_hook($obj) {
    
    if(!empty($_SESSION['mod_stockgallery_enabled']) )
        return $obj->get('stockgallery');

}

$addHandler = array
    (
    'specMain' => 'specMain_stockgallery_hook'
);
?>