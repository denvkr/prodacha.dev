/*select count(id) cnt from phpshop_products prod join phpshop_product_promo_relation ppr on prod.id=ppr.product_id and prod.id=2352*/
select * from phpshop_promocode;
select * from phpshop_product_promo_relation;
insert into phpshop_promocode(promocode,discountprice) values('345dfgdf',3453454)
select max(id) id from phpshop_promocode where promocode='45654ssdfg'
select max(id) id from phpshop_promocode where promocode='345erterte'