 CREATE TABLE `craft_user_func` (
`user_id` INT NOT NULL ,
`func_id` TINYINT NOT NULL ,
`time_stamp` INT UNSIGNED NOT NULL ,
PRIMARY KEY ( `user_id` )
) ENGINE = InnoDB COMMENT = '����������� ��� Craft-�' ;
ALTER TABLE `craft_user_func` ADD `func_sub_id` TINYINT NOT NULL COMMENT '���� ���� �� ������������' AFTER `func_id` ;
