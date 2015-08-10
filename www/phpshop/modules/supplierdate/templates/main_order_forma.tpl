<script>
    // Обработка формы заказа
    function select_user_type(type){
        var org_name=document.getElementById("org_name").style;
        var org_inn=document.getElementById("org_inn").style;
        var org_kpp=document.getElementById("org_kpp").style;
        var name_person=document.getElementById("name_person").style;
        var adr_name=document.getElementById("adr_name").style;
        var dop_pole_1=document.getElementById("dop_pole_1").style;
        var dop_pole_2=document.getElementById("dop_pole_2").style;
        var dop_pole_3=document.getElementById("dop_pole_3").style;
        var dop_pole_4=document.getElementById("dop_pole_4").style;
        var dop_pole_5=document.getElementById("dop_pole_5").style;
        var dop_pole_6=document.getElementById("dop_pole_6").style;
        
        if(type == 1){
            // Выводим формы компании
            org_name.display='';
            org_inn.display='';
            org_kpp.display='';
            dop_pole_1.display='';
            dop_pole_2.display='';
            dop_pole_3.display='';
            dop_pole_4.display='';
            dop_pole_5.display='';
            dop_pole_6.display='';
            
            // Убираем форму физлица
            name_person.display='none';
            adr_name.display='none';
        }
        else{
            
            // Убираем формы компании
            org_name.display='none';
            org_inn.display='none';
            org_kpp.display='none';
            dop_pole_1.display='none';
            dop_pole_2.display='none';
            dop_pole_3.display='none';
            dop_pole_4.display='none';
            dop_pole_5.display='none';
            dop_pole_6.display='none';
            
            // Выводим форму физлица
            name_person.display='';
            adr_name.display='';
        }
    }
</script>
@ComStartReg@
<div  id=allspecwhite style="margin-bottom:20px">
    <img src="images/shop/icon_key.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
    <a href="/users/register.html" class="b">Зарегистрируйтесь</a> и получите дополнительные возможности и <b>скидки</b>.
</div>
@ComEndReg@
<p><br></p>
<form method="post" name="forma_order" action="/done/" enctype="multipart/form-data">
    <table  cellpadding="5" cellspacing="0" width=100% >
        <tr>
            <td align="right">
                <b>Заказ №</b>
            </td>
            <td>
                <input type="text" name=ouid style="width:50px; height:18px; font-family:tahoma; font-size:11px ; color:#9e0b0e; background-color:#f2f2f2;" value="@orderNum@"  readonly="1"> <b>/</b>
                <input type="text" style="width:50px; height:18px; font-family:tahoma; font-size:11px ; color:#9e0b0e; background-color:#f2f2f2;" value="@orderDate@"  readonly="1">
            </td>	
        </tr>
        <tr>
            <td align="right">Доставка</td>
            <td>
                @orderDelivery@
            </td>
        </tr>
        <tr valign="top">
            <td align="right"></td>
            <td>
                <input type="radio" name="user_type" value="0" checked="" onclick="select_user_type(this.value)">
                Физическое лицо    
                <input type="radio" name="user_type" value="1" onclick="select_user_type(this.value)">
                Юридическое лицо
            </td>
        </tr>
        <tr valign="top">
            <td align="right">
                E-mail:
            </td>
            <td>
                <input type="text" name="mail" style="width:300px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="30" value="@UserMail@" @formaLock@><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
            </td>
        </tr>
        <tr id="name_person">
            <td align="right" >
                Ф.И.О:
            </td>
            <td>
                <input type="text" name="name_person" style="width:300px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="30" value="@UserName@" @formaLock@><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
            </td>
        </tr>
        <tr>
            <td align="right">
                Телефон:
            </td>
            <td>
                <input type="text" name="tel_name" style="width:150px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="30" value="@UserTel@"><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
            </td>
        </tr>
        <tr id="dop_pole_5" style="display:none">
            <td align="right">
                Факс:
            </td>
            <td>
                <input type="text" name="dop_pole_5" style="width:150px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="30" value="">
            </td>
        </tr>
        <tr id="adr_name">
            <td align="right" >
                Адрес доставки:
            </td>
            <td>
                <textarea style="width:300px; height:100px; font-family:tahoma; font-size:11px ; color:#4F4F4F " name="adr_name" >@UserAdres@</textarea><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
            </td>
        </tr>
        <tr id="org_name" style="display:none">
            <td align="right" >Предприятие: </td>
            <td><input type="text" name="org_name" style="width:300px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="100" value="@UserComp@" @formaLock@>
            </td>
        </tr>
        <tr id="dop_pole_1" style="display:none">
            <td align="right" >
                Юридический адрес:
            </td>
            <td>
                <textarea style="width:300px; height:50px; font-family:tahoma; font-size:11px" name="dop_pole_1"></textarea>
            </td>
        </tr>
        <tr id="dop_pole_2" style="display:none">
            <td align="right" >
                Фактический адрес:
            </td>
            <td>
                <textarea style="width:300px; height:50px; font-family:tahoma; font-size:11px" name="dop_pole_2"></textarea>
            </td>
        </tr>
        <tr id="dop_pole_3" style="display:none">
            <td align="right" >
                Почтовый адрес:
            </td>
            <td>
                <textarea style="width:300px; height:50px; font-family:tahoma; font-size:11px" name="dop_pole_3"></textarea>
            </td>
        </tr>
        <tr id="org_inn" style="display:none">
            <td align="right" >ИНН: </td>
            <td><input type="text" name="org_inn" style="width:150px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="50" value="@UserInn@" @formaLock@>
            </td>
        </tr>
        <tr id="org_kpp" style="display:none">
            <td align="right" >КПП: </td>
            <td><input type="text" name="org_kpp" style="width:150px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="50" value="@UserKpp@" @formaLock@>
            </td>
        </tr>
        <tr id="dop_pole_4" style="display:none">
            <td align="right" >Код ОКПО: </td>
            <td><input type="text" name="dop_pole_4" style="width:150px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="50">
            </td>
        </tr>
        <tr id="dop_pole_6" style="display:none">
            <td align="right" >Прикрепить файл: </td>
            <td><input type="file" name="dop_pole_6">
            </td>
        </tr>
        <tr>
            <td align="right">Тип оплаты <br>покупки</td>
            <td>
                @orderOplata@
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <div  id=allspecwhite><img src="images/shop/comment.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">Данные, отмеченные <b>флажками</b> обязательны для заполнения.<br>
                </div>

            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <p><br></p>
                <table align="center">
                    <tr>
                        <td>
                            <img src="images/shop/brick_error.gif" border="0" align="absmiddle">
                            <a href="javascript:forma_order.reset();" class=link>Очистить форму</a></td>
                        <td width="20"></td>
                        <td><img src="images/shop/brick_go.gif"  border="0" align="absmiddle">
                            <a href="javascript:OrderChek();" class=link>Оформить заявку</a></td>


                    </tr>
                </table>
                <input type="hidden" name="send_to_order" value="ok" >
                <input type="hidden" name="d" id="d" value="@deliveryId@">
                <input type="hidden" name="nav" value="done">
            </td>
        </tr>
    </table>
</form>
