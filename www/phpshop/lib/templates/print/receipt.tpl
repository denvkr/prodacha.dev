<html>
    <head>
        <title>Квитанция Сбербанка заказ № @ouid@</title>
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
    <body  onload="window.focus()" bgcolor="#FFFFFF" text="#000000">
        <div align="right" class="nonprint"><a href="#" onclick="window.print();return false;" style="color: #0078BD;">Распечатать</a><br><br></div>
        <table cellpadding=2 cellspacing=0>
            <col style="padding-bottom: 5px;" width=30% height=50%>
            <col width=70% height=50%>
            <tr>
                <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" valign=top height=50%><table width=100% height=100% cellpadding=0 cellspacing=0>
                        <tr><td width=100% height=100% valign=top align=right>ИЗВЕЩЕНИЕ</td></tr>
                        <tr><td width=100% align=left valign=bottom>Кассир</td></tr>
                    </table></td>
                <td style="border-bottom: 1px solid #000000; padding-right: 0px;" height=50%><table width=100% height=100% cellpadding=0 cellspacing=0>
                        <tr>
                            <td class=data align=center width=100%><div style="width: 100%;">@org_name@</div></td>
                        </tr>
                        <tr>
                            <td align=center class=comment>(наименование получателя платежа)</td>
                        </tr>
                        <tr>
                            <td width=100%><table width=100% cellpadding=0 cellspacing=0>
                                    <tr>
                                        <td></td>
                                        <td class=data width=90><div width=90>@org_inn@</div></td>
                                        <td style="padding-left: 75px;"></td>
                                        <td>№</td>
                                        <td class=data width=145><div width=145>@org_schet@</div></td>
                                        <td width=*></td>
                                    </tr>
                                    <tr class=comment>
                                        <td colspan=2 class=comment>(ИНН получателя платежа)</td>
                                        <td style="padding-left: 75px;"></td>
                                        <td colspan=3 class=comment>(номер счета получателя платежа)</td>
                                    </tr>
                                    <tr>
                                        <td colspan=6>@org_bank@</td>
                                    </tr>
                                    <tr>
                                        <td>БИК</td>
                                        <td class=data width=90><div width=90>@org_bic@</div></td>
                                        <td style="padding-left: 75px;"></td>
                                        <td>№</td>
                                        <td class=data width=145><div width=145>@org_bank_schet@</div></td>
                                        <td width=*></td>
                                    </tr>
                                    <tr class=comment>
                                        <td colspan=2></td>
                                        <td style="padding-left: 75px;"></td>
                                        <td colspan=3 class=comment>(номер кор./с банка получателя платежа)</td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td><table width=100%>
                                    <td style="padding-right: 16px;">Плательщик</td>
                                    <td class=data width=100%>@user@</td>
                                </table></td>
                        </tr>
                        <tr>
                            <td><table width=100%>
                                    <td style="padding-right: 5px;" nowrap>Назначение платежа</td>
                                    <td class=data width=100%>Оплата заказа № @ouid@ от @date@</td>
                                </table></td>
                        </tr>
                        <?

                        ?>
                        <tr>
                            <td><table width=100% cellpadding=0 cellspacing=0>
                                    <tr>
                                        <td style="padding-left: 90px;"></td>
                                        <td>Сумма платежа</td>
                                        <td width=65 class=data nowrap><div width=65>@total@</div></td>
                                        <td><?=$GetIsoValutaOrder?></td>
                                        <td width=65 class=data><div width=65><?= $sum_kop?></div></td>
                                        <td>коп.</td>
                                        <td width=100%></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 90px;"></td>
                                        <td>Итого</td>
                                        <td width=65 class=data nowrap><div width=65>@total@</div></td>
                                        <td></td>
                                        <td width=65 class=data><div width=65></div></td>
                                        <td>коп.</td>
                                        <td width=100%></td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td><div height=20>&nbsp;</div></td>
                        </tr>
                        <tr>
                            <td><table width=100% cellpadding=0 cellspacing=0>
                                    <td style="padding-right: 4px;">Плательщик</td>
                                    <td width=80 class=data><div width=80>&nbsp;</div></td>
                                    <td style="padding-left: 2px;">(подпись)</td>
                                    <td>&nbsp;&nbsp;&quot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&quot;&nbsp;&nbsp;</td>
                                    <td class=data width=85><div width=85>&nbsp;</div></td>
                                    <td>201&nbsp;&nbsp;г.</td>
                                </table></td>
                        </tr>
                        <tr>
                            <td align=left>С условием приема банком суммы, указанной в платежном документе, </td>
                        </tr>
                        <tr>
                            <td><table cellpadding=0 cellspacing=0 width=100%>
                                    <tr>
                                        <td>ознакомлен и согласен.</td>
                                        <td class=data width=85><div width=85>&nbsp;</div></td>
                                        <td>&nbsp;&nbsp;&quot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&quot;&nbsp;&nbsp;</td>
                                        <td class=data width=85><div width=85>&nbsp;</div></td>
                                        <td>201&nbsp;&nbsp;г.</td>
                                    </tr>
                                    <tr>
                                        <td colspan=2 align=right style="padding-right: 10px;" class=comment>(подпись плательщика)</td>
                                        <td></td>
                                        <td class=comment align=center>(Дата)</td>
                                        <td></td>
                                    </tr>
                                </table></td>
                        </tr>
                    </table></td>
            </tr>

            <tr>
                <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;" valign=top height=50%><table width=100% height=100% cellpadding=0 cellspacing=0>
                        <tr><td width=100% height=100% valign=bottom align=right>КВИТАНЦИЯ</td></tr>
                        <tr><td width=100% align=left valign=bottom>Кассир</td></tr>
                    </table></td>
                <td style="border-bottom: 1px solid #000000; padding-right: 0px;" height=50%><table width=100% height=100% cellpadding=0 cellspacing=0>
                        <tr>
                            <td class=data align=center width=100%><div style="width: 100%;">@org_name@</div></td>
                        </tr>
                        <tr>
                            <td align=center class=comment>(наименование получателя платежа)</td>
                        </tr>
                        <tr>
                            <td width=100%><table width=100% cellpadding=0 cellspacing=0>
                                    <tr>
                                        <td></td>
                                        <td class=data width=90><div width=90>@org_inn@</div></td>
                                        <td style="padding-left: 75px;"></td>
                                        <td>№</td>
                                        <td class=data width=145><div width=145>@org_schet@</div></td>
                                        <td width=*></td>
                                    </tr>
                                    <tr class=comment>
                                        <td colspan=2 class=comment>(ИНН получателя платежа)</td>
                                        <td style="padding-left: 75px;"></td>
                                        <td colspan=3 class=comment>(номер счета получателя платежа)</td>
                                    </tr>
                                    <tr>
                                        <td colspan=6>@org_bank@</td>
                                    </tr>
                                    <tr>
                                        <td>БИК</td>
                                        <td class=data width=90><div width=90>@org_bic@</div></td>
                                        <td style="padding-left: 75px;"></td>
                                        <td>№</td>
                                        <td class=data width=145><div width=145>@org_bank_schet@</div></td>
                                        <td width=*></td>
                                    </tr>
                                    <tr class=comment>
                                        <td colspan=2></td>
                                        <td style="padding-left: 75px;"></td>
                                        <td colspan=3 class=comment>(номер кор./с банка получателя платежа)</td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td><table width=100%>
                                    <td style="padding-right: 16px;">Плательщик</td>
                                    <td class=data width=100%>@user@</td>
                                </table></td>
                        </tr>
                        <tr>
                            <td><table width=100%>
                                    <td style="padding-right: 5px;" nowrap>Назначение платежа</td>
                                    <td class=data width=100%>Оплата заказа № @ouid@ от @date@</td>
                                </table></td>
                        </tr>
                        <tr>
                            <td><table width=100% cellpadding=0 cellspacing=0>
                                    <tr>
                                        <td style="padding-left: 90px;"></td>
                                        <td>Сумма платежа</td>
                                        <td width=65 class=data nowrap><div width=65>@total@</div></td>
                                        <td><?=$GetIsoValutaOrder?></td>
                                        <td width=65 class=data><div width=65></div></td>
                                        <td>коп.</td>
                                        <td width=100%></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 90px;"></td>
                                        <td>Итого</td>
                                        <td width=65 class=data nowrap><div width=65>@total@</div></td>
                                        <td><?=$GetIsoValutaOrder?></td>
                                        <td width=65 class=data><div width=65></div></td>
                                        <td>коп.</td>
                                        <td width=100%></td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td><div height=20>&nbsp;</div></td>
                        </tr>
                        <tr>
                            <td><table width=100% cellpadding=0 cellspacing=0>
                                    <td style="padding-right: 4px;">Плательщик</td>
                                    <td width=80 class=data><div width=80>&nbsp;</div></td>
                                    <td style="padding-left: 2px;">(подпись)</td>
                                    <td>&nbsp;&nbsp;&quot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&quot;&nbsp;&nbsp;</td>
                                    <td class=data width=85><div width=85>&nbsp;</div></td>
                                    <td>201&nbsp;&nbsp;г.</td>
                                </table></td>
                        </tr>
                        <tr>
                            <td align=left>С условием приема банком суммы, указанной в платежном документе, </td>
                        </tr>
                        <tr>
                            <td><table cellpadding=0 cellspacing=0 width=100%>
                                    <tr>
                                        <td>ознакомлен и согласен.</td>
                                        <td class=data width=85><div width=85>&nbsp;</div></td>
                                        <td>&nbsp;&nbsp;&quot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&quot;&nbsp;&nbsp;</td>
                                        <td class=data width=85><div width=85>&nbsp;</div></td>
                                        <td>201&nbsp;&nbsp;г.</td>
                                    </tr>
                                    <tr>
                                        <td colspan=2 align=right style="padding-right: 10px;" class=comment>(подпись плательщика)</td>
                                        <td></td>
                                        <td class=comment align=center>(Дата)</td>
                                        <td></td>
                                    </tr>
                                </table></td>
                        </tr>
                    </table></td>
            </tr>
        </table>
    </body>
</html