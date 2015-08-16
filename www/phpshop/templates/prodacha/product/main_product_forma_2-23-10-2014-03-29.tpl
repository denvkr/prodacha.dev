<table align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="tovar">
<div class="item">
							<span class="new"></span>
							<div class="thumb">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="160" align="center"><a href="/shop/UID_@productUid@@nameLat@.html"><img src="@productImg@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this,@productImgWidth@)" alt="@productName@" title="@productName@" border="0"></a></td>
  </tr>
</table>
</div>
	

<div style="clear:both"></div>
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" height="45"><a href="/shop/UID_@productUid@@nameLat@.html"><span class="description">@productName@</span></a></td>
  </tr>
</table>
@ComStartCart@<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@)"  value="В корзину" /></span>@ComEndCart@
@ComStartNotice@
@Producticons@
  
   <span class="prev_price">@productPriceRub@</span> @ComStart@ <span class="price">@productPrice@<span class="smallfont">@productValutaName@</span></span>

   @ComEnd@
  
	   <div class="buybuttons">

	@php
	$SysValue = parse_ini_file('phpshop/inc/config.ini', 1);
    //Для каталога stihl модифицируем кнопку уточнить
    $stihl_catalog_search=false;
	$viking_catalog_search=false;
	$sql="select id,name from ".$SysValue['base']['categories']." where name like '%stihl%' or name like '%viking%'";
	$res=mysql_query($sql);
    while ($catalog_id_rows=mysql_fetch_row($res)) {
		//print_r($catalog_id_rows);

		if ($GLOBALS['SysValue']['nav']['id']==$catalog_id_rows[0]) {
				if (preg_match("/stihl/i", $catalog_id_rows[1])) {
					$stihl_catalog_search=true;
				}
				if (preg_match("/viking/i", $catalog_id_rows[1])) {
					$viking_catalog_search=true;
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
		if ($viking_catalog_search==true) {
			echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="viking_window(\\''.$region_info.'\\')"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';		
		} else {
			echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="stihl_window(\\''.$region_info.'\\')"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';
		}
		echo '@ComEndNotice@';			
	} else {
		//echo '@ComStartCart@<span class="addtochart">';
		//echo '<input type="button" onclick="javascript:AddToCart(@productUid@)"  value="@productSale@" />';
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
