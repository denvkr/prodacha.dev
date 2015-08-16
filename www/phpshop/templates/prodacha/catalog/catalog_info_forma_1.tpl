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
  <h1>
@php
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/seotools/seotools.class.php');
	$ST = new Seotools; 

	// Вывод метки, где 
	// $variable = название переменной (столбца). 
	// $default_value = значение по умолчанию 


	$oldh1=$GLOBALS['SysValue']['other']['catalogName']; //"@catalogName@";
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
php@
  </h1>
</div>

<!--
@php
	require_once ($_SERVER['DOCUMENT_ROOT'] .'/mtbl_tools_catalog_advanced.php');
php@
-->

<!--<div>@catalogContent@</div>-->

<div class="simpletextbox_h">
	@catalogContent_h@
</div>
<div class="filter_" id="filter_">
<!--<div id="by_type" style="display:@display_status1@;font: 18px Myriad Pro;"><b>По типу</b></div>-->
<div class="add_catalog" style="display:@display_status1@;">@catalogList@</div>
<!--<div id="by_manufacture" style="display:@display_status2@;font: 18px Myriad Pro;"><b>По производителю</b></div>-->
<div class="add_catalog" style="display:@display_status2@;">@catalogList1@</div>
</div>
@php
	//require_once ($_SERVER['DOCUMENT_ROOT'] . '/filtr_maker.php');
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

@catalogoutput@

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