/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    //переделываем ссылку на документацию
var cnt = 0;
elm=document.getElementsByClassName("blue");
var regExpr = new RegExp('^.*none$', 'ig');
//console.log(elm.length);
for (cnt=0;cnt<elm.length;cnt++){
    if ( regExpr.test(elm[cnt].getAttribute("href"))===true ){
        elm[cnt].setAttribute("href", "http://trello.com");
        //console.log('sql_optimizer in array');
    };    
}
//console.log('sql_optimizer');
//ajax запрос по очистке таблиц
function DoClearTables(){
        $.ajax({
                url: '/phpshop/modules/sql_optimizer/inc/docleartables.php',
                type: 'post',
                data: 'ok=1',
                dataType: 'json',
                beforeSend: function() {},
                complete: function() {},
                success: function(json) {
                if (json['rv']==1) {
                    $('#table_rows1').html(json['trdd1']);
                    $('#table_rows2').html(json['trdd2']);
                } 
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('error save_(reg)' + ' ' + textStatus);
                }
        });        

}