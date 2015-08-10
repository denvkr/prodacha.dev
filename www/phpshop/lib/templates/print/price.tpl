<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
    <head>
        <title>@name@ / Прайс / Печатная форма</title>
        <META http-equiv="Content-Type" content="text-html; charset=windows-1251">
        <style>
            BODY {
                FONT-FAMILY: tahoma,verdana,arial,sans-serif
                    color:000000;
                font-size: 11px;
            }
            td {
                font-size: 11px;
                font-family:Tahoma;
                color:#000000;
            }
            a {
                font-size: 11px;
                font-family:Tahoma;
                color:#000000;
                text-decoration: none;
            }
            a:hover {
                font-size: 11px;
                font-family:Tahoma;
                color:#000000;
                text-decoration: underline;
            }

            .bor {
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
    <body>
        <div style="padding-left:10"><h3>Прайс-лист Интернет магазина "@name@"</h3></div>
        <TABLE cellpadding="0" cellspacing="0" width="100%" class="style5">
            <TR>
                <TD>
                    <TABLE width="100%">
                        <TR>
                            <TD class="black" style="padding:10" width="50%">
												 Дата: <b>@date@</b>


                            </TD>
                            <td align="right" width="100%">
                                <input type="submit" value="Распечатать" onclick="window.print();return false;" class="nonprint">
                            </td>
                        </TR>
                    </TABLE>
                </TD>
            </TR>
        </TABLE>
        <table cellpadding=2 cellspacing=1 width="98%" align="center" border="1">
            @price@
        </table>

        <TABLE cellpadding="0" cellspacing="0" width="100%" class="style5">
            <TR>
                <TD>
                    <TABLE width="100%">
                        <TR>
                            <TD class="black" style="padding:10" width="50%">
												Дата: <b>@date@</b>


                            </TD>
                            <td align="right" width="100%">
                                <input type="submit" value="Распечатать" onclick="window.print();return false;" class="nonprint">
                            </td>
                        </TR>
                    </TABLE>
                </TD>
            </TR>
        </TABLE>
    </body>
</html>	