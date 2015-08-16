<?
$TitlePage=__("Ссылки");

function actionStart()
{
global $PHPShopInterface;
$PHPShopInterface->size="630,550";
$PHPShopInterface->link="links/adm_linksID.php";
$PHPShopInterface->setCaption(array("&plusmn;","5%"),array("Название","30%"),array("Описание","50%"),array("Кнопка","10%"));

// SQL
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['links']);
$data = $PHPShopOrm->select(array('*'),false,array('order'=>'num desc'),array("limit"=>"1000"));
if(is_array($data))
foreach($data as $row){
extract($row);
$PHPShopInterface->setRow($id,$PHPShopInterface->icon($enabled),$name,$content,$image);
}

$PHPShopInterface->setAddItem('links/adm_links_new.php');
$PHPShopInterface->Compile();
}
?>
