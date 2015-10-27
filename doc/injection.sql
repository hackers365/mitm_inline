
create table domain_info(
    id bigint unsigned not null auto_incremtn primary key,
    domain varchar(256) not  null default '',
    sign char(32) not null default '' unique key comment 'crc32(domain)',
    parent_id bigint unsigned not null default '0' comment '上层域名的id',
    root_id bigint unsigned not null default '0' comment '最顶层域名的id',
    ctime int unsigned not null default '0'
);

create table url_info(
    id bigint unsigned not null auto_increment primary key,
    domain_id bigint unsigned not null default '0' comment '当前域的id',
    url varchar(1024) not null default '' comment '完整的url',
    method char(4) not null default '' comment 'get or post',
    sign char(32) not null default '' unique key comment 'crc32(method:url:params)',
    is_injection tinyint not null default '0' comment '是否可注入',
    ctime int unsigned not null default '0',
    key(domain_id)
);

create table result(
    id bigint unsigned not null auto_increment primary key,
    url_id bigint unsigned not null default '0',
    type tinyint unsigned not null default '0',
    log text not null default '',
    data text not null default '',
    ctime int unsigned not null default '0',
    key(url_id)
);
