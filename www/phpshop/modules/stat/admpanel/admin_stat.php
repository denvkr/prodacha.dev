<?php
PHPShopObj::loadClass("modules");
//подключаем функции для совместимости с php4
include_once('php4_compatibility.php');

function actionStart() {   
    $stat = new PluginStat;
    $stat->getReport();

}

class PluginStat extends PHPShopModules {
    var $options = array();

    function PluginStat() {
        global $_classPath;
        parent::PHPShopModules($_classPath."modules/");

        // Имя графика
        $this->chartfile = '../../../UserFiles/Image/chart_'.md5($_ENV["PROCESSOR_IDENTIFIER"]).'.png';

        //initvars
        $this->options['fromdate'] = date('d-m-Y', strtotime('-1 week'));
        if (isset($_REQUEST['fromdate'])) {
            $this->options['fromdate'] = $_REQUEST['fromdate'];
        }
        $this->options['todate'] = date('d-m-Y');
        if (isset($_REQUEST['todate'])) {
            $this->options['todate'] = $_REQUEST['todate'];
        }

        //групировка результатов
        $this->options['group'] = 'day';
        if (isset($_REQUEST['group'])) {
            $this->options['group'] = $_REQUEST['group'];
        }
        $this->options['groups'] = array(
                'visitorshits'=>array(
                        'day'=>array('select'=>'дням','dateformat'=>'d-m-Y'),
                        'week'=>array('select'=>'неделям','dateformat'=>array('start'=>'с d-m-Y ', 'end'=>'по d-m-Y')),
                        'month'=>array('select'=>'месяцам','dateformat'=>'m-Y'),
                        'year'=>array('select'=>'годам','dateformat'=>'Y'),
                    ),
                 'fromsites'=>array(),
                 'views'=>array(),
        );

        //виды графиков
        $this->options['chartmode'] = 'line';
        if (isset($_REQUEST['chartmode'])) {
            $this->options['chartmode'] = $_REQUEST['chartmode'];
        }
        
        $this->options['chartmodes'] = array(
                'visitorshits'=>array(
                        'line'=>'линейный',
                        'bar'=>'столбцы',
                    ),
                'fromsites'=>array(),
                'views'=>array(),
        );

        //настройки для подробного отчета
        //URL
        $this->options['fullmode']['page']['selected'] = 'none';
        if (isset($_REQUEST['fullmode']['page']['selected'])){
            $this->options['fullmode']['page']['selected'] = $_REQUEST['fullmode']['page']['selected'];
        }
        $this->options['fullmode']['page']['search'] = null;
        if (isset($_REQUEST['fullmode']['page']['search'])){
            $this->options['fullmode']['page']['search'] = $_REQUEST['fullmode']['page']['search'];
        }
        $this->options['fullmode']['page']['select'] = array(
            'none'=>'не важно',
            'like'=>'содержит',
            'not like'=>'не содержит',
        );

        //referer
        $this->options['fullmode']['referer']['selected'] = 'none';
        if (isset($_REQUEST['fullmode']['referer']['selected'])){
            $this->options['fullmode']['referer']['selected'] = $_REQUEST['fullmode']['referer']['selected'];
        }
        $this->options['fullmode']['referer']['search'] = null;
        if (isset($_REQUEST['fullmode']['referer']['search'])){
            $this->options['fullmode']['referer']['search'] = $_REQUEST['fullmode']['referer']['search'];
        }
        $this->options['fullmode']['referer']['select'] = array(
            'none'=>'не важно',
            'like'=>'содержит',
            'not like'=>'не содержит',
        );

        //ip
        $this->options['fullmode']['ip']['selected'] = 'none';
        if (isset($_REQUEST['fullmode']['ip']['selected'])){
            $this->options['fullmode']['ip']['selected'] = $_REQUEST['fullmode']['ip']['selected'];
        }
        $this->options['fullmode']['ip']['search'] = null;
        if (isset($_REQUEST['fullmode']['ip']['search'])){
            $this->options['fullmode']['ip']['search'] = $_REQUEST['fullmode']['ip']['search'];
        }
        $this->options['fullmode']['ip']['select'] = array(
            'none'=>'не важно',
            'like'=>'содержит',
            'not like'=>'не содержит',
        );

        //unique
        $this->options['fullmode']['unique']['checked'] = 0;
        if (isset($_REQUEST['fullmode']['unique']['checkbox'])){
            $this->options['fullmode']['unique']['checked'] = 1;
        }

        //типы отчетов
        $this->options['reportmode'] = 'visitorshits';
        if (isset($_REQUEST['reportmode'])) {
            $this->options['reportmode'] = $_REQUEST['reportmode'];
        }
        $this->options['reportmodes'] = array(
                'visitorshits'=>'посетители/просмотры',
                'fromsites'=>'переходы с сайтов',
                'views'=>'просмотры страниц',
                'bots'=>'поисковые боты',
                'sewords'=>'поисковые запросы',
                'full'=>'подробная статистика',
        );


        //подключаем интерфейс
        include_once('plugin_stat_interface.php');
        $this->interface = new PluginStatInterface();
        //interface vars
        $this->interface->params = $this->options;
        $this->interface->window = true;
        
        //подключаем класс для работы с БД
        include_once('plugin_stat_model.php');
        $this->visitors = new PluginStatModel($this->getParam("base.stat.stat_visitors"));
        include_once('plugin_stat_model_sebots.php');
        $this->bots = new PluginStatModelSebots($this->getParam("base.stat.stat_sebots"));

    }

    function getReport() {
        switch ($this->options['reportmode']) {
            case 'visitorshits':
                $this->getVisitorsReport();
                break;
            
            case 'fromsites':
                $this->getRefererReport();
                break;

            case 'views':
                $this->getViewsReport();
                break;
            
            case 'bots':
                $this->getBotsReport();
                break;

            case 'sewords':
                $this->getSEWordsReport();
                break;

            case 'full':
                $this->getFullReport();
                $this->interface->getPage(true, true);
                return;
                break;

            default:
                break;
        }
        $this->interface->getPage(true);
    }

    function getVisitorsReport() {

        $from = $this->interface->datelimit->dayStart($this->interface->datelimit->getTimeFromDMY($this->options['fromdate']));
        $to = $this->interface->datelimit->dayEnd($this->interface->datelimit->getTimeFromDMY($this->options['todate']));

        $visits = $this->visitors->findVisitorsHitsCount($from, $to, $this->options['group']);
        if (count($visits)>0){//если есть данные
            $this->interface->setCaption(array("Период","33%"),array("Посетители","33%"),array("Просмотры","34%"));
            foreach ($visits as $visit) {
                $cap = $this->interface->getPeriodCaption($visit['time']);
                $this->interface->setRow('',$cap,$visit['visitors'],$visit['hits']);
                $forchart[] = array(
                        'Name'=>iconv('cp1251', 'utf-8', $cap),
                        iconv('cp1251', 'utf-8', 'Посетители')=>$visit['visitors'],
                        iconv('cp1251', 'utf-8', 'Просмотры страниц')=>$visit['hits'],
                );
            }

            //make chart

            $chartfile = $this->chartfile;
            $chartimg = $this->buildChart($forchart, 'График посещений', $chartfile, $this->options['chartmode']);
            $this->interface->setChart($chartimg);

            $this->interface->setContent($this->interface->Compile());
        }else{//если нет данных
            $this->interface->setContent($this->noResults());
        }
    }

    function getFullReport() {

        $from = $this->interface->datelimit->dayStart($this->interface->datelimit->getTimeFromDMY($this->options['fromdate']));
        $to = $this->interface->datelimit->dayEnd($this->interface->datelimit->getTimeFromDMY($this->options['todate']));
        $con = array();
        if ($this->options['fullmode']['page']['search'] && ($this->options['fullmode']['page']['selected']!='none')){
            if ($this->options['fullmode']['page']['selected'] == 'like'){
                $con['`request_uri` LIKE '] = "'%".$this->options['fullmode']['page']['search']."%'";
            }elseif($this->options['fullmode']['page']['selected'] == 'not like'){
                $con['`request_uri` NOT LIKE '] = "'%".$this->options['fullmode']['page']['search']."%'";
            }
        }
        
        if ($this->options['fullmode']['referer']['search'] && ($this->options['fullmode']['referer']['selected']!='none')){
            if ($this->options['fullmode']['referer']['selected'] == 'like'){
                $con['`referer` LIKE '] = "'%".$this->options['fullmode']['referer']['search']."%'";
            }elseif($this->options['fullmode']['referer']['selected'] == 'not like'){
                $con['`referer` NOT LIKE '] = "'%".$this->options['fullmode']['referer']['search']."%'";
            }
        }
        
        if ($this->options['fullmode']['ip']['search'] && ($this->options['fullmode']['ip']['selected']!='none')){
            if ($this->options['fullmode']['ip']['selected'] == 'like'){
                $con['`ip` LIKE '] = "'%".$this->options['fullmode']['ip']['search']."%'";
            }elseif($this->options['fullmode']['ip']['selected'] == 'not like'){
                $con['`ip` NOT LIKE '] = "'%".$this->options['fullmode']['ip']['search']."%'";
            }
        }

        $unique = false;
        if ($this->options['fullmode']['unique']['checked']){
            $unique = true;
        }

        $visits = $this->visitors->findVisitors($from, $to, $con, $unique);
        if (count($visits)>0){//если есть данные
            $this->interface->setCaption(
                            array("Дата","9%"),
                            array("URL","20%"),
                            array("Реферер","20%"),
                            array("IP","5%"),
                            array("User-Agent","50%")
                    );
            foreach ($visits as $visit) {
                $this->interface->setRow('',
                            date('d.m.Y H:i:s', $visit['timestamp']),
                            $this->interface->link($visit['request_uri']),
                            $this->interface->link($visit['referer']),
                            $visit['ip'],
                            $visit['user_agent']
                        );
            }

            $this->interface->setContent($this->interface->Compile());
        }else{//если нет данных
            $this->interface->setContent($this->noResults());
        }
    }

    function getRefererReport() {

        $from = $this->interface->datelimit->dayStart($this->interface->datelimit->getTimeFromDMY($this->options['fromdate']));
        $to = $this->interface->datelimit->dayEnd($this->interface->datelimit->getTimeFromDMY($this->options['todate']));

        $referers = $this->visitors->findReferers($from, $to);
        if (count($referers)>0){//если есть данные
            $this->interface->setCaption(array("Сайт источник перехода","70%"),array("Переходов","30%"));
            foreach ($referers as $referer) {
                $this->interface->setRow('',$this->interface->link($referer['referer_host']),$referer['rcount']);
                $forchart[] = array(
                        'Name'=>iconv('cp1251', 'utf-8', $referer['referer_host']),
                        iconv('cp1251', 'utf-8', 'Сайты источники переходов')=>$referer['rcount'],
                );
            }

            //make chart

            $chartfile = $this->chartfile;
            $chartimg = $this->buildChart($forchart, 'График переходов', $chartfile, 'pie'/*$this->options['chartmode']*/);
            $this->interface->setChart($chartimg);

            $this->interface->setContent($this->interface->Compile());
        }else{//если нет данных
            $this->interface->setContent($this->noResults());
        }
    }

    function getSEWordsReport() {

        $from = $this->interface->datelimit->dayStart($this->interface->datelimit->getTimeFromDMY($this->options['fromdate']));
        $to = $this->interface->datelimit->dayEnd($this->interface->datelimit->getTimeFromDMY($this->options['todate']));

        $words = $this->visitors->findSEWords($from, $to);
        if (count($words)>0){//если есть данные
            $this->interface->setCaption(array("Поисковая фраза","70%"),array("Количество","30%"));
            foreach ($words as $word) {
                $this->interface->setRow('',$this->interface->link($word['referer'], $word['seword']),$word['wcount']);
                $forchart[] = array(
                        'Name'=>iconv('cp1251', 'utf-8', $word['seword']),
                        iconv('cp1251', 'utf-8', 'Поисковая фраза')=>$word['wcount'],
                );
            }

            //make chart

            $chartfile = $this->chartfile;
            $chartimg = $this->buildChart($forchart, 'График переходов', $chartfile, 'pie'/*$this->options['chartmode']*/);
            $this->interface->setChart($chartimg);

            $this->interface->setContent($this->interface->Compile());
        }else{//если нет данных
            $this->interface->setContent($this->noResults());
        }
    }

    function getViewsReport() {

        $from = $this->interface->datelimit->dayStart($this->interface->datelimit->getTimeFromDMY($this->options['fromdate']));
        $to = $this->interface->datelimit->dayEnd($this->interface->datelimit->getTimeFromDMY($this->options['todate']));

        $views = $this->visitors->findViews($from, $to);
        if (count($views)>0){//если есть данные
            $this->interface->setCaption(array("Страница","70%"),array("Просмотров","30%"));
            foreach ($views as $view) {
                $this->interface->setRow('',$this->interface->link($view['request_uri']),$view['uricount']);
                $forchart[] = array(
                        'Name'=>iconv('cp1251', 'utf-8', $view['request_uri']),
                        iconv('cp1251', 'utf-8', 'Страницы')=>$view['uricount'],
                );
            }

            //make chart

            $chartfile = $this->chartfile;
            $chartimg = $this->buildChart($forchart, 'График просмотров', $chartfile, 'pie'/*$this->options['chartmode']*/);
            $this->interface->setChart($chartimg);

            $this->interface->setContent($this->interface->Compile());
        }else{//если нет данных
            $this->interface->setContent($this->noResults());
        }
    }

    function getBotsReport() {

        $from = $this->interface->datelimit->dayStart($this->interface->datelimit->getTimeFromDMY($this->options['fromdate']));
        $to = $this->interface->datelimit->dayEnd($this->interface->datelimit->getTimeFromDMY($this->options['todate']));

        $bots = $this->visitors->findBots($from, $to);
        $botslist = $this->bots->listBots();
        if (count($bots)>0){//если есть данные
            $this->interface->setCaption(array("Имя бота","70%"),array("Посещений","30%"));
            foreach ($bots as $bot) {
                $this->interface->setRow('',$botslist[$bot['sebot_id']],$bot['count']);
                $forchart[] = array(
                        'Name'=>iconv('cp1251', 'utf-8', $botslist[$bot['sebot_id']]),
                        iconv('cp1251', 'utf-8', 'Страницы')=>$bot['count'],
                );
            }

            //make chart

            $chartfile = $this->chartfile;
            $chartimg = $this->buildChart($forchart, 'График приходов бота', $chartfile, 'pie'/*$this->options['chartmode']*/);
            $this->interface->setChart($chartimg);

            $this->interface->setContent($this->interface->Compile());
        }else{//если нет данных
            $this->interface->setContent($this->noResults());
        }
    }

    function noResults() {
        return '<h2>Для выбранных параметров нет результатов</h2>';
    }

    function buildChart($chartdata, $title, $imgplace, $mode = 'line') {
        // подключаем pChart
        include "pChart/pChart.class";
        include "pChart/pData.class";
        $font = '../../modules/stat/tahoma.ttf';


        $data = new pData;
        $data->Data = $chartdata;
        $data->AddAllSeries();
        $data->SetAbsciseLabelSerie('Name');


        // теперь готовим гистограмму
        $chart = new pChart(680,450);
        //$chart->drawGraphAreaGradient(250,231,181,90,TARGET_BACKGROUND);
        $chart->drawGraphAreaGradient(219,230,245,50,TARGET_BACKGROUND);
        // задаем шрифт
        $chart->setFontProperties($font, 8);
        $chart->loadColorPalette('../../modules/stat/myton.txt');
        
        

        // drow scale
        if ($mode != 'pie'){
            // какую часть изображения выделить под гистограмму?
            $chart->setGraphArea(50, 75, 650, 320);
            $chart->drawGraphArea(252,252,252, false);
            $chart->drawScale($data->GetData(),$data->GetDataDescription(),
                    SCALE_NORMAL ,0,0,0,TRUE,45,0,TRUE);
            $chart->drawGrid(4,TRUE,230,230,230,0);
        }

        switch ($mode) {
            case 'line':
            // drow line chart
                $chart->drawLineGraph($data->GetData(),$data->GetDataDescription());
                $chart->drawPlotGraph($data->GetData(),$data->GetDataDescription(),3,2,255,255,255);
                //drow legend
                $chart->setFontProperties($font,12);
                $chart->drawLegend(60,15,$data->GetDataDescription(),255,255,255);
                break;
            case 'bar':
            // Draw the bar chart
                $chart->drawBarGraph($data->GetData(),$data->GetDataDescription(),true, 100);
                //drow legend
                $chart->setFontProperties($font,12);
                $chart->drawLegend(60,15,$data->GetDataDescription(),255,255,255);
                break;
            case 'pie':
            // Draw the bar chart
                $chart->drawPieGraph($data->GetData(),$data->GetDataDescription(),230,200,190,PIE_PERCENTAGE,TRUE,50,20,5);
                $chart->drawPieLegend(450,15,$data->GetData(),$data->GetDataDescription(),250,250,250);
                break;

            default:
                break;
        }


        // Draw the stacked bar chart
//    $chart->drawStackedBarGraph($data->GetData(),$data->GetDataDescription(),100, false);

        // Draw the overlay bar chart
//    $chart->drawOverlayBarGraph($data->GetData(),$data->GetDataDescription(),100);


        //drow title
//        $chart->setFontProperties($font,16);
//        $chart->drawTitle(200,50,iconv('cp1251', 'utf-8', $title),50,50,50,585);
        //drow border
        $chart->addBorder(1);

        // выводим результат
        $chart->Render($imgplace);
        return $imgplace;
    }
   
}
?>
