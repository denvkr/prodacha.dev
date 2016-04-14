<div class="tovar">
<div class="item" id="_tool_@productUid@">
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
            if ($catalog_id_rows[3]==1) {
                    $row_outdated=true;
            }
            if ($catalog_id_rows[3]==1) {
                    $row_analog=$catalog_id_rows[4];
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
        //echo '<input type="button" onclick="javascript:stihl_window(\\''.$region_info.'\\');"  value="@product_sale@" />';
        //echo '</span>@ComEndCart@';		
        echo '@ComStartNotice@';
        if ($row_sklad==true && $row_outdated==false) {
                //кнопка как купить без старой цены со статусом уточнить
                //echo '<span class="addtochart notice"> <input type="button" onclick="window.location.replace(\\'/users/notice.html?productId=@productUid@\\');" value="'.$SysValue['lang']['product_notice'].'" /> </span>';//@productNotice@
                if ($viking_catalog_search==true) {
                        echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="viking_window(\\''.$region_info.'\\',\\'/shop/UID_'.$GLOBALS['SysValue']['other']['productUid'].'.html\\',document.getElementsByClassName(\\'netref\\'));"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';		
                } else {
                        echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="stihl_window(\\''.$region_info.'\\',\\'/shop/UID_'.$GLOBALS['SysValue']['other']['productUid'].'.html\\',document.getElementsByClassName(\\'netref\\'));"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';
                }
                echo '@ComEndNotice@';		

                echo '@ComStart@';	
                echo '<div class="price"><span class="price_cat_3">Цена:</span>@productPrice@ @productValutaName@';   
                echo '<div style="clear:both"></div>';
                echo '<div class="prev_price lostandfound" style="position:absolute;font-size:11px !important;left: -117px;top: 25px;">@productPriceRub@</div>         </div>';
                echo '@ComEnd@';
                echo '</div>';
                echo '</div>';
        } else if ( $row_sklad==true && $row_outdated==true && !(empty($row_analog)) ) {
                //кнопка снято с производства + посмотреть аналог
                    echo '@ComStartCart@';
                    echo '<span class="addtochart">';
                    echo '<a id="analog_href'.$GLOBALS['SysValue']['other']['productUid'].'" href="/shop/UID_'.$row_analog.'.html">АНАЛОГ</a>';
                    echo '</span>';
                    echo '@ComEndCart@';

                    echo '@ComStart@';	
                    echo '<div class="price"><span class="price_cat_3">Цена:</span>@productPrice@ @productValutaName@';
                    echo '<div style="clear:both"></div>';
                    echo '<span class="outdated_message" style="position:absolute;font-size:11px !important;left: -106px;top: 25px;"><!--noindex-->'.$SysValue['lang']['outdated_message3'].'<!--/noindex--></span></div>';
                    echo '@ComEnd@';
                    echo '</div>';
                    echo '</div>';
                
/*                
                echo '@ComStartCart@';
                //echo '@price_comlain@';
                echo '<span class="outdated_message" style="position:relative;display: inline-block;top:3px;margin:-5px 0px 0px 10px;font-size:12px !important;"><!--noindex-->'.$SysValue['lang']['outdated_message'].'<!--/noindex--></span>';
                echo '@ComEndCart@';                
                $productnotice='<div id="price_comlain'.$GLOBALS['SysValue']['other']['productUid'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;"><!--noindex-->'.$SysValue['lang']['outdated_message2'].'<!--/noindex--></div>';
                $productnotice.='<a id="analog_href'.$GLOBALS['SysValue']['other']['productUid'].'" style="visibility: hidden;" href="/shop/UID_'.$row_analog.'.html"></a>';
                $productnotice.='<script type="text/javascript">';
                $productnotice.='$(document).ready(function() {';
                $productnotice.='$("#price_comlain'.$GLOBALS['SysValue']['other']['productUid'].'").click( function( event ) {';
                $productnotice.='	window.location =$("#analog_href'.$GLOBALS['SysValue']['other']['productUid'].'").attr("href");';
                $productnotice.='	return false;';
                $productnotice.='});});';
                $productnotice.='</script>';
                echo $productnotice;

                echo '@ComStart@';	
                echo '<div class="price" style="display:relative;margin:-32px 15px 0px 0px;"><span class="price_cat_3">Цена:</span>@productPrice@ @productValutaName@';   
                echo '@ComEnd@';

                echo '@ComEndNotice@';		
                echo '</div>';
                echo '</div>';
*/
        } else if ( $row_sklad==true && $row_outdated==true && empty($row_analog) ) {
                //кнопка снято с производства
                    echo '@ComStartCart@';
                    echo '<span class="addtochart inactive">';
                    echo '<a id="analog_href'.$GLOBALS['SysValue']['other']['productUid'].'" href="/shop/UID_'.$row_analog.'.html" onclick="return false;">АНАЛОГ</a>';
                    echo '</span>';
                    echo '@ComEndCart@';

                    echo '@ComStart@';	
                    echo '<div class="price"><span class="price_cat_3">Цена:</span>@productPrice@ @productValutaName@';
                    echo '<div style="clear:both"></div>';
                    echo '<span class="outdated_message" style="position:absolute;font-size:11px !important;left: -106px;top: 25px;"><!--noindex-->'.$SysValue['lang']['outdated_message3'].'<!--/noindex--></span></div>';
                    echo '@ComEnd@';
                    echo '</div>';
                    echo '</div>';
    /*
                $productnotice='<div id="price_comlain'.$GLOBALS['SysValue']['other']['productUid'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;left:-20px;border-bottom:1px;"><!--noindex-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--/noindex--></div>';
                echo $productnotice;

                echo '@ComEndNotice@';		
                echo '</div>';
                echo '</div>';
    */
        } else {
                //кнопка как купить (предполагается со старой ценой)
                if ($viking_catalog_search==true) {
                        echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="viking_window(\\''.$region_info.'\\',\\'/shop/UID_'.$GLOBALS['SysValue']['other']['productUid'].'.html\\',document.getElementsByClassName(\\'netref\\'));"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';		
                } else {
                        echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="stihl_window(\\''.$region_info.'\\',\\'/shop/UID_'.$GLOBALS['SysValue']['other']['productUid'].'.html\\',document.getElementsByClassName(\\'netref\\'));"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';
                }
                echo '@ComStart@';	
                echo '<div class="price"><span class="price_cat_3">Цена:</span>@productPrice@ @productValutaName@';   
                echo '@ComEnd@';

                echo '@ComEndNotice@';		
                echo '</div>';
                echo '</div>';
        }
        } else {
            $row_sklad=false;
            $price_n=false;
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
                    echo '@ComStartNotice@';
                    echo '<span class="addtochart">';
                    //echo '<input type="button" onclick="ask_product_availability(\\'/shop/UID_'.$GLOBALS['SysValue']['other']['productUid'].'.html\\',document.getElementsByClassName(\\'netref\\'));" value="'.$SysValue['lang']['product_notice'].'">';
                    echo '<a href="#_tool_'.$GLOBALS['SysValue']['other']['productUid'].'" id="azakaz'.$GLOBALS['SysValue']['other']['productUid'].'" onclick="ask_product_availability(\\'/shop/UID_'.$GLOBALS['SysValue']['other']['productUid'].'.html\\',document.getElementsByClassName(\\'netref\\'));">'.$SysValue['lang']['product_notice'].'</a>';
                    echo '</span>';
                    echo '@ComEndNotice@';

                    echo '@ComStart@';	
                    echo '<div class="price"><span class="price_cat_3">Цена:</span>@productPrice@ @productValutaName@';   
                    echo '<div style="clear:both"></div>';
                    echo '<div class="prev_price lostandfound" style="position:absolute;font-size:11px !important;left: -117px;top: 25px;">@productPriceRub@</div>         </div>';
                    echo '@ComEnd@';
                    echo '</div>';
                    echo '</div>';
            } else if ($row_sklad==false) {
                    //возможно вывод данного статуса не требуется
                    echo '@ComStartCart@';
                    //echo '<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@);"  value="'.$SysValue['lang']['product_sale'].'"></span>';
                    echo '<span class="addtochart"><a href="#_tool_@productUid@" id="a@productUid@" onclick="javascript:AddToCart(@productUid@);">'.$SysValue['lang']['product_sale'].'</a></span>';
                    echo '@ComEndCart@';

                    echo '@ComStart@';	
                    echo '<div class="price"><span class="price_cat_3">Цена:</span>@productPrice@ @productValutaName@';  
                    echo '@ComEnd@';
                    echo '</div>';
                    echo '</div>';
            } else if ($row_sklad==true && $row_outdated==true && !empty($row_analog)) {
                    //кнопка снято с производства + посмотреть аналог
                    echo '@ComStartCart@';
                    echo '<span class="addtochart">';
                    echo '<a id="analog_href'.$GLOBALS['SysValue']['other']['productUid'].'" href="/shop/UID_'.$row_analog.'.html">АНАЛОГ</a>';
                    echo '</span>';
                    echo '@ComEndCart@';

                    echo '@ComStart@';	
                    echo '<div class="price"><span class="price_cat_3">Цена:</span>@productPrice@ @productValutaName@';
                    echo '<div style="clear:both"></div>';
                    echo '<span class="outdated_message" style="position:absolute;font-size:11px !important;left: -106px;top: 25px;"><!--noindex-->'.$SysValue['lang']['outdated_message3'].'<!--/noindex--></span></div>';
                    echo '@ComEnd@';
                    echo '</div>';
                    echo '</div>';
/*            

                    echo '@ComStartCart@';
                    //echo '@price_comlain@';
                    echo '<span class="outdated_message" style="position:relative;display: inline-block;top:3px;margin:-5px 0px 0px 10px;font-size:12px !important;"><!--noindex-->'.$SysValue['lang']['outdated_message'].'<!--/noindex--></span>';
                    echo '@ComEndCart@';
                    $productnotice= '<div id="price_comlain'.$GLOBALS['SysValue']['other']['productUid'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;"><!--noindex-->'.$SysValue['lang']['outdated_message2'].'<!--/noindex--></div>';
                    $productnotice.='<a id="analog_href'.$GLOBALS['SysValue']['other']['productUid'].'" style="visibility: hidden;" href="/shop/UID_'.$row_analog.'.html"></a>';
                    $productnotice.='<script type="text/javascript">';
                    $productnotice.='$(document).ready( function() {';
                    $productnotice.='$("#price_comlain'.$GLOBALS['SysValue']['other']['productUid'].'").click( function( event ) {';
                    $productnotice.='	window.location =$("#analog_href'.$GLOBALS['SysValue']['other']['productUid'].'").attr("href");';
                    $productnotice.='	return false;';
                    $productnotice.='});});';
                    $productnotice.='</script>';
                    echo $productnotice;
                    echo '@ComStart@';	
                    echo '<div class="price" style="display:relative;margin:-32px 15px 0px 0px;"><span class="price_cat_3">Цена:</span>@productPrice@ @productValutaName@';   
                    echo '@ComEnd@';
                    echo '</div>';
                    echo '</div>';
*/
            } else if ( $row_sklad==true && $row_outdated==true && empty($row_analog) ) {
                    //кнопка снято с производства
                    echo '@ComStartCart@';
                    echo '<span class="addtochart inactive">';
                    echo '<a id="analog_href'.$GLOBALS['SysValue']['other']['productUid'].'" href="/shop/UID_'.$row_analog.'.html" onclick="return false;">АНАЛОГ</a>';
                    echo '</span>';
                    echo '@ComEndCart@';

                    echo '@ComStart@';	
                    echo '<div class="price"><span class="price_cat_3">Цена:</span>@productPrice@ @productValutaName@';
                    echo '<div style="clear:both"></div>';
                    echo '<span class="outdated_message" style="position:absolute;font-size:11px !important;left: -106px;top: 25px;"><!--noindex-->'.$SysValue['lang']['outdated_message3'].'<!--/noindex--></span></div>';
                    echo '@ComEnd@';
                    echo '</div>';
                    echo '</div>';

/*
                    echo '@ComStart@';	
                    echo '<div style="clear:both"></div>';
                    echo '<span class="prev_price" style="position:absolute;display: inline-block;top: 215px;left: 10px;font-size:12px !important;"><!--noindex-->'.$SysValue['lang']['outdated_message'].'<!--/noindex--></span>';
                    echo '<div class="price"><span class="price_cat_3">Цена:</span>@productPrice@ @productValutaName@';
                    echo '</div>         </div>';
                    echo '@ComEnd@';
                    echo '</div>';                        
                    echo '</div>';
*/
            } else {
                    //кнопка купить со старой ценой
                    echo '@ComStartCart@';
                    //echo '<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@);" value="'.$SysValue['lang']['product_sale'].'"></span>';
                    echo '<span class="addtochart"><a href="#a@productUid@" id="a@productUid@" onclick="javascript:AddToCart(@productUid@);">'.$SysValue['lang']['product_sale'].'</a></span>';
                    echo '@ComEndCart@';

                    echo '@ComStart@';	
                    echo '<div class="price"><span class="price_cat_3">Цена:</span>@productPrice@ @productValutaName@';
                    echo '@ComEnd@';
                    echo '</div>';
                    //$old_price=100000;
                    //$price=100000;
                    //$old_price=str_pad($old_price,strlen($old_price)+strlen($old_price)," ",STR_PAD_RIGHT);
                    //echo strlen($price);
                    if (strlen($price)==3){
                    if (strlen($price)<strlen($old_price))
                            $left=200-strlen($old_price)-46;
                    else
                            $left=200-strlen($old_price)-44;
                    }
                    else if (strlen($price)==4){
                    if (strlen($price)<strlen($old_price))
                            $left=200-strlen($old_price)-49;
                    else
                            $left=200-strlen($old_price)-47;
                    }
                    else if (strlen($price)==5){
                    if (strlen($price)<strlen($old_price))
                            $left=200-strlen($old_price)-51;
                    else
                            $left=200-strlen($old_price)-50;
                    }
                    else if (strlen($price)==6){
                    if (strlen($price)<strlen($old_price))
                            $left=200-strlen($old_price)-55;
                    else
                            $left=200-strlen($old_price)-53;
                    }
                    else if (strlen($price)==7){
                    if (strlen($price)<strlen($old_price))
                            $left=200-strlen($old_price)-58;
                    else
                            $left=200-strlen($old_price)-56;
                    }
                    else {
                    if (strlen($price)<strlen($old_price))
                            $left=200-strlen($old_price)-52;
                    else
                            $left=200-strlen($old_price)-50;
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
                    echo '<div class="prev_price" style="top: 245px;left: '.$left.'px;text-decoration:line-through;">'.$old_price.'</div>';
                    echo '</div>';
            }
        }

	php@