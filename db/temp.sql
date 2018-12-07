create table if not exists `user_main`(
	`id` int(11) unsigned NOT NULL  auto_increment,
	`name` varchar(50) NOT NULL DEFAULT '',
	`email` varchar(50) NOT NULL DEFAULT '',
	`password` char(32) NOT NULL DEFAULT '', 
	PRIMARY KEY(`id`),
)default charset=utf8;
create table if not exists `user_detail`(
	`id` int(11) unsigned NOT NULL  auto_increment,

	`sex` varchar(2) DEFAULT '保密',
	`create_time` int(11) unsigned NOT NULL default 0,
	`update_time` int(11) unsigned NOT NULL default 0,
	
	PRIMARY KEY(`id`)
)default charset=utf8;