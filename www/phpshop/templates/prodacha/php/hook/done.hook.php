<?php
//echo 'index_hook_start';
function send_to_order_hook($obj,$post,$rout) {
	//echo 'index_hook_function';
	PHPShopObj::loadClass("orm");
	// SQL
	$PHPShopOrm = new PHPShopOrm();
	
	if ($rout == 'MIDDLE') {
		$ga_commerce="<script type='text/javascript'>
										ga('require', 'ecommerce', 'ecommerce.js');
										ga('ecommerce:addTransaction', {";
		//$gag_commerce="_gaq.push(['_addTrans',";
		if ( isset($obj->ouid) ) {
			$ga_commerce.="'id': '".$obj->ouid."',";
			//$gag_commerce.="'".$obj->ouid."',";
		}
		else {
			$ga_commerce.="'id': '0',";
			//$gag_commerce.="'',";
		}
		if ( $obj->PHPShopDelivery->getCity()!='' ) {
			$ga_commerce.="'affiliation': '".$obj->PHPShopDelivery->getCity()."',";
			//$gag_commerce.="'".$obj->PHPShopDelivery->getCity()."',";
		}
		else {
			$ga_commerce.="'affiliation': 'Unknown',";
			//$gag_commerce.="'',";
		}
		if ( isset($obj->total) )  {
			$ga_commerce.="'revenue': '".$obj->total."',";
			//$gag_commerce.="'".$obj->total."',";
		}
		else {
			$ga_commerce.="'revenue': '0',";
			//$gag_commerce.="'',";
		}
		if ( isset($obj->delivery) ) {
			$ga_commerce.="'shipping': '".$obj->delivery."',";
		    //$gag_commerce1.="'".$obj->delivery."',";
		}
		else {
			$ga_commerce.="'shipping': '0',";
			//$gag_commerce1.="'',";
		}
		
			$ga_commerce.="'tax': '0'";
			//$gag_commerce.="'',".$gag_commerce1;
			//$gag_commerce.="'',";
			//$gag_commerce.="'',";
			//$gag_commerce.="'Russia',";
			$ga_commerce.="});";
			//$gag_commerce.="]);";
			
				
		if (isset($obj->PHPShopCart)) {	
			foreach ($obj->PHPShopCart->getArray() as $cart_item) {

					$cart_item1=reset($cart_item);
					$ga_commerce.="ga('ecommerce:addItem', {";
					$ga_commerce.="'id': '".$obj->ouid."',"; 		// Transaction ID. Required.
					$ga_commerce.="'name': '".$cart_item[name]."',";    // Product name. Required.
					$ga_commerce.="'sku': '".$cart_item[id]."',";       // SKU/code. Required.
					//выясняем категорию товара
					$sql="select distinct name from ".$GLOBALS['SysValue']['base']['categories']." where id=(select category from ".$GLOBALS['SysValue']['base']['products']." where id=".$cart_item[id].")";
					//echo $sql;
					$result = $PHPShopOrm->query($sql);
					while ($row=mysql_fetch_assoc($result)){
						$cat_name=$row['name'];
					}
					mysql_free_result($result);
					if (isset($cat_name)) {
						$ga_commerce.="'category': '".$cat_name."',";        	// Category or variation.
					} else						
					$ga_commerce.="'category': 'Unknown',";         	// Category or variation.
					$ga_commerce.="'price': '".$cart_item[price]."',";  // Unit price. Required.
					$ga_commerce.="'quantity': '".$cart_item[num]."'"; // Quantity (integer). Required.
					$ga_commerce.="});";
					//print_r($cart_item);
					//$gag_commerce.="_gaq.push(['_addItem',";
					//$gag_commerce.="'".$cart_item[id]."',";
					//$gag_commerce.="'".$cart_item[id]."',";
					//$gag_commerce.="'".$cart_item[name]."',";
					//$gag_commerce.="'',";
					//$gag_commerce.="'".$cart_item[price]."',";
					//$gag_commerce.="'".$cart_item[num]."',";
					//$gag_commerce.="]);";
						
			}
			$ga_commerce.="ga('ecommerce:send');
						   ga('ecommerce:clear');";
			//$gag_commerce.="_gaq.push(['_trackTrans']);";
			//$ga_commerce.=$gag_commerce;
			$ga_commerce.="</script>";

				
		}
		//$ga_commerce=print_r($obj->PHPShopCart);
		//$ga_commerce.=print_r($post);
		$obj->set('google_commerce', $ga_commerce);

	}

	//return true;
}
$addHandler=array
(
		'send_to_order'=>'send_to_order_hook'
);
/*
 (
    [_CART] => Array
        (
            [2182] => Array
                (
                    [id] => 2182
                    [name] => Мотокультиватор МКМ-1-С6 Lander
                    [price] => 29990
                    [price2] => 0
                    [price3] => 0
                    [uid] => 
                    [num] => 1
                    [weight] => 
                    [ed_izm] => шт.
                    [pic_small] => /UserFiles/Image/img2182_38054s.jpg
                    [parent] => 0
                    [user] => 2
                )

        )

    [store_check] => 
    [Valuta] => Array
        (
            [4] => Array
                (
                    [id] => 4
                    [name] => Гривны
                    [code] => гр.
                    [iso] => UAU
                    [kurs] => 0.06
                )

            [5] => Array
                (
                    [id] => 5
                    [name] => Доллары
                    [code] => $
                    [iso] => USD
                    [kurs] => 0.03
                )

            [6] => Array
                (
                    [id] => 6
                    [name] => Рубли
                    [code] => руб.
                    [iso] => RUR
                    [kurs] => 1
                )

        )

    [debug] => 1
)
Array
(
    [ouid] => 12-167
    [dostavka_metod] => 10
    [mail] => test@test.ru
    [name_person] => test
    [tel_code] => 00-05
    [tel_name] => 1111111111
    [adr_name] => 
    [dos_ot] => 
    [dos_do] => 
    [order_metod] => 3
    [legal_form] => legal_form_phys
    [org_name] => 
    [org_inn] => 
    [org_kpp] => 
    [bic_code] => <div id="VVC_BUTTON_STYLE1_COLORGREEN_ROUND_2_2182" style="display:none;">[{"id":2182,"title":"Мотокультиватор МКМ-1-С6 Lander","amount":29990,"info":"Мотокультиватор МКМ-1-С6 Lander"},{"id":2182,"title":"Комиссия сервиса 'Все в кредит' - 5%","amount":1499.5,"info":"Комиссия сервиса 'Все в кредит' - 5%"}]</div>
    [send_to_order] => ok
    [d] => 10
    [nav] => done
)
 */
?>