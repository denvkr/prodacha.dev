<table align="center" border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td><div class="tovar">
<div class="item" id="_tool_@productUid@">
							<span class="new"></span>
							<div class="thumb">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="160" align="center"><a href="/shop/UID_@productUid@@nameLat@.html" alt="@productName@" title="@productName@"><img src="@productImg@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this,@productImgWidth@)" alt="@productName@" title="@productName@" border="0"></a></td>
  </tr>
</table>
</div>
	

<div style="clear:both"></div>
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" height="45"><a href="/shop/UID_@productUid@@nameLat@.html"><span class="description">@productName@</span></a></td>
  </tr>
</table>
@ComStartCart@<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@)"  value="� �������" /></span>@ComEndCart@
@ComStartNotice@
@Producticons@
  
   <span class="prev_price">@productPriceRub@</span> @ComStart@ <span class="price">@productPrice@<span class="smallfont">@productValutaName@</span></span>

   @ComEnd@
  
	   <div class="buybuttons">

	@php

	$SysValue = parse_ini_file('phpshop/inc/config.ini', 1);
    //��� �������� stihl ������������ ������ ��������
    $stihl_catalog_search=false;
	$viking_catalog_search=false;
	$row_sklad=false;
	if (!empty($GLOBALS['SysValue']['other']['productUid'])) {
		$sql="select id,name,sklad,price_n,outdated,analog from ".$SysValue['base']['products']." where id=".$GLOBALS['SysValue']['other']['productUid'];
	}
	//if (!empty($GLOBALS['SysValue']['other']['productId'])) {	
		//$sql="select id,name from ".$SysValue['base']['categories']." where name like '%stihl%' or name like '%viking%'";
	//}
	$res=mysql_query($sql);
    while ($catalog_id_rows=mysql_fetch_row($res)) {
				$prod_id=$catalog_id_rows[0];
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

    if ($stihl_catalog_search==true || $viking_catalog_search==true) {
		if (isset($_COOKIE['sincity'])) {
			$region_info=$_COOKIE['sincity'];
		} else {
			$region_info='m';
		}
		echo '@ComStartNotice@';
		if ($row_sklad==true && $row_outdated==false) {
			//echo '<span class="addtochart notice"> <input type="button" onclick="window.location.replace(\\'/users/notice.html?productId=@productUid@\\');" value="'.$SysValue['lang']['product_notice'].'" /> </span>';//@productNotice@
			if ($viking_catalog_search==true) {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="viking_window(\\''.$region_info.'\\',\\'/shop/UID_'.$prod_id.'.html\\',document.getElementsByClassName(\\'netref\\'))"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';		
			} else {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="stihl_window(\\''.$region_info.'\\',\\'/shop/UID_'.$prod_id.'.html\\',document.getElementsByClassName(\\'netref\\'))"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';
			}
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
		} else if ( $row_sklad==true && $row_outdated==true && empty($row_analog) ) {
			$productnotice='<div id="price_comlain'.$GLOBALS['SysValue']['other']['productUid'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;left:-20px;border-bottom:1px;"><!--noindex-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--/noindex--></div>';
			echo $productnotice;
		} else {
			if ($viking_catalog_search==true) {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="viking_window(\\''.$region_info.'\\',\\'/shop/UID_'.$prod_id.'.html\\',document.getElementsByClassName(\\'netref\\'))"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';		
			} else {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="stihl_window(\\''.$region_info.'\\',\\'/shop/UID_'.$prod_id.'.html\\',document.getElementsByClassName(\\'netref\\'))"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';
			}
		}
		echo '@ComEndNotice@';		
		//echo '@ComStartCart@<span class="addtochart">';
		//echo '<input type="button" onclick="javascript:stihl_window(\\''.$region_info.'\\')"  value="@product_sale@" />';
		//echo '</span>@ComEndCart@';
	} else {
		$row_sklad=false;
		$price_n=false;
		$sql="select sklad,price_n,outdated from ".$SysValue['base']['products']." where id=".$GLOBALS['SysValue']['other']['productUid'];
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

		}
		if ($row_sklad==true && ($price_n==true || $price_n==false) && $row_outdated==false) {
			echo '@ComStartNotice@';
			echo '<span class="addtochart notice">';
			//echo '<input type="button" onclick="window.location.replace(\\'/users/notice.html?productId=@productUid@\\');"  value="'.$SysValue['lang']['product_notice'].'">';
			echo '<input type="button" onclick="ask_product_availability(\\'/shop/UID_'.$GLOBALS['SysValue']['other']['productUid'].'.html\\',document.getElementsByClassName(\\'netref\\'));" value="'.$SysValue['lang']['product_notice'].'">';
			echo '</span>';
			echo '@ComEndNotice@';	
		} else if ($row_sklad==false && $price_n==true && $row_outdated==true) {
			echo '@ComStartCart@';
			//echo '<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@)"  value="'.$SysValue['lang']['product_sale'].'"></span>';
                        echo '<span class="addtochart"><a href="#a@productUid@" id="a@productUid@" onclick="javascript:AddToCart(@productUid@)">'.$SysValue['lang']['product_sale'].'</a></span>';
			echo '@ComEndCart@';
		} else if ($row_sklad==true && $row_outdated==true) {
			echo '@ComStartCart@';
			echo '@price_comlain@';
			echo '@ComEndCart@';
		} else {
			echo '@ComStartCart@';
			//echo '<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@)"  value="'.$SysValue['lang']['product_sale'].'"></span>';
                        echo '<span class="addtochart"><a href="#a@productUid@" id="a@productUid@" onclick="javascript:AddToCart(@productUid@)">'.$SysValue['lang']['product_sale'].'</a></span>';
			echo '@ComEndCart@';	
		}

		
		//echo '@ComStartCart@<span class="addtochart">';
		//echo '<input type="button" onclick="javascript:AddToCart(@productUid@)"  value="@product_sale@" />';
		//echo '</span>@ComEndCart@';
		//echo '@ComStartNotice@';
		//echo '<span class="addtochart notice"> <input type="button" onclick="window.location.replace(\\'/users/notice.html?productId=@productUid@\\');" value="'.$SysValue['lang']['product_notice'].'" /> </span>'; //@productNotice@
		//echo '@ComEndNotice@';
	}

	php@		
						@ComStart@	<div class="price">@productPrice@ @productValutaName@   
                         <div style="clear:both"></div>
                        <div class="prev_price">@productPriceRub@</div>         </div>
                        
                                              
                        
                         @ComEnd@
						</div>

</div></td>
  
  </tr>
</table>
