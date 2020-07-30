-- 武汉江宸天街
CREATE TABLE x191028_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) null comment '昵称',
  avatar varchar(255) null  comment '头像',
  phone varchar(15) not null default '' comment '电话',
  truename varchar(16) not null default '' comment '姓名',
  subscribe tinyint not null default 0 comment '是否关注:1,关注,2,未关注',
  subscribe_num tinyint not null default 0 comment '关注次数',
  share_num tinyint not null default 0 comment '分享次数',
  game_num tinyint not null default 0 comment '游戏次数',
  game_score int not null default 0 comment '游戏成绩',
  status tinyint not null default 0 comment '抽奖状态,1:已抽奖,2:未抽奖,3:未中奖',
  prize varchar(32) not null default '' comment '奖品',
  prize_id tinyint not null default 0 comment '奖品ID',
  prize_at TIMESTAMP NULL DEFAULT NULL comment '中奖时间',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';


-- 武汉江宸天街 20191206
CREATE TABLE x191206_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) null comment '昵称',
  avatar varchar(255) null  comment '头像',
  phone varchar(15) not null default '' comment '电话',
  name varchar(16) not null default '' comment '姓名',
  address varchar(255) not null default '' comment '地址',
  card tinyint not null default '' comment '锦鲤卡0:自己写,1-8选择',
  card_title varchar(16) not null default '' comment '锦鲤卡标题',
  card_detail varchar(125) not null default '' comment '锦鲤卡详情',
  share_num int not null default 0 comment '分享次数',
  prize_num int not null default 0 comment '抽奖次数',
  status tinyint not null default 0 comment '抽奖状态,1:已抽奖,2:未抽奖,3:未中奖',
  prize varchar(32) not null default '' comment '奖品',
  prize_id tinyint not null default 0 comment '奖品ID',
  prize_at TIMESTAMP NULL DEFAULT NULL comment '中奖时间',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

-- 助力表
CREATE TABLE x191206_share (
  id INT UNSIGNED AUTO_INCREMENT,
  target_user_id int not null default 0,
  help_user_id int not null default 0 ,
  created_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '助力表';
