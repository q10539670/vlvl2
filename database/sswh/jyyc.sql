-- 投票 用户表  [ 20200730]
CREATE TABLE x200730_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   varchar(64)  not null default '',
    avatar     varchar(255) not null default '',
    vote_num   int          not null default 0 comment '投票次数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id),
    key (openid)
) ENGINE = innodb
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

create table x200730_contestant
(
    id         INT UNSIGNED AUTO_INCREMENT,
    number     int          default 0  not null comment '编号',
    name       varchar(255) default '' not null comment '姓名',
    poll       int          default 0  not null comment '票数',
    created_at timestamp               null,
    updated_at timestamp               null,
    primary key (id)
) ENGINE = innodb
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '选手表';

create table x200730_log
(
    id         int unsigned auto_increment,
    user_id    int          default 0  not null comment '用户id',
    program_id int          default 0  not null comment '选手id',
    nickname   varchar(64)  default '' not null comment '昵称',
    avatar     varchar(255) default '' not null comment '头像',
    created_at timestamp               null,
    updated_at timestamp               null,
    primary key (id)
) ENGINE = innodb
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '记录表';


create table x200901_user
(
    id         int unsigned auto_increment,
    nickname   varchar(64)  default '' not null comment '昵称',
    avatar     varchar(255) default '' not null comment '头像',
    name       varchar(16)  default '' not null comment '姓名',
    phone      varchar(11)  default '' not null comment '电话',
    status     tinyint(1)   default 0  not null comment '状态: 0,未抽奖 1:已中奖 2:未中奖',
    prize      varchar(16)  default '' not null comment '中奖奖品',
    prize_id   tinyint(1)   default 0  not null comment '奖品ID',
    prized_at  datetime                null comment '中奖时间',
    created_at datetime                null,
    updated_at datetime                null,
    primary key (id)
) ENGINE = innodb
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 宜昌中心天宸府现金抽奖
CREATE TABLE x200928_user
(
    id                 INT UNSIGNED AUTO_INCREMENT,
    openid             VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname           VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar             VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name               VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone              VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '电话',
    status             TINYINT(2)      NOT NULL DEFAULT 0 COMMENT '状态[0:未抽奖,10:已中奖未兑奖,11:中奖已兑奖,12:中奖已过期,2:未中奖]',
    v_code             TINYINT(3)   NOT NULL DEFAULT 0 COMMENT '验证码',
    money              TINYINT(4)   NOT NULL DEFAULT '0' COMMENT '中奖金额',
    redpack_return_msg VARCHAR(128) NOT NULL DEFAULT '' COMMENT '红包返回信息',
    redpack_describle  text COMMENT '红包详情',
    prized_at          DATETIME     NULL     DEFAULT NULL,
    created_at         DATETIME     NULL     DEFAULT NULL,
    updated_at         DATETIME     NULL     DEFAULT NULL,
    PRIMARY KEY (id),
    KEY openid (openid),
    KEY status (status),
    KEY money (money)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

-- 宜昌中心
CREATE TABLE x201028_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '手机号',
    score      INT          NOT NULL DEFAULT 0 COMMENT '游戏成绩',
    help_num   INT          NOT NULL DEFAULT 0 COMMENT '助力次数',
    total      INT          NOT NULL DEFAULT 0 COMMENT '总成绩',
    created_at DATETIME     NULL     DEFAULT NULL,
    updated_at DATETIME     NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

CREATE TABLE x201028_help
(
    id             INT UNSIGNED AUTO_INCREMENT,
    target_user_id INT      NOT NULL DEFAULT 0,
    help_user_id   INT      NOT NULL DEFAULT 0,
    created_at     DATETIME NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '助力表';