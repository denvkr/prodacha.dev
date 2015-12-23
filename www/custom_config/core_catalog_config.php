<?php

/* 
 *$CustomCatalogIDArray1-запролняется в случае каталогов, для который не требуется разделение в меню по типу/по производителю
 * Борис: (внутри каталога одного бренда)- см. CID_500. 
 * + для подобных каталогов определяются вложенные каталоги с случае если это необходимо через функцию buildwheresql в которой
 * редактируется раздел switch case <каталог>: $where=' where id in (<вложенные каталоги>,....)';
 * Борис: в этих каталогах НЕ выводятся заголовки "По типу" и "По производителю"
 */

    $CustomCatalogIDArray1=array(442,459,461,464,465,467,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,498,499,500,501,502,509,536,538,543,545,549,551,555,562,581,585,586,591,594,595);
/* 
 *$CustomCatalogIDArray2-запролняется в случае каталогов, для которых требуется разделение в меню по типу/по производителю 
 * Борис: в этих каталогах выводятся заголовки "По типу" И/ИЛИ "По производителю"
 */
    $CustomCatalogIDArray2=array(5,9,16,18,30,31,32,33,34,35,36,37,38,44,60,62,77,81,85,88,96,97,98,99,120,121,122,123,134,141,142,143,154,172,186,190,191,201,211,215,224,227,228,234,247,248,249,254,252,256,257,258,260,272,288,290,292,293,295,297,298,299,300,332,333,334,335,336,337,350,351,352,353,354,355,356,382,414,415,416,418,419,420,421,422,423,424,425,436,437,440,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,498,499,500,501,502,503,536,545,585);
/* 
 *$CustomCatalogIDArray2_2-запролняется в случае каталогов, для которых требуется кастомизированный вывод в одну строку. 
 */

    $CustomCatalogIDArray2_2=array(30,31,32,33,34,35,81,96,121,141,154,186,190,191,201,227,333,334,382,414,415,416,418,419,420,421,422,423,424,425,431,432,436,433,437,440,442,459,461,464,465,467,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,498,499,500,501,502,536,538,543,545,549,551,555,562,581,585,586,591,594,595);
    
/* 
 * Борис: Перед добавлением каталога в функцию buildwheresql необходимо добавить его в $CustomCatalogIDArray1
 */
    function buildwheresql($id,$source=1,$category=0){
        $where='';
        switch (intval($id)) {
				case 442: $where=' where id in (443)';
                          break;  
                case 459: $where=' where id in (121,250,460)';
                          break;             
                case 461: $where=' where id in (124,233,273,462,463)';
                          break; 
                case 464: $where=' where id in (274,275)';
                          break;
                case 465: $where=' where id in (281,283,466)';
                          break;
                case 467: $where=' where id in (294,296,311)';
                          break; 
                case 473: $where=' where id in (38,120,62,314,45,74,19,15,17,20,21,22,30,27,28,23,24,25,26,29,229,126,173)';
                          break;
                case 474: $where=' where id in (42,46,316)';
                          break;
                case 475: $where=' where id in (41,319,40,318,330)';
                          break;
                case 476: $where = " where id in (70,71,131,102,112,219,409,414,144,198,92,268,269)";
                          break;
                case 477: $where = " where id in (84,127,101,138,216,410,255,309,420,226,305,212)";
                          break;
                case 478: $where = " where id in (83,105,113,415)";
                          break;
                case 479: $where = " where id in (245,103,116,220,411,424,200)";
                          break;
                case 480: $where = " where id in (128,100)";
                          break;
                case 481: $where = " where id in (251,193,203,218,408,310,422,271,225,307,509,517)";
                          break;
                case 482: $where = " where id in (179,178,416,150,325,326)";
                          break;
                case 483: $where = " where id in (129,109,217,421,213)";
                          break;
                case 484: $where = " where id in (171,321)";
                          break;
                case 485: $where = " where id in (240,239,227,241,238,418,204,235)";
                          break;
                case 486: $where = " where id in (174,210,221)";
                          break;
                case 487: $where = " where id in (175,207,223)";
                          break;
                case 488: $where = " where id in (181,205,180,222)";
                          break;
                case 489: $where = " where id in (195,246,194,185,412,199)";
                          break;
                case 490: $where = " where id in (140,111,152)";
                          break;
                case 491: $where = " where id in (139,104)";
                          break;
                case 492: $where = " where id in (52,190)";
                          break;
                case 493: $where = " where id in (303,156,115,413,425,148,243,197,530)";
                          break;
                case 494: $where = " where id in (423,132,106,114,313)";
                          break;
                case 495: $where = " where id in (419,151)";
                          break;
                case 496: $where = " where id in (130,108,306,537)";
                          break;
                case 497: $where = " where id in (168,170)";
                          break;
                case 498: $where = " where id in (209)";
                          break;
                case 499: $where = " where id in (149)";
                          break;
                case 500: $where = " where id in (304,186)";
                          break;
                case 501: $where = " where id in (183)";
                          break;
                case 502: $where = " where id in (244,522,523,524)";
                          break;
                case 509: $where = " where id in (510,511,512,513,514,515)";
                          break;                      
                case 536: $where = " where id in (537,596)";
                          break;
                case 538: $where = " where id in (539,540)";
                          break;
                case 543: $where = " where id in (544)";
                          break;
                case 545: $where = " where id in (538,541,542,543)";
                          break;
                case 549: $where = " where id in (550,552)";
                          break;
                case 551: $where = " where id in (549,553,547,548,554)";
                          break;                                            
                case 555: $where = " where id in (556,557,558,559,560)";
                          break;
                case 562: $where = " where id in (563,564,565,566,567)";
                          break;
                case 581: $where = " where id in (582,569,572,571,576,578,583)";
                          break;
                case 585: $where = " where id in (584,586)";
                          break;
                case 586: $where = " where id in (587,588)";
                          break;
                case 591: $where = " where id in (592,593,597)";
                          break;
                case 594: $where = " where id in (598,599)";
                          break;
                case 595: $where = " where id in (600,601)";
                          break;
                default: // Условия выборки
                          if ($source==2 && $category<>0) {
                             $where = array('parent_to' => '=' . $category, 'skin_enabled' => "!='1'");
                          }
        }
        return $where;
    }
    
        
    //не править
    $CustomCatalogIDArray2_1=array_merge($CustomCatalogIDArray1,$CustomCatalogIDArray2);//array(5,9,16,18,30,31,32,33,34,35,36,37,38,44,60,62,77,81,85,88,96,97,98,99,120,121,122,123,134,141,142,143,154,172,186,190,191,201,211,215,224,227,228,234,247,248,249,254,252,256,257,258,270,272,288,290,292,293,295,297,298,299,300,332,333,334,335,336,337,350,351,352,353,354,355,356,382,414,415,416,418,419,420,421,422,423,424,425,436,437,440,442,459,461,464,465,467,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,498,499,500,501,502,503,509,536,538,543,545,549,551,555,562,581,585,586,591,594,595);
    
    //не править
    $CustomCatalogIDArray3=$CustomCatalogIDArray1;
    array_push($CustomCatalogIDArray3,472);

?>
