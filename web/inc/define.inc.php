<?php
define('money_weight',0);

define('close_combat',(int)mysql_result(myquery("SELECT `value` FROM game_constants WHERE name='close_combat'"),0,0));
define('close_game',(int)mysql_result(myquery("SELECT `value` FROM game_constants WHERE name='close_game'"),0,0));
define('clans_war',(int)mysql_result(myquery("SELECT `value` FROM game_constants WHERE name='clans_war'"),0,0));
define('clans_war_type',(int)mysql_result(myquery("SELECT `value` FROM game_constants WHERE name='clans_war_type'"),0,0));
define('chaos_war',(int)mysql_result(myquery("SELECT `value` FROM game_constants WHERE name='chaos_war'"),0,0));
define('add_exp_for_sklon',(int)mysql_result(myquery("SELECT `value` FROM game_constants WHERE name='add_exp_for_sklon'"),0,0));
//��� ���������� ����� ����� - clans_war=1 � chaos_war=1
//��� ���������� ����� ������ - clans_war=1 � chaos_war=0, clans_war_type=4
//��� ���������� ����� ����������� - clans_war=1 � chaos_war=0, clans_war_type=6
//��������!!! ����� ��������� ����� ����������� ��������� clans_war=0

define('vsad',20);

define('svitok_small_item_id',299);
define('svitok_sred_item_id',300);
define('svitok_big_item_id',301);
define('kolba_item_id',302);
define('propusk_item_id',356);

define('zelye_glubin_item_id',361);
define('zelye_glubin_medium_item_id',478);
define('zelye_glubin_big_item_id',754);

define('license_item_id',374);

define('shamp_item_id',405);
define('hlop_item_id',404);
define('beer_td_item_id',416);
define('beer_t_item_id',415);
define('beer_s_item_id',414);
define('ell_item_id',413);
define('berez_item_id',412);

//������ ������
define("qengine_item_type",95);

define('combat_wait_refresh',15);
define('map_sea_id',17);
define('map_coliseum',35);

define('eliksir_mogushestva_item_id',418);
define('eliksir_bodrosti_item_id',421);
define('eliksir_zorkosti_item_id',420);
define('eliksir_nevidimka_item_id',419);

define('povyazka_kamikadze',543);
define('plash_monaha',544);
define('molot_kuzn',487);

define('npc_id_boss_labirint',1056113);//template ID ���� �������� ��������� - ���� ����������
define('npc_id_olen',1056281);//template ID ���� �����

define('item_id_key_constructor',584);  //ID �������� - ���� �� ������� ��������

// ���������� ���� ����� http://localhost/sovet/index.php?showtopic=388
//NPC ID ���� �����, ������� ���������� � ������ 10 ���� �� �����������������
define('npc_id_nechto',1057072);
//id ����� ������ ������
define('id_black_map',805);
//id ������� �����
define('id_black_key',929);


//���������� �������� ��� http://localhost/sovet/index.php?showtopic=383\
//ID ���� ������� ���������
define('npc_id_mrachn_hranitel',1057201);
//ID �������� ����� ������ ���������
define('item_id_part_svitok_hranitel',986);
//ID �������� ������ ���������
define('item_id_svitok_hranitel',984);
//ID ��������-������� � �������� ����
define('id_portal_tuman',401);
//ID ����� �������� ����
define('id_map_tuman',820);
//ID ���� ������ ������
define('npc_id_gorn_troll',1057202);
//ID �������� ������
define('item_id_sunduk',983);
//ID �������� ������ ����
define('item_id_old_key',985);
//ID �������� ����� ������ �������� �������
define('item_id_part_svitok_ice_portal',977);
//ID �������� ������ �������� �������
define('item_id_svitok_ice_portal',978);
//ID �������� ������ ������� ��������
define('item_id_svitok_light_usil',982);
//ID �������� ������ �������� ��������
define('item_id_svitok_medium_usil',981);
//ID �������� ������ �������� ��������
define('item_id_svitok_hard_usil',980);
//ID �������� ������ ����������� ��������
define('item_id_svitok_absolut_usil',979);
//id ������� ������
define('id_resource_saphire',52);
//ID �� game_npc ���� ������ �������
define('id_npc_nepruha',1055814);
//ID �������� ������ ������� �������������
define('item_id_svitok_light_sopr',1084);
//ID �������� ������ �������� �������������
define('item_id_svitok_medium_sopr',1085);
//ID �������� ������ �������� �������������
define('item_id_svitok_hard_sopr',1086);
//ID �������� ������ ����������� �������������
define('item_id_svitok_absolut_sopr',1087);

?>