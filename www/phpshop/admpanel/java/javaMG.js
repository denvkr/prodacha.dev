//***************************************************//
// PHPShop JavaScript 3                              //
// Copyright © www.phpshop.ru. Все права защищены.   //
//***************************************************//

function showGraph() {
    d = document;
    var tempp = d.getElementById('graph').style.display;
    if (tempp != "none") {
        d.getElementById('graph').style.display = "none";
        SetCookie('stat_graph', 0, 10);
    }
    else {
        d.getElementById('graph').style.display = "block";
        SetCookie('stat_graph', 1, 10);
    }
}

function PHPShopJS() {

    this.button_on = function(a) {
        this.classStyle(a.id, 'buton');
    }

    this.button_off = function(a) {
        this.classStyle(a.id, 'butoff');
    }

    this.rowshow_on = function(a) {
        this.classStyle(a.id, 'row_show_on');
    }

    this.rowshow_out = function(a, line) {
        if (line != ' line2')
            this.classStyle(a.id, 'row_show_off');
        else
            this.classStyle(a.id, 'row line2');

    }

    this.classStyle = function(a, name) {
        document.getElementById(a).className = name;
    }

    this.style = function(a, style) {
        document.getElementById(a).style = style;
    }

    this.value = function(a, value) {
        document.getElementById(a).value = value;
    }

    this.open = function(url, w, h) {
        var Width = getClientWidth();
        var Height = getClientHeight();
        Width = (Width / 2) - (w / 2);
        Height = (Height / 2) - (h / 2);
        window.open(url, "_blank", "dependent=1,left=" + Width + ",top=" + Height + ",width=" + w + ",height=" + h + ",location=0,menubar=0,resizable=1,scrollbars=0,status=0,titlebar=0,toolbar=0");
    }

    this.loadjs = function(page) {
        var data = document.createElement('script');
        data.type = 'text/javascript';
        data.async = true;
        data.id = page + '_jslib';
        data.src = page + '/gui/' + page + '.gui.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(data, s);
    }

    // Выделить все [1|2]
    this.selectall = function(value) {

        if (window.frame2)
            obj = window.frame2.document.flag_form;
        else
            obj = window.document.flag_form;

        if (value == 1) {
            for (var i = 0; i <= obj.length; i++)
                if (obj.elements[i])
                    (obj.elements[i]).checked = true;
        }
        else {
            for (var i = 0; i <= obj.length; i++)
                if (obj.elements[i])
                    (obj.elements[i]).checked = false;
        }
    }

    // Групповое действие
    this.action = function(a) {
        var num = 1000;

        if (window.frame2)
            obj = window.frame2.document.flag_form;
        else
            obj = window.document.flag_form;

        try {
            if (a != 0) {
                var IDS = new Array();
                var j = 0;
                for (var i = 0; i <= num; i++) {
                    if (obj.elements[i]) {
                        if ((obj.elements[i]).checked) {
                            IDS[j] = (obj.elements[i]).value;
                            j++;
                        }
                    }
                }

                if (IDS.length > 0)
                    PHPShopJS.open('window/adm_window.php?do=' + a + '&ids=' + IDS, 300, 220);
            }
        } catch (e) {
        }

        try {
            document.getElementById('action').value = 0;
            // document.getElementById('DoAll').checked=false;
        } catch (e) {
        }
    }
}

var PHPShopJS = new PHPShopJS();

function DoUpgrade(name) {
    document.getElementById(name).submit();
}

// Управление модулями
function DoUpdateModules(action, xid, pid) {
    var req = new Subsys_JsHttpRequest_Js();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                window.top.location.replace('../admin.php?page=modules&var2=' + pid);
            }
        }
    }
    req.caching = false;
    // Подготваливаем объект.
    req.open('POST', 'action.php?do=' + action, true);
    req.send({
        xid: xid
    });


}


// Калибровка
function savesize() {
    //Получаем новые и старые значения
    var width_icon = document.getElementById("width_icon").value;
    var newW = document.getElementById("neww").value;
    var newH = document.getElementById("newh").value;
    var oldW = document.getElementById("oldw").value;
    var oldH = document.getElementById("oldh").value;

    var wiW = ((newW * (1 + width_icon / 100) / oldW) - 1) * 100;
    var wiH = ((newH * (1 + width_icon / 100) / oldH) - 1) * 100;
    if (wiW > wiH) {
        w = Math.round(wiW);
    } else {
        w = Math.round(wiH);
    }
    document.getElementById("width_icon_new").value = w;

    if (w != width_icon) {//Если введено новое значение размера
        document.getElementById("sizeSaver").value = "Попытка сохранения";
        var req = new Subsys_JsHttpRequest_Js();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                //        if (req.responseJS) {
                //При успешном сохранении
                document.getElementById("sizeSaver").value = "Сохранено!";
                document.getElementById("sizeSaver").disabled = true;

                if (document.getElementById("calOk"))
                    document.getElementById("calOk").value = "Закрыть";
                //        }
            }
        }
        req.caching = false;
        // Подготваливаем объект.
        req.open('POST', '/phpshop/admpanel/interface/windowsresize.php', true);
        req.send({
            w: w
        });
    }



}


// Настройка подтипов товара
function ShowPodtipOption(v) {
    var obj = document.getElementById('podtip_list');
    if (v == 1)
        obj.style.visibility = "hidden";
    else
        obj.style.visibility = "visible";
}


// Управление панелью
function ClosePanelProductDisp() {
    var obj = document.getElementById('prevpanel_act');
    var clientW = document.body.clientWidth;
    var clientH = document.body.clientHeight;
    var mem = document.getElementById('prevpanel_mem').value;
    if (!obj.checked) {
        document.getElementById('prevpanel').innerHTML = "";


        // Новое окно
        if (window.opener)
            document.getElementById("frame2").height = (clientH - 150);
        else
            document.getElementById("frame2").height = (clientH - 70);

    } else {

        // Новое окно
        if (window.opener)
            document.getElementById("frame2").height = (clientH - 300);
        else
            document.getElementById("frame2").height = (clientH - 450);

        var req = new Subsys_JsHttpRequest_Js();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                if (req.responseJS) {
                    document.getElementById('prevpanel').innerHTML = req.responseJS.interfaces;
                    document.getElementById('prevpanel_mem').value = mem;
                }
            }
        }
        req.caching = false;
        // Подготваливаем объект.
        req.open('POST', './product/action.php?do=prev', true);
        req.send({
            xid: mem
        });


    }
}


// Описание товара в списке
function DoUpdateProductDisp(xid) {
    var obj = window.top.document.getElementById('prevpanel_act');
    var clientW = window.top.document.body.clientWidth;
    var clientH = window.top.document.body.clientHeight;
    if (obj.checked) {

        // Новое окно
        if (window.top.opener)
            window.top.document.getElementById("frame2").height = (clientH - 300);
        else if (window.top.document)
            window.top.document.getElementById("frame2").height = (clientH - 450);

        var req = new Subsys_JsHttpRequest_Js();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                if (req.responseJS) {
                    window.top.document.getElementById('prevpanel').innerHTML = req.responseJS.interfaces;
                    window.top.document.getElementById('prevpanel_mem').value = xid;
                }
            }
        }
        req.caching = false;
        // Подготваливаем объект.
        req.open('POST', '../product/action.php?do=prev', true);
        req.send({
            xid: xid
        });
    } else {
        //window.top.document.getElementById('prevpanel').innerHTML = "";
        var req = new Subsys_JsHttpRequest_Js();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                if (req.responseJS) {
                    window.top.document.getElementById('prevpanel').innerHTML = req.responseJS.interfaces;
                    window.top.document.getElementById('prevpanel_mem').value = xid;
                }
            }
        }
        req.caching = false;
        // Подготваливаем объект.
        req.open('POST', '../product/action.php?do=info', true);
        req.send({
            xid: xid
        });
    }
}



// Панель справки в подчиненном окне
function helpWinParent(page) {
    try {
        window.opener.top.document.getElementById('helppage').value = page;
        window.opener.top.initSlide(0);
        window.opener.top.loadhelp();
    } catch (e) {
        alert("Справка отключена. Опция включается в настройках системы - режимы - интерактивная справка")
    }
    try {
        window.opener.top.focus();
    } catch (e) {
    }
}


// Панель справки
function initAnime() {
    anime = {
        wCurr: 0,
        wTarg: 0,
        wStep: 0,
        wDelta: 0,
        wTravel: 0,
        vel: 1,
        pathLen: 1,
        interval: null
    };
}

function loadhelp() {
    anime.wCurr = document.getElementById("helpdiv").offsetWidth;
    if (anime.wCurr == 15) {  //Если состояние свернутое, значит надо загружать данные и

        preloader(1);
        var q = document.getElementById('helppage').value;
        var text = '';
        var req = new Subsys_JsHttpRequest_Js();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                if (req.responseJS) {
                    // Записываем в <div> результат работы.
                    var data = (req.responseJS.text || '');
                    document.getElementById('helpcontent').innerHTML = "" + data + "";
                    preloader(0);
                }
            }
        }
        req.caching = false;
        // Подготваливаем объект.
        req.open('POST', 'gethelp.php', true);
        req.send({
            q: q,
            text: text
        });

    }
}

function initSlide(killer) {
    initAnime();
    anime.wCurr = document.getElementById("helpdiv").offsetWidth;
    if ((anime.wCurr == 15) && (killer == 0)) {  //Если состояние свернутое, значит надо загружать данные и
        anime.wTarg = 255;
        //		document.getElementById("slidebutt").src="icon/helpout.gif";
    } else {
        anime.wTarg = 15;
        //		document.getElementById("slidebutt").src="icon/helpin.gif";
    }
    anime.pathLen = anime.wTarg - anime.wCurr;
    anime.wDelta = Math.abs(anime.pathLen);
    anime.vel = 10;
    anime.wStep = (anime.pathLen / Math.abs(anime.pathLen)) * anime.vel;
    anime.interval = setInterval("doSlide()", 10);
}

function doSlide() {
    if ((anime.wTravel + Math.abs(anime.wStep)) <= anime.wDelta) {
        var w = anime.wCurr + anime.wStep;
        document.getElementById("helpdiv").style.width = w + "px";
        anime.wTravel += Math.abs(anime.wStep);
        anime.wCurr = w;
    } else {
        document.getElementById("helpdiv").style.width = anime.wTarg + "px";
        clearInterval(anime.interval);
    }
}

function centerOnElement(baseElemID, posElemID) {
    baseElem = document.getElementById(baseElemID);
    posElem = document.getElementById(posElemID);
    var offsetTrail = baseElem;
    var offsetTop = 0;
    while (offsetTrail) {
        offsetTop += offsetTrail.offsetTop;
        offsetTrail = offsetTrail.offsetParent;
    }
    if (navigator.userAgent.indexOf("Mac") != -1 && typeof(document.body.leftMargin) != "undefined") {
        offsetTop += document.body.topMargin;
    }
    posElem.style.top = offsetTop + parseInt(baseElem.offsetHeight / 2) - parseInt(posElem.offsetHeight / 2) + "px";
}


function addOption(oListbox, text, value, isDefaultSelected, isSelected)
{
    var oOption = document.createElement("option");
    oOption.appendChild(document.createTextNode(text));
    oOption.setAttribute("value", value);

    if (isDefaultSelected)
        oOption.defaultSelected = true;
    else if (isSelected)
        oOption.selected = true;

    oListbox.appendChild(oOption);
}

function enterchar(num) {

    var sellist = document.getElementById("list" + num);
    var aoptions = sellist.options;
    var addit = document.getElementById("addval" + num).value;
    selopts = new Array;
    var masi = 0;

    for (i = 0; i < aoptions.length; i++) {
        var cse = aoptions[i].selected;
        if (cse == true) {
            selopts[masi] = aoptions[i].value;
            masi++;
        }
    }

    if (addit.length > 0) { //Если значение введено, значит работаем
        var req = new Subsys_JsHttpRequest_Js();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                if (req.responseJS) {
                    optsres = req.responseJS.interfaces;
                    sellist.options.length = 0;
                    addOption(sellist, "Нет данных", "", false, false);
                    for (i = 0; i < optsres.length; i++) {
                        addOption(sellist, optsres[i]['name'], optsres[i]['id'], false, optsres[i]['selected']);
                    }
                }
            }
        }
        req.caching = false;
        // Подготваливаем объект.
        req.open('POST', 'action_char.php', true);
        req.send({
            num: num,
            selopts: selopts,
            addit: addit
        });

    } else {//Нечего вводить!
        alert("Введите значение!");
    }

}


function GenPassword(a) {
    document.getElementById("pas1").value = a;
    document.getElementById("pas2").value = a;
    alert("Сгенерирован пароль: " + a);
}

function DispPasPole(p) {
    p.value = "";
    document.getElementById("pas2").disabled = false;
    document.getElementById("gen_button").disabled = false;
}


function TestPas() {
    var update = 0;
    if (document.getElementById("update")) {
        if (document.getElementById("update").checked == true)
            update = 1;
    } else
        update = 1;

    if (update == 1) {
        var pas1 = document.getElementById("pas1").value;
        var pas2 = document.getElementById("pas2").value;
        var login = document.getElementById("login").value;
        var mes_zag = "Внимание, обнаружены ошибки при заполнении формы:\n";
        var mes = "";
        var pattern = /\w+@\w+/;
        if (pas1.length < 6 || pas2.length < 6)
            mes += "-> Пароль должен содержать не менее 6 символов\n";
        if (pas1 != pas2)
            mes += "-> Пароли должны совпадать\n";
        if (login.length < 4)
            mes += "-> Логин должен содержать не менее 4 символов\n";
        if (mes != "")
            alert(mes_zag + mes);
        else
            document.product_edit.submit();
    } else
        document.product_edit.submit();
}

// Проверка дефолтных параметров
function rootNote() {
    if (confirm("Вы используете стандартный пароль и логин для входа в панель управления\nЭто может привести к взлому сайта. Сменить пароль администратора сейчас?"))
        miniWin('users/adm_userID.php?id=1', 500, 360)
}

function CloseProdForm(IDS) {
    if (confirm("Удалить все изображения к товару из галереи?\nПри отказе изображения автоматически появятся в создании следующего товара."))
        miniWin('../window/adm_window.php?do=40&ids=' + IDS, 300, 300);
    self.close();
}


function LoadAgent() {
    if (confirm("Загрузить Order Agent Windows на ваш компьютер?"))
        window.open("http://www.phpshop.ru/loads/ThLHDegJUj/setup.exe");
}

// Резиновый экран
function ResizeWin(page) {
    /*
     var clientW=document.body.clientWidth;
     var clientH=document.body.clientHeight;
     if(document.getElementById("interfacesWin") || document.getElementById("interfacesWin1")){
     
     
     // Если заказы
     //if(page=="orders") clientW=clientW-200;
     
     
     // Если новое окно
     if(window.opener){
     if(document.getElementById("interfacesWin"))
     document.getElementById("interfacesWin").style.height=(clientH-150);
     else {
     document.getElementById("interfacesWin1").height=(clientH-150);
     //document.getElementById("interfacesWin2").height=(clientH-150);
     }
     }
     // В тоже окно
     else{
     if(document.getElementById("interfacesWin"))
     document.getElementById("interfacesWin").style.height=(clientH-150);
     else {
     document.getElementById("interfacesWin1").height=(clientH-150);
     //document.getElementById("interfacesWin2").height=(clientH-150);
     }
     }
     }
     */
}


// Обновляем список галереи
function DoUpdateFotoList(xid) {
    var req = new Subsys_JsHttpRequest_Js();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                document.getElementById('fotolist').innerHTML = req.responseJS.interfaces;
            }
        }
    }
    req.caching = false;
    // Подготваливаем объект.
    req.open('POST', 'action.php?do=update', true);
    req.send({
        xid: xid
    });
}



function RoboxStatus(login, uid, crc) {
    var formData = "<robox.opstate.req><merchant_login>" + login + "</merchant_login><merchant_invid>" + uid + "</merchant_invid><crc>" + crc + "</crc></robox.opstate.req>";
    var xmlhttp = null;
    if (document.all)
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    else if (window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    if (xmlhttp)
    {
        try {
            xmlhttp.open("POST", "https://www.roboxchange.com/xmlssl/opstate.asp", false);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.send(formData);
        }
        catch (e) {
            //alert('error:'+(new Number(e.number)).toString(16)+'<br>\r\ndesc:'+e.description+'\r\n')
            alert("Настройки безопасности браузера не разрешают соединиться с сервером для проверки состояние платежа.\nВ настройках безопасности браузера разрешите доступ к источникам данных за пределами домена.")
        }

        var xmlrobox = xmlhttp.responseXML;
        var cur = xmlrobox.getElementsByTagName('out_curr');
        var date = xmlrobox.getElementsByTagName('date');
        var sum = xmlrobox.getElementsByTagName('out_cnt');
        var state = xmlrobox.getElementsByTagName('state');


        RoboxStatus.ReturnState = function(n) {
            switch (n) {
                case("5"):
                    return "только инициирована, деньги не получены";
                    break;
                case("10"):
                    return "деньги не были получены, операция отменена";
                    break;
                case("60"):
                    return "деньги после получения были возвращены пользователю";
                    break;
                case("80"):
                    return "исполнение операции приостановлено";
                    break;
                case("100"):
                    return "операция завершена успешно";
                    break;
                default:
                    return "заказа не существует!\nПроверьте поступления оплаты зака №" + uid + " на ваш счет...";
                    break;
            }
        }

        try {
            var str = "Валюта: " + cur[0].firstChild.nodeValue + "\nДата: " + date[0].firstChild.nodeValue + "\nСумма: " + sum[0].firstChild.nodeValue + "\nСтатус: " + RoboxStatus.ReturnState(state[0].firstChild.nodeValue);
            alert(str);
        }
        catch (e) {
            alert("Статус: " + RoboxStatus.ReturnState());
        }
    }
}


// Палетта цветов
function DoColor(color) {
    try {
        document.getElementById('color_new').value = color;
        document.getElementById('color_new').style.background = color;
    } catch (e) {
    }
}

// Корректировка размера окна
function DoResize(p, w, h) {
//var mywin = p/100;
//window.resizeTo(w+w*mywin, h+h*mywin);
}

var combowidth = '';
var comboheight = '';



function getClientWidth()
{
    return document.compatMode == 'CSS1Compat' && !window.opera ? document.documentElement.clientWidth : document.body.clientWidth;
}

function getClientHeight()
{
    return document.compatMode == 'CSS1Compat' && !window.opera ? document.documentElement.clientHeight : document.body.clientHeight;
}




function initialize() {
    try {
        cartwindow = document.getElementById('cartwindow');
        combowidth = cartwindow.offsetWidth;
        comboheight = cartwindow.offsetHeight;
        Width = getClientWidth();
        Height = getClientHeight();
        if (document.all) {
            cartwindow.style.pixelLeft = Width - combowidth - 10;
            cartwindow.style.pixelTop = Height - comboheight;

            if (navigator.appName == "Microsoft Internet Explorer") {
                cartwindow.filters.revealTrans.Apply();
                cartwindow.filters.revealTrans.Play();
            }
        } else {
            cartwindow.style.left = (Width - combowidth - 10) + "px";
            cartwindow.style.top = (Height - comboheight) + "px";
        }
        cartwindow.style.visibility = "visible";
    } catch (e) {
    }
}

function initializelicense() {
    try {
        licensewindow = document.getElementById('licensewindow');
        combowidth = licensewindow.offsetWidth;
        comboheight = licensewindow.offsetHeight;
        Width = getClientWidth();
        Height = getClientHeight();
        if (document.all) {
            licensewindow.style.pixelLeft = Width - combowidth - 730;
            licensewindow.style.pixelTop = Height - comboheight;

            if (navigator.appName == "Microsoft Internet Explorer") {
                licensewindow.filters.revealTrans.Apply();
                licensewindow.filters.revealTrans.Play();
            }
        } else {
            licensewindow.style.left = (Width - combowidth - 730) + "px";
            licensewindow.style.top = (Height - comboheight) + "px";
        }
        licensewindow.style.visibility = "visible";
    } catch (e) {
    }
}

function initializecomment() {
    try {
        commentwindow = document.getElementById('commentwindow');
        combowidth = commentwindow.offsetWidth;
        comboheight = commentwindow.offsetHeight;
        Width = getClientWidth();
        Height = getClientHeight();
        if (document.all) {
            commentwindow.style.pixelLeft = Width - combowidth - 250;
            commentwindow.style.pixelTop = Height - comboheight;

            if (navigator.appName == "Microsoft Internet Explorer") {
                commentwindow.filters.revealTrans.Apply();
                commentwindow.filters.revealTrans.Play();
            }
        } else {
            commentwindow.style.left = (Width - combowidth - 250) + "px";
            commentwindow.style.top = (Height - comboheight) + "px";
        }
        commentwindow.style.visibility = "visible";
    } catch (e) {
    }
}


function initializemessage() {
    try {
        messagewindow = document.getElementById('messagewindow');
        combowidth = messagewindow.offsetWidth;
        comboheight = messagewindow.offsetHeight;
        Width = getClientWidth();
        Height = getClientHeight();
        if (document.all) {
            messagewindow.style.pixelLeft = Width - combowidth - 490;
            messagewindow.style.pixelTop = Height - comboheight;

            if (navigator.appName == "Microsoft Internet Explorer") {
                messagewindow.filters.revealTrans.Apply();
                messagewindow.filters.revealTrans.Play();
            }
        }
        else {
            messagewindow.style.left = (Width - combowidth - 490) + "px";
            messagewindow.style.top = (Height - comboheight) + "px";
        }
        messagewindow.style.visibility = "visible";
    } catch (e) {
    }
}

function initialize_off() {
    cartwindow.style.visibility = "hidden";
    DoMessageComment();
}

function initializelicense_off() {
    licensewindow.style.visibility = "hidden";
}

function initializecomment_off() {
    commentwindow.style.visibility = "hidden";
    DoMessageMessage();
}

function initializemessage_off() {
    messagewindow.style.visibility = "hidden";
}

function staticit_ie() {
    cartwindow.style.pixelLeft = document.body.scrollLeft + document.body.clientWidth - combowidth - 30;
    cartwindow.style.pixelTop = document.body.scrollTop + document.body.clientHeight - comboheight;
}

function startmessage() {
    setTimeout("initialize()", 1000);
    setTimeout("initialize_off()", 4000);
}

function startmessagelicense() {
    setTimeout("initializelicense()", 5000);
//setTimeout("initializelicense_off()",5000);
}

function startmessagecomment() {
    setTimeout("initializecomment()", 2000);
    setTimeout("initializecomment_off()", 5000);
}

function startmessagemessage() {
    setTimeout("initializemessage()", 3000);
    setTimeout("initializemessage_off()", 6000);
}

function startnotice() {
    setTimeout("initialize()", 1000);
//setTimeout("initialize_off()",10000);
}

// Модальное окно
function DoModalDialog(path, w, h) {
    window.showModalDialog(path, print, "dialogWidth:" + w + "px;dialogHeight:" + h + "px;edge:Raised;center:Yes;help:No;resizable:No;status:No;");
}

// Редактируем скидку из заказа
function DoUpdateDiscountFromOrder(xid, uid) {
    var req = new JsHttpRequest();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            document.getElementById('interfaces').innerHTML = req.responseJS.interfaces;
            //setTimeout("window.close()",0);
        }
    }
    req.open(null, 'action.php?do=discount', true);
    req.send({
        xid: xid,
        uid: uid
    });
}

// Новый товар в заказ
function DoAddProductFromOrder(xid, uid) {
    if (xid != "") {
        if (confirm("Внимание!\nВы действительно хотите добавить новый товар в заказ?")) {
            var req = new JsHttpRequest();
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    document.getElementById('interfaces').innerHTML = req.responseJS.interfaces;
                }
            }
            req.open(null, 'action.php?do=add', true);
            req.send({
                xid: xid,
                uid: uid
            });
        }
    }
    else
        alert("Укажите ID товара!");
}

// Редактируем доставку из заказа
function DoUpdateDeliveryFromOrder(xid, uid) {
    var req = new JsHttpRequest();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            window.opener.document.getElementById('interfaces').innerHTML = req.responseJS.interfaces;
            setTimeout("window.close()", 0);
        }
    }
    req.open(null, 'action.php?do=delivery', true);
    req.send({
        xid: xid,
        uid: uid
    });
}

// Редактируем товар из заказа
function DoUpdateFromOrder(xid, uid, name, num, price) {
    var req = new JsHttpRequest();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            window.opener.document.getElementById('interfaces').innerHTML = req.responseJS.interfaces;
            setTimeout("window.close()", 0);
        }
    }
    req.open(null, 'action.php?do=update', true);
    req.send({
        xid: xid,
        uid: uid,
        name: name,
        num: num,
        price: price
    });
}

// Удаляем товар из заказа
function DoDelFromOrder(xid, uid) {
    if (confirm("Внимание!\nДанная операция может привести к потере позиции.\nВы действительно хотите выполнить данную команду?")) {
        var req = new JsHttpRequest();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                window.opener.document.getElementById('interfaces').innerHTML = req.responseJS.interfaces;
                setTimeout("window.close()", 0);
            }
        }
        req.open(null, 'action.php?do=del', true);
        req.send({
            xid: xid,
            uid: uid
        });
    }
}


// Всплывыающее окно нового заказа 
function DoMessage() {
    name = "start";
    var req = new JsHttpRequest();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS.order == 1)
                startmessage();

        }
    }

    req.open(null, 'interface/cartwindow.php', true);
    req.send({
        name: name
    });
    DoMessageComment();
    DoMessageMessage();
}



// Всплывыающее окно нового отзыва
function DoMessageComment() {
    name = "start";
    var req = new JsHttpRequest();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS.order == 1)
                startmessagecomment();
        }
    }

    req.open(null, 'interface/comment.php', true);
    req.send({
        name: name
    });
}

// Всплывыающее окно нового сообщения
function DoMessageMessage() {
    name = "start";
    var req = new JsHttpRequest();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS.order == 1)
                startmessagemessage();
        }
    }

    req.open(null, 'interface/message.php', true);
    req.send({
        name: name
    });
}

// Панель настроек загрузки 1с
function Option1c(tip) {
    d = document;
    switch (tip) {
        case 0:
            d.getElementById('pole_1c_option').style.display = "none";
            d.getElementById('pole_user_option').style.display = "none";
            d.getElementById('1c_tree_check').value = 1;
            break;

        case 1:
            d.getElementById('pole_1c_option').style.display = "block";
            d.getElementById('pole_user_option').style.display = "none";
            d.getElementById('1c_tree_check').value = 0;
            break;

        case 2:
            d.getElementById('pole_1c_option').style.display = "none";
            d.getElementById('pole_user_option').style.display = "block";
            d.getElementById('1c_tree_check').value = 2;
            break;

    }
}


// Если загружается список каталогов
function UpdateFileNameBase1C(name) {
    var d = document;
    var pattern = /tree/;
    var pattern2 = /user/;
    if (pattern.test(name) == true) {
        d.getElementById('filenametree').checked = true;
        d.getElementById('1c_target_check').value = 1;
        d.getElementById('pole_1c_option').style.display = "none";
        d.getElementById('pole_user_option').style.display = "none";
    }
    else if (pattern2.test(name) == true) {
        d.getElementById('filenameuser').checked = true;
        d.getElementById('1c_target_check').value = 2;
        d.getElementById('pole_user_option').style.display = "block";
        d.getElementById('pole_1c_option').style.display = "none";
    }
    else {
        d.getElementById('filenamebase').checked = true;
        d.getElementById('1c_target_check').value = 0;
        d.getElementById('pole_1c_option').style.display = "block";
        d.getElementById('pole_user_option').style.display = "none";
    }
}


// Загрузка базы из 1C 
function DoLoadBase1C(value, page, name) {
    var tip = new Array();
    var totalItems = 30;
    d = document;
    if (page == "predload") {
        i = 1;
        while (i < totalItems) {
            if (d.getElementById('tip_' + i))
                if (d.getElementById('tip_' + i).checked == true)
                    tip[i] = 1;
            i++;
        }
    }

    if (page == "load") {
        i = 1;
        while (i < totalItems) {
            if (d.getElementById('tip_' + i))
                tip[i] = d.getElementById('tip_' + i).value;
            i++;
        }
    }

    preloader(1);
    var req = new JsHttpRequest();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            document.getElementById('interfaces').innerHTML = req.responseJS.content;
            preloader(0);
        }
    }

    // Действия
    if (d.getElementById('1c_target_check').value == 1)
        target = "admin_tree_csv.php";
    if (d.getElementById('1c_target_check').value == 0)
        target = "admin_csv.php";
    if (d.getElementById('1c_target_check').value == 2)
        target = "admin_user_csv.php";

    req.open(null, '1c/' + target, true);
    req.send({
        'file': value,
        tip: tip,
        page: page,
        name: name
    });
}


// Загрузка базы из файла 
function DoLoadBase(value, page, name) {
    var tip = new Array();
    var d = document;
    var totalItems = 25;
    if (page == "predload") {

        i = 1;
        while (i < totalItems) {
            if (d.getElementById('tip_' + i))
                if (d.getElementById('tip_' + i).checked == true)
                    tip[i] = 1;
            i++;
        }
        tip[16] = d.getElementById('tip_16').value;
    }

    if (page == "load") {
        i = 1;
        while (i < totalItems) {
            if (d.getElementById('tip_' + i))
                tip[i] = d.getElementById('tip_' + i).value;
            i++;
        }
    }
    preloader(1);
    var req = new JsHttpRequest();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            document.getElementById('interfaces').innerHTML = req.responseJS.content;
            DoCheckInterfaceLang('csv_base', 'self');
            preloader(0);
        }
    }

    req.open(null, 'export/admin_csv_base.php', true);
    req.send({
        'file': value,
        tip: tip,
        name: name,
        page: page
    });
}





// Загрузка прайса
function DoLoad(value, page, name, pages) {
    preloader(1);
    var req = new JsHttpRequest();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            document.getElementById('interfaces').innerHTML = req.responseJS.content;
            DoCheckInterfaceLang(pages, 'self');
            preloader(0);
        }
    }

    req.open(null, 'export/admin_csv.php', true);
    req.send({
        'file': value,
        name: name,
        page: page
    });
}



// Интерфейс перезагрузка
function DoReloadMainWindow(page, var1, var2)
{
    if (window.opener.document.getElementById('cartwindow')) {
        if (page != "") {
            preloadertop(1)
            var req = new Subsys_JsHttpRequest_Js();
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.responseJS) {
                        if (window.opener.document.getElementById('interfaces')) {
                            window.opener.document.getElementById('interfaces').innerHTML = (req.responseJS.xid || '');
                            //DoCheckInterfaceLang(page,'top');
                            //ResizeWin(page);
                            preloadertop(0);
                            window.close();
                            //setTimeout("window.close()",500);
                        }


                    }
                }
            }
            req.caching = false;
            // Подготваливаем объект.
            req.open('POST', '../interface/api.php', true);
            req.send({
                xid: 1,
                page: page,
                tit: 1,
                var1: var1,
                var2: var2,
                test: 304
            });
        }
        else
            self.close();
    } else {
        self.close();
        window.opener.document.location.reload();
    }
}

// Интерфейс
function DoReload(page, var1, var2, var3, var4) {

    if (null != document.getElementById('helppage')) {
        document.getElementById('helppage').value = page;
        initSlide(1);
    }

    preloader(1);
    domenu = 0;
    var req = new Subsys_JsHttpRequest_Js();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {

                // Записываем в <div> результат работы.
                document.getElementById('interfaces').innerHTML = (req.responseJS.xid || '');
                document.title = (req.responseJS.tit || '');

                // Загрузка дополнительного JS по требованию
                if (req.responseJS.js != null)
                    PHPShopJS.loadjs(page);

                // Сортировка
                sortables_init();

                preloader(0);
                // ResizeWin(page);

            }
        }
    }
    req.caching = false;
    // Подготваливаем объект.
    req.open('POST', './interface/api.php', true);
    req.send({
        xid: 1,
        page: page,
        tit: 1,
        var1: var1,
        var2: var2,
        var3: var3,
        var4: var4,
        test: 304
    });
}



// Вызов калькулятора характеритик
function CalcSort() {
    if (window.frame2.document.getElementById("catal")) {
        var catal = window.frame2.document.getElementById("catal").value;
        miniWin('./product/adm_calc_sort.php?id=' + catal, 500, 600);
    } else
        alert("Выберите подкаталог для редактирования");
}


// Копировать в буфер
function copyToClipboard() {
    document.getElementById('upload_log').select();
    var CopiedTxt = document.selection.createRange();
    CopiedTxt.execCommand("Copy");
    alert("Данные скопированы в буфер обмена.");
}

// Обзор картинок ресайз
function ReturnPicResize(id) {
    var pic = document.getElementById('pic_resize');
    var path = '../editor3/assetmanager/resize.php?id=' + id;
    miniWin(path, 350, 200);
}


// Обзор картинок
function ReturnPic(id) {
    var pic = document.getElementById(id);
    var path = '../editor3/assetmanager/assetmanager.php?name=' + pic.value + '&tip=' + id;

    miniWin(path, 640, 500);

}


// Печать
function DoPrint(path) {
    window.open(path, "_blank", "dependent=1,left=0,top=0,width=650,height=650,location=1,menubar=1,resizable=1,scrollbars=1,status=1,titlebar=1,toolbar=1");
//window.showModalDialog(path,print,"dialogWidth:650px;dialogHeight:550px;edge:Raised;center:Yes;help:No;resizable:Yes;status:No;");
}

function DoPrintFactura(path) {
    window.showModalDialog(path, print, "dialogWidth:10040px;dialogHeight:800px;edge:Raised;center:Yes;help:No;resizable:Yes;status:No;");
}

// Форма шаблонов
function ShablonAdd(pole, id) {
    var Shablon = document.getElementById(id).value;
    Shablon = Shablon + pole;
    document.getElementById(id).value = Shablon;
}

// Форма шаблонов
function ShablonPromt(id) {
    var pole = window.prompt("Введите слово", "");
    if (pole != null) {
        var Shablon = document.getElementById(id).value;
        Shablon = Shablon + pole;
        document.getElementById(id).value = Shablon;
    }
}

function ShablonDell(id) {
    document.getElementById(id).value = "";
}


// Вывод скриншота
function GetSkinIcon(skin) {
    var path = "../../templates/" + skin + "/icon/icon.gif";
    document.getElementById("icon").src = path;
}

function clockRus() {
    document.write("<div id='CSCClockRus' style='color:#000000;font-family:tahoma,verdana,sans-serif;font-size:8pt;font-weight:normal;'></div>");
    clockGetTimeRus();
}
function clockGetTimeRus() {
    var now = new Date();
    var year = now.getFullYear();
    var month = now.getMonth();
    var date = now.getDate();
    var day = now.getDay();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    month = monthName[month];
    day = dayNameFull[day];
    if (hour < 10) {
        hour = "0" + hour
    }
    if (minute < 10) {
        minute = "0" + minute
    }
    if (second < 10) {
        second = "0" + second
    }
    document.getElementById("CSCClockRus").innerHTML = day + ", " + date + " " + month + ", " + hour + ":" + minute + ":" + second;
    tickRus();
}
function tickRus() {
    setTimeout("clockGetTimeRus()", 100);
}

// Защита кода
function mp(e) {
    now = new Date();
    if (document.all) {
        if ((event.button == 2) || (event.button == 3)) {
            alert('Copyright 2003-' + now.getYear() + ' \© PHPShop\.ru\. All rights reserved\. ');
            return false
        }
    }
    if (document.layers) {
        if (e.which == 3) {
            alert('Copyright 2003-' + now.getYear() + ' \© PHPShop\.ru\. All rights reserved\. ');
            return false
        }
    }
}
if (document.layers) {
    document.captureEvents(event.mousedown)
}

/*
 function do_err(){return true}onerror=do_err;if(window.location.href.substring(0,4)=="file")window.location="about:blank";
 
 function atlpdp1(){for(wi=0;wi<document.all.length;wi++){if(document.all[wi].style.visibility!='hidden'){document.all[wi].style.visibility='hidden';document.all[wi].id='atlpdpst'}}}function atlpdp2(){for (wi=0;wi<document.all.length;wi++){if(document.all[wi].id=='atlpdpst')document.all[wi].style.visibility=''}}window.onbeforeprint=atlpdp1;window.onafterprint=atlpdp2;
 */

function ChekOptionDefault() {
    var browser = navigator.appName;
    confirm("Для работы с программой << PHPShop  - Панель управления >> советуем внести следующие изменения в настройках браузера  " + browser + "\n-----------------------------------------------------------------------------------------------------------------------------------------\n\n1. В браузере Internet Explorer войдите в меню: Сервис -> Cвойство обозревателя -> Безопасность. Уровень безопасности понизить до среднего уровня\n\n2. Сервис -> Cвойство обозревателя -> Безопасность -> Другой -> Разное -> Разрешить запущенные сценарием окна без ограничения на размеры и положение. В выбранной опции поставить переключатель на опцию разрешить.\n\n3. Сервис -> Cвойство обозревателя -> Безопасность -> Другой -> Разное -> Разрешить сценарии для элемента управления обозревателем  Internet Explorer. В выбранной опции поставить переключатель на опцию разрешить.\n\n4. При возникновении трудностей с авторизацией пользователей пройдите по ссылке  Сервис -> Cвойство обозревателя. Нажмите на кнопку 'Удалить Cookie' и подтвердите данную операцию.");
}

function SetCookie(name, value, days) {
    var today = new Date();
    expires = new Date(today.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = name + "=" + escape(value) + "; expires=" + expires.toGMTString();
}

function GetCookie(cookieName) {
    var cookieValue = '';
    var posName = document.cookie.indexOf(escape(cookieName) + '=');
    if (posName != -1) {
        var posValue = posName + (escape(cookieName) + '=').length;
        var endPos = document.cookie.indexOf(';', posValue);
        if (endPos != -1)
            cookieValue = unescape(document.cookie.substring(posValue, endPos));
        else
            cookieValue = unescape(document.cookie.substring(posValue));
    }
    return cookieValue;
}

// Прелоадер
function lock(fl) {
    if (navigator.appName == "Microsoft Internet Explorer") {
        var el = document.getElementById('lock');
        if (null != el) {
            el.style.visibility = (fl == 1) ? 'visible' : 'hidden';
            el.style.display = (fl == 1) ? 'block' : 'none';
        }
    }
}

// Прелоадер
function preloader(fl) {
    if (navigator.appName == "Microsoft Internet Explorer") {
        var el = document.getElementById('loader');
        if (null != el) {
            el.style.visibility = (fl == 1) ? 'visible' : 'hidden';
            el.style.display = (fl == 1) ? 'block' : 'none';
        }
    }
}

function preloadertop(fl) {
    if (navigator.appName == "Microsoft Internet Explorer") {
        var el = window.opener.document.getElementById('loader');
        if (null != el) {
            el.style.visibility = (fl == 1) ? 'visible' : 'hidden';
            el.style.display = (fl == 1) ? 'block' : 'none';
        }
    }
}

function Save() {
    document.forms.product_edit.elements.EditorContent.value = oEdit1.getHTMLBody();
    if (document.forms.product_edit.elements.EditorContent2)
        document.forms.product_edit.elements.EditorContent2.value = oEdit2.getHTMLBody();
    document.forms.product_edit.submit()
}

function GetMailTo(mail, tema) {
    window.open('mailto:' + mail + '?subject=' + tema);
}

// Активная кнопка
function ButOn(Id) {
    var IdStyle = document.getElementById("but" + Id);
    IdStyle.className = 'buton'
}

function ButOff(Id) {
    var IdStyle = document.getElementById("but" + Id);
    IdStyle.className = 'butoff'
}

function ButClick(Id) {
    var IdStyle = document.getElementById("but" + Id);
    IdStyle.className = 'butclick'
}

// Выделение товаров при нажатии v1.0
function ClickID(Id) {
    //var IdStyle = document.getElementById("r"+Id);
    //if(IdStyle){
    miniWin('../product/adm_productID.php?productID=' + Id, 650, 500);
//IdStyle.style.background = '#C0D2EC';
//}
}


// Поиск товаров v0.1
function SearchProducts(words) {
    window.frame2.location.replace('catalog/admin_cat_content.php?words=' + words);
}

// Поиск страниц v0.1
function SearchPage(words) {
    window.frame2.location.replace('page/admin_cat_content.php?words=' + words);
}

function AllPage() {
    try {
        window.frame2.location.replace('page/admin_cat_content.php?pid=all');
    } catch (e) {
        window.document.location.reload();
    }
}


function AllProducts() {
    try {
        window.frame2.document.location.replace('catalog/admin_cat_content.php?pid=all');
    } catch (e) {
        window.document.location.reload();
    }

}

function NewProductPage() {
    if (window.frame2.document.getElementById("catal")) {
        var catal = window.frame2.document.getElementById("catal").value;
    }
    miniWin('page/adm_pages_new.php?categoryID=' + catal, 700, 650);
}

function NewDelivery() {
    if (window.frame2.document.getElementById("catal")) {
        var catal = window.frame2.document.getElementById("catal").value;
    }
    miniWin('delivery/adm_delivery_new.php?categoryID=' + catal, 650, 630);
}

function NewDeliveryCatalog() {
    if (window.frame2.document.getElementById("catal")) {
        var catal = window.frame2.document.getElementById("catal").value;
    }
    return miniWin('delivery/adm_catalog_new.php?categoryID=' + catal, 650, 370);
}


function NewProduct() {
    if (window.frame2.document.getElementById("catal")) {
        var catal = window.frame2.document.getElementById("catal").value;
    }
    miniWin('product/adm_product_new.php?reload=true&categoryID=' + catal, 700, 650);
}

function NewUMessage() {
    if (window.frame2.document.getElementById("catal")) {
        var catal = window.frame2.document.getElementById("catal").value;
        if (catal != "ALL")
            miniWin('shopusers/adm_messages_new.php?UID=' + catal, 500, 370);
    } else
        alert("Выберите пользователя для создания сообщения");

}

function DeleteUMessages() {
    if (window.frame2.document.getElementById("catal")) {
        var catal = window.frame2.document.getElementById("catal").value;
        miniWin('./window/adm_window.php?do=42&ids=' + catal, 300, 300)
    } else
        alert("Выберите пользователя для УДАЛЕНИЯ всех сообщений");

}



function EditCatalogPage() {

    if (window.frame2.document.getElementById("catal")) {
        var catal = window.interfacesWin2.document.getElementById("catal").value;
        if (catal != 1000 && catal != 2000)
            miniWin('page/adm_catalogID.php?catalogID=' + catal, 600, 600);
    } else
        alert("Выберите подкаталог для редактирования");

}

function EditCatalogDelivery() {
    if (window.frame2) {
        var catal = window.frame2.document.getElementById("catal").value;
        return miniWin('delivery/adm_catalogID.php?id=' + catal, 600, 370);
    } else
        alert("Выберите подкаталог для редактирования");

}



function EditCatalog() {

    try {
        if (window.frame2.document.getElementById("catalog_products")) {
            if (window.frame2.document.getElementById("catal_chek"))
            {
                var catal = window.frame2.document.getElementById("catal_chek").value;
                if (catal != 1000001 && catal != 1000002)
                    miniWin('catalog/adm_catalogID.php?catalogID=' + catal, 650, 630);
            } else
                alert("Выберите каталог для редактирования");
        }
        else
            EditCatalogPage();

    } catch (e) {
        alert("Выберите каталог для редактирования");
        window.document.location.replace('./admin.php?page=cat_prod');
    }
}



// Выделение заказов при нажатии v1.0
function ClickUID(Id) {
    var IdStyle = document.getElementById("Order" + Id);
    if (IdStyle) {
        miniWin('order/adm_visitor.php?visitorUID=' + Id, 650, 500);
        IdStyle.style.background = '#C0D2EC';
    }
}


// Поиск и выделение заказов v1.2
function SearchOrder(OrderId) {
    var OrderIdStyle = document.getElementById("Order" + OrderId);
    if (OrderIdStyle) {
        OrderIdStyle.style.background = '#C0D2EC';
        location.href('#Order' + OrderId);
        miniWin('order/adm_visitor.php?visitorUID=' + OrderId, 650, 500);
    }
    else
        alert("Внимание!\nЗаказ №" + OrderId + " не обнаружен а базе.\nПроверьте правильность выбора диапазона даты.");
}

function StatusChek(tip) {
    if (tip == "Новый заказ") {
        document.getElementById("status_forma").disabled = true;
        document.getElementById("status_forma").value = '';
    }
    else
        document.getElementById("status_forma").disabled = false;
}

function ListChek(id) {
    document.getElementById(id).checked = true;
}

function PromptThis() {
    message1 = 'Внимание!\nДанная операция может привести к потери позиции.\nВы действительно хотите выполнить данную команду?';
    if (confirm(message1)) {
        document.getElementById("productDELETE").value = 'doIT';
        document.product_edit.submit();
    }
}

function SelectQuerySql(sql) {
    return document.getElementById("sql_area").value = sql;
}

function SqlSend2() {
    if (document.getElementById("csv_file").value.length != 0)
        if (confirm(message2))
            document.getElementById("sql_forma2").submit();
    /*"Внимание!\nДанная операция может привести к потере базы.\nВы действительно хотите выполнить данную команду?"*/
}

function SqlSend() {
    if (document.getElementById("sql_area").value.length != 0)
        if (confirm(message2))
            document.getElementById("sql_forma").submit();
        else
            return document.getElementById("sql_area").value = '';
}

function SelectAll(obj2, obj, num) {
    if (obj2.value == 1) {
        for (var i = 0; i <= obj.length; i++)
            if (obj.elements[i])
                (obj.elements[i]).checked = true;
    }
    else {
        for (var i = 0; i <= obj.length; i++)
            if (obj.elements[i])
                (obj.elements[i]).checked = false;
    }
}

function SelectAllBox(obj2, obj) {
    if (obj2.value == 1) {
        for (var i = 0; i <= obj.length; i++)
            if (obj.elements[i])
                (obj.elements[i]).checked = true;
        obj2.value = 2;
    }
    else {
        for (var i = 0; i <= obj.length; i++)
            if (obj.elements[i])
                (obj.elements[i]).checked = false;

        obj2.value = 1;
    }
}

function DoWithSelect(tip, obj, num) {

    if (document.location.href.indexOf(".php?") == -1) {
        var dots = "";
    } else {
        var dots = ".";
    }

    if (document.location.href.indexOf(".php?plugin") != -1)
        dots = "";
    if (document.location.href.indexOf("cat_prod") != -1)
        dots = "";
    if (document.location.href.indexOf(".php?page") != -1)
        dots = "";

    try {
        if (tip != 0) {
            var IDS = new Array();
            var j = 0;
            for (var i = 0; i <= num; i++) {
                if (obj.elements[i]) {
                    if ((obj.elements[i]).checked) {
                        IDS[j] = (obj.elements[i]).value;
                        j++;
                    }
                }
            }


            if (tip == 9) {
                if (j > 1)
                    alert('Внимание!\nДанная операция может быть выполнена только с одним объектом.\nУберите ненужные флажки.');
                if (j == 1)
                    miniWin(dots + './product/adm_product_new.php?productID=' + IDS, 700, 650);
            }
            else if (tip == 8) {
                // Выгрузка в CSV
                miniWin('./export/adm_csv.php?IDS=' + IDS, 100, 100);
            }
            else if (tip == 24) {// Характеристки
                if (window.frame2.document.getElementById("catal")) {
                    var catal = window.frame2.document.getElementById("catal").value;
                    miniWin(dots + './window/adm_window.php?do=' + tip + '&ids=' + IDS + '&catal=' + catal, 500, 500);
                }
            }
            else if (tip == 38) {// Новый заказ
                if (j > 1)
                    alert('Внимание!\nДанная операция может быть выполнена только с одним объектом.\nУберите ненужные флажки.');
                if (j == 1)
                    miniWin(dots + './order/adm_visitor_new.php?orderAdd=' + IDS, 650, 500);
            }

            else if (IDS.length > 0)
                miniWin(dots + './window/adm_window.php?do=' + tip + '&ids=' + IDS, 300, 220);



        }
    } catch (e) {
        alert("Выберите категорию для выполнения операций...");
    }
    ;

    try {
        document.getElementById('actionSelect').value = 0;
        document.getElementById('DoAll').checked = false;
    } catch (e) {
    }

} //Конец функции





function myDialog2(url, param, w, h)
{
    var args = 'dialogHeight: ' + w + '; dialogWidth: ' + h + '; dialogTop: 10px; dialogLeft: 10px; edge: Raised; center: Yes; help: No; resizable: 0; status: 0;scroll: 0';
    return showModelessDialog(url, param, args);
}

function myDialog(url, param, w, h)
{
    //var param=window.document.all.myId.value;
    var args = 'dialogHeight: ' + w + '; dialogWidth: ' + h + '; dialogTop: 10px; dialogLeft: 10px; edge: Raised; center: Yes; help: No; resizable: 0; status: 0;scroll: 0';
    window.document.all.myId.value = window.showModalDialog(url, param, args);
}

function AdmCat(pid, w, h)
{
    miniWin('adm_catalogID.php?tip=main&catalogID=' + pid, '650', '630');
}

function winApplet(url)
{
    var h = 270;
    var w = 500;
    window.open(url, "_blank", "left=300,top=100,width=" + w + ",height=" + h + ",location=0,menubar=0,resizable=0,scrollbars=0,status=0");
}

function pressbutt(subm) {
    SUBMENU = document.all[subm].style;
    if (SUBMENU.visibility == 'hidden')
    {
        SUBMENU.visibility = 'visible';
        SUBMENU.position = 'relative';
    }
    else
    {
        SUBMENU.visibility = 'hidden';
        SUBMENU.position = 'absolute';
    }
}
function CL()
{
    window.close();
}

function CLREL(tip)// v2.4
{
    try {
        switch (tip) {

            case "left":
                if (window.opener.top.frame1)
                    window.opener.top.frame1.location.reload();
                //else window.opener.location.reload();
                break;

            case "right":
                if (window.opener.top.frame2)
                    window.opener.top.frame2.location.reload();
                //else window.opener.location.reload();
                break;

            case "top":
                window.opener.location.reload();
                break;

            case "right_top":
                if (window.frame2)
                    window.frame2.location.reload();
                else
                    window.location.reload();
                break;

            default:
                window.opener.location.reload();
        }
        window.close();
    } catch (e) {
        opener.location.reload();
        window.close();
    }

}


/*
 function miniWin(url,w,h) {
 var win=window.showModelessDialog(url, "","dialogHeight: "+h+"px; dialogWidth: "+w+"px; dialogTop: px; dialogLeft:px; edge: Raised; center: Yes; help: No; resizable: 0; status: 0;scroll: 0");
 }
 */

// Новое окно с подчетом координат клика
function miniWin(url, w, h, event) {

    var Width = getClientWidth();
    var Height = getClientHeight();
    Width = (Width / 2) - (w / 2);
    Height = (Height / 2) - (h / 2);
    //lock(1);
    window.open(url, "_blank", "dependent=1,left=" + Width + ",top=" + Height + ",width=" + w + ",height=" + h + ",location=0,menubar=0,resizable=1,scrollbars=0,status=0,titlebar=0,toolbar=0");
}

function miniModalPrice(url, w, h)
{
    var p1 = document.getElementById("priceOne").value;
    var p2 = document.getElementById("priceBox").value;
    var p3 = document.getElementById("numBox").value;
    var lang = document.getElementById("lang").value;
    var arg = url + "?priceOne=" + p1 + "&priceBox=" + p2 + "&numBox=" + p3 + "&lang=" + lang;
    window.open(arg, "_blank", "dependent=1,left=300,top=300,width=" + w + ",height=" + h + ",location=0,menubar=0,resizable=0,scrollbars=0,status=0,titlebar=0,toolbar=0");
}

function miniModalYML(url, w, h)
{
    var p1 = document.getElementById("yml_new").value;
    var p2 = document.getElementById("p_enabled").value;
    var p3 = document.getElementById("yml_bid").value;
    var p4 = document.getElementById("yml_cbid").value;
    var p5 = document.getElementById("yml_bid_enabled").value;
    var p6 = document.getElementById("yml_cbid_enabled").value;
    var lang = document.getElementById("lang").value;
    var arg = url + "?yml_new=" + p1 + "&p_enabled=" + p2 + "&yml_bid=" + p3 + "&yml_cbid=" + p4 + "&bid_enabled=" + p5 + "&cbid_enabled=" + p6 + "&lang=" + lang;
    window.open(arg, "_blank", "dependent=1,left=300,top=300,width=" + w + ",height=" + h + ",location=0,menubar=0,resizable=1,scrollbars=0,status=0,titlebar=0,toolbar=0");
}

function miniModalSpec(url, w, h)
{
    var p1 = document.getElementById("spec_new").value;
    var p2 = document.getElementById("newtip_new").value;
    var p3 = document.getElementById("enabled_new").value;
    var p4 = document.getElementById("num_new").value;
    var lang = document.getElementById("lang").value;
    var arg = url + "?spec=" + p1 + "&newtip=" + p2 + "&enabled=" + p3 + "&num=" + p4 + "&lang=" + lang;
    window.open(arg, "_blank", "left=300,top=300,width=" + w + ",height=" + h + ",location=0,menubar=0,resizable=0,scrollbars=0,status=0,titlebar=0,toolbar=0");
}

function miniWinFull(url, w, h)
{
    window.open(url, "_blank", "left=300,top=100,width=" + w + ",height=" + h + ",location=0,menubar=0,resizable=1,scrollbars=1,status=0,titlebar=0,toolbar=0");
}


var IDS = 0; //Начальное значение текущего идентификатора
function show_on(a) {
    document.getElementById(a).style.background = '#C0D2EC';
    IDS = a.replace("r", "");
}

function show_out(a) {
    document.getElementById(a).style.background = 'white';
    IDS = 0;
}

function onPreview() {
    var f_url = document.getElementById("f_url");
    var url = f_url.value;
    if (!url) {
        alert("You have to enter an URL first");
        f_url.focus();
        return false;
    }
    window.ipreview.location.replace(url);
    return false;
}
function onCancel() {
    window.close(null);
    return false;
}
