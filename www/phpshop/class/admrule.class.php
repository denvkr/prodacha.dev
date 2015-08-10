<?php

/**
 * Библиотека проверки прав администрирования
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 * @param string $Status права пользователя
 */
class PHPShopAdminRule {

    var $UserStatus;

    /**
     * Конструктор
     */
    function PHPShopAdminRule($Status) {
        global $UserStatus;

        if (empty($Status))
            $this->UserStatus = $UserStatus;
        else
            $this->UserStatus = $Status;
    }

    /**
     * Проверка прав
     * @param string $path раздел администрирования [news|gbook]
     * @param string $do действие [view|edit|remove]
     * @return boolean 
     */
    function CheckedRules($path, $do = 'view') {

        $rules_array = array(
            'view' => 0,
            'edit' => 1,
            'create' => 2,
            'remove' => 4,
            'all'=>3,
            'rule'=>5
            );

        $array = explode("-", $this->UserStatus[$path]);

        if (!empty($array[$rules_array[$do]]))
            return true;
    }

    /**
     * Собщение об отсутствии права
     */
    function BadUserFormaWindow() {
        echo'
	  <script>
	  if(confirm("Внимание ' . $_SESSION['logPHPSHOP'] . '!\nУ Вас недостаточно прав для выполнения данной операции.\nЗакрыть это окно?"))
	  window.close();
          else history.back(1);
	  </script>';
    }

}

?>
