<?php
class PluginStatModelSebots extends PHPShopOrm {

    function listBots() {
        $fields = array(
            'id',
            'name',
        );
        $list = $this->select($fields);
        foreach ($list as $value) {
            $return[$value['id']] = $value['name'];
        }
        return $return;
    }
}

?>
