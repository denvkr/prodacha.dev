SET SQL_SAFE_UPDATES = 0;
update u301639.phpshop_categories set title_shablon=replace(title_shablon,' в Москве',''); /*,title_shablon FROM u301639.phpshop_categories;*/
select title_shablon from u301639.phpshop_categories;
update u301639.phpshop_categories set title_shablon=replace(title_shablon,' - купить',' в Москве - купить');/* select replace(title_shablon,' - купить',' в Москве - купить'),replace(title_shablon,' в Москве',''),title_shablon FROM u301639.phpshop_categories;*/
select title_shablon from u301639.phpshop_categories;