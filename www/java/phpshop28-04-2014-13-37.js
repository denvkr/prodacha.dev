/**
 * Поддержка JavaScript функций
 * @package PHPShopJavaScript
 * @author PHPShop Software
 * @version 2.1
 */

var ROOT_PATH="";

// Динамическое меню горизонтальной навигации
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


// Вывод фильтров в поиске
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
    // Подготваливаем объект.
    // Реальное размещение
    var dir=dirPath();
    req.open('POST', dir+'/phpshop/ajax/search.php', true);
    req.send({
        category: category
    });
}


// Прорисовка календаря
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
    // Подготваливаем объект.
    // Реальное размещение
    var dir=dirPath();
    req.open('POST', dir+'/phpshop/ajax/calres.php', true);
    req.send({
        year: year,
        month: month
    });
}

	
// Проверка формы связи
function CheckOpenMessage(){
    var tema = document.getElementById("tema").value;
    var name = document.getElementById("name").value;
    var content = document.getElementById("content").value;
    if(tema=="" || name=="" || content=="") alert("Ошибка заполения формы сообщения!\nДанные, отмеченные флажками обязательны для заполнения.");
    else document.forma_message.submit();
}


// Проверка формы пожаловаться на цену
function CheckPricemail(){
    var mail = document.getElementById("mail").value;
    var name = document.getElementById("name").value;
    var links = document.getElementById("links").value;
    var key = document.getElementById("key").value;
    if(mail=="" || name=="" || links=="" || key=="") alert("Ошибка заполнения формы сообщения!\nДанные, отмеченные флажками обязательны для заполнения.");
    else forma_pricemail.submit();
}

function LoadPath(my_path){
    ROOT_PATH = my_path;
}

function dirPath(){
    return ROOT_PATH;
}

// Активная кнопка
function ButOn(Id){
    Id.className='imgOn';
}

function ButOff(Id){
    Id.className='imgOff';
}

// Обновить картинку
function CapReload(){
    var dd=new Date();
    document.getElementById("captcha").src="../phpshop/captcha.php?time="+dd.getTime();
}

// Смайлики
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

// Подсчет лимита символов
function countSymb(lim) {
    var lim = lim || 500;
    if (document.getElementById("message").value.length > lim) {
        alert("К сожалению, вы превысили максимально допустимую длину комментария");
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

// Комментарии
function commentList(xid,comand,page,cid) {
    var message="";

    if(comand == "add") {
        message = document.getElementById('message').value;
        alert ("Комментарий будет доступен после прохождения модерации...");
    }

    if(comand == "edit_add"){
        message = document.getElementById('message').value;
        cid = document.getElementById('commentEditId').value;
        document.getElementById('commentButtonAdd').style.visibility = 'visible';
        document.getElementById('commentButtonEdit').style.visibility = 'hidden';
    }

    if(comand == "dell"){
        if(confirm("Вы действительно хотите удалить комментарий?")){
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
                    if(req.responseJS.status == "error") alert("Функция добавления комментария возможна только для авторизованных пользователей.\nАвторизуйтесь или пройдите регистрацию.");
                    document.getElementById('commentList').innerHTML = (req.responseJS.comment||'');
                }
            }
        }
    }
    req.caching = false;
    // Подготваливаем объект.
    // Реальное размещение
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

// Фотогалерея
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
    // Подготваливаем объект.
    // Реальное размещение
    var dir=dirPath();
    req.open('POST', dir+'/phpshop/ajax/fotoload.php', true);
    req.send({
        xid: xid,
        fid: fid
    });
}

var flag = false;

// Просчет доставки
function UpdateDelivery(xid) {
    var req = new Subsys_JsHttpRequest_Js();
    var sum = document.getElementById('OrderSumma').value;
    var wsum = document.getElementById('WeightSumma').innerHTML;
	var adr_name_img=getNextElement(document.getElementsByName('adr_name')[0]);
	var adr_name_info=document.getElementsByName('adr_name')[0];
	var deliver_time_info=document.getElementsByName('delivery_time_info');	
	//модифицируем поле "Адрес и дополнительная информация:"
	//alert('var_'+xid);
	if (xid==10 || xid==11 || xid==13 || xid==14) {
		var delivery_options=document.getElementById('var_'+xid);
			//alert('var_'+xid);
		if ( delivery_options.selected==true && (xid==10 || xid==11 || xid==13 || xid==14) ) {
			//alert('var_'+xid);
			document.getElementById('address_and_info').innerHTML='Дополнительная<br>информация:';
			adr_name_img.parentNode.removeChild(adr_name_img);
			deliver_time_info[0].style.display='none';
			deliver_time_info[1].style.display='none';
			//adr_name_img.remove();
		}
	}  else {
		document.getElementById('address_and_info').innerHTML='Адрес и <br> дополнительная<br>информация:';
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

		//adr_name_img.src='/phpshop/templates/prodacha/images/shopflag_green.gif';
	}
	
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                document.getElementById('DosSumma').innerHTML = (req.responseJS.delivery||'');
                document.getElementById('d').value = xid;
                document.getElementById('TotalSumma').innerHTML = (req.responseJS.total||'');
                document.getElementById('seldelivery').innerHTML = (req.responseJS.dellist||'');
			
					var z=req.responseJS.total;	
					
					if (Number(z)<5000)
					{
					   document.getElementById('delivery_warning').style.display="table-cell";
					/*
						$("#var_1").attr("disabled", true);
						$("#var_1").hide();
							
						var firstVal = $('#dostavka_metod option:visible:first').val();
							
						if (flag==false)
						{	
							$('#dostavka_metod').val(firstVal);
								flag = true;
						}
					*/	
					} else if (Number(z)>=5000) {
					  document.getElementById('delivery_warning').style.display="none";
					}
					

            }
        }
    }
    req.caching = false;
    // Подготваливаем объект.
    // Реальное размещение
    var dir=dirPath();
		
    req.open('POST', dir+'/phpshop/ajax/delivery.php', true);
    req.send({
        xid: xid,
        sum: sum,
        wsum: wsum
    });
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
// Очистка корзины
function cartClean(){
    if(confirm("Вы действительно хотите очистить корзину?"))  window.location.replace('./?cart=clean');
}


// Удаление заявки
function NoticeDel(id){
    if(confirm("Вы действительно хотите удалить заявку?"))
        window.location.replace('./notice.html?noticeId='+id);
}

// Проверка наличия файла картинки, вставляем заглушку
function NoFoto(obj,pathTemplate){
    obj.src=pathTemplate +'/images/shop/no_photo.gif';
}

// Проверка наличия файла картинки, прячем картинку
function NoFoto2(obj){
    obj.height=0;
    obj.width=0;
}

// Коректировка размера картинки, отключена функция
function EditFoto(obj,max_width){
/*
var w,h,pr,max_height;
w=Number(obj.width);
if(w > max_width) obj.width = max_width;
*/
}

// Вывод полной формы прайс-листа
function GetAllForma(catId){
    if(catId!="" && catId!="ALL") window.location.replace("../shop/CID_"+catId+".html");
    if(catId=="ALL") alert('Для всех категорий форма с описанием не доступна! Выберите из выпадающего меню категорию и нажмите "показать". После этого форма с описание станет доступна.');
    if(catId=="") alert('Выберите из выпадающего меню категорию и нажмите "показать". После этого форма с описание станет доступна.');
}

// Сортировка прайса
function DoPriceSort(){
    var catId=document.getElementById("catId").value;
    location.replace("../price/CAT_SORT_"+catId+".html");
}

// Активация закладок
function NavActive(nav){
    if(document.getElementById(nav)){
        var IdStyle = document.getElementById(nav);
        IdStyle.className='menu_bg';
    }
}

// Проверка формы восстанволения пароля
function ChekUserSendForma(){
    var d=document.userpas_forma;
    var login=d.login.value;
    if(login=="") alert("Ошибка заполнения формы восстанволения пароля");
    else d.submit();
}

// Проверка регистрации нового пользователя
function CheckNewUserForma(){
    var d=document.users_data;
    var login=d.login_new.value;
    var password=d.password_new.value;
    var password2=d.password_new2.value;
    var name=d.name_new.value;
    var mail=d.mail_new.value;
    var tel=d.tel_new.value;
    var adres=d.adres_new.value;

    if(name=="" || mail=="" || login=="" || password=="" || password!=password2)
        alert("Ошибка заполнения формы регистрации пользователя");
    else d.submit();
}

// Выход пользователя
function UserLogOut(){
    if(confirm("Вы действительно хотите выйти из личного кабинета?"))
        window.location.replace('?logout=true');
}


// Проверка смены пароля
function DispPasDiv(){
    if(document.getElementById("password_chek").checked) document.getElementById("password").style.display='block';
    else document.getElementById("password").style.display='none';
}

// Проверка изменения паролей пользователей
function UpdateUserPassword(){
    var d=document.users_password;
    var login=d.login_new.value;
    var password=d.password_new.value;
    var password2=d.password_new2.value;

    if(login=="" || password=="" || password!=password2){
        alert("Ошибка заполнения формы для изменения доступа");
        document.getElementById("password").style.display='block';
        document.getElementById("password_chek").checked="true";
    }
    else d.submit();
}

// Проверка изменения данных пользователей
function UpdateUserForma(){
    var d=document.users_data;
    var name=d.name_new.value;
    var mail=d.mail_new.value;

    if(name=="" || mail=="")
        alert("Ошибка заполнения формы для изменения данных");
    else d.submit();
}


// Проверка формы авторизации
function ChekUserForma(){
    var login=document.user_forma.login.value;
    var password=document.user_forma.password.value;
    if(login!="" || password!="")
        document.user_forma.submit();
    else alert("Ошибка заполнения формы авторизации");
}


function do_err(){
    return true
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

// Изменение кол-ва в поле
function ChangeNumProduct(pole,znak){

//ищем ближайщий id="num_new"
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

// Смена валюты
function ChangeValuta(){
    document.ValutaForm.submit();
}

// Смена скина
function ChangeSkin(){
    document.SkinForm.submit();
}

// Ajax добавление в корзину
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
                    alert("Этот товар добавлялся ранее с другой характеристикой. Количество товара в корзине увеличено и характеристика обновлена на последний вариант!");
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

    req.open('POST', truePath+'/phpshop/ajax/cartload.php', true);
    req.send({
        xid: xid,
        num: num,
        xxid: xxid,
        addname: addname,
        same: same,
        test:303
    });
}

// Добавление товара в корзину 1 шт.
function AddToCart(xid) {
    var num=1;
    var xxid=0;
    if (confirm("Добавить выбранный товар ("+num+" шт.) в корзину?")){
        ToCart(xid,num,xxid);
        if(document.getElementById("order")) document.getElementById("order").style.display='block';
		return true;
    }
	else
	{
		return false;
	}
}	
		
// Добавление товара в корзину N шт.
function AddToCartNum(xid,pole) {
    var num=Number(document.getElementById(pole).value);
    var xxid=xid;
    if(num<1) num=1;
    if(confirm("Добавить выбранный товар ("+num+" шт.) в корзину?"))
	{
        ToCart(xid,num,xxid);
        if(document.getElementById("order")) document.getElementById("order").style.display='block';
		return true;
    }
	else
	{
		return false;
	}
}
	
// Добавление подчиненного товара в корзину N шт.
function AddToCartParent(xxid) {
    var num=1;
    var xid=document.getElementById("parentId").value;
    if(confirm("Добавить выбранный товар ("+num+" шт.) в корзину?")){
        ToCart(xid,num,xxid);
        initialize();
        setTimeout("initialize_off()",3000);
        if(document.getElementById("order")) document.getElementById("order").style.display='block';
    }
}	

// Добавить товара в сравнение
function AddToCompare(xid) {
    var num=1;
    var same=0;
    if(confirm("Добавить выбранный товар в таблицу сравнения?")){

        var req = new Subsys_JsHttpRequest_Js();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                if (req.responseJS) {
                    // Записываем в <div> результат работы.
                    same=(req.responseJS.same||'');

                    if (same==0) {
                        initialize2();
                        setTimeout("initialize_off2()",3000);
                    } else {
                        alert("Товар уже есть в таблице сравнения!");
                    }

                    document.getElementById('numcompare').innerHTML = (req.responseJS.num||'');
				
                }
            }
        }
        req.caching = false;
        // Подготваливаем объект.
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
	
// Создание ссылки для сортировки
function ReturnSortUrl(v){
    var s,url="";
    if(v>0){
        s=document.getElementById(v).value;
        if(s!="") url="v["+v+"]="+s+"&";
    }
    return url;
}

// Сортировка по всем фильтрам
function GetSortAll(){
    var url=ROOT_PATH+"/shop/CID_"+arguments[0]+".html?";
    
    var i=1;
    var c=arguments.length;

    for(i=1; i<c; i++)
        if(document.getElementById(arguments[i])) url=url+ReturnSortUrl(arguments[i]);

    location.replace(url);
    
}

// Сортировка по фильтрам
function GetSort(id,sort){ 
    var path=location.pathname;
    if(sort!=0) location.replace(path+'?'+id+'='+sort);
    else location.replace(path);
}

// Системная информация
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

	
// Перенаправление на панель управления по сочетанию клавиш
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

// Загрузка установок 
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

// функция получает данные по голосованию для текущей страницы
function vote_get() {
	if (document.URL='http://test.prodacha.ru/page/china_engines.html') {
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
		// Подготваливаем объект.
		//alert(window.location.hostname);
		req.open('POST','../../../vote.php', true);
		req.send({
			// url статьи
			q1_page: document.URL,
			//только чтение
			only_read: 1	
		});
	}
}

// Загрузка позиции каталога статей
function pressbutt_load_catalog(subm,dir){
    if(!dir) dir='';
    if(subm!='' && document.getElementById("p"+subm)){
        SUBMENU.visibility = 'visible';
        SUBMENU.position = 'relative';
    }
}


// Загрузка позиции каталога товаров
function pressbutt_load(subm,dir,copyrigh,protect,psubm){
    var path=location.pathname;

    // Работа с классом
    if(document.getElementById("cat"+subm)){
        var IdStyle = document.getElementById("cat"+subm);
        if(IdStyle.className == 'catalog_forma') IdStyle.className='catalog_forma_open';
        else IdStyle.className='catalog_forma';
    }

    // Загрузка установок
    var load=default_load(copyrigh,protect);

    // Убираем форму авторизации
    if(path=="/users/" && document.getElementById("autorization")) document.getElementById("autorization").style.display='none';

    // Убираем форму поиска
    var path=location.pathname;
    if(path=="/search/" && document.getElementById("search")) document.getElementById("search").style.display='none';

    // Убираем форму корзины
    var path=location.pathname;
    if((path=="/order/" || path=="/done/") && document.getElementById("cart")) document.getElementById("cart").style.display='none';

    // Убираем форму заказа
    var path=location.pathname;
    if((path=="/done/" || path=="/done/") && document.getElementById("cart")) document.getElementById("cart").style.display='block';

    // Проверяем каталог статей
    var pattern=/page/;
    if(pattern.test(path)==true){
        var catalog=pressbutt_load_catalog(subm,dir);
    }
    else{
        // Каталог товаров
        if(!dir) dir='';
        if(subm!=''){
            var SUBMENU = document.getElementById("m"+subm).style;
            SUBMENU.visibility = 'visible';
            SUBMENU.position = 'relative';
        }
        // Каталог статей
        if(psubm!=''){
            var PSUBMENU = document.getElementById("m"+psubm).style;
            PSUBMENU.visibility = 'visible';
            PSUBMENU.position = 'relative';
        }
    }
}

// Открытие подкаталогов
function pressbutt(subm,num,dir,i,m){

    // Работа с классом
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


// Проверка формы сообщений
function CheckMessage(message){
    var message = document.getElementById("message").value;
    if(message=="") alert("Ошибка заполения формы сообщения!");
    else document.forma_message.submit();
}

// Проверка формы подписки на новости
function NewsChek()
{
    var s1=window.document.forms.forma_news.mail.value;
    if (s1=="" || s1=="E-mail..."){
        alert("Ошибка заполнения формы подписки!");
        return false;
    }
    else
        document.forma_news.submit();
    return true;
}

// Проверка формы поиска
function SearchChek()
{
    var s1=window.document.forms.forma_search.words.value;
    if (s1==""  || s1=="Я ищу..."){
        alert("Ошибка заполнения формы поиска!");
        return false;
    }
    else document.forma_search.submit();
    return true;
}

// Проверка формы заказа
function OrderChek()
{
    var s1=window.document.forms.forma_order.mail.value;
    var s2=window.document.forms.forma_order.name_person.value;
    var s3=window.document.forms.forma_order.tel_name.value;
    var s4=window.document.forms.forma_order.adr_name.value;
    if (document.getElementById("makeyourchoise").value=="DONE") {
        bad=0;
    } else {
        bad=1;
    }

    if (s1=="" || s2=="" || s3=="" || s4=="") {
        alert("Ошибка заполнения формы заказа.\nДанные отмеченные флажками заполнять обязательно! ");
    } else if (bad==1) {
        alert("Ошибка заполнения формы заказа.\nВыберите доставку!");
    } else{
        document.forma_order.submit();
    }
}

// Проверка формы отзывов
function Fchek()
{
    var s1=window.document.forms.forma_gbook.name_new.value;
    var s2=window.document.forms.forma_gbook.tema_new.value;
    var s3=window.document.forms.forma_gbook.otsiv_new.value;
    if (s1=="" || s2=="" || s3=="")
        alert("Ошибка заполнения формы отзыва!");
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
// Если идет сразу переадресация на заказ
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

// Для сравнения товаров
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
