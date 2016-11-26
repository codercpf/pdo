create table if not exists mail(
	id int(11) auto_increment not null primary key,
	username varchar(30) not null,
	password varchar(32) not null,
	email varchar(30) not null,
	token varchar(50) not null,
	token_exptime int(10) not null,
	status tinyint(1) not null,
	regtime int(10) not null
);