<div class="page_nava">
  <div> <a href="/">PROДАЧА</a> / Прайс-лист </div>
</div>
	<div class="pagetitle">
					<h1>Прайс-лист</h1>
				</div>

<p>
<form>
  @searchPageCategory@
  <input type="button" value="Показать" onclick="DoPriceSort();" class="ok">
</form>
</p>
<div class="pod_cart" style="">
  <table class="standart">
    <tr>
      <td width="100"></td>
      <td width="150"><a href="/price/CAT_ALL.html" title="Вывести все позиции">Вывести все позиции</a> </td>
      <td width="150"><a href="javascript:GetAllForma('@PageCategory@')" title="Форма с описанием">Форма с описанием</a> </td>
      <td width="150"><a href="#" onclick="window.open('phpshop/forms/priceprint/print.html?catId=@PageCategory@')" title="Печатная форма">Печатная форма</a> </td>
      <td width="100"></td>
    </tr>
    <tr>
      <td></td>
      <td><a href="#" onclick="window.open('/files/priceSave.php?catId=@PageCategory@')" title="Excel Форма">Excel Форма</a> </td>
      <td><a href="#" onclick="window.open('/files/priceSave.php?catId=@PageCategory@&gzip=true')" title="Excel Форма GZIP">Excel Форма GZIP</a> </td>
      <td><a href="/files/onlineprice/" target="_blank" title="Интерактивная форма">Интерактивная форма</a></td>
      <td></td>
    </tr>
  </table>
</div>
<div id="pri">@productPageDis@</div>
