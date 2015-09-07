function initgeoobjects() {
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
				hintContent: 'ул. Латышских стрелков, д. 29, корп. 3, лит. Б.<br>Пн — Пт: с 9:00 до 21:00. Сб: с 10:00 до 19:00',              
				// Чтобы балун и хинт открывались на метке, необходимо задать ей определенные свойства.
				//balloonContentHeader: "Адрес:",
				//balloonContentBody: "ул. Латышских стрелков, д. 29, корп. 3, лит. Б",
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
                coordinates: [59.907844,30.403256]
            },
            // Свойства.
            properties: {
                // Контент метки.
				iconContent: 'ул. Хрустальная',
				hintContent: 'ул. Хрустальная, д. 27, лит. А. Въезд со стороны ул. 2-ой Луч д. 8.<br>Пн — Пт: с 9:00 до 19:00.',              
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
				hintContent: 'ул. Домостроительная, д. 3, лит. В.<br>Пн — Пт: с 9:00 до 19:00',              
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
				hintContent: 'пр. Девятого января, д. 3, корп. 1.<br>Пн — Пт: с 9:00 до 19:00',              
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
				hintContent: 'наб. р. Карповки, д. 5, лит. М.<br>Въезд со стороны ул. Профессора Попова д. 4.<br>Въезд на территорию платный - 100 руб/час.<br>Пн — Пт: с 9:00 до 19:00.',              
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
				hintContent: 'ул. Кубинская, д. 75, стр. 2, лит. А.<br>Пн — Пт: с 9:00 до 19:00',              
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
				hintContent: 'ул. Кубинская, д.80, лит. К.<br>Пн — Пт: с 9:00 до 19:00. Сб: с 10:00 до 19:00',              
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
				hintContent: 'ул. Мебельная, д. 2, лит. В.<br>Пн — Пт: с 9:00 до 19:00. Сб: с 10:00 до 19:00',              
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
				iconContent: 'Московское шоссе',
				hintContent: 'Московское шоссе, д.25, корп. 1, лит. Ж.<br>Пн — Пт: с 9:00 до 19:00',              
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
				hintContent: 'ул. Политехническая, д. 7, корп. 5, лит. Л.<br>Пн — Пт: с 9:00 до 19:00',              
            }
        }, {
				// Опции.
				// Иконка метки будет растягиваться под размер ее содержимого.
				preset: 'islands#blackStretchyIcon',
				// Метку можно перемещать.
				draggable: false
        });
	return [myGeoObject3,myGeoObject4,
            myGeoObject5,myGeoObject6,
            myGeoObject7,myGeoObject8,
            myGeoObject9,myGeoObject10,
            myGeoObject11,myGeoObject12];
}