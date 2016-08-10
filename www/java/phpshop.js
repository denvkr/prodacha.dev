/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * ��������� JavaScript �������
 * @package PHPShopJavaScript
 * @author PHPShop Software,Denis Krasavin
 * @version 2.1
 */

var ROOT_PATH="";

// ������������ ���� �������������� ���������
function JtopMenuOn(id){
    document.getElementById("menu_"+id).style.display='block';
    var pattern=/menu/;

    for(wi=0;wi<document.all.length;wi++)
        if(pattern.test(document.all[wi].id)==false) a=1;
        else if(document.all[wi].id != "menu_"+id) document.all[wi].style.display='none';

    setTimeout("JtopMenuOff("+id+")",10000);
}
function JtopMenuOff(id){
    document.getElementById("menu_"+id).style.display='none';
}


// ����� �������� � ������
function proSearch(category) {
    var req = new Subsys_JsHttpRequest_Js();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                document.getElementById('sort').innerHTML = (req.responseJS.sort||'');
            }
        }
    }
    req.caching = false;
    // �������������� ������.
    // �������� ����������
    var dir=dirPath();
    req.open('POST', dir+'/phpshop/ajax/search.php', true);
    req.send({
        category: category
    });
}


// ���������� ���������
function calres(year,month) {
    var req = new Subsys_JsHttpRequest_Js();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                document.getElementById('calres').innerHTML = (req.responseJS.calres||'');
            }
        }
    }
    req.caching = false;
    // �������������� ������.
    // �������� ����������
    var dir=dirPath();
    req.open('POST', dir+'/phpshop/ajax/calres.php', true);
    req.send({
        year: year,
        month: month
    });
}

	
// �������� ����� �����
function CheckOpenMessage(){
    var tema = document.getElementById("tema").value;
    var name = document.getElementById("name").value;
    var content = document.getElementById("content").value;
    if(tema=="" || name=="" || content=="") alert("������ ��������� ����� ���������!\n������, ���������� �������� ����������� ��� ����������.");
    else document.forma_message.submit();
}


// �������� ����� ������������ �� ����
function CheckPricemail(){
    var mail = document.getElementById("mail").value;
    var name = document.getElementById("name").value;
    var links = document.getElementById("links").value;
    var key = document.getElementById("key").value;
    if(mail=="" || name=="" || links=="" || key=="") alert("������ ���������� ����� ���������!\n������, ���������� �������� ����������� ��� ����������.");
    else forma_pricemail.submit();
}

function LoadPath(my_path){
    ROOT_PATH = my_path;
}

function dirPath(){
    return ROOT_PATH;
}

// �������� ������
function ButOn(Id){
    Id.className='imgOn';
}

function ButOff(Id){
    Id.className='imgOff';
}

// �������� ��������
function CapReload(){
    var dd=new Date();
    document.getElementById("captcha").src="../phpshop/captcha.php?time="+dd.getTime();
}

// ��������
function emoticon(text) {
    var txtarea = document.getElementById("message");
    if (txtarea.createTextRange && txtarea.caretPos) {
        var caretPos = txtarea.caretPos;
        caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
        txtarea.focus();
    } else {
        txtarea.value  += text;
        txtarea.focus();
    }
}

// ������� ������ ��������
function countSymb(lim) {
    var lim = lim || 500;
    if (document.getElementById("message").value.length > lim) {
        alert("� ���������, �� ��������� ����������� ���������� ����� �����������");
        document.getElementById("message").value = document.getElementById("message").value.substring(0,lim);
        return false;
    }
    if (document.getElementById("message").value.length > (lim - 50)) {
        document.getElementById("count").style.color = "red";
    }
    if (document.getElementById("message").value.length < (lim - 50)) {
        document.getElementById("count").style.color = "green";
    }
    document.getElementById("count").innerHTML = document.getElementById("message").value.length;
}

// �����������
function commentList(xid,comand,page,cid) {
    var message="";

    if(comand == "add") {
        message = document.getElementById('message').value;
        alert ("����������� ����� �������� ����� ����������� ���������...");
    }

    if(comand == "edit_add"){
        message = document.getElementById('message').value;
        cid = document.getElementById('commentEditId').value;
        document.getElementById('commentButtonAdd').style.visibility = 'visible';
        document.getElementById('commentButtonEdit').style.visibility = 'hidden';
    }

    if(comand == "dell"){
        if(confirm("�� ������������� ������ ������� �����������?")){
            cid = document.getElementById('commentEditId').value;
            document.getElementById('commentButtonAdd').style.visibility = 'visible';
            document.getElementById('commentButtonEdit').style.visibility = 'hidden';
        }
        else cid=0;
    }

    var req = new Subsys_JsHttpRequest_Js();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
				
                if(comand == "edit"){
                    document.getElementById('message').value = (req.responseJS.comment||'');
                    document.getElementById('commentButtonAdd').style.visibility = 'hidden';
                    document.getElementById('commentButtonEdit').style.visibility = 'visible';
                    document.getElementById('commentButtonEdit').style.display = '';
                    document.getElementById('commentEditId').value=cid;
                }
                else
                {
                    document.getElementById('message').value = "";
                    if(req.responseJS.status == "error") alert("������� ���������� ����������� �������� ������ ��� �������������� �������������.\n������������� ��� �������� �����������.");
                    document.getElementById('commentList').innerHTML = (req.responseJS.comment||'');
                }
            }
        }
    }
    req.caching = false;
    // �������������� ������.
    // �������� ����������
    var dir=dirPath();
    req.open('POST', dir+'/phpshop/ajax/comment.php', true);
    req.send({
        xid: xid,
        comand: comand,
        page: page,
        message: message,
        cid: cid
    });
}

// �����������
function fotoload(xid,fid) {
    document.getElementById('fotoload').innerHTML = document.getElementById('fotoload').innerHTML;
    var req = new Subsys_JsHttpRequest_Js();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                document.getElementById('fotoload').innerHTML = (req.responseJS.foto||'');
            }
        }
    }
    req.caching = false;
    // �������������� ������.
    // �������� ����������
    var dir=dirPath();
    req.open('POST', dir+'/phpshop/ajax/fotoload.php', true);
    req.send({
        xid: xid,
        fid: fid
    });
}

var flag = false;

// ������� ��������
function UpdateDelivery(xid) {

    var req = new Subsys_JsHttpRequest_Js();

    var sum = document.getElementById('OrderSumma').value;
    var wsum = document.getElementById('WeightSumma').innerHTML;
    var adr_name_info=document.getElementsByName('adr_name')[0];
    var adr_name_img=getNextElement(document.getElementsByName('adr_name')[0]);
    var legal_form=document.getElementsByName('legal_form');
    var deliver_time_info=document.getElementsByName('delivery_time_info');
    var order_metod=document.getElementById('order_metod_div');
    var region='m';
    //console.log(document.getElementById('var_'+xid));
    //console.log(typeof(document.getElementById('var_'+xid)));
    //������������ ���� "����� � �������������� ����������:"

    //if (Number(document.getElementById('TotalSumma').innerHTML)<3000 && xid==1) {
            //console.log(document.getElementById('TotalSumma').innerHTML);//('var_'+xid);
            //return true;

    //}
    //����� �������� ������ �������� ���������� ������,�����,���������
    //console.log($('.city_link').eq(0).text());
    if (typeof(document.getElementById('var_'+xid))!=='undefined' && (Number(xid)===10 || Number(xid)===13 || Number(xid)===14 || Number(xid)===43 || Number(xid)===69 || Number(xid)===70 || Number(xid)===71)) {
            //�������� ������ �������� � ��������
            var delivery_options=document.getElementById('var_'+xid);
            //���� ������� ������������ �������� �� ������ ��������� � ������ �������� address_and_info � ������ �������������� ����
            if ( ((delivery_options!==null && delivery_options.selected===true) || delivery_options===null) && (Number(xid)===10 || Number(xid)===13 || Number(xid)===14 || Number(xid)===43 || Number(xid)===69) ) {
                document.getElementById('address_and_info').innerHTML='��������������<br>����������:';
                if (typeof(adr_name_img) !== 'undefined' && adr_name_img !== null) {
                        adr_name_img.parentNode.removeChild(adr_name_img);
                }
                //�������� ���� ������� ��������, ��� ���������� ��� �� �����
                deliver_time_info[0].style.display='none';
                deliver_time_info[1].style.display='none';
                //����� ���������� ��� �������
                if ( ((delivery_options!==null && delivery_options.selected===true) || delivery_options===null) && legal_form[0].checked===true && (Number(xid)===10 || Number(xid)===13) ) {
                    //������� ����� ������
                    if ($('#postal_index').length){
                        delete_order_elements(1);				
                    }
                    //������� ��
                    if ($('#pass_no_info').length){
                        delete_order_elements(2);				
                    }
                    //������ �������� ���� ����������� "�������������� ����������:"
                    document.getElementById('address_and_info').style.display="table-cell";
                    document.getElementById('adr_name').style.display="table-cell";
                    //� ����������� �� �������� � ���� ������������ ���������� ���� ������
                    if (($("td input[name='order_metod']:eq(0)").length && 
                        $("td input[name='order_metod']:eq(0)+:eq(0)").html()==='���� (���������) ��� ������ ����� ����') ||
                        ($("td input[name='order_metod']:eq(0)").length && 
                        $("td input[name='order_metod']:eq(0)+:eq(0)").html()==='���� �� ����������� (����������� ������)') ||
                        ($("td input[name='order_metod']:eq(0)").length && 
                        $("td input[name='order_metod']:eq(0)+:eq(0)").html()==='�������� ������' &&                        
                        $("td input[name='order_metod']:eq(1)").length && 
                        $("td input[name='order_metod']:eq(1)+:eq(0)").html()==='�������� ������' &&
                        !$("td input[name='order_metod']:eq(2)").length && 
                        $("td input[name='order_metod']:eq(2)+:eq(0)").html()!=='������ � �������� ������ VISA, Mastercard')) {
                        $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" checked value=\"3\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>�������� ������</label><br>"+
                            "<input type=\"radio\" @creditdisabled@  name=\"order_metod\" value=\"25\" onClick=\"document.getElementById('bic').style.display='block';document.getElementById('bin').style.display='none';\"><label>�������� ������</label><br>"+
                            "<div id=\"order_metod_div\" style=\"display:table-cell;\"><input type=\"radio\" name=\"order_metod\" value=\"26\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>������ � �������� ������ VISA, Mastercard</label><br>");//+ "<input type=\"radio\" name=\"order_metod\" value=\"15\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>������ ������.</label><br></div>"
                            //console.log('������ � �������� ������ VISA, Mastercard');
                    }
                    //���������� ������� "������ � �������� ������ VISA, Mastercard"
                    document.getElementById('order_metod_div').style.display='table-cell';
                //����� ���������� ��� ������
                } else if ( ((delivery_options!==null && delivery_options.selected===true) || delivery_options===null) && legal_form[1].checked===true && (Number(xid)===10 || Number(xid)===13) ) {
                    //������� ����� ������
                    if ($('#postal_index').length){
                        delete_order_elements(1);				
                    }
                    //������� ��
                    if ($('#pass_no_info').length){
                        delete_order_elements(2);				
                    }
                    //������ �������� ���� ����������� "�������������� ����������:"                    
                    document.getElementById('address_and_info').style.display="table-cell";
                    document.getElementById('adr_name').style.display="table-cell";
                    //���������� ���� ������
                    $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" checked value=\"14\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>���� �� ����������� (����������� ������)</label><br>");
                    //��� ������ ������ ���������
                    document.getElementsByName("order_metod")[0].checked=true;
                    //��������� ���� ��� ������ ������� ������ � ������
                    if (document.getElementById('bic')!==null && document.getElementById('bic').style.display==='block'){
                        document.getElementById('bic').style.display='none';
                        document.getElementById('bin').style.display='block';                        
                    }
                //��� ������ ������� ������ ������
                } else if ($('#order_metod_div').length){
                    document.getElementById('order_metod_div').style.display='none';
                }
                //��� �������� ������ ���������� ��� �������
                if (Number(xid)===43 && legal_form[0].checked===true){
                    $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" checked value=\"3\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>�������� ������</label><br>"+
                      "<div id=\"order_metod_div\" style=\"display:table-cell;\"><input type=\"radio\" name=\"order_metod\" value=\"26\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>������ � �������� ������ VISA, Mastercard</label><br></div>");
                    document.getElementsByName("order_metod")[0].checked=true;
                    //console.log('��������');
                }
                //��� �������� ������ ���������� ��� ������
                if (Number(xid)===43 && legal_form[1].checked===true){
                    $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" checked value=\"14\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>���� �� ����������� (����������� ������)</label><br>");
                    document.getElementsByName("order_metod")[0].checked=true;
                    //console.log('��������');
                }
                //��� ��� ������ ����������
                if (Number(xid)===69){
                    //������� ����� ������
                    if ($('#postal_index').length){
                        delete_order_elements(1);				
                    }
                    //������� ��
                    if ($('#pass_no_info').length){
                        delete_order_elements(2);				
                    }
                    //������ �������� ���� ����������� "�������������� ����������:"  
                    document.getElementById('address_and_info').style.display="table-cell";
                    document.getElementById('adr_name').style.display="table-cell";
                    //������ ��� �������
                    if (legal_form[0].checked===true){
                        $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" checked value=\"1\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>���� (���������) ��� ������ ����� ����</label><br>");
                    //������ ��� ������    
                    } else if (legal_form[1].checked===true) {
                        $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" checked value=\"14\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>���� �� ����������� (����������� ������)</label><br>");
                    }
                    document.getElementsByName("order_metod")[0].checked=true;
                    //console.log('���� (���������) ��� ������ ����� ����');
                }
                
            }

            //��� ������ ������ ��������� ��� �������� �� ������ � ������� ����� ��
            if ( ((delivery_options!==null && delivery_options.selected===true) || delivery_options===null) && (Number(xid)===70 || Number(xid)===71)) {
                
                //���� ���� ������ ������ �� ���������� ���
                if (document.getElementById('address_and_info')!==null){
                    document.getElementById('address_and_info').innerHTML='����� �������� �<br>��������������<br>����������:';
                    document.getElementById('address_and_info').style.display='table-cell';
                }
                //������� ���� ��� ����������
                if (document.getElementById('adr_name')!==null){
                    document.getElementById('adr_name').style.display='table-cell';
                }
                //������ �������� ���� ���������� � ����� ���� ������� �������� �� �������� ��
                if (deliver_time_info[0]!==null && deliver_time_info[0].style.display==='table-cell'){
                    deliver_time_info[0].style.display='none';
                    deliver_time_info[1].style.display='none';
                }
                //console.log(document.getElementById('order_metod_div'));
                if (document.getElementById('order_metod_div')!==null){
                    document.getElementById('order_metod_div').style.display='none';
                }

            }
            //������� ����� ������
            if ($('#postal_index').length){
                delete_order_elements(1);				
            }
            //������� ��
            if ($('#pass_no_info').length){
                delete_order_elements(2);				
            }
            //� ������ ���������� ���� �������� ���������� ��������������� ������
            if ($("td input[name='order_metod']:eq(2)").length && $("td input[name='order_metod']:eq(2)+:eq(0)").html()==='������ � �������� ������ VISA, Mastercard'){
                get_payments_elements(region,xid);
            }
            if ($("td input[name='order_metod']:eq(1)").length && $("td input[name='order_metod']:eq(1)+:eq(0)").html()==='�������� ������'){
                get_payments_elements(region,xid);
            }
            if ($("td input[name='order_metod']:eq(0)").length && $("td input[name='order_metod']:eq(0)+:eq(0)").html()==='�������� ������'){
                get_payments_elements(region,xid);
            }
            //���������� ����� ����� � �������� �������� � ���
            if (((delivery_options!==null && delivery_options.selected===true) || delivery_options===null) && (Number(xid)===69 || Number(xid)===70 || Number(xid)===71)) {
                
                if (legal_form[0].checked===true){
                    $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" checked value=\"1\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>���� (���������) ��� ������ ����� ����</label><br>");
                    $('#spb_map_delivery_office_info').css('top','-245px');
                    //������ ��� ������    
                } else if (legal_form[1].checked===true){
                    $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" checked value=\"14\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>���� �� ����������� (����������� ������)</label><br>");
                    $('#spb_map_delivery_office_info').css('top','-205px');
                }
                document.getElementsByName("order_metod")[0].checked=true;
                
                delete_order_payments_elements();

                if (!$('#spb_map_area').length){
                    if (Number(xid)===70 || Number(xid)===71){
                        $("form[name='forma_order']>table:eq(0) tr:eq(14) td:eq(0)").html('<div id="spb_map_delivery_office_info" style="position:relative;top:-245px;">�������� �����<br>������ �������,<br>�� �������� ����� ��������������<br>��������</div>');
                        $("form[name='forma_order']>table:eq(0) tr:eq(14) td:eq(0)").prop('align','right');
                        if (legal_form[1].checked===true)
                            $("form[name='forma_order']>table:eq(0) tr:eq(14) td:eq(1)").html('<div id="spb_map_area" style="position:relative;top:0px;"><p>�������� �� �����-���������� � ������������� ������� ������������ ������ ���������� ������. ������� ��� ����� ��������� � ���� �� ������� ���������� � �����-����������. �������� �� ������ �������� �� ��������� ����. ��������� �������� ������� �� ��������� ����� � � ������� ���������� 300-500 ���. (� �������� ���) - ��� ����� ������������ ��� ��������� ������. �������� ����� ��������� � �������� ������ �� �����, ���� �� �������� � ����� ����������.</p><select id="tk_delivery_points_list"></select><div style="height:18px;display:block;"></div><div id="map" style="width:630px; height:450px;"></div></div>');
                         else
                            $("form[name='forma_order']>table:eq(0) tr:eq(14) td:eq(1)").html('<div id="spb_map_area" style="position:relative;top:-40px;"><p>�������� �� �����-���������� � ������������� ������� ������������ ������ ���������� ������. ������� ��� ����� ��������� � ���� �� ������� ���������� � �����-����������. �������� �� ������ �������� �� ��������� ����. ��������� �������� ������� �� ��������� ����� � � ������� ���������� 300-500 ���. (� �������� ���) - ��� ����� ������������ ��� ��������� ������. �������� ����� ��������� � �������� ������ �� �����, ���� �� �������� � ����� ����������.</p><select id="tk_delivery_points_list"></select><div style="height:18px;display:block;"></div><div id="map" style="width:630px; height:450px;"></div></div>');
                    } else {
                        //console.log(1);
                        $("form[name='forma_order']>table:eq(0) tr:eq(14) td:eq(0)").html('<div id="spb_map_delivery_office_info" style="position:relative;top:-245px;">�������� �����<br>������ �������</div>');
                        $("form[name='forma_order']>table:eq(0) tr:eq(14) td:eq(0)").prop('align','right');
                        if (legal_form[1].checked===true)
                            $("form[name='forma_order']>table:eq(0) tr:eq(14) td:eq(1)").html('<div id="spb_map_area" style="position:relative;top:0px;"><select id="tk_delivery_points_list"></select><div style="height:18px;display:block;"></div><div id="map" style="width:630px; height:450px;"></div></div>');
                         else
                            $("form[name='forma_order']>table:eq(0) tr:eq(14) td:eq(1)").html('<div id="spb_map_area" style="position:relative;top:-40px;"><select id="tk_delivery_points_list"></select><div style="height:18px;display:block;"></div><div id="map" style="width:630px; height:450px;"></div></div>');
                    }
                    $('#order_metod_div').css('display','none');
                    var script   = document.createElement("script");
                    script.type  = "text/javascript";
                    script.text  = "ymaps.ready(init);";
                    script.text  += 'function init () {';
                    script.text  += 'var myGeoObjects;';
                    script.text  += 'var myMapcenter;';
                    script.text  += 'var myMapzoom;';
                    script.text  += 'var myMap = new ymaps.Map("map", {';
                    script.text  += 'center: [59.9281401,30.3626595],';
                    script.text  += 'zoom: 10,';
                    script.text  += 'controls: ["zoomControl","rulerControl","routeEditor","searchControl","geolocationControl"]';
                    script.text  += '});';
                    script.text  += 'myMapcenter=getmapcenter();';
                    script.text  += 'myMapzoom=getmapzoom();';
                    script.text  += 'myMap.setCenter(myMapcenter,myMapzoom);';                    
                    script.text  += 'myGeoObjects=initgeoobjects();';
                    script.text  += 'for (gobj_cnt=0;gobj_cnt<myGeoObjects.length;gobj_cnt++){';
                    script.text  += 'myMap.geoObjects.add(myGeoObjects[gobj_cnt]);';
                    script.text  += '$(\'<option value="\'+myGeoObjects[gobj_cnt].properties.get("hintContent")+\'">\'+myGeoObjects[gobj_cnt].properties.get("iconContent")+\'</option>\').appendTo(\'#tk_delivery_points_list\');';
                    script.text  += 'myGeoObjects[gobj_cnt].events.add(\'mouseup\', function (e) {';
                    script.text  += 'var eMap = e.get(\'target\');';            
                    script.text  += '$("#tk_delivery_points_list").val(eMap.properties.get("hintContent")).prop("selected",true);';
                    script.text  += 'eMap.options.set("preset","islands#greenStretchyIcon");';
                    script.text  += '});';               
                    script.text  += '};';
                    script.text  += 'myMap.geoObjects.events.add("mousedown", function () {';
                    script.text  += '    this.each(function (geoObject, i) {';
                    script.text  += '   if (geoObject.options.get("preset") == "islands#greenStretchyIcon") {';
                    script.text  += '       geoObject.options.set("preset","islands#blackStretchyIcon");';
                    script.text  += '   }';
                    script.text  += '    })}, myMap.geoObjects);';
                    script.text  += '$("#tk_delivery_points_list").bind("change", function(){';                
                    script.text  += 'var tk_office_adress=$(this).find("option:selected").text();';
                    script.text  += 'myMap.geoObjects.each(function (geoObject) {';
                    script.text  += 'if (geoObject.properties.get("iconContent")!==tk_office_adress){';
                    script.text  += 'geoObject.options.set("preset","islands#blackStretchyIcon");';                   
                    script.text  += '} else {';
                    script.text  += 'geoObject.options.set("preset", "islands#greenStretchyIcon");';
                    //script.text  += 'myMap.setCenter(geoObject.geometry.getCoordinates(),10);';
                    script.text  += '}';
                    script.text  += '});';  
                    script.text  += '});';
                    script.text  += '$("#tk_delivery_points_list option:eq(0)").prop("selected","selected");';
                    script.text  += '$("#tk_delivery_points_list").change();';
                    script.text  += '}';                   
                    //��������� ������ � ����� ��������
                    document.body.appendChild(script);

                } else {
                    // � ������ �������� � ������� ������ � ����� �������� 70,71
                    if ($('#spb_map_area>p:eq(0)').length && Number(xid)===69){
                       $('#spb_map_area>p:eq(0)').remove();
                       if (legal_form[1].checked===true) {
                            $('#spb_map_delivery_office_info').css('top','-205px');
                       }
                       $('#spb_map_delivery_office_info').html("�������� �����<br>������ �������");
                    }
                }
            }
    //�������� �� ����� � �� ����
    }  else if (typeof(document.getElementById('var_'+xid))!=='undefined' && Number(xid)!==10 && Number(xid)!==11 && Number(xid)!==67 && Number(xid)!==41 && Number(xid)!==13 && Number(xid)!==14 && Number(xid)!==43 && Number(xid)!==68 && Number(xid)!==69 && Number(xid)!==70 && Number(xid)!==71) {
            //var delivery_options=document.getElementById('var_'+xid);
            document.getElementById('address_and_info').innerHTML='����� � <br> ��������������<br>����������:';
            document.getElementById('address_and_info').style.display='table-cell';
            document.getElementById('adr_name').style.display='inline';
            var oImg=document.createElement('img');
            oImg.setAttribute('src', '/phpshop/templates/prodacha/images/shop/flag_green.gif');
            oImg.setAttribute('alt', '');
            oImg.setAttribute('height', '16');
            oImg.setAttribute('width', '16');
            oImg.setAttribute('border', '0');
            oImg.setAttribute('hspace','5');
            oImg.setAttribute('align','middle');
            //var adr_name_img_new=adr_name_info.nextSibling;	
            if ( adr_name_img ) {
                    //var img_src_path=adr_name_img_new.getAttribute('src');
                    //if ( img_src_path.search('/phpshop/templates/prodacha/images/shop/flag_green.gif')>0 ) {
                            //alert(adr_name_img.nodeType);
                    //}
            } else {
                    adr_name_info.parentNode.insertBefore(oImg, adr_name_info.nextSibling);
            }
			
            deliver_time_info[0].style.display='table-cell';
            deliver_time_info[1].style.display='table-cell';
            if ( $('#order_metod_div').length && order_metod.style.display==='table-cell') {
                order_metod.style.display='none';
            }
			
            if ($('#postal_index').length){
                delete_order_elements(1);				
            }

            if ($('#pass_no_info').length){
                delete_order_elements(2);				
            }
            
            if ($('#spb_map_area').length){
                $('#spb_map_area').remove();
                $("form[name='forma_order']>table:eq(0) tr:eq(14) td:eq(0)").html('');
                // remove from the dom
                document.body.removeChild(document.body.lastChild);
            }
            if (legal_form[0].checked===true){
                if ($("td input[name='order_metod']:eq(0)").length && $("td input[name='order_metod']:eq(0)+:eq(0)").html()==='���� (���������) ��� ������ ����� ����'){
                                $("td input[name='order_metod']:eq(0)+:eq(0)").next().remove();
                                $("td input[name='order_metod']:eq(0)+:eq(0)").remove();
                                $("td input[name='order_metod']:eq(0)").remove();
                }            
                if ($("td input[name='order_metod']:eq(0)+:eq(0)").html()==='���� �� ����������� (����������� ������)' || (!$("td input[name='order_metod']:eq(0)").length && 
                        $("td input[name='order_metod']:eq(0)+:eq(0)").html()!=='�������� ������' &&
                        !$("td input[name='order_metod']:eq(1)").length && 
                        $("td input[name='order_metod']:eq(1)+:eq(0)").html()!=='�������� ������')){
                                $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" checked value=\"3\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>�������� ������</label><br>"+
                                  "<input type=\"radio\" @creditdisabled@  name=\"order_metod\" value=\"25\" onClick=\"document.getElementById('bic').style.display='block';document.getElementById('bin').style.display='none';\"><label>�������� ������</label><br>");
                }
            }
            if (legal_form[1].checked===true){
                $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" checked value=\"14\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>���� �� ����������� (����������� ������)</label><br>");
                //��������� ���� ��� ������ ������� ������ � ������
                if (document.getElementById('bic')!==null && document.getElementById('bic').style.display==='block'){
                    document.getElementById('bic').style.display='none';
                    document.getElementById('bin').style.display='block';                        
                }
            }
            document.getElementsByName("order_metod")[0].checked=true;
            
            //('#tk_delivery_block').remove();
            //adr_name_img.src='/phpshop/templates/prodacha/images/shopflag_green.gif';
	} else if (typeof(document.getElementById('var_'+xid))!=='undefined' && (Number(xid)===11 || Number(xid)===67 || Number(xid)===41)) {
            
            if (document.getElementById('address_and_info')){
                document.getElementById('address_and_info').style.display='none';               
            }
            if (document.getElementById('adr_name')){
                document.getElementById('adr_name').style.display='none';    
            }
            if ($('#adr_name~img:eq(0)').length){
                $('#adr_name~img:eq(0)').remove();
            }
            if (document.getElementsByName("delivery_time_info")[0]){
                document.getElementsByName("delivery_time_info")[0].style.display='none';           
            }
            if (document.getElementsByName("delivery_time_info")[1]){
                document.getElementsByName("delivery_time_info")[1].style.display='none';                
            }
			
            delete_order_payments_elements();
			
            //������� ����� ������
            if ($('#postal_index').length){
                delete_order_elements(1);				
            }

            if ($('#pass_no_info').length){
                delete_order_elements(2);				
            }
            
            if ($('#spb_map_area').length){
                $('#spb_map_area').remove();
                $("form[name='forma_order']>table:eq(0) tr:eq(14) td:eq(0)").html('');
                // remove script from the dom
                document.body.removeChild(document.body.lastChild);
            }            
            if (legal_form[0].checked===true){
                $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" checked value=\"1\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>���� (���������) ��� ������ ����� ����</label><br>");
            }
            if (legal_form[1].checked===true){
                $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" checked value=\"14\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>���� �� ����������� (����������� ������)</label><br>");
            }
            document.getElementsByName("order_metod")[0].checked=true;

            //��������� ���� ��� ������ ������� ������ � ������
            if (document.getElementById('bic')!==null && document.getElementById('bic').style.display==='block'){
                document.getElementById('bic').style.display='none';
                document.getElementById('bin').style.display='block';                        
            }

            $('<tr>'+
                '<td align="right"><div id="tk_info" name="tk_info"> ������������ ��������: </div></td>'+
                '<td>'+
                '<div id="tk_list" name="tk_list">'+		  
                '<input type="radio" name="tk_list_item" checked="checked" value="������� �����">������� �����&nbsp;&nbsp;'+
		'<input type="radio" name="tk_list_item" value="���">���&nbsp;&nbsp;'+
                '<input type="radio" name="tk_list_item" value="������">������&nbsp;&nbsp;'+
                '<input type="radio" name="tk_list_item" value="�������">�������&nbsp;&nbsp;'+
                '<input type="radio" name="tk_list_item" value="���">���&nbsp;&nbsp;'+
                '<input type="radio" name="tk_list_item" value="������">������&nbsp;'+
                '<input type="text" id="tk_other" disabled="disabled" name="tk_other" maxlength="40" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������� �������� ��">'+
                '</div>'+
                '</td>'+
                '</tr>'+
                '<tr>'+    
                '<td align="right"><div id="cart_tk_delivery_pass_msg_info" name="cart_tk_delivery_pass_msg_info"></div></td>'+
                '<td>'+
                '<div id="cart_tk_delivery_pass_msg" name="cart_tk_delivery_pass_msg"></div>'+
                '</td>'+
                '</tr>'+   
                '<tr>'+   
                '<td align="right"><div id="firstname_info" name="firstname_info"> ��� ����������: </div></td>'+
                '<td>'+
                '<input type="text" id="lastname" name="lastname" data-container="body" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������� ���� �������." placeholder="�������" maxlength="28" value="'+$("#lastname_person").val().replace('_','')+'">'+
                '<img/>'+
                '<input type="text" id="firstname" name="firstname" data-container="body" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������� ���� ���." placeholder="���" maxlength="28" value="'+$("#name_person").val().replace('_','')+'">'+
                '<img/>'+            
                '<input type="text" id="middlename" name="middlename" data-container="body" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������� ���� ��������." placeholder="��������" maxlength="28">'+
                '<img/> '+            
                '</td>'+
                '</tr>'+  
                '<tr>'+
                '<td align="right"><div id="pass_no_info" name="pass_no_info"> ������� �����: </div></td>'+
                '<td>'+
                '<input type="text" id="pass_no1" name="pass_no1" maxlength="4" data-container="body" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������� ����� ��������." placeholder="1111">'+
                '<img/>&nbsp;�����:&nbsp;'+
                '<input type="text" id="pass_no2" name="pass_no2" maxlength="6" data-container="body" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������� ����� ��������." placeholder="111111">'+
                '<img/>&nbsp;<div id="pass_police_info" name="pass_police_info"> ���� ������: </div>&nbsp;'+
                '<input type="text" id="pass_police" name="pass_police" maxlength="10" data-container="body" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������� ���� ������ ��������." placeholder="11.11.2011">'+
                '<img/>'+
                '</td>'+         
                '</tr>'+
                '<tr>'+
                '<td align="right"><div id="tel2_info" name="tel2_info"> ������� ����������: </div></td>'+
                '<td>'+
                '<input type="tel" id="tel2" name="tel2" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������� ���. ������� ��� ��������� � �������: [+7(901)123-45-67]" placeholder="+7(901)123-45-67" maxlength="16">'+
                '<img/>'+            
                '</td>'+         
                '</tr>'+
                '<tr>'+
                '<td align="right"><div id="delivery_city_info" name="delivery_city_info"> ����� ��������: </div></td>'+
                '<td>'+
                '<input type="text" id="delivery_city" name="delivery_city" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������������ �������� ���������� �������� �� ��������� � ��������� ������" placeholder="������" maxlength="55">'+
                '<img/>'+			
                '</td>'+         
                '</tr>'+
                '<tr>'+
                '<td align="right"><div id="delivery_address_info" name="delivery_address_info"> ��������: </div></td>'+
                '<td>'+
                '<input type="radio" name="tk_delivery_item" checked="checked" value="�� ������ ������ ��">�� ������ ������ ��&nbsp;&nbsp;'+
		'<input type="radio" name="tk_delivery_item" value="�� ������">�� ������&nbsp;&nbsp;'+                
                '<input type="text" id="delivery_address" name="delivery_address" disabled="disabled" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="�� ������ �������, ������������ �������� ����� ���������� �������� �� ������, �� �������������� �����" placeholder="����� ������, 1" maxlength="85">'+
                '</td>'+         
                '</tr>').insertBefore("form[name='forma_order']>table:eq(0) tr:eq(9)");
            $('#tk_info').css("display","table-cell");
            $('#tk_list').css("display","table-cell");
            $('#tk_other').css({"width":"222px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"black"});
            $('#cart_tk_delivery_pass_msg_info').css("display","table-cell");
            $('#cart_tk_delivery_pass_msg').css({"width":"500px", "height":"36px", "font": "normal 12px/1.4 Arial, Helvetica, sans-serif", "color":"black","display":"table-cell"});
            $('#cart_tk_delivery_pass_msg').text("��� ��������� ������ � ��������� ������������ �������� (��) ��� �������� ���������� �������. ���������� ������� ���������� ������ ����������.");
            $('#firstname_info').css("display","table-cell");
            $('#firstname').css({"width":"160px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"black"});//, "color":"#4F4F4F"
            //$('#firstname').next().css("display","table-cell");
            $('#firstname').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#firstname').next().prop('alt', '');
            $('#firstname').next().prop('height', '16');
            $('#firstname').next().prop('width', '16');
            $('#firstname').next().prop('border', '0');
            $('#firstname').next().prop('hspace','5');
            $('#firstname').next().prop('align','middle');
            $('middlename_info').css("display","table-cell");
            $('#middlename').css({"width":"160px", "height":"18px","font-family":"tahoma", "font-size":"11px", "color":"black"});
            //$('#middlename').next().css("display","table-cell");
            $('#middlename').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#middlename').next().prop('alt', '');
            $('#middlename').next().prop('height', '16');
            $('#middlename').next().prop('width', '16');
            $('#middlename').next().prop('border', '0');
            $('#middlename').next().prop('hspace','5');
            $('#middlename').next().prop('align','middle');    
            //$('lastname_info').css("display","table-cell");
            $('#lastname').css({"width":"160px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"black"});
            //$('#lastname').next().css("display","table-cell");
            $('#lastname').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#lastname').next().prop('alt', '');
            $('#lastname').next().prop('height', '16');
            $('#lastname').next().prop('width', '16');
            $('#lastname').next().prop('border', '0');
            $('#lastname').next().prop('hspace','5');
            $('#lastname').next().prop('align','middle');
            //$('#pass_no_info').css("display","table-cell");
            $('#pass_no1').css({"width":"30px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"black"});
            //$('#pass_no1').next().css("display","table-cell");
            $('#pass_no1').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#pass_no1').next().prop('alt', '');
            $('#pass_no1').next().prop('height', '16');
            $('#pass_no1').next().prop('width', '16');
            $('#pass_no1').next().prop('border', '0');
            $('#pass_no1').next().prop('hspace','5');
            $('#pass_no1').next().prop('align','middle');
            $('#pass_no2').css({"width":"40px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"black"});
            //$('#pass_no2').next().css("display","table-cell");
            $('#pass_no2').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#pass_no2').next().prop('alt', '');
            $('#pass_no2').next().prop('height', '16');
            $('#pass_no2').next().prop('width', '16');
            $('#pass_no2').next().prop('border', '0');
            $('#pass_no2').next().prop('hspace','5');
            $('#pass_no2').next().prop('align','middle');
            $('#pass_police_info').css("display","inline");
            $('#pass_police').css({"width":"60px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"black"});
            //$('#pass_police').next().css("display","table-cell");
            $('#pass_police').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#pass_police').next().prop('alt', '');
            $('#pass_police').next().prop('height', '16');
            $('#pass_police').next().prop('width', '16');
            $('#pass_police').next().prop('border', '0');
            $('#pass_police').next().prop('hspace','5');
            $('#pass_police').next().prop('align','middle');
            $('#tel2_info').css("display","table-cell");
            $('#tel2').css({"width":"150px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"black"});
            $('#tel2').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#tel2').next().prop('alt', '');
            $('#tel2').next().prop('height', '16');
            $('#tel2').next().prop('width', '16');
            $('#tel2').next().prop('border', '0');
            $('#tel2').next().prop('hspace','5');
            $('#tel2').next().prop('align','middle');
            $('#delivery_city_info').css("display","table-cell");
            //$('#delivery_city').css({"width":"330px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"#4F4F4F"});
            $('#delivery_city').css({"width":"160px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"black"});
            $('#delivery_city').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#delivery_city').next().prop('alt', '');
            $('#delivery_city').next().prop('height', '16');
            $('#delivery_city').next().prop('width', '16');
            $('#delivery_city').next().prop('border', '0');
            $('#delivery_city').next().prop('hspace','5');
            $('#delivery_city').next().prop('align','middle');	
            $('#delivery_address_info').css("display","table-cell");
            $('#delivery_address').css({"width":"400px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"black"});
            fillTKtablePart();
	} else if (typeof(document.getElementById('var_'+xid))!=='undefined' && Number(xid)===68) {
            
            if (document.getElementById('address_and_info')){
                document.getElementById('address_and_info').style.display='none';               
            }
            if (document.getElementById('adr_name')){
                document.getElementById('adr_name').style.display='none';    
            }
            if ($('#adr_name~img:eq(0)').length){
                $('#adr_name~img:eq(0)').remove();
            }
            if (document.getElementsByName("delivery_time_info")[0]){
                document.getElementsByName("delivery_time_info")[0].style.display='none';           
            }
            if (document.getElementsByName("delivery_time_info")[1]){
                document.getElementsByName("delivery_time_info")[1].style.display='none';                
            }
            if ($('#spb_map_area').length){
                $('#spb_map_area').remove();
                $("form[name='forma_order']>table:eq(0) tr:eq(14) td:eq(0)").html('');
                // remove script from the dom
                document.body.removeChild(document.body.lastChild);
            }            
            delete_order_payments_elements();

            //������� ����� ������
            if ($('#postal_index').length){
                delete_order_elements(1);				
            }
            
            if ($('#pass_no_info').length){
                delete_order_elements(2);				
            }

            if (legal_form[0].checked===true){
                $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" checked value=\"1\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>���� (���������) ��� ������ ����� ����</label><br>");
            }
            if (legal_form[1].checked===true){
                $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" checked value=\"14\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>���� �� ����������� (����������� ������)</label><br>");
            }
            document.getElementsByName("order_metod")[0].checked=true;
            //��������� ���� ��� ������ ������� ������ � ������
            if (document.getElementById('bic')!==null && document.getElementById('bic').style.display==='block'){
                document.getElementById('bic').style.display='none';
                document.getElementById('bin').style.display='block';                        
            }			
            $(  '<tr>'+    
                '<td align="right"><div id="cart_tk_delivery_pass_msg_info" name="cart_tk_delivery_pass_msg_info"></div></td>'+
                '<td>'+
                '<div id="cart_tk_delivery_pass_msg" name="cart_tk_delivery_pass_msg"></div>'+
                '</td>'+
                '</tr>'+   
                '<tr>'+   
                '<td align="right"><div id="firstname_info" name="firstname_info"> ��� ����������: </div></td>'+
                '<td>'+
                '<input type="text" id="lastname" name="lastname" data-container="body" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������� ���� �������." placeholder="�������" maxlength="28" value="'+$("#lastname_person").val().replace('_','')+'">'+
                '<img/>'+
                '<input type="text" id="firstname" name="firstname" data-container="body" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������� ���� ���." placeholder="���" maxlength="28" value="'+$("#name_person").val().replace('_','')+'">'+
                '<img/>'+            
                '<input type="text" id="middlename" name="middlename" data-container="body" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������� ���� ��������." placeholder="��������" maxlength="28">'+
                '<img/> '+            
                '</td>'+
                '</tr>'+  
                '<tr>'+
                '<td align="right"><div id="tel2_info" name="tel2_info"> ������� ����������: </div></td>'+
                '<td>'+
                '<input type="tel" id="tel2" name="tel2" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������� ���. ������� ��� ��������� � �������: [+7(901)123-45-67]" placeholder="+7(901)123-45-67" maxlength="16">'+
                '<img/>'+            
                '</td>'+         
                '</tr>'+
                '<tr>'+
                '<td align="right"><div id="delivery_city_info" name="delivery_city_info"> ����� ��������: </div></td>'+
                '<td>'+
                '<input type="text" id="delivery_city" name="delivery_city" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������������ �������� ���������� �������� �� ��������� � ��������� ������" placeholder="������" maxlength="55">'+
                '<img/>&nbsp;������:&nbsp;'+			
                '<input type="text" id="postal_index" name="postal_index" maxlength="6" data-container="body" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������� �������� ������." placeholder="111111">'+				
                '<img/>'+ 
                '</td>'+         
                '</tr>'+
                '<tr>'+
                '<td align="right"><div id="delivery_address_info" name="delivery_address_info"> ����� ��������: </div></td>'+
                '<td>'+
                '<input type="text" id="delivery_address" name="delivery_address" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="�� ������ �������, ������������ �������� ����� ���������� �������� �� ������, �� �������������� �����" placeholder="��. ������, �.1, �.1, ��.123" maxlength="85">'+
	        '<img/>'+ 			
                '</td>'+         
            '</tr>').insertBefore("form[name='forma_order']>table:eq(0) tr:eq(9)");
            $('#cart_tk_delivery_pass_msg_info').css("display","table-cell");
            $('#cart_tk_delivery_pass_msg').css({"width":"630px", "height":"36px", "font": "normal 12px/1.4 Arial, Helvetica, sans-serif", "color":"black","display":"table-cell"});//#4F4F4F
            $('#cart_tk_delivery_pass_msg').text("�������� ������ ������ �������������� ��� ������� ������ ����� 3 �� � ���������� ����� 40�25�35 ��. ����� ���������� ������ ������������ ������������� ���������� (������� �����, ���, ���������������� � �.�.)");
            $('#firstname_info').css("display","table-cell");
            $('#firstname').css({"width":"160px", "height":"18px", "font-family":"tahoma", "font-size":"11px"});//, "color":"#4F4F4F"
            //$('#firstname').next().css("display","table-cell");
            $('#firstname').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#firstname').next().prop('alt', '');
            $('#firstname').next().prop('height', '16');
            $('#firstname').next().prop('width', '16');
            $('#firstname').next().prop('border', '0');
            $('#firstname').next().prop('hspace','5');
            $('#firstname').next().prop('align','middle');
            $('middlename_info').css("display","table-cell");
            $('#middlename').css({"width":"160px", "height":"18px","font-family":"tahoma", "font-size":"11px", "color":"black"});
            //$('#middlename').next().css("display","table-cell");
            $('#middlename').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#middlename').next().prop('alt', '');
            $('#middlename').next().prop('height', '16');
            $('#middlename').next().prop('width', '16');
            $('#middlename').next().prop('border', '0');
            $('#middlename').next().prop('hspace','5');
            $('#middlename').next().prop('align','middle');    
            //$('lastname_info').css("display","table-cell");
            $('#lastname').css({"width":"160px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"black"});
            //$('#lastname').next().css("display","table-cell");
            $('#lastname').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#lastname').next().prop('alt', '');
            $('#lastname').next().prop('height', '16');
            $('#lastname').next().prop('width', '16');
            $('#lastname').next().prop('border', '0');
            $('#lastname').next().prop('hspace','5');
            $('#lastname').next().prop('align','middle');
            $('#tel2_info').css("display","table-cell");
            $('#tel2').css({"width":"150px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"black"});
            $('#tel2').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#tel2').next().prop('alt', '');
            $('#tel2').next().prop('height', '16');
            $('#tel2').next().prop('width', '16');
            $('#tel2').next().prop('border', '0');
            $('#tel2').next().prop('hspace','5');
            $('#tel2').next().prop('align','middle');
            $('#delivery_city_info').css("display","table-cell");
            //$('#delivery_city').css({"width":"330px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"#4F4F4F"});
            $('#delivery_city').css({"width":"160px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"black"});
            $('#delivery_city').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#delivery_city').next().prop('alt', '');
            $('#delivery_city').next().prop('height', '16');
            $('#delivery_city').next().prop('width', '16');
            $('#delivery_city').next().prop('border', '0');
            $('#delivery_city').next().prop('hspace','5');
            $('#delivery_city').next().prop('align','middle');
            $('#postal_index').css({"width":"40px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"black"});
            $('#postal_index').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#postal_index').next().prop('alt', '');
            $('#postal_index').next().prop('height', '16');
            $('#postal_index').next().prop('width', '16');
            $('#postal_index').next().prop('border', '0');
            $('#postal_index').next().prop('hspace','5');
            $('#postal_index').next().prop('align','middle');	
            $('#delivery_address_info').css("display","table-cell");
            $('#delivery_address').css({"width":"510px", "height":"18px", "font-family":"tahoma", "font-size":"11px", "color":"black"});
            $('#delivery_address').next().prop('src','/phpshop/templates/prodacha/images/shop/flag_green.gif');
            $('#delivery_address').next().prop('alt', '');
            $('#delivery_address').next().prop('height', '16');
            $('#delivery_address').next().prop('width', '16');
            $('#delivery_address').next().prop('border', '0');
            $('#delivery_address').next().prop('hspace','5');
            $('#delivery_address').next().prop('align','middle');
            fillTKtablePart();
	}
	
    //�������� ����� ajax ����� �������� ������ �������� ��� �������� �������
    req.onreadystatechange = function() {
        if (req.readyState === 4) {
            if (req.responseJS) {
                document.getElementById('DosSumma').innerHTML = (req.responseJS.delivery||'');
                document.getElementById('d').value = xid;
                document.getElementById('TotalSumma').innerHTML = (req.responseJS.total||'');
                document.getElementById('seldelivery').innerHTML = (req.responseJS.dellist||'');
            	//console.log(req.responseJS.dellist);			
                var z=(req.responseJS.total||'');
                var city=(req.responseJS.city||'');

                if (Number(z)<5000 && (Number(xid)!==69 && city!=='sp') && (Number(xid)!==41 && city!=='chb')){
                   document.getElementById('delivery_warning').style.display="table-cell";
                } else if (Number(z)>=5000) {
                   document.getElementById('delivery_warning').style.display="none";
                }
                    //��� ������ � �������� ������������ ������� ������ ��� ���� ��� ���� < 1000
                    if ( ((((Number(xid)===69 || Number(xid)===0 || isNaN(Number(xid))) && city==='sp') || (city==='chb'))) && Number(z)<1000){
                        //console.log('test');
                        if (city==='sp') {
                            cityname='�����-����������';
                            if ($("form[name='forma_order']>table:eq(0) tr:eq(10)").length){
                                //������� ������ ��������
                                for (cnt=0;cnt<=7;cnt++) {
                                    $("form[name='forma_order']>table:eq(0) tr:eq(10)").remove();
                                }            
                            }                            
                        }
                        if (city==='chb') {
                            cityname='����������';
                            if ($("form[name='forma_order']>table:eq(0) tr:eq(9)").length){
                                //������� ������ ��������
                                for (cnt=0;cnt<=15;cnt++) {
                                    $("form[name='forma_order']>table:eq(0) tr:eq(9)").remove();
                                }            
                            }
                        }
                        $('#delivery_warning').html('����������� ����� ������ � ��������-�������� PRO���� � '+cityname+' ���������� 1000 ���. ����������, ��������� ��� ����� �� ����������� �����.');
                    $('#delivery_warning').css({'display':'table-cell','font-family':'tahoma', 'font-size':'12px'});
                    $("form[name='forma_order']>table:eq(0) tr:eq(0)").css('display','none');
                    $("form[name='forma_order']>table:eq(0) tr:eq(1)").css('display','none');
                    $("form[name='forma_order']>table:eq(0) tr:eq(2)").css('display','none');
                    $("form[name='forma_order']>table:eq(0) tr:eq(4)").css('display','none');
                    $("form[name='forma_order']>table:eq(0) tr:eq(5)").css('display','none');
                    $("form[name='forma_order']>table:eq(0) tr:eq(6)").css('display','none');
                    $("form[name='forma_order']>table:eq(0) tr:eq(7)").css('display','none');
                    $("form[name='forma_order']>table:eq(0) tr:eq(8)").css('display','none');
                    $("form[name='forma_order']>table:eq(0) tr:eq(9)").css('display','none');
                    $('bin').css('display','none');
                }
                //if (city==='chb' && Number(z)>=1000)
                    //$('div[name="seldelivery"]:eq(1)>a:first')[0].click();
                    //console.log($('div[name="seldelivery"]:eq(1)>a:first').text());
                if (city==='chb' && $('#delivery_city').length)
                $('#delivery_city').val('���������');
            }
        }
    };
    req.caching = false;
    // �������������� ������.
    // �������� ����������
    var dir=dirPath();
		
    req.open('POST', dir+'/phpshop/ajax/delivery.php', true);
    req.send({
        xid: xid,
        sum: sum,
        wsum: wsum
    });
    //��������� ����������� ���� � ������ �������������� ���� ��������� ������������ � ������ ���� �� ���� � ������ ��������
    add_popover();
    if ($.trim($('.city_link').eq(0).text())==="���������"){
        $('div[name="seldelivery"]:eq(1)>a:first')[0].click();
    }
    if ($.trim($('.city_link').eq(0).text())==="�����-���������"){
    }    
}

function get_payments_elements(region,delivery){

    var req = new Subsys_JsHttpRequest_Js();
    //region='m';
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                //initialize();
                //setTimeout("initialize_off()",3000);
                if ($("td input[name='order_metod']:eq(0)").length)
                    $("td input[name='order_metod']:eq(0)").prop('value',(req.responseJS.payment_method[0]||''));
                if ($("td input[name='order_metod']:eq(1)").length)
                    $("td input[name='order_metod']:eq(1)").prop('value',(req.responseJS.payment_method[1]||''));
                if ($("td input[name='order_metod']:eq(2)").length)
                    $("td input[name='order_metod']:eq(2)").prop('value',(req.responseJS.payment_method[2]||''));
            }
        }
    }
    req.caching = false;
    var truePath=dirPath();
    //console.log(truePath+'/phpshop/ajax/payment_method.php');
    req.open('POST', truePath+'/phpshop/ajax/payment_method.php', true);
    req.send({
        region: region,
        delivery:delivery        
    });
}

function delete_order_payments_elements(){
            if ($("td input[name='order_metod']:eq(0)").length && $("td input[name='order_metod']:eq(0)+:eq(0)").html()==='���� (���������) ��� ������ ����� ����'){
		return true;
            }
            if ($("td input[name='order_metod']:eq(0)").length && $("td input[name='order_metod']:eq(0)+:eq(0)").html()==='���� �� ����������� (����������� ������)'){
		return true;
            }
            if ($("td input[name='order_metod']:eq(0)").length && 
                $("td input[name='order_metod']:eq(0)+:eq(0)").html()==='�������� ������' &&
                $("td input[name='order_metod']:eq(1)").length && 
                $("td input[name='order_metod']:eq(1)+:eq(0)").html()==='�������� ������'){
                    $("#order_metod_div").remove();
                    $("td input[name='order_metod']:eq(1)+:eq(0)").next().remove();
                    $("td input[name='order_metod']:eq(1)+:eq(0)").remove();
                    $("td input[name='order_metod']:eq(1)").remove();
                    $("td input[name='order_metod']:eq(0)+:eq(0)").next().remove();
                    $("td input[name='order_metod']:eq(0)+:eq(0)").remove();
                    $("td input[name='order_metod']:eq(0)").remove();				
                    if (!$("input[name='order_metod']:eq(0)").length){
                        $("form[name='forma_order']>table:eq(0) tr:eq(9) td:eq(1)").html("<input type=\"radio\" name=\"order_metod\" value=\"1\" onClick=\"document.getElementById('bin').style.display='block';document.getElementById('bic').style.display='none';\"><label>���� (���������) ��� ������ ����� ����</label><br>");
                        //$("td input[name='order_metod']:eq(0)+:eq(0)").fadeOut();
                        //$("td input[name='order_metod']:eq(0)").fadeOut();
                        document.getElementsByName("order_metod")[0].checked=true;
                    }
                    //document.getElementsByName("order_metod")[0].checked=true;
                    //document.getElementsByName("order_metod")[1].checked=false;					
                    //$("td input[name='order_metod']:eq(2)").remove();
                    //$("td input[name='order_metod']:eq(2)+:eq(0)").remove();
                    //$("td input[name='order_metod']:eq(2)+:eq(0)").next().remove();					
                    //$("td input[name='order_metod']:eq(1)+:eq(0)").fadeOut();
                    //$("td input[name='order_metod']:eq(2)+:eq(0)").fadeOut();
                    //console.log($("#order_metod_div>input[name='order_metod']:eq(0)").next());
                    //$("#order_metod_div :input[name='order_metod']:eq(0)").next.fadeOut();
            }

		//������� ������ �������� ������,��������� ������ ��������� �����
		if (document.getElementsByName("order_metod")[0] && document.getElementsByName("order_metod")[1]){					
		}
		return true;
}

function delete_order_elements(param){
	//�������� ������������ ���������
        //console.log(param);
	if (param==2){
		if ( document.getElementById('pass_no_info') ){
			//console.log($("input[name='forma_order']:eq(0) tr:eq(9)"));
			for(i=0;i<=6;i++){
                            $("form[name='forma_order']:eq(0)>table:eq(0) tr:eq(9)").remove();
			}
		}
		return true;		
	}
	//�������� ������ ������
	if (param==1){
		if ( document.getElementById('postal_index') ){
			//console.log($("input[name='forma_order']:eq(0) tr:eq(9)"));
			for(i=0;i<=4;i++){
                            $("form[name='forma_order']:eq(0)>table:eq(0) tr:eq(9)").remove();
			}
		}
		return true;		
	}
	
}

function set_legal_form() {
	var legal_form_elm=document.getElementsByName('legal_form');
	var org_name_info = document.getElementById('org_name_info');
	var org_name_value = document.getElementById('org_name_value');
	var org_inn_info = document.getElementById('org_inn_info');
	var org_inn_value = document.getElementById('org_inn_value');
	var org_kpp_info = document.getElementById('org_kpp_info');
	var org_kpp_value = document.getElementById('org_kpp_value');
	var annual_number_info = document.getElementById('annual_number_info');
	var annual_number_value = document.getElementById('annual_number_value');
	var bic_bank_number_info = document.getElementById('bic_bank_number_info');
	var bic_bank_number_value = document.getElementById('bic_bank_number_value');
	var bic_bank_number_info = document.getElementById('bic_bank_number_info');
	var bic_bank_number_value = document.getElementById('bic_bank_number_value');
	var bank_name_info = document.getElementById('bank_name_info');
	var bank_name_value = document.getElementById('bank_name_value');
	var gen_manager_initial_info = document.getElementById('gen_manager_initial_info');
	var gen_manager_initial_value = document.getElementById('gen_manager_initial_value');
        var dostavka_metod=document.getElementById('dostavka_metod').childNodes;
	//alert(legal_form_elm[0].select);
	if ( legal_form_elm[0].checked===true ) {
		org_name_info.style.display='none';
		org_name_value.style.display='none';
		org_inn_info.style.display='none';
		org_inn_value.style.display='none';
		org_kpp_info.style.display='none';
		org_kpp_value.style.display='none';
                annual_number_info.style.display='none';
                annual_number_value.style.display='none';
                bic_bank_number_info.style.display='none';
                bic_bank_number_value.style.display='none';
                bank_name_info.style.display='none';
                bank_name_value.style.display='none';
                gen_manager_initial_info.style.display='none';
                gen_manager_initial_value.style.display='none';
                if ($('#spb_map_delivery_office_info').length && $('#spb_map_area').length){
                    $('#spb_map_delivery_office_info').css("top","-40px;");
                    $('#spb_map_area').css("top","-245px;");
                }                
	} else if ( legal_form_elm[1].checked===true ) {
		org_name_info.style.display='table-cell';
		org_name_value.style.display='table-cell';
		org_inn_info.style.display='table-cell';
		org_inn_value.style.display='inline';
		org_kpp_info.style.display='inline';
		org_kpp_value.style.display='inline';		
                annual_number_info.style.display='table-cell';
                annual_number_value.style.display='inline';
                bic_bank_number_info.style.display='inline';
                bic_bank_number_value.style.display='inline';
                bank_name_info.style.display='table-cell';
                bank_name_value.style.display='inline';
                gen_manager_initial_info.style.display='inline';
                gen_manager_initial_value.style.display='inline';
                if ($('#spb_map_delivery_office_info').length && $('#spb_map_area').length){
                    $('#spb_map_delivery_office_info').css("top","-20px;");
                    $('#spb_map_area').css("top","-225px;");
                }
		
	}
        //console.log(dostavka_metod.length);
        for(i=0;i<=dostavka_metod.length;i++){
            //if (dostavka_metod[i].hasOwnProperty('selected')) console.log(i);
            if (typeof(dostavka_metod[i])!=='undefined')
                if (dostavka_metod[i].selected){
                    console.log(dostavka_metod[i].value);
                    UpdateDelivery(dostavka_metod[i].value);
                }
            //console.log(dostavka_metod[i].selected);
        }
        //UpdateDelivery(dostavka_metod.value);
}

function getNextElement(elem) {
  var nextElem = elem.nextElementSibling;
  if (nextElem === undefined) {
    nextElem = elem.nextSibling;
    while (nextElem && nextElem.nodeType != 1) {
      nextElem = nextElem.nextSibling;
    }
  } 
  return nextElem;
}

//create function, it expects 2 values.
function insertAfter(newElement,targetElement) {
    //target is what you want it to go after. Look for this elements parent.
    var parent = targetElement.parentNode;

    //if the parents lastchild is the targetElement...
    if(parent.lastchild == targetElement) {
        //add the newElement after the target element.
        parent.appendChild(newElement);
        } else {
        // else the target has siblings, insert the new element between the target and it's next sibling.
        parent.insertBefore(newElement, targetElement.nextSibling);
        }
}
// ������� �������
function cartClean(){
    if(confirm("�� ������������� ������ �������� �������?"))  window.location.replace('./?cart=clean');
}


// �������� ������
function NoticeDel(id){
    if(confirm("�� ������������� ������ ������� ������?"))
        window.location.replace('./notice.html?noticeId='+id);
}

// �������� ������� ����� ��������, ��������� ��������
function NoFoto(obj,pathTemplate){
    obj.src=pathTemplate +'/images/shop/no_photo.gif';
}

// �������� ������� ����� ��������, ������ ��������
function NoFoto2(obj){
    obj.height=0;
    obj.width=0;
}

// ������������ ������� ��������, ��������� �������
function EditFoto(obj,max_width){
/*
var w,h,pr,max_height;
w=Number(obj.width);
if(w > max_width) obj.width = max_width;
*/
}

// ����� ������ ����� �����-�����
function GetAllForma(catId){
    if(catId!="" && catId!="ALL") window.location.replace("../shop/CID_"+catId+".html");
    if(catId=="ALL") alert('��� ���� ��������� ����� � ��������� �� ��������! �������� �� ����������� ���� ��������� � ������� "��������". ����� ����� ����� � �������� ������ ��������.');
    if(catId=="") alert('�������� �� ����������� ���� ��������� � ������� "��������". ����� ����� ����� � �������� ������ ��������.');
}

// ���������� ������
function DoPriceSort(){
    var catId=document.getElementById("catId").value;
    location.replace("../price/CAT_SORT_"+catId+".html");
}

// ��������� ��������
function NavActive(nav){
    if(document.getElementById(nav)){
        var IdStyle = document.getElementById(nav);
        IdStyle.className='menu_bg';
        //���������� ����� charttitle2 � ������� span class=menu_bg id=order
        if ($('.menu_bg').length && $('.menu_bg').prop('id')==='order'){
           $('.menu_bg').prop('class','charttitle2');
        }
    }
}

// �������� ����� �������������� ������
function ChekUserSendForma(){
    var d=document.userpas_forma;
    var login=d.login.value;
    if(login=="") alert("������ ���������� ����� �������������� ������");
    else d.submit();
}

// �������� ����������� ������ ������������
function CheckNewUserForma(){
    var d=document.users_data;
    var login=d.login_new.value;
    var password=d.password_new.value;
    var password2=d.password_new2.value;
    var name=d.name_new.value;
    var lastname=d.lastname_new.value;
    var mail=d.mail_new.value;
    var tel=d.tel_new.value;
    var adres=d.adres_new.value;   
    if(name=="" || lastname=="" || mail=="" || login=="" || password=="" || password!=password2 || name.length<2 || lastname.length<2)
        alert("������ ���������� ����� ����������� ������������");
    else d.submit();
}

// ����� ������������
function UserLogOut(){
    if(confirm("�� ������������� ������ ����� �� ������� ��������?"))
        window.location.replace('?logout=true');
}

// �������� ����� ������
function DispPasDiv(){
    if(document.getElementById("password_chek").checked) document.getElementById("password").style.display='block';
    else document.getElementById("password").style.display='none';
}

// �������� ��������� ������� �������������
function UpdateUserPassword(){
    var d=document.users_password;
    var login=d.login_new.value;
    var password=d.password_new.value;
    var password2=d.password_new2.value;

    if(login=="" || password=="" || password!=password2){
        alert("������ ���������� ����� ��� ��������� �������");
        document.getElementById("password").style.display='block';
        document.getElementById("password_chek").checked="true";
    }
    else d.submit();
}

// �������� ��������� ������ �������������
function UpdateUserForma(){
    var d=document.users_data;
    var name=d.name_new.value;
    var lastname=d.lastname_new.value;
    var mail=d.mail_new.value;
    var tel=d.tel_new.value;
    var adres=d.adres_new.value; 
    if(name=="" || lastname=="" || mail=="" || name.length<2 || lastname.length<2)
        alert("������ ���������� ����� ��� ��������� ������");
    else d.submit();
}


// �������� ����� �����������
function ChekUserForma(){
    var login=document.user_forma.login.value;
    var password=document.user_forma.password.value;
    if(login!="" || password!="")
        document.user_forma.submit();
    else alert("������ ���������� ����� �����������");
}


function do_err(err){
	console.log(err);
    return true;
}

onerror=do_err;

function walkTheDOM(node,nodeval,prevtxtnode, func) {
    func(node,nodeval,prevtxtnode);
    node = node.firstChild;
    while (node) {
        walkTheDOM(node,nodeval,prevtxtnode, func);
        node = node.nextSibling;
    }
}

// ��������� ���-�� � ����
function ChangeNumProduct(pole,znak){

//���� ��������� id="num_new"
/*
    var elm;

    for(i = 0; i < document.all.length; i++)
    {
        elm = document.all(i);
		if ( elm.getAttribute("name")=="num_new" ) {
			prevtxtnode=elm;
		}
		if ( elm.value==pole ) {
			alert(prevtxtnode.value);
			var num=Number(prevtxtnode.value);
			if(znak=="+") prevtxtnode.value=(num+1);
			if(znak=="-" && num!=1) prevtxtnode.value=(num-1);			
		}
    }
*/
/*	
    node = document.body.firstChild;
    while (node) {
        node = node.nextSibling;
    }
walkTheDOM(document.body, pole,prevtxtnode, function (node,pole,prevtxtnode) {
 if ( node.nodeType === 1 ) { // Is it a Text node?
	//alert(node.data+" "+pole);
	//alert(node.nodeValue+" "+pole);
	if ( node.nodeType === 3 ) {
		prevtxtnode=node;
	}
	if ( node.value == pole ) {
		//if (typeof txtnode !== 'undefined') {
			alert(prevtxtnode.nodeValue);	
			var num=Number(prevtxtnode.value);
			if(znak=="+") prevtxtnode.value=(num+1);
			if(znak=="-" && num!=1) prevtxtnode.value=(num-1);
		//}	
		return 1;
	}
 }
});
*/
	//alert(rtn);
	/*
if (typeof txtnode !== 'undefined') {
	alert(txtnode.value);	
	var num=Number(txtnode.value);
	if(znak=="+") txtnode.value=(num+1);
	if(znak=="-" && num!=1) txtnode.value=(num-1);
}
*/
//alert('test');
//[lbl] 1:

//while (document.getElementById(pole).value!=pole) {
//var num=Number(document.getElementsById("num_new").value);
//
//}
//alert(znak);
    var num=Number(document.getElementById(pole).value);
    if(znak=="+") document.getElementById(pole).value=(num+1);
    if(znak=="-" && num>0) document.getElementById(pole).value=(num-1);
	if(znak=="=") document.getElementById(pole).value=(num);
}
// ��������� ���-�� � ����
function ChangeNumProduct2(pole,znak){

    var num=Number(document.getElementById(pole).value);
    var cart_num=Number(document.getElementById("prod_count_label").innerHTML);
    var cart_price=Number(document.getElementById("prod_sum_info").innerHTML);
    var cart_base_num=Number(document.getElementById("cart_base_num_val").innerHTML);
    var cart_base_price=Number(document.getElementById("cart_base_price_val").innerHTML);    
    //console.log(cart_num);
    //console.log(cart_price);
    //console.log(cart_base_num);
    //console.log(cart_base_price);
    
    if(znak=="+") {
        var price=Number(document.getElementById('prod_base_price_val').innerHTML);
        if (num>=999){
            num=999;
            price=cart_price;
        } else {
            price=price+cart_price;
            cart_num=cart_num+1;
            num=(num+1);
        }

        document.getElementById(pole).value=num;
        //document.getElementById('prod_count_label').innerHTML=document.getElementById(pole).value;
        document.getElementById('prod_sum_info').innerHTML=price;
        document.getElementById('prod_count_label').innerHTML=cart_num;

        var num_str=document.getElementById('prod_count_label').innerHTML;
        //console.log(num_str);
        if (Number(num_str.slice(-1))==1){       
            document.getElementById('prod_tovar_label').innerHTML='�����';
        }
        if ((Number(num_str.slice(-1))>1 && Number(num_str.slice(-1))<=4)){
            document.getElementById('prod_tovar_label').innerHTML='������';
        }
        if (((Number(num_str.slice(-1))>4 && Number(num_str.slice(-1))<=9) || Number(num_str.slice(-1))==0)){
            document.getElementById('prod_tovar_label').innerHTML='�������';
        }
        if (Number(num_str)>4 && Number(num_str)<=20){
            document.getElementById('prod_tovar_label').innerHTML='�������';
        }

    }
    if(znak=="-") {
        if (num<=1){
            document.getElementById(pole).value=1;
            var num_str=document.getElementById('prod_count_label').innerHTML;
            //console.log(num_str);
            if (Number(num_str.slice(-1))==1){       
                document.getElementById('prod_tovar_label').innerHTML='�����';
            }
            if ((Number(num_str.slice(-1))>1 && Number(num_str.slice(-1))<=4)){
                document.getElementById('prod_tovar_label').innerHTML='������';
            }
            if (((Number(num_str.slice(-1))>4 && Number(num_str.slice(-1))<=9) || Number(num_str.slice(-1))==0)){
                document.getElementById('prod_tovar_label').innerHTML='�������';
            }
            if (Number(num_str)>4 && Number(num_str)<=20){
                document.getElementById('prod_tovar_label').innerHTML='�������';
            }        
        } else {
            num=(num-1);
            var num_str=num.toString();

            document.getElementById(pole).value=num;
            document.getElementById('prod_count_label').innerHTML=(cart_num-1);
            //price=price-Number(document.getElementById('prod_base_price_val').innerHTML);
            document.getElementById('prod_sum_info').innerHTML=cart_price-Number(document.getElementById('prod_base_price_val').innerHTML);;
            
            var num_str=document.getElementById('prod_count_label').innerHTML;
            //console.log(num_str);
            if (Number(num_str.slice(-1))==1){       
                document.getElementById('prod_tovar_label').innerHTML='�����';
            }
            if ((Number(num_str.slice(-1))>1 && Number(num_str.slice(-1))<=4)){
                document.getElementById('prod_tovar_label').innerHTML='������';
            }
            if (((Number(num_str.slice(-1))>4 && Number(num_str.slice(-1))<=9) || Number(num_str.slice(-1))==0)){
                document.getElementById('prod_tovar_label').innerHTML='�������';
            }
            if (Number(num_str)>4 && Number(num_str)<=20){
                document.getElementById('prod_tovar_label').innerHTML='�������';
            }

        }
    }
    if(znak=="=") {
        if (num<=0){
            num=1;
        }
        if (num>=999){
            num=999;
        }

        document.getElementById(pole).value=num;
        document.getElementById('prod_count_label').innerHTML=(cart_base_num+num);
        document.getElementById('prod_sum_info').innerHTML=cart_base_price+(Number(document.getElementById('prod_base_price_val').innerHTML)*num);
        
        var num_str=document.getElementById('prod_count_label').innerHTML;
        //console.log(num_str);
        if (Number(num_str.slice(-1))==1){       
            document.getElementById('prod_tovar_label').innerHTML='�����';
        }
        if ((Number(num_str.slice(-1))>1 && Number(num_str.slice(-1))<=4)){
            document.getElementById('prod_tovar_label').innerHTML='������';
        }
        if (((Number(num_str.slice(-1))>4 && Number(num_str.slice(-1))<=9) || Number(num_str.slice(-1))==0)){
            document.getElementById('prod_tovar_label').innerHTML='�������';
        }
        if (Number(num_str)>4 && Number(num_str)<=20){
            document.getElementById('prod_tovar_label').innerHTML='�������';
        }
    }
}
// ����� ������
function ChangeValuta(){
    document.ValutaForm.submit();
}

// ����� �����
function ChangeSkin(){
    document.SkinForm.submit();
}

// Ajax ���������� � �������
function ToCart(xid,num,xxid) {
    var req = new Subsys_JsHttpRequest_Js();
    var same= 0;

    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                initialize();
                setTimeout("initialize_off()",3000);
                document.getElementById('num').innerHTML = (req.responseJS.num||'');
                document.getElementById('sum').innerHTML = (req.responseJS.sum||'');
                same=(req.responseJS.same||'');
                if (same==1) {
                    alert("���� ����� ���������� ����� � ������ ���������������. ���������� ������ � ������� ��������� � �������������� ��������� �� ��������� �������!");
                }
            }
        }
    }
    req.caching = false;
    var truePath=dirPath();

    var name="allOptionsSet"+xid;
    if(document.getElementById(name)) {
        addname=document.getElementById(name).value;
    } else {
        addname="";
    }
    //console.log(xid+' '+num+' '+xxid+' '+same);
    req.open('POST', truePath+'/phpshop/ajax/cartload.php', true);
    req.send({
        xid: xid,
        num: num,
        xxid: xxid,
        addname: addname,
        same: same
    });
}

// Ajax ���������� � �������
function ToCartSync(xid,num,xxid) {
    var truePath=dirPath();

    $.ajax({
        url: truePath+'/phpshop/ajax/cartloadsync.php',
        type: 'post',
        data: 'xid='+ xid+'&num='+ num+'&xxid='+xxid,
        dataType: 'json',
        async: false,
        beforeSend: function() {},
        complete: function() {},
        success: function(json) {
        if (json['ok'] == '1'){
            document.getElementById('num').innerHTML = (json['num']||'');
            document.getElementById('sum').innerHTML = (json['sum']||'');
        }
        }
    });
}

// ���������� ������ � ������� 1 ��.
function AddToCart(xid) {
    var num=1;
    var xxid=0;
    //console.log('before_addtochart_window');    
    addtochart_window(xid);
    //console.log('after_addtochart_window');

    return true;

    if (confirm("�������� ��������� ����� ("+num+" ��.) � �������?")){
        ToCart(xid,num,xxid);
        if(document.getElementById("order")) document.getElementById("order").style.display='block';
            var script   = document.createElement("script");
            script.type  = "text/javascript";
            script.text  = "ga('send', 'event', 'buy', 'click');";              // use this for inline script
            //script.text  += "console.log('buy');";              // use this for inline script
            document.body.appendChild(script);
            // remove from the dom
            document.body.removeChild(document.body.lastChild);        
        
            return true;
    }
	else
	{
            return false;
	}

}	

// ���������� ������ � ������� 1 ��.
/*
 * 
 * @param {type} xid id ������
 * @param {type} option 1-� ��������� � �������, 2 ��� �������� � �������
 * @returns {Boolean}
 */
function AddToCart2(xid,option) {
    var num=1;
    var xxid=0;
    //console.log(xid);
    ToCart(xid,num,xxid);
    //if(document.getElementById("order")) document.getElementById("order").style.display='block';

    var script   = document.createElement("script");
    script.type  = "text/javascript";
    script.text  = "ga('send', 'event', 'buy', 'click');";// use this for inline script
    //script.text  += "console.log('buy');";              // use this for inline script
    document.body.appendChild(script);
    // remove from the dom
    document.body.removeChild(document.body.lastChild);

    if ($('.fancybox-item.fancybox-close:eq(0)').length) $.fancybox.close();//$('.fancybox-item.fancybox-close:eq(0)').click();
    //a_temp=document.getElementsByTagName('body')[0];
    //console.log(typeof a_temp);
    //if (typeof document.getElementById('a_temp_'+id)=="object") {
        //console.log($('#a_temp_'+xid).length);
        //$('#a_temp_'+xid).remove();
        //a_temp.removeChild(document.getElementById('a_temp_'+id));
        //document.body.removeChild(document.getElementById('a_temp_'+id));
        //console.log($('#a_temp_'+xid).length);
    //}
    //console.log(typeof a_temp);
    if (option==1)
    {
        window.location.replace('/order/');
    }
    return true;
}

// ���������� ������ � ������� N ��.
/*
 * 
 * @param {type} xid
 * @param {type} pole
 * @param {type} option 1-� ��������� � �������, 2 ��� �������� � �������
 * @returns {Boolean}
 */
function AddToCartNum(xid,pole,option) {

    var num=Number(document.getElementById(pole).value); 
    var xxid=xid;
    if (num<1) num=1;
    //if(confirm("�������� ��������� ����� ("+num+" ��.) � �������?"))
	//{
    ToCartSync(xid,num,xxid);
    if(document.getElementById("order")) document.getElementById("order").style.display='block';
    ga('send', 'event', 'buy', 'click');
    //var script   = document.createElement("script");
    //script.type  = "text/javascript";
    //script.text  = "ga('send', 'event', 'buy', 'click');";// use this for inline script
    //script.text  += "console.log('buy');";              // use this for inline script
    //document.body.appendChild(script);
    // remove from the dom
    //document.body.removeChild(document.body.lastChild);        
    if ($('.fancybox-item.fancybox-close:eq(0)').length) 
        $.fancybox.close();//$('.fancybox-item.fancybox-close:eq(0)').click();
        //if ($('#_tool_'+xid).length){
        //    var top = $('#_tool_'+xid).offset(); //Getting Y of target element
        //    $("body,html,document").scrollTop(top.top);            
        //}
        //console.log('11111111');
    if (option===1){
        window.location.replace('/order/');
    }
    return true;
    /*        
    }
	else
	{
		return false;
	}
    */
}
	
// ���������� ������������ ������ � ������� N ��.
function AddToCartParent(xxid) {
    var num=1;
    var xid=document.getElementById("parentId").value;

    addtochart_window(xid);

    return true;

    if(confirm("�������� ��������� ����� ("+num+" ��.) � �������?")){
        ToCart(xid,num,xxid);
        initialize();
        setTimeout("initialize_off()",3000);
        if(document.getElementById("order")) document.getElementById("order").style.display='block';
    }
}	

// �������� ������ � ���������
function AddToCompare(xid) {
    var num=1;
    var same=0;
    if(confirm("�������� ��������� ����� � ������� ���������?")){

        var req = new Subsys_JsHttpRequest_Js();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                if (req.responseJS) {
                    // ���������� � <div> ��������� ������.
                    same=(req.responseJS.same||'');

                    if (same==0) {
                        initialize2();
                        setTimeout("initialize_off2()",3000);
                    } else {
                        alert("����� ��� ���� � ������� ���������!");
                    }

                    document.getElementById('numcompare').innerHTML = (req.responseJS.num||'');
				
                }
            }
        }
        req.caching = false;
        // �������������� ������.
        var truePath=dirPath();
        req.open('POST', truePath+'/phpshop/ajax/compare.php', true);
        req.send({
            xid: xid,
            num: num,
            same:same
        });
        if(document.getElementById("compare")) document.getElementById("compare").style.display='block';
    }
}	
	
// �������� ������ ��� ����������
function ReturnSortUrl(v){
    var s,url="";
    if(v>0){
        s=document.getElementById(v).value;
        if(s!="") url="v["+v+"]="+s+"&";
    }
    return url;
}

// ���������� �� ���� ��������
function GetSortAll(){
    var url=ROOT_PATH+"/shop/CID_"+arguments[0]+".html?";
    
    var i=1;
    var c=arguments.length;

    for(i=1; i<c; i++)
        if(document.getElementById(arguments[i])) url=url+ReturnSortUrl(arguments[i]);

    location.replace(url);
    
}

// ���������� �� ��������
function GetSort(id,sort){ 
    var path=location.pathname;
    if(sort!=0) location.replace(path+'?'+id+'='+sort);
    else location.replace(path);
}

// ��������� ����������
function systemInfo() {
    var req = new Subsys_JsHttpRequest_Js();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                Info= (req.responseJS.info||'');
                confirm(Info);
            }
        }
    }
    req.caching = false;
    req.open('POST', '/phpshop/ajax/info.php', true);
    req.send({
        test:303
    });
}

	
// ��������������� �� ������ ���������� �� ��������� ������
function getKey(e){
    var dir=dirPath();

    if (e == null) { // ie
        key = event.keyCode;
        var ctrl=event.ctrlKey;
    } else { // mozilla
        key = e.which;
        var ctrl=e.ctrlKey;
    }
    if((key=='123') && ctrl) window.location.replace(dir+'/phpshop/admpanel/');
    if(key=='120') systemInfo();
}
document.onkeydown = getKey; 

// �������� ��������� 
function default_load(copyrigh,protect){
    if(copyrigh=="true") window.status="Powered & Developed by PHPShop.ru";
    if(protect=="true"){
        if (document.layers) {
            document.captureEvents(event.mousedown)
        }
        document.onmousedown=mp;
    }
	vote_get();
}

// ������� �������� ������ �� ����������� ��� ������� ��������
function vote_get() {
	if (document.URL='http://prodacha.ru/page/china_engines.html') {
		var req = new Subsys_JsHttpRequest_Js();
		
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if (req.responseJS) {
					document.getElementById("question1_answer_yes").innerHTML=(req.responseJS.answers_yes||'');
					document.getElementById("question1_answer_no").innerHTML=(req.responseJS.answers_no||'');
				}
			}
		}
		req.caching = false;
		// �������������� ������.
		//alert(window.location.hostname);
		req.open('POST','../../../vote.php', true);
		req.send({
			// url ������
			q1_page: document.URL,
			//������ ������
			only_read: 1	
		});
	}
}

// �������� ������� �������� ������
function pressbutt_load_catalog(subm,dir){
    if(!dir) dir='';
    if(subm!='' && document.getElementById("p"+subm)){
        SUBMENU.visibility = 'visible';
        SUBMENU.position = 'relative';
    }

}


// �������� ������� �������� �������
function pressbutt_load(subm,dir,copyrigh,protect,psubm){
    var path=location.pathname;

    // ������ � �������
    if(document.getElementById("cat"+subm)){
        var IdStyle = document.getElementById("cat"+subm);
        if(IdStyle.className == 'catalog_forma') IdStyle.className='catalog_forma_open';
        else IdStyle.className='catalog_forma';
    }

    // �������� ���������
    var load=default_load(copyrigh,protect);

    // ������� ����� �����������
    if(path=="/users/" && document.getElementById("autorization")) document.getElementById("autorization").style.display='none';

    // ������� ����� ������
    var path=location.pathname;
    if(path=="/search/" && document.getElementById("search")) document.getElementById("search").style.display='none';

    // ������� ����� �������
    var path=location.pathname;
    if((path=="/order/" || path=="/done/") && document.getElementById("cart")) document.getElementById("cart").style.display='none';

    // ������� ����� ������
    var path=location.pathname;
    if((path=="/done/" || path=="/done/") && document.getElementById("cart")) document.getElementById("cart").style.display='block';

    // ��������� ������� ������
    var pattern=/page/;
    if(pattern.test(path)==true){
        var catalog=pressbutt_load_catalog(subm,dir);
    }
    else{
        // ������� �������
        if(!dir) dir='';
        if(subm!=''){
			if (typeof($("m"+psubm).css('style'))!=='undefined'){
				var SUBMENU = $("m"+psubm).css('style');//document.getElementById("m"+subm).style;
				SUBMENU.visibility = 'visible';
				SUBMENU.position = 'relative';
			}
        }
        // ������� ������
        if(psubm!=''){
			if (typeof($("m"+psubm).css('style'))!=='undefined'){
				var PSUBMENU = $("m"+psubm).css('style');//document.getElementById("m"+psubm).style;
				PSUBMENU.visibility = 'visible';
				PSUBMENU.position = 'relative';				
			}
        }
    }
}

// �������� ������������
function pressbutt(subm,num,dir,i,m){

    // ������ � �������
    if(document.getElementById("cat"+subm)){
        var IdStyle = document.getElementById("cat"+subm);
        if(IdStyle.className == 'catalog_forma') IdStyle.className='catalog_forma_open';
        else IdStyle.className='catalog_forma';
    }


    if(!dir) dir='';
    if(!m) m="m";
    if(!i) i="i";
    var SUBMENU = document.getElementById(m+subm).style;

    if (SUBMENU.visibility=='hidden'){
        SUBMENU.visibility = 'visible';
        SUBMENU.position = 'relative';
    }

    else{
        SUBMENU.visibility = 'hidden';
        SUBMENU.position = 'absolute';
    }

    for(j=0;i<num;j++)
        if(j != subm)
            if(document.all[m+j]){
                document.all[m+j].style.visibility = 'hidden';
                document.all[m+j].style.position = 'absolute';
            }
        }


// �������� ����� ���������
function CheckMessage(message){
    var message = document.getElementById("message").value;
    if(message=="") alert("������ ��������� ����� ���������!");
    else document.forma_message.submit();
}

// �������� ����� �������� �� �������
function NewsChek()
{
    var s1=window.document.forms.forma_news.mail.value;
    if (s1=="" || s1=="E-mail..."){
        alert("������ ���������� ����� ��������!");
        return false;
    }
    else
        document.forma_news.submit();
    return true;
}

// �������� ����� ������
function SearchChek()
{
    var s1=window.document.forms.forma_search.words.value;
    if (s1==""  || s1=="� ���..."){
        alert("������ ���������� ����� ������!");
        return false;
    }
    else document.forma_search.submit();
    return true;
}

//�������� ����� ������
function OrderChek() {
    var s1=window.document.forms.forma_order.mail.value;
    
    var s2_1=$('input[id="lastname_person"]:eq(0)').val();
    var s2_2=$('input[id="name_person"]:eq(0)').val();
    var s3=window.document.forms.forma_order.tel_name.value;
    var s4=window.document.forms.forma_order.adr_name.value;
    if (document.getElementById('#tk_other') && typeof ($('#tk_other').prop('disabled'))==='undefined'){
        var s5_1=$('#tk_other').val();
    }
    if (document.getElementById('tk_list') && typeof ($('#tk_other').prop('disabled'))==='string'){
        var s5_1=$("input[type=radio][name='tk_list_item']:checked").val();
    }
    if (document.getElementById('firstname')){
        var s5_2=window.document.forms.forma_order.firstname.value;
    }
    if (document.getElementById('middlename')){
        var s5_3=window.document.forms.forma_order.middlename.value;
    }
    if (document.getElementById('lastname')){
        var s5_4=window.document.forms.forma_order.lastname.value;
    }
    if (document.getElementById('pass_no1')){
        var s5_5=window.document.forms.forma_order.pass_no1.value;
    }
    if (document.getElementById('pass_no2')){
        var s5_6=window.document.forms.forma_order.pass_no2.value;
    }
    if (document.getElementById('pass_police')){
        var s5_7=window.document.forms.forma_order.pass_police.value;
    }
    if (document.getElementById('delivery_city')){
        var s5_8=window.document.forms.forma_order.delivery_city.value;
    }
    if (document.getElementById('postal_index')){
        var s5_9=window.document.forms.forma_order.postal_index.value;
        var s5_10=window.document.forms.forma_order.delivery_address.value;		
    }
    var adr_name_img=getNextElement(document.getElementsByName('adr_name')[0]);

    if (document.getElementsByName('dos_ot')[0] && document.getElementsByName('dos_do')[0]){
        //console.log($('input[name="dos_ot"]').css('border-color'));
        if ($('input[name="dos_ot"]').css('border-color')==='rgb(255, 85, 85)' || $('input[name="dos_do"]').css('border-color')==='rgb(255, 85, 85)'){
            alert("������ ���������� ������� ��������.\n����� �������� ������ ���� � ��������� � 08�. �� 22�.");
            return false;
        }
    }

    //console.log(document.getElementById("makeyourchoise"));
    if (document.getElementById("makeyourchoise").value==="DONE") {
        bad=0;
        if (document.getElementById("dostavka_metod").value==='1') bad=1;
    } else {
        bad=1;
    }
    //console.log(document.getElementById("dostavka_metod").value);
    //alert($.browser.android+' '+document.getElementById('tel_name').value.search(/^[0-9]{10}$/)+' '+typeof(adr_name_img));
    console.log($('#pass_no1').next('img').length);
	if ( adr_name_img ) {
		//alert('99');
		if (s1==="" || s2_1==="" || s2_2==="" || s3==="" || s4==="" ) {
			alert("������ ���������� ����� ������.\n������ ���������� �������� ��������� �����������! ");
		} else if (bad===1) {
			alert("������ ���������� ����� ������.\n�������� ��������!");
		} else if (document.getElementById('mail').value.toLowerCase().search(/^[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/) === -1) {
			//^[a-zA-Z0-9\_\.\+\-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9\-\.]+$
			alert("������ ���������� ����� ������.\n���� 'E-mail' ��������� �����������! ");
		} else {
                        console.log(document.getElementById('telregion').value);
			if (typeof($.browser.android)==='undefined') {
                            if (document.getElementById('telregion').value==1){
				if (document.getElementById('tel_name').value.search(/^\+7\([0-9]{3}\)[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/) === -1) {
					//^[0-9]{10}$/
					//([0-9]{5,11}) ^[0-9]{3,4}\ [0-9]{5,7}$
					alert("������ ���������� ����� ������.\n���� '�������' ��������� �����������! ");
					return false;
				}
                            }
                            if (document.getElementById('telregion').value==2){
				if (document.getElementById('tel_name').value.search(/^\+[0-9]{10,15}$/) === -1) {
					//^[0-9]{10}$/
					//([0-9]{5,11}) ^[0-9]{3,4}\ [0-9]{5,7}$
					alert("������ ���������� ����� ������.\n���� '�������' ��������� �����������! ");
					return false;
				}
                            }                            
			} else if (typeof($.browser.android)!=='undefined' && $.browser.android===true) {
                            if (document.getElementById('telregion').value==1){
				if (document.getElementById('tel_name').value.search(/^[0-9]{10}$/) === -1) {
					//^[0-9]{10}$/
					//([0-9]{5,11}) ^[0-9]{3,4}\ [0-9]{5,7}$
					alert("������ ���������� ����� ������.\n���� '�������' ��������� �����������! ");
					return false;
				}                                
                            }
                            if (document.getElementById('telregion').value==2){
				if (document.getElementById('tel_name').value.search(/^\+[0-9]{10,15}$/) === -1) {
					//^[0-9]{10}$/
					//([0-9]{5,11}) ^[0-9]{3,4}\ [0-9]{5,7}$
					alert("������ ���������� ����� ������.\n���� '�������' ��������� �����������! ");
					return false;
				}                                
                            }
			}
			document.forma_order.submit();
		}
	} else {
		if (s1==="" || 
                    s2_1==="" ||
                    s2_2==="" || 
                    s3==="" || 
                    s5_1==="" ||
                    s5_2==="" ||
                    s5_3==="" ||
                    s5_4==="" ||
                    (s5_7==="" && $('#pass_police').next('img').length) ||
                    s5_8==="" ||
                    s5_9==="" ||
                    s5_10==="") 
                {
                    alert("������ ���������� ����� ������.\n������ ���������� �������� ��������� �����������! ");
		} else if ((document.getElementById('telregion').value==1 || document.getElementById('telregion').value==2) && (s5_5==="" || s5_6==="") && $('#pass_no1').next('img').length && $('#pass_no2').next('img').length) {
                    alert("������ ���������� ����� ������.\n������ ���������� �������� ��������� �����������! ");
                } else if (bad===1) {
			alert("������ ���������� ����� ������.\n�������� ��������!");
		} else if (document.getElementById('mail').value.toLowerCase().search(/^[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/) === -1) {
			alert("������ ���������� ����� ������.\n���� 'E-mail' ��������� �����������! ");
		} else if (typeof (s5_1)!=='undefined' && s5_1.search(/^[A-Za-z\u0410-\u044F\u0401\u0451\u00C0-\u00FF\u00B5* -_]{3,40}$/) === -1) {
			alert("������ ���������� ����� ������.\n���� '������������ ��������' ��������� �����������! ");
		} else if (typeof (s5_2)!=='undefined' && s5_2.search(/^[A-Za-z\u0410-\u044F\u0401\u0451\u00C0-\u00FF\u00B5 -_]{2,28}$/) === -1) {
			alert("������ ���������� ����� ������.\n���� '���' ��������� �����������! ");
		} else if (typeof (s5_3)!=='undefined' && s5_3.search(/^[A-Za-z\u0410-\u044F\u0401\u0451\u00C0-\u00FF\u00B5 -_]{2,28}$/) === -1) {
			alert("������ ���������� ����� ������.\n���� '��������' ��������� �����������! ");
		} else if (typeof (s5_4)!=='undefined' && s5_4.search(/^[A-Za-z\u0410-\u044F\u0401\u0451\u00C0-\u00FF\u00B5 -_]{2,28}$/) === -1) {
			alert("������ ���������� ����� ������.\n���� '�������' ��������� �����������! ");
		} else if ((document.getElementById('telregion').value==1) && ((typeof (s5_5)!=='undefined' && s5_5.search(/^[0-9]{4}$/) === -1) ||
                        (typeof (s5_6)!=='undefined' && s5_6.search(/^[0-9]{6}$/) === -1)) && $('#pass_no1').next('img').length && $('#pass_no2').next('img').length) {
                        if (typeof (s5_5)!=='undefined' && s5_5.search(/^[0-9]{4}$/) === -1)
                            alert("������ ���������� ����� ������.\n���� '�����' ��������� �����������! ");
                        if (typeof (s5_6)!=='undefined' && s5_6.search(/^[0-9]{6}$/) === -1)
                            alert("������ ���������� ����� ������.\n���� '�����' ��������� �����������! ");
		} else if (typeof (s5_7)!=='undefined' && s5_7.search(/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/) === -1 && $('#pass_police').next('img').length) {
			alert("������ ���������� ����� ������.\n���� '���� ������' ��������� �����������! ");
		} else if (typeof (s5_8)!=='undefined' && s5_8.search(/^[A-Za-z\u0410-\u044F\u0401\u0451\u00C0-\u00FF\u00B5. -_]{2,55}/) === -1) {
			alert("������ ���������� ����� ������.\n���� '����� ��������' ��������� �����������! ");
		} else if (typeof (s5_9)!=='undefined' && s5_9.search(/^[0-9]{6}$/) === -1) {
			alert("������ ���������� ����� ������.\n���� '������' ��������� �����������! ");
		} else if (typeof (s5_10)!=='undefined' && s5_10.search(/^[0-9A-Za-z\u0410-\u044F\u0401\u0451\u00C0-\u00FF\u00B5/,. -_]{10,150}$/) === -1) {
			alert("������ ���������� ����� ������.\n���� '����� ��������' ��������� �����������! ");
		} else {
			if (typeof($.browser.android)==='undefined') {
                            console.log(document.getElementById('telregion').value);
                            console.log(document.getElementById('tel_name').value.replace());
                            if (document.getElementById('telregion').value==1){
				if (document.getElementById('tel_name').value.search(/^\+7\([0-9]{3}\)[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/) === -1) {
					//^[0-9]{10}$/
					//([0-9]{5,11}) ^[0-9]{3,4}\ [0-9]{5,7}$
					alert("������ ���������� ����� ������.\n���� '�������' ��������� �����������! ");
					return false;
				}
                                if (document.getElementById('tel2') && document.getElementById('tel2').value.search(/^\+7\([0-9]{3}\)[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/) === -1) {
                                        //^[0-9]{10}$/
                                        //([0-9]{5,11}) ^[0-9]{3,4}\ [0-9]{5,7}$
                                        alert("������ ���������� ����� ������.\n���� '������� ����������' ��������� �����������! ");
                                        return false;
                                }
                            }
                            if (document.getElementById('telregion').value==2){
				if (document.getElementById('tel_name').value.search(/^\+[0-9]{10,15}$/) === -1) {
					//^[0-9]{10}$/
					//([0-9]{5,11}) ^[0-9]{3,4}\ [0-9]{5,7}$
					alert("������ ���������� ����� ������.\n���� '�������' ��������� �����������! ");
					return false;
				}
                                if (document.getElementById('tel2') && document.getElementById('tel2').value.search(/^\+[0-9]{10,15}$/) === -1) {
                                        //^[0-9]{10}$/
                                        //([0-9]{5,11}) ^[0-9]{3,4}\ [0-9]{5,7}$
                                        alert("������ ���������� ����� ������.\n���� '������� ����������' ��������� �����������! ");
                                        return false;
                                }
                            }
			} else if (typeof($.browser.android)!=='undefined' && $.browser.android===true) {
                            if (document.getElementById('telregion').value==1){
				if (document.getElementById('tel_name').value.search(/^[0-9]{10}$/) === -1) {
					//^[0-9]{10}$/
					//([0-9]{5,11}) ^[0-9]{3,4}\ [0-9]{5,7}$
					alert("������ ���������� ����� ������.\n���� '�������' ��������� �����������! ");
					return false;
				}
                                if (document.getElementById('tel2').value.search(/^[0-9]{10}$/) === -1) {
                                        //^[0-9]{10}$/
                                        //([0-9]{5,11}) ^[0-9]{3,4}\ [0-9]{5,7}$
                                        alert("������ ���������� ����� ������.\n���� '������� ����������' ��������� �����������! ");
                                        return false;
                                }
                            }
                            if (document.getElementById('telregion').value==2){
				if (document.getElementById('tel_name').value.search(/^\+[0-9]{10,15}$/) === -1) {
					//^[0-9]{10}$/
					//([0-9]{5,11}) ^[0-9]{3,4}\ [0-9]{5,7}$
					alert("������ ���������� ����� ������.\n���� '�������' ��������� �����������! ");
					return false;
				}
				if (document.getElementById('tel2').value.search(/^\+[0-9]{10,15}$/) === -1) {
					//^[0-9]{10}$/
					//([0-9]{5,11}) ^[0-9]{3,4}\ [0-9]{5,7}$
					alert("������ ���������� ����� ������.\n���� '������� ����������' ��������� �����������! ");
					return false;
				}
                                
                            }
                            /*
                            if (document.getElementById('tel2').value.search(/^[0-9]{10}$/) === -1) {
                                    //^[0-9]{10}$/
                                    //([0-9]{5,11}) ^[0-9]{3,4}\ [0-9]{5,7}$
                                    alert("������ ���������� ����� ������.\n���� '������� ����������' ��������� �����������! ");
                                    return false;
                            }
                            */
			}
			if ($('#tk_delivery_points_list').length && $('#adr_name').length) {
                            $('#adr_name').val($('#adr_name').val()+'��������� � �����-���������� - �� ������� - '+$('#tk_delivery_points_list').val().replace(/(�����:)|(<br>)/ig, ""));
                        }
			document.forma_order.submit();
		}	
	}
}

// �������� ����� �������
function Fchek()
{
    var s1=window.document.forms.forma_gbook.name_new.value;
    var s2=window.document.forms.forma_gbook.tema_new.value;
    var s3=window.document.forms.forma_gbook.otsiv_new.value;
    if (s1=="" || s2=="" || s3=="")
        alert("������ ���������� ����� ������!");
    else
        document.forma_gbook.submit();
}


var combowidth='';
var comboheight='';


function initialize(){
    var cartwindow=document.getElementById('cartwindow');
    combowidth=cartwindow.offsetWidth;
    comboheight=cartwindow.offsetHeight;

    if (document.all && !document.querySelector){
        setInterval("staticit_ie()",50);

        if(navigator.appName == "Microsoft Internet Explorer"){
            cartwindow.filters.revealTrans.Apply();
            cartwindow.filters.revealTrans.Play();
        }

    }else{
        setInterval("staticit_ff()",50);
    }

    cartwindow.style.visibility="visible";
}

function initialize_off(){
    var cartwindow=document.getElementById('cartwindow');
    if (document.all && !document.querySelector){
        setInterval("staticit_ie()",50);
        cartwindow.style.visibility="hidden";
    }
    else{
        setInterval("staticit_ff()",50);
        cartwindow.style.visibility="hidden";
    }
// ���� ���� ����� ������������� �� �����
//location.replace("/order/");
}

function staticit_ie(){
    var cartwindow=document.getElementById('cartwindow');
    cartwindow.style.pixelLeft=document.body.scrollLeft+document.body.clientWidth-combowidth-10;
    
    // HTML
    cartwindow.style.pixelTop=document.body.scrollTop+document.body.clientHeight-comboheight;

    // XHTML
    //cartwindow.style.top=(document.documentElement.scrollTop+document.documentElement.clientHeight-comboheight) + "px"
}

function staticit_ff(){
    var cartwindow=document.getElementById('cartwindow');
    cartwindow.style.left=(document.body.scrollLeft+document.body.clientWidth-combowidth-10) + "px";
    
    // HTML
    cartwindow.style.top=(document.body.scrollTop+document.body.clientHeight-comboheight) + "px";
    
    // XHTML
    //cartwindow.style.top=(document.documentElement.scrollTop+document.documentElement.clientHeight-comboheight) + "px";
}

// ��� ��������� �������
function initialize2(){
    var comparewindow=document.getElementById('comparewindow');
    combowidth=comparewindow.offsetWidth;
    comboheight=comparewindow.offsetHeight;
    if (document.all){
        setInterval("staticit_ie2()",50);

        if(navigator.appName == "Microsoft Internet Explorer"){
            comparewindow.filters.revealTrans.Apply();
            comparewindow.filters.revealTrans.Play();
        }

    }else{
        setInterval("staticit_ff2()",50);
    }

    comparewindow.style.visibility="visible";
}

function initialize_off2(){
    var comparewindow=document.getElementById('comparewindow');
    if (document.all){
        setInterval("staticit_ie2()",50);
        comparewindow.style.visibility="hidden";
    }
    else{
        setInterval("staticit_ff2()",50);
        comparewindow.style.visibility="hidden";
    }
}

function staticit_ie2(){
    var comparewindow=document.getElementById('comparewindow');
    comparewindow.style.pixelLeft=document.body.scrollLeft+document.body.clientWidth-combowidth-10;
    
    // HTML
    comparewindow.style.pixelTop=document.body.scrollTop+document.body.clientHeight-comboheight;
    
    // XHTML
    //comparewindow.style.top=(document.documentElement.scrollTop+document.documentElement.clientHeight-comboheight) + "px"
}

function staticit_ff2(){
    var comparewindow=document.getElementById('comparewindow');
    comparewindow.style.left=(document.body.scrollLeft+document.body.clientWidth-combowidth-10) + "px";
    
    // HTML
    comparewindow.style.top=(document.body.scrollTop+document.body.clientHeight-comboheight) + "px";
    
    // XHTML
    //comparewindow.style.top=(document.documentElement.scrollTop+document.documentElement.clientHeight-comboheight) + "px";
}