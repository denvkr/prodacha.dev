function initgeoobjects() {
		// ������� ���������
        myGeoObject1 = new ymaps.GeoObject({
            // �������� ���������.
            geometry: {
                type: "Point",
                coordinates: [59.9073771,30.4030378]
            },
            // ��������.
            properties: {
                // ������� �����.
                iconContent: '��. �����������',
                hintContent: '�����:�����-���������,��. �����������, �. 27, ���. �. ������ �� ������� ��. 2-�� ��� �. 8',
				// ����� ����� � ���� ����������� �� �����, ���������� ������ �� ������������ ��������.
				//balloonContentHeader: "�����:",
				//balloonContentBody: "�����-���������,��. �����������, �. 27, ���. �. ������ �� ������� ��. 2-�� ��� �. 8",
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
        myGeoObject2 = new ymaps.GeoObject({
            // �������� ���������.
            geometry: {
                type: "Point",
                coordinates: [59.8941675,30.456874]
            },
            // ��������.
            properties: {
                // ������� �����.
				iconContent: '��. ��������',
				hintContent: '�����:�����-���������,��. ��������, �. 8. ����� �� ������� ��. ��������������� �. 15',              
            }
        }, {
				// �����.
				// ������ ����� ����� ������������� ��� ������ �� �����������.
				preset: 'islands#blackStretchyIcon',
				// ����� ����� ����������.
				draggable: false
        });
		
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
				hintContent: '�����:�����-���������,��. ��������� ��������, �. 29, ����. 3, ���. �',              
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
                coordinates: [59.929369,30.325702]
            },
            // ��������.
            properties: {
                // ������� �����.
				iconContent: '��. �������',
				hintContent: '�����:�����-���������,��. �������, �. 28, ����. 5',              
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
				hintContent: '�����:�����-���������,��. ����������������, �. 3, ���. �',              
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
				hintContent: '�����:�����-���������, ��. �������� ������, �. 3, ����. 1',              
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
				hintContent: '�����:�����-���������, ���. �. ��������, �. 5, ���. �',              
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
				hintContent: '�����:�����-���������,��. ���������, �. 75, ���. 2, ���. �',              
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
				hintContent: '�����:�����-���������,��. ���������, �.80, ���. �',              
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
				hintContent: '�����:�����-���������,��. ���������, �. 2, ���. �',              
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
				iconContent: '���������� �����, �.25',
				hintContent: '�����:�����-���������, ���������� �����, �.25, ����. 1, ���. �',              
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
				hintContent: '�����:�����-���������,��. ���������������, �. 7, ����. 5, ���. �',              
            }
        }, {
				// �����.
				// ������ ����� ����� ������������� ��� ������ �� �����������.
				preset: 'islands#blackStretchyIcon',
				// ����� ����� ����������.
				draggable: false
        });
	return [myGeoObject1,myGeoObject2,
            myGeoObject3,myGeoObject4,
            myGeoObject5,myGeoObject6,
            myGeoObject7,myGeoObject8,
            myGeoObject9,myGeoObject10,
            myGeoObject11,myGeoObject12];
}