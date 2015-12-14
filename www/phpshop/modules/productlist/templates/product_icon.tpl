<div style="clear:both"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="120"><table cellpadding="0" cellspacing="0" border="0" class="tovarDivImg1" align="left" width="100%">
        <tr>
          <td align="center" valign="middle"><a class="highslide" onclick="return hs.expand(this)" href="@productlist_product_pic_big@" target="_blank" getParams="null" title="@productlist_product_name@"><img src="@productlist_product_pic_small@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" alt="@productName@" title="@productlist_product_name@" border="0"></a>
            <div class=highslide-caption>@productlist_product_name@</div></td>
        </tr>
      </table></td>
    <td valign="top" ><div class="tovarDivName1" style=" text-align: left; padding:10px 0px" ><a href="/shop/UID_@productUid@.html" title="@productName@">@productName@</a></div>
      <div class="tovarDivContent1" style=" text-align: left;">@productlist_product_description@</div></td>
    <td width="120"><div class="prcent">
        <table align="center" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50">@ComStart@
              <div class="tovarDivPrice1"> <span>@productlist_product_price@ @productValutaName@</span> </div>
              @ComEnd@</td>
          </tr>
        </table>
      </div>
      <div style="clear:both;"></div>
      <div class="tovarDivAdd1">
        <div class="tovarDivAdd3">
          <!-- Блок купить -->
          @ComStartCart@<a href="javascript:AddToCart(@productlist_product_id@)" title="@product_sale@">@product_sale@</a>@ComEndCart@
          <div style="clear:both; height:10px"></div>
          <!-- Блок купить -->
          <!-- Блок уведомить -->
          @ComStartNotice@<a href="/users/notice.html?productId=@productlist_product_id@" title="@productNotice@">@productNotice@</a>@ComEndNotice@
          <!-- Блок уведомить -->
        </div>
        <div class="tovarDivAdd2"><a href="javascript:AddToCompare(@productlist_product_id@)" title="Сравнить @productlist_product_name@">Сравнить</a></div>
      </div></td>
  </tr>
</table>
