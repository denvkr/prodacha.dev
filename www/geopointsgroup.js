function initgeoobjects() {
		// Создаем геообъект
        myGeoObject1 = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [59.9073771,30.4030378]
            },
            // Свойства.
            properties: {
                // Контент метки.
                iconContent: 'ул. Хрустальная',
                hintContent: 'Адрес:Санкт-Петербург,ул. Хрустальная, д. 27, лит. Б. ‚Въезд со стороны ул. 2-ой Луч д. 8',
				// Чтобы балун и хинт открывались на метке, необходимо задать ей определенные свойства.
				//balloonContentHeader: "Адрес:",
				//balloonContentBody: "Санкт-Петербург,ул. Хрустальная, д. 27, лит. Б. ‚Въезд со стороны ул. 2-ой Луч д. 8",
				//balloonContentFooter: ""               
            }
        }, {
				// Опции.
				// Иконка метки будет растягиваться под размер ее содержимого.
				preset: 'islands#blackStretchyIcon',
				// Метку можно перемещать.
				draggable: false
        });
		
		// Создаем геообъект
        myGeoObject2 = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [59.8941675,30.456874]
            },
            // Свойства.
            properties: {
                // Контент метки.
				iconContent: 'ул.Крыленко',
				hintContent: 'Адрес:ул. Крыленко, д. 8. Въезд со стороны пр. Дальневосточный д. 15',
				// Чтобы балун и хинт открывались на метке, необходимо задать ей определенные свойства.
				//balloonContentHeader: "Адрес:",
				//balloonContentBody: "Санкт-Петербург,ул. Крыленко, д. 8. Въезд со стороны пр. Дальневосточный д. 15",
				//balloonContentFooter: ""               
            }
        }, {
				// Опции.
				// Иконка метки будет растягиваться под размер ее содержимого.
				preset: 'islands#blackStretchyIcon',
				// Метку можно перемещать.
				draggable: false
        });
		
		// Создаем геообъект
        myGeoObject3 = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [59.9346643,30.4560231]
            },
            // Свойства.
            properties: {
                // Контент метки.
				iconContent: 'ул. Латышских стрелков',
				hintContent: 'Адрес:ул. Латышских стрелков, д. 29, корп. 3, лит. Б',
				// Чтобы балун и хинт открывались на метке, необходимо задать ей определенные свойства.
				//balloonContentHeader: "Адрес:",
				//balloonContentBody: "Санкт-Петербург,ул. Латышских стрелков, д. 29, корп. 3, лит. Б",
				//balloonContentFooter: ""               
            }
        }, {
				// Опции.
				// Иконка метки будет растягиваться под размер ее содержимого.
				preset: 'islands#blackStretchyIcon',
				// Метку можно перемещать.
				draggable: false
        });
		
		// Создаем геообъект
        myGeoObject4 = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [59.929369,30.325702]
            },
            // Свойства.
            properties: {
                // Контент метки.
				iconContent: 'ул. Садовая',
				hintContent: 'Адрес:ул. Садовая, д. 28, корп. 5',
				// Чтобы балун и хинт открывались на метке, необходимо задать ей определенные свойства.
				//balloonContentHeader: "Адрес:",
				//balloonContentBody: "Санкт-Петербург,ул. Садовая, д. 28, корп. 5",
				//balloonContentFooter: ""               
            }
        }, {
				// Опции.
				// Иконка метки будет растягиваться под размер ее содержимого.
				preset: 'islands#blackStretchyIcon',
				// Метку можно перемещать.
				draggable: false
        });		
	return [myGeoObject1,myGeoObject2,myGeoObject3,myGeoObject4];
}