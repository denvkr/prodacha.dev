	var VVC_SETTINGS = {
	        // shop_id - ����� �����. ������������� �������� � ������� "�� � ������".
	        shop_id : @php
	        		$SysValue = parse_ini_file('phpshop/inc/config.ini', 1);
					//������� id
					echo $SysValue['lang']['vsevkredit_id'];
	        php@,
	
	        // ����� ������ �� ������� ��������; ����� ���� �������.
	        order_id : "@orderNum@",
	
	        // ������� � ��� ������� (���� �������������).
	        // ������ ����� ��������� � �������: ������� ���, � �� ��������.
	        name: "@UserName@",
	
	        // ����� �������� �������� �������  (���� �������������).
	        // ������� ��������� ��� 8 ��� +7, (����������� ������ ������ ������� � ���� ������ �+7�).
	        // ������� ����������� � ������� �1231234567�.
	        phone:"@UserTel@",
	
	        // css - ���������� ������
	        css: "green",
			// ���������� ������� ������� (���� �������������).
	        onStateChange :
	            function (state) {
	                switch(state) {
	                    case 'opened':
	                        function v_opened(){
	                        	console.log('opened');
	                        }
	                    break;
	                    case 'order':
	                        function v_opened(){
	                        	console.log('order');
	                        }
	                    break;
	                    case 'closed':
	                        function v_opened(){
	                        	console.log('closed');
	                        }
	                    break;
	                }
	            },
	        // �� ������� �� ������ �������� �������������� ������ ������ ������� ��� ��������
	        // (�������� �� ���� ��������� ���������, ��� ������ ������� ���������� �� ����� �����).
	        // (���� �������������.)
	        final_link: {
	            // �������� ������
	            text:'������ �������',
	            // ������ ��� ��������
	            uri:'http://prodacha.ru/'
	        }
	}