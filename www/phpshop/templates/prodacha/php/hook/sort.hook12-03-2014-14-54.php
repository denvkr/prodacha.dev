<?php
 
/**
 * ����� ������������� � ������� ��������  ������
 */
function checkStore_add_sorttable_hook($obj, $row) {
    if (empty($obj->category)) {
        $obj->PHPShopCategory = new PHPShopCategory($row['category']);
    }
    $obj->doLoadFunction('PHPShopShop', 'sort_table', $row, 'shop');	


	// ������ ����
    $obj->set('productPrice2',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price2'],$row['baseinputvaluta']));
 
    // ���� ����� ��������� ����
    $obj->set('productPrice3',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price3'],$row['baseinputvaluta']));
    $obj->set('productPrice4',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price4'],$row['baseinputvaluta']));
    $obj->set('productPrice5',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price5'],$row['baseinputvaluta']));
    return true;
	
    return true;
}
 
 
 
 
 
 
$addHandler=array
        (
        'checkStore'=>'checkStore_add_sorttable_hook'
);


 
?>
