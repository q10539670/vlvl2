CREATE TABLE auto_check_v3_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname VARCHAR(64) NOT NULL DEFAULT '' comment'微信昵称',
  avatar varchar(255) not null default '',
  status tinyint not null default 1 comment '账号状态1：正常；2：禁用',
  upload_num int not null default 0 comment '累计上传小票次数',
  total_money int not null default 0 comment '累计中奖总金额',
  prize_num int not null default 0 comment '累计中奖总次数',
  is_active_area tinyint not null default 0 comment '是否为活动地区  0:未解析 || 1：在活动区域   || 2：不在活动区域',
  address_code varchar(32) not null default '-1' comment '-1：未解析  -2：解析失败',
  address_str varchar(255) not null default '',
  location varchar(128) not null default '' comment '经纬度',
  created_at TIMESTAMP NULL DEFAULT NULL COMMENT '注册时间',
  updated_at TIMESTAMP NULL DEFAULT NULL COMMENT '',
  last_upload_at TIMESTAMP NULL DEFAULT NULL COMMENT '',
  last_signup_location_at TIMESTAMP NULL DEFAULT NULL COMMENT '上次提交定位时间',
  last_location_at TIMESTAMP NULL DEFAULT NULL comment '上次解析定位时间',
  PRIMARY KEY (id),
  key idx1(openid,status,is_active_area)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '自动审核-小票用户表';

CREATE TABLE auto_check_v3_tickets (
  id INT UNSIGNED AUTO_INCREMENT,
  user_id int not null default 0 comment '',
  check_status tinyint not null default 0 comment '审核状态: 0:未审核 || 11：已审核通过 || 12：已审核不通过 || 21：发送红包成功 || 发送红包失败',
  money  int unsigned not null default 0 comment '本次中奖金额 单位（分）',
  img_url varchar(255) not null default '' comment '图片地址',
  result_check_msg varchar(128) not null default '' comment '当前审核进度信息',
  result_check_desc varchar(255) comment '当前审核进度描述',
  result_redpack_msg varchar(128) not null default '' comment '当前红包进度信息',
  result_redpack_desc varchar(255) comment '当前红包进度描述',
  result_redpack text comment '微信红包返回结果',
  address_code varchar(32) not null default '-1' comment '地址 编号',
  address_str varchar(255) not null default '' comment '地址字符串',
  pic_words text comment '图片文字',
  pic_hash varchar(36) not null default '' comment '图片文字的md5哈希值',
  created_at TIMESTAMP NULL DEFAULT NULL COMMENT '上传时间',
  checked_at TIMESTAMP NULL DEFAULT NULL COMMENT '审核时间',
  prize_at TIMESTAMP NULL DEFAULT NULL COMMENT '发放红包时间',
  PRIMARY KEY (id),
  key(pic_hash),
  key(user_id),
  key idx1(check_status,money,user_id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '自动审核-小票记录表';

CREATE TABLE `user_demo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) DEFAULT NULL,
  `sex` int(11) DEFAULT '0',
  `language` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `nickname2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `items` varchar(255) DEFAULT NULL,
  `updateTime` bigint(20) DEFAULT '0',
  `addTime` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=InnoDB  CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '授权表';

create database 33wh default character set utf8mb4 collate utf8mb4_unicode_ci;
create database sina default character set utf8mb4 collate utf8mb4_unicode_ci;

update auto_check_v1_tickets set money=1,check_status=0,result_check_msg='',
result_check_desc='',result_redpack_msg='',result_redpack_desc='' limit 10;



