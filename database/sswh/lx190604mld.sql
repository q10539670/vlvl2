
-- 美年达 start
-- 美年达 用户表  [ 20190604]
CREATE TABLE lx190604mld_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) not null default '',
  avatar varchar(255) not null default '',
  post_num int not null default 0 comment '用户提交信息次数',
  message_num int not null default 0 comment '用户反馈次数',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  key (openid)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '用户表';


-- 美年达 用户信息提交表  [ 20190604]
CREATE TABLE lx190604mld_post (
  id INT UNSIGNED AUTO_INCREMENT,
  user_id int not null default 0 comment '',
  user_address varchar(255) not null default '',
  user_name  varchar(64) not null default '',
  user_phone  varchar(30) not null default '',
  user_sale  varchar(64) not null default '',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '用户信息提交表';

-- 美年达 用户反馈表  [ 20190604]
CREATE TABLE lx190604mld_message (
  id INT UNSIGNED AUTO_INCREMENT,
  user_id int not null default 0 comment '',
  message text,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '用户反馈表';

-- 美年达 end