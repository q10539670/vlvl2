
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


-- bl190716
CREATE TABLE bl190716_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) not null default '' comment '昵称',
  truename varchar(64) not null default '' comment '真实姓名',
  avatar varchar(255) not null default '' comment '头像',
  phone varchar(11) not null default '' comment '手机号',
  score int NOT NULL DEFAULT 0 comment '游戏成绩',
  help_num int not null default 0 comment '助力次数',
  total int not null default 0 comment '总成绩',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '孝感保利游戏成绩表';

CREATE TABLE bl190716_help (
  id INT UNSIGNED AUTO_INCREMENT,
  target_user_id int not null default 0,
  help_user_id int not null default 0 ,
  created_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '孝感保利游戏助力表';


--七夕搭鹊桥小游戏 [ 20190726 ]
CREATE TABLE ls190726_score (
  id int UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  score int NOT NULL DEFAULT 0 comment '游戏成绩',
  game_num int not null default 0 comment '游戏次数',
  share_num int not null default 0 comment '分享次数',
  total int not null default 0 comment '总成绩',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '七夕搭鹊桥游戏表';
--游戏分数记录
CREATE TABLE ls190726_score_log (
  id int UNSIGNED AUTO_INCREMENT,
  user_id int not null default 0,
  score int NOT NULL DEFAULT 0 comment '游戏成绩',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '七夕搭鹊桥游戏成绩表';


--报名 [ 20190805 ]
CREATE TABLE bm190805_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  truename varchar(64) not null default '' comment '真实姓名',
  phone varchar(11) not null default '' comment '手机号',
  sex tinyint not null default 0 comment '性别,0:男,1女',
  age tinyint not null default 0 comment '年龄',
  cooking_age tinyint not null default 0 comment '厨龄',
  specialty varchar(30) not null default '' comment '拿手菜',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '报名';


--大桥吃瓜小游戏 [ 20190808 ]
CREATE TABLE lx190808_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  truename varchar(64) not null default '' comment '真实姓名',
  phone varchar(11) not null default '' comment '手机号',
  score int NOT NULL DEFAULT 0 comment '游戏成绩',
  game_num int not null default 0 comment '游戏次数 -1:无限次数',
  share_num int not null default 0 comment '分享次数',
  total int not null default 0 comment '总成绩',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '大桥吃瓜小游戏用户表';
--游戏分数记录
CREATE TABLE lx190808_score_log (
  id int UNSIGNED AUTO_INCREMENT,
  user_id int not null default 0,
  score int NOT NULL DEFAULT 0 comment '游戏成绩',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '大桥吃瓜小游戏成绩表';


--投票页面 [ 20190822 ]
CREATE TABLE tp190822_team (
  id int UNSIGNED AUTO_INCREMENT,
  number int not null default 0 comment '编号',
  team_name varchar(255) not null default '' comment '参赛队伍名称',
  team_img varchar (255) not null default '' comment '参赛队伍图片',
  poll int not null default 0 comment '票数',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '参赛队伍表';

CREATE TABLE tp190822_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  vote_num tinyint not null default 0 comment '剩余投票次数',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '投票用户表';

CREATE TABLE tp190822_vote_log (
  id int UNSIGNED AUTO_INCREMENT,
  user_id int NOT NULL DEFAULT 0 comment '用户id',
  team_id int NOT NULL DEFAULT 0 comment '队伍id',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '投票记录表';


--大桥广场舞大赛报名 [ 20190830 ]
CREATE TABLE bm190830_team (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  truename varchar(64) not null default '' comment '真实姓名',
  team_name varchar(64) not null default '' comment '舞团名称',
  team_peoples int not null default 0 comment '舞团人数',
  team_introduction varchar(255) not null default '' comment '舞团简介',
  team_age int not null default 0 comment '成员均龄',
  phone varchar(15) not null default '' comment '负责人电话',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '大桥广场舞大赛报名';


--保利中秋活动 [ 20190902 ]
CREATE TABLE bt190902_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  truename varchar(64) not null default '' comment '真实姓名',
  phone varchar(15) not null default '' comment '电话',
  image varchar(255) not null default '' comment '作品',
  polls int not null default 0 comment '票数',
  num tinyint not null default 1 comment '剩余投票次数',
  share_num tinyint not null default 1 comment '分享+投票次数',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '保利中秋活动';

CREATE TABLE bt190902_vote_log (
  id int UNSIGNED AUTO_INCREMENT,
  user_id int NOT NULL DEFAULT 0 comment '用户id',
  target_id int NOT NULL DEFAULT 0 comment '目标id',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '投票记录表';

--大桥舞林争霸投票页面 [ 20190905 ]
CREATE TABLE tp190905_team (
  id int UNSIGNED AUTO_INCREMENT,
  number int not null default 0 comment '编号',
  team_name varchar(255) not null default '' comment '参赛队伍名称',
  team_img varchar (255) not null default '' comment '参赛队伍图片',
  team_introduction varchar(255) not null default '' comment '舞团简介',
  team_slogan varchar(255) not null default '' comment '舞团宣言',
  poll int not null default 0 comment '票数',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '参赛队伍表';

CREATE TABLE tp190905_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  vote_num tinyint not null default 0 comment '剩余投票次数',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '投票用户表';

CREATE TABLE tp190905_vote_log (
  id int UNSIGNED AUTO_INCREMENT,
  user_id int NOT NULL DEFAULT 0 comment '用户id',
  team_id int NOT NULL DEFAULT 0 comment '队伍id',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '投票记录表';

--保利香颂投票页面 [ 20190911 ]
CREATE TABLE tp190911_users (
  id int UNSIGNED AUTO_INCREMENT,
  number int not null default 0 comment '编号',
  name varchar(255) not null default '' comment '姓名',
  poll int not null default 0 comment '票数',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '投票选手表';

CREATE TABLE tp190911_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  vote_num tinyint not null default 0 comment '剩余投票次数',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '投票用户表';

CREATE TABLE tp190911_vote_log (
  id int UNSIGNED AUTO_INCREMENT,
  user_id int NOT NULL DEFAULT 0 comment '用户id',
  player_id int NOT NULL DEFAULT 0 comment '选手id',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '投票记录表';


--汉江府"你加油我买单" [ 20190916 ]
CREATE TABLE hjf190916_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  truename varchar(255) not null default '' comment '姓名',
  phone varchar(15) not null default '' comment '电话',
  gasoline int not null default 0 comment '油气值',
  help_num int not null default 0 comment '加油次数',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '用户表';

CREATE TABLE hjf190916_help (
  id INT UNSIGNED AUTO_INCREMENT,
  target_user_id int not null default 0 comment '目标id',
  help_user_id int not null default 0 comment '用户id',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '加油表';

--保利香颂节奏大师游戏 [ 20190920 ]
CREATE TABLE bl190920_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  truename varchar(255) not null default '' comment '姓名',
  phone varchar(15) not null default '' comment '电话',
  draw_num tinyint not null default 0 comment '抽奖次数',
  help_num int not null default 0 comment '助力',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '用户表';

CREATE TABLE bl190920_help (
  id INT UNSIGNED AUTO_INCREMENT,
  target_user_id int not null default 0 comment '目标id',
  help_user_id int not null default 0 comment '用户id',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '助力表';

CREATE TABLE bl190920_draw (
  id INT UNSIGNED AUTO_INCREMENT,
  user_id int not null default 0 comment '中奖id',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  truename varchar(255) not null default '' comment '姓名',
  phone varchar(15) not null default '' comment '电话',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '中奖表';

CREATE TABLE bl190920_draw_log (
  id INT UNSIGNED AUTO_INCREMENT,
  user_id int not null default 0 comment '用户id',
  draw tinyint not null default 0 comment '0:未中奖,1:中奖',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '抽奖记录表';

--电信9月份抽奖活动
CREATE TABLE dx190925_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  phone varchar(15) not null default '' comment '电话',
  share_code varchar(16) not null default '' comment '分享码',
  subscribe tinyint not null default 0 comment '是否关注:1,关注,2,未关注',
  prize_num tinyint not null default 0 comment '抽卡次数',
  real_share tinyint not null default 0 comment '有效分享次数',
  virtual_address_str varchar(128) not null default '' comment '区域',
  virtual_address_code varchar(16) not null default '' comment '区域编号',
  address_str varchar(128) not null default '' comment '地址',
  address_code varchar(128) not null default '' comment '地址编号',
  location varchar(128) not null default '' comment '经纬度',
  cards_num tinyint not null default  0 comment '卡数量',
  status tinyint not null default 0 comment '是否抽奖:1,已抽奖,2,未抽奖',
  prize tinyint not null default 0 comment '奖品',
  prize_at TIMESTAMP NULL DEFAULT NULL comment '中奖时间',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  a tinyint not null default 0 comment '卡1',
  b tinyint not null default 0 comment '卡2',
  c tinyint not null default 0 comment '卡3',
  d tinyint not null default 0 comment '卡4',
  e tinyint not null default 0 comment '卡5',
  f tinyint not null default 0 comment '卡6',
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

CREATE TABLE dx190925_help (
  id INT UNSIGNED AUTO_INCREMENT,
  target_user_id int not null default 0 comment '目标id',
  help_user_id int not null default 0 comment '用户id',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  status tinyint not null default  0 comment '是否有效',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '助力表';

--保利抽奖活动
CREATE TABLE x190929_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  truename varchar(16) not null default '' comment '真实姓名',
  phone varchar(16) not null default '' comment '手机号码',
  prize_num tinyint not null default 0 comment '抽奖次数',
  share_num tinyint not null default 0 comment '分享次数',
  status tinyint not null default 0 comment '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
  prize_id tinyint not null default 0 comment '奖品ID',
  prize varchar(32) not null default '' comment '奖品',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

CREATE TABLE x190929_draw (
  id INT UNSIGNED AUTO_INCREMENT,
  user_id int not null default 0 comment '中奖id',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  truename varchar(255) not null default '' comment '姓名',
  phone varchar(15) not null default '' comment '电话',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '中奖表';

CREATE TABLE x190929_draw_log (
  id INT UNSIGNED AUTO_INCREMENT,
  user_id int not null default 0 comment '用户id',
  draw tinyint not null default 0 comment '0:未中奖,1:中奖',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '抽奖记录表';

CREATE TABLE x190929_help (
  id INT UNSIGNED AUTO_INCREMENT,
  target_user_id int not null default 0 comment '目标id',
  help_user_id int not null default 0 comment '用户id',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '助力表';


--保利17万现金抽奖活动
CREATE TABLE x191008_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  truename varchar(16) not null default '' comment '真实姓名',
  id_num varchar(18) not null default '' comment '身份证号码',
  phone varchar(16) not null default '' comment '手机号码',
  projects varchar(32) not null default '' comment '认购签约项目名',
  ticket tinyint not null default 0 comment '抽奖券0:没有,1:获取,2:使用',
  status tinyint not null default 0 comment '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
  prize varchar(32) not null default '' comment '奖品',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

CREATE TABLE x191008_info (
  id INT UNSIGNED AUTO_INCREMENT,
  truename varchar(16) not null default '' comment '真实姓名',
  id_num varchar(18) not null default '' comment '身份证号码',
  phone varchar(16) not null default '' comment '手机号码',
  projects varchar(32) not null default '' comment '认购签约项目名',
  status tinyint not null default 0 comment '验证状态:0,未验证1,已验证',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '验证表';

CREATE TABLE x191008_token (
  id INT UNSIGNED AUTO_INCREMENT,
  token varchar(12) not null default '' comment '令牌',
  status tinyint not null default 0 comment '是否使用:0,未使用1,已使用',
  used_at TIMESTAMP NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '令牌表';

--天街娃娃机
CREATE TABLE x191009_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  truename varchar(16) not null default '' comment '真实姓名',
  phone varchar(16) not null default '' comment '手机号码',
  ip varchar(32) not null default '' comment 'IP地址',
  ip_status tinyint not null default 0 comment 'IP状态',
  prize_num tinyint not null default 0 comment '抽奖次数',
  total_num tinyint not null default 0 comment '总抽奖次数',
  score int not null default 0 comment '最佳成绩',
  address_str varchar(128) not null default '' comment '地址',
  address_code varchar(128) not null default '' comment '地址编号',
  status tinyint not null default 0 comment '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
  verification_code  varchar(16) not null default '' comment '核销码',
  verification tinyint not null default 0 comment '核销状态,0:未核销,1:已核销',
  prize_id tinyint not null default 0 comment '奖品ID',
  prize varchar(32) not null default '' comment '奖品',
  prize_at TIMESTAMP NULL DEFAULT NULL comment '中奖时间',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

--金地投票
CREATE TABLE x191014_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  vote_num tinyint not null default 0 comment '投票次数',
  status tinyint not null default 0 comment '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
  prize_id tinyint not null default 0 comment '奖品ID',
  prize varchar(32) not null default '' comment '奖品',
  prize_code  varchar(16) not null default '' comment '中奖码',
  prize_at TIMESTAMP NULL DEFAULT NULL comment '中奖时间',
  verification tinyint not null default 0 comment '核销状态,0:未核销,1:已核销',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

CREATE TABLE x191014_shop (
  id int UNSIGNED AUTO_INCREMENT,
  number int not null default 0 comment '编号',
  team_name varchar(255) not null default '' comment '商家名称',
  team_img varchar (255) not null default '' comment '商家图片',
  poll int not null default 0 comment '票数',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '商家表';

CREATE TABLE x191014_vote_log (
  id int UNSIGNED AUTO_INCREMENT,
  user_id int NOT NULL DEFAULT 0 comment '用户id',
  shop_id int NOT NULL DEFAULT 0 comment '商家id',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '投票记录表';

CREATE TABLE x191014_comment (
  id int UNSIGNED AUTO_INCREMENT,
  user_id int NOT NULL DEFAULT 0 comment '用户id',
  shop_id int NOT NULL DEFAULT 0 comment '商家id',
  comment varchar(255) not null default '' comment '评论',
  images text null default '' comment '图片',
  rated tinyint not null default 0 comment '打分',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '评论表';

--武汉院子 [ 20191029 ]
CREATE TABLE x191029_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(64) not null default '' comment '姓名',
  phone varchar(64) not null default '' comment '电话',
  room_num varchar(64) not null default '' comment '房号',
  id_num varchar(64) not null default '' comment '身份证号',
  banquet_id tinyint not null default 0 comment '私宴id',
  banquet varchar(32) not null default '' comment '私宴名称',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '武汉院子';

--湘中跑酷 [20191101]
CREATE TABLE x191101_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(64) not null default '' comment '姓名',
  phone varchar(11) not null default '' comment '手机号',
  game_num tinyint not null default 0 comment '游戏次数',
  share_num tinyint not null default 0 comment '分享次数',
  score int NOT NULL DEFAULT 0 comment '游戏成绩',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '用户表';

--湘中美的置业 [ 20191115 ]
CREATE TABLE x191115_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(64) not null default '' comment '姓名',
  phone varchar(64) not null default '' comment '电话',
  pty varchar(32) not null default '' comment '节目类型',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '湘中美的置业报名';

--百事感恩节
CREATE TABLE x191122_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(16) not null default '' comment '真实姓名',
  phone varchar(16) not null default '' comment '手机号码',
  address varchar(255) not null default '' comment '邮寄地址',
  game_num tinyint not null default 0 comment '游戏次数',
  score int not null default 0 comment '最佳成绩',
  status tinyint not null default 0 comment '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
  prize_id tinyint not null default 0 comment '奖品ID',
  prize varchar(32) not null default '' comment '奖品',
  prize_at TIMESTAMP NULL DEFAULT NULL comment '中奖时间',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

--长沙天街感恩节
CREATE TABLE x191125_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(16) not null default '' comment '真实姓名',
  phone varchar(16) not null default '' comment '手机号码',
  address varchar(255) not null default '' comment '邮寄地址',
  prize_num tinyint not null default 0 comment '抽奖次数',
  share_num tinyint not null default 0 comment '分享次数',
  status tinyint not null default 0 comment '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
  prize_id tinyint not null default 0 comment '奖品ID',
  prize varchar(32) not null default '' comment '奖品',
  prize_at TIMESTAMP NULL DEFAULT NULL comment '中奖时间',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';


CREATE TABLE auto_check_v3_prize (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  type tinyint not null default 0 comment '活动类型1:1月1奖金池,2:1月25神秘奖',
  join_num  unsigned tinyint not null default 0 comment '参与活动次数',
  prize int not null default 0 comment '中奖金额',
  status tinyint not null default 0 comment '状态 0:未分配,1:分配未确认,2:已分配确认,3:红包发送成功,4:红包发送失败',
  prized_at TIMESTAMP NULL DEFAULT NULL comment '红包发送时间',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';


CREATE TABLE X191202_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(16) not null default '' comment '姓名',
  card_id varchar(4) not null default '' comment '身份证后4位',
  phone varchar(16) not null default '' comment '手机号',
  auth tinyint not null default 0 comment '身份验证,0:未验证,1:是业主,2:不是业主',
  status tinyint not null default 0 comment '抽奖状态,0:未抽奖,1:已中奖,2:未中奖',
  prize_num int not null default 0 comment '抽奖次数',
  prize varchar(32) not null default '' comment '奖品名称',
  prize_id tinyint not null default 0 comment '奖品id',
  prize_code varchar(32) not null default '' comment '奖品核销码',
  validate tinyint not null default 0 comment '是否核销,0:未核销,1:已核销',
  prized_at TIMESTAMP NULL DEFAULT NULL comment '中奖时间',
  validated_at TIMESTAMP NULL DEFAULT NULL comment '核销时间',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

CREATE TABLE X191202_info (
  id INT UNSIGNED AUTO_INCREMENT,
  name varchar(16) not null default '' comment '姓名',
  card_id varchar(4) not null default '' comment '身份证后4位',
  status tinyint not null default 0 comment '验证状态,0:未验证,1:已验证',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '业主信息表';


--奥特莱斯转盘H5
CREATE TABLE x191202a_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  prize_num tinyint not null default 0 comment '抽奖次数',
  share_num tinyint not null default 0 comment '分享次数',
  status tinyint not null default 0 comment '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
  prize_id tinyint not null default 0 comment '奖品ID',
  prize varchar(32) not null default '' comment '奖品',
  prize_at TIMESTAMP NULL DEFAULT NULL comment '中奖时间',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

--宜昌中心天宸府现金抽奖
CREATE TABLE x191203_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(16) not null default '' comment '姓名',
  phone varchar(16) not null default '' comment '电话',
  prize_num tinyint not null default 0 comment '抽奖次数',
  share_num tinyint not null default 0 comment '分享次数',
  status tinyint not null default 0 comment '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
  money tinyint(4) NOT NULL DEFAULT '0' COMMENT '中奖金额',
  redpack_return_msg varchar(128) NOT NULL DEFAULT '' COMMENT '红包返回信息',
  redpack_describle text COMMENT '红包详情',
  prized_at timestamp NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY openid (openid),
  KEY status (status),
  KEY money (money)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';


--宜昌中心天宸府现金红包
CREATE TABLE x191211_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(16) not null default '' comment '姓名',
  phone varchar(16) not null default '' comment '电话',
  status tinyint not null default 0 comment '状态,0:未领取,1:已领取',
  money int NOT NULL DEFAULT 0 COMMENT '中奖金额',
  redpack_return_msg varchar(128) NOT NULL DEFAULT '' COMMENT '红包返回信息',
  redpack_describle text COMMENT '红包详情',
  prized_at timestamp NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY openid (openid),
  KEY status (status),
  KEY money (money)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

--宜昌中心天宸府台州商会
CREATE TABLE x200106_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  status tinyint not null default 0 comment '状态,0:未领取,1:已领取',
  round tinyint not null default 0 comment '抽奖轮数,0:未开始,1:第一轮,2...'
  prize int NOT NULL DEFAULT 0 COMMENT '中奖奖品',
  redpack_return_msg varchar(128) NOT NULL DEFAULT '' COMMENT '红包返回信息',
  redpack_describle text COMMENT '红包详情',
  prized_at timestamp NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY openid (openid),
  KEY status (status),
  KEY prize (prize)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

--华为
CREATE TABLE x200102a_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(16) not null default '' comment '姓名',
  phone varchar(11) not null default '' comment '电话',
  address varchar(128) not null default '' comment '地址',
  prize_num tinyint not null default 0 comment '抽奖次数',
  share_num tinyint not null default 0 comment '分享次数',
  status tinyint not null default 0 comment '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
  prize_id tinyint not null default 0 comment '中奖ID',
  prize int NOT NULL DEFAULT 0 COMMENT '中奖奖品',
  prized_at timestamp NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY openid (openid),
  KEY status (status),
  KEY prize_id (prize_id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

--大桥
CREATE TABLE x200103_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  game_num tinyint not null default 0 comment '游戏次数',
  share_num tinyint not null default 0 comment '分享次数',
  score int not null default 0 comment '游戏分数',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

--百事新年
CREATE TABLE x200109_user (
  id INT UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '' comment 'openid',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(16) not null default '' comment '姓名',
  phone varchar(11) not null default '' comment '电话',
  address varchar(128) not null default '' comment '地址',
  prize_num tinyint not null default 0 comment '抽奖次数',
  share_num tinyint not null default 0 comment '分享次数',
  status tinyint not null default 0 comment '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
  prize_id tinyint not null default 0 comment '中奖ID',
  prize int NOT NULL DEFAULT 0 COMMENT '中奖奖品',
  prized_at timestamp NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY openid (openid),
  KEY status (status),
  KEY prize_id (prize_id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

--湘中年味照片征集 [ 20200113 ]
CREATE TABLE x200113_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(64) not null default '' comment '真实姓名',
  phone varchar(15) not null default '' comment '电话',
  image varchar(255) not null default '' comment '照片',
  polls int not null default 0 comment '票数',
  num tinyint not null default 0 comment '剩余投票次数',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '用户表';

CREATE TABLE x200113_vote_log (
  id int UNSIGNED AUTO_INCREMENT,
  user_id int NOT NULL DEFAULT 0 comment '用户id',
  target_id int NOT NULL DEFAULT 0 comment '目标id',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '投票记录表';

--首创奥特莱斯掷骰子
CREATE TABLE x200114_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  seat tinyint not null default 0 comment '骰子位置',
  score int not null default 0 comment '分数',
  game_num int not null default 0 comment '剩余次数',
  share_num int not null default 0 comment '分享次数',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '用户表';

--上传
CREATE TABLE x200120_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  image text not null default '图片',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '用户表';

--百事新春
CREATE TABLE x200121_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  love int not null default 0 comment '热爱值',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '用户表';

CREATE TABLE x200121_help (
  id INT UNSIGNED AUTO_INCREMENT,
  target_user_id int not null default 0 comment '目标id',
  help_user_id int not null default 0 comment '用户id',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '助力表';

CREATE TABLE x200121_love (
  id INT UNSIGNED AUTO_INCREMENT,
  target_user_id int not null default 0 comment '目标id',
  help_user_id int not null default 0 comment '用户id',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '热力记录表';


--美的情人节
CREATE TABLE x200212_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  score int not null default 0 comment '成绩',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '用户表';


--美的业主专宠福利 [ 20200305 ]
CREATE TABLE x20200305_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid VARCHAR(36) NOT NULL DEFAULT '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(255) not null default '' comment '姓名',
  phone varchar(15) not null default '' comment '电话',
  likes int not null default 0 comment '点赞数',
  like_num int not null default 0 comment '点赞次数',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '用户表';

CREATE TABLE x20200305_like (
  id INT UNSIGNED AUTO_INCREMENT,
  target_user_id int not null default 0 comment '目标id',
  help_user_id int not null default 0 comment '用户id',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '点赞表';


--武汉院子报名 [ 20200307 ]
CREATE TABLE x200307_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(64) not null default '' comment '真实姓名',
  phone varchar(11) not null default '' comment '手机号',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '报名';


--中国中铁 [ 20200312 ]
CREATE TABLE x200312_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  status tinyint not null default 0 comment '是否答题',
  topic_num tinyint(4) NOT NULL DEFAULT '0' COMMENT '答对题数量',
  money tinyint not null default 0 comment '中奖金额',
  redpack_return_msg varchar(128) not null default '' comment '红包返回信息',
  redpack_describle text comment '红包详情',
  prize_at TIMESTAMP NULL DEFAULT NULL comment '中奖时间',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '中国中铁用户表';

--宜昌中心老带新 [ 20200325 ]
CREATE TABLE x200325_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  custom_num tinyint not null default 0 comment '邀请客户数量',
  money int not null default 0 comment '中奖总额',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '宜昌中心老带新用户表';

CREATE TABLE x200325_code (
  id int UNSIGNED AUTO_INCREMENT,
  v_code varchar(8) not null default '' comment '验证码',
  status tinyint not null default 0 comment '使用状态,0:未使用,1:已使用',
  user_id int not null default 0 comment '使用ID',
  name varchar(64) not null default '' comment '客户姓名',
  phone varchar(11) not null default '' comment '客户手机号',
  money int not null default 0 comment '中奖金额',
  redpack_return_msg varchar(128) not null default '' comment '红包返回信息',
  redpack_describle text comment '红包详情',
  prize_at TIMESTAMP NULL DEFAULT NULL comment '中奖时间',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '宜昌中心老带新验证码';

-- 金桥璟园
CREATE TABLE x200615_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(10) not null default '' comment '姓名',
  phone varchar(11) not null default '' comment '手机号',
  status tinyint not null default 0 comment '抽奖状态,0:未抽奖,1:已抽奖,2:已中奖',
  prize_num int not null default 0 comment '抽奖次数',
  share_name tinyint not null default 0 comment '分享次数',
  prize_id tinyint not null default 0 comment '奖品ID',
  prize varchar(16) not null default '' comment '奖品',
  prize_at TIMESTAMP NULL DEFAULT NULL comment '中奖时间',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '金桥璟园扭蛋机游戏';

-- 宜昌中心
CREATE TABLE x200617_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  name varchar(10) not null default '' comment '姓名',
  phone varchar(11) not null default '' comment '手机号',
  score int not null default 0 comment '成绩',
  status tinyint not null default 0 comment '抽奖状态,0:未抽奖,1:已抽奖,2:已中奖',
  prize_id tinyint not null default 0 comment '奖品ID',
  prize varchar(16) not null default '' comment '奖品',
  prized_at TIMESTAMP NULL DEFAULT NULL comment '中奖时间',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 中国中铁·世纪山水---赛龙舟游戏
CREATE TABLE x200622_user (
  id int UNSIGNED AUTO_INCREMENT,
  openid varchar(36) not null default '',
  nickname varchar(64) not null default '' comment '昵称',
  avatar varchar(255) not null default '' comment '头像',
  game_num int not null default 0 comment '游戏次数',
  share_num int not null default 0 comment '分享次数',
  score int not null default 0 comment '成绩',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE = innodb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '用户表';

