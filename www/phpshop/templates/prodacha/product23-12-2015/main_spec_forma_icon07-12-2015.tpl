<div class="tovar">
<div class="item" id="_tool_@productUid@">
							<span class="new"></span>
							<div class="thumb">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="160" align="center"><a href="/shop/UID_@productUid@@nameLat@.html" alt="@productName@" title="@productName@"><img src="@productImg@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this,@productImgWidth@)" alt="@productName@" title="@productName@" border="0"/></a></td>
  </tr>
</table>
</div>
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" height="45"><a href="/shop/UID_@productUid@@nameLat@.html"><span class="description">@productName@</span></a></td>
  </tr>
</table>
	@php
	$SysValue = parse_ini_file('phpshop/inc/config.ini', 1);

	$host=$SysValue['connect']['host'];
	$user_db=$SysValue['connect']['user_db'];
	$pass_db=$SysValue['connect']['pass_db'];
	$dbase=$SysValue['connect']['dbase'];
	
	$dbcnx=mysql_connect($host,$user_db,$pass_db);
	
	if (!$dbcnx)
	{
		echo("<p> error MySql </p>");
		exit;
	}
	
	if (!@mysql_select_db($dbase,$dbcnx))
	{
		echo("<p> error DB MySql </p>");
		exit;
	}

    //для каталога stihl модифицируем кнопку уточнить
    $stihl_catalog_search=false;
	$viking_catalog_search=false;
	$row_sklad=false;
	$sql="select distinct id,name,sklad,outdated,analog from ".$SysValue['base']['products']." where category in (select id from ".$SysValue['base']['categories']." where  name_rambler like '%stihl%' or name_rambler like '%viking%' or name like '%stihl%' or name like '%viking%') and id=".$GLOBALS['SysValue']['other']['productUid'];
	$res=mysql_query($sql);
    while ($catalog_id_rows=mysql_fetch_row($res)) {

		if ($GLOBALS['SysValue']['other']['productUid']==$catalog_id_rows[0]) {
			if (preg_match("/stihl/i", $catalog_id_rows[1])) {
				$stihl_catalog_search=true;
			}		
			if (preg_match("/viking/i", $catalog_id_rows[1])) {
				$viking_catalog_search=true;
			}
			if ($catalog_id_rows[2]==1) {
				$row_sklad=true;
			}
			if ($catalog_id_rows[4]==1) {
				$row_outdated=true;
			}
			if ($catalog_id_rows[4]==1) {
				$row_analog=$catalog_id_rows[5];
			}
	  }
    }

    if ($stihl_catalog_search==true || $viking_catalog_search==true) {
		if (isset($_COOKIE['sincity'])) {
			$region_info=$_COOKIE['sincity'];
		} else {
			$region_info='m';
		}
		//echo '@ComStartCart@<span class="addtochart">';
		//echo '<input type="button" onclick="javascript:stihl_window(\\''.$region_info.'\\')"  value="@productSale@" />';
		//echo '</span>@ComEndCart@';		
		echo '@ComStartNotice@';
		if ($row_sklad==true && $row_outdated==false) {

			//echo '<span class="addtochart notice"> <input type="button" onclick="window.location.replace(\\'/users/notice.html?productId=@productUid@\\');" value="'.$SysValue['lang']['product_notice'].'" /> </span>';//@productNotice@
			if ($viking_catalog_search==true) {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="viking_window(\\''.$region_info.'\\',\\'/shop/UID_'.$GLOBALS['SysValue']['other']['productUid'].'.html\\',document.getElementsByClassName(\\'netref\\'))"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';		
			} else {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="stihl_window(\\''.$region_info.'\\',\\'/shop/UID_'.$GLOBALS['SysValue']['other']['productUid'].'.html\\',document.getElementsByClassName(\\'netref\\'))"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';
			}
			echo '@ComEndNotice@';		

			echo '@ComStart@';	
			echo '<div class="price"><span class="price_cat_3">÷ена:</span>@productPrice@ @productValutaName@';   
                        echo '<div style="clear:both"></div>';
                        echo '<div class="prev_price">@productPriceRub@</div>         </div>';
			echo '@ComEnd@';
			
		} else if ( $row_sklad==true && $row_outdated==true && !(empty($row_analog)) ) {

			$productnotice='<div id="price_comlain'.$GLOBALS['SysValue']['other']['productUid'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;left:-20px;"><!--noindex-->'.$SysValue['lang']['outdated_message2'].'<!--/noindex--></div>';
			$productnotice.='<a id="analog_href'.$GLOBALS['SysValue']['other']['productUid'].'" style="visibility: hidden;" href="http://prodacha.ru/shop/UID_'.$row_analog.'.html"></a>';
			$productnotice.='<script type="text/javascript">';
			$productnotice.='$(document).ready(function() {';
			$productnotice.='$("#price_comlain'.$GLOBALS['SysValue']['other']['productUid'].'").click( function( event ) {';
			$productnotice.='	window.location =$("#analog_href'.$GLOBALS['SysValue']['other']['productUid'].'").attr("href");';
			$productnotice.='	return false;';
			$productnotice.='});});';
			$productnotice.='</script>';
			echo $productnotice;

			echo '@ComStart@';	
			echo '<div class="price" style="display:relative;margin:-32px 15px 0px 0px;"><span class="price_cat_3">÷ена:</span>@productPrice@ @productValutaName@';   
			echo '@ComEnd@';

			echo '@ComEndNotice@';		
			
		} else if ( $row_sklad==true && $row_outdated==true && empty($row_analog) ) {

			$productnotice='<div id="price_comlain'.$GLOBALS['SysValue']['other']['productUid'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;left:-20px;border-bottom:1px;"><!--noindex-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--/noindex--></div>';
			echo $productnotice;

			echo '@ComEndNotice@';		

		} else {
			if ($viking_catalog_search==true) {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="viking_window(\\''.$region_info.'\\',\\'/shop/UID_'.$GLOBALS['SysValue']['other']['productUid'].'.html\\',document.getElementsByClassName(\\'netref\\'))"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';		
			} else {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="stihl_window(\\''.$region_info.'\\',\\'/shop/UID_'.$GLOBALS['SysValue']['other']['productUid'].'.html\\',document.getElementsByClassName(\\'netref\\'))"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';
			}
			echo '@ComStart@';	
			echo '<div class="price"><span class="price_cat_3">÷ена:</span>@productPrice@ @productValutaName@';   
			echo '@ComEnd@';

			echo '@ComEndNotice@';		

		}
	} else {
		$row_sklad=false;
		$price_n=false;
		$sql="select sklad,price_n,outdated,analog from ".$SysValue['base']['products']." where id=".$GLOBALS['SysValue']['other']['productUid'];
		$res=mysql_query($sql);
		while ($catalog_id_rows=mysql_fetch_row($res)) {
			if ($catalog_id_rows[0]==1) {
				$row_sklad=true;
			}
			if ($catalog_id_rows[1]>0) {
				$price_n=true;
			}
			if (intval($catalog_id_rows[2])==1) {
				$row_outdated=true;
			}
			if (intval($catalog_id_rows[2])==1) {
				$row_analog=$catalog_id_rows[3];
			}			
		}
		if ($row_sklad==true && ($price_n==true || $price_n==false) && $row_outdated==false) {

			echo '@ComStartNotice@';
			echo '<span class="addtochart">';
			//echo '<input type="button" onclick="window.location.replace(\\'/users/notice.html?productId=@productUid@\\');"  value="'.$SysValue['lang']['product_notice'].'">';
			echo '<input type="button" onclick="ask_product_availability(\\'/shop/UID_'.$GLOBALS['SysValue']['other']['productUid'].'.html\\',document.getElementsByClassName(\\'netref\\'));" value="'.$SysValue['lang']['product_notice'].'">';
			echo '</span>';
			echo '@ComEndNotice@';

			echo '@ComStart@';	
			echo '<div class="price"><span class="price_cat_3">÷ена:</span>@productPrice@ @productValutaName@';   
                        echo '<div style="clear:both"></div>';
                        echo '<div class="prev_price">@productPriceRub@</div>         </div>';
			echo '@ComEnd@';

		} else if ($row_sklad==false) {

			echo '@ComStartCart@';
			//echo '<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@)"  value="'.$SysValue['lang']['productSale'].'"></span>';
                        echo '<span class="addtochart"><a href="#_tool_@productUid@" id="a@productUid@" onclick="javascript:AddToCart(@productUid@)">'.$SysValue['lang']['productSale'].'</a></span>';
			echo '@ComEndCart@';
			
			echo '@ComStart@';	
			echo '<div class="price"><span class="price_cat_3">÷ена:</span>@productPrice@ @productValutaName@';   
			echo '@ComEnd@';
			
		} else if ($row_sklad==true && $row_outdated==true && !empty($row_analog)) {

			echo '@ComStartCart@';
			//echo '@price_comlain@';
			echo '<span class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-5px 0px 0px 10px;font-size:12px !important;"><!--noindex-->'.$SysValue['lang']['outdated_message'].'<!--/noindex--></span>';
			echo '@ComEndCart@';
			$productnotice= '<div id="price_comlain'.$GLOBALS['SysValue']['other']['productUid'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;"><!--noindex-->'.$SysValue['lang']['outdated_message2'].'<!--/noindex--></div>';
			$productnotice.='<a id="analog_href'.$GLOBALS['SysValue']['other']['productUid'].'" style="visibility: hidden;" href="http://prodacha.ru/shop/UID_'.$row_analog.'.html"></a>';
			$productnotice.='<script type="text/javascript">';
			$productnotice.='$(document).ready( function() {';
			$productnotice.='$("#price_comlain'.$GLOBALS['SysValue']['other']['productUid'].'").click( function( event ) {';
			$productnotice.='	window.location =$("#analog_href'.$GLOBALS['SysValue']['other']['productUid'].'").attr("href");';
			$productnotice.='	return false;';
			$productnotice.='});});';
			$productnotice.='</script>';
			echo $productnotice;
			echo '@ComStart@';	
			echo '<div class="price" style="display:relative;margin:-32px 15px 0px 0px;"><span class="price_cat_3">÷ена:</span>@productPrice@ @productValutaName@';   
			echo '@ComEnd@';

		} else if ( $row_sklad==true && $row_outdated==true && empty($row_analog) ) {

			echo '@ComStart@';	
                        echo '<div style="clear:both"></div>';
			echo '<span class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-5px 0px 0px 10px;font-size:12px !important;"><!--noindex-->'.$SysValue['lang']['outdated_message'].'<!--/noindex--></span>';
			echo '<div class="price" style="display:relative;margin:-5px 15px 0px 0px;"><span class="price_cat_3">÷ена:</span>@productPrice@ @productValutaName@';
			echo '</div>         </div>';
			echo '@ComEnd@';

		} else {

			echo '@ComStartCart@';
			//echo '<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@)"  value="'.$SysValue['lang']['productSale'].'"></span>';
                        echo '<span class="addtochart"><a href="#a@productUid@" id="a@productUid@" onclick="javascript:AddToCart(@productUid@)">'.$SysValue['lang']['productSale'].'</a></span>';
			echo '@ComEndCart@';

			echo '@ComStart@';	
			echo '<div class="price"><span class="price_cat_3">÷ена:</span>@productPrice@ @productValutaName@';   
			echo '@ComEnd@';

		}
	}
	php@			
	</div>
</div>