<?

if(@$_POST['news_plus']=="ok"){
	  // Определяем переменые
$SysValue['other']['orderMesage']=News_write(@$mail);
$disp=ParseTemplateReturn($SysValue['templates']['news_forma_mesage_main']);
}
elseif($SysValue['nav']['nav']=="ID") @$disp=@NewsPodrob($SysValue['nav']['id']);
	  else @$disp=@News();

		
// Определяем переменые
  $SysValue['other']['NavActive']="news";
  $SysValue['other']['DispShop']=@$disp;
  

if($SysValue['other']['DispShop'] == 404){
header("HTTP/1.0 404 Not Found");
header("Status: 404 Not Found");
include("pages/error.php");
}
  
// Подключаем шаблон 
@ParseTemplate($SysValue['templates']['index']);

?>

