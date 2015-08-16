
function PHPShopXmlManager(){

    this.style='margin-bottom:10px;border:outset;border-width:1px;border-color:silver;width:97%';
    this.imgwidth=100;
    this.category=0;
    this.key='****';
    this.limit=10;
    this.id='shopItem';
    this.obj=1;
    this.code='windows-1251'; // utf-8
    
    this.xmldoc = function(txt){
        if (window.DOMParser)
        {
            parser=new DOMParser();
            xmlDoc=parser.parseFromString(txt,"text/xml");
        }
        else // Internet Explorer
        {
            xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
            xmlDoc.async="false";
            xmlDoc.loadXML(txt);
        }
        return xmlDoc;
    }

    this.tmp = function(data){
        var tmparea= document.createElement('textarea');
        tmparea.id = 'shopItemTmp'+this.obj;
        tmparea.value=data;
        tmparea.style.display='none';
        document.body.appendChild(tmparea);
    }

    this.read = function(){
        this.xml=document.getElementById('shopItemTmp'+this.obj).value;
        return this.xmldoc(this.xml);
    }

    // Шаблон вывода
    this.template = function(pic_small,name,id,price){
        d='<table cellpadding="5" cellspacing="0"  width="100%" style="'+this.style+'">';
        d+='<tr><td><img align="left" title="'+name+'" src="'+this.url+pic_small+'" width="'+this.imgwidth+'"></td></tr>';
        d+='<tr><td><a title="'+name+'" href="'+this.url+'/shop/UID_'+id+'.html?partner='+this.partner+'" target="_blank">'+name+'</a></td></tr>';
        d+='<tr><td>Цена: '+price+' '+this.currency+'</td></tr>';
        d+='<tr><td><a href="'+this.url+'/shop/UID_'+id+'.html?partner='+this.partner+'" target="_blank">Купить</a></td></tr>';
        d+='</table>';
        return d;
    }

    this.display = function(divid){
        var xmldoc =  this.read();
        var disp='';
        var itemList = xmldoc.getElementsByTagName('row');
        var id = xmldoc.getElementsByTagName('id');
        var name = xmldoc.getElementsByTagName('name');
        var pic_small = xmldoc.getElementsByTagName('pic_small');
        var price = xmldoc.getElementsByTagName('price');
        for (var i=0; i< itemList.length; i++){
            try{
                disp+=this.template(pic_small[i].firstChild.nodeValue,name[i].firstChild.nodeValue,id[i].firstChild.nodeValue,price[i].firstChild.nodeValue);
            }catch(e){}
        }
        // Выводим данные
        document.getElementById(divid).innerHTML=disp;
    }

    this.load = function(){
        var data = document.createElement('script');
        data.type = 'text/javascript';
        data.async = true;
        data.id = this.obj+'_lib';
        data.src = this.url+'/phpshop/modules/partner/lib/data.js.php?partner='+this.partner+'&cat='+this.category+'&limit='+this.limit+'&key='+this.key+'&id='+this.id+'&code='+this.code;
        data.src+='&obj='+this.obj;
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(data, s);

        if(!document.getElementById(this.id)){
            var displaydiv= document.createElement('div');
            displaydiv.id = this.id;
            var lib = document.getElementById('phpshop-lib-xml');
            lib.parentNode.insertBefore(displaydiv, lib);
        }
        
    }

    this.destroy = function(){
        delete this.xml;
    }

}