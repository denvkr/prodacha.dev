<?
@$SysValue['other']['pageTitl']= "Ошибка 404";

header("HTTP/1.0 404 Not Found");
header("Status: 404 Not Found");

// Определяем переменые
$SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['error_page_forma']);
  
// Подключаем шаблон 
//@ParseTemplate($SysValue['templates']['shop']);
?>
	