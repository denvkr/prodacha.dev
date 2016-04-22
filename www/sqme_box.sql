select * from phpshop_categories where parent_to=9;
select id,uid,pic_small,name,price from phpshop_products where id<>1487 and sklad="0" and outdated="0" and category in (587) order by RAND()
select id,uid,pic_small,name,price from phpshop_products where id<>394 and sklad="0" and outdated="0" and category in (40,41) order by RAND()