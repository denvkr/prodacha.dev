<?php

function userorderdoclink_mod_supplierdate_hook($obj, $value) {

    if (is_array($value['row'])) {

        // �������� ��������
        $dis = $obj->caption(__('���������������'), __('����'), __('��������'));
        $n = $value['val']['id'];
        foreach ($value['row'] as $row) {

            // �����
            if ($obj->PHPShopSystem->ifValue('1c_load_accounts')) {
                $link_def = '../files/docsSave.php?orderId=' . $n . '&list=accounts&datas=' . $row['datas'];
                $link_html = '../files/docsSave.php?orderId=' . $n . '&list=accounts&tip=html&datas=' . $row['datas'];
                $link_doc = '../files/docsSave.php?orderId=' . $n . '&list=accounts&tip=doc&datas=' . $row['datas'];
                $link_xls = '../files/docsSave.php?orderId=' . $n . '&list=accounts&tip=xls&datas=' . $row['datas'];
                $link_pdf = '../files/docsSave.php?orderId=' . $n . '&list=accounts&tip=pdf&datas=' . $row['datas'];
				$link_def = $link_pdf;
                $dis.=$obj->tr(PHPShopText::a($link_def, __('���� �� ������'), false, false, false, '_blank', 'b'), PHPShopDate::dataV($row['datas']), PHPShopText::a($link_html, __('HTML'), __('������ Web'), false, false, '_blank', 'b') . ' ' .
                        PHPShopText::a($link_doc, __('DOC'), __('������ Word'), false, false, '_blank', 'b') . ' ' .
                        PHPShopText::a($link_xls, __('XLS'), __('������ Excel'), false, false, '_blank', 'b') . ' ' .
                        PHPShopText::a($link_pdf, __('PDF'), __('������ PDF'), false, false, '_blank', 'b')
                );
            }

            // �����-�������
            if (!empty($row['datas_f']) and $obj->PHPShopSystem->ifValue('1c_load_invoice')) {
                $link_def = '../files/docsSave.php?orderId=' . $n . '&list=invoice&datas=' . $row['datas'];
                $link_html = '../files/docsSave.php?orderId=' . $n . '&list=invoice&tip=html&datas=' . $row['datas'];
                $link_doc = '../files/docsSave.php?orderId=' . $n . '&list=invoice&tip=doc&datas=' . $row['datas'];
                $link_xls = '../files/docsSave.php?orderId=' . $n . '&list=invoice&tip=xls&datas=' . $row['datas'];
				$link_def = $link_pdf;
                $dis.=$obj->tr(PHPShopText::a($link_def, __('����-�������'), false, false, false, '_blank', 'b'), PHPShopDate::dataV($row['datas_f']), PHPShopText::a($link_html, __('HTML'), __('������ Web'), false, false, '_blank', 'b') . ' ' .
                        PHPShopText::a($link_doc, __('DOC'), __('������ Word'), false, false, '_blank', 'b') . ' ' .
                        PHPShopText::a($link_xls, __('XLS'), __('������ Excel'), false, false, '_blank', 'b'));
            }
        }

        return $dis;

    }
    
}

$addHandler = array
    (
    'userorderdoclink' => 'userorderdoclink_mod_supplierdate_hook'
);
?>