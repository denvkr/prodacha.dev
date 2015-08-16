/**
 * Библиотека JavaScript
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopJS
 */

function PHPShopJS(){

    this.button_on = function(a){
        this.classStyle(a.id,'buton');
    }

    this.button_off = function(a){
        this.classStyle(a.id,'butoff');
    }

    this.rowshow_on = function(a){
        this.classStyle(a.id,'row_show_on');
    }

    this.rowshow_out = function(a){
        this.classStyle(a.id,'row_show_off');
    }

    this.classStyle = function(a, name){
        document.getElementById(a).className = name;
    }

    this.style = function(a, style){
        document.getElementById(a).style = style;
    }

    this.value = function(a, value){
        document.getElementById(a).value = value;
    }
}

var PHPShopJS = new PHPShopJS();



function DoUpdateOrderProductSum(){
    var num=document.getElementById('num').value;
    var price=document.getElementById('price').value;
    document.getElementById('sum').innerHTML=num*price;
}

function show_on(a){
    document.getElementById(a).style.background='#C0D2EC';
    IDS=a.replace("r","");
}

function show_out(a){
    document.getElementById(a).style.background='white';
    IDS=0;
}


// Ajax перезагрузка для модулей
function DoReloadMainWindowModule(page,var1,var2,type){

    if(type === undefined){
        path='../../../admpanel/';
        var3=var2;
    }
    else if(type == 'core'){
    path='../';
    var3='';
    }

    var req = new Subsys_JsHttpRequest_Js();
    req.onreadystatechange = function(){
        if (req.readyState == 4){
            if (req.responseJS){
                if(window.opener.document.getElementById('interfaces')){
                    window.opener.document.getElementById('interfaces').innerHTML = (req.responseJS.xid||'');
                    window.close();
                }

            }
        }
    }
    req.caching = false;
    req.open('POST', path+'interface/api.php', true);
    req.send({
        xid: 1,
        page: page,
        var1: var1,
        var2: var2,
        var3: var3
    });
}


function onDelete(message){
    if(confirm(message)){
        document.getElementById("product_edit").action+='?delID=ok';
        document.getElementById("product_edit").submit();
    }
}


function onCancel(){
    winOpenType = GetCookie('winOpenType');
    if(winOpenType == "") winOpenType=parent.window.winOpenType;
    switch (winOpenType) {
        
        case 'highslide':
            return parent.window.hs.close();
            break;

        default:
            window.close(null);
            return false;
            break;

    }
}

function onReload(){
    winOpenType = GetCookie('winOpenType');
    if(winOpenType == "") winOpenType=parent.window.winOpenType;
    switch (winOpenType) {

        case 'highslide':
            return parent.window.frames[parent.window.hs.getExpander().iframe.name].location.reload(true);
            break;

        default:
            return window.location.reload(true);
            break;

    }
}


// Новое окно
function miniWin(url,w,h){
    var winOpenType = GetCookie('winOpenType');

    switch (winOpenType) {

        case 'highslide':
            //parent.window.hs.headingText=url;
            parent.window.hs.htmlExpand(null, {
                objectType: 'iframe',
                src: ''+url+'',
                width: ''+w+'',
                height: ''+h+''
            });
            break;

        default:
            window.open(url,"_blank","dependent=1,left=100,top=20,width="+w+",height="+h+",location=0,menubar=0,resizable=1,scrollbars=0,status=0,titlebar=0,toolbar=0");
            return false;
            break;
    }
}


function SetCookie(name, value, days){
    var today = new Date();
    expires = new Date(today.getTime() + days*24*60*60*1000);
    document.cookie = name + "=" + escape(value) +"; expires=" + expires.toGMTString();
}

function GetCookie(cookieName){
    var cookieValue = '';
    var posName = document.cookie.indexOf(escape(cookieName) + '=');
    if (posName != -1) {
        var posValue = posName + (escape(cookieName) + '=').length;
        var endPos = document.cookie.indexOf(';', posValue);
        if (endPos != -1) cookieValue = unescape(document.cookie.substring(posValue, endPos));
        else cookieValue = unescape(document.cookie.substring(posValue));
    }
    return cookieValue;
}


// Вывод скриншота
function GetSkinIcon(skin){
    var path="../../templates/"+skin+"/icon/icon.gif";
    document.getElementById("icon").src=path;
}

function GhekReg(){
    if(document.getElementById('mod_id').value>0) document.forms.product_edit.submit();
}

function GetThemeIcon(skin){
    var path="../skins/"+skin+"/icon.gif";
    document.getElementById("theme").src=path;
}

// Обзор картинок ресайз
function ReturnPicResize(id){
    var pic=document.getElementById('pic_resize');
    var path='../editor3/assetmanager/resize.php?id='+id;
    miniWin(path,350,200);
}

// Обзор картинок
function ReturnPic(id){
    var pic=document.getElementById(id);
    var path='../editor3/assetmanager/assetmanager.php?name='+pic.value+'&tip='+id;

    miniWin(path,640,500);
}


// Поиск страниц
function SearchPage(){
    var words=document.getElementById('words').value;
    window.frame2.location.replace('catalog/admin_cat_content.php?words='+words);
}

function EditCatalogPages(){
    //alert(window.frame2.document.getElementById("catal").value);
    if(window.frame2.document.getElementById("catal") && (window.frame2.document.getElementById("catal").value>0)){
        var catal=window.frame2.document.getElementById("catal").value;
        if(catal != 1000 && catal != 2000) miniWin('catalog/adm_catalogID.php?id='+catal,650,630);
    }else alert("Выберете подкаталог для редактирования");

}

function NewCatalogPages(){
    if(window.frame2.document.getElementById("catal") && (window.frame2.document.getElementById("catal").value>0)){
        var catal=window.frame2.document.getElementById("catal").value;
    }else var catal=0;

    miniWin('catalog/adm_catalog_new.php?id='+catal,650,630);
}

function AllPage(){
    window.frame2.location.replace('catalog/admin_cat_content.php?pid=all');
}

function NewPage(){
    if(window.frame2.document.getElementById("catal") && (window.frame2.document.getElementById("catal").value>0)){
        var catal=window.frame2.document.getElementById("catal").value;
    }else var catal=0;

    miniWin('page/adm_pages_new.php?catalogID='+catal,850,630);

}

function NewPhoto() {
    if(window.frame2.document.getElementById("catal") && (window.frame2.document.getElementById("catal").value>0)){
        var catal=window.frame2.document.getElementById("catal").value;
        miniWin('photo/swfupload/index.php?pid='+catal,500,450)
    }else alert("Выберете подкаталог для добавления фото!");
}

function EditCatalogPhoto(){
    if(window.frame2.document.getElementById("catal") && (window.frame2.document.getElementById("catal").value>0)){
        // && (window.frame2.document.getElementById("catal").value>0)
        var catal=window.frame2.document.getElementById("catal").value;
        miniWin('photo/adm_catalogID.php?id='+catal,650,630);
    }else alert("Выберете подкаталог для редактирования");

}

function NewPhotoCatalog(){
    miniWin('photo/adm_catalog_new.php',650,630);
}



// Проверка дефолтных параметров
function rootNote(){
    if(confirm("Вы используете стандартный пароль и логин для входа в панель управления.\nЭто может привести к взлому сайта. Сменить пароль администратора сейчас?"))
        miniWin('users/adm_userID.php?id=1',500,360)
}


// Резиновый экран
function ResizeWin(){
    var clientW=document.body.clientWidth;
    if(document.getElementById("interfacesWin") || document.getElementById("interfacesWin1")){


        // Если новое окно
        if(window.opener){
            if(document.getElementById("interfacesWin"))
                document.getElementById("interfacesWin").style.height=(clientW-425);
            else {
                document.getElementById("interfacesWin1").height=(clientW-470);
                document.getElementById("interfacesWin2").height=(clientW-490);
            }
        }
        // В тоже окно
        else{
            if(document.getElementById("interfacesWin"))
                document.getElementById("interfacesWin").style.height=(clientW-500);
            else {
                document.getElementById("interfacesWin1").height=(clientW-535);
                document.getElementById("interfacesWin2").height=(clientW-555);
            }
        }
    }
}





function PromptThis(){
    if(confirm("Внимание!\nДанная операция может привести к потере позиции.\nВы действительно хотите выполнить данную команду?")){
        document.getElementById("productDELETE").value='doIT';
        document.product_edit.submit();
    }
}

function SelectQuerySql(sql){
    return document.getElementById("sql_area").value=sql;
}

function SqlSend2(){
    if(document.getElementById("csv_file").value.length!=0)
        if(confirm("Внимание!\nДанная операция может привести к потере базы.\nВы действительно хотите выполнить данную команду?"))
            document.getElementById("sql_forma2").submit();
}

function SqlSend(){
    if(document.getElementById("sql_area").value.length!=0)
        if(confirm("Внимание!\nДанная операция может привести к потере базы.\nВы действительно хотите выполнить данную команду?"))
            document.getElementById("sql_forma").submit();
        else
            return document.getElementById("sql_area").value='';
}



function CL()
{
    window.close();
}

function CLREL(tip)
{
    if (tip=="left") window.opener.top.frame1.location.reload();
    if (tip=="right") window.opener.top.frame2.location.reload();
    if (tip=="top") window.opener.location.reload();
    else window.opener.location.reload();
    window.close();
}



function miniWinFull(url,w,h)
{
    window.open(url,"_blank","left=100,top=100,width="+w+",height="+h+",location=0,menubar=0,resizable=1,scrollbars=1,status=0,titlebar=0,toolbar=0");
}

function Ras(w,h)
{
    var s=window.document.data_list.data_news.value;
    var url="news/news_to_mail.php?data="+s;
    miniWin(url,w,h);
}

