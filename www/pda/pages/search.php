<?
// Определяем переменые
$SysValue['other']['DispShop']=DisSearch(@$words,$cat);


// Подключаем шаблон 
ParseTemplate($SysValue['templates']['index']);
?>