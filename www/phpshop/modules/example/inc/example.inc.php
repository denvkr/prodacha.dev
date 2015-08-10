<?

class PHPShopExampleElement extends PHPShopElements {

    // Конструктор
    function PHPShopExampleElement() {
        parent::PHPShopElements();
    }

    // Прорисовка ссылки Example
    function addToTopMenu() {

        // Название меню
        $this->set('topMenuName','Example');

        // Ссылка
        $this->set('topMenuLink','index');

        // Парсируем шаблон с заменой 'page' на 'example'
        $dis=$this->PHPShopModules->Parser(array('page'=>'example'),$this->getValue('templates.top_menu'));
        return $dis;
    }

    // Прорисовка текстового блока Example
    function addToRightMenu() {

        // Название меню
        $this->set('leftMenuName','Example');

        // Ссылка
        $this->set('leftMenuContent','<p>Текстовый блок Example сгенерирован модулем Example в файле example.inc.php</p>');

        // Парсируем шаблон
        $dis=$this->parseTemplate($this->getValue('templates.right_menu'));
        return $dis;
    }
}




// Вызываем генерацию главного горизонтального меню
$PHPShopTextElement = &new PHPShopTextElement();
$PHPShopTextElement->init('topMenu'); // Вывод главного меню
$PHPShopTextElement->init('rightMenu'); // Вывод главного меню
//
// Добавляем ссылку Example в главного горизонтальное меню
$PHPShopExampleElement = &new PHPShopExampleElement();
$GLOBALS['SysValue']['other']['topMenu'].=$PHPShopExampleElement->addToTopMenu();

// Добавляем ссылку Example в левый текстовый блок
$PHPShopExampleElement = &new PHPShopExampleElement();
$GLOBALS['SysValue']['other']['rightMenu'].=$PHPShopExampleElement->addToRightMenu();
?>