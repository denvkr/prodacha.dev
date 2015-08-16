<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
/**
 * Родительский класс ядра
 * Примеры использования размещены в папке phpshop/core/
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopCore
 * @version 1.6
 * @package PHPShopClass
 */
class PHPShopCore {

    /**
     * имя БД
     * @var string 
     */
    var $objBase;

    /**
     * Путь для навигации
     * @var string
     */
    var $objPath;

    /**
     * режим отладки
     * @var bool 
     */
    var $debug = false;

    /**
     * результат работы парсера
     * @var string 
     */
    var $Disp, $ListInfoItems;

    /**
     * массив обработки POST, GET запросов
     * @var array 
     */
    var $action = array("nav" => "index");

    /**
     * префикс экшен функций (action_|a_), используется при большом количестве методов в классах
     * @var string 
     * 
     */
    var $action_prefix = null;

    /**
     * метатеги
     * @var string
     */
    var $title, $description, $keywords, $lastmodified;

    /**
     * ссылка в навигации от корня
     * @var string 
     */
    var $navigation_link, $navigation_array = null;

    /**
     * шаблон вывода
     * @var string 
     */
    var $template = 'templates.shop';

    /**
     * таблица массива навигации
     * @var string  
     */
    var $navigationBase = 'base.categories';
    var $arrayPath;

    /**
     * длина пагинации для форматирования длины строки
     * @var int 
     */
    var $nav_len = 10;
    var $cache = false;

    /**
     * очистка временных переменных шаблона 
     * @var bool 
     */
    var $garbage_enabled = false;

    /**
     * Конструктор
     */
    function PHPShopCore() {
        global $PHPShopSystem, $PHPShopNav, $PHPShopModules;

        if ($this->objBase)
            $this->PHPShopOrm = new PHPShopOrm($this->objBase);

        $this->PHPShopOrm->debug = $this->debug;
        $this->SysValue = &$GLOBALS['SysValue'];
        $this->PHPShopSystem = $PHPShopSystem;
        $this->PHPShopOrm->cache = $this->cache;
        $this->num_row = $this->PHPShopSystem->getParam('num_row');
        $this->PHPShopNav = $PHPShopNav;
        $this->PHPShopModules = &$PHPShopModules;
        $this->page = $this->PHPShopNav->getId();
        
        if (strlen($this->page) == 0)
            $this->page = 1;

        // Определяем переменные
        $this->set('pageProduct', $this->SysValue['license']['product_name']);
    }

    /**
     * Сравнение параметра из массива
     * @param string $paramName имя переменной
     * @param string $paramValue значение переменной
     * @return bool
     */
    function ifValue($paramName, $paramValue = false) {
        if (empty($paramValue))
            $paramValue = 1;
        if ($this->objRow[$paramName] == $paramValue)
            return true;
    }

    /**
     * Расчет навигации хлебных крошек
     * @param int $id ИД позиции
     * @return array
     */
    function getNavigationPath($id) {
        $PHPShopOrm = new PHPShopOrm($this->getValue($this->navigationBase));
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->cache = $this->cache;


		//seo~mah		
		//$need2hide=array('14','44','48');
		// AND (!in_array($id,$need2hide))
        if  ( !empty($id) )
		 {
            $PHPShopOrm->comment="Навигация";
            $v=$PHPShopOrm->select(array('name,id,parent_to'),array('id'=>'='.$id),false,array('limit'=>1));
            if(is_array($v)) {
                $this->navigation_array[]=array('id'=>$v['id'],'name'=>$v['name'],'parent_to'=>$v['parent_to']);
                if(!empty($v['parent_to']))
                    $this->getNavigationPath($v['parent_to']);
            }
        }
    }

    /**
     * Навигация хлебных крошек
     * @param int $id текущий ИД родителя
     * @param string $name имя раздела
     */
    function navigation($id, $name) {
        $dis = null;
        
        // Шаблоны разделителя навигации
        $spliter = ParseTemplateReturn($this->getValue('templates.breadcrumbs_splitter'));
        $home = ParseTemplateReturn($this->getValue('templates.breadcrumbs_home'));

        // Если нет шаблона разделителей
        if (empty($spliter))
            $spliter = ' / ';
        if (empty($home))
            $home = PHPShopText::a('/', __('Главная'));

        // Реверсивное построение массива категорий
        $this->getNavigationPath($id);

        if (is_array($this->navigation_array))
            $arrayPath = array_reverse($this->navigation_array);

        if (!empty($arrayPath) and is_array($arrayPath)) {
			$cnt=0;
            foreach ($arrayPath as $v) {
				if ($cnt==1 || $cnt==2) {
					if (stripos( $this->navigation_array[0]['name'] , '(' )!==false) {
						$v['name'] = trim(substr ( $v['name'] ,0, stripos( $v['name'] , '(' )-1 ));						
					}
				}
				$v['name']=preg_replace('/^(.*)stihl(.*)$/i','$1Штиль$3',$v['name']);
				$v['name']=preg_replace('/^(.*)viking(.*)$/i','$1Викинг$3',$v['name']);
				$v['name']=preg_replace('/^(.*)karcher(.*)$/i','$1Керхер$3',$v['name']);
				$href_text='<span itemprop="title">'.$v['name'].'</span>';
				$dis.= $spliter.'<div itemscope itemtype="http:data-vocabulary.orgBreadcrumb"><a href="/' . $this->PHPShopNav->getPath() . '/CID_' . $v['id'] . '.html" itemprop="url">'.$href_text.'</a></div>'; //. '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/' . $this->PHPShopNav->getPath() . '/CID_' . $v['id'] . '.html" itemprop="url">'.$href_text.'</a></div>'; //PHPShopText::a('/' . $this->PHPShopNav->getPath() . '/CID_' . $v['id'] . '.html', $href_text)
				$cnt++;
            }
        }
		//print_r($arrayPath);

		//print_r($GLOBALS['SysValue']);
		//echo $arrayPath[2].' '.isset($arrayPath[2]);
		/*
		if (is_array($this->navigation_array[0])) {
			if (stripos( $this->navigation_array[0]['name'] , '(' )!==false) {
				$this->navigation_array[0]['name'] = trim(substr ( $this->navigation_array[0]['name'] ,0, stripos( $this->navigation_array[0]['name'] , '(' )-1 ));		
			}
			//print_r($this->navigation_array[0]);
		}
		*/
		//$search_array=array('(',')');
		//str_replace($search_array,'',PHPShopText::b($name))
		//print_r($GLOBALS['SysValue']['nav']);
		
		if (($cnt==1 ||$cnt==2 || $cnt==3) && $GLOBALS['SysValue']['nav']['nav']!='UID') {
			if (stripos( $name , '(' )!==false) {
				$href_text='<span itemprop="title">'.trim(substr ( $name ,0, stripos($name , '(' )-1 )).'</span>';
				if ( (($GLOBALS['SysValue']['other']['productPageThis']=='ALL' || $GLOBALS['SysValue']['other']['productPageThis']!=1)  && $GLOBALS['SysValue']['other']['productPageThis']!='' ) || (($this->PHPShopNav->getPage()=='ALL' || $this->PHPShopNav->getPage()!=1) && $this->PHPShopNav->getPage()!='') ) {
					//$dis = $home . $dis . $spliter . PHPShopText::a('/' . $this->PHPShopNav->getPath() . '/CID_' . $GLOBALS['SysValue']['nav']['id'] . '.html', trim(substr ( $name ,0, stripos($name , '(' )-1 )));
					$dis = $home . $dis . $spliter .'<div itemscope itemtype="http:data-vocabulary.orgBreadcrumb"><a href="/' . $this->PHPShopNav->getPath() . '/CID_' . $GLOBALS['SysValue']['nav']['id'] . '.html" itemprop="url">'.$href_text.'</a></div>';
				} else
					$dis = $home . $dis . $spliter .'<div itemscope itemtype="http:data-vocabulary.orgBreadcrumb"><a href="/' . $this->PHPShopNav->getPath() . '/CID_' . $GLOBALS['SysValue']['nav']['id'] . '.html" itemprop="url">'.$href_text.'</a></div>';					
					//$dis = $home . $dis . $spliter . PHPShopText::b(trim(substr ( $name ,0, stripos($name , '(' )-1 )));
			} else {
				$href_text='<span itemprop="title">'.$name.'</span>';
				if ((($GLOBALS['SysValue']['other']['productPageThis']=='ALL' || $GLOBALS['SysValue']['other']['productPageThis']!=1)  && $GLOBALS['SysValue']['other']['productPageThis']!='' ) || (($this->PHPShopNav->getPage()=='ALL' || $this->PHPShopNav->getPage()!=1) && $this->PHPShopNav->getPage()!='')) {
					//$dis = $home . $dis . $spliter . PHPShopText::a('/' . $this->PHPShopNav->getPath() . '/CID_' . $GLOBALS['SysValue']['nav']['id'] . '.html', $name);
					$dis = $home . $dis . $spliter .'<div itemscope itemtype="http:data-vocabulary.orgBreadcrumb"><a href="/' . $this->PHPShopNav->getPath() . '/CID_' . $GLOBALS['SysValue']['nav']['id'] . '.html" itemprop="url">'.$href_text.'</a></div>';
				} else
					$dis = $home . $dis . $spliter .'<div itemscope itemtype="http:data-vocabulary.orgBreadcrumb"><a href="/' . $this->PHPShopNav->getPath() . '/CID_' . $GLOBALS['SysValue']['nav']['id'] . '.html" itemprop="url">'.$href_text.'</a></div>';					
					//$dis = $home . $dis . $spliter . PHPShopText::b($name);
			}
		} else {
			$href_text='<span itemprop="title">'.$name.'</span>';
			if ((($GLOBALS['SysValue']['other']['productPageThis']=='ALL' || $GLOBALS['SysValue']['other']['productPageThis']!=1)  && $GLOBALS['SysValue']['other']['productPageThis']!='' ) || (($this->PHPShopNav->getPage()=='ALL' || $this->PHPShopNav->getPage()!=1) && $this->PHPShopNav->getPage()!='')) {
				//$dis = $home . $dis . $spliter . PHPShopText::a('/' . $this->PHPShopNav->getPath() . '/CID_' . $GLOBALS['SysValue']['nav']['id'] . '.html', $name);
				$dis = $home . $dis . $spliter .'<div itemscope itemtype="http:data-vocabulary.orgBreadcrumb"><a href="/' . $this->PHPShopNav->getPath() . '/CID_' . $GLOBALS['SysValue']['nav']['id'] . '.html" itemprop="url">'.$href_text.'</a></div>';
			} else
				$dis = $home . $dis . $spliter .'<div itemscope itemtype="http:data-vocabulary.orgBreadcrumb"><a href="/' . $this->PHPShopNav->getPath() . '/CID_' . $GLOBALS['SysValue']['nav']['id'] . '.html" itemprop="url">'.$href_text.'</a></div>';
				//$dis = $home . $dis . $spliter . PHPShopText::b($name);
		}
		
		//echo $cnt;
		//print_r ($GLOBALS['SysValue']['other']['productPageThis']);
		//print_r($dis);
		
        $this->set('breadCrumbs', $dis);

        // Навигация для javascript в shop.tpl
        $this->set('pageNameId', $id);

    }

    /**
     * Генерация даты изменения документа
     */
    function header() {
        if ($this->getValue("cache.last_modified") == "true") {

            // Некоторые сервера требуют обзательных заголовков 200
            //header("HTTP/1.1 200");
            //header("Status: 200");
            @header("Cache-Control: no-cache, must-revalidate");
            @header("Pragma: no-cache");

            if (!empty($this->lastmodified)) {
                $updateDate = @gmdate("D, d M Y H:i:s", $this->lastmodified);
            } else {
                $updateDate = gmdate("D, d M Y H:i:s", (date("U") - 21600));
            }

            @header("Last-Modified: " . $updateDate . " GMT");
        }
    }

    /**
     * Генерация заголовков документа
     */
    function meta() {

    	//проверяем для UID
    	//в случае неадекватного номера страницы отправляем ее в стандартную страницу
    	if ($this->PHPShopNav->getName()=='UID') {
    		//анализируем часть url после второго подчеркивания если оно есть и смотрим что если подчеркиваний больше это сразу код 301
    		$first_found=stripos($this->PHPShopNav->getUrl(),'_');
    		$last_found=strripos($this->PHPShopNav->getUrl(),'_');
    		//вырезаем часть строки после последнего _
    		if ( $first_found!=$last_found ) {
    	
    			//анализируем если между этими символами есть еще один или несколько _ то это в 301
    			$url_part=substr($this->PHPShopNav->getUrl(),$first_found+1,($last_found-$first_found-1));
    			If (stripos($url_part,'_')==true) {
    				header("HTTP/1.1 301 Moved Permanently");
    				header("Location:".$this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html');
    				//echo '1';
    			}
    	
    			//если в строке нет .html то это 301
    			$url_part=stripos($this->PHPShopNav->getUrl(),'.html');
    			if ($url_part==false) {
    				header("HTTP/1.1 301 Moved Permanently");
    				header("Location:".$this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html');
    				//echo '2';
    			}
    	
    			//выясняем что сожержит строка после второго _
    			$first_found=strripos($this->PHPShopNav->getUrl(),'_');
    			$last_found=stripos($this->PHPShopNav->getUrl(),'.html');
    			$url_part=substr($this->PHPShopNav->getUrl(),$first_found+1,($last_found-$first_found-1));
    	
    			//echo $url_part.' ';
    			//если там буквы с цифрами или еще чемто то сразу отправляем в 301
    			if ($url_part!='ALL') {
    				if (strlen((int) $url_part)!=strlen($url_part)) {
    					header("HTTP/1.1 301 Moved Permanently");
    					header("Location:".$this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html');
    					//echo '3';
    				}
    			}
    	
    			//теперь остается проверить соответствует ли номеру страницы наша подстрока
    			//echo $this->page;
    			//print_r($this->PHPShopNav);
    			//echo $this->num_of_pages;
    			if ($url_part!='ALL') {
    				if ($url_part-$this->num_of_pages>0) {
    					header("HTTP/1.1 301 Moved Permanently");
    					header("Location:".$this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html');
    					//echo '4';
    				}
    			}
    			//если это url с одним "_"
    		} else {
    			//echo '5';
    			//выясняем что сожержит строка после _
    			$first_found=strripos($this->PHPShopNav->getUrl(),'_');
    			$last_found=stripos($this->PHPShopNav->getUrl(),'.html');
    			$url_part=substr($this->PHPShopNav->getUrl(),$first_found+1,($last_found-$first_found-1));
    			//echo $this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html';
    			//если там буквы с цифрами или еще чемто то сразу отправляем в 301
    			if ($url_part!='ALL') {
    				if (strlen((int) $url_part)!=strlen($url_part) || strlen((int) $url_part)>4) {
    					header("HTTP/1.1 301 Moved Permanently");
    					header("Location:".$this->curPageURL());
    					//echo '5';
    				}
    			}
    		}
    	}
    	 
		//$this->title='Садовая техника купить, интернет магазин садовой техники в ';
		if ($this->get('catalogName')!='') {
			$catname=" id='".$this->get('productId')."'"; //$this->get('catalogName')
		}
		if ($this->get('catalogCategory')!='') {
			$catname=" name='".$this->get('catalogCategory')."'";
		}	

        if (!empty($this->title)) {
		
			/* установка региональности
			$city='';
			
			if ($_COOKIE['sincity']=="sp") {
				$city='Санкт-Петербурге PROДАЧА';
			} else if ($_COOKIE['sincity']=="chb") {
				$city='Чебоксарах PROДАЧА';
			} else if ($_COOKIE['sincity']=="m") {
				$city='Москве PROДАЧА';
			}
			
			$this->title=$this->title.$city;
			*/
			//выборка установленных title для конкретного каталога
			if ($catname!='') {

				if ($GLOBALS['SysValue']['nav']['name']=='UID') {
					$this->PHPShopOrm->sql = 'select title_shablon from ' .$this->SysValue['base']['products']. " where id=" . $GLOBALS['SysValue']['nav']['id'];
				} else {
					$this->PHPShopOrm->sql = 'select title_shablon from ' .$this->SysValue['base']['categories']. " where " . $catname;
				}

				$res=$this->PHPShopOrm->select();
				
				if (is_array($res)) {
					foreach ($res as $row_shablon) {
						$this->set('pageTitl',$row_shablon['title_shablon']);
					}
				} else {
					$this->set('pageTitl',$this->title);
				}
			} else {
				$this->set('pageTitl',$this->title);
			}
		}
        else {
			if ($catname!='') {

				if ($GLOBALS['SysValue']['nav']['name']=='UID') {
					$this->PHPShopOrm->sql = 'select title_shablon from ' .$this->SysValue['base']['products']. " where id=" . $GLOBALS['SysValue']['nav']['id'];
				} else {			
					$this->PHPShopOrm->sql = 'select title_shablon from ' .$this->SysValue['base']['categories']. " where " . $catname;
				}

				$res=$this->PHPShopOrm->select();
				
				if (is_array($res)) {
					foreach ($res as $row_shablon) {
						$this->set('pageTitl',$row_shablon['title_shablon']);
					}
				} else {
					$this->set('pageTitl', $this->PHPShopSystem->getValue("title"));
				}
			} else {
				$this->set('pageTitl', $this->PHPShopSystem->getValue("title"));
			}
		}
        if (!empty($this->description))
			//выборка установленных description для конкретного каталога
			if ($catname!='') {
				
				if ($GLOBALS['SysValue']['nav']['name']=='UID') {
					$this->PHPShopOrm->sql = 'select descrip_shablon from ' .$this->SysValue['base']['products']. " where id=" . $GLOBALS['SysValue']['nav']['id'];
				} else {			
					$this->PHPShopOrm->sql = 'select descrip_shablon from ' .$this->SysValue['base']['categories']. " where " . $catname;
				}
				$res=$this->PHPShopOrm->select();
				
				if (is_array($res)) {
					foreach ($res as $row_shablon) {
						$this->set('pageDesc',$row_shablon['descrip_shablon']);
					}
				} else {
					$this->set('pageDesc',$this->description);
				}
			} else {
				$this->set('pageDesc',$this->description);
			}
        else {
			if ($catname!='') {
				if ($GLOBALS['SysValue']['nav']['name']=='UID') {
					$this->PHPShopOrm->sql = 'select descrip_shablon from ' .$this->SysValue['base']['products']. " where id=" . $GLOBALS['SysValue']['nav']['id'];
				} else {			
					$this->PHPShopOrm->sql = 'select descrip_shablon from ' .$this->SysValue['base']['categories']. " where " . $catname;
				}
				$res=$this->PHPShopOrm->select();
				
				if (is_array($res)) {
					foreach ($res as $row_shablon) {
						$this->set('pageDesc',$row_shablon['descrip_shablon']);
					}
				} else {
					$this->set('pageDesc', $this->PHPShopSystem->getValue("descrip"));
				}
			} else {
				$this->set('pageDesc', $this->PHPShopSystem->getValue("descrip"));
			}
		}
        if (!empty($this->keywords))
			//выборка установленных description для конкретного каталога
			if ($catname!='') {
				if ($GLOBALS['SysValue']['nav']['name']=='UID') {
					$this->PHPShopOrm->sql = 'select keywords_shablon from ' .$this->SysValue['base']['products']. " where id=" . $GLOBALS['SysValue']['nav']['id'];
				} else {			
					$this->PHPShopOrm->sql = 'select keywords_shablon from ' .$this->SysValue['base']['categories']. " where " . $catname;
				}
				$res=$this->PHPShopOrm->select();
				
				if (is_array($res)) {
					foreach ($res as $row_shablon) {
						$this->set('pageKeyw',$row_shablon['keywords_shablon']);
					}
				} else {
					$this->set('pageKeyw',$this->keywords);
				}
			} else {
				$this->set('pageKeyw',$this->keywords);
			}
        else {
			if ($catname!='') {
				if ($GLOBALS['SysValue']['nav']['name']=='UID') {			
					$this->PHPShopOrm->sql = 'select keywords_shablon from ' .$this->SysValue['base']['products']. " where id=" . $GLOBALS['SysValue']['nav']['id'];
				} else {			
					$this->PHPShopOrm->sql = 'select keywords_shablon from ' .$this->SysValue['base']['categories']. " where " . $catname;
				}
				$res=$this->PHPShopOrm->select();
				
				if (is_array($res)) {
					foreach ($res as $row_shablon) {
						$this->set('pageKeyw',$row_shablon['keywords_shablon']);
					}
				} else {
					$this->set('pageKeyw', $this->PHPShopSystem->getValue("keywords"));
				}
			} else {
				$this->set('pageKeyw', $this->PHPShopSystem->getValue("keywords"));
			} 
		}
    }

    /**
     * Загрузка экшенов
     */
    function loadActions() {
        $this->setAction();
        $this->Compile();
    }

    /**
     * Выдача списка данных
     * @param array $select имена колонок БД для выборки
     * @param array $where параметры условий запроса
     * @param array $order параметры сортировки данных при выдаче
     * @return array
     */
    function getListInfoItem($select = false, $where = false, $order = false, $class_name = false, $function_name = false, $sql = false) {
        $this->ListInfoItems = null;
        $this->where = $where;

        // Обработка номера страницы
        if (!PHPShopSecurity::true_num($this->page) and strtoupper($this->page) != 'ALL')
            return $this->setError404();

        if (empty($this->page)) {
            $num_ot = 0;
            $num_do = $this->num_row;
        } else {
            $num_ot = $this->num_row * ($this->page - 1);
            $num_do = $this->num_row;
        }

        // Вывод всех страниц
        if (strtoupper($this->page) == 'ALL') {
            $num_ot = 0;
            $num_do = $this->max_item;
        }


        $option = array('limit' => $num_ot . ',' . $num_do);

        $this->set('productFound', $this->getValue('lang.found_of_products'));
        $this->set('productNumOnPage', $this->getValue('lang.row_on_page'));
        $this->set('productPage', $this->getValue('lang.page_now'));

        $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;

        if (!empty($sql)) {
            $this->PHPShopOrm->sql = 'select * from ' . $this->objBase . ' where ' . $sql . ' limit ' . $option['limit'];
        }

        return $this->PHPShopOrm->select($select, $where, $order, $option, $class_name, $function_name);
    }

    /**
     * Генерация пагинатора
     */
    function setPaginator() {

        $SQL = null;
        // Выборка по параметрам WHERE
        $nWhere = 1;
        if (is_array($this->where)) {
            $SQL.=' where ';
            foreach ($this->where as $pole => $value) {
                $SQL.=$pole . $value;
                if ($nWhere < count($this->where))
                    $SQL.=$this->PHPShopOrm->Option['where'];
                $nWhere++;
            }
        }

        // Кол-во страниц
        $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
        $result = $this->PHPShopOrm->query("select COUNT('id') as count from " . $this->objBase . $SQL);
        $row = mysql_fetch_array($result);
        $this->num_page = $row['count'];
        $i = 1;
        $navigat = null;

        // Кол-во страниц в навигации
        $num = ceil($this->num_page / $this->num_row);

        while ($i <= $num) {

            if ($i > 1) {
                $p_start = $this->num_row * ($i - 1);
                $p_end = $p_start + $this->num_row;
            } else {
                $p_start = $i;
                $p_end = $this->num_row;
            }
            if ($i != $this->page) {
                if ($i > ($this->page - $this->nav_len) and $i < ($this->page + $this->nav_len))
                    $navigat.=PHPShopText::a($this->objPath . $i . '.html', $p_start . '-' . $p_end) . ' / ';
                else if ($i - ($this->page + $this->nav_len) < 3 and (($this->page - $this->nav_len) - $i) < 3)
                    $navigat.=".";
            }
            else
                $navigat.=PHPShopText::b($p_start . '-' . $p_end . ' / ');
            $i++;
        }

        // Расчет навигации вперед и назад
        if ($num > 1) {
            if ($this->page >= $num) {
                $p_to = $i - 1;
                $p_do = $this->page - 1;
            } else {
                $p_to = $this->page + 1;
                $p_do = 1;
            }

            $nav = $this->getValue('lang.page_now') . ': ';
            $nav.=PHPShopText::a($this->objPath . ($p_do) . '.html', '&laquo;&laquo;&nbsp;', '&laquo; ' . $this->lang('nav_back'));
            $nav.=' / ' . $navigat . '&nbsp';
            $nav.=PHPShopText::a($this->objPath . ($p_to) . '.html', '&raquo;&raquo;&nbsp;', $this->lang('nav_forw') . ' &raquo;');
            $this->set('productPageNav', $nav);
        }
    }

    /**
     * Выдача подробного описания
     * @param array $select имена колонок БД для выборки
     * @param array $where параметры условий запроса
     * @param array $order параметры сортировки данных при выдаче
     * @return array
     */
    function getFullInfoItem($select, $where, $class_name = false, $function_name = false) {
        $result = $this->PHPShopOrm->select($select, $where, false, array('limit' => '1'), $class_name, $function_name);
        return $result;
    }

    /**
     * Добавление данных в вывод парсера
     * @param string $template шаблон для парсинга
     * @param bool $mod работа в модуле
     */
    function addToTemplate($template, $mod = false) {
        if ($mod)
            $template_file = $template;
        else
            $template_file = $this->getValue('dir.templates') . chr(47) . $_SESSION['skin'] . chr(47) . $template;
        if (is_file($template_file)) {
            $this->ListInfoItems.=ParseTemplateReturn($template, $mod);
            $this->set('pageContent', $this->ListInfoItems);
        }else
            $this->setError("addToTemplate", $template_file);
    }

    /**
     * Добавление данных
     * @param string $content содержание
     * @param bool $list [1] - добавление в список данных, [0] - добавление в общую переменную вывода
     */
    function add($content, $list = false) {
        if ($list)
            $this->ListInfoItems.=$content;
        else
            $this->Disp.=$content;
    }

    /**
     * Парсинг шаблона и добавление в общую переменную вывода
     * @param string $template имя шаблона
     * @param bool $mod работа в модуле
     */
    function parseTemplate($template, $mod = false) {
        $this->set('productPageDis', $this->ListInfoItems);
        $this->Disp = ParseTemplateReturn($template, $mod);
    }

    /**
     * Сообщение об ошибке
     * @param string $name имя функции
     * @param string $action сообщение
     */
    function setError($name, $action) {
        echo '<p style="BORDER: #000000 1px dashed;padding-top:10px;padding-bottom:10px;background-color:#FFFFFF;color:000000;font-size:12px">
<img hspace="10" style="padding-left:10px" align="left" src="../phpshop/admpanel/img/i_domainmanager_med[1].gif"
width="32" height="32" alt="PHPShopCore Debug On"/ ><strong>Ошибка обработчика события:</strong> ' . $name . '()
	 <br><em>' . $action . '</em></p>';
    }

    /**
     * Компиляция парсинга
     */
    function Compile() {
        global $PHPShopDebug;

        // Переменная вывода
        $this->set('DispShop', $this->Disp, false, true);

        // Мета
        $this->meta();

        // Дата модификации
        $this->header();

        // Запись файла локализации
        writeLangFile();

        // Вывод в шаблон
        ParseTemplate($this->getValue($this->template));

        // Очистка временных переменных шаблонов
        $this->garbage();
    }

    /**
     * Создание переменной шаблонизатора для парсинга
     * @param string $name имя
     * @param mixed $value значение
     * @param bool $flag [1] - добавить, [0] - переписать
     */
    function set($name, $value, $flag = false) {
        if ($flag)
            $this->SysValue['other'][$name].=$value;
        else
            $this->SysValue['other'][$name] = $value;
    }

    /**
     * Выдача переменной шаблонизатора
     * @param string $name
     * @return string
     */
    function get($name) {
        return $this->SysValue['other'][$name];
    }

    /**
     * Выдача системной переменной
     * @param string $param раздел.имя переменной
     * @return mixed
     */
    function getValue($param) {
        $param = explode(".", $param);

        if (count($param) > 2 and !empty($this->SysValue[$param[0]][$param[1]][$param[2]]))
            return $this->SysValue[$param[0]][$param[1]][$param[2]];

        if (!empty($this->SysValue[$param[0]][$param[1]]))
            return $this->SysValue[$param[0]][$param[1]];
    }

    /**
     * Назначение экшена обработки переменных POST и GET
     */
    function setAction() {

        if (is_array($this->action)) {
            foreach ($this->action as $k => $v) {

                switch ($k) {

                    // Экшен POST
                    case("post"):

                        // Если несколько экшенов
                        if (is_array($v)) {
                            foreach ($v as $function)
                                if (!empty($_POST[$function]) and $this->isAction($function))
                                    return call_user_func(array(&$this, $this->action_prefix . $function));
                        } else {
                            // Если один экшен
                            if (!empty($_POST[$v]) and $this->isAction($v))
                                return call_user_func(array(&$this, $this->action_prefix . $v));
                        }
                        break;

                    // Экшен GET
                    case("get"):

                        // Если несколько экшенов
                        if (is_array($v)) {
                            foreach ($v as $function)
                                if (!empty($_GET[$function]) and $this->isAction($function))
                                    return call_user_func(array(&$this, $this->action_prefix . $function));
                        } else {
                            // Если один экшен
                            if (!empty($_GET[$v]) and $this->isAction($v))
                                return call_user_func(array(&$this, $this->action_prefix . $v));
                        }

                        break;

                    // Экшен NAME
                    case("name"):

                        // Если несколько экшенов
                        if (is_array($v)) {
                            foreach ($v as $function)
                                if ($this->PHPShopNav->getName() == $function and $this->isAction($function))
                                    return call_user_func(array(&$this, $this->action_prefix . $function));
                        } else {
                            // Если один экшен
                            if ($this->PHPShopNav->getName() == $v and $this->isAction($v))
                                return call_user_func(array(&$this, $this->action_prefix . $v));
                        }

                        break;


                    // Экшен NAV
                    case("nav"):

                        // Если несколько экшенов
                        if (is_array($v)) {
                            foreach ($v as $function) {
                                if ($this->PHPShopNav->getNav() == $function and $this->isAction($function)) {
                                    return call_user_func(array(&$this, $this->action_prefix . $function));
                                    $call_user_func = true;
                                }
                            }
                            if (empty($call_user_func)) {
                                if ($this->isAction('index'))
                                    call_user_func(array(&$this, $this->action_prefix . 'index'));
                                else
                                    $this->setError($this->action_prefix . "index", "метод не существует");
                            }
                        } else {
                            // Если один экшен
                            if ($this->PHPShopNav->getNav() == $v and $this->isAction($v))
                                return call_user_func(array(&$this, $this->action_prefix . $v));
                            elseif ($this->isAction('index'))
                                call_user_func(array(&$this, $this->action_prefix . 'index'));
                            else
                                $this->setError($this->action_prefix . "phpshop" . $this->PHPShopNav->getPath() . "->index", "метод не существует");
                        }

                        break;
                }
            }
        }else
            $this->setError("action", "экшены объявлена неверно");
    }

    /**
     * Проверка экшена
     * @param string $method_name имя метода
     * @return bool
     */
    function isAction($method_name) {
        if (method_exists($this, $this->action_prefix . $method_name))
            return true;
    }

    /**
     * Ожидание экшена
     * @param string $method_name  имя метода
     */
    function waitAction($method_name) {
        if (!empty($_REQUEST[$method_name]) and $this->isAction($method_name))
            call_user_func(array(&$this, $this->action_prefix . $method_name));
    }

    /**
     * Генерация ошибки 404
     */
    function setError404() {

        // Титл
        $this->title = "Ошибка 404  - " . $this->PHPShopSystem->getValue("name");

        // Заголовок ошибки
        header("HTTP/1.0 404 Not Found");
        header("Status: 404 Not Found");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.error_page_forma'));
    }

    /**
     * Подключение функций из файлов ядра
     * @param string $class_name имя класса
     * @param string $function_name имя функции
     * @param array $function_row массив дополнительны данных из функции
     * @param string $path имя раздела
     * @return mixed
     */
    function doLoadFunction($class_name, $function_name, $function_row = false, $path = false) {

        if (empty($path))
            $path = $GLOBALS['SysValue']['nav']['path'];

        $function_path = './phpshop/core/' . $path . '.core/' . $function_name . '.php';
        if (is_file($function_path)) {
            include_once($function_path);
            if (function_exists($function_name)) {
                return call_user_func_array($function_name, array(&$this, $function_row));
            }
        }
    }

    /**
     * Вывод языкового параметра по ключу [config.ini]
     * @param string $str ключ языкового массива
     * @return string
     */
    function lang($str) {
        if ($this->SysValue['lang'][$str])
            return $this->SysValue['lang'][$str];
        else
            return 'Не определено';
    }

    /**
     * Запись в память
     * @param string $param имя параметра [catalog.param]
     * @param mixed $value значение
     */
    function memory_set($param, $value) {
        if (!empty($this->memory)) {
            $param = explode(".", $param);
            $_SESSION['Memory'][__CLASS__][$param[0]][$param[1]] = $value;
            $_SESSION['Memory'][__CLASS__]['time'] = time();
        }
    }

    /**
     * Выборка из памяти
     * @param string $param имя параметра [catalog.param]
     * @param bool $check сравнить с нулем
     * @return
     */
    function memory_get($param, $check = false) {
        $this->memory_clean();
        if (!empty($this->memory)) {
            $param = explode(".", $param);
            if (isset($_SESSION['Memory'][__CLASS__][$param[0]][$param[1]])) {
                if (!empty($check)) {
                    if (!empty($_SESSION['Memory'][__CLASS__][$param[0]][$param[1]]))
                        return true;
                }
                else
                    return $_SESSION['Memory'][__CLASS__][$param[0]][$param[1]];
            }
            elseif (!empty($check))
                return true;
        }
        else
            return true;
    }

    /**
     * Чистка памяти по времени
     * @param bool $clean_now принудительная чистка
     */
    function memory_clean($clean_now = false) {
        if (!empty($_SESSION['Memory'])) {
            if (!empty($clean_now))
                unset($_SESSION['Memory'][__CLASS__]);
            elseif (@$_SESSION['Memory'][__CLASS__]['time'] < (time() - 60 * 10))
                unset($_SESSION['Memory'][__CLASS__]);
        }
    }

    /**
     * Назначение перехвата события выполнения модулем
     * @param string $class_name имя класса
     * @param string $function_name имя метода
     * @param mixed $data данные для обработки
     * @param string $rout позиция вызова к функции [END | START | MIDDLE], по умолчанию END
     * @return bool
     */
    function setHook($class_name, $function_name, $data = false, $rout = false) {
        return $this->PHPShopModules->setHookHandler($class_name, $function_name, array(&$this), $data, $rout);
    }

    /**
     * Сообщение
     * @param string $title заголовок
     * @param string $content содержание
     * @return string
     */
    function message($title, $content) {
        $message = PHPShopText::b(PHPShopText::notice($title, false, '14px')) . PHPShopText::br();
        $message.=PHPShopText::message($content, false, '12px', 'black');
        return $message;
    }

    /**
     * Очистка временных переменных
     */
    function garbage() {
        if ($this->garbage_enabled) {
            timer('start', 'Garbage');
            unset($this->SysValue['other']);
            timer('end', 'Garbage');
        }
    }

    function select_depend_region($cookie_region) {
    	$retval=array();
    	if ( $cookie_region=="sp" ) {
    		$retval[0]="where city like '%'";//city='Санкт-Петербург'
    	} elseif($cookie_region=="chb") {
    		$retval[0]="where city like '%'"; //city='Чебоксары'
    	} elseif($cookie_region=="m") {
    		$retval[0]="where city like '%'"; //city='Москва'
    	} elseif($cookie_region=="other" || empty($cookie_region)) {
    		$retval[0]="where city like '%'";
    	}
    	return $retval;
    }
    
    function check_service_existing_by_region($cookie_region,$brand='') {
    	//1.Проверяем если сервис в данном городе
    	$retval=array();
    	$select[0]="city";
    	$where[0]=$brand;
    	$has_service_in_region=false;
    
    	switch($cookie_region){
    		case "sp":
    			$sql="SELECT brand from ".$GLOBALS['SysValue']['base']['service_and_varranty']." where city='Санкт-Петербург' and brand='".$brand."'";
    			$res=mysql_query($sql);
    			//$result=$this->select($select, $where,false,'',false,$GLOBALS['SysValue']['base']['service_and_varranty']);
    			if ( mysql_num_rows($res)>0 ) {
    				$has_service_in_region=true;
    			}
    			break;
    		case 'chb':
    			$sql="SELECT brand from ".$GLOBALS['SysValue']['base']['service_and_varranty']." where city='Чебоксары' and brand='".$brand."'";
    			$res=mysql_query($sql);
    			//$where[1]='Чебоксары';
    			//$result=$this->select($select, $where,false,'',false,$GLOBALS['SysValue']['base']['service_and_varranty']);
    			if ( mysql_num_rows($res)>0 ) {
    				$has_service_in_region=true;
    			}
    			break;
    		case 'm':
    			$sql="SELECT brand from ".$GLOBALS['SysValue']['base']['service_and_varranty']." where city='Москва' and brand='".$brand."'";
    			$res=mysql_query($sql);
    			//$where[1]='Москва';
    			//$result=$this->select($select, $where,false,'',false,$GLOBALS['SysValue']['base']['service_and_varranty']);
    			if ( mysql_num_rows($res)>0 ) {
    				$has_service_in_region=true;
    			}
    			break;
    	}
    
    	//2.Проверяем есть ли сервисы в других регионах
    	if ($has_service_in_region==false) {
    		$retval=array('service_existing_in_moscow' => 0);
    		$retval=array('service_existing_in_few_region' => 0);
    		$retval=array('service_existing_in_current_region' => 0);
    		$sql="SELECT city from ".$GLOBALS['SysValue']['base']['service_and_varranty']." where brand='".$brand."'";
    		$res=mysql_query($sql);
    		if ( mysql_num_rows($res)>0 ) {
    			while ($row=mysql_fetch_assoc($res)) {
    				//3.Если сервис присутствует в других регионах в том числе и в Москве то выводим все сервисы и выбираем москву
    				//если в Москве нет сервисного центра то не выбираем конкретный город
    				if ( $row['city']=='Москва' ) {
    					$retval['service_existing_in_moscow']=1;
    				} else {
    					$retval['service_existing_in_few_region']=1;
    				}
    			}
    		}
    
    		//$result=$this->select($select, $where,false,'',false,$GLOBALS['SysValue']['base']['service_and_varranty']);
    	} else {
    		$retval=array('service_existing_in_moscow' => 0);
    		$retval=array('service_existing_in_few_region' => 0);
    		$retval=array('service_existing_in_current_region' => 1);
    		$sql="SELECT city from ".$GLOBALS['SysValue']['base']['service_and_varranty']." where brand='".$brand."'";
    		$res=mysql_query($sql);
    		if ( mysql_num_rows($res)>0 ) {
    			while ($row=mysql_fetch_assoc($res)) {
    				//3.Если сервис присутствует в других регионах в том числе и в Москве то выводим все сервисы и выбираем москву
    				//если в Москве нет сервисного центра то не выбираем конкретный город
    				if ( $row['city']=='Москва' ) {
    					$retval['service_existing_in_moscow']=1;
    				} else {
    					$retval['service_existing_in_few_region']=1;
    				}
    			}
    		}
    	}
    	return $retval;
    	//возвращаем ассоциативный массив $retval
    	//[service_existing_in_moscow]
    	//[service_existing_in_current_region]
    	//[service_existing_in_few_region]
    
    }
    
}

?>