
<div class="pod_cart">
<table>
<tr>
	<td>
	
 <a href="/shop/UID_@productUid@.html"  title="@productName@"><img src="@productImg@"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this,100)"  alt="@productName@" title="@productName@" border="0"  vspace="15" ></a>
	</td>
	<td>
	<div><a href="/shop/UID_@productUid@.html"  title="@productName@">@productName@</a></div>
<!-- Блок корзина -->
	<div>
    @ComStart@Цена: <strong>@productPrice@</strong> @productValutaName@ @ComEnd@
	@ComStartCart@
	<img src="images/shop/arr2.gif" alt="" width="16" height="16" border="0" align="absmiddle"><a href="/order/?xid=@productUid@" title="@product_sale@">[@product_sale@]</a>
	@ComEndCart@
	<!-- Блок корзина -->
   </div>
		<div>@productDes@</div>
     </div>
		
	</td>
</tr>
</table>



</div>