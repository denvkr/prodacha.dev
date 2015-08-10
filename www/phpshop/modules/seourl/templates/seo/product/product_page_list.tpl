<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="page_nava">
        <div> @breadCrumbs@ </div>
      </div>
      <h2>@catalogCategory@</h2></td>
    <td width="100" ><div >
        <div align="right"><img src="images/page_excel.gif" alt="" border="0" align="absmiddle" hspace="5"><a href="/price/CAT_SORT_@pcatalogId@.html" title="Прайс-лист каталога @catalogCategory@" class="link">Прайс-лист каталога</a></div>
      </div></td>
  </tr>
</table>
<div  style="padding:10px 0px">@catalogContent@</div>
<NOINDEX>
  <form method="post" action="/shop/CID_@productId@.html" name="sort">
    <table width="704" class="filt6" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="3" height="3"><img src="images/filt1.gif" alt="" width="3" height="3" /></td>
        <td ><img src="images/spacer.gif" alt="" width="1" height="1" /></td>
        <td width="3" height="3"><img src="images/filt2.gif" alt="" width="3" height="3" /></td>
      </tr>
      <tr>
        <td  colspan="3"><table cellpadding="0" cellspacing="0" border="0" class="vendorDisp" >
            <tr>
              <td  ><table border="0" cellspacing="0" cellpadding="0">
                  <tr> @vendorDisp@ </tr>
                </table></td>
              <td >@vendorSelectDisp@</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td  height="2"  colspan="3"  class="filt5"><img src="images/spacer.gif" alt="" width="1" height="2" /></td>
      </tr>
      <tr>
        <td colspan="3" height="29"><div class="divSortPageList">Сортировать по: наименованию ( <span class="spanSortPageList"><a href="./CID_@productId@_@productPageThis@@nameLat@.html?@productVendor@&f=1&amp;s=1">возр</a> / <a href="./CID_@productId@_@productPageThis@@nameLat@.html?@productVendor@&f=2&amp;s=1">убыв</a></span> ), цене ( <span class="spanSortPageList"><a href="./CID_@productId@_@productPageThis@@nameLat@.html?@productVendor@&f=1&amp;s=2">возр</a> / <a href="./CID_@productId@_@productPageThis@@nameLat@.html?@productVendor@&f=2&amp;s=2">убыв</a></span> )</div></td>
      </tr>
      <tr>
        <td width="3" height="3"><img src="images/filt4.gif" alt="" width="3" height="3" /></td>
        <td><img src="images/spacer.gif" alt="" width="1" height="1" /></td>
        <td width="3" height="3"><img src="images/filt3.gif" alt="" width="3" height="3" /></td>
      </tr>
    </table>
  </form>
</NOINDEX>
<div style="overflow:hidden; margin-top:20px">
  <table cellpadding="0" cellspacing="0" border="0" width="100%">
    @productPageDis@
  </table>
</div>
<div class="navi">@productPageNav@</div>
