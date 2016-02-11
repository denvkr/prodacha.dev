select uid from u301639.phpshop_products where uid like 'ps%';
update u301639.phpshop_products set uid=replace(uid,'ps','ps_') where uid like 'ps%';
select * from u301639.phpshop_products where uid='ps_ECT7000K1 '