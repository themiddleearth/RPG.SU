CREATE TABLE `game_users_hunter` (
`user_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL ,
`level` SMALLINT( 3 ) UNSIGNED NOT NULL DEFAULT '5',
`times` SMALLINT( 3 ) UNSIGNED NOT NULL DEFAULT '0',
`map` MEDIUMINT( 5 ) UNSIGNED NOT NULL COMMENT '18 - квест в Средиземье, 5 - в Белерианде',
UNIQUE (
`user_id` ,
`map` 
)
) ENGINE = MYISAM COMMENT = '"Помощь Трапперу"';