    <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script src="placemark.js" type="text/javascript"></script>
    <style>
        html, body, #map {
            width: 100%; height: 100%; padding: 0; margin: 0;
        }
    </style> 
<script>
ymaps.ready(init);

function init () {
    var myMap = new ymaps.Map("map", {
            center: [59.93,30.34],
            zoom: 10
        }, {
            searchControlProvider: 'yandex#search'
        }),

    // ������� ��������� � ����� ��������� "�����".
        myGeoObject = new ymaps.GeoObject({
            // �������� ���������.
            geometry: {
                type: "Point",
                coordinates: [59.93,30.4]
            },
            // ��������.
            properties: {
                // ������� �����.
                iconContent: '��.�����������',
                hintContent: '�����:�����-���������, ����������� ��., �. 27, ���. �. ����� �� ������� ��. 2-�� ��� �. 8',
            // ����� ����� � ���� ����������� �� �����, ���������� ������ �� ������������ ��������.
            balloonContentHeader: "�����",
            balloonContentBody: "�����-���������, ����������� ��., �. 27, ���. �. ����� �� ������� ��. 2-�� ��� �. 8",
            balloonContentFooter: "������",
            hintContent: "���� �����"                
            }
        }, {
            // �����.
            // ������ ����� ����� ������������� ��� ������ �� �����������.
            preset: 'islands#blackStretchyIcon',
            // ����� ����� ����������.
            draggable: true
        });

    myMap.geoObjects
        .add(myGeoObject)
        .add(new ymaps.Placemark([59.907912,30.398692], {
            
            balloonContent: '���� <strong>���� ����� �����</strong>',
            hintContent: '�����:�����-���������, ����������� ��., �. 27, ���. �. ����� �� ������� ��. 2-�� ��� �. 8'
        }, {
            preset: 'islands#icon',
            iconColor: '#0095b6'
            }));
}
</script>    
<div class="page_nava">
  <div> @breadCrumbs@ </div>
</div>
	<div class="pagetitle">
					<h1>@pageTitle@</h1>
				</div>

<p>@catContent@</p>
<div style="padding:5px 0px">@pageContent@    <div id="map"></div></div>
