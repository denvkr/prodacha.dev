<?php
/******************************************************************
   Projectname:   Automatic Keyword Generator Application Script
   Version:       0.1
   Author:        Ver Pangonilo <smp@limbofreak.com>
   Last modified: 14 July 2006
   Copyright (C): 2006 Ver Pangonilo, All Rights Reserved

   * GNU General Public License (Version 2, June 1991)
   *
   * This program is free software; you can redistribute
   * it and/or modify it under the terms of the GNU
   * General Public License as published by the Free
   * Software Foundation; either version 2 of the License,
   * or (at your option) any later version.
   *
   * This program is distributed in the hope that it will
   * be useful, but WITHOUT ANY WARRANTY; without even the
   * implied warranty of MERCHANTABILITY or FITNESS FOR A
   * PARTICULAR PURPOSE. See the GNU General Public License
   * for more details.

   Description:
   This class can generates automatically META Keywords for your 
   web pages based on the contents of your articles. This will 
   eliminate the tedious process of thinking what will be the best
   keywords that suits your article. The basis of the keyword
   generation is the number of iterations any word or phrase
   occured within an article. 
   
   This automatic keyword generator will create single words, 
   two word phrase and three word phrases. Single words will be
   filtered from a common words list.

******************************************************************/


//assuming that your site contents is from a database.
//set the outbase of the database to $data.

//this the actual application.
include('class.autokeyword.php');
$keyword = new autokeyword();

$params['_W'] = $data; //page content
//set the length of keywords you like
$params['_W1'] = 5;  //minimum length of single words
$params['_W2'] = 4;  //minimum length of words for 2 word phrases
$params['_W3'] = 3;  //minimum length of words for 3 word phrases
$params['_P2'] = 12; //minimum length of 2 word phrases
$params['_P3'] = 15; //minimum length of 3 word phrases

echo $keyword->autokeyword($params);
?>
