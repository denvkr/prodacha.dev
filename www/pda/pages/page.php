<?

include_once './phpshop/inc/page.inc.php';

if($SysValue['nav']['nav']=="CID"){

 if($SysValue['nav']['id'] == "00")
   header("Location: ../map/");


 $SysValue['other']['thisCat'] = $LoadItems['CatalogPage'][$SysValue['nav']['id']]['parent_to'];

  // Проверка вложенности
  $podcatalog_id = array_keys($LoadItems['CatalogPageKeys'],$SysValue['nav']['id']);
  if(count($podcatalog_id)>0)
     $SysValue['other']['DispShop']=DispCatalogPageTree($SysValue['nav']['id']);
    else $SysValue['other']['DispShop']=DispListPage($SysValue['nav']['id']);
 
  }
    else{
	  $SysValue['other']['DispShop']=DispContentPage($SysValue['nav']['name']);
	  }


if($SysValue['other']['DispShop'] == 404){
header("HTTP/1.0 404 Not Found");
header("Status: 404 Not Found");
include("pages/error.php");
}
	  
// Подключаем шаблон
$SysValue['other']['NavActive']=$SysValue['nav']['name'];
@ParseTemplate($SysValue['templates']['index']);
?>