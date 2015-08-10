<?php

/**
 *  Генерация случайных сео-предложений на основе схемы 
 * 
 * { блок } - начало и конец блока
 * |	    - или
 * <метка>  - заменяется значением
 * 
 * Пример: 
 *  В { {{этом|данном|текущем|настоящем} {разделе|подразделе}} | {{этой|данной|текущей|настоящей} {части|рубрике}}} {сайта|ресурса|вебсайта|портала|интернет-магазина} ООО "Трио Сервис" {представлены|находятся|показаны|выведены } {подходящие|соответствующие} фаркопы для <марка> <модель>:'
 */

require('config.php');
 
class SeoText
{
    public $str; // оригинальная строка-схема
    private $operators = array ('{', '}', '|');
    
    public function __construct($s)
    {
	$this->str = trim(preg_replace('/[\n\r\t]/', '', $s));
	
	try 
	{
	    $this->verify($this->str);
	}
	catch (Exception $e)
	{
	    echo 'Выброшено исключение: ',  $e->getMessage(), "<br>";
	    //die;
	}
    }
    
    
    /**
     * 
     * Проверка на ошибки
     * 
     * @throw
     * 
     */
    public function verify($s)
    {
	if (!$s) 
	{
	    throw new Exception ('Пустое значение!');
	}
	
	// проверка {*n == }*n
	$open_tag_count	    = substr_count($s, '{');
	$close_tag_count    = substr_count($s, '}');
	
	if ($open_tag_count != $close_tag_count) 
	{
	    throw new Exception('Количество открывающих тегов "{" не равно количеству закрывающих "}". ('. $open_tag_count .' != '. $close_tag_count .')');
	}
    }
    
        
    /**
     * 
     * Процесс парсинга
     * 
     * @return string 
     * 
     */
    public function parsing($s)
    {
	// крутим пока не останется ни одного "{"
	$new_string = $s;
	
	while (strpos($new_string, '{') !== false)
	{
//	    echo strpos($new_string, '{') . $new_string . '<hr>';
	    $new_string = $this->parse($new_string);
	}
		
	// очищяем 
	$new_string = $this->cleaning($new_string);
	
	return $new_string;
    }
    
    /**
     *
     * Парсим простые (без вложенных) блоки { ... }
     *  
     * @return string 
     * 
     */
    public function parse($s)
    {
	$r	= '';
	$blocks = array();
	preg_match_all('/{[^}^{]*}/', $s, $blocks);
	
	foreach ($blocks[0] as $i => $block) 
	{
	    $block  = trim($block);
	    $words  = explode('|', substr($block, 1, strlen($block) - 2));
	    $word   = $words[ array_rand($words) ];	    
	    
	    $r = str_replace($block, $word, $s );
	}
	return $r;
    }
    
    
    /**
     * 
     * Замена <меток> в тексте
     * 
     * @param string $s source text
     * @param array $labels (name => value)
     * 
     * @return string $s
     * 
     */
    public function labelReplace($s, $labels = array())
    {
	$r = $s;
	foreach ($labels as $name => $value)
	{
	    $r = str_replace('<' . $name . '>', $value, $r);
	}
	return $r;
    }
    
    /**
     * 
     * Очистка оставшихся контрольных символов
     * 
     * @return string
     *  
     */
    public function cleaning($s)
    {
	return str_replace($this->operators, '', $s);
    }
    
    
    /**
     * 
     * Выводим в удобочитаемом формате
     * 
     * @return string 
     * 
     */
    public function print_r($mode = 'html')
    {
	$s	= $this->str ;
	$level	= 0;
	$r	= '';
		
	// определяем режим вывода
	switch ($mode) 
	{
	    case 'text':
		$BL = '\n';
		$TAB = '\t';
		break;

	    case 'html':
	    default:
		$BL = '<br>';
		$TAB = '&nbsp;&nbsp;&nbsp;&nbsp;';
		break;
	}
	
	// проходим по каждому символу и делаем отступы
	for ($i = 0; $i < strlen($s); $i++) 
	{
	    
	    $next_operator = $this->next_operator(substr($s, $i + 1));
	    
	    switch ($s[$i]) {
		case '{':
		    $r .= $BL . str_repeat ($TAB, $level) . $s[$i] ;
		    
		    if ($next_operator == '|')
		    {
			$r .= $BL . str_repeat ($TAB, $level + 1);
		    }
		    
		    $level++;
		    break;
		
		case '}':
		    
		    $r .= $BL ;
//		    if ($next_operator != '{')
//		    {
//			
//		    }
		    if ($level != 0) $r .= str_repeat ($TAB, $level - 1);  
		    $r .= $s[$i];
		    $level--;
		    break;
		
		case '|':
		    if ($next_operator != '|' && $next_operator != '}')
		    {
			$r .= $BL . str_repeat ($TAB, $level);
		    }
		    $r .= $s[$i];
		    break;

		default:
		    $r .= $s[$i];
		    break;
	    }
	    
	}
	
	return $r;
    }
    
    /**
     * 
     * Находим следующий управляющий символ
     * 
     * @param string $str 
     * @param string $operator
     * @return string $operator
     * 
     */
    public function next_operator($str, $only_operator = '')
    {
	$result_pos = strlen($str);
	$result_operator = '';
	
	if ($only_operator)
	{
	    $result_pos = strpos($str, $only_operator);
	    $result_operator = $only_operator;
	}
	else
	{
	    foreach ($this->operators as $op) 
	    {
		$pos = strpos($str, $op);
		if (!$pos) $pos = strlen($str);
		
		if ($pos < $result_pos)
		{
		    $result_pos = $pos;
		    $result_operator = $op;
		}

	    }
	}
	
	if ($result_operator)
	    return $result_operator;
	else 
	    return false;
    }
}
?>
