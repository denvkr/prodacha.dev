<?php
class PluginStatInterface extends PHPShopInterface{
    var $content = null;
    var $chart = null;
    var $header = null;
    var $params = array();
    var $datelimit = null;

    function PluginStatInterface() {
        parent::PHPShopInterface();
        //подключаем класс для работы с датами
        include_once('datelimit.class.php');
        $this->datelimit = new DateLimit();
    }

    function getPage($echo = false, $fullmode = false) {
        if ($fullmode){
            $content = $this->getFullForm().'<table width="100%"><tr valign="top">'.$this->getChart().$this->getContent().'</tr></table>';
        }else{
            $content = $this->getForm().'<table width="100%"><tr valign="top">'.$this->getChart().$this->getContent().'</tr></table>';
        }
        if ($echo){
            echo ($content);
        }
        return $content;
    }

    function setContent($value) {
        $this->content = $value;
    }

    function setChart($value) {
        $this->chart = $value;
    }

    function getContent() {
        if (!$this->content) return '';
        return '<td width="100%">'.$this->content.'</td>';
    }

    function getChart() {
        if (!$this->chart) return '';
        $inner = '<div id="interfacesWin" name="terfacesWin" align="left" style="width:100%;overflow:auto">
                  <table width="100%"  cellpadding="0" cellspacing="0" style="border:1px;border-style:inset;">
                  <tr>
                         <td valign="top">
                            <table cellpadding="0" cellspacing="1" width="100%" border="0">
                                <tr><td width="100%"><button class="pane"><img src="'.$this->imgPath.'arrow_d.gif" width="7" height="7" border="0" hspace="5">График</button></td></tr>
                                <tr class="row" id="rchart"><td align="center"><img src="'.$this->chart.'" width="682"></td></tr>
                                </table>
                         </td>
                  </tr>
                  </table></div>
        ';
        return '<td width="700">'.$inner.'</td>';
    }


    function getMenuRow($pieces = array()) {
        if (count($pieces)<1) return '';
        $delimeter = '<td width="5"></td><td width="1" bgcolor="#ffffff"></td><td width="1" class="menu_line"></td><td width="6"></td>';
        $first = true;
        foreach ($pieces as $piece) {
            if (empty($piece)) continue;
            if (!$first){
                $tds .= $delimeter;
            }
            $tds .= '<td>'.$piece.'</td>';
            $first = false;
        }
        $items = '<td width="15"></td>'.$tds.'<td width="5"></td>';
        $menurow = '<table width="100%" cellpadding="0" cellpadding="0" style="border: 1px;border-style: outset;">
                    <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0">
                                <tr>'.$items.'
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </table>';
        return $menurow;
    }

    function getFullForm() {
        $fields = array();
        $fields[] = $this->getReportModeSelect();
        $fields[] = $this->getDateFileds();
        $fields[] = $this->getGroupSelect();
        $fields[] = $this->getChartModeSelect();
        $fullfields[] = $this->getFullModePageField();
        $fullfields[] = $this->getFullModeRefererField();
        $fullfields[] = $this->getFullModeIpField();
        $fullfields[] = $this->getFullModeUniqueField();
        $fields[] = '<input type="submit" value="Показать">';
        $form = '
        <form method="GET">
            <input type="hidden" name="plugin" value="stat">
            '.$this->getMenuRow($fields).'
            '.$this->getMenuRow($fullfields).'
        </form>
        ';
        return $form;
    }

    function getForm() {
        $fields = array();
        $fields[] = $this->getReportModeSelect();
        $fields[] = $this->getDateFileds();
        $fields[] = $this->getGroupSelect();
        $fields[] = $this->getChartModeSelect();
        $fields[] = '<input type="submit" value="Показать">';
        $form = '
        <form method="GET">
            <input type="hidden" name="plugin" value="stat">
            '.$this->getMenuRow($fields).'
        </form>
        ';
        return $form;
    }

    function getDateFileds() {
        $fields = '
        с <input type="text" name="fromdate" value="'.$this->params['fromdate'].'" size="8" autocomplete="off" onclick="popUpCalendar(this, this, \'dd-mm-yyyy\');">
        по <input type="text" name="todate" value="'.$this->params['todate'].'" size="8" autocomplete="off" onclick="popUpCalendar(this, this, \'dd-mm-yyyy\');">
        ';
        return $fields;
    }

    function getGroupSelect() {
        if (count($this->params['groups'][$this->params['reportmode']])<1) return '';
        
        foreach ($this->params['groups'][$this->params['reportmode']] as $key => $value) {
            $selected = '';
            if ($this->params['group'] == $key) $selected = ' selected="selected" ';
            $options.='<option value="'.$key.'" '.$selected.'>'.$value['select'].'</option>';
        }
        $select = 'группировать по <select name="group">'.$options.'</select>';
        return $select;
    }

    function getChartModeSelect() {
        if (count($this->params['chartmodes'][$this->params['reportmode']])<1) return '';
        
        foreach ($this->params['chartmodes'][$this->params['reportmode']] as $key => $value) {
            $selected = '';
            if ($this->params['chartmode'] == $key) $selected = ' selected="selected" ';
            $options.='<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }
        $select = 'вид графика <select name="chartmode">'.$options.'</select>';
        return $select;
    }

    function getReportModeSelect() {
        foreach ($this->params['reportmodes'] as $key => $value) {
            $selected = '';
            if ($this->params['reportmode'] == $key) $selected = ' selected="selected" ';
            $options.='<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }
        $select = 'Тип отчета <select name="reportmode">'.$options.'</select>';
        return $select;
    }

    function getPeriodCaption($time) {
        $curentgroup = $this->params['group'];
        if (is_array($this->params['groups'][$this->params['reportmode']][$curentgroup]['dateformat'])){
            $startmethod = $curentgroup.'Start';
            $endmethod = $curentgroup.'End';
            $caption = $this->datelimit->$startmethod($time, $this->params['groups'][$this->params['reportmode']][$curentgroup]['dateformat']['start']);
            $caption .= $this->datelimit->$endmethod($time, $this->params['groups'][$this->params['reportmode']][$curentgroup]['dateformat']['end']);
            return $caption;
        }else{
            return date($this->params['groups'][$this->params['reportmode']][$curentgroup]['dateformat'], $time);
        }
    }

    function getFullModePageField() {
        $return = 'URL ';
        $return .= '<select name="fullmode[page][selected]">';
        foreach ($this->params['fullmode']['page']['select'] as $key => $value) {
            $selected = '';
            if ($key == $this->params['fullmode']['page']['selected']) $selected = ' selected="selected" ';
            $return.='<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }
        $return .= '<input type="text" name="fullmode[page][search]" value="'.$this->params['fullmode']['page']['search'].'">';
        return $return;
    }

    function getFullModeRefererField() {
        $return = 'Реферер ';
        $return .= '<select name="fullmode[referer][selected]">';
        foreach ($this->params['fullmode']['referer']['select'] as $key => $value) {
            $selected = '';
            if ($key == $this->params['fullmode']['referer']['selected']) $selected = ' selected="selected" ';
            $return.='<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }
        $return .= '<input type="text" name="fullmode[referer][search]" value="'.$this->params['fullmode']['referer']['search'].'">';
        return $return;
    }

    function getFullModeIpField() {
        $return = 'IP ';
        $return .= '<select name="fullmode[ip][selected]">';
        foreach ($this->params['fullmode']['ip']['select'] as $key => $value) {
            $selected = '';
            if ($key == $this->params['fullmode']['ip']['selected']) $selected = ' selected="selected" ';
            $return.='<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }
        $return .= '<input type="text" name="fullmode[ip][search]" value="'.$this->params['fullmode']['ip']['search'].'">';
        return $return;
    }

    function getFullModeUniqueField() {
        $checked = '';
        if ($this->params['fullmode']['unique']['checked']) $checked = ' checked="checked" ';
        $return = '<input type="checkbox" name="fullmode[unique][checkbox]" '.$checked.'>';
        $return .= 'только уникальные хосты';
        return $return;
    }

    function link($url, $title = null) {
        if (!$title) $title = $url;
        if(strlen($url)>35) $dots='...';
        else $dots=false;
        $link = '<a href="'.$url.'" title="'.$title.'" target="_blank">'.substr($title, 0, 35).$dots.'</a>';
        return $link;
    }
}

?>
