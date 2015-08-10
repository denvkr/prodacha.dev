<?php
class VisitorsWriter {
    var $userData = array();
    var $Visitors = null;
    var $SeBots = null;
    var $botlist = null;

    function VisitorsWriter() {//constructor
        $this->Visitors = new PHPShopOrm($GLOBALS['SysValue']['base']['stat']['stat_visitors']);
        $this->SeBots = new PHPShopOrm($GLOBALS['SysValue']['base']['stat']['stat_sebots']);
    }
    
    function init() {
        //init bots vars
        $this->botlist = $this->SeBots->select(array('id', 'useragent', 'host', 'qvar'));
        //init visitor vars
        $this->userData['ip'] = $_SERVER['REMOTE_ADDR'];
        if ($_SERVER['HTTP_REFERER']){
            $parsedurl = parse_url($_SERVER['HTTP_REFERER']);

            $parsedurl['host'] = preg_replace('/^www./i', '', $parsedurl['host']);
            
            $referer = $parsedurl['scheme'].'://'.$parsedurl['host'].$parsedurl['path'];
            if ($parsedurl['query']){
                $referer .= '?'.$parsedurl['query'];
            }
            $referer_host = $parsedurl['scheme'].'://'.$parsedurl['host'].'/';
            $referer = rawurldecode($referer);
            if (is_unicode($referer)) {
              $referer=iconv("utf-8","windows-1251",$referer);
            }
            $this->userData['referer'] = $referer;
            $this->userData['referer_host'] = $referer_host;
        }
        $this->userData['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $this->userData['request_uri'] = $_SERVER['REQUEST_URI'];
        $this->userData['sebot_id'] = $this->detectBot($_SERVER['HTTP_USER_AGENT']);
        $this->userData['seword'] = $this->isFromSE($referer);
        $time = time();
        $this->userData['timestamp'] = $time;
        $this->userData['gyear'] = date('Y', $time);
        $this->userData['gmonth'] = date('Ym', $time);
        $this->userData['gweek'] = date('YW', $time);
        $this->userData['gday'] = date('Ymd', $time);
    }

    function detectBot($ua = null) {
        if (!$ua) return 0;
        $bots = $this->botlist;
        foreach ($bots as $bot) {
            if (preg_match('/'.$bot['useragent'].'/i', $ua)){
                //Bot Detected!!
                return $bot['id'];
            }
        }
        return 0;
    }

    function isFromSE($referer = null) {
        if (!$referer) return '';
        $bots = $this->botlist;
        foreach ($bots as $bot) {
            $bot['host']=str_replace('//', '\/\/', $bot['host']);
            if (@preg_match('/^('.$bot['host'].')/i', $referer)){
                $urlarr = parse_url($referer);
                if (!empty($urlarr['query'])){
                    parse_str($urlarr['query'], $query);
                    if (isset($query[$bot['qvar']])){
                        return substr($query[$bot['qvar']], 0, 255);
                    }
                }
            }
        }
        return '';
    }

    function write() {
        $this->init();
        if (!preg_match('/(gif|png|jpg|jpeg|bmp|ico|css|js|captcha.php)$/i', $this->userData['request_uri'])) {
            $this->Visitors->insert($this->userData, null);
        }
    }

    function fillTbl() {//just for test
        if (preg_match('/(gif|png|jpg|jpeg|bmp)$/i', $this->userData['request_uri'])) return;
        for ($i = 1; $i<10000; $i++){
            $time = rand(1104526800, 1262293200);
            $ar = array(
                'ip'=>rand(1, 255).'.'.rand(1, 255).'.'.rand(1, 255).'.'.rand(1, 255),
                'timestamp'=>$time,
                'gyear'=>date('Y', $time),
                'gmonth'=>date('Ym', $time),
                'gweek'=>date('YW', $time),
                'gday'=>date('Ymd', $time),
            );
            $this->Visitors->insert($ar, null);
        }
    }

}
require 'phpshop/modules/stat/admpanel/php4_compatibility.php';

$writer = new VisitorsWriter();
$writer->write();
//$writer->fillTbl();//fill test data

?>
