<span>Вы можете забрать @php
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/seotools/seotools.class.php');
	$ST = new Seotools; 

	// Вывод метки, где 
	// $variable = название переменной (столбца). 
	// $default_value = значение по умолчанию 

	$oldh1="@productName@";

	echo $ST->get("h1", $oldh1);

	php@ из пункта самовывоза. Для осуществления самовывоза необходимо оплатить заказ. Заказ в пункте самовывоза будет доступен через 2-4 рабочих дня после оплаты. Подробные условия доставки заказа в пункт самовывоза доступны <a href="http://prodacha.ru/page/shop_spb.html" style="color:#588910;"><u>по ссылке</u></a>.
<br /><br />
<span style="font-size: 16px; font-weight: bold;">Стоимость доставки до пункта выдачи</span><br /><br />
<ul>

<li>Минимальная сумма заказа - 1000 руб. При сумме заказа от 1000 до 4999 руб. - 300 руб.</li>
<li>При сумме заказа от 5000 руб. - <span style="color: red; font-weight: bold;">бесплатно</span></li>
</ul><br /><br />
Адреса пунктов выдачи в Санкт-Петербурге представлены на карте:</span> <br /><br />

<script async type="text/javascript">
ymaps.ready(init);
function init() {
var myGeoObjects;
var myMap = new ymaps.Map("map", {
center: [59.965309,30.29271],
zoom: 10,
controls: ["zoomControl","rulerControl","routeEditor","searchControl","geolocationControl"]
});
myGeoObjects=initgeoobjects();
for (gobj_cnt=0;gobj_cnt<myGeoObjects.length;gobj_cnt++){
myMap.geoObjects.add(myGeoObjects[gobj_cnt]);
$('<option value="'+myGeoObjects[gobj_cnt].properties.get("hintContent")+'">'+myGeoObjects[gobj_cnt].properties.get("iconContent")+'</option>').appendTo('#tk_delivery_points_list');
$('#tk_geopoints_list').append('<li>'+myGeoObjects[gobj_cnt].properties.get("hintContent").replace('<br>','')+'</li>');
myGeoObjects[gobj_cnt].events.add('mouseup', function (e) {
var eMap = e.get('target');           
$("#tk_delivery_points_list").val(eMap.properties.get("hintContent")).prop("selected",true);
eMap.options.set("preset","islands#greenStretchyIcon");
});              
};
myMap.geoObjects.events.add("mousedown", function () {
this.each(function (geoObject, i) {
if (geoObject.options.get("preset") == "islands#greenStretchyIcon") {
geoObject.options.set("preset","islands#blackStretchyIcon");
}
})}, myMap.geoObjects);
$("#tk_delivery_points_list").bind("change", function(){              
var tk_office_adress=$(this).find("option:selected").text();
myMap.geoObjects.each(function (geoObject) {
if (geoObject.properties.get("iconContent")!==tk_office_adress){
geoObject.options.set("preset","islands#blackStretchyIcon");                
} else {
geoObject.options.set("preset", "islands#greenStretchyIcon");                
}
});
});
$("#tk_delivery_points_list").change();
}

</script>
<table>
<tr>
<td>
<div id="spb_map_area" style="position:relative;top:-13px;"><div style="height:18px;display:block;"></div><div id="map" style="width:530px; height:555px;"></div></div>
</td>
<td style="vertical-align: top;">
<ul id="tk_geopoints_list" style="padding-left: 0px;">
</ul>
</td>
</tr>
</table>
<br /><br />

<span style="font-size: 16px; font-weight: bold;">Доставка заказов из пункта выдачи по Санкт-Петербургу и Ленинградской области</span><br /><br />
<ul>
<li>Транспортная компания Vozovoz может организовать доставку вашего заказа по необходимому адресу. </li>
<li>Стоимость доставки зависит от габаритов груза и в среднем составляет 300-500 руб. (в пределах КАД). Точную стоимость можно рассчитать <a href="http://vozovoz.ru/calculate-the-order" target="_blank" style="color: #588910; text-decoration: underline;">по ссылке.</a> Габариты груза уточняйте в описании товара на сайте, либо по телефону у наших менеджеров. </li>
<li>Внимание! При заказе доставки груза "до адреса" водитель подъедет максимально близко к месту разгрузки, но выгрузку груза из машины Получатель осуществляет самостоятельно. Груз будет расположен на краю кузова машины для обеспечения удобства разгрузки. Если вам требуется разгрузка или подъем груза до офиса или квартиры, необходимо заказать услугу "Разгрузочные работы".</li>
</ul>
