create table `user_main`(
	`id` int(11) unsigned NOT NULL  auto_increment,
	`email` varchar(50) NOT NULL DEFAULT '',
	`password` char(32) NOT NULL DEFAULT '', 
	`code` varchar(10) NOT NULL DEFAULT '',
	`name` varchar(50) NOT NULL DEFAULT '',
	PRIMARY KEY(`id`),
	KEY (name),
)
create table `user_detail`(
	`id` int(11) unsigned NOT NULL  auto_increment,

	`sex` varchar(2) DEFAULT '保密',
	`last_ip` varchar(20) NOT NULL DEFAULT '',
	`last_login_time` int(11) NOT NULL DEFAULT 0,
	`list_order` int(8) unsigned NOT NULL DEFAULT 0,
	`status` tinyint(1) NOT NULL DEFAULT 0,
	`create_time` int(11) unsigned NOT NULL default 0,
	`update_time` int(11) unsigned NOT NULL default 0,
	
	PRIMARY KEY(`id`),
	KEY (name),
)