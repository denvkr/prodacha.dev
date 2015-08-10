<div id="_tool_" class="tool_@productUid@">

<div class="tovar1"> <div class="item1"> 

<span class="popular-"></span>
  <div class="thumb">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="160" align="top"><a href="/shop/UID_@productUid@@nameLat@.html"><img src="@productImg@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this,@productImgWidth@)" alt="@productName@" title="@productName@" border="0"></a></td>
      </tr>
    </table>
  </div>
  <div class="descr_wrapper"><a href="/shop/UID_@productUid@@nameLat@.html"><span class="description">@productName@</span></a>

@Producticons@
  
   <span class="prev_price">@productPriceRub@</span> @ComStart@ <span class="price">@productPrice@<span class="smallfont">@productValutaName@</span></span>

   @ComEnd@
   
	   <div class="buybuttons">
	   
			@ComStartCart@<span class="addtochart">
			<input type="button" onclick="javascript:AddToCart(@productUid@)"  value="В корзину" />
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
			
			
			@firstcreditpunch@
			 
			
			</span>  


		
		
		</div>
		
	</div>
  <div class="parameters">

    <table  width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="160" valign=top ><div style="overflow:hidden; height:150px; padding-right:20px;">@vendorDisp@</div></td>
      </tr>
    </table>
  </div>
  </div> </div>


</div>
