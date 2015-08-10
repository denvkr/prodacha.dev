

PHPShopJS.page = {}

// Создание новой страницы
PHPShopJS.page.init = function(){
    this.obj=window.frame2;
    if(this.obj.document.getElementById("catal")){
        this.catal=this.obj.document.getElementById("catal").value;
    } 
}


// Создание новой страницы
PHPShopJS.page.addpage = function(){
    this.init();
    PHPShopJS.open('page/adm_pages_new.php?categoryID='+this.catal,650,600);
}

// Создание нового каталога
PHPShopJS.page.addcat = function(){
    PHPShopJS.open('page/adm_catalog_new.php',650,630);
}

// Редактирование каталога
PHPShopJS.page.edit = function(){
    this.init();
    if(this.catal != undefined){
        if(this.catal != 1000 && this.catal != 2000 && this.catal != 0)
            PHPShopJS.open('page/adm_catalogID.php?id='+this.catal,650,630);
    }
    else alert("Выберите подкаталог для редактирования");
}

// Показать все страницы
PHPShopJS.page.all = function(){
    this.init();
    try{
        this.obj.location.replace('page/admin_page_content.php?pid=all');
    }catch(e){
        this.obj.location.reload();
    }
}

// Поиск страниц
PHPShopJS.page.search = function(words){
    this.init();
    this.obj.location.replace('page/admin_page_content.php?words='+words);
}


// Групповое действие
PHPShopJS.page.action = function(a){
    var num=1000;
    var obj=window.frame2.document.flag_form;
    try{
        if(a!=0){
            var IDS=new Array();
            var j=0;
            for (var i=0;i<=num; i++){
                if (obj.elements[i]){
                    if ((obj.elements[i]).checked){
                        IDS[j]=(obj.elements[i]).value;
                        j++;
                    }
                }
            }
            
            if(IDS.length>0) 
                PHPShopJS.open('window/adm_window.php?do='+a+'&ids='+IDS,300,220);
        }
    }catch(e){}
    
    try{
        document.getElementById('actionS').value=0;
    // document.getElementById('DoAll').checked=false;
    }catch(e){}
}