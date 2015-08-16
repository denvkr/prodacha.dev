<?php
class DateLimit {
    
    function dayStart($time, $format = null) {
        $resulttime = mktime('0', '0', '0', date('m', $time), date('d', $time), date('Y', $time));
        if ($format){
            return date($format, $resulttime);
        }
        return $resulttime;
    }

    function dayEnd($time, $format = null) {
        $resulttime = mktime('23', '59', '59', date('m', $time), date('d', $time), date('Y', $time));
        if ($format){
            return date($format, $resulttime);
        }
        return $resulttime;
    }

    function weekStart($time, $format = null) {
        $weeknumber = date('W', $time);
        $yearnumber = date('Y', $time);
//        $resulttime = strtotime($yearnumber.'W'.$weeknumber);
        $weekday = date('w', $time);
        if ($weekday === 0){
            $daycor = 6;
        }else{
            $daycor = $weekday-1;
        }
        $resulttime = mktime('0', '0', '0', date('m', $time), date('d', $time)-$daycor, date('Y', $time));
        if ($format){
            return date($format, $resulttime);
        }
        return $resulttime;
    }
    
    function weekEnd($time, $format = null) {
        $weeknumber = date('W', $time);
        $yearnumber = date('Y', $time);
//        $resulttime = strtotime($yearnumber.'W'.$weeknumber.', +1 week, -1 second');
        $weekday = date('w', $time);
        if ($weekday == 0){
            $daycor = 0;
        }else{
            $daycor = 7-$weekday;
        }
        $resulttime = mktime('23', '59', '59', date('m', $time), date('d', $time)+$daycor, date('Y', $time));
        if ($format){
            return date($format, $resulttime);
        }
        return $resulttime;
    }
    
    function monthStart($time, $format = null) {
        $resulttime = mktime('0', '0', '0', date('m', $time), '1', date('Y', $time));
        if ($format){
            return date($format, $resulttime);
        }
        return $resulttime;
    }
    
    function monthEnd($time, $format = null) {
        $date = date('-m-Y', $time);
        $resulttime = strtotime('1'.$date.', +1 months, -1 second');
        if ($format){
            return date($format, $resulttime);
        }
        return $resulttime;
    }

    function yearStart($time, $format = null) {
        $resulttime = mktime('0', '0', '0', '1', '1', date('Y', $time));
        if ($format){
            return date($format, $resulttime);
        }
        return $resulttime;
    }
    
    function yearEnd($time, $format = null) {
        $resulttime = mktime('23', '59', '59', '12', '31', date('Y', $time));
        if ($format){
            return date($format, $resulttime);
        }
        return $resulttime;
    }

    function getTimeFromDMY($dmy, $delimeter = '-') {
        $dmyarr = explode($delimeter, $dmy);
        $resulttime = mktime('0', '0', '0', $dmyarr[1], $dmyarr[0], $dmyarr[2]);
        return $resulttime;
    }
}

?>