<?
$dirclass = "../class";
require('../inc/config.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();

echo 'Расчет средних значений показателей для уровня <input type="text" name="clevel" id="clevel" value="'.(isset($_GET['clevel']) ? $_GET['clevel'] : '0').'">&nbsp;&nbsp;&nbsp;<input type="button" value="Рассчитать" onclick="location.replace(\'?clevel=\'+document.getElementById(\'clevel\').value+\'\')"><br /><br /><br />';

if (isset($_GET['clevel']))
{
    $sel = myquery("SELECT
    COUNT(*) AS kol, 
    MIN(STR) AS min_STR, 
    AVG(STR) AS avg_STR, 
    MAX(STR) AS max_STR, 
    MIN(DEX) AS min_DEX, 
    AVG(DEX) AS avg_DEX, 
    MAX(DEX) AS max_DEX, 
    MIN(SPD) AS min_SPD, 
    AVG(SPD) AS avg_SPD, 
    MAX(SPD) AS max_SPD, 
    MIN(VIT) AS min_VIT, 
    AVG(VIT) AS avg_VIT, 
    MAX(VIT) AS max_VIT, 
    MIN(PIE) AS min_PIE, 
    AVG(PIE) AS avg_PIE, 
    MAX(PIE) AS max_PIE, 
    MIN(NTL) AS min_NTL, 
    AVG(NTL) AS avg_NTL, 
    MAX(NTL) AS max_NTL, 
    MIN(CW) AS min_CW, 
    AVG(CW) AS avg_CW, 
    MAX(CW) AS max_CW, 
    MIN(CC) AS min_CC, 
    AVG(CC) AS avg_CC, 
    MAX(CC) AS max_CC, 
    MIN(MS_KULAK) AS min_MS_KULAK, 
    AVG(MS_KULAK) AS avg_MS_KULAK, 
    MAX(MS_KULAK) AS max_MS_KULAK, 
    MIN(MS_WEAPON) AS min_MS_WEAPON, 
    AVG(MS_WEAPON) AS avg_MS_WEAPON, 
    MAX(MS_WEAPON) AS max_MS_WEAPON, 
    MIN(MS_SPEAR) AS min_MS_SPEAR, 
    AVG(MS_SPEAR) AS avg_MS_SPEAR, 
    MAX(MS_SPEAR) AS max_MS_SPEAR, 
    MIN(MS_AXE) AS min_MS_AXE, 
    AVG(MS_AXE) AS avg_MS_AXE, 
    MAX(MS_AXE) AS max_MS_AXE, 
    MIN(MS_SWORD) AS min_MS_SWORD, 
    AVG(MS_SWORD) AS avg_MS_SWORD, 
    MAX(MS_SWORD) AS max_MS_SWORD, 
    MIN(MS_VOR) AS min_MS_VOR, 
    AVG(MS_VOR) AS avg_MS_VOR, 
    MAX(MS_VOR) AS max_MS_VOR, 
    MIN(MS_LUK) AS min_MS_LUK, 
    AVG(MS_LUK) AS avg_MS_LUK, 
    MAX(MS_LUK) AS max_MS_LUK, 
    MIN(MS_LEK) AS min_MS_LEK, 
    AVG(MS_LEK) AS avg_MS_LEK, 
    MAX(MS_LEK) AS max_MS_LEK, 
    MIN(MS_KUZN) AS min_MS_KUZN, 
    AVG(MS_KUZN) AS avg_MS_KUZN, 
    MAX(MS_KUZN) AS max_MS_KUZN, 
    MIN(MS_ART) AS min_MS_ART, 
    AVG(MS_ART) AS avg_MS_ART, 
    MAX(MS_ART) AS max_MS_ART, 
    MIN(MS_PARIR) AS min_MS_PARIR, 
    AVG(MS_PARIR) AS avg_MS_PARIR, 
    MAX(MS_PARIR) AS max_MS_PARIR, 
    MIN(MS_VSADNIK) AS min_MS_VSADNIK, 
    AVG(MS_VSADNIK) AS avg_MS_VSADNIK, 
    MAX(MS_VSADNIK) AS max_MS_VSADNIK, 
    MIN(skill_war) AS min_skill_war, 
    AVG(skill_war) AS avg_skill_war, 
    MAX(skill_war) AS max_skill_war, 
    MIN(skill_music) AS min_skill_music, 
    AVG(skill_music) AS avg_skill_music, 
    MAX(skill_music) AS max_skill_music, 
    MIN(skill_cook) AS min_skill_cook, 
    AVG(skill_cook) AS avg_skill_cook, 
    MAX(skill_cook) AS max_skill_cook, 
    MIN(skill_art) AS min_skill_art, 
    AVG(skill_art) AS avg_skill_art, 
    MAX(skill_art) AS max_skill_art, 
    MIN(skill_explor) AS min_skill_explor, 
    AVG(skill_explor) AS avg_skill_explor, 
    MAX(skill_explor) AS max_skill_explor, 
    MIN(skill_craft) AS min_skill_craft, 
    AVG(skill_craft) AS avg_skill_craft, 
    MAX(skill_craft) AS max_skill_craft, 
    MIN(skill_card) AS min_skill_card, 
    AVG(skill_card) AS avg_skill_card, 
    MAX(skill_card) AS max_skill_card, 
    MIN(skill_pet) AS min_skill_pet, 
    AVG(skill_pet) AS avg_skill_pet, 
    MAX(skill_pet) AS max_skill_pet, 
    MIN(skill_uknow) AS min_skill_uknow, 
    AVG(skill_uknow) AS avg_skill_uknow, 
    MAX(skill_uknow) AS max_skill_uknow 
    FROM game_users WHERE clevel=".$_GET['clevel']." AND clan_id!=1");
    $d = mysql_fetch_assoc($sel);

    echo 'Количество игроков данного уровня: '.$d['kol'];
    echo '<table cellspacing=1 cellpadding=2 border=2 bordercolor="#000080">';
    echo '<tr><td><b>Показатель</b></td><td><b>Минимальное значение</b></td><td><b>Среднее значение</b></td><td><b>Максимальное значение</b></td></tr>';
    echo '<tr><td bgcolor="#FFC0C0">&nbsp;</td><td bgcolor="#FFC0C0">&nbsp;</td><td bgcolor="#FFC0C0">&nbsp;</td><td bgcolor="#FFC0C0">&nbsp;</td></tr>';
    echo '<tr><td><b>СИЛА</b></td><td align="right">'.$d['min_STR'].'</td><td align="right">'.$d['avg_STR'].'</td><td align="right">'.$d['max_STR'].'</td></tr>';
    echo '<tr><td><b>ЛОВКОСТЬ</b></td><td align="right">'.$d['min_PIE'].'</td><td align="right">'.$d['avg_PIE'].'</td><td align="right">'.$d['max_PIE'].'</td></tr>';
    echo '<tr><td><b>ВЫНОСЛИВОСТЬ</b></td><td align="right">'.$d['min_DEX'].'</td><td align="right">'.$d['avg_DEX'].'</td><td align="right">'.$d['max_DEX'].'</td></tr>';
    echo '<tr><td><b>ЗАЩИТА</b></td><td align="right">'.$d['min_VIT'].'</td><td align="right">'.$d['avg_VIT'].'</td><td align="right">'.$d['max_VIT'].'</td></tr>';
    echo '<tr><td><b>ИНТЕЛЛЕКТ</b></td><td align="right">'.$d['min_NTL'].'</td><td align="right">'.$d['avg_NTL'].'</td><td align="right">'.$d['max_NTL'].'</td></tr>';
    echo '<tr><td><b>МУДРОСТЬ</b></td><td align="right">'.$d['min_SPD'].'</td><td align="right">'.$d['avg_SPD'].'</td><td align="right">'.$d['max_SPD'].'</td></tr>';
    echo '<tr><td><b>ТЕКУЩИЙ ВЕС</b></td><td align="right">'.$d['min_CW'].'</td><td align="right">'.$d['avg_CW'].'</td><td align="right">'.$d['max_CW'].'</td></tr>';
    echo '<tr><td><b>МАКСИМАЛЬНЫЙ ВЕС</b></td><td align="right">'.$d['min_CC'].'</td><td align="right">'.$d['avg_CC'].'</td><td align="right">'.$d['max_CC'].'</td></tr>';
    echo '<tr><td bgcolor="#FFC0C0">&nbsp;</td><td bgcolor="#FFC0C0">&nbsp;</td><td bgcolor="#FFC0C0">&nbsp;</td><td bgcolor="#FFC0C0">&nbsp;</td></tr>';
    echo '<tr><td><b>НАВЫК "ЭКСПЕРТ АРТЕФАКТОВ"</b></td><td align="right">'.$d['min_MS_ART'].'</td><td align="right">'.$d['avg_MS_ART'].'</td><td align="right">'.$d['max_MS_ART'].'</td></tr>';
    echo '<tr><td><b>НАВЫК "МАСТЕР КУЛАЧНОГО БОЯ"</b></td><td align="right">'.$d['min_MS_KULAK'].'</td><td align="right">'.$d['avg_MS_KULAK'].'</td><td align="right">'.$d['max_MS_KULAK'].'</td></tr>';
    echo '<tr><td><b>НАВЫК "ЭКСПЕРТ ВОИНСКИХ УМЕНИЙ"</b></td><td align="right">'.$d['min_MS_WEAPON'].'</td><td align="right">'.$d['avg_MS_WEAPON'].'</td><td align="right">'.$d['max_MS_WEAPON'].'</td></tr>';
    echo '<tr><td><b>НАВЫК "МАСТЕР СТРЕЛКОВОГО ОРУЖИЯ"</b></td><td align="right">'.$d['min_MS_LUK'].'</td><td align="right">'.$d['avg_MS_LUK'].'</td><td align="right">'.$d['max_MS_LUK'].'</td></tr>';
    echo '<tr><td><b>НАВЫК "МАСТЕР РУБЯЩЕГО ОРУЖИЯ"</b></td><td align="right">'.$d['min_MS_SWORD'].'</td><td align="right">'.$d['avg_MS_SWORD'].'</td><td align="right">'.$d['max_MS_SWORD'].'</td></tr>';
    echo '<tr><td><b>НАВЫК "МАСТЕР ДРОБЯЩЕГО ОРУЖИЯ"</b></td><td align="right">'.$d['min_MS_AXE'].'</td><td align="right">'.$d['avg_MS_AXE'].'</td><td align="right">'.$d['max_MS_AXE'].'</td></tr>';
    echo '<tr><td><b>НАВЫК "МАСТЕР КОЛЮЩЕГО ОРУЖИЯ"</b></td><td align="right">'.$d['min_MS_SPEAR'].'</td><td align="right">'.$d['avg_MS_SPEAR'].'</td><td align="right">'.$d['max_MS_SPEAR'].'</td></tr>';
    echo '<tr><td><b>НАВЫК "МАСТЕР ПАРИРОВАНИЯ"</b></td><td align="right">'.$d['min_MS_PARIR'].'</td><td align="right">'.$d['avg_MS_PARIR'].'</td><td align="right">'.$d['max_MS_PARIR'].'</td></tr>';
    echo '<tr><td><b>НАВЫК "КУЗНЕЦ"</b></td><td align="right">'.$d['min_MS_KUZN'].'</td><td align="right">'.$d['avg_MS_KUZN'].'</td><td align="right">'.$d['max_MS_KUZN'].'</td></tr>';
    echo '<tr><td><b>НАВЫК "ЛЕКАРЬ"</b></td><td align="right">'.$d['min_MS_LEK'].'</td><td align="right">'.$d['avg_MS_LEK'].'</td><td align="right">'.$d['max_MS_LEK'].'</td></tr>';
    echo '<tr><td><b>НАВЫК "ВОР"</b></td><td align="right">'.$d['min_MS_VOR'].'</td><td align="right">'.$d['avg_MS_VOR'].'</td><td align="right">'.$d['max_MS_VOR'].'</td></tr>';
    echo '<tr><td><b>НАВЫК "ВСАДНИК"</b></td><td align="right">'.$d['min_MS_VSADNIK'].'</td><td align="right">'.$d['avg_MS_VSADNIK'].'</td><td align="right">'.$d['max_MS_VSADNIK'].'</td></tr>';
    echo '<tr><td bgcolor="#FFC0C0">&nbsp;</td><td bgcolor="#FFC0C0">&nbsp;</td><td bgcolor="#FFC0C0">&nbsp;</td><td bgcolor="#FFC0C0">&nbsp;</td></tr>';
    echo '<tr><td><b>МАГИЯ "ВОИН"</b></td><td align="right">'.$d['min_skill_war'].'</td><td align="right">'.$d['avg_skill_war'].'</td><td align="right">'.$d['max_skill_war'].'</td></tr>';
    echo '<tr><td><b>МАГИЯ "БАРД"</b></td><td align="right">'.$d['min_skill_music'].'</td><td align="right">'.$d['avg_skill_music'].'</td><td align="right">'.$d['max_skill_music'].'</td></tr>';
    echo '<tr><td><b>МАГИЯ "ВОЛШЕБНИК"</b></td><td align="right">'.$d['min_skill_cook'].'</td><td align="right">'.$d['avg_skill_cook'].'</td><td align="right">'.$d['max_skill_cook'].'</td></tr>';
    echo '<tr><td><b>МАГИЯ "ЛУЧНИК"</b></td><td align="right">'.$d['min_skill_art'].'</td><td align="right">'.$d['avg_skill_art'].'</td><td align="right">'.$d['max_skill_art'].'</td></tr>';
    echo '<tr><td><b>МАГИЯ "ПАЛЛАДИН"</b></td><td align="right">'.$d['min_skill_explor'].'</td><td align="right">'.$d['avg_skill_explor'].'</td><td align="right">'.$d['max_skill_explor'].'</td></tr>';
    echo '<tr><td><b>МАГИЯ "ВАРВАР"</b></td><td align="right">'.$d['min_skill_craft'].'</td><td align="right">'.$d['avg_skill_craft'].'</td><td align="right">'.$d['max_skill_craft'].'</td></tr>';
    echo '<tr><td><b>МАГИЯ "ВОР"</b></td><td align="right">'.$d['min_skill_card'].'</td><td align="right">'.$d['avg_skill_card'].'</td><td align="right">'.$d['max_skill_card'].'</td></tr>';
    echo '<tr><td><b>МАГИЯ "ДРУИД"</b></td><td align="right">'.$d['min_skill_pet'].'</td><td align="right">'.$d['avg_skill_pet'].'</td><td align="right">'.$d['max_skill_pet'].'</td></tr>';
    echo '<tr><td><b>МАГИЯ "РАЗБОЙНИК"</b></td><td align="right">'.$d['min_skill_uknow'].'</td><td align="right">'.$d['avg_skill_uknow'].'</td><td align="right">'.$d['max_skill_uknow'].'</td></tr>';
    echo '<tr><td bgcolor="#FFC0C0">&nbsp;</td><td bgcolor="#FFC0C0">&nbsp;</td><td bgcolor="#FFC0C0">&nbsp;</td><td bgcolor="#FFC0C0">&nbsp;</td></tr>';


    echo '</table>';    
}
?>