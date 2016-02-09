<div class="page_nava">
        <div>@breadCrumbs@
@php
	if ($GLOBALS['SysValue']['other']['productPageThis']=='ALL') {
		$cur_page_num=' - все товары';
	} else {
	if ($GLOBALS['SysValue']['other']['productPageThis']!=1)
		$cur_page_num=' - страница '.$GLOBALS['SysValue']['other']['productPageThis'];
	else
		$cur_page_num='';
	}
	echo $cur_page_num;
php@
		</div>
      </div>
	<div class="pagetitle">
<h1>@php
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/seotools/seotools.class.php');
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/custom_config/config_functions.php');
        $ceo_custom_menu1=read_ceo_custom_menu($_SERVER['DOCUMENT_ROOT'] . '/custom_config/pagecontent-h1_catalog_rename.txt');
        foreach ($ceo_custom_menu1 as $ceo_custom_menu1_item) {
            if (in_array($GLOBALS['SysValue']['other']['productId'],$ceo_custom_menu1_item)) {
                $catalogList_mod=str_replace($ceo_custom_menu1_item['str1'],$ceo_custom_menu1_item['str2'],$GLOBALS['SysValue']['other']['catalogCategory']);
                $oldh1=$catalogList_mod;
            }
        }
        if (!isset($oldh1)){
            	$oldh1=$GLOBALS['SysValue']['other']['catalogCategory']; //"@catalogName@";
        }
        $ST = new Seotools; 

        // Вывод метки, где 
        // $variable = название переменной (столбца). 
        // $default_value = значение по умолчанию 

        //$oldh1=$GLOBALS['SysValue']['other']['catalogCategory'];//"@catalogCategory@";
        if ($GLOBALS['SysValue']['other']['productPageThis']=='ALL') {
                $cur_page_num=' - все товары';
        } else {
                if ($GLOBALS['SysValue']['other']['productPageThis']!=1)
                        $cur_page_num=' - страница '.$GLOBALS['SysValue']['other']['productPageThis'];
                else
                        $cur_page_num='';	
        }
        if (stripos( $oldh1 , '(' )===false) {
                $dis = $oldh1;		
        } else {
                $dis = trim(substr ( $oldh1 ,0, stripos( $oldh1 , '(' )-1 ));		
        }		
        echo $ST->get("h1", $dis.$cur_page_num); 

php@</h1>				
</div>
<div class="simpletextbox_h">
	@catalogContent_h@
</div>
@php
	//require_once ($_SERVER['DOCUMENT_ROOT'] . '/custom_config/menu-items_catalog_show-hide-choice-merge-equal.php');
php@  
<div class="sorting">
				<div id="paging_container" class="reviewcontainer">
                <!--noindex-->
				<form method="post" action="/shop/CID_@productId@@nameLat@.html" name="sort">
					<div class="itemsort">
						<span class="sorttitle">Сортировка:</span>
                        	<span class="popularity"><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=2&amp;s=3" class="decrease">decrease</a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=1&amp;s=3" class="increase">increase</a>По популярности</span>
						<span class="price"><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=2&amp;s=2" class="decrease">decrease</a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=1&amp;s=2" class="increase">increase</a>По цене</span>
						<span class="rating"><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=2&amp;s=1" class="decrease">decrease</a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=1&amp;s=1" class="increase">increase</a>По рейтингу</span>
					</div>
                 </form>
				 <!--/noindex-->
					<div class="page_navigation2">@productPageNav@</div>	
  <div style="clear:both"></div>             
    <!-- Стандартный фильтр     <div style="clear:both"></div>             
<table cellpadding="0" cellspacing="0" border="0" class="vendorDisp" >
            <tr>
              <td  ><table border="0" cellspacing="0" cellpadding="0">
                  <tr> @vendorDisp@ </tr>
                </table></td>
              <td >@vendorSelectDisp@</td>
            </tr>
          </table> -->
<div style="clear:both"></div>   
<div class="content">
  <table cellpadding="0" cellspacing="0" border="0" width="100%" id="manufacturers_products" >
    @productPageDis@
  </table>
</div>
<div style="clear:both"></div>         
				<!--noindex-->
				<div class="itemsort">
					<span class="sorttitle">Сортировка:</span>
				<span class="popularity"><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=2&amp;s=3" class="decrease">decrease</a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=1&amp;s=3" class="increase">increase</a>По популярности</span>
						<span class="price"><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=2&amp;s=2" class="decrease">decrease</a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=1&amp;s=2" class="increase">increase</a>По цене</span>
						<span class="rating"><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=2&amp;s=1" class="decrease">decrease</a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=1&amp;s=1" class="increase">increase</a>По рейтингу</span>
				</div>	
				<!--/noindex-->
				<div class="page_navigation2">@productPageNav@</div>
			</div>
			<!-- paging container end -->
			</div>
			<!-- sorting end -->
			<div class="simpletextbox">
						@catalogContent@
			</div>


