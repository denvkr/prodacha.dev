d=document;	
var isOpera=self.opera;
var isNav = (navigator.appName == "Netscape");
var isIE= (navigator.appName == "Microsoft Internet Explorer");
var isChrome=(navigator.userAgent.toLowerCase().indexOf('chrome') > -1);
if (isChrome) {//Для хрома
isNav=false;
isIE=true;
}
var timerID;
var domenu=0; //По умолчанию КМ выключено
var curpage="visiter";  //По умолчанию при ЛКМ действуем как при клике на товар
cMenus=['Nws'];		//Классы блоков, имеющих конт.меню

function starter(cpage) { //При загрузке фрейма
	domenu=1;
	if (cpage) {curpage=cpage;}
} //При загрузке фрейма


d.oncontextmenu=function(e){
if (domenu) {
	if(!isOpera&&!d.all)event=e;
	var o=d.all?event.srcElement:event.target;
	while(o){	//поиск конт.меню среди родительских блоков
		for(var i=0;i<cMenus.length;i++){
			if(o.id.substring(0,cMenus[i].length)==cMenus[i]) {break;}
		}
		if(i<cMenus.length)break;
		o=o.offsetParent;
	}
	if(o){
		cMenu(o,i);
		event.returnValue=!1;
	}else {
		cMenuOff();	//откр./откл.конт.меню по клику
	}
	//event.returnValue=!1;	//здесь - если надо вообще отменить системное конт.меню
}
}

d.onclick=function(e){
if (domenu) {
	if(isOpera) {
		DoWith(999,IDS); //Редактировать по ЛКМ
//		d.oncontextmenu(); //Выдвигаем контекстное меню по ЛКМ (специальное для оперы)
	}else if (isNav){
	}else {
		if (IDS!==0) {DoWith(999,IDS);} else {cMenuOff();}
	}
}
}	//откл.конт.меню по кликам

if (isNav) document.addEventListener("click",show, true);

function show(event){//Функция обработчик кнопок мыши для FF
if (domenu) {
	if(event.button == 0) {//Для первой кнопки
		if (IDS!==0) {DoWith(999,IDS);} else {cMenuOff();}
	} else if(event.button == 1) {
		//  alert("Midle");
	} else {
		cMenuOff();
	}
}
}

cMenuOff=function(j){		//закрыть все (или указанное) конт.меню
	if(!j) {
		for(var i=0;i<cMenus.length;i++) {
			d.getElementById("cMenu"+cMenus[i]).style.visibility='hidden';
		}
	} else {
		j.style.visibility='hidden';
	}
	//event.cancelBubble=0;
}
cMenuOff2=function(j){		//закрыть все (или указанное) конт.меню (убран cancelBubble, добавлен timerid)
  if (timerID) {clearInterval(timerID);}
  timerID = null;
	if(!j) {
		for(var i=0;i<cMenus.length;i++) {
			d.getElementById("cMenu"+cMenus[i]).style.visibility='hidden';
		}
	} else {
		j.style.visibility='hidden';
	}
}
	
function cMenu(t,i){
	cMenuOff();

	var o2=d.getElementById('cMenu'+cMenus[i]);

	var cheIDS=ifcheckboxs(document.form_flag); //Массив с галками
	var cheIDSAmount=cheIDS.length;
	
	if(cMenus[i]=='Nws'){	
		var coll = getLikeElements("A","name","tarurl");
		for(var ii=0;ii<coll.length;ii++) {
			var tip=coll[ii].id.replace("nameNews",""); //Получаем id для ссылки
			if (cheIDSAmount>0) {
				coll[ii].href='javascript:DoWithSelect("'+tip+'",document.form_flag,1000)';			
			} else {
				coll[ii].href='javascript:DoWith("'+tip+'",'+IDS+')';
			}
			
		}
		
	}
/*	
	o2.style.left=d.all?d.body.scrollTop+event.clientX:d.body.scrollLeft+event.pageX; //Проверяем на IEобразность и показываем около курсора

	if(isIE&&(d.getElementById("interfacesWin"))&&(curpage=="visiter")) {var topper=d.getElementById("interfacesWin").offsetTop;} else {var topper=0;} //Если слой  нас сдвинул, то  сдвинуть контменю обратно на величину сдвижки.
	
	//alert(topper);


	o2.style.top=d.all?event.clientY-topper:event.pageY-topper; //Проверяем на IEобразность и показываем около курсора
*/

	o2.style.left=d.all?d.body.scrollLeft+event.clientX:d.body.scrollLeft+event.pageX; //Проверяем на IEобразность и показываем около курсора

	var topper=0;
	var inntopper=0;

	if (d.getElementById("interfacesWin")) {
		inntopper=d.getElementById("interfacesWin").scrollTop;
	}


	if(isIE&&(d.getElementById("interfacesWin"))&&(curpage=="visiter")) {var topper=d.getElementById("interfacesWin").offsetTop;} //Если слой  нас сдвинул, то  сдвинуть контменю обратно на величину сдвижки.
	o2.style.top=d.all?event.clientY-topper+d.body.scrollTop+inntopper:event.pageY-topper+d.body.scrollTop; //Проверяем на IEобразность и показываем около курсора


	o2.style.visibility ='visible';
	event.cancelBubble=!0;
	if (timerID) {clearInterval(timerID);} timerID = null;
	timerID =setTimeout("cMenuOff2()", 6000);	//После создания меню прячем его через секунду, если кто-нть не наведет на слой мыш
}

//alert(curpage);

//Модифицированные  переопределенные функции PHPSHOP
function DoWith (tip,IDS) {
if (document.location.href.indexOf(".php?")==-1) {var dots="";} else {var dots=".";} //Багообразное условие проверки откуда стартует скрипт	
	if(tip==9){
		miniWin(dots+'./product/adm_product_new.php?productID='+IDS,650,630);
	} else if(tip==24){// Характеристки
		if(window.top.frame2.document.getElementById("catal")){
			var catal=window.top.frame2.document.getElementById("catal").value;
			miniWin(dots+'./window/adm_window.php?do='+tip+'&ids='+IDS+'&catal='+catal,300,220);
		}
	} else if(tip==38){// Новый заказ
		miniWin(dots+'./order/adm_visitor_new.php?orderAdd='+IDS,650,500);
	} else if(tip==999){// Редактирование товара
/*
		if (curpage=="product") miniWin(dots+'./product/adm_productID.php?productID='+IDS,650,630);
		if (curpage=="pages") miniWin(dots+'./page/adm_pagesID.php?id='+IDS,650,600);		
		if (curpage=="news") miniWin(dots+'./news/adm_newsID.php?id='+IDS,650,650);				
		if (curpage=="search_jurnal") {}						
		if (curpage=="search_pre") {miniWin(dots+'./report/adm_preID.php?id='+IDS,400,380);}
		if (curpage=="gbook") {miniWin(dots+'./gbook/adm_gbookID.php?id='+IDS,630,630);}										
		if (curpage=="visiter") {miniWin(dots+'./order/adm_visitorID.php?visitorID='+IDS,650,500);}
		if (curpage=="users") {miniWin(dots+'./shopusers/adm_userID.php?id='+IDS,500,500);}
		if (curpage=="notice") {miniWin(dots+'./product/adm_productID.php?productID='+IDS,700,630);}
		if (curpage=="comment") {miniWin(dots+'./comment/adm_commentID.php?id='+IDS,650,530);}
		if (curpage=="chanels") {miniWin(dots+'./rssgraber/adm_chanelsID.php?id='+IDS,400,450);}
*/
	} else {
		miniWin(dots+'./window/adm_window.php?do='+tip+'&ids='+IDS,300,220);
	}
}
//Модифицированные  переопределенные функции PHPSHOP



///////////////Пока не используем
function remover() {
	if (timerID) {clearInterval(timerID);} timerID = null;	
	clearInterval(timerID);
	timerID =setTimeout("cMenuOff2()", 1000);
} //Убиратель меню после того как увели мыш
function stoptime() {
	clearInterval(timerID);
	if (timerID) {clearInterval(timerID);} timerID = null;
} //Остановка таймера, если мыш навели на слой с меню
///////////////Пока не используем



//Функция проверки наличия отмеченных чекбоксов.
function ifcheckboxs(obj) {
var IDS=new Array();
var j=0;
var num=1000;
for (var i=0;i<=num; i++){
	if (obj.elements[i]){
		if ((obj.elements[i]).checked){
			IDS[j]=(obj.elements[i]).value;
			j++;
		}
	}
}

return IDS;
}



//Для поиска нужных ссылок чтобы их потом заменить
function getLikeElements(tagName, attrName, attrValue) {
    var startSet;
    var endSet = new Array();
    if (tagName) {
        startSet = document.getElementsByTagName(tagName);    
    } else {
        startSet = (document.all) ? document.all : document.getElementsByTagName("*");
    }
    if (attrName) {
        for (var i = 0; i < startSet.length; i++) {
            if (startSet[i].getAttribute(attrName)) {
                if (attrValue) {
                    if (startSet[i].getAttribute(attrName) == attrValue) {
                        endSet[endSet.length] = startSet[i];
                    }
                } else {
                    endSet[endSet.length] = startSet[i];
                }
            }
        }
    } else {
        endSet = startSet;
    }
    return endSet;
}

