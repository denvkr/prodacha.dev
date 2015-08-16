<?php

/**
 * Добавление иконок авторизации в соц сетях.
 * @param array $obj объект
 * @param array $row массив данных
 * @param string $rout роутер места вызовы модуля [START|MIDDLE|END]
 */
function usersDisp_hook($obj,$row,$rout) {
    $obj->set('facebookAuth',ParseTemplateReturn($GLOBALS['SysValue']['templates']['socauth']['socauth_facebook_link_icon'], true));
    $obj->set('twitterAuth',ParseTemplateReturn($GLOBALS['SysValue']['templates']['socauth']['socauth_twitter_link_icon'], true));
    $obj->set('twitterAuth',ParseTemplateReturn($GLOBALS['SysValue']['templates']['socauth']['socauth_vkontakte_link_icon'], true),true);
}



$addHandler=array
        (
        'usersDisp'=>'usersDisp_hook',
);

?>
