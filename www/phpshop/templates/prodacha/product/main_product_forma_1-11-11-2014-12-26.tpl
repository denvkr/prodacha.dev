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
   <span class="prev_price"><noindex>@productPriceRub@</noindex></span> @ComStart@ <span class="price">@productPrice@<span class="smallfont">@productValutaName@</span></span>

   @ComEnd@
   
	   <div class="buybuttons">
	   
	@php
	$SysValue = parse_ini_file('phpshop/inc/config.ini', 1);

    //для каталога stihl модифицируем кнопку уточнить
    $stihl_catalog_search=false;
	$viking_catalog_search=false;
	$row_sklad=false;
	if (!empty($GLOBALS['SysValue']['other']['productUid'])) {
		$sql="select id,name,sklad,price_n from ".$SysValue['base']['products']." where id=".$GLOBALS['SysValue']['other']['productUid'];
	}
	//if (!empty($GLOBALS['SysValue']['other']['productId'])) {	
		//$sql="select id,name from ".$SysValue['base']['categories']." where name like '%stihl%' or name like '%viking%'";
	//}
	$res=mysql_query($sql);
    while ($catalog_id_rows=mysql_fetch_row($res)) {
		//print_r($catalog_id_rows);
		//if ($GLOBALS['SysValue']['nav']['id']==$catalog_id_rows[0]) {
				if (preg_match("/stihl/i", $catalog_id_rows[1])) {
					$stihl_catalog_search=true;
				}
				if (preg_match("/viking/i", $catalog_id_rows[1])) {
					$viking_catalog_search=true;
				}
				if ($catalog_id_rows[2]==1) {
					$row_sklad=true;
				}
		  //}          	 
    }

    if ($stihl_catalog_search==true || $viking_catalog_search==true) {
		if (isset($_COOKIE['sincity'])) {
			$region_info=$_COOKIE['sincity'];
		} else {
			$region_info='m';
		}
		echo '@ComStartNotice@';
		if ($row_sklad==true) {
			//echo '<span class="addtochart notice"> <input type="button" onclick="window.location.replace(\\'/users/notice.html?productId=@productUid@\\');" value="'.$SysValue['lang']['product_notice'].'" /> </span>';//@productNotice@
			if ($viking_catalog_search==true) {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="viking_window(\\''.$region_info.'\\')"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';		
			} else {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="stihl_window(\\''.$region_info.'\\')"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';
			}
		} else {
			if ($viking_catalog_search==true) {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="viking_window(\\''.$region_info.'\\')"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';		
			} else {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="stihl_window(\\''.$region_info.'\\')"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';
			}
		}
		echo '@ComEndNotice@';		
		//echo '@ComStartCart@<span class="addtochart">';
		//echo '<input type="button" onclick="javascript:stihl_window(\\''.$region_info.'\\')"  value="@productSale@" />';
		//echo '</span>@ComEndCart@';	
	} else {
		$row_sklad=false;
		$price_n=false;
		$sql="select sklad,price_n from ".$SysValue['base']['products']." where id=".$GLOBALS['SysValue']['other']['productUid'];
		$res=mysql_query($sql);
		while ($catalog_id_rows=mysql_fetch_row($res)) {
			if ($catalog_id_rows[0]==1) {
				$row_sklad=true;
			}
			if ($catalog_id_rows[1]>0) {
				$price_n=true;
			}				
		}
		if ($row_sklad==true && $price_n=true) {
			echo '@ComStartNotice@';
			echo '<span class="addtochart notice">';
			echo '<input type="button" onclick="window.location.replace(\\'/users/notice.html?productId=@productUid@\\');"  value="'.$SysValue['lang']['product_notice'].'">';
			echo '</span>';
			echo '@ComEndNotice@';	
		} else if ($row_sklad==false && $price_n=true) {
			echo '@ComStartCart@';
			echo '<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@)"  value="'.$SysValue['lang']['productSale'].'"></span>';
			echo '@ComEndCart@';		
		} else {
			echo '@ComStartCart@';
			echo '<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@)"  value="'.$SysValue['lang']['productSale'].'"></span>';
			echo '@ComEndCart@';	
		}
		
		//echo '@ComStartCart@<span class="addtochart">';
		//echo '<input type="button" onclick="javascript:AddToCart(@productUid@)"  value="@productSale@" />';
		//echo '</span>@ComEndCart@';
		//echo '@ComStartNotice@';
		//echo '<span class="addtochart notice"> <input type="button" onclick="window.location.replace(\\'/users/notice.html?productId=@productUid@\\');" value="'.$SysValue['lang']['product_notice'].'" /> </span>'; //@productNotice@
		//echo '@ComEndNotice@';
	}

	php@			
			
			<!--@firstcreditpunch@-->
			 		
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
