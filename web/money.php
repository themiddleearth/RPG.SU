<?
include('inc/config.inc.php');
include('inc/template_header.inc.php');
define("img_domain","images.rpg.su");
?>
<center>
<table width="780" border="0" cellspacing="0" cellpadding="0">
		<tr>
				<td width="8" height="8"><img src="<?php echo 'http://'.img_domain.'/';?>nav/2_01.jpg" width="8" height="8"></td>
				<td width="189" background="<?php echo 'http://'.img_domain.'/';?>nav/2_02.jpg"></td>
				<td width="10"><img src="<?php echo 'http://'.img_domain.'/';?>nav/2_04.jpg" width="8" height="8"></td>
		</tr>
		<tr>
				<td background="<?php echo 'http://'.img_domain.'/';?>nav/2_05.jpg"></td>
				<td>
				<table border=1>
				<?php
				/*
				<tr>
				<td colspan=4 align="center"><h5>������</h5></td>
				</tr>
				<tr>
				<td colspan=4 align="center"><h5>� ���������, ���� �� ����� ������������ �� ���������� ����������� ���������, ������� ��� ������������� ���� �� ��������� �������� ������� �������. ������� ��� ������ ��������� � ���������� ��������� � �� ����� ���� � ���������� ���������� ����� ���������� ������ �� �������!</h5></td>
				</tr>
				<tr>
				<td width="15%" align="center">WebMoney</td>
				<td width="15%" align="center"><a href="http:\\www.webmoney.ru"><img src="http://'.img_domain.'/webmoney2.gif" border=0></td>
				<td width="35%">��������:<br><br>
				WMZ - Z355860190093<br>
				WMR - R356821382096<br>
				WME - E503307899457
				</td>
				<td width="35%" bgcolor="#000000" align="center">
				��� �������� WebMoney:
				<hr>
				<a href="https://passport.webmoney.ru/asp/certview.asp?wmid=375635682697" target=_blank>
				<IMG SRC="images/trusted8.gif" title="����� ��������� �������� ������ WM �������������� 375635682697" border="0">
				<br>
				<font size=1>��������� ��������</font></a>
				</td>
				</tr>

				<tr>
				<td width="15%" align="center">Yandex.������</td>
				<td width="15%" align="center"><a href="http:\\money.yandex.ru"><img src="images\yandex.gif" border=0></td>
				<td colspan=2 width="70%">����: 4100128679280
				</td>
				</tr>

				<tr>
				<td width="15%" align="center">����</td>
				<td width="15%" align="center"><a href="http:\\www.sbrf.ru"><img src="images\sberbank.gif" border=0></td>
				<td colspan=2 width="70%">
				<center><b>���������� ��������� ��� ������������ �������:</b></center><br>
				���������� ������� - �������� ������ ������������<br>
				���� ���������� ������� - ��������� ��� 8613 �.���������<br>
				����. ���� - 42301810275040000536<br>
				����. ���� - 30101810300000000609<br>
				��� - 049706609<br>
				�/� - 90500000646 (����������� � ���� "���������� �������")         <br>
				</td>
				</tr>

				<tr>
				<td width="15%" align="center">RuPay</td>
				<td width="15%" align="center"><a href="http:\\www.rupay.ru"><img src="images\rupay.gif" border=0></td>
				<td colspan=2 width="70%">����: RU76871700
				</td>
				</tr>


				<tr>
				<td width="15%" align="center">RuPay</td>
				<td width="15%" align="center"><a href="http:\\www.rupay.ru"><img src="images\rupay.gif" border=0></td>
				<td colspan=2 width="70%">
						<table>
						<tr><td colspan="2"  valign="center" align="center"><b>����� ���� RuPay.com ����������� ������� ���������� ���������:</b></td></tr>
						<tr><td width="70%" valign="center">
								<ol>
								<li>E-Port</li>
								<li>��������� � ������� ������</li>
								<li>�������: ������ 24 ($ ��� ���.)</li>
								<li>��. ������� ��������</li>
								<li>�������: PrivatMoney</li>
								<li>�������: ��������� � �����������</li>
								<li>�������: ���������� �������</li>
								<li>��������.������ �������</li>
								<li>WebMoney WMU</li>
								<li>��-�� �������: Ecuator</li>
								<li>��-�� �������: Fethard</li>
								<li>��-�� �������: Wire Transfer</li>
								<li>��-�� �������: ��������� � ���</li>
								<li>��-�� �������: Western Union</li>
								<li>��-�� �������: GoldMoney</li>
								<li>��-�� �������: NetPay</li>
								<li>��-�� �������: ������</li>
								<li>��-�� �������: ������ ���������</li>
								<li>��-�� �������: Finex</li>
								</ol>
						</td>
						<td valign="center" align="left">
								<table width="160" border="0" cellspacing="1"
								 cellpadding="1" bgcolor="#000000">
								<form action="http://www.rupay.ru/rupay/pay/index.php" name="pay"
								 method="POST">
								<tr>
								<td bgcolor="#eee000">
								<div align="center"><b><font color="000000">RUpay</font></b></div>
								</td>
								</tr>
								<tr>
								<td bgcolor="#ffffaa">
								<center><font color="000000">
								 ����� ������ : </font><input type="hidden" name="pay_id" value="6484">
								<input type="text" name="sum_pol" value=""><font color="000000"> ���.</font>
								<input type="hidden" name="sum_val" value="RUR">
								<input type="hidden" name="name_service" value="���������� :: ����� ��������">
								<input type="hidden" name="order_id" value="0001">
								</center>
								</td>
								</tr>
								<tr>
								<td align="center" bgcolor="#ffffaa"><input type="submit"
								 name="biutton" value=" ��������"
								 style="font-family:Verdana, Arial, sans-serif; font-size : 11px;">
								</td>
								</tr>
								</form>
								</table>
						</td></tr></table>
				</td>
				</tr>


				<tr>
				<td width="15%" align="center">e-Gold</td>
				<td width="15%" align="center"><a href="http:\\www.e-gold.com"><img src="images\e-gold.gif" border=0></td>
				<td colspan=2 width="70%">����:  2197163
				</td>
				</tr>

				<tr>
				<td colspan=4 align="center">
				<b>���� ������ ��� ������:</b><br>
				</td>
				</tr>
				*/
				?>
				<tr>
				<td>
				</td>
				<td align="center">
				<a href="http://rpg.su/" target=_blank><img src="http://<? echo img_domain;?>/bns/88x31.gif" width=88 height=31 border=0 alt="RPG.SU" title="���������� :: ����� �������� :: ������� online-����"></a>
				</td>
				<td colspan=2>
						���� ��� ����������� ���� ����, �� �� ����� ���������� ��������.
						���������� � ���� ���� ������, � �������� ��� ����.

						 ��� ��� ����� ������:<br><br>
						&lt;!-- begin RPG.SU --&gt;<br>
						&lt;a href="http://rpg.su/" target=_blank&gt;&lt;img src="http://images.rpg.su/bns/88x31.gif" width=88 height=31 border=0 alt="RPG.SU" title="���������� :: ����� �������� :: ������� online-����"&gt;&lt;/a&gt;<br>
						&lt;!-- end RPG.SU --&gt;
				</td>
				</table>
				</td>
				<td background="<?php echo 'http://'.img_domain.'/';?>nav/2_07.jpg"></td>
		</tr>
		<tr>
				<td><img src="<?php echo 'http://'.img_domain.'/';?>nav/2_10.jpg" width="8" height="8"></td>
				<td background="<?php echo 'http://'.img_domain.'/';?>nav/2_11.jpg"></td>
				<td><img src="<?php echo 'http://'.img_domain.'/';?>nav/2_13.jpg" width="8" height="8"></td>
		</tr>
</table>
</center>