<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tbody>
		<tr>
        	<td align="center" valign="top"><table cellpadding="0" cellspacing="0" border="0" class="tovarDivImg2" align="left"><tr><td align="center" valign="middle"><a class="highslide" onclick="return hs.expand(this)" href="@productImgBigFoto@" target="_blank" getParams="null" title="@productName@"><img src="@productImg@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')"  alt="@productName@" title="@productName@" border="0"></a><div class=highslide-caption>@productName@</div></td></tr></table></td>
		</tr>
		<tr>
        	<td height="130" valign="top"><div class="tovarDivName1"><a href="/shop/UID_@productUid@@nameLat@.html" title="@productName@">@productName@</a></div><sapn class="tovarDivArt">@productArt@</span>
@ComStart@<div class="tovarDivPrice1">@productPrice@ @productValutaName@<br>
<span class="tovarDivOldPrice1">@productPriceRub@</span></div>@ComEnd@

<div class="tovarDivAdd1"><table border="0" cellpadding="0" cellspacing="0">
	<tbody>
    	<tr>
        	<td align="left" valign="middle"><div class="tovarDivAdd3"><!-- Блок купить -->@ComStartCart@<a href="javascript:AddToCart(@productUid@);" title="@product_sale@">@product_sale@</a>@ComEndCart@<!-- Блок купить --><!-- Блок уведомить -->@ComStartNotice@<a href="/users/notice.html?productId=@productUid@" title="@productNotice@">@productNotice@</a>@ComEndNotice@<!-- Блок уведомить --></div><div class="tovarDivAdd2"><a href="javascript:AddToCompare(@productUid@)" title="Сравнить @productName@">Сравнить</a></div></td>
        </tr>
    </tbody>
</table></div></td>
		</tr>
	</tbody>
</table>