<div class="page_nava">
        <div> @breadCrumbs@ </div>
      </div>
	<div class="pagetitle">
	<h1>@php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/seotools/seotools.class.php');
$ST = new Seotools; 

// Вывод метки, где 
// $variable = название переменной (столбца). 
// $default_value = значение по умолчанию 


$oldh1="@catalogCategory@";

echo $ST->get("h1", $oldh1); 

php@</h1>
					
				</div>


<div class="sorting">
				<div id="paging_container" class="reviewcontainer">
                <form method="post" action="/shop/CID_@productId@@nameLat@.html" name="sort">
					<div class="itemsort">
						<span class="sorttitle">Сортировка:</span>
                        	<span class="popularity"><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=2&amp;s=3" class="decrease">decrease</a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=1&amp;s=3" class="increase">increase</a>По популярности</span>
						<span class="price"><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=2&amp;s=2" class="decrease">decrease</a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=1&amp;s=2" class="increase">increase</a>По цене</span>
                        
						<span class="rating"><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=2&amp;s=1" class="decrease">decrease</a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=1&amp;s=1" class="increase">increase</a>По рейтингу</span>
					
					</div>
                 </form>
					<div class="page_navigation2">@productPageNav@</div>	
  <!--div style="clear:both"></div>                           
                    
        <div style="clear:both"></div>             
<table cellpadding="0" cellspacing="0" border="0" class="vendorDisp" >
            <tr>
              <td  ><table border="0" cellspacing="0" cellpadding="0">
                  <tr> @vendorDisp@ </tr>
                </table></td>
              <td >@vendorSelectDisp@</td>
            </tr>
          </table>

<div style="clear:both"></div-->   
      
<div class="content">
  <table cellpadding="0" cellspacing="0" border="0" width="100%">
    @productPageDis@
  </table>
</div>
<div style="clear:both"></div>         
				
				<div class="itemsort">
					<span class="sorttitle">Сортировка:</span>
				<span class="popularity"><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=2&amp;s=3" class="decrease">decrease</a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=1&amp;s=3" class="increase">increase</a>По популярности</span>
						<span class="price"><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=2&amp;s=2" class="decrease">decrease</a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=1&amp;s=2" class="increase">increase</a>По цене</span>
                        
						<span class="rating"><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=2&amp;s=1" class="decrease">decrease</a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=1&amp;s=1" class="increase">increase</a>По рейтингу</span>
				</div>	
				<div class="page_navigation2">@productPageNav@</div>
			</div>
			<!-- paging container end -->

			</div>
			<!-- sorting end -->
			
			<div class="simpletextbox">
						@catalogContent@
			</div>


