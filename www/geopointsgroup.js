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
				iconContent: '��.��������',
				hintContent: '�����:��. ��������, �. 8. ����� �� ������� ��. ��������������� �. 15',
				// ����� ����� � ���� ����������� �� �����, ���������� ������ �� ������������ ��������.
				//balloonContentHeader: "�����:",
				//balloonContentBody: "�����-���������,��. ��������, �. 8. ����� �� ������� ��. ��������������� �. 15",
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
				hintContent: '�����:��. ��������� ��������, �. 29, ����. 3, ���. �',
				// ����� ����� � ���� ����������� �� �����, ���������� ������ �� ������������ ��������.
				//balloonContentHeader: "�����:",
				//balloonContentBody: "�����-���������,��. ��������� ��������, �. 29, ����. 3, ���. �",
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
                coordinates: [59.929369,30.325702]
            },
            // ��������.
            properties: {
                // ������� �����.
				iconContent: '��. �������',
				hintContent: '�����:��. �������, �. 28, ����. 5',
				// ����� ����� � ���� ����������� �� �����, ���������� ������ �� ������������ ��������.
				//balloonContentHeader: "�����:",
				//balloonContentBody: "�����-���������,��. �������, �. 28, ����. 5",
				//balloonContentFooter: ""               
            }
        }, {
				// �����.
				// ������ ����� ����� ������������� ��� ������ �� �����������.
				preset: 'islands#blackStretchyIcon',
				// ����� ����� ����������.
				draggable: false
        });		
	return [myGeoObject1,myGeoObject2,myGeoObject3,myGeoObject4];
}