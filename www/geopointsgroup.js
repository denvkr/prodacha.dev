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
				iconContent: 'ул. Крыленко',
				hintContent: 'Адрес:Санкт-Петербург,ул. Крыленко, д. 8. Въезд со стороны пр. Дальневосточный д. 15',              
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
				hintContent: 'Адрес:Санкт-Петербург,ул. Латышских стрелков, д. 29, корп. 3, лит. Б',              
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
				hintContent: 'Адрес:Санкт-Петербург,ул. Садовая, д. 28, корп. 5',              
            }
        }, {
				// Опции.
				// Иконка метки будет растягиваться под размер ее содержимого.
				preset: 'islands#blackStretchyIcon',
				// Метку можно перемещать.
				draggable: false
        });
		// Создаем геообъект
        myGeoObject5 = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [60.053735,30.369659]
            },
            // Свойства.
            properties: {
                // Контент метки.
				iconContent: 'ул. Домостроительная',
				hintContent: 'Адрес:Санкт-Петербург,ул. Домостроительная, д. 3, лит. В',              
            }
        }, {
				// Опции.
				// Иконка метки будет растягиваться под размер ее содержимого.
				preset: 'islands#blackStretchyIcon',
				// Метку можно перемещать.
				draggable: false
        });
		// Создаем геообъект
        myGeoObject6 = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [59.846567,30.437626]
            },
            // Свойства.
            properties: {
                // Контент метки.
				iconContent: 'пр. Девятого января',
				hintContent: 'Адрес:Санкт-Петербург, пр. Девятого января, д. 3, корп. 1',              
            }
        }, {
				// Опции.
				// Иконка метки будет растягиваться под размер ее содержимого.
				preset: 'islands#blackStretchyIcon',
				// Метку можно перемещать.
				draggable: false
        });
		// Создаем геообъект
        myGeoObject7 = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [59.970186,30.316245]
            },
            // Свойства.
            properties: {
                // Контент метки.
				iconContent: 'наб. р. Карповки',
				hintContent: 'Адрес:Санкт-Петербург, наб. р. Карповки, д. 5, лит. М',              
            }
        }, {
				// Опции.
				// Иконка метки будет растягиваться под размер ее содержимого.
				preset: 'islands#blackStretchyIcon',
				// Метку можно перемещать.
				draggable: false
        });
		// Создаем геообъект
        myGeoObject8 = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [59.840890,30.297974]
            },
            // Свойства.
            properties: {
                // Контент метки.
				iconContent: 'ул. Кубинская 75',
				hintContent: 'Адрес:Санкт-Петербург,ул. Кубинская, д. 75, стр. 2, лит. А',              
            }
        }, {
				// Опции.
				// Иконка метки будет растягиваться под размер ее содержимого.
				preset: 'islands#blackStretchyIcon',
				// Метку можно перемещать.
				draggable: false
        });
		// Создаем геообъект
        myGeoObject9 = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [59.842165,30.297929]
            },
            // Свойства.
            properties: {
                // Контент метки.
				iconContent: 'ул. Кубинская 80',
				hintContent: 'Адрес:Санкт-Петербург,ул. Кубинская, д.80, лит. К',              
            }
        }, {
				// Опции.
				// Иконка метки будет растягиваться под размер ее содержимого.
				preset: 'islands#blackStretchyIcon',
				// Метку можно перемещать.
				draggable: false
        });
		// Создаем геообъект
        myGeoObject10 = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [59.990305,30.248737]
            },
            // Свойства.
            properties: {
                // Контент метки.
				iconContent: 'ул. Мебельная',
				hintContent: 'Адрес:Санкт-Петербург,ул. Мебельная, д. 2, лит. В',              
            }
        }, {
				// Опции.
				// Иконка метки будет растягиваться под размер ее содержимого.
				preset: 'islands#blackStretchyIcon',
				// Метку можно перемещать.
				draggable: false
        });
		// Создаем геообъект
        myGeoObject11 = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [59.821037,30.356643]
            },
            // Свойства.
            properties: {
                // Контент метки.
				iconContent: 'Московское шоссе, д.25',
				hintContent: 'Адрес:Санкт-Петербург, Московское шоссе, д.25, корп. 1, лит. Ж',              
            }
        }, {
				// Опции.
				// Иконка метки будет растягиваться под размер ее содержимого.
				preset: 'islands#blackStretchyIcon',
				// Метку можно перемещать.
				draggable: false
        });
		// Создаем геообъект
        myGeoObject12 = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [59.992060,30.364548]
            },
            // Свойства.
            properties: {
                // Контент метки.
				iconContent: 'ул. Политехническая',
				hintContent: 'Адрес:Санкт-Петербург,ул. Политехническая, д. 7, корп. 5, лит. Л',              
            }
        }, {
				// Опции.
				// Иконка метки будет растягиваться под размер ее содержимого.
				preset: 'islands#blackStretchyIcon',
				// Метку можно перемещать.
				draggable: false
        });
	return [myGeoObject1,myGeoObject2,
            myGeoObject3,myGeoObject4,
            myGeoObject5,myGeoObject6,
            myGeoObject7,myGeoObject8,
            myGeoObject9,myGeoObject10,
            myGeoObject11,myGeoObject12];
}