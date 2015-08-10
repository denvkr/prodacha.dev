<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
/**
 * ������������ ����� ����
 * ������� ������������� ��������� � ����� phpshop/core/
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopCore
 * @version 1.6
 * @package PHPShopClass
 */
class PHPShopCore {

    /**
     * ��� ��
     * @var string 
     */
    var $objBase;

    /**
     * ���� ��� ���������
     * @var string
     */
    var $objPath;

    /**
     * ����� �������
     * @var bool 
     */
    var $debug = false;

    /**
     * ��������� ������ �������
     * @var string 
     */
    var $Disp, $ListInfoItems;

    /**
     * ������ ��������� POST, GET ��������
     * @var array 
     */
    var $action = array("nav" => "index");

    /**
     * ������� ����� ������� (action_|a_), ������������ ��� ������� ���������� ������� � �������
     * @var string 
     * 
     */
    var $action_prefix = null;

    /**
     * ��������
     * @var string
     */
    var $title, $description, $keywords, $lastmodified;

    /**
     * ������ � ��������� �� �����
     * @var string 
     */
    var $navigation_link, $navigation_array = null;

    /**
     * ������ ������
     * @var string 
     */
    var $template = 'templates.shop';

    /**
     * ������� ������� ���������
     * @var string  
     */
    var $navigationBase = 'base.categories';
    var $arrayPath;

    /**
     * ����� ��������� ��� �������������� ����� ������
     * @var int 
     */
    var $nav_len = 10;
    var $cache = false;

    /**
     * ������� ��������� ���������� ������� 
     * @var bool 
     */
    var $garbage_enabled = false;

    /**
     * �����������
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

        // ���������� ����������
        $this->set('pageProduct', $this->SysValue['license']['product_name']);
    }

    /**
     * ��������� ��������� �� �������
     * @param string $paramName ��� ����������
     * @param string $paramValue �������� ����������
     * @return bool
     */
    function ifValue($paramName, $paramValue = false) {
        if (empty($paramValue))
            $paramValue = 1;
        if ($this->objRow[$paramName] == $paramValue)
            return true;
    }

    /**
     * ������ ��������� ������� ������
     * @param int $id �� �������
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
            $PHPShopOrm->comment="���������";
            $v=$PHPShopOrm->select(array('name,id,parent_to'),array('id'=>'='.$id),false,array('limit'=>1));
            if(is_array($v)) {
                $this->navigation_array[]=array('id'=>$v['id'],'name'=>$v['name'],'parent_to'=>$v['parent_to']);
                if(!empty($v['parent_to']))
                    $this->getNavigationPath($v['parent_to']);
            }
        }
    }

    /**
     * ��������� ������� ������
     * @param int $id ������� �� ��������
     * @param string $name ��� �������
     */
    function navigation($id, $name) {
        $dis = null;
        
        // ������� ����������� ���������
        $spliter = ParseTemplateReturn($this->getValue('templates.breadcrumbs_splitter'));
        $home = ParseTemplateReturn($this->getValue('templates.breadcrumbs_home'));

        // ���� ��� ������� ������������
        if (empty($spliter))
            $spliter = ' / ';
        if (empty($home))
            $home = PHPShopText::a('/', __('�������'));

        // ����������� ���������� ������� ���������
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
				$v['name']=preg_replace('/^(.*)stihl(.*)$/i','$1�����$3',$v['name']);
				$v['name']=preg_replace('/^(.*)viking(.*)$/i','$1������$3',$v['name']);
				$v['name']=preg_replace('/^(.*)karcher(.*)$/i','$1������$3',$v['name']);
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

        // ��������� ��� javascript � shop.tpl
        $this->set('pageNameId', $id);

    }

    /**
     * ��������� ���� ��������� ���������
     */
    function header() {
        if ($this->getValue("cache.last_modified") == "true") {

            // ��������� ������� ������� ����������� ���������� 200
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
     * ��������� ���������� ���������
     */
    function meta() {

    	//��������� ��� UID
    	//� ������ ������������� ������ �������� ���������� �� � ����������� ��������
    	if ($this->PHPShopNav->getName()=='UID') {
    		//����������� ����� url ����� ������� ������������� ���� ��� ���� � ������� ��� ���� ������������� ������ ��� ����� ��� 301
    		$first_found=stripos($this->PHPShopNav->getUrl(),'_');
    		$last_found=strripos($this->PHPShopNav->getUrl(),'_');
    		//�������� ����� ������ ����� ���������� _
    		if ( $first_found!=$last_found ) {
    	
    			//����������� ���� ����� ����� ��������� ���� ��� ���� ��� ��������� _ �� ��� � 301
    			$url_part=substr($this->PHPShopNav->getUrl(),$first_found+1,($last_found-$first_found-1));
    			If (stripos($url_part,'_')==true) {
    				header("HTTP/1.1 301 Moved Permanently");
    				header("Location:".$this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html');
    				//echo '1';
    			}
    	
    			//���� � ������ ��� .html �� ��� 301
    			$url_part=stripos($this->PHPShopNav->getUrl(),'.html');
    			if ($url_part==false) {
    				header("HTTP/1.1 301 Moved Permanently");
    				header("Location:".$this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html');
    				//echo '2';
    			}
    	
    			//�������� ��� �������� ������ ����� ������� _
    			$first_found=strripos($this->PHPShopNav->getUrl(),'_');
    			$last_found=stripos($this->PHPShopNav->getUrl(),'.html');
    			$url_part=substr($this->PHPShopNav->getUrl(),$first_found+1,($last_found-$first_found-1));
    	
    			//echo $url_part.' ';
    			//���� ��� ����� � ������� ��� ��� ����� �� ����� ���������� � 301
    			if ($url_part!='ALL') {
    				if (strlen((int) $url_part)!=strlen($url_part)) {
    					header("HTTP/1.1 301 Moved Permanently");
    					header("Location:".$this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html');
    					//echo '3';
    				}
    			}
    	
    			//������ �������� ��������� ������������� �� ������ �������� ���� ���������
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
    			//���� ��� url � ����� "_"
    		} else {
    			//echo '5';
    			//�������� ��� �������� ������ ����� _
    			$first_found=strripos($this->PHPShopNav->getUrl(),'_');
    			$last_found=stripos($this->PHPShopNav->getUrl(),'.html');
    			$url_part=substr($this->PHPShopNav->getUrl(),$first_found+1,($last_found-$first_found-1));
    			//echo $this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html';
    			//���� ��� ����� � ������� ��� ��� ����� �� ����� ���������� � 301
    			if ($url_part!='ALL') {
    				if (strlen((int) $url_part)!=strlen($url_part) || strlen((int) $url_part)>4) {
    					header("HTTP/1.1 301 Moved Permanently");
    					header("Location:".$this->curPageURL());
    					//echo '5';
    				}
    			}
    		}
    	}
    	 
		//$this->title='������� ������� ������, �������� ������� ������� ������� � ';
		if ($this->get('catalogName')!='') {
			$catname=" id='".$this->get('productId')."'"; //$this->get('catalogName')
		}
		if ($this->get('catalogCategory')!='') {
			$catname=" name='".$this->get('catalogCategory')."'";
		}	

        if (!empty($this->title)) {
		
			/* ��������� ��������������
			$city='';
			
			if ($_COOKIE['sincity']=="sp") {
				$city='�����-���������� PRO����';
			} else if ($_COOKIE['sincity']=="chb") {
				$city='���������� PRO����';
			} else if ($_COOKIE['sincity']=="m") {
				$city='������ PRO����';
			}
			
			$this->title=$this->title.$city;
			*/
			//������� ������������� title ��� ����������� ��������
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
			//������� ������������� description ��� ����������� ��������
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
			//������� ������������� description ��� ����������� ��������
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
     * �������� �������
     */
    function loadActions() {
        $this->setAction();
        $this->Compile();
    }

    /**
     * ������ ������ ������
     * @param array $select ����� ������� �� ��� �������
     * @param array $where ��������� ������� �������
     * @param array $order ��������� ���������� ������ ��� ������
     * @return array
     */
    function getListInfoItem($select = false, $where = false, $order = false, $class_name = false, $function_name = false, $sql = false) {
        $this->ListInfoItems = null;
        $this->where = $where;

        // ��������� ������ ��������
        if (!PHPShopSecurity::true_num($this->page) and strtoupper($this->page) != 'ALL')
            return $this->setError404();

        if (empty($this->page)) {
            $num_ot = 0;
            $num_do = $this->num_row;
        } else {
            $num_ot = $this->num_row * ($this->page - 1);
            $num_do = $this->num_row;
        }

        // ����� ���� �������
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
     * ��������� ����������
     */
    function setPaginator() {

        $SQL = null;
        // ������� �� ���������� WHERE
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

        // ���-�� �������
        $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
        $result = $this->PHPShopOrm->query("select COUNT('id') as count from " . $this->objBase . $SQL);
        $row = mysql_fetch_array($result);
        $this->num_page = $row['count'];
        $i = 1;
        $navigat = null;

        // ���-�� ������� � ���������
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

        // ������ ��������� ������ � �����
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
     * ������ ���������� ��������
     * @param array $select ����� ������� �� ��� �������
     * @param array $where ��������� ������� �������
     * @param array $order ��������� ���������� ������ ��� ������
     * @return array
     */
    function getFullInfoItem($select, $where, $class_name = false, $function_name = false) {
        $result = $this->PHPShopOrm->select($select, $where, false, array('limit' => '1'), $class_name, $function_name);
        return $result;
    }

    /**
     * ���������� ������ � ����� �������
     * @param string $template ������ ��� ��������
     * @param bool $mod ������ � ������
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
     * ���������� ������
     * @param string $content ����������
     * @param bool $list [1] - ���������� � ������ ������, [0] - ���������� � ����� ���������� ������
     */
    function add($content, $list = false) {
        if ($list)
            $this->ListInfoItems.=$content;
        else
            $this->Disp.=$content;
    }

    /**
     * ������� ������� � ���������� � ����� ���������� ������
     * @param string $template ��� �������
     * @param bool $mod ������ � ������
     */
    function parseTemplate($template, $mod = false) {
        $this->set('productPageDis', $this->ListInfoItems);
        $this->Disp = ParseTemplateReturn($template, $mod);
    }

    /**
     * ��������� �� ������
     * @param string $name ��� �������
     * @param string $action ���������
     */
    function setError($name, $action) {
        echo '<p style="BORDER: #000000 1px dashed;padding-top:10px;padding-bottom:10px;background-color:#FFFFFF;color:000000;font-size:12px">
<img hspace="10" style="padding-left:10px" align="left" src="../phpshop/admpanel/img/i_domainmanager_med[1].gif"
width="32" height="32" alt="PHPShopCore Debug On"/ ><strong>������ ����������� �������:</strong> ' . $name . '()
	 <br><em>' . $action . '</em></p>';
    }

    /**
     * ���������� ��������
     */
    function Compile() {
        global $PHPShopDebug;

        // ���������� ������
        $this->set('DispShop', $this->Disp, false, true);

        // ����
        $this->meta();

        // ���� �����������
        $this->header();

        // ������ ����� �����������
        writeLangFile();

        // ����� � ������
        ParseTemplate($this->getValue($this->template));

        // ������� ��������� ���������� ��������
        $this->garbage();
    }

    /**
     * �������� ���������� ������������� ��� ��������
     * @param string $name ���
     * @param mixed $value ��������
     * @param bool $flag [1] - ��������, [0] - ����������
     */
    function set($name, $value, $flag = false) {
        if ($flag)
            $this->SysValue['other'][$name].=$value;
        else
            $this->SysValue['other'][$name] = $value;
    }

    /**
     * ������ ���������� �������������
     * @param string $name
     * @return string
     */
    function get($name) {
        return $this->SysValue['other'][$name];
    }

    /**
     * ������ ��������� ����������
     * @param string $param ������.��� ����������
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
     * ���������� ������ ��������� ���������� POST � GET
     */
    function setAction() {

        if (is_array($this->action)) {
            foreach ($this->action as $k => $v) {

                switch ($k) {

                    // ����� POST
                    case("post"):

                        // ���� ��������� �������
                        if (is_array($v)) {
                            foreach ($v as $function)
                                if (!empty($_POST[$function]) and $this->isAction($function))
                                    return call_user_func(array(&$this, $this->action_prefix . $function));
                        } else {
                            // ���� ���� �����
                            if (!empty($_POST[$v]) and $this->isAction($v))
                                return call_user_func(array(&$this, $this->action_prefix . $v));
                        }
                        break;

                    // ����� GET
                    case("get"):

                        // ���� ��������� �������
                        if (is_array($v)) {
                            foreach ($v as $function)
                                if (!empty($_GET[$function]) and $this->isAction($function))
                                    return call_user_func(array(&$this, $this->action_prefix . $function));
                        } else {
                            // ���� ���� �����
                            if (!empty($_GET[$v]) and $this->isAction($v))
                                return call_user_func(array(&$this, $this->action_prefix . $v));
                        }

                        break;

                    // ����� NAME
                    case("name"):

                        // ���� ��������� �������
                        if (is_array($v)) {
                            foreach ($v as $function)
                                if ($this->PHPShopNav->getName() == $function and $this->isAction($function))
                                    return call_user_func(array(&$this, $this->action_prefix . $function));
                        } else {
                            // ���� ���� �����
                            if ($this->PHPShopNav->getName() == $v and $this->isAction($v))
                                return call_user_func(array(&$this, $this->action_prefix . $v));
                        }

                        break;


                    // ����� NAV
                    case("nav"):

                        // ���� ��������� �������
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
                                    $this->setError($this->action_prefix . "index", "����� �� ����������");
                            }
                        } else {
                            // ���� ���� �����
                            if ($this->PHPShopNav->getNav() == $v and $this->isAction($v))
                                return call_user_func(array(&$this, $this->action_prefix . $v));
                            elseif ($this->isAction('index'))
                                call_user_func(array(&$this, $this->action_prefix . 'index'));
                            else
                                $this->setError($this->action_prefix . "phpshop" . $this->PHPShopNav->getPath() . "->index", "����� �� ����������");
                        }

                        break;
                }
            }
        }else
            $this->setError("action", "������ ��������� �������");
    }

    /**
     * �������� ������
     * @param string $method_name ��� ������
     * @return bool
     */
    function isAction($method_name) {
        if (method_exists($this, $this->action_prefix . $method_name))
            return true;
    }

    /**
     * �������� ������
     * @param string $method_name  ��� ������
     */
    function waitAction($method_name) {
        if (!empty($_REQUEST[$method_name]) and $this->isAction($method_name))
            call_user_func(array(&$this, $this->action_prefix . $method_name));
    }

    /**
     * ��������� ������ 404
     */
    function setError404() {

        // ����
        $this->title = "������ 404  - " . $this->PHPShopSystem->getValue("name");

        // ��������� ������
        header("HTTP/1.0 404 Not Found");
        header("Status: 404 Not Found");

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.error_page_forma'));
    }

    /**
     * ����������� ������� �� ������ ����
     * @param string $class_name ��� ������
     * @param string $function_name ��� �������
     * @param array $function_row ������ ������������� ������ �� �������
     * @param string $path ��� �������
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
     * ����� ��������� ��������� �� ����� [config.ini]
     * @param string $str ���� ��������� �������
     * @return string
     */
    function lang($str) {
        if ($this->SysValue['lang'][$str])
            return $this->SysValue['lang'][$str];
        else
            return '�� ����������';
    }

    /**
     * ������ � ������
     * @param string $param ��� ��������� [catalog.param]
     * @param mixed $value ��������
     */
    function memory_set($param, $value) {
        if (!empty($this->memory)) {
            $param = explode(".", $param);
            $_SESSION['Memory'][__CLASS__][$param[0]][$param[1]] = $value;
            $_SESSION['Memory'][__CLASS__]['time'] = time();
        }
    }

    /**
     * ������� �� ������
     * @param string $param ��� ��������� [catalog.param]
     * @param bool $check �������� � �����
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
     * ������ ������ �� �������
     * @param bool $clean_now �������������� ������
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
     * ���������� ��������� ������� ���������� �������
     * @param string $class_name ��� ������
     * @param string $function_name ��� ������
     * @param mixed $data ������ ��� ���������
     * @param string $rout ������� ������ � ������� [END | START | MIDDLE], �� ��������� END
     * @return bool
     */
    function setHook($class_name, $function_name, $data = false, $rout = false) {
        return $this->PHPShopModules->setHookHandler($class_name, $function_name, array(&$this), $data, $rout);
    }

    /**
     * ���������
     * @param string $title ���������
     * @param string $content ����������
     * @return string
     */
    function message($title, $content) {
        $message = PHPShopText::b(PHPShopText::notice($title, false, '14px')) . PHPShopText::br();
        $message.=PHPShopText::message($content, false, '12px', 'black');
        return $message;
    }

    /**
     * ������� ��������� ����������
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
    		$retval[0]="where city like '%'";//city='�����-���������'
    	} elseif($cookie_region=="chb") {
    		$retval[0]="where city like '%'"; //city='���������'
    	} elseif($cookie_region=="m") {
    		$retval[0]="where city like '%'"; //city='������'
    	} elseif($cookie_region=="other" || empty($cookie_region)) {
    		$retval[0]="where city like '%'";
    	}
    	return $retval;
    }
    
    function check_service_existing_by_region($cookie_region,$brand='') {
    	//1.��������� ���� ������ � ������ ������
    	$retval=array();
    	$select[0]="city";
    	$where[0]=$brand;
    	$has_service_in_region=false;
    
    	switch($cookie_region){
    		case "sp":
    			$sql="SELECT brand from ".$GLOBALS['SysValue']['base']['service_and_varranty']." where city='�����-���������' and brand='".$brand."'";
    			$res=mysql_query($sql);
    			//$result=$this->select($select, $where,false,'',false,$GLOBALS['SysValue']['base']['service_and_varranty']);
    			if ( mysql_num_rows($res)>0 ) {
    				$has_service_in_region=true;
    			}
    			break;
    		case 'chb':
    			$sql="SELECT brand from ".$GLOBALS['SysValue']['base']['service_and_varranty']." where city='���������' and brand='".$brand."'";
    			$res=mysql_query($sql);
    			//$where[1]='���������';
    			//$result=$this->select($select, $where,false,'',false,$GLOBALS['SysValue']['base']['service_and_varranty']);
    			if ( mysql_num_rows($res)>0 ) {
    				$has_service_in_region=true;
    			}
    			break;
    		case 'm':
    			$sql="SELECT brand from ".$GLOBALS['SysValue']['base']['service_and_varranty']." where city='������' and brand='".$brand."'";
    			$res=mysql_query($sql);
    			//$where[1]='������';
    			//$result=$this->select($select, $where,false,'',false,$GLOBALS['SysValue']['base']['service_and_varranty']);
    			if ( mysql_num_rows($res)>0 ) {
    				$has_service_in_region=true;
    			}
    			break;
    	}
    
    	//2.��������� ���� �� ������� � ������ ��������
    	if ($has_service_in_region==false) {
    		$retval=array('service_existing_in_moscow' => 0);
    		$retval=array('service_existing_in_few_region' => 0);
    		$retval=array('service_existing_in_current_region' => 0);
    		$sql="SELECT city from ".$GLOBALS['SysValue']['base']['service_and_varranty']." where brand='".$brand."'";
    		$res=mysql_query($sql);
    		if ( mysql_num_rows($res)>0 ) {
    			while ($row=mysql_fetch_assoc($res)) {
    				//3.���� ������ ������������ � ������ �������� � ��� ����� � � ������ �� ������� ��� ������� � �������� ������
    				//���� � ������ ��� ���������� ������ �� �� �������� ���������� �����
    				if ( $row['city']=='������' ) {
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
    				//3.���� ������ ������������ � ������ �������� � ��� ����� � � ������ �� ������� ��� ������� � �������� ������
    				//���� � ������ ��� ���������� ������ �� �� �������� ���������� �����
    				if ( $row['city']=='������' ) {
    					$retval['service_existing_in_moscow']=1;
    				} else {
    					$retval['service_existing_in_few_region']=1;
    				}
    			}
    		}
    	}
    	return $retval;
    	//���������� ������������� ������ $retval
    	//[service_existing_in_moscow]
    	//[service_existing_in_current_region]
    	//[service_existing_in_few_region]
    
    }
    
}

?>