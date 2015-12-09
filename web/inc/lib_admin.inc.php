<?php
function admin_delete_user($user)
{
	$user = (int)$user;
	if ($user>0)
	{
		$name = get_user('name',$user);
		myquery("DELETE FROM arcomage_call WHERE user_id='".$user."'");
		myquery("DELETE FROM arcomage_history WHERE user_id='".$user."'");
		myquery("DELETE FROM arcomage_users WHERE user_id='".$user."'");
		myquery("DELETE FROM arcomage_users_cards WHERE user_id='".$user."'");
		
        myquery("DELETE FROM blog_closed WHERE user_id='".$user."' OR close_id='".$user."'");		
		myquery("DELETE FROM blog_friends WHERE user_id='".$user."' OR friend_id='".$user."'");		
		myquery("DELETE FROM blog_love WHERE user_id='".$user."' OR friend_id='".$user."'");		
		myquery("DELETE FROM blog_comm WHERE post_id in (select post_id FROM blog_post WHERE user_id='".$user."')");
		myquery("DELETE FROM blog_post WHERE user_id='".$user."'");
		myquery("DELETE FROM blog_rating WHERE user_id='".$user."'");
		myquery("DELETE FROM blog_users WHERE user_id='".$user."'");
		myquery("UPDATE blog_comm SET user_id=0 WHERE user_id='".$user."'");
		
        myquery("DELETE FROM combat_actions WHERE user_id='".$user."'");		
		myquery("DELETE FROM combat_lose_user WHERE user_id='".$user."'");		
		myquery("DELETE FROM combat_users WHERE user_id='".$user."'");
		myquery("DELETE FROM combat_users_exp WHERE user_id='".$user."' or prot_id='".$user."'");
		myquery("DELETE FROM combat_users_state WHERE user_id='".$user."'");
		
        myquery("DELETE FROM craft_build_rab WHERE user_id='".$user."'");
		myquery("DELETE FROM craft_build_user WHERE user_id='".$user."'");
        myquery("DELETE FROM craft_build_founder WHERE user_id='".$user."'");
        myquery("UPDATE craft_build_lumberjack SET user_id=0 WHERE user_id='".$user."'");
        myquery("UPDATE craft_build_mining SET user_id=0 WHERE user_id='".$user."'");
        myquery("UPDATE craft_build_stonemason SET user_id=0 WHERE user_id='".$user."'");
		myquery("DELETE FROM craft_resource_market WHERE user_id='".$user."'");
		myquery("DELETE FROM craft_resource_user WHERE user_id='".$user."'");
        myquery("DELETE FROM craft_stat WHERE user='".$user."'");
		myquery("DELETE FROM craft_user_func WHERE user_id='".$user."'");
		
        myquery("DELETE FROM dungeon_quests_done WHERE user_id='".$user."'");
		myquery("DELETE FROM dungeon_users_data WHERE user_id='".$user."'");
		myquery("DELETE FROM dungeon_users_progress WHERE user_id='".$user."'");
		
        myquery("DELETE FROM forum_read WHERE user_id='".$user."'");
        myquery("DELETE FROM forum_setup WHERE user_id='".$user."'");
        myquery("DELETE FROM forum_thanks WHERE user_id='".$user."'");
		$topic_check=myquery("SELECT id FROM forum_topics WHERE top like 'ПСЖ (удалить По Собственному Желанию)'");
		while (list($topic_id)=mysql_fetch_array($topic_check))
		{
			myquery("DELETE FROM forum_otv WHERE user_id='".$user."' AND text like 'ПСЖ' AND topics_id='".$topic_id."' ");
		}
		
		myquery("UPDATE game_tavern SET vladel=612 WHERE vladel='".$user."'");			
        myquery("DELETE FROM game_activity WHERE name='".$name."'");
		myquery("DELETE FROM game_activity_mult WHERE name='".$name."'");
		myquery("DELETE FROM game_ban WHERE user_id='".$user."'");
		myquery("DELETE FROM game_bank WHERE user_id='".$user."'");
		myquery("DELETE FROM game_bank_db_kr WHERE user_id='".$user."'");
		myquery("DELETE FROM game_chat_ignore WHERE user_id='".$user."' or ignore_id='".$user."'");
		myquery("DELETE FROM game_chat_log WHERE user_id='".$user."'");
		myquery("DELETE FROM game_chat_nakaz WHERE user_id='".$user."'");
		myquery("DELETE FROM game_chat_option WHERE user_id='".$user."'");
		myquery("DELETE FROM game_combats_users WHERE user_id='".$user."'");
		myquery("DELETE FROM game_gift WHERE user_to='".$user."'");
		myquery("DELETE FROM game_invite WHERE user_id='".$user."'");
		myquery("DELETE FROM game_items_opis WHERE item_id in (select id FROM game_items WHERE user_id='".$user."')");
		myquery("DELETE FROM game_items WHERE user_id='".$user."'");
		myquery("DELETE FROM quest_constructor WHERE user_id='".$user."'");
		myquery("DELETE FROM game_mag WHERE name='".$name."'");
		myquery("DELETE FROM game_medal_users WHERE user_id='".$user."'");
		myquery("DELETE FROM game_nakaz WHERE user_id='".$user."'");
        myquery("DELETE FROM game_npc WHERE for_user_id='".$user."'");
		myquery("DELETE FROM game_npc_guild_log WHERE user_id='".$user."'");
		myquery("DELETE FROM game_obelisk_users WHERE user_id='".$user."'");
		myquery("DELETE FROM game_pm WHERE komu='".$user."'");
		myquery("DELETE FROM game_pm WHERE otkogo='".$user."'");
		myquery("DELETE FROM game_pm_deleted WHERE komu='".$user."'");
		myquery("DELETE FROM game_pm_deleted WHERE otkogo='".$user."'");
		myquery("DELETE FROM game_pm_folder WHERE user_id='".$user."'");
		myquery("DELETE FROM game_port_bil WHERE user_id='".$user."'");
		myquery("DELETE FROM game_prison WHERE user_id='".$user."'");
		myquery("DELETE FROM game_quest_users WHERE user_id='".$user."'");
		myquery("DELETE FROM game_stats_timemarker WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_skills WHERE user_id='".$user."'");
        myquery("DELETE FROM game_turnir_users WHERE user_id='".$user."'");		
		myquery("DELETE FROM game_users_active WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_active_delay WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_active_host WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_brak WHERE user1='".$user."'");
		myquery("DELETE FROM game_users_brak WHERE user2='".$user."'");
        myquery("DELETE FROM game_users_crafts WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_guild WHERE user_id=".$user."");
		myquery("DELETE FROM game_users_clan_reg WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_data WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_event WHERE user_id='".$user."'");
        myquery("DELETE FROM game_users_horses WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_func WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_intro WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_map WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_maze WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_npc WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_stat_exp WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_stat_gp WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_songs WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_reincarnation WHERE user_id='".$user."'");
		myquery("UPDATE game_items SET kleymo=0,kleymo_nomer=0,kleymo_id=0 WHERE kleymo=2 and kleymo_id=".$user."");
        myquery("DELETE FROM game_lr_services_hist WHERE user_id=".$user."");
		myquery("DELETE FROM game_users_hunter WHERE user_id=".$user."");
		myquery("DELETE FROM game_users_psg WHERE user_id='".$user."'");
		
        myquery("DELETE FROM houses_market WHERE user_id=".$user."");
        myquery("DELETE FROM houses_nalog WHERE user_id=".$user."");
        myquery("DELETE FROM houses_users WHERE user_id=".$user."");
		
		myquery("DELETE FROM game_admins WHERE user_id=".$user."");
		myquery("DELETE FROM game_admins_ip WHERE user_id=".$user."");
        
		myquery("DELETE FROM game_users WHERE user_id=".$user."");
		myquery("DELETE FROM game_users_archive WHERE user_id=".$user."");
	}
	
	return 1;
}
?>