ALTER TABLE `game_shop` 
ADD `others` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
ADD `others_store_current` INT( 7 ) UNSIGNED NOT NULL DEFAULT '0',
ADD `others_store_max` INT( 7 ) UNSIGNED NOT NULL DEFAULT '0';

update game_items set ref_id=0 where item_id in (302, 986, 985, 984, 978, 977, 929, 356, 569, 570, 571, 572, 573, 574, 575, 576, 577, 578, 579, 580, 581, 582, 584);
update game_items_factsheet set type=97 where id in (302, 356, 284, 271, 138, 137, 136);
CREATE VIEW t1 AS SELECT user_id, item_id, min(id) as mi, sum(count_item) as ci FROM game_items Where item_id in (302, 986, 985, 984, 978, 977, 929, 356, 569, 570, 571, 572, 573, 574, 575, 576, 577, 578, 579, 580, 581, 582, 584) and priznak=0 Group By user_id, item_id;
update game_items set count_item=(select ci from t1 where t1.item_id=game_items.item_id and t1.user_id=game_items.user_id) where game_items.item_id in (302, 986, 985, 984, 978, 977, 929, 356, 569, 570, 571, 572, 573, 574, 575, 576, 577, 578, 579, 580, 581, 582, 584) and game_items.id=(select mi from t1 where t1.item_id=game_items.item_id and t1.user_id=game_items.user_id);
update game_items set count_item=0 where game_items.item_id in (302, 986, 985, 984, 978, 977, 929, 356, 569, 570, 571, 572, 573, 574, 575, 576, 577, 578, 579, 580, 581, 582, 584) and priznak=0 and game_items.id<>(select mi from t1 where t1.item_id=game_items.item_id and t1.user_id=game_items.user_id);
delete from game_items where item_id in (302, 986, 985, 984, 978, 977, 929, 356, 569, 570, 571, 572, 573, 574, 575, 576, 577, 578, 579, 580, 581, 582, 584) and count_item=0;
drop view t1;
