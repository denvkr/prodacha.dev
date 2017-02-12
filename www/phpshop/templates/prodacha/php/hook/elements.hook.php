<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//функция фильтрации меню в зависимости от региона
function topMenuFilter($obj,$row,$rout){
    $filter=0;
    if (($_COOKIE['sincity']=='m' || $_COOKIE['sincity']=='other') &&
            strtolower($row['link'])==='video') {
            // Определяем переменные
            $obj->set('topMenuName', '');
            $obj->set('topMenuLink', '');
            $filter=1;
    }
    return $filter;
    
}
        
$addHandler=array
(
		'topMenu'=>'topMenuFilter'
);
