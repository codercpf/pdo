
create table if not exists userAccount(
	id tinyint unsigned auto_increment key,
	username varchar(20) not null UNIQUE,
	money decimal(10,2)
)ENGINE=INNODB;

INSERT userAccount(username,money) VALUES('imooc',10000),('king',5000);

