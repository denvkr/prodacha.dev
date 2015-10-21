SELECT count(*),(select count(*) from u301639.phpshop_categories) FROM u301639.phpshop_categories where num_cow=30;
select * FROM u301639.phpshop_categories where num_cow<>39;
SET SQL_SAFE_UPDATES = 0;
update u301639.`phpshop_categories` set num_cow=30;