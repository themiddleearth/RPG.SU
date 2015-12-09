CREATE OR REPLACE VIEW view_map_info AS
SELECT combat.id as combid , game_shop.id as shopid , game_npc.npc_id as npcid , craft_build_user.id as userid,game_users_map.user_id,game_gorod.rustown, game_map.*
FROM game_map
LEFT JOIN combat ON 
(game_map.name=combat.map_name
AND game_map.xpos=combat.map_xpos
AND game_map.ypos=combat.map_ypos
AND combat.last_hod>=(unix_timestamp()-300)
)
LEFT JOIN game_gorod ON (
game_map.town=game_gorod.town 
)
LEFT JOIN game_shop ON (
game_map.name=game_shop.map 
AND game_map.xpos=game_shop.pos_x 
AND game_map.ypos=game_shop.pos_y 
)
LEFT JOIN game_npc ON (
game_map.name=game_npc.npc_map_name 
AND game_map.xpos=game_npc.npc_xpos 
AND game_map.ypos=game_npc.npc_ypos
AND game_npc.npc_exp<=200 AND game_npc.prizrak='0' AND game_npc.view=1 AND game_npc.npc_time+game_npc.respawn<unix_timestamp()
)
LEFT JOIN craft_build_user ON (
game_map.name=craft_build_user.map
AND game_map.xpos=craft_build_user.x
AND game_map.ypos=craft_build_user.y
)
LEFT JOIN (game_users_map,view_active_users) ON (
game_map.name=game_users_map.map_name 
AND game_map.xpos=game_users_map.map_xpos 
AND game_map.ypos=game_users_map.map_ypos
AND game_users_map.user_id = view_active_users.user_id
);