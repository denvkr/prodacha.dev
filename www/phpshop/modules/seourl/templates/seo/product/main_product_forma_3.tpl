<div style="width:220px">
<div class="posrelblank"><div class="posrabs1">&nbsp;</div><div class="posrabs2">&nbsp;</div></div>
<div style="clear:both;"></div>
<div class="prod_cart">
    <div class="prod_cart2">
        <table cellpadding="0" cellspacing="0" border="0" class="tovarDivImg1" align="left" width="100%">
            <tr><td align="center" valign="middle"><a class="highslide" onclick="return hs.expand(this)" href="@productImgBigFoto@" target="_blank" getParams="null" title="@productName@">
                        <img src="@productImg@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')"  alt="@productName@" title="@productName@" border="0">
                    </a>
                    <div class=highslide-caption>@productName@</div></td>
            </tr>
        </table>
        <div style="clear:both"></div>
        <div class="tovarDivName1"><a href="/shop/UID_@productUid@@nameLat@.html" title="@productName@">@productName@</a></div>
        <div class="tovarDivContent1">@productDes@</div>
        <div class="prcent"><table align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>@ComStart@
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><div align="center"><span class="tovarDivOldPrice1">@productPriceRub@</span></div></td>
                                <td>  <div class="tovarDivPrice1">
                                        <span>@productPrice@ @productValutaName@</span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        @ComEnd@</td>
                </tr>
            </table>

        </div>
        <div class="tovarDivAdd1">
            <div class="tovarDivAdd3"><!-- Блок купить -->@ComStartCart@<a href="javascript:AddToCart(@productUid@)" title="@productSale@">@productSale@</a>@ComEndCart@<!-- Блок купить --><!-- Блок уведомить -->@ComStartNotice@<a href="/users/notice.html?productId=@productUid@" title="@productNotice@">@productNotice@</a>@ComEndNotice@<!-- Блок уведомить --></div><div class="tovarDivAdd2"><a href="javascript:AddToCompare(@productUid@)" title="Сравнить @productName@">Сравнить</a></div>
        </div>

    </div>
</div>
</div>
