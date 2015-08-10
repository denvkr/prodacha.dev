<head>
    <title>Форма предварительного заказа</title>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
    <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
    <script>
        function NoFoto(obj){
            obj.height=1;
            obj.width=1;
        }
    </script>
    <style type="text/css">
        body {text-decoration: none;font: normal 11px x-small/normal Verdana, Arial, Helvetica, sans-serif;text-transform: none}
        TABLE {font: normal 11px Verdana, Arial, Helvetica, sans-serif;}
        p {font: normal 11px Verdana, Arial, Helvetica, sans-serif;word-spacing: normal;white-space: normal;margin: 5px 5px 5px 5px;letter-spacing : normal;}
        TD {
            font: normal 11px Verdana, Arial, Helvetica, sans-serif;
            background: #FFFFFF;
        }
        H4 {
            font: Verdana, Arial, Helvetica, sans-serif;
            background: #FFFFFF;
        }
        .tablerow {
            border: 0px;
            border-top: 1px solid #000000;
            border-left: 1px solid #000000;
        }
        .tableright {
            border: 0px;
            border-top: 1px solid #000000;
            border-left: 1px solid #000000;
            border-right: 1px solid #000000;
            text-align: right;
        }
    </style>
    <style media="print" type="text/css">
        <!--
        .nonprint {
            display: none;
            
        }
        -->
    </style>
</head>
<body onload="window.focus()" bgcolor="#FFFFFF" text="#000000" marginwidth=5 leftmargin=5 style="padding: 2px;">
    <div align="right" class="nonprint"><a href="#" onclick="window.print();return false;" >Распечатать</a><br><br></div>
    <div align="center"><table align="center" width="100%">
            <tr>
                <td align="center"><img onerror=NoFoto(this) src="@logo@" alt="" border="0"></td>
                <td align="center"><h4 align=center>Форма предварительного заказа &nbsp;от&nbsp;@date@</h4></td>
            </tr>
        </table>
    </div>

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
            <td class=tableright nowrap><b>@total@</b></td>
        </tr>
        <tr><td colspan=6 style="border: 0px; border-top: 1px solid #000000;">&nbsp;</td></tr>
    </table>
    <p><b>Всего наименований @item@, на сумму @total@ @currency@
            <br />
    @totaltext@
        </b></p>
</body>
</html>