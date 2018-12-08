/* mysql->运行SQL文件 */

DROP DATABASE IF EXISTS db_course_design;
CREATE DATABASE db_course_design DEFAULT CHARSET UTF8 COLLATE UTF8_GENERAL_CI;
USE db_course_design;

create table if not exists `user_main`(
	`id` int(11) unsigned NOT NULL  auto_increment,
	`name` varchar(50) NOT NULL UNIQUE DEFAULT '',
	`email` varchar(50) NOT NULL UNIQUE DEFAULT '',
	`password` char(32) NOT NULL DEFAULT '', 
	PRIMARY KEY(`id`)
)default charset=utf8;

create table if not exists `user_detail`(
	`id` int(11) unsigned NOT NULL,
	`sex` varchar(6) DEFAULT '保密',
	`create_time` int(11) unsigned NOT NULL default 0,
	`update_time` int(11) unsigned NOT NULL default 0,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`id`) REFERENCES user_main(`id`)
)default charset=utf8;

CREATE TABLE IF NOT EXISTS product (	/* 物品 */
	product_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	product_name VARCHAR(50) NOT NULL,
	detail TEXT NOT NULL DEFAULT '',
	price DOUBLE NOT NULL,
	quantity INT UNSIGNED NOT NULL,
	likes INT UNSIGNED NOT NULL DEFAULT 0,
	/* 类别暂时还没想好怎么存, 先简单点只分几个大类吧 */
	category_no INT UNSIGNED NOT NULL,
	PRIMARY KEY(product_id),
	FOREIGN KEY (category_no) REFERENCES category(category_no)
) default charset=utf8;

CREATE TABLE category (
	category_no INT UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(20) NOT NULL UNIQUE,
	category_comment VARCHAR(100) NOT NULL,
	PRIMARY KEY (category_no)
) default charset=utf8;

CREATE TABLE IF NOT EXISTS user_product (
	user_id INT UNSIGNED NOT NULL,
	product_id INT UNSIGNED NOT NULL UNIQUE,
	PRIMARY KEY (user_id, product_id),
	FOREIGN KEY (user_id) REFERENCES user_main(id),
	FOREIGN KEY (product_id) REFERENCES product(product_id)
) default charset=utf8;

CREATE TABLE IF NOT EXISTS product_comment (
	comment_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id INT UNSIGNED NOT NULL,
	product_id INT UNSIGNED NOT NULL,
	content VARCHAR(500) NOT NULL,
	comment_time DATETIME NOT NULL,
	PRIMARY KEY (comment_id),
	FOREIGN KEY (user_id) REFERENCES user_main(id),
	FOREIGN KEY (product_id) REFERENCES product(product_id)
) default charset=utf8;

CREATE TABLE IF NOT EXISTS product_picture (
	pic_no INT UNSIGNED NOT NULL AUTO_INCREMENT,
	product_id INT UNSIGNED NOT NULL,
	pic_path VARCHAR(1024) NOT NULL,
	PRIMARY KEY (pic_no),
	FOREIGN KEY (product_id) REFERENCES product(product_id)
)default charset=utf8;

CREATE TABLE IF NOT EXISTS orders (
	order_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	buyer_id INT UNSIGNED NOT NULL,
	order_time DATETIME NOT NULL,
	status VARCHAR(10) NOT NULL,	
	PRIMARY KEY (order_id),
	FOREIGN KEY (buyer_id) REFERENCES user_main(id)
)default charset=utf8;

CREATE TABLE IF NOT EXISTS order_detail (
	detail_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	order_id INT UNSIGNED NOT NULL,
	product_id INT UNSIGNED NOT NULL,
	quantity INT UNSIGNED NOT NULL,
	price DOUBLE NOT NULL,	/* 这个价格跟product表里的价格应该是一致的 */
	PRIMARY KEY (detail_id),
	FOREIGN KEY (order_id) REFERENCES orders(order_id),
	FOREIGN KEY (product_id) REFERENCES product(product_id)
)default charset=utf8;

INSERT INTO user_main VALUES -- pwd:123456
(null, 'xiaoming', 'xm@ww', 'e10adc3949ba59abbe56e057f20f883e'),
(null, 'xiaohong', 'xh@ww', 'e10adc3949ba59abbe56e057f20f883e');

INSERT INTO user_detail VALUES
(1, '男', 1544173011, 1544173011),
(2, '女', 1544173011, 1544173011);

INSERT INTO category VALUES
(null, '教材', ''),
(null, '生鲜', '');

INSERT INTO product VALUES
(null, '数据库系统概论第5版', '高等教育出版社', 39.60, 2, 0, 1),
(null, '土豆', '曙光 云南红皮黄心小土豆 10斤 新鲜马铃薯 蔬菜洋芋 10斤小土豆 70个左右', 18.90, 10, 1, 2);

INSERT INTO user_product VALUES 
(1, 1),
(1, 2);

INSERT INTO product_comment VALUES
(null, 2, 1, '好书', CURRENT_TIME()),
(null, 1, 2, '很好', CURRENT_TIME());

INSERT INTO product_picture VALUES 
(null, 1, 'db.jpg'),
(null, 2, 'tudou.jpg'),
(null, 2, '5b39c72eNca23cf0f.jpg');

INSERT INTO orders VALUES 
(null, 2, CURRENT_TIME(), '已完成');

INSERT INTO order_detail VALUES
(null, 1, 1, 11, 39.60),
(null, 1, 2, 22, 18.90);

/* 
CREATE TABLE IF NOT EXISTS admin (
	admin_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	admin_name VARCHAR(16) NOT NULL,
	pnumber CHAR(11) NOT NULL,
	PRIMARY KEY (admin_id)
)default charset=utf8;

-- 审核
CREATE TABLE IF NOT EXISTS admin_audit (
	admin_id INT UNSIGNED NOT NULL,
	order_id INT UNSIGNED NOT NULL,
	status VARCHAR(10) NOT NULL,
	PRIMARY KEY (admin_id, order_id),
	FOREIGN KEY (order_id) REFERENCES order(order_id),
	FOREIGN KEY (admin_id) REFERENCES admin(admin_id)
)default charset=utf8;
*/