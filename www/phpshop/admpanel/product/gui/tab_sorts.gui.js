/**
 * JS Библиотека панели характеристик товара tab_sorts.gui.php
 */

function addOption (oListbox, text, value, isDefaultSelected, isSelected){
    var oOption = document.createElement("option");
    oOption.appendChild(document.createTextNode(text));
    oOption.setAttribute("value", value);

    if (isDefaultSelected) oOption.defaultSelected = true;
    else if (isSelected) oOption.selected = true;

    oListbox.appendChild(oOption);
}

function enterchar(num){
    var sellist=document.getElementById("list"+num);
    var aoptions=sellist.options;
    var addit=document.getElementById("addval"+num).value;
    selopts=new Array;
    var masi=0;

    for(i=0;i<aoptions.length;i++){
        var cse=aoptions[i].selected;
        if (cse==true) {
            selopts[masi]=aoptions[i].value;
            masi++;
        }
    }
    

    if (addit.length>0) { //Если значение введено, значит работаем
        var req = new Subsys_JsHttpRequest_Js();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                if (req.responseJS) {

                    optsres=req.responseJS.interfaces;
                    sellist.options.length = 0;
                    addOption(sellist, "Нет данных", "", false,false);
                    for (i=0;i<optsres.length;i++) {
                        addOption(sellist,optsres[i]['name'] , optsres[i]['id'], false,optsres[i]['selected']);
                    }
                }
            }
        }
        req.caching = false;
        // Подготваливаем объект.
        req.open('POST', 'action_char.php', true);
        req.send( {
            num: num,
            selopts:selopts,
            addit:addit
        } );

    } else {//Нечего вводить!
        alert("Введите значение!");
    }

}