<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function get_vote_page_id ($QUESTION){
    if ($QUESTION=='http://test.prodacha.ru/page/china_engines.html' || $QUESTION=='http://prodacha.ru/page/china_engines.html') {
        return 'VOTED1';
    } else
     if ($QUESTION=='http://test.prodacha.ru/page/motoblok_ili_motokultivator.html' || $QUESTION=='http://prodacha.ru/page/motoblok_ili_motokultivator.html'){
        return 'VOTED2';
    } else
     if ($QUESTION=='http://test.prodacha.ru/page/kak_vybrat_motoblok.html'  || $QUESTION=='http://prodacha.ru/page/kak_vybrat_motoblok.html'){
        return 'VOTED3';
    } else
     if ($QUESTION=='http://test.prodacha.ru/page/kak_vybrat_motokultivator.html' || $QUESTION=='http://prodacha.ru/page/kak_vybrat_motokultivator.html'){
        return 'VOTED4';
    } else
     if ($QUESTION=='http://test.prodacha.ru/page/frezy_dlya_motobloka.html' || $QUESTION=='http://prodacha.ru/page/frezy_dlya_motobloka.html'){
        return 'VOTED5';
    } else
     if ($QUESTION=='http://test.prodacha.ru/page/kak_vybrat_motoblok_po_funkcionalu.html' || $QUESTION=='http://prodacha.ru/page/kak_vybrat_motoblok_po_funkcionalu.html'){
        return 'VOTED6';
    } else
     if ($QUESTION=='http://prodacha.ru/page/reduktory_motoblokov.html') {
        return 'VOTED7';
     } else
        return ''; 
}