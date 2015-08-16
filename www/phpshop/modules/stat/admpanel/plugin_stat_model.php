<?php
class PluginStatModel extends PHPShopOrm {

    function findVisitorsHitsCount($from, $to, $group) {
        $fields = array(
            'COUNT(`id`) AS hits',
            'COUNT(DISTINCT `ip`) as visitors',
            '`timestamp` as time',
        );
        $conditions = array(
            '`timestamp` > ' => $from,
            '`timestamp` < ' => $to,
            '`sebot_id` = ' => 0,
        );
        $order = array(
            'group'=>'g'.$group,
        );
        $return = $this->select($fields, $conditions, $order);
        if (isset($return['hits'])){//hook if result has only 1 row
            $tmpreturn = $return;
            unset($return);
            $return[] = $tmpreturn;
        }
        return $return;
    }

    function findVisitors($from, $to, $con = array(), $unique = false) {
        $conditions = array(
            '`timestamp` > ' => $from,
            '`timestamp` < ' => $to,
            '`sebot_id` = ' => 0,
        );
        if (count($con)>0){
            $conditions = array_merge($conditions, $con);
        }
        
        if ($unique){
            $order = array(
                'group'=>'ip',
            );
        }else{
            $order = array();
        }
        
        $fields =  array(
            ' `ip`',
            ' `timestamp`',
            ' `request_uri`',
            ' `referer`',
            ' `user_agent`',
        );
        
        $return = $this->select($fields, $conditions, $order);
        if (isset($return['ip'])){//hook if result has only 1 row
            $tmpreturn = $return;
            unset($return);
            $return[] = $tmpreturn;
        }
        return $return;
    }

    function findReferers($from, $to) {
        $fields = array(
            'referer_host',
            'COUNT(`referer_host`) as rcount',
        );
        $conditions = array(
            '`timestamp` > ' => $from,
            '`timestamp` < ' => $to,
            '`referer_host` NOT IN ' => "('', 'http://".$_SERVER['SERVER_NAME']."/')",
            '`sebot_id` = ' => 0,
        );
        $order = array(
            'GROUP'=>'referer_host',
            'ORDER'=>'rcount DESC',
        );
        $return = $this->select($fields, $conditions, $order);
        if (isset($return['referer_host'])){//hook if result has only 1 row
            $tmpreturn = $return;
            unset($return);
            $return[] = $tmpreturn;
        }
        return $return;
    }

    function findSEWords($from, $to) {
        $fields = array(
            'seword',
            'referer',
            'COUNT(`seword`) as wcount',
        );
        $conditions = array(
            '`timestamp` > ' => $from,
            '`timestamp` < ' => $to,
            '`seword` <> ' => "''",
            '`sebot_id` = ' => 0,
        );
        $order = array(
            'GROUP'=>'seword',
            'ORDER'=>'wcount DESC',
        );
        $return = $this->select($fields, $conditions, $order);
        if (isset($return['seword'])){//hook if result has only 1 row
            $tmpreturn = $return;
            unset($return);
            $return[] = $tmpreturn;
        }
        return $return;
    }

    function findViews($from, $to) {
        $fields = array(
            'request_uri',
            'COUNT(`id`) AS uricount',
        );
        $conditions = array(
            '`timestamp` > ' => $from,
            '`timestamp` < ' => $to,
            '`sebot_id` = ' => 0,
        );
        $order = array(
            'GROUP'=>'request_uri',
            'ORDER'=>'uricount DESC',
        );
        $return = $this->select($fields, $conditions, $order);
        if (isset($return['request_uri'])){//hook if result has only 1 row
            $tmpreturn = $return;
            unset($return);
            $return[] = $tmpreturn;
        }
        return $return;
    }
    
    function findBots($from, $to) {
        $fields = array(
            'sebot_id',
            'COUNT( id ) AS count',
        );
        $conditions = array(
            '`timestamp` > ' => $from,
            '`timestamp` < ' => $to,
            '`sebot_id` > ' => 0,
        );
        $order = array(
            'GROUP'=>'sebot_id',
            'ORDER'=>'count DESC',
        );
        $return = $this->select($fields, $conditions, $order);
        if (isset($return['count'])){//hook if result has only 1 row
            $tmpreturn = $return;
            unset($return);
            $return[] = $tmpreturn;
        }
        return $return;
    }
}

?>
