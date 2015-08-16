<script>
    function checkModReturnCallForma(){
        if(document.getElementById('returncall_mod_name').value == "" || document.getElementById('returncall_mod_tel').value == "")
            return false;
    }
</script>
<a href="javascript:void(0)" onclick="document.getElementById('mod_recall_forma').style.display='block';">Заказать звонок</a>

<div style="position:relative">
    <div id="mod_recall_forma" style="display: none;width:200px;position:absolute;">
        <div>
            <div style="position:relative; border:1px solid #ccc; background:#ebf1f6 ">
                    <form action="@ShopDir@/returncall/" method="post" onsubmit="return checkModReturnCallForma();" >
                    <table style="margin:0px 8px 0px 9px" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td colspan="2" style=" padding:10px;" align="right"><a style="padding:0px; font-size:11px;margin:0px 0px 0px 5px;color:#086ebd" href="javascript:void(0)" onclick="document.getElementById('mod_recall_forma').style.display='none';">закрыть</a></td>
                            </tr>
                             <tr>
                                <td colspan="2">@leftMenuName@</td>
                            </tr>
                            <tr>
                                <td><b>Имя</b>:</td>
                                <td><input type="text" name="returncall_mod_name" id="returncall_mod_name" size="15"></td>
                            </tr>
                            <tr>
                                <td><b>Телефон</b>:</td>
                                <td><input type="text" name="returncall_mod_tel" id="returncall_mod_tel" size="15"> </td>
                            </tr>
                            <tr>
                                <td>Время звонка:</td>
                                <td>от <input type="text" name="returncall_mod_time_start" size="2"> до 
                                    <input type="text" name="returncall_mod_time_end" size="2"></td>
                            </tr>
                            <tr>
                                <td>Сообщение:</td>
                                <td><textarea name="returncall_mod_message" cols="12" rows="3"></textarea></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input type="submit" name="returncall_mod_send" value="Перезвоните мне"></td>
                            </tr>

                        </tbody>
                    </table>

                </form>
            </div>

        </div>

    </div>

</div>