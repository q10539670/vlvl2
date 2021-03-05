CREATE TABLE x201225_user
(
    id         INT UNSIGNED AUTO_INCREMENT,
    openid     VARCHAR(36)  NOT NULL DEFAULT '',
    nickname   VARCHAR(64)  NOT NULL DEFAULT '' COMMENT '昵称',
    avatar     VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
    name       VARCHAR(16)  NOT NULL DEFAULT '' COMMENT '姓名',
    mobile     VARCHAR(11)  NOT NULL DEFAULT '' COMMENT '电话',
    game_num   INT          NOT NULL DEFAULT 0 COMMENT '游戏次数',
    share_num  TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '分享次数',
    prize_id   INT(1)       NOT NULL DEFAULT 0 COMMENT '中奖ID',
    prize      VARCHAR(10)  NOT NULL DEFAULT '' COMMENT '中奖奖品',
    status     INT(1)       NOT NULL DEFAULT 0 COMMENT '状态[0:未抽奖,1:已中奖,2:未中奖,]',
    prized_at  DATETIME     NULL     DEFAULT NULL COMMENT '中奖时间',
    created_at DATETIME     NULL     DEFAULT NULL,
    updated_at DATETIME     NULL     DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB
  DEFAULT CHARACTER
      SET utf8mb4
  COLLATE utf8mb4_unicode_ci COMMENT '用户表';
