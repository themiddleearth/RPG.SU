<?
if (function_exists("start_debug")) start_debug();
if ($adm['bank']>=1)
{
//������� ������ ���������� , ������������ � ������� �� ����� � "������ � �����".

//$dirclass="../class";
//include('../inc/config.inc.php');
//include('../inc/lib.inc.php');
//DbConnect();
global $in,$reason,$r_name;


// ���������
//    ?opt=main&option=gp_stat&
function nav() {
// echo"<html><head>"
  //. "<title>��������� ����������.</title></head><body>"
  echo "<table border=\"1\" width=\"100%\" id=\"table1\" cellpadding=\"0\" cellspacing=\"1\">"
  . "<tr><td align=\"center\"><a href=\"?opt=main&option=gp_stat&\">����� ����������.</a></td>"
  . "<td align=\"center\"><a href=\"?opt=main&option=gp_stat&in=list_r\">�� ���� Reason.</a></td>"
  . "<td align=\"center\"><a href=\"?opt=main&option=gp_stat&in=date_r\">����� �� ������ ���.</a></td>"
  . "<td align=\"center\"><a href=\"?opt=main&option=gp_stat&in=date_all\">�� ���� Reason �� ������ ���.</a></td>"
  . "</tr></table><hr>";
}
function footer() {
//echo"<hr>�&nbsp;www.rpg.su</body></html>";
}

// ����������� ������-������-������ �� ���������.
function main() {
global $reason,$r_name,$r_type;
$sql = myquery("select * from game_users_stat_gp order by user_id");

## ������ ������� ������ ����� ����������, ��������� ������� - ������ ����.
 // ��������� ����������� ����������
$gp=0;
$gp_in=0;
$gp_out=0;
$gp_game_in=0;
$gp_game_out=0;
$gp_user_in=0;
$gp_user_out=0;
$gp_nul=0;
$gp_adm_in=0;
$gp_adm_out=0;
$gp_zeml_in=0;
$gp_zeml_out=0;
nav();
while($row = mysql_fetch_array($sql))  // ������� �� ���� �� ��������� ���� ��������.
{
$reason=$row['reason'];
r($reason);  // ��������� ���� reason � ���� ��������.
switch($r_type) {   // ������ ������� ���� ��������.
case "0": // 0- �� �������.
$gp_nul=$gp_nul+$row['gp'];
break;
case "1": // 1- ������� � ���� (������).
//if ($row['gp']<=0){   // ��� �� ����- ������ ������������� ������ �� �������� ������?
//$gp_in=$gp_in-$row['gp'];
//}else{
$gp_in=$gp_in+$row['gp'];
//}
  break;
case "2": // 2- �������� �� ���� (�� ������).
if ($row['gp']<=0){  // �������� �� ������������� ����� � �� ����� ��� �� ��� � ���� �������� ���.
 $gp_out=$gp_out+$row['gp'];
}else{
$gp_out=$gp_out-$row['gp'];
}
break;
case "3": // 3-(6) �������� ������ ���� (�� ������ ������ �������)..
$gp_game_out=$gp_game_out+$row['gp'];
break;
case "4": // 4-(7) ����� ������ ������ (� ����)
$gp_user_out=$gp_user_out+$row['gp'];
break;
case "5": // 5- ��������� ��������
// �������� ��������������� � ���������� �� ���-����
if($row['gp']>=0){
$gp_adm_in=$gp_adm_in+$row['gp']; // ���� ������
}else{
$gp_adm_out=$gp_adm_out+$row['gp'];   // �������� � ������.
}
break;
case "6": // 6-(3) ��������  ������ ���� (�� ������ ������ �������).
$gp_game_in=$gp_game_in+$row['gp'];
break;
case "7": // 7-(4) �������� ������ ������ (�� �����)
$gp_user_in=$gp_user_in+$row['gp'];

break;
break;
case "8":
// 8- �������� ������� ����� ��������.
$g_z= $row['gp'];
if ($g_z>=0){  // ������� �����.
$gp_zeml_in=$gp_zeml_in+$row['gp'];
}else{ // ������� �����.
$gp_zeml_out=$gp_zeml_out+$row['gp'];
}
break;
default:
$gp_nul=$gp_nul+$row['gp'];
break;
}
 // echo "gp=$row[gp], reason=$row[reason]=$r_name r_type=$r_type<br>"; // ���� ����� ������ �� ����.
}
echo "�� ������ ������:<br> ";
echo"<table border=\"1\" width=\"65%\" id=\"table1\" cellpadding=\"0\" cellspacing=\"1\">"
  . "<tr><td align=\"center\" width=\"5%\"><span lang=\"ru\">�</span></td>"
  . "<td align=\"center\" width=\"85%\">��� Reason</td>"
  . "<td align=\"center\" width=\"10%\"><span lang=\"ru\">�����</span></td>"
  . "</tr><tr>";
echo"<td align=\"center\" width=\"5%\">0</td>"
  . "<td align=\"center\" width=\"85%\">�� ������������ ��������</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_nul</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">1(+)</td>"
  . "<td align=\"center\" width=\"85%\">������� � ���� (�������)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">2(-)</td>"
  . "<td align=\"center\" width=\"85%\">�������� �� ���� (�� �������)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">3(-)</td>"
  . "<td align=\"center\" width=\"85%\">�������� ������ ���� (�� ������ ������ �������)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_game_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">6(+)</td>"
  . "<td align=\"center\" width=\"85%\">��������  ������ ���� (�� ������ ������ �������)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_game_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">4(-)</td>"
  . "<td align=\"center\" width=\"85%\">����� ������ ������ (� ����)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_user_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">7(+)</td>"
  . "<td align=\"center\" width=\"85%\">�������� ������ ������ (�� �����)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_user_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">8(+)</td>"
  . "<td align=\"center\" width=\"85%\">������� �����</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_zeml_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">8(-)</td>"
  . "<td align=\"center\" width=\"85%\">������� �����</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_zeml_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">5(+)</td>"
  . "<td align=\"center\" width=\"85%\">������ ���� �������</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_adm_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">5(-)</td>"
  . "<td align=\"center\" width=\"85%\">������ �������� � �������</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_adm_out</b></td></tr>";
echo"</tr></table>";
echo"<p>// ���������� � �������� ����� Reason:<br>"
  . "// 0- �� �������. ��� ������������, ������ ��� � ���� ���� reason � ������� ������ 62, �������� ������ ��� �� �����. <br>��� �� � ���� ��������� ������ ������������ ��������.</p>";

## ����� ������� ������ ����� ����������, ��������� ������� - ������ ����.
## ����� footer() ������� ����.

footer();
}

// ���������� �� reason
function reason(){
global $reason,$r_name,$gp_reason_min,$gp_reason_plus;
$sql = myquery("select * from game_users_stat_gp where reason=$reason");
$gp_reason_min  =0;
$gp_reason_plus =0;
while($row = mysql_fetch_array($sql))
{
if ($row['gp']<=0){
$gp_reason_min= $gp_reason_min+$row['gp'];
}else{
$gp_reason_plus = $gp_reason_plus+$row['gp'];
}
}
}


// ������� ���� reason ��� ������ ���������� �� ���.
function list_r(){
global $db,$reason,$r_name,$gp_reason_plus,$gp_reason_min;
$reason=1;
   nav();
$gp_r_plus= 0;
$gp_r_min= 0;
echo"<table border=\"1\" width=\"70%\" id=\"table1\" cellpadding=\"0\" cellspacing=\"1\">"
  . "<tr><td align=\"center\" width=\"5%\"><span lang=\"ru\">�</span></td>"
  . "<td align=\"left\" width=\"75%\">Reason</td>"
  . "<td align=\"center\" width=\"10%\"><span lang=\"ru\">����� � ����</span></td>"
  . "<td align=\"center\" width=\"10%\"><span lang=\"ru\">����� � �����</span></td>"
  . "</tr>"
 ."";

while($reason!=62)
{
r($reason);
reason($reason);
echo"<tr><td align=\"center\" width=\"5%\">$reason</td>"
  . "<td align=\"left\" width=\"75%\">$r_name</td>"
  . "<td align=\"center\" width=\"10%\">$gp_reason_plus</td>"
  . "<td align=\"center\" width=\"10%\">$gp_reason_min</td>"
 ."</tr>";
$gp_r_plus =$gp_r_plus +$gp_reason_plus;
$gp_r_min =$gp_r_min +$gp_reason_min;
$reason=++$reason;
}
echo"<tr><td align=\"center\" width=\"5%\">==</td>"
  . "<td align=\"left\" width=\"75%\">�����:</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_r_plus</b></td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_r_min</b></td>"
 ."</tr>";
echo"</table>";
footer();
}


## ������ ����� ���������� �� ���� reason �� ������ ����.
 function date_all(){
 global $db,$reason,$r_name,$gp_reason_plus,$gp_reason_min,$r_type;
 ##########
$reason=1;
nav();
// ������� ������� ���� � ������� �� ����,
// nav();
echo "<form action=\"\" method=\"post\" name=\"forma\"> �������� ���������� �� ����:<br>";
// ���� ����_b
echo "�&nbsp;<input type=\"text\" name=\"data_b\" value=\"";
if (isset($_REQUEST['data_e'])){
echo $_REQUEST['data_b'];
}else{
echo '0000-00-00';
}
echo "\" size=\"15\" maxsize=\"10\">";
// ���� ����_e
echo "&nbsp;��&nbsp;<input type=\"text\" name=\"data_e\" value=\"";
if (isset($_REQUEST['data_e'])){
echo $_REQUEST['data_e'];
}else{
echo '0000-00-00';
}
echo "\" size=\"15\" maxsize=\"10\"><br>";

// ���� ����� ������
echo "��� ������&nbsp;<input type=\"text\" name=\"u_name\" value=\"";
if (isset($_REQUEST['u_name']))
{
echo $_REQUEST['u_name'];
}
else
{
echo '��� ������';
}
echo "\" size=\"15\" maxsize=\"10\"><br>";

echo "<input type=\"submit\" value=\"�������� ����������\" name=\"submit\"></form>";
echo "������� ���� � ������� ����-��-��<br />��� ������ �� �����������, �� ���� ������� �� ������ �� �����, �� � ���� ����- ��� ������� ��� �������� �� ��� �� ���������.<br>�� ���� ������ ������� \"������������\", ���� ����� �� 1 ����- �� ������� ������ � (���� ����) �� (���� �� ����).<br> � ������� � 2007-11-15 �� 2007-11-15<hr>";

if (isset($_REQUEST['data_b']) AND isset($_REQUEST['data_e'])) // AND isset($_REQUEST['u_name'])
{
$data_b=$_REQUEST['data_b'];
$data_e=$_REQUEST['data_e'];
$u_name=$_REQUEST['u_name'];
// ����������� ���� ��� �������.
$date_ex_b  = explode("-",$data_b);
$date_ex_e  = explode("-",$data_e);
// �����
// $date_ex[0] = ���
// $date_ex[1] = �����
// $date_ex[2] = ����
if ($data_b!="" AND $data_e!=""){
$ex_dat_b= mktime(0,0,0,$date_ex_b[1],$date_ex_b[2],$date_ex_b[0]);
$ex_dat_e= mktime(0,0,0,$date_ex_e[1],$date_ex_e[2],$date_ex_e[0])+86399; // + �����
}else {
$ex_dat_b =0;            //  ���� ���� ������ ��� ����� ������� ��� "" �� ������ � ����� �� 0 �� ���������.
$ex_dat_e=99999999999999;
}

$gp_r_plus= 0;
$gp_r_min= 0;

echo"<table border=\"1\" width=\"70%\" id=\"table1\" cellpadding=\"0\" cellspacing=\"1\">"
  . "<tr><td align=\"center\" width=\"5%\"><span lang=\"ru\">�</span></td>"
  . "<td align=\"left\" width=\"75%\">Reason</td>"
  . "<td align=\"center\" width=\"10%\"><span lang=\"ru\">����� � ����</span></td>"
  . "<td align=\"center\" width=\"10%\"><span lang=\"ru\">����� � �����</span></td>"
  . "</tr>"
 ."";

while($reason!=62)
{
r($reason);
//reason($reason);
// �� ��� ������� � ����������
//$sql = myquery("select * from game_users_stat_gp where AND reason=$reason");
// ����� �������� ������
if($u_name!='' OR $u_name!='��� ������'){  //��� �������
$user_s = myquery("SELECT user_id FROM game_users WHERE name='$u_name'");
if (!mysql_num_rows($user_s)) $user_s = myquery("SELECT user_id FROM game_users_archive WHERE name='$u_name'");   
$mesage_1= "1";
if(mysql_num_rows($user_s)){	//��� ���� � ����
	 $user_row = mysql_fetch_array($user_s) ;
	 $user_id= $user_row['user_id'];
$mesage_1= "2";
if($data_b=='0000-00-00' OR $data_e=='0000-00-00'  OR $data_b=='' OR $data_e==''){	//���� ���
//����� ���
$mesage_1= "3";
$mesage= "������ ������ � ������ <b>$u_name</b> ID=<b>$user_id</b>,<br>������ ��� �� ������, ������� ���.";
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE user_id='$user_id' AND reason='$reason'");
}else{
$mesage_1= "4";
//����� ��� + ����
$mesage= "������ ������ � ������ <b>$u_name</b> ID=<b>$user_id</b>,<br> �� ������ ��� � <b>$data_b</b> �� <b>$data_e</b>.";
//$sql = myquery("SELECT * FROM game_users_stat_gp WHERE DATE_FORMAT(FROM_UNIXTIME( `timestamp` ),'%Y-%m-%d')>='".$_REQUEST['data_b']."' AND DATE_FORMAT(FROM_UNIXTIME( `timestamp` ),'%Y-%m-%d')<='".$_REQUEST['data_e']."' AND user_id='$user_id'");
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE timestamp >='$ex_dat_b' AND timestamp <='$ex_dat_e' AND user_id='$user_id' AND reason='$reason'");
}
}else{
$mesage_1= "5";
//������
if($data_b=='0000-00-00' OR $data_e=='0000-00-00'  OR $data_b=='' OR $data_e==''){  //���� ���
//����� ���
 $mesage= "��� � ������ ��� �� �������, �������� ��� ����������.";
$sql = myquery("SELECT * FROM game_users_stat_gp where reason='$reason'");
}else{
$mesage_1= "7";
//����� ����
$mesage= "������ ������ �� ������ ��� � <b>$data_b</b> �� <b>$data_e</b>.<br>��� �� �������, ������� ���.";
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE timestamp >='$ex_dat_b' AND timestamp <='$ex_dat_e' AND reason='$reason'");
}
}
}else{
$mesage_1= "6";
if($data_b=='0000-00-00' OR $data_e=='0000-00-00'  OR $data_b=='' OR $data_e==''){  //���� ���
//����� ���
 $mesage= "3 ��� � ������ ��� �� �������, �������� ��� ����������.";
$sql = myquery("SELECT * FROM game_users_stat_gp whwre reason='$reason'");
}else{
$mesage_1= "7";
//����� ����
$mesage= "4 ������ ������ �� ������ ��� � <b>$data_b</b> �� <b>$data_e</b>.<br>��� �� �������, ������� ���.";
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE timestamp >='$ex_dat_b' AND timestamp <='$ex_dat_e' AND reason='$reason'");
}
}
// ��������� �������� ������ 0
// �� ��� ������� � ����������
$gp_reason_min  =0;
$gp_reason_plus =0;
while($row = mysql_fetch_array($sql))
{
if ($row['gp']<=0){
$gp_reason_min= $gp_reason_min+$row['gp'];
}else{
$gp_reason_plus = $gp_reason_plus+$row['gp'];
}
}
//reason($reason);
echo"<tr><td align=\"center\" width=\"5%\">$reason</td>"
  . "<td align=\"left\" width=\"75%\">$r_name</td>"
  . "<td align=\"center\" width=\"10%\">$gp_reason_plus</td>"
  . "<td align=\"center\" width=\"10%\">$gp_reason_min</td>"
 ."</tr>";
$gp_r_plus =$gp_r_plus +$gp_reason_plus;
$gp_r_min =$gp_r_min +$gp_reason_min;
$reason=++$reason;
}
echo"<tr><td align=\"center\" width=\"5%\">==</td>"
  . "<td align=\"left\" width=\"75%\">�����:</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_r_plus</b></td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_r_min</b></td>"
 ."</tr>";
echo"</table>";
echo "$mesage<br>";
footer();

 ##///////////////////////##
 
}
}

// ����- ������
## ������ ����� ���������� �� ����� reason �� ������ ����.
function date_r(){   //
 global $db,$reason,$r_name,$r_type;

 ##########
 // ������� ������� ���� � ������� �� ����,
 nav();
echo "<form action=\"\" method=\"post\" name=\"forma\"> �������� ���������� �� ����:<br>";
// ���� ����_b
echo "�&nbsp;<input type=\"text\" name=\"data_b\" value=\"";
if (isset($_REQUEST['data_e'])){
echo $_REQUEST['data_b'];
}else{
echo '0000-00-00';
}
echo "\" size=\"15\" maxsize=\"10\">";
// ���� ����_e
echo "&nbsp;��&nbsp;<input type=\"text\" name=\"data_e\" value=\"";
if (isset($_REQUEST['data_e'])){
echo $_REQUEST['data_e'];
}else{
echo '0000-00-00';
}
echo "\" size=\"15\" maxsize=\"10\"><br>";
// ���� ����� ������
echo "��� ������&nbsp;<input type=\"text\" name=\"u_name\" value=\"";
if (isset($_REQUEST['u_name']))
{
echo $_REQUEST['u_name'];
}
else
{
echo '��� ������';
}
echo "\" size=\"15\" maxsize=\"10\"><br>";
echo "<input type=\"submit\" value=\"�������� ����������\" name=\"submit\"></form>";
echo "������� ���� � ������� ����-��-��<br />��� ������ �� �����������, �� ���� ������� �� ������ �� �����, �� � ���� ����- ��� ������� ��� �������� �� ��� �� ���������.<br>�� ���� ������ ������� \"������������\", ���� ����� �� 1 ����- �� ������� ������ � (���� ����) �� (���� �� ����).<br> � ������� � 2007-11-15 �� 2007-11-15<hr>";

if (isset($_REQUEST['data_b']) AND isset($_REQUEST['data_e'])) // AND isset($_REQUEST['u_name'])
{
$data_b=$_REQUEST['data_b'];
$data_e=$_REQUEST['data_e'];
$u_name=$_REQUEST['u_name'];
// ����������� ���� ��� �������.
$date_ex_b  = explode("-",$data_b);
$date_ex_e  = explode("-",$data_e);
// �����
// $date_ex[0] = ���
// $date_ex[1] = �����
// $date_ex[2] = ����
if ($data_b!="" AND $data_e!=""){
$ex_dat_b= mktime(0,0,0,$date_ex_b[1],$date_ex_b[2],$date_ex_b[0]);
$ex_dat_e= mktime(0,0,0,$date_ex_e[1],$date_ex_e[2],$date_ex_e[0])+86399; // + �����
}else {
$ex_dat_b =0;            //  ���� ���� ������ ��� ����� ������� ��� "" �� ������ � ����� �� 0 �� ���������.
$ex_dat_e=99999999999999;
}


//echo "��� $u_name ���� � $data_b �� $data_e<hr>";
// ����� �������� ������
if($u_name!='' OR $u_name!='��� ������'){  //��� �������
$user_s = myquery("SELECT user_id FROM game_users WHERE name='$u_name'");
if (!mysql_num_rows($user_s)) $user_s = myquery("SELECT user_id FROM game_users_archive WHERE name='$u_name'");   
$mesage_1= "1";
if(mysql_num_rows($user_s)){	//��� ���� � ����
	 $user_row = mysql_fetch_array($user_s) ;
	 $user_id= $user_row['user_id'];
$mesage_1= "2";
if($data_b=='0000-00-00' OR $data_e=='0000-00-00'  OR $data_b=='' OR $data_e==''){	//���� ���
//����� ���
$mesage_1= "3";
$mesage= "������ ������ � ������ <b>$u_name</b> ID=<b>$user_id</b>,<br>������ ��� �� ������, ������� ���.";
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE user_id='$user_id'");
}else{
$mesage_1= "4";
//����� ��� + ����
$mesage= "������ ������ � ������ <b>$u_name</b> ID=<b>$user_id</b>,<br> �� ������ ��� � <b>$data_b</b> �� <b>$data_e</b>.";
//$sql = myquery("SELECT * FROM game_users_stat_gp WHERE DATE_FORMAT(FROM_UNIXTIME( `timestamp` ),'%Y-%m-%d')>='".$_REQUEST['data_b']."' AND DATE_FORMAT(FROM_UNIXTIME( `timestamp` ),'%Y-%m-%d')<='".$_REQUEST['data_e']."' AND user_id='$user_id'");
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE timestamp >='$ex_dat_b' AND timestamp <='$ex_dat_e' AND user_id='$user_id'");
}
}else{
$mesage_1= "5";
//������
if($data_b=='0000-00-00' OR $data_e=='0000-00-00'  OR $data_b=='' OR $data_e==''){  //���� ���
//����� ���
 $mesage= "��� � ������ ��� �� �������, �������� ��� ����������.";
$sql = myquery("SELECT * FROM game_users_stat_gp");
}else{
$mesage_1= "7";
//����� ����
$mesage= "������ ������ �� ������ ��� � <b>$data_b</b> �� <b>$data_e</b>.<br>��� �� �������, ������� ���.";
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE timestamp >='$ex_dat_b' AND timestamp <='$ex_dat_e'");
}
}
}else{
$mesage_1= "6";
if($data_b=='0000-00-00' OR $data_e=='0000-00-00'  OR $data_b=='' OR $data_e==''){  //���� ���
//����� ���
 $mesage= "3 ��� � ������ ��� �� �������, �������� ��� ����������.";
$sql = myquery("SELECT * FROM game_users_stat_gp");
}else{
$mesage_1= "7";
//����� ����
$mesage= "4 ������ ������ �� ������ ��� � <b>$data_b</b> �� <b>$data_e</b>.<br>��� �� �������, ������� ���.";
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE timestamp >='$ex_dat_b' AND timestamp <='$ex_dat_e'");
}
}
// ��������� �������� ������ 0

//echo "�� ������� ������ � <b>$data_b</b> �� <b>$data_e</b><br>";
//echo "������:$mesage_1<br>"; // ����� ������� ��� ������
echo "$mesage<br>";
## ������ ������� ������ ����� ����������, ��������� ������� - ������ ����.
 // ��������� ����������� ����������
$gp=0;
$gp_in=0;
$gp_out=0;
$gp_game_in=0;
$gp_game_out=0;
$gp_user_in=0;
$gp_user_out=0;
$gp_nul=0;
$gp_adm_in=0;
$gp_adm_out=0;
$gp_zeml_in=0;
$gp_zeml_out=0;
while($row = mysql_fetch_array($sql))  // ������� �� ���� �� ��������� ���� ��������.
{
$reason=$row['reason'];
r($reason);  // ��������� ���� reason � ���� ��������.
switch($r_type) {   // ������ ������� ���� ��������.
case "0": // 0- �� �������.
$gp_nul=$gp_nul+$row['gp'];
break;
case "1": // 1- ������� � ���� (������).
//if ($row['gp']<=0){   // ��� �� ����- ������ ������������� ������ �� �������� ������?
//$gp_in=$gp_in-$row['gp'];
//}else{
$gp_in=$gp_in+$row['gp'];
//}
  break;
case "2": // 2- �������� �� ���� (�� ������).
if ($row['gp']<=0){  // �������� �� ������������� ����� � �� ����� ��� �� ��� � ���� �������� ���.
 $gp_out=$gp_out+$row['gp'];
}else{
$gp_out=$gp_out-$row['gp'];
}
break;
case "3": // 3-(6) �������� ������ ���� (�� ������ ������ �������)..
$gp_game_out=$gp_game_out+$row['gp'];
break;
case "4": // 4-(7) ����� ������ ������ (� ����)
$gp_user_out=$gp_user_out+$row['gp'];
break;
case "5": // 5- ��������� ��������
// �������� ��������������� � ���������� �� ���-����
if($row['gp']>=0){
$gp_adm_in=$gp_adm_in+$row['gp']; // ���� ������
}else{
$gp_adm_out=$gp_adm_out+$row['gp'];   // �������� � ������.
}
break;
case "6": // 6-(3) ��������  ������ ���� (�� ������ ������ �������).
$gp_game_in=$gp_game_in+$row['gp'];
break;
case "7": // 7-(4) �������� ������ ������ (�� �����)
$gp_user_in=$gp_user_in+$row['gp'];

break;
break;
case "8":
// 8- �������� ������� ����� ��������.
$g_z= $row['gp'];
if ($g_z>=0){  // ������� �����.
$gp_zeml_in=$gp_zeml_in+$row['gp'];
}else{ // ������� �����.
$gp_zeml_out=$gp_zeml_out+$row['gp'];
}
break;
default:
$gp_nul=$gp_nul+$row['gp'];
break;
}
 // echo "gp=$row[gp], reason=$row[reason]=$r_name r_type=$r_type<br>"; // ���� ����� ������ �� ����.
}
echo "�� ��������� ������:<br> ";
echo"<table border=\"1\" width=\"65%\" id=\"table1\" cellpadding=\"0\" cellspacing=\"1\">"
  . "<tr><td align=\"center\" width=\"5%\"><span lang=\"ru\">�</span></td>"
  . "<td align=\"center\" width=\"85%\">��� Reason</td>"
  . "<td align=\"center\" width=\"10%\"><span lang=\"ru\">�����</span></td>"
  . "</tr><tr>";
echo"<td align=\"center\" width=\"5%\">0</td>"
  . "<td align=\"center\" width=\"85%\">�� ������������ ��������</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_nul</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">1(+)</td>"
  . "<td align=\"center\" width=\"85%\">������� � ���� (�������)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">2(-)</td>"
  . "<td align=\"center\" width=\"85%\">�������� �� ���� (�� �������)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">3(-)</td>"
  . "<td align=\"center\" width=\"85%\">�������� ������ ���� (�� ������ ������ �������)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_game_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">6(+)</td>"
  . "<td align=\"center\" width=\"85%\">��������  ������ ���� (�� ������ ������ �������)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_game_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">4(-)</td>"
  . "<td align=\"center\" width=\"85%\">����� ������ ������ (� ����)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_user_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">7(+)</td>"
  . "<td align=\"center\" width=\"85%\">�������� ������ ������ (�� �����)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_user_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">8(+)</td>"
  . "<td align=\"center\" width=\"85%\">������� �����</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_zeml_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">8(-)</td>"
  . "<td align=\"center\" width=\"85%\">������� �����</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_zeml_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">5(+)</td>"
  . "<td align=\"center\" width=\"85%\">������ ���� �������</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_adm_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">5(-)</td>"
  . "<td align=\"center\" width=\"85%\">������ �������� � �������</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_adm_out</b></td></tr>";
echo"</tr></table>";
echo"<p>// ���������� � �������� ����� Reason:<br>"
  . "// 0- �� �������. ��� ������������, ������ ��� � ���� ���� reason � ������� ������ 62, �������� ������ ��� �� �����. <br>��� �� � ���� ��������� ������ ������������ ��������.</p>";

## ����� ������� ������ ����� ����������, ��������� ������� - ������ ����.
## ����� footer() ������� ����.

////  ����� �������� �� ����������� ������
  }else{
echo "�� ����-�� �� �������";
 }

footer();
}
## ����� ������� ����� ���������� �� ����� reason �� ������ ����.

####################################
#   ���������� ����- ����� reason  #
// �������� $r_type:
// 0- �� �������. ��� ������������.
// 1- ������� � ���� (������).
// 2- �������� �� ���� (�� ������).
// 3- �������� ������ ���� (�� ������ ������ �������).
// 4- ����� ������ ����� (� ����)
// 5- ��������� ��������
// 6- ��������  ������ ���� (�� ������ ������ �������).
// 7- �������� ������ ����� (�� �����)
// 8- �������� ������� ����� ��������.
 function r(){
 global $db,$reason,$r_name, $r_type;
 switch($reason) {

	case "1":
	 $r_name="��������� �� ������ ";
	 $r_type= 1;
   break;
	case "2":
	 $r_name="������ ��������� ����� ���������";
	 $r_type= 1;
   break;
	case "3":
	 $r_name="����� ������� ������ ��� ����������� ������ ������ �� ������ �����������";
	 $r_type= 1;
   break;
	case "4":
	 $r_name="��������� ������ ��� �����������";
	 $r_type= 1;
   break;
	case "5":
	 $r_name="������ ������ � �������";
	 $r_type= 1;
   break;
	case "6":
	 $r_name="������� ������� ������� � ������� -������ �����������";
	 $r_type= 2;
   break;
	case "7":
	 $r_name="������ �� ������������� �������� � �������� ";
	 $r_type= 2;
   break;
	case "8":
	 $r_name="������� �������� ��������";
	 $r_type= 1;
   break;
	case "9":
	 $r_name="������� �������� � ��������";
	 $r_type= 2;
   break;
	case "10":
	 $r_name="������� ��������� � ��������";
	 $r_type= 2;
   break;
	case "11":
	 $r_name="������ �������� � �������� ";
	 $r_type= 2;
   break;
	case "12":
	 $r_name="������� �������� �� �����";
	 $r_type= 3;
   break;
	case "13":
	 $r_name="������� �������� �� �����";
	 $r_type= 6;
   break;
	case "14":
	 $r_name="����� �� ����������� �������� �� �����";
	 $r_type= 2;
   break;
	case "15":
	 $r_name="������ �� ������������� ������";
	 $r_type= 2;
   break;
	case "16":
	 $r_name="������� ������";
	 $r_type= 2;
   break;
	case "17":
	 $r_name="������� ������ ";
	 $r_type= 1;
   break;
	case "18":
	 $r_name="������ �� ����������� ��������� ";
	 $r_type= 2;
   break;
	case "19":
	 $r_name="������� ����� ��� �������� ���� �� ��������� ������ ����� ���������";
	 $r_type= 1;
   break;
	case "20":
	 $r_name="��������� ������ ����� ��������� �������� ������";
	 $r_type= 5;
   break;
	case "21":
	 $r_name="������ �� ��������� ������";
	 $r_type= 1;
   break;
	case "22":
	 $r_name="������ �� �������� ����";
	 $r_type= 1;
   break;
	case "23":
	 $r_name="�������� ����� � ��������";
	 $r_type= 3;
   break;
	case "24":
	 $r_name="������� ����� � ��������";
	 $r_type= 6;
   break;
	case "25":
	 $r_name="������ �� �������� ������ ";
	 $r_type= 1;
   break;
	case "26":
	 $r_name="���������� ����� ��� ������ ������ � ��� (�������)";
	 $r_type= 2;
   break;
	case "27":
	 $r_name="������ �� ������ � ��� (�������)";
	 $r_type= 1;
   break;
	case "28":
	 $r_name="������ �� ����������� ����������";
	 $r_type= 1;
   break;
	case "29":
	 $r_name="��������� ����� ��� ��������� ������";
	 $r_type= 2; //���������, ����� ������ ��� �������� ���������.
   break;
	case "30":
	 $r_name="����� ����� �� ���� ������� ���� � ����� �� �������� ������";
	 $r_type= 4;
   break;
	case "31":
	 $r_name="������ ����� � �������� ����� � ����� � ������� ������";
	 $r_type= 7;
   break;
	case "32":
	 $r_name="����� �� �������� �������� ����� ";
	 $r_type= 2;
   break;
	case "33":
	 $r_name="������ ����� ����� �������������� ";
	 $r_type= 2;
   break;
	case "34":
	 $r_name="������ ��� ������������� ������������� � ������ � ��� � �� ������� �������� ";
	 $r_type= 2;
   break;
	case "35":
	 $r_name="�������-������� ����� ����� ����� ��������� ����� �����";
	 $r_type= 8;
   break;
	case "36":
	 $r_name="�������-������� ���� ����� ��������� ����� �����";
	 $r_type= 8;
   break;
	case "37":
	 $r_name="�������� ��� ������������� ���������� ����� ����� ";
	 $r_type= 2;
   break;
	case "38":
	 $r_name="������ ������ �� ��������� (������������ ��������)";
	 $r_type= 2;
   break;
	case "39":
	 $r_name="����� �� ����� ������ � ����";
	 $r_type= 2;
   break;
	case "40":
	 $r_name="������ ������ �� ���� ";
	 $r_type= 2;
   break;
	case "41":
	 $r_name="������ ����������� ������ �����";
	 $r_type= 2;
   break;
	case "42":
	 $r_name="������� ������ � ������� ";
	 $r_type= 2;
   break;
	case "43":
	 $r_name="������� ������ � �������";
	 $r_type= 1;
   break;
	case "44":
	 $r_name="������ �� ������� ������ � ������ ";
	 $r_type= 2;
   break;
	case "45":
	 $r_name="������� ����� �� ���������� �������� ��� ���������";
	 $r_type= 1;
   break;
	case "46":
	 $r_name="������ �������� �������� ";
	 $r_type= 2;
   break;
	case "47":
	 $r_name="������ ����� ��������������� ������ ������� ��������� �� ���������";
	 $r_type= 2;
   break;
	case "48":
	 $r_name="������� ������� �� ����� ";
	 $r_type= 3;
   break;
	case "49":
	 $r_name="������� ������� �� �����";
	 $r_type= 6;
   break;
	case "50":
	 $r_name="������ ������ ����� �� �����";
	 $r_type= 2;
   break;
	case "51":
	 $r_name="������� ������ � ������� �����";
	 $r_type= 2;
   break;
	case "52":
	 $r_name="������ �� ������������� ������ ������� ����";
	 $r_type= 2;
   break;
	case "53":
	 $r_name="������� ����� �� ���� ��� ��������� ������ �������� �� ������ ������� ����";
	 $r_type= 1;
   break;
	case "54":
	 $r_name="����� �� ���������� ������� ������� ";
	 $r_type= 2;
   break;
	case "55":
	 $r_name="������� ��� � �������";
	 $r_type= 2;
   break;
	case "56":
	 $r_name="������ �� ������� ";
	 $r_type= 1;
   break;
	case "57":
	 $r_name="������ ����� �� ���������� ����� ������� ����� ������� ���������� ";
	 $r_type= 2;
   break;
	case "58":
	 $r_name="��������� ��� ��������� ������� ��������";
	 $r_type= 1;
   break;
	case "59":
	 $r_name="��������� ����� �� ����� �������� ���������� ����������";
	 $r_type= 1;
   break;
	case "60":
	 $r_name="���������� ����� � ��������� ������ ������� ��������";
	 $r_type= 1;
   break;
	case "61":
	 $r_name="�������������� ������� ������ ��� ����������� �� ������� ���� � ����������� �������� ";
	 $r_type= 1;
   break;

default:
   $r_name="�� �������!!!";
   $r_type= 0;
   break;
}
}
####################################
#   ���������� ����- ����� reason  #






switch($in) {

case "date_all":
   date_all();
   break;

case "list_r":
   list_r();
   break;
   
 case "date_r":
   date_r();
   break;

default:
   main();
   break;
}




}

if (function_exists("save_debug")) save_debug();

?>

