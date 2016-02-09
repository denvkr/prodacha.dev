<div id="_tool_@productUid@" class="tool_@productUid@">

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
      <div style="clear:both;"></div>
@Producticons@
   @ComStart@ <span class="price">@productPrice@<span class="smallfont">&nbsp;@productValutaName@</span></span>

   @ComEnd@
    
	@php
	$SysValue = parse_ini_file('phpshop/inc/config.ini', 1);

        //для каталога stihl модифицируем кнопку уточнить
        $stihl_catalog_search=false;
	$viking_catalog_search=false;
	$row_sklad=false;
	$row_outdated=false;	
	if (!empty($GLOBALS['SysValue']['other']['productUid'])) {
		$sql="select id,name,sklad,outdated,analog from ".$SysValue['base']['products']." where id=".$GLOBALS['SysValue']['other']['productUid'];
	}

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
                                    if ($catalog_id_rows[3]==1) {
                                            $row_outdated=true;
                                    }
                                    if ($catalog_id_rows[3]==1) {
                                            $row_analog=$catalog_id_rows[4];
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
                        echo '<div class="buybuttons">';
			if ($viking_catalog_search==true) {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="viking_window(\\''.$region_info.'\\',\\'/shop/UID_'.$prod_id.'.html\\',document.getElementsByClassName(\\'netref\\'))"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';		
			} else {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="stihl_window(\\''.$region_info.'\\',\\'/shop/UID_'.$prod_id.'.html\\',document.getElementsByClassName(\\'netref\\'))"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';
			}
                        echo '<div class="prev_price lostandfound" style="position:relative;font-size:11px !important;left:-24px;top:5px;">@productPriceRub@</div>';

		} else if ( $row_sklad==true && $row_outdated==true && !(empty($row_analog)) ) {
                        echo '<div class="buybuttons">';
                        echo '<a id="analog_href'.$GLOBALS['SysValue']['other']['productUid'].'" href="/shop/UID_'.$row_analog.'.html">АНАЛОГ</a>';
                        echo '<span class="outdated_message" style="position:relative;font-size:11px !important;left: -12px"><!--noindex-->'.$SysValue['lang']['outdated_message3'].'<!--/noindex--></span>';
                        /*
			$productnotice='<div id="price_comlain'.$GLOBALS['SysValue']['other']['productUid'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;left:-20px;"><noindex>'.$SysValue['lang']['outdated_message2'].'</noindex></div>';
			$productnotice.='<a id="analog_href'.$GLOBALS['SysValue']['other']['productUid'].'" style="visibility: hidden;" href="http://prodacha.ru/shop/UID_'.$row_analog.'.html"></a>';
			$productnotice.='<script type="text/javascript">';
			$productnotice.='$(document).ready(function() {';
			$productnotice.='$("#price_comlain'.$GLOBALS['SysValue']['other']['productUid'].'").click( function( event ) {';
			$productnotice.='	window.location =$("#analog_href'.$GLOBALS['SysValue']['other']['productUid'].'").attr("href");';
			$productnotice.='	return false;';
			$productnotice.='});});';
			$productnotice.='</script>';
			echo $productnotice;
                        */
		} else if ( $row_sklad==true && $row_outdated==true && empty($row_analog) ) {
                        echo '<div class="buybuttons inactive">';
                        echo '<a id="analog_href'.$GLOBALS['SysValue']['other']['productUid'].'" href="/shop/UID_.html" onclick="return false;">АНАЛОГ</a>';
                        echo '<span class="outdated_message" style="position:relative;font-size:11px !important;left: -12px"><!--noindex-->'.$SysValue['lang']['outdated_message3'].'<!--/noindex--></span>';

			//$productnotice='<div id="price_comlain'.$GLOBALS['SysValue']['other']['productUid'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;left:-20px;border-bottom:1px;"><!--noindex-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--/noindex--></div>';
			//echo $productnotice;		
		} else {
                        echo '<div class="buybuttons">';
			if ($viking_catalog_search==true) {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="viking_window(\\''.$region_info.'\\',\\'/shop/UID_'.$prod_id.'.html\\',document.getElementsByClassName(\\'netref\\'))"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';		
			} else {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="stihl_window(\\''.$region_info.'\\',\\'/shop/UID_'.$prod_id.'.html\\',document.getElementsByClassName(\\'netref\\'))"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';
			}
		}
		echo '@ComEndNotice@';
                echo '</div>';
		//echo '@ComStartCart@<span class="addtochart">';
		//echo '<input type="button" onclick="javascript:stihl_window(\\''.$region_info.'\\')"  value="@product_sale@" />';
		//echo '</span>@ComEndCart@';	
	} else {
		//echo 'main_product_forma_1';
		$row_sklad=false;
		$price_n=false;
		$row_outdated=false;
                $old_price;
                $sql="select sklad,price_n,outdated,analog,price from ".$SysValue['base']['products']." where id=".$GLOBALS['SysValue']['other']['productUid'];
                $res=mysql_query($sql);
                while ($catalog_id_rows=mysql_fetch_row($res)) {
                        if ($catalog_id_rows[0]==1) {
                                $row_sklad=true;
                        }
                        if (intval($catalog_id_rows[1])>0) {
                                $price_n=true;
                                $old_price=intval($catalog_id_rows[1]);
                        }
                        if (intval($catalog_id_rows[2])==1) {
                                $row_outdated=true;
                        }
                        if (intval($catalog_id_rows[2])==1) {
                                $row_analog=intval($catalog_id_rows[3]);
                        }
                        if (intval($catalog_id_rows[4])>0) {
                                $price=intval($catalog_id_rows[4]);
                        }
                }
		if ($row_sklad==true && $row_outdated==false) {
                        //кнопка уточнить без старой цены
                        echo '<div class="buybuttons">';
			echo '@ComStartNotice@';
			echo '<span class="addtochart notice">';
			echo '<input type="button" onclick="ask_product_availability(\\'/shop/UID_'.$GLOBALS['SysValue']['other']['productUid'].'.html\\',document.getElementsByClassName(\\'netref\\'));" value="'.$SysValue['lang']['product_notice'].'">';
			echo '</span>';
                        echo '<div class="prev_price lostandfound" style="position:relative;font-size:11px !important;left:-24px;top:5px;">@productPriceRub@</div>';
			echo '@ComEndNotice@';
                        echo '</div>';
		} else if ($row_sklad==false && $price_n==true && $row_outdated==false) {
                    $fix_len=117;
                    if (strlen($price)==3){
                    if (strlen($price)<strlen($old_price))
                            $left=$fix_len-strlen($old_price)-46;
                    else
                            $left=$fix_len-strlen($old_price)-44;
                    }
                    else if (strlen($price)==4){
                    if (strlen($price)<strlen($old_price))
                            $left=$fix_len-strlen($old_price)-49;
                    else
                            $left=$fix_len-strlen($old_price)-47;
                    }
                    else if (strlen($price)==5){
                    if (strlen($price)<strlen($old_price))
                            $left=$fix_len-strlen($old_price)-51;
                    else
                            $left=$fix_len-strlen($old_price)-50;
                    }
                    else if (strlen($price)==6){
                    if (strlen($price)<strlen($old_price))
                            $left=$fix_len-strlen($old_price)-52;
                    else
                            $left=$fix_len-strlen($old_price)-50;
                    }
                    else if (strlen($price)==7){
                    if (strlen($price)<strlen($old_price))
                            $left=$fix_len-strlen($old_price)-55;
                    else
                            $left=$fix_len-strlen($old_price)-53;
                    }
                    else {
                    if (strlen($price)<strlen($old_price))
                            $left=$fix_len-strlen($old_price)-52;
                    else
                            $left=$fix_len-strlen($old_price)-50;
                    }
                    
                    switch (strlen($old_price)) {
                            case 4:
                                    $old_price=substr($old_price,0,1).' '.substr($old_price,1,strlen($old_price)-1);
                                    break;
                            case 5:
                                    $old_price=substr($old_price,0,2).' '.substr($old_price,2,strlen($old_price)-2);
                                    break;
                            case 6:
                                    $old_price=substr($old_price,0,3).' '.substr($old_price,3,strlen($old_price)-3);
                                    break;
                            case 7:
                                    $old_price=substr($old_price,0,1).' '.substr($old_price,1,strlen($old_price)-1);
                                    break;				
                    }
                    echo '<div class="prev_price" style="top:-15px;left:'.$left.'px;text-decoration:line-through;">'.$old_price.'</div>';
                    echo '<div class="buybuttons">';
                    echo '@ComStartCart@';
                    //echo '<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@)"  value="'.$SysValue['lang']['product_sale'].'"></span>';
                    echo '<span class="addtochart"><a href="#_tool_@productUid@" id="a@productUid@" onclick="javascript:AddToCart(@productUid@)">'.$SysValue['lang']['product_sale'].'</a></span>';
                    echo '@ComEndCart@';
                    echo '</div>';

		} else if ($row_sklad==true && $row_outdated==true && !empty($row_analog)) {
                        echo '<div class="buybuttons">';
			echo '@ComStartCart@';
			echo '@price_comlain@';
			echo '@ComEndCart@';
                        echo '</div>';
		} else if ($row_sklad==true && $row_outdated==true && empty($row_analog)) {
                        echo '<div class="buybuttons inactive">';
			echo '@ComStartCart@';
			echo '@price_comlain@';
			echo '@ComEndCart@';
                        echo '</div>';
		} else {
                        echo '<div class="buybuttons">';
			echo '@ComStartCart@';
			//echo '<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@)"  value="'.$SysValue['lang']['product_sale'].'"></span>';
                        echo '<span class="addtochart"><a href="#_tool_@productUid@" id="a@productUid@" onclick="javascript:AddToCart(@productUid@)">'.$SysValue['lang']['product_sale'].'</a></span>';
			echo '@ComEndCart@';
                        echo '</div>';
		}
		
		//echo '@ComStartCart@<span class="addtochart">';
		//echo '<input type="button" onclick="javascript:AddToCart(@productUid@)"  value="@product_sale@" />';
		//echo '</span>@ComEndCart@';
		//echo '@ComStartNotice@';
		//echo '<span class="addtochart notice"> <input type="button" onclick="window.location.replace(\\'/users/notice.html?productId=@productUid@\\');" value="'.$SysValue['lang']['product_notice'].'" /> </span>'; //@productNotice@
		//echo '@ComEndNotice@';
	}
        //echo '549-cat-1';
	php@			
			
			<!--@firstcreditpunch@-->	
		
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
