<html>
<head>
    <script src="https://api-maps.yandex.ru/2.1-dev/?lang=ru_RU&load=package.full&mode=debug&onerror=mymaperror(err)" type="text/javascript"></script>
</head>
<body>
<script type="text/javascript">
    var myMap = new ymaps.Map("map", {
            center: [59.93,30.34],
            zoom: 10
        }),

    // Создаем геообъект с типом геометрии "Точка".
        myGeoObject = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [59.93,30.4]
            },
            // Свойства.
            properties: {
                // Контент метки.
                iconContent: 'ул.Хрустальная',
                hintContent: 'Адрес:Санкт-Петербург, Хрустальная ул., д. 27, лит. А. Въезд со стороны ул. 2-ой Луч д. 8',
            // Чтобы балун и хинт открывались на метке, необходимо задать ей определенные свойства.
            balloonContentHeader: "Адрес",
            balloonContentBody: "Санкт-Петербург, Хрустальная ул., д. 27, лит. А. Въезд со стороны ул. 2-ой Луч д. 8",
            balloonContentFooter: "Подвал",
            hintContent: "Хинт метки"                
            }
        }, {
            // Опции.
            // Иконка метки будет растягиваться под размер ее содержимого.
            preset: 'islands#blackStretchyIcon',
            // Метку можно перемещать.
            draggable: true
        });

    myMap.geoObjects
        .add(myGeoObject)
        .add(new ymaps.Placemark([59.907912,30.398692], {
            
            balloonContent: 'цвет <strong>воды пляжа бонди</strong>',
            hintContent: 'Адрес:Санкт-Петербург, Хрустальная ул., д. 27, лит. А. Въезд со стороны ул. 2-ой Луч д. 8'
        }, {
            preset: 'islands#icon',
            iconColor: '#0095b6'
            }));
}
</script>
<p>Карта Санкт-Петербург</p>
    <div id="map" style="width:400px; height:300px"></div>
</body>
</html>