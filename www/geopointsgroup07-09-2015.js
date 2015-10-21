function initgeoobjects() {
		// ������� ���������
        myGeoObject3 = new ymaps.GeoObject({
            // �������� ���������.
            geometry: {
                type: "Point",
                coordinates: [59.9346643,30.4560231]
            },
            // ��������.
            properties: {
                // ������� �����.
				iconContent: '��. ��������� ��������',
				hintContent: '��. ��������� ��������, �. 29, ����. 3, ���. �.<br>�� � ��: � 9:00 �� 21:00. ��: � 10:00 �� 19:00',              
				// ����� ����� � ���� ����������� �� �����, ���������� ������ �� ������������ ��������.
				//balloonContentHeader: "�����:",
				//balloonContentBody: "��. ��������� ��������, �. 29, ����. 3, ���. �",
				//balloonContentFooter: ""               
            }
        }, {
				// �����.
				// ������ ����� ����� ������������� ��� ������ �� �����������.
				preset: 'islands#blackStretchyIcon',
				// ����� ����� ����������.
				draggable: false
        });
		
		// ������� ���������
        myGeoObject4 = new ymaps.GeoObject({
            // �������� ���������.
            geometry: {
                type: "Point",
                coordinates: [59.907844,30.403256]
            },
            // ��������.
            properties: {
                // ������� �����.
				iconContent: '��. �����������',
				hintContent: '��. �����������, �. 27, ���. �. ����� �� ������� ��. 2-�� ��� �. 8.<br>�� � ��: � 9:00 �� 19:00.',              
            }
        }, {
				// �����.
				// ������ ����� ����� ������������� ��� ������ �� �����������.
				preset: 'islands#blackStretchyIcon',
				// ����� ����� ����������.
				draggable: false
        });
		// ������� ���������
        myGeoObject5 = new ymaps.GeoObject({
            // �������� ���������.
            geometry: {
                type: "Point",
                coordinates: [60.053735,30.369659]
            },
            // ��������.
            properties: {
                // ������� �����.
				iconContent: '��. ����������������',
				hintContent: '��. ����������������, �. 3, ���. �.<br>�� � ��: � 9:00 �� 19:00',              
            }
        }, {
				// �����.
				// ������ ����� ����� ������������� ��� ������ �� �����������.
				preset: 'islands#blackStretchyIcon',
				// ����� ����� ����������.
				draggable: false
        });
		// ������� ���������
        myGeoObject6 = new ymaps.GeoObject({
            // �������� ���������.
            geometry: {
                type: "Point",
                coordinates: [59.846567,30.437626]
            },
            // ��������.
            properties: {
                // ������� �����.
				iconContent: '��. �������� ������',
				hintContent: '��. �������� ������, �. 3, ����. 1.<br>�� � ��: � 9:00 �� 19:00',              
            }
        }, {
				// �����.
				// ������ ����� ����� ������������� ��� ������ �� �����������.
				preset: 'islands#blackStretchyIcon',
				// ����� ����� ����������.
				draggable: false
        });
		// ������� ���������
        myGeoObject7 = new ymaps.GeoObject({
            // �������� ���������.
            geometry: {
                type: "Point",
                coordinates: [59.970186,30.316245]
            },
            // ��������.
            properties: {
                // ������� �����.
				iconContent: '���. �. ��������',
				hintContent: '���. �. ��������, �. 5, ���. �.<br>����� �� ������� ��. ���������� ������ �. 4.<br>����� �� ���������� ������� - 100 ���/���.<br>�� � ��: � 9:00 �� 19:00.',              
            }
        }, {
				// �����.
				// ������ ����� ����� ������������� ��� ������ �� �����������.
				preset: 'islands#blackStretchyIcon',
				// ����� ����� ����������.
				draggable: false
        });
		// ������� ���������
        myGeoObject8 = new ymaps.GeoObject({
            // �������� ���������.
            geometry: {
                type: "Point",
                coordinates: [59.840890,30.297974]
            },
            // ��������.
            properties: {
                // ������� �����.
				iconContent: '��. ��������� 75',
				hintContent: '��. ���������, �. 75, ���. 2, ���. �.<br>�� � ��: � 9:00 �� 19:00',              
            }
        }, {
				// �����.
				// ������ ����� ����� ������������� ��� ������ �� �����������.
				preset: 'islands#blackStretchyIcon',
				// ����� ����� ����������.
				draggable: false
        });
		// ������� ���������
        myGeoObject9 = new ymaps.GeoObject({
            // �������� ���������.
            geometry: {
                type: "Point",
                coordinates: [59.842165,30.297929]
            },
            // ��������.
            properties: {
                // ������� �����.
				iconContent: '��. ��������� 80',
				hintContent: '��. ���������, �.80, ���. �.<br>�� � ��: � 9:00 �� 19:00. ��: � 10:00 �� 19:00',              
            }
        }, {
				// �����.
				// ������ ����� ����� ������������� ��� ������ �� �����������.
				preset: 'islands#blackStretchyIcon',
				// ����� ����� ����������.
				draggable: false
        });
		// ������� ���������
        myGeoObject10 = new ymaps.GeoObject({
            // �������� ���������.
            geometry: {
                type: "Point",
                coordinates: [59.990305,30.248737]
            },
            // ��������.
            properties: {
                // ������� �����.
				iconContent: '��. ���������',
				hintContent: '��. ���������, �. 2, ���. �.<br>�� � ��: � 9:00 �� 19:00. ��: � 10:00 �� 19:00',              
            }
        }, {
				// �����.
				// ������ ����� ����� ������������� ��� ������ �� �����������.
				preset: 'islands#blackStretchyIcon',
				// ����� ����� ����������.
				draggable: false
        });
		// ������� ���������
        myGeoObject11 = new ymaps.GeoObject({
            // �������� ���������.
            geometry: {
                type: "Point",
                coordinates: [59.821037,30.356643]
            },
            // ��������.
            properties: {
                // ������� �����.
				iconContent: '���������� �����',
				hintContent: '���������� �����, �.25, ����. 1, ���. �.<br>�� � ��: � 9:00 �� 19:00',              
            }
        }, {
				// �����.
				// ������ ����� ����� ������������� ��� ������ �� �����������.
				preset: 'islands#blackStretchyIcon',
				// ����� ����� ����������.
				draggable: false
        });
		// ������� ���������
        myGeoObject12 = new ymaps.GeoObject({
            // �������� ���������.
            geometry: {
                type: "Point",
                coordinates: [59.992060,30.364548]
            },
            // ��������.
            properties: {
                // ������� �����.
				iconContent: '��. ���������������',
				hintContent: '��. ���������������, �. 7, ����. 5, ���. �.<br>�� � ��: � 9:00 �� 19:00',              
            }
        }, {
				// �����.
				// ������ ����� ����� ������������� ��� ������ �� �����������.
				preset: 'islands#blackStretchyIcon',
				// ����� ����� ����������.
				draggable: false
        });
	return [myGeoObject3,myGeoObject4,
            myGeoObject5,myGeoObject6,
            myGeoObject7,myGeoObject8,
            myGeoObject9,myGeoObject10,
            myGeoObject11,myGeoObject12];
}