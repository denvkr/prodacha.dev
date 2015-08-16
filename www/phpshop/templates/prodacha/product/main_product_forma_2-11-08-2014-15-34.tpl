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
	   
			@ComStartCart@<span class="addtochart">
			<input type="button" onclick="javascript:AddToCart(@productUid@)"  value="@productSale@" />
			</span>@ComEndCart@
			@ComStartNotice@
	@php

    //Для каталога stihl модифицируем кнопку уточнить
    $stihl_catalog_search=false;
	$sql="select id from phpshop_categories where name like '%stihl%'";

	$res=mysql_query($sql);
    while ($catalog_id_rows=mysql_fetch_row($res)) {
	//print_r($catalog_id_rows);
	if ($GLOBALS['SysValue']['nav']['id']==$catalog_id_rows[0]) {
		  	$stihl_catalog_search=true;
	  }          	 
    }

    if ($stihl_catalog_search==true) {
		if (isset($_COOKIE['sincity'])) {
			$region_info=$_COOKIE['sincity'];
		} else {
			$region_info='m';
		}
       	echo '<span class="addtochart notice"> <input type="button" onclick="stihl_window(\\''.$region_info.'\\')"  value="@productNotice@" /> </span>';
		} else {
       	echo '<span class="addtochart notice"> <input type="button" onclick="window.location.replace(\\'/users/notice.html?productId=@productUid@\\');" value="@productNotice@" /> </span>';
    }

	php@	

							@ComEndNotice@			
						@ComStart@	<div class="price">@productPrice@ @productValutaName@   
                         <div style="clear:both"></div>
                        <div class="prev_price">@productPriceRub@</div>         </div>
                        
                                              
                        
                         @ComEnd@
						</div>

</div></td>
  
  </tr>
</table>
