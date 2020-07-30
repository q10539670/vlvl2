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