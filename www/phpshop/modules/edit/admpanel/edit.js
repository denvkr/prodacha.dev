
// Инонка шаблона
function GetTemplateIcon(skin,path,id,icon_path){
    path+="templates/"+skin+"/"+icon_path+"icon.gif";
    document.getElementById(id).alt=skin;
    document.getElementById(id).src=path;
}

function CheckTemplateIcon(path,id){
    templ=document.getElementById(id).alt;
    if(templ != '')
    window.open(path+'/?skin='+templ);
}
