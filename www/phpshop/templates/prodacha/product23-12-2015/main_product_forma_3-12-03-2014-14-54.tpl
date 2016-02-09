<div class="tovar">
<div class="item">
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

@ComStartCart@<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@)"  value="В корзину" /></span>@ComEndCart@
@ComStartNotice@<span class="addtochart notice"><input type="button" onclick="window.location.replace('/users/notice.html?productId=@productUid@');"  value="@productNotice@" />
                            </span>@ComEndNotice@			
						@ComStart@	<div class="price">@productPrice@ @productValutaName@   
                         <div style="clear:both"></div>
                        <div class="prev_price">@productPriceRub@  </div>         </div>
                        
                                              
                        
                         @ComEnd@
						</div>

</div>