-- 美年达 start
-- 美年达 用户表  [ 20190604]
CREATE TABLE lx190604mld_user
(
    id          INT UNSIGNED AUTO_INCREMENT,
    openid      VARCHAR(36)  NOT NULL DEFAULT '',
    nickname    VARCHAR(64)  NOT NULL DEFAULT '',
    avatar      VARCHAR(255) NOT NULL DEFAULT '',
    post_num    INT          NOT NULL DEFAULT 0 COMMENT '用户提交信息次数',
    message_num INT          NOT NULL DEFAULT 0 COMMENT '用户反馈次数',
    created_at  TIMESTAMP    NULL     DEFAULT NULL,
    updated_at  TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id),
    KEY (openid)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 美年达 用户信息提交表  [ 20190604]
CREATE TABLE lx190604mld_post
(
    id           INT UNSIGNED AUTO_INCREMENT,
    user_id      INT          NOT NULL DEFAULT 0 COMMENT '',
    user_address VARCHAR(255) NOT NULL DEFAULT '',
    user_name    VARCHAR(64)  NOT NULL DEFAULT '',
    user_phone   VARCHAR(30)  NOT NULL DEFAULT '',
    user_sale    VARCHAR(64)  NOT NULL DEFAULT '',
    created_at   TIMESTAMP    NULL     DEFAULT NULL,
    updated_at   TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户信息提交表';

-- 美年达 用户反馈表  [ 20190604]
CREATE TABLE lx190604mld_message
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT       NOT NULL DEFAULT 0 COMMENT '',
    message    text,
    created_at TIMESTAMP NULL     DEFAULT NULL,
    updated_at TIMESTAMP NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户反馈表';

-- 美年达 end
-- bl190716
CREATE TABLE bl190716_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    truename   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '真实姓名',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    phone      VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '手机号',
    score      INT          NOT NULL DEFAULT 0 COMMENT '游戏成绩',
    help_num   INT          NOT NULL DEFAULT 0 COMMENT '助力次数',
    total      INT          NOT NULL DEFAULT 0 COMMENT '总成绩',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '孝感保利游戏成绩表';

CREATE TABLE bl190716_help
(
    id             INT UNSIGNED AUTO_INCREMENT,
    target_user_id INT       NOT NULL DEFAULT 0,
    help_user_id   INT       NOT NULL DEFAULT 0,
    created_at     TIMESTAMP NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '孝感保利游戏助力表';

-- 七夕搭鹊桥小游戏 [ 20190726 ]
CREATE TABLE ls190726_score
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    score      INT          NOT NULL DEFAULT 0 COMMENT '游戏成绩',
    game_num   INT          NOT NULL DEFAULT 0 COMMENT '游戏次数',
    share_num  INT          NOT NULL DEFAULT 0 COMMENT '分享次数',
    total      INT          NOT NULL DEFAULT 0 COMMENT '总成绩',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '七夕搭鹊桥游戏表';

-- 游戏分数记录
CREATE TABLE ls190726_score_log
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT       NOT NULL DEFAULT 0,
    score      INT       NOT NULL DEFAULT 0 COMMENT '游戏成绩',
    created_at TIMESTAMP NULL     DEFAULT NULL,
    updated_at TIMESTAMP NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '七夕搭鹊桥游戏成绩表';

-- 报名 [ 20190805 ]
CREATE TABLE bm190805_user
(
    id          INT UNSIGNED AUTO_INCREMENT,
    openid      VARCHAR(36)  NOT NULL DEFAULT '',
    nickname    VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar      VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    truename    VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '真实姓名',
    phone       VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '手机号',
    sex         TINYINT      NOT NULL DEFAULT 0 COMMENT '性别,0:男,1女',
    age         TINYINT      NOT NULL DEFAULT 0 COMMENT '年龄',
    cooking_age TINYINT      NOT NULL DEFAULT 0 COMMENT '厨龄',
    specialty   VARCHAR(30)  NOT NULL DEFAULT '' COMMENT '拿手菜',
    created_at  TIMESTAMP    NULL     DEFAULT NULL,
    updated_at  TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '报名';

-- 大桥吃瓜小游戏 [ 20190808 ]
CREATE TABLE lx190808_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    truename   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '真实姓名',
    phone      VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '手机号',
    score      INT          NOT NULL DEFAULT 0 COMMENT '游戏成绩',
    game_num   INT          NOT NULL DEFAULT 0 COMMENT '游戏次数 -1:无限次数',
    share_num  INT          NOT NULL DEFAULT 0 COMMENT '分享次数',
    total      INT          NOT NULL DEFAULT 0 COMMENT '总成绩',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '大桥吃瓜小游戏用户表';

-- 游戏分数记录
CREATE TABLE lx190808_score_log
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT       NOT NULL DEFAULT 0,
    score      INT       NOT NULL DEFAULT 0 COMMENT '游戏成绩',
    created_at TIMESTAMP NULL     DEFAULT NULL,
    updated_at TIMESTAMP NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '大桥吃瓜小游戏成绩表';

-- 投票页面 [ 20190822 ]
CREATE TABLE tp190822_team
(
    id         INT UNSIGNED AUTO_INCREMENT,
    number     INT          NOT NULL DEFAULT 0 COMMENT '编号',
    team_name  VARCHAR(255) NOT NULL DEFAULT '' COMMENT '参赛队伍名称',
    team_img   VARCHAR(255) NOT NULL DEFAULT '' COMMENT '参赛队伍图片',
    poll       INT          NOT NULL DEFAULT 0 COMMENT '票数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '参赛队伍表';

CREATE TABLE tp190822_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    vote_num   TINYINT      NOT NULL DEFAULT 0 COMMENT '剩余投票次数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '投票用户表';

CREATE TABLE tp190822_vote_log
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    team_id    INT          NOT NULL DEFAULT 0 COMMENT '队伍id',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '投票记录表';

-- 大桥广场舞大赛报名 [ 20190830 ]
CREATE TABLE bm190830_team
(
    id                INT UNSIGNED AUTO_INCREMENT,
    openid            VARCHAR(36)  NOT NULL DEFAULT '',
    nickname          VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar            VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    truename          VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '真实姓名',
    team_name         VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '舞团名称',
    team_peoples      INT          NOT NULL DEFAULT 0 COMMENT '舞团人数',
    team_introduction VARCHAR(255) NOT NULL DEFAULT '' COMMENT '舞团简介',
    team_age          INT          NOT NULL DEFAULT 0 COMMENT '成员均龄',
    phone             VARCHAR(15)  NOT NULL DEFAULT '' COMMENT '负责人电话',
    created_at        TIMESTAMP    NULL     DEFAULT NULL,
    updated_at        TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '大桥广场舞大赛报名';

-- 保利中秋活动 [ 20190902 ]
CREATE TABLE bt190902_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    truename   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '真实姓名',
    phone      VARCHAR(15)  NOT NULL DEFAULT '' COMMENT '电话',
    image      VARCHAR(255) NOT NULL DEFAULT '' COMMENT '作品',
    polls      INT          NOT NULL DEFAULT 0 COMMENT '票数',
    num        TINYINT      NOT NULL DEFAULT 1 COMMENT '剩余投票次数',
    share_num  TINYINT      NOT NULL DEFAULT 1 COMMENT '分享+投票次数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '保利中秋活动';

CREATE TABLE bt190902_vote_log
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    target_id  INT          NOT NULL DEFAULT 0 COMMENT '目标id',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '投票记录表';

-- 大桥舞林争霸投票页面 [ 20190905 ]
CREATE TABLE tp190905_team
(
    id                INT UNSIGNED AUTO_INCREMENT,
    number            INT          NOT NULL DEFAULT 0 COMMENT '编号',
    team_name         VARCHAR(255) NOT NULL DEFAULT '' COMMENT '参赛队伍名称',
    team_img          VARCHAR(255) NOT NULL DEFAULT '' COMMENT '参赛队伍图片',
    team_introduction VARCHAR(255) NOT NULL DEFAULT '' COMMENT '舞团简介',
    team_slogan       VARCHAR(255) NOT NULL DEFAULT '' COMMENT '舞团宣言',
    poll              INT          NOT NULL DEFAULT 0 COMMENT '票数',
    created_at        TIMESTAMP    NULL     DEFAULT NULL,
    updated_at        TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '参赛队伍表';

CREATE TABLE tp190905_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    vote_num   TINYINT      NOT NULL DEFAULT 0 COMMENT '剩余投票次数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '投票用户表';

CREATE TABLE tp190905_vote_log
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    team_id    INT          NOT NULL DEFAULT 0 COMMENT '队伍id',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '投票记录表';

-- 保利香颂投票页面 [ 20190911 ]
CREATE TABLE tp190911_users
(
    id         INT UNSIGNED AUTO_INCREMENT,
    number     INT          NOT NULL DEFAULT 0 COMMENT '编号',
    name       VARCHAR(255) NOT NULL DEFAULT '' COMMENT '姓名',
    poll       INT          NOT NULL DEFAULT 0 COMMENT '票数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '投票选手表';

CREATE TABLE tp190911_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    vote_num   TINYINT      NOT NULL DEFAULT 0 COMMENT '剩余投票次数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '投票用户表';

CREATE TABLE tp190911_vote_log
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    player_id  INT          NOT NULL DEFAULT 0 COMMENT '选手id',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '投票记录表';

-- 汉江府"你加油我买单" [ 20190916 ]
CREATE TABLE hjf190916_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    truename   VARCHAR(255) NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(15)  NOT NULL DEFAULT '' COMMENT '电话',
    gasoline   INT          NOT NULL DEFAULT 0 COMMENT '油气值',
    help_num   INT          NOT NULL DEFAULT 0 COMMENT '加油次数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

CREATE TABLE hjf190916_help
(
    id             INT UNSIGNED AUTO_INCREMENT,
    target_user_id INT          NOT NULL DEFAULT 0 COMMENT '目标id',
    help_user_id   INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    nickname       VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar         VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    created_at     TIMESTAMP    NULL     DEFAULT NULL,
    updated_at     TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '加油表';

-- 保利香颂节奏大师游戏 [ 20190920 ]
CREATE TABLE bl190920_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    truename   VARCHAR(255) NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(15)  NOT NULL DEFAULT '' COMMENT '电话',
    draw_num   TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖次数',
    help_num   INT          NOT NULL DEFAULT 0 COMMENT '助力',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

CREATE TABLE bl190920_help
(
    id             INT UNSIGNED AUTO_INCREMENT,
    target_user_id INT          NOT NULL DEFAULT 0 COMMENT '目标id',
    help_user_id   INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    nickname       VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar         VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    created_at     TIMESTAMP    NULL     DEFAULT NULL,
    updated_at     TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '助力表';

CREATE TABLE bl190920_draw
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT          NOT NULL DEFAULT 0 COMMENT '中奖id',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    truename   VARCHAR(255) NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(15)  NOT NULL DEFAULT '' COMMENT '电话',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '中奖表';

CREATE TABLE bl190920_draw_log
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT       NOT NULL DEFAULT 0 COMMENT '用户id',
    draw       TINYINT   NOT NULL DEFAULT 0 COMMENT '0:未中奖,1:中奖',
    created_at TIMESTAMP NULL     DEFAULT NULL,
    updated_at TIMESTAMP NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '抽奖记录表';

-- 电信9月份抽奖活动
CREATE TABLE dx190925_user
(
    id                   INT UNSIGNED AUTO_INCREMENT,
    openid               VARCHAR(36)  NOT NULL DEFAULT '',
    nickname             VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar               VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    phone                VARCHAR(15)  NOT NULL DEFAULT '' COMMENT '电话',
    share_code           VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '分享码',
    subscribe            TINYINT      NOT NULL DEFAULT 0 COMMENT '是否关注:1,关注,2,未关注',
    prize_num            TINYINT      NOT NULL DEFAULT 0 COMMENT '抽卡次数',
    real_share           TINYINT      NOT NULL DEFAULT 0 COMMENT '有效分享次数',
    virtual_address_str  VARCHAR(128) NOT NULL DEFAULT '' COMMENT '区域',
    virtual_address_code VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '区域编号',
    address_str          VARCHAR(128) NOT NULL DEFAULT '' COMMENT '地址',
    address_code         VARCHAR(128) NOT NULL DEFAULT '' COMMENT '地址编号',
    location             VARCHAR(128) NOT NULL DEFAULT '' COMMENT '经纬度',
    cards_num            TINYINT      NOT NULL DEFAULT 0 COMMENT '卡数量',
    status               TINYINT      NOT NULL DEFAULT 0 COMMENT '是否抽奖:1,已抽奖,2,未抽奖',
    prize                TINYINT      NOT NULL DEFAULT 0 COMMENT '奖品',
    prize_at             TIMESTAMP    NULL     DEFAULT NULL COMMENT '中奖时间',
    created_at           TIMESTAMP    NULL     DEFAULT NULL,
    updated_at           TIMESTAMP    NULL     DEFAULT NULL,
    a                    TINYINT      NOT NULL DEFAULT 0 COMMENT '卡1',
    b                    TINYINT      NOT NULL DEFAULT 0 COMMENT '卡2',
    c                    TINYINT      NOT NULL DEFAULT 0 COMMENT '卡3',
    d                    TINYINT      NOT NULL DEFAULT 0 COMMENT '卡4',
    e                    TINYINT      NOT NULL DEFAULT 0 COMMENT '卡5',
    f                    TINYINT      NOT NULL DEFAULT 0 COMMENT '卡6',
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

CREATE TABLE dx190925_help
(
    id             INT UNSIGNED AUTO_INCREMENT,
    target_user_id INT          NOT NULL DEFAULT 0 COMMENT '目标id',
    help_user_id   INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    nickname       VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar         VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    status         TINYINT      NOT NULL DEFAULT 0 COMMENT '是否有效',
    created_at     TIMESTAMP    NULL     DEFAULT NULL,
    updated_at     TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '助力表';

-- 保利抽奖活动
CREATE TABLE x190929_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    truename   VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '真实姓名',
    phone      VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '手机号码',
    prize_num  TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖次数',
    share_num  TINYINT      NOT NULL DEFAULT 0 COMMENT '分享次数',
    status     TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
    prize_id   TINYINT      NOT NULL DEFAULT 0 COMMENT '奖品ID',
    prize      VARCHAR(32)  NOT NULL DEFAULT '' COMMENT '奖品',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

CREATE TABLE x190929_draw
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT          NOT NULL DEFAULT 0 COMMENT '中奖id',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    truename   VARCHAR(255) NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(15)  NOT NULL DEFAULT '' COMMENT '电话',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '中奖表';

CREATE TABLE x190929_draw_log
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT       NOT NULL DEFAULT 0 COMMENT '用户id',
    draw       TINYINT   NOT NULL DEFAULT 0 COMMENT '0:未中奖,1:中奖',
    created_at TIMESTAMP NULL     DEFAULT NULL,
    updated_at TIMESTAMP NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '抽奖记录表';

CREATE TABLE x190929_help
(
    id             INT UNSIGNED AUTO_INCREMENT,
    target_user_id INT          NOT NULL DEFAULT 0 COMMENT '目标id',
    help_user_id   INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    nickname       VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar         VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    created_at     TIMESTAMP    NULL     DEFAULT NULL,
    updated_at     TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '助力表';

-- 保利17万现金抽奖活动
CREATE TABLE x191008_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    truename   VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '真实姓名',
    id_num     VARCHAR(18)  NOT NULL DEFAULT '' COMMENT '身份证号码',
    phone      VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '手机号码',
    projects   VARCHAR(32)  NOT NULL DEFAULT '' COMMENT '认购签约项目名',
    ticket     TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖券0:没有,1:获取,2:使用',
    status     TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
    prize      VARCHAR(32)  NOT NULL DEFAULT '' COMMENT '奖品',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

CREATE TABLE x191008_info
(
    id         INT UNSIGNED AUTO_INCREMENT,
    truename   VARCHAR(16) NOT NULL DEFAULT '' COMMENT '真实姓名',
    id_num     VARCHAR(18) NOT NULL DEFAULT '' COMMENT '身份证号码',
    phone      VARCHAR(16) NOT NULL DEFAULT '' COMMENT '手机号码',
    projects   VARCHAR(32) NOT NULL DEFAULT '' COMMENT '认购签约项目名',
    status     TINYINT     NOT NULL DEFAULT 0 COMMENT '验证状态:0,未验证1,已验证',
    created_at TIMESTAMP   NULL     DEFAULT NULL,
    updated_at TIMESTAMP   NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '验证表';

CREATE TABLE x191008_token
(
    id         INT UNSIGNED AUTO_INCREMENT,
    token      VARCHAR(12) NOT NULL DEFAULT '' COMMENT '令牌',
    status     TINYINT     NOT NULL DEFAULT 0 COMMENT '是否使用:0,未使用1,已使用',
    used_at    TIMESTAMP   NULL     DEFAULT NULL,
    created_at TIMESTAMP   NULL     DEFAULT NULL,
    updated_at TIMESTAMP   NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '令牌表';

-- 天街娃娃机
CREATE TABLE x191009_user
(
    id                INT UNSIGNED AUTO_INCREMENT,
    openid            VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname          VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar            VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    truename          VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '真实姓名',
    phone             VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '手机号码',
    ip                VARCHAR(32)  NOT NULL DEFAULT '' COMMENT 'IP地址',
    ip_status         TINYINT      NOT NULL DEFAULT 0 COMMENT 'IP状态',
    prize_num         TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖次数',
    total_num         TINYINT      NOT NULL DEFAULT 0 COMMENT '总抽奖次数',
    score             INT          NOT NULL DEFAULT 0 COMMENT '最佳成绩',
    address_str       VARCHAR(128) NOT NULL DEFAULT '' COMMENT '地址',
    address_code      VARCHAR(128) NOT NULL DEFAULT '' COMMENT '地址编号',
    status            TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
    verification_code VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '核销码',
    verification      TINYINT      NOT NULL DEFAULT 0 COMMENT '核销状态,0:未核销,1:已核销',
    prize_id          TINYINT      NOT NULL DEFAULT 0 COMMENT '奖品ID',
    prize             VARCHAR(32)  NOT NULL DEFAULT '' COMMENT '奖品',
    prize_at          TIMESTAMP    NULL     DEFAULT NULL COMMENT '中奖时间',
    created_at        TIMESTAMP    NULL     DEFAULT NULL,
    updated_at        TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

-- 金地投票
CREATE TABLE x191014_user
(
    id           INT UNSIGNED AUTO_INCREMENT,
    openid       VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname     VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar       VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    vote_num     TINYINT      NOT NULL DEFAULT 0 COMMENT '投票次数',
    status       TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
    prize_id     TINYINT      NOT NULL DEFAULT 0 COMMENT '奖品ID',
    prize        VARCHAR(32)  NOT NULL DEFAULT '' COMMENT '奖品',
    prize_code   VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '中奖码',
    prize_at     TIMESTAMP    NULL     DEFAULT NULL COMMENT '中奖时间',
    verification TINYINT      NOT NULL DEFAULT 0 COMMENT '核销状态,0:未核销,1:已核销',
    created_at   TIMESTAMP    NULL     DEFAULT NULL,
    updated_at   TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

CREATE TABLE x191014_shop
(
    id         INT UNSIGNED AUTO_INCREMENT,
    number     INT          NOT NULL DEFAULT 0 COMMENT '编号',
    team_name  VARCHAR(255) NOT NULL DEFAULT '' COMMENT '商家名称',
    team_img   VARCHAR(255) NOT NULL DEFAULT '' COMMENT '商家图片',
    poll       INT          NOT NULL DEFAULT 0 COMMENT '票数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '商家表';

CREATE TABLE x191014_vote_log
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    shop_id    INT          NOT NULL DEFAULT 0 COMMENT '商家id',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '投票记录表';

CREATE TABLE x191014_comment
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    shop_id    INT          NOT NULL DEFAULT 0 COMMENT '商家id',
    COMMENT    VARCHAR(255) NOT NULL DEFAULT '' COMMENT '评论',
    images     text         NULL     DEFAULT '' COMMENT '图片',
    rated      TINYINT      NOT NULL DEFAULT 0 COMMENT '打分',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '评论表';

-- 武汉院子 [ 20191029 ]
CREATE TABLE x191029_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '电话',
    room_num   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '房号',
    id_num     VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '身份证号',
    banquet_id TINYINT      NOT NULL DEFAULT 0 COMMENT '私宴id',
    banquet    VARCHAR(32)  NOT NULL DEFAULT '' COMMENT '私宴名称',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '武汉院子';

-- 湘中跑酷 [20191101]
CREATE TABLE x191101_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '手机号',
    game_num   TINYINT      NOT NULL DEFAULT 0 COMMENT '游戏次数',
    share_num  TINYINT      NOT NULL DEFAULT 0 COMMENT '分享次数',
    score      INT          NOT NULL DEFAULT 0 COMMENT '游戏成绩',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 湘中美的置业 [ 20191115 ]
CREATE TABLE x191115_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '电话',
    pty        VARCHAR(32)  NOT NULL DEFAULT '' COMMENT '节目类型',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '湘中美的置业报名';

-- 百事感恩节
CREATE TABLE x191122_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '真实姓名',
    phone      VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '手机号码',
    address    VARCHAR(255) NOT NULL DEFAULT '' COMMENT '邮寄地址',
    game_num   TINYINT      NOT NULL DEFAULT 0 COMMENT '游戏次数',
    score      INT          NOT NULL DEFAULT 0 COMMENT '最佳成绩',
    status     TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
    prize_id   TINYINT      NOT NULL DEFAULT 0 COMMENT '奖品ID',
    prize      VARCHAR(32)  NOT NULL DEFAULT '' COMMENT '奖品',
    prize_at   TIMESTAMP    NULL     DEFAULT NULL COMMENT '中奖时间',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

-- 长沙天街感恩节
CREATE TABLE x191125_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '真实姓名',
    phone      VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '手机号码',
    address    VARCHAR(255) NOT NULL DEFAULT '' COMMENT '邮寄地址',
    prize_num  TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖次数',
    share_num  TINYINT      NOT NULL DEFAULT 0 COMMENT '分享次数',
    status     TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
    prize_id   TINYINT      NOT NULL DEFAULT 0 COMMENT '奖品ID',
    prize      VARCHAR(32)  NOT NULL DEFAULT '' COMMENT '奖品',
    prize_at   TIMESTAMP    NULL     DEFAULT NULL COMMENT '中奖时间',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

CREATE TABLE auto_check_v3_prize
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    type       TINYINT      NOT NULL DEFAULT 0 COMMENT '活动类型1:1月1奖金池,2:1月25神秘奖',
    join_num   TINYINT      NOT NULL DEFAULT 0 COMMENT '参与活动次数',
    prize      INT          NOT NULL DEFAULT 0 COMMENT '中奖金额',
    status     TINYINT      NOT NULL DEFAULT 0 COMMENT '状态 0:未分配,1:分配未确认,2:已分配确认,3:红包发送成功,4:红包发送失败',
    prized_at  TIMESTAMP    NULL     DEFAULT NULL COMMENT '红包发送时间',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

CREATE TABLE X191202_user
(
    id           INT UNSIGNED AUTO_INCREMENT,
    openid       VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname     VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar       VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name         VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '姓名',
    card_id      VARCHAR(4)   NOT NULL DEFAULT '' COMMENT '身份证后4位',
    phone        VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '手机号',
    auth         TINYINT      NOT NULL DEFAULT 0 COMMENT '身份验证,0:未验证,1:是业主,2:不是业主',
    status       TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖状态,0:未抽奖,1:已中奖,2:未中奖',
    prize_num    INT          NOT NULL DEFAULT 0 COMMENT '抽奖次数',
    prize        VARCHAR(32)  NOT NULL DEFAULT '' COMMENT '奖品名称',
    prize_id     TINYINT      NOT NULL DEFAULT 0 COMMENT '奖品id',
    prize_code   VARCHAR(32)  NOT NULL DEFAULT '' COMMENT '奖品核销码',
    validate     TINYINT      NOT NULL DEFAULT 0 COMMENT '是否核销,0:未核销,1:已核销',
    prized_at    TIMESTAMP    NULL     DEFAULT NULL COMMENT '中奖时间',
    validated_at TIMESTAMP    NULL     DEFAULT NULL COMMENT '核销时间',
    created_at   TIMESTAMP    NULL     DEFAULT NULL,
    updated_at   TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

CREATE TABLE X191202_info
(
    id         INT UNSIGNED AUTO_INCREMENT,
    name       VARCHAR(16) NOT NULL DEFAULT '' COMMENT '姓名',
    card_id    VARCHAR(4)  NOT NULL DEFAULT '' COMMENT '身份证后4位',
    status     TINYINT     NOT NULL DEFAULT 0 COMMENT '验证状态,0:未验证,1:已验证',
    created_at TIMESTAMP   NULL     DEFAULT NULL,
    updated_at TIMESTAMP   NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '业主信息表';

-- 奥特莱斯转盘H5
CREATE TABLE x191202a_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    prize_num  TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖次数',
    share_num  TINYINT      NOT NULL DEFAULT 0 COMMENT '分享次数',
    status     TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
    prize_id   TINYINT      NOT NULL DEFAULT 0 COMMENT '奖品ID',
    prize      VARCHAR(32)  NOT NULL DEFAULT '' COMMENT '奖品',
    prize_at   TIMESTAMP    NULL     DEFAULT NULL COMMENT '中奖时间',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

-- 宜昌中心天宸府现金抽奖
CREATE TABLE x191203_user
(
    id                 INT UNSIGNED AUTO_INCREMENT,
    openid             VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname           VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar             VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name               VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone              VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '电话',
    prize_num          TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖次数',
    share_num          TINYINT      NOT NULL DEFAULT 0 COMMENT '分享次数',
    status             TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
    money              TINYINT(4)   NOT NULL DEFAULT '0' COMMENT '中奖金额',
    redpack_return_msg VARCHAR(128) NOT NULL DEFAULT '' COMMENT '红包返回信息',
    redpack_describle  text COMMENT '红包详情',
    prized_at          TIMESTAMP    NULL     DEFAULT NULL,
    created_at         TIMESTAMP    NULL     DEFAULT NULL,
    updated_at         TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id),
    KEY openid (openid),
    KEY status (status),
    KEY money (money)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

-- 宜昌中心天宸府现金红包
CREATE TABLE x191211_user
(
    id                 INT UNSIGNED AUTO_INCREMENT,
    openid             VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname           VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar             VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name               VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone              VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '电话',
    status             TINYINT      NOT NULL DEFAULT 0 COMMENT '状态,0:未领取,1:已领取',
    money              INT          NOT NULL DEFAULT 0 COMMENT '中奖金额',
    redpack_return_msg VARCHAR(128) NOT NULL DEFAULT '' COMMENT '红包返回信息',
    redpack_describle  text COMMENT '红包详情',
    prized_at          TIMESTAMP    NULL     DEFAULT NULL,
    created_at         TIMESTAMP    NULL     DEFAULT NULL,
    updated_at         TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id),
    KEY openid (openid),
    KEY status (status),
    KEY money (money)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

-- 宜昌中心天宸府台州商会
CREATE TABLE x200106_user
(
    id                 INT UNSIGNED AUTO_INCREMENT,
    openid             VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname           VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar             VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    status             TINYINT      NOT NULL DEFAULT 0 COMMENT '状态,0:未领取,1:已领取',
    round              TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖轮数,0:未开始,1:第一轮,2...',
    prize              INT          NOT NULL DEFAULT 0 COMMENT '中奖奖品',
    redpack_return_msg VARCHAR(128) NOT NULL DEFAULT '' COMMENT '红包返回信息',
    redpack_describle  text COMMENT '红包详情',
    prized_at          TIMESTAMP    NULL     DEFAULT NULL,
    created_at         TIMESTAMP    NULL     DEFAULT NULL,
    updated_at         TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id),
    KEY openid (openid),
    KEY status (status),
    KEY prize (prize)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

-- 华为
CREATE TABLE x200102a_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '电话',
    address    VARCHAR(128) NOT NULL DEFAULT '' COMMENT '地址',
    prize_num  TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖次数',
    share_num  TINYINT      NOT NULL DEFAULT 0 COMMENT '分享次数',
    status     TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
    prize_id   TINYINT      NOT NULL DEFAULT 0 COMMENT '中奖ID',
    prize      INT          NOT NULL DEFAULT 0 COMMENT '中奖奖品',
    prized_at  TIMESTAMP    NULL     DEFAULT NULL,
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id),
    KEY openid (openid),
    KEY status (status),
    KEY prize_id (prize_id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

-- 大桥
CREATE TABLE x200103_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    game_num   TINYINT      NOT NULL DEFAULT 0 COMMENT '游戏次数',
    share_num  TINYINT      NOT NULL DEFAULT 0 COMMENT '分享次数',
    score      INT          NOT NULL DEFAULT 0 COMMENT '游戏分数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

-- 百事新年
CREATE TABLE x200109_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '' COMMENT 'openid',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '电话',
    address    VARCHAR(128) NOT NULL DEFAULT '' COMMENT '地址',
    prize_num  TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖次数',
    share_num  TINYINT      NOT NULL DEFAULT 0 COMMENT '分享次数',
    status     TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖状态:0,未抽奖1,已中奖,2,未中奖',
    prize_id   TINYINT      NOT NULL DEFAULT 0 COMMENT '中奖ID',
    prize      INT          NOT NULL DEFAULT 0 COMMENT '中奖奖品',
    prized_at  TIMESTAMP    NULL     DEFAULT NULL,
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id),
    KEY openid (openid),
    KEY status (status),
    KEY prize_id (prize_id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

-- 湘中年味照片征集 [ 20200113 ]
CREATE TABLE x200113_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '真实姓名',
    phone      VARCHAR(15)  NOT NULL DEFAULT '' COMMENT '电话',
    image      VARCHAR(255) NOT NULL DEFAULT '' COMMENT '照片',
    polls      INT          NOT NULL DEFAULT 0 COMMENT '票数',
    num        TINYINT      NOT NULL DEFAULT 0 COMMENT '剩余投票次数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

CREATE TABLE x200113_vote_log
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    target_id  INT          NOT NULL DEFAULT 0 COMMENT '目标id',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '投票记录表';

-- 首创奥特莱斯掷骰子
CREATE TABLE x200114_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    seat       TINYINT      NOT NULL DEFAULT 0 COMMENT '骰子位置',
    score      INT          NOT NULL DEFAULT 0 COMMENT '分数',
    game_num   INT          NOT NULL DEFAULT 0 COMMENT '剩余次数',
    share_num  INT          NOT NULL DEFAULT 0 COMMENT '分享次数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 上传
CREATE TABLE x200120_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36) NOT NULL DEFAULT '',
    image      text        NOT NULL DEFAULT '图片',
    created_at TIMESTAMP   NULL     DEFAULT NULL,
    updated_at TIMESTAMP   NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 百事新春
CREATE TABLE x200121_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    love       INT          NOT NULL DEFAULT 0 COMMENT '热爱值',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '用户表';

CREATE TABLE x200121_help
(
    id             INT UNSIGNED AUTO_INCREMENT,
    target_user_id INT          NOT NULL DEFAULT 0 COMMENT '目标id',
    help_user_id   INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    nickname       VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar         VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    created_at     TIMESTAMP    NULL     DEFAULT NULL,
    updated_at     TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '助力表';

CREATE TABLE x200121_love
(
    id             INT UNSIGNED AUTO_INCREMENT,
    target_user_id INT       NOT NULL DEFAULT 0 COMMENT '目标id',
    help_user_id   INT       NOT NULL DEFAULT 0 COMMENT '用户id',
    created_at     TIMESTAMP NULL     DEFAULT NULL,
    updated_at     TIMESTAMP NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '热力记录表';

-- 美的情人节
CREATE TABLE x200212_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    score      INT          NOT NULL DEFAULT 0 COMMENT '成绩',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 美的业主专宠福利 [ 20200305 ]
CREATE TABLE x20200305_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(255) NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(15)  NOT NULL DEFAULT '' COMMENT '电话',
    likes      INT          NOT NULL DEFAULT 0 COMMENT '点赞数',
    like_num   INT          NOT NULL DEFAULT 0 COMMENT '点赞次数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

CREATE TABLE x20200305_like
(
    id             INT UNSIGNED AUTO_INCREMENT,
    target_user_id INT          NOT NULL DEFAULT 0 COMMENT '目标id',
    help_user_id   INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    nickname       VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar         VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    created_at     TIMESTAMP    NULL     DEFAULT NULL,
    updated_at     TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '点赞表';

-- 武汉院子报名 [ 20200307 ]
CREATE TABLE x200307_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '真实姓名',
    phone      VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '手机号',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '报名';

-- 中国中铁 [ 20200312 ]
CREATE TABLE x200312_user
(
    id                 INT UNSIGNED AUTO_INCREMENT,
    openid             VARCHAR(36)  NOT NULL DEFAULT '',
    nickname           VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar             VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    status             TINYINT      NOT NULL DEFAULT 0 COMMENT '是否答题',
    topic_num          TINYINT(4)   NOT NULL DEFAULT '0' COMMENT '答对题数量',
    money              TINYINT      NOT NULL DEFAULT 0 COMMENT '中奖金额',
    redpack_return_msg VARCHAR(128) NOT NULL DEFAULT '' COMMENT '红包返回信息',
    redpack_describle  text COMMENT '红包详情',
    prize_at           TIMESTAMP    NULL     DEFAULT NULL COMMENT '中奖时间',
    created_at         TIMESTAMP    NULL     DEFAULT NULL,
    updated_at         TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '中国中铁用户表';

-- 宜昌中心老带新 [ 20200325 ]
CREATE TABLE x200325_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    custom_num TINYINT      NOT NULL DEFAULT 0 COMMENT '邀请客户数量',
    money      INT          NOT NULL DEFAULT 0 COMMENT '中奖总额',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '宜昌中心老带新用户表';

CREATE TABLE x200325_code
(
    id                 INT UNSIGNED AUTO_INCREMENT,
    v_code             VARCHAR(8)   NOT NULL DEFAULT '' COMMENT '验证码',
    status             TINYINT      NOT NULL DEFAULT 0 COMMENT '使用状态,0:未使用,1:已使用',
    user_id            INT          NOT NULL DEFAULT 0 COMMENT '使用ID',
    name               VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '客户姓名',
    phone              VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '客户手机号',
    money              INT          NOT NULL DEFAULT 0 COMMENT '中奖金额',
    redpack_return_msg VARCHAR(128) NOT NULL DEFAULT '' COMMENT '红包返回信息',
    redpack_describle  text COMMENT '红包详情',
    prize_at           TIMESTAMP    NULL     DEFAULT NULL COMMENT '中奖时间',
    created_at         TIMESTAMP    NULL     DEFAULT NULL,
    updated_at         TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '宜昌中心老带新验证码';

-- 金桥璟园
CREATE TABLE x200615_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(10)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '手机号',
    status     TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖状态,0:未抽奖,1:已抽奖,2:已中奖',
    prize_num  INT          NOT NULL DEFAULT 0 COMMENT '抽奖次数',
    share_name TINYINT      NOT NULL DEFAULT 0 COMMENT '分享次数',
    prize_id   TINYINT      NOT NULL DEFAULT 0 COMMENT '奖品ID',
    prize      VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '奖品',
    prize_at   TIMESTAMP    NULL     DEFAULT NULL COMMENT '中奖时间',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '金桥璟园扭蛋机游戏';

-- 宜昌中心
CREATE TABLE x200617_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(10)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '手机号',
    score      INT          NOT NULL DEFAULT 0 COMMENT '成绩',
    status     TINYINT      NOT NULL DEFAULT 0 COMMENT '抽奖状态,0:未抽奖,1:已抽奖,2:已中奖',
    prize_id   TINYINT      NOT NULL DEFAULT 0 COMMENT '奖品ID',
    prize      VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '奖品',
    prized_at  TIMESTAMP    NULL     DEFAULT NULL COMMENT '中奖时间',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 中国中铁·世纪山水---赛龙舟游戏
CREATE TABLE x200622_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    game_num   INT          NOT NULL DEFAULT 0 COMMENT '游戏次数',
    share_num  INT          NOT NULL DEFAULT 0 COMMENT '分享次数',
    score      INT          NOT NULL DEFAULT 0 COMMENT '成绩',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 宜昌中心---意见箱
CREATE TABLE x200629_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

CREATE TABLE x200629_advise
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT           NOT NULL DEFAULT 0 COMMENT '用户ID',
    advise     VARCHAR(1023) NOT NULL DEFAULT '' COMMENT '建议',
    name       VARCHAR(16)   NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(11)   NOT NULL DEFAULT '' COMMENT '电话',
    created_at TIMESTAMP     NULL     DEFAULT NULL,
    updated_at TIMESTAMP     NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '建议表';

CREATE TABLE l20200701_hn_user
(
    id             INT UNSIGNED AUTO_INCREMENT,
    openid         VARCHAR(36)  NOT NULL DEFAULT '',
    nickname       VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '微信昵称',
    avatar         VARCHAR(255) NOT NULL DEFAULT '' COMMENT '微信头像',
    truename       VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone          VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '电话',
    address        VARCHAR(255) NOT NULL DEFAULT '' COMMENT '收货地址',
    img_upload_num INT          NOT NULL DEFAULT 0 COMMENT '上传总次数',
    img_pass_num   INT          NOT NULL DEFAULT 0 COMMENT '通过次数',
    game_num       INT          NOT NULL DEFAULT 0 COMMENT '剩余抽奖次数',
    prize_num      INT          NOT NULL DEFAULT 0 COMMENT '累计抽奖次数',
    bingo_num      INT          NOT NULL DEFAULT 0 COMMENT '累计中奖次数',
    created_at     datetime     NULL     DEFAULT NULL,
    updated_at     datetime     NULL     DEFAULT NULL,
    PRIMARY KEY (id),
    KEY (openid)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '红牛_用户表';

CREATE TABLE l20200701_hn_images
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    path       VARCHAR(255) NOT NULL DEFAULT '' COMMENT '物理地址',
    url        VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'url路径',
    status     TINYINT      NOT NULL DEFAULT 0 COMMENT '0：待审核 1:审核通过 2：审核不通过',
    add_num    TINYINT      NOT NULL DEFAULT 0 COMMENT '通过后增加的抽奖次数',
    content    VARCHAR(255) NOT NULL DEFAULT '' COMMENT '备注',
    created_at datetime     NULL     DEFAULT NULL,
    checked_at datetime     NULL     DEFAULT NULL COMMENT '审核时间',
    PRIMARY KEY (id),
    KEY (user_id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '红牛_小票表';

CREATE TABLE l20200701_hn_prize_log
(
    id          INT UNSIGNED AUTO_INCREMENT,
    user_id     INT          NOT NULL DEFAULT 0 COMMENT '用户id',
    result_id   INT          NOT NULL DEFAULT 0 COMMENT '抽奖结果 id',
    result_name INT          NOT NULL DEFAULT 0 COMMENT '抽奖结果名称',
    content     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '备注',
    created_at  datetime     NULL     DEFAULT NULL COMMENT '抽奖时间',
    PRIMARY KEY (id),
    KEY (user_id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8
  COLLATE utf8_general_ci COMMENT '红牛_抽奖记录表';

-- 兰州中海铂悦府 报名
CREATE TABLE x200708_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '电话',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 中国中铁·世纪山水
CREATE TABLE x200715_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    game_num   INT          NOT NULL DEFAULT 0 COMMENT '游戏次数',
    share_num  TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '分享次数',
    score      INT          NOT NULL DEFAULT 0 COMMENT '游戏分数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 宜昌中心
CREATE TABLE x200716_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    status     INT          NOT NULL DEFAULT 0 COMMENT '抽奖状态 0:未抽奖 1:已抽奖',
    prize_id   TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '中奖ID',
    prize      VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '奖品',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 大桥龙虾节猜拳
CREATE TABLE x200722_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    game_num   INT          NOT NULL DEFAULT 0 COMMENT '游戏次数',
    share_num  TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '分享次数',
    score      INT          NOT NULL DEFAULT 0 COMMENT '游戏分数',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 宜昌中心·世纪山水
CREATE TABLE x200731_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '电话',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 楚天地产 上传
CREATE TABLE x200806_user
(
    id             INT UNSIGNED AUTO_INCREMENT,
    openid         VARCHAR(36)  NOT NULL DEFAULT '',
    nickname       VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar         VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name           VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone          VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '电话',
    img_upload_num INT          NOT NULL DEFAULT 0 COMMENT '上传次数',
    created_at     TIMESTAMP    NULL     DEFAULT NULL,
    updated_at     TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

CREATE TABLE x200806_images
(
    id         INT UNSIGNED AUTO_INCREMENT,
    user_id    INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '关联用户表',
    images     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '图片',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id),
    KEY (user_id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '上传图片表';


-- 金地华中第六届纳凉音乐节 抽奖
CREATE TABLE x200817_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '姓名',
    phone      VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '电话',
    status     TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '状态',
    prize      VARCHAR(36)  NOT NULL DEFAULT '' COMMENT '奖品',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';

-- 金地华中第六届纳凉音乐节 游戏
CREATE TABLE x200818_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    score      INT(10)      NOT NULL DEFAULT 0 COMMENT '最佳游戏成绩',
    game_num   TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '游戏次数',
    ranking    INT(10)      NOT NULL DEFAULT 0 COMMENT '最佳排名',
    ranking_at DATETIME     NULL     DEFAULT NULL COMMENT '最佳排名时间',
    created_at TIMESTAMP    NULL     DEFAULT NULL,
    updated_at TIMESTAMP    NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';