<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function get_icon_bottom_text($iconid,$region) {
//������ ���������� ��������
 $icons_text_array=array(
    'm_181'=>array('bottom_text'=>'������������� ����������','description'=>'������������� ���������� ��� ���������� �� ����� ��������� ���������. ��������, �������, ��������!<br /><br />'),
    'm_184'=>array('bottom_text'=>'�������� � ������','description'=>'��� �����������, �������� �� �������������'),
    'm_185'=>array('bottom_text'=>'����������� ��������','description'=>'��������� �� �������� � �������, � ������� �� ��������� �������.<br /><br />��������� ����������� � ������� 5 ������� ����, ������ ��� ������ � ������� 10 ������� ����.'),
    'm_186'=>array('bottom_text'=>'��������� � ���� ������','description'=>'��������� � ���� ������'),
    'm_302'=>array('bottom_text'=>'������� ��� �������!','description'=>''),
    'other_181'=>array('bottom_text'=>'������������� ����������','description'=>'������������� ���������� ��� ���������� �� ����� ��������� ���������. ��������, �������, ��������!<br /><br />'),
    'other_184'=>array('bottom_text'=>'�������� � ������','description'=>'��� �����������, �������� �� �������������'),
    'other_185'=>array('bottom_text'=>'����������� ��������','description'=>'��������� �� �������� � �������, � ������� �� ��������� �������.<br /><br />��������� ����������� � ������� 5 ������� ����, ������ ��� ������ � ������� 10 ������� ����.'),
    'other_186'=>array('bottom_text'=>'��������� � ���� ������','description'=>'��������� � ���� ������'),
    'other_302'=>array('bottom_text'=>'������� ��� �������!','description'=>''),
    'sp_181'=>array('bottom_text'=>'������������� ����������','description'=>'������������� ���������� ��� ���������� �� ����� ��������� ���������. ��������, �������, ��������!<br /><br />'),
    'sp_184'=>array('bottom_text'=>'�������� � ������','description'=>'��� �����������, �������� �� �������������'),
    'sp_185'=>array('bottom_text'=>'����������� ��������','description'=>'��������� �� �������� � �������, � ������� �� ��������� �������.<br /><br />��������� ����������� � ������� 5 ������� ����, ������ ��� ������ � ������� 10 ������� ����.'),
    'sp_186'=>array('bottom_text'=>'��������� � ���� ������','description'=>'��������� � ���� ������'),
    'sp_302'=>array('bottom_text'=>'������� ��� �������!','description'=>''),
    'chb_181'=>array('bottom_text'=>'������������� ����������','description'=>'������������� ���������� ��� ���������� �� ����� ��������� ���������. ��������, �������, ��������!<br /><br />'),
    'chb_184'=>array('bottom_text'=>'�������� � ������','description'=>'��� �����������, �������� �� �������������'),
    'chb_185'=>array('bottom_text'=>'����������� ��������','description'=>'��������� �� �������� � �������, � ������� �� ��������� �������.<br /><br />��������� ����������� � ������� 5 ������� ����, ������ ��� ������ � ������� 10 ������� ����.'),
    'chb_186'=>array('bottom_text'=>'��������� � ���� ������','description'=>'��������� � ���� ������'),
    'chb_302'=>array('bottom_text'=>'������� ��� �������!','description'=>''),
    'm_dostavka'=>array('bottom_text'=>'�������� ���������',  'description'=>'&nbsp;&nbsp;������ �� 10000 ���. ������������ ��������� � �������� ����&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="��� ������� ��������">��� ������� ��������</a>'),
    'other_dostavka'=>array('bottom_text'=>'�������� ���������',  'description'=>'&nbsp;&nbsp;������ �� 10000 ���. ������������ ��������� � �������� ����&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="��� ������� ��������">��� ������� ��������</a>'),
    'sp_dostavka'=>array('bottom_text'=>'�������� ���������',  'description'=>'&nbsp;&nbsp;������ �� 10000 ���. ������������ ��������� � �������� ����&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="��� ������� ��������">��� ������� ��������</a>'),
    'chb_dostavka'=>array('bottom_text'=>'�������� ���������',  'description'=>'&nbsp;&nbsp;������ �� 10000 ���. ������������ ��������� � �������� ����&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="��� ������� ��������">��� ������� ��������</a>')
    );
 
    foreach($icons_text_array as $key=>$val) {
        if ($key== $region.'_'.$iconid)
            //var_dump($val);
           return $val;
    }
}

function get_more_gift($siteurl,$prodid) {
$searchgift=false;
//������ ���������� ��������
 $gifts_array=array(
     364=>'<table><tr><td><span><ul><li><a href="http://'.$siteurl.'/shop/UID_8896.html" style="color: #588910;font: 12px/1.4 Arial,Helvetica,sans-serif;">���������� ������������ MTD SMART M 56</a></li><br/>'
                       . '<li><a href="http://'.$siteurl.'/shop/UID_8896.html" style="color: #588910;font: 12px/1.4 Arial,Helvetica,sans-serif;">���������� ������������� Parton PA550N21RH3 ������������</a></li><br/></ul></span><span style="color: #e7193f;font: 14px Arial,Helvetica,sans-serif;font-weight: bold;"><strike>0 ���.</strike></span><br><span style="font: 14px Arial,Helvetica,sans-serif;font-weight: bold;color:#6C4B46;">� �������!</span></td></tr></table>',
    );
 
    foreach($gifts_array as $key=>$val) {
        if ($key== $prodid){
            $searchgift=true;
           return $val;            
        }
    }
    return $searchgift;
}