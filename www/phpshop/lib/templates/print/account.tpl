<head>
    <title>Счет в банк заказ № @ouid@</title>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
    <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
    <link href="../style.css" type=text/css rel=stylesheet>
    <style media="print" type="text/css">
        <!--
        .nonprint {
            display: none;
        }
        -->
    </style>
</head>
<body onload="window.focus()" bgcolor="#FFFFFF" text="#000000" marginwidth=5 leftmargin=5 style="padding: 2px;">
    <div align="right" class="nonprint"><a href="#" onclick="window.print();return false;" style="color: #0078BD;">Распечатать</a> <br><br></div>
    <p align=left>@org_name@</p>
    <table cellpadding=3 cellspacing=0 width=99%>
        <tr>
            <td>Юридический адрес:</td>
            <td>@org_ur_adres@</td>
        </tr>
        <tr>
            <td>Почтовый адрес:</td>
            <td>@org_adres@</td>
        </tr>
    </table>
    <h4 align=center>Образец&nbsp;заполнения&nbsp;платежного&nbsp;поручения</h4>
    <table cellpadding=0 cellspacing=1 bgcolor=#BBBBBB width=99% border=0 align=center>
        <tr>
            <td nowrap>ИНН&nbsp;@org_inn@</td>
            <td nowrap>КПП&nbsp;@org_kpp@</td>
            <td rowspan=2 valign=bottom>Сч.№</td>
            <td rowspan=2 valign=bottom>@org_schet@
            </td>
        </tr>
        <tr>
            <td colspan=2>Получатель<br />@org_name@</td>
        </tr>
        <tr>
            <td rowspan=2 colspan=2 valign=top>Банк получателя<br />@org_bank@
            </td>
            <td>БИК</td>
            <td nowrap rowspan=2>@org_bic@<br>@org_bank_acount@</td>
        </tr>
        <tr>
            <td>Сч.№</td>
        </tr>
    </table>
    <h2 align=center>Счет&nbsp;№&nbsp;@ouid@&nbsp;от&nbsp;@date@</h2>
    <br />
    <table cellpadding=0 cellspacing=2>
        <tr>
            <td>Заказчик:</td>
            <td>@person_user@</td>
        </tr>
        <tr>
            <td>Плательщик:</td>
            <td>@person_org@</td>
        </tr>
    </table>
    <table width=99% cellpadding=2 cellspacing=0 align=center>
        <tr class=tablerow>
            <td class=tablerow>№</td>
            <td width=50% class=tablerow>Наименование</td>
            <td class=tablerow>Единица измерения&nbsp;</td>
            <td class=tablerow>Количество</td>
            <td class=tablerow>Цена</td>
            <td class=tableright>Сумма</td>
        </tr>
        @cart@
        <tr>
            <td colspan=5 align=right style="border-top: 1px solid #000000;border-left: 1px solid #000000;">Скидка:</td>
            <td class=tableright nowrap><b>@discount@%</b></td>
        </tr>
        <tr>
            <td colspan=5 align=right style="border-top: 1px solid #000000;border-left: 1px solid #000000;">Итого:</td>
            <td class=tableright nowrap><b>@total@ @currency@</b></td>
        </tr>
        @nds_block_start@
        <tr>
            <td colspan=5 align=right style="border-top: 1px solid #000000;border-left: 1px solid #000000;">В т.ч. НДС: @nds@%</td>
            <td class=tableright nowrap><b>@totalnds@ @currency@</b></td>
        </tr>
        @nds_block_end@
        <tr><td colspan=6 style="border: 0px; border-top: 1px solid #000000;">&nbsp;</td></tr>
    </table>
    <p><b>Всего наименований @item@, на сумму @totaltext@
            <br />
        </b></p><br>
    <p>Руководитель предприятия<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></p>
    <p>Главный бухгалтер<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></p>
</body>
</html>