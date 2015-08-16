

<div class="pod_cart">
<table>
<tr>
	<td valign="top">
 <A href="@productImg@"  title="Увеличить" target="_blank" ><img src="@productImg@"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this,150)" alt="@productName@" title="Увеличить" border="0"  vspace="15" ></A>
<!-- Блок корзина -->
	<div>
     @ComStart@Цена: <strong>@productPrice@</strong> @productValutaName@ @ComEnd@<br>
	@ComStartCart@
	<img src="images/shop/arr2.gif" alt="" width="16" height="16" border="0" align="absmiddle"><A href="/order/?xid=@productUid@" title="@productSale@">[@productSale@]</A><br>

	@ComEndCart@
	<!-- Блок корзина -->
	<img src="images/shop/arrow_left.gif" alt="" width="16" height="16" border="0" align="absmiddle"><A href="javascript:history.back(1)" title="@productSale@">[@productBack@]</A><br>
   </div>

	</td>
	<td>
	<div><h1>@productName@</h1>
	</div>
	
		<div>@productDes@</div>
     </div>
		
	</td>
</tr>
</table>
</div>