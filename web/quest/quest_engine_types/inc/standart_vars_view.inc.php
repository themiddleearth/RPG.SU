<?PHP
$char_name='����';
$char_race='�����';
$reward=mt_rand(1,1000);
$time=time(); 

switch ($quest_type)
{
	case 1: 
		$npc_name="���� ����";//��� ������� 
		$npc_race="����� ������"; //���� �������
	break;
	
	case 2: 
		$part_name="������"; //�������� ��������� ����� �������. 
		$num=mt_rand(2,10);  //�����, ����������� ���-�� ������. 
		$par2_rus_name=", ���� �������� �� ������"; //�������� ������� ��������� � ������� ", ���� �������� �� ������". 
		$par2_value=mt_rand(10,50); //�����, �������� ������� ���������.
		$par3_rus_name=", �������� �������� �� ������";
		$par3_value=mt_rand(10,50);
		$par4_rus_name="";
		$par4_value="";
	break;
	
	case 3: 
		$exp=mt_rand(100,5000);//����������� ���-�� �����.    
	break;
	
	case 4: 
		$wins=mt_rand(5,15);//����������� ���-�� �����.  
	break;
	
	case 5: 
		$map_name="����������";// - �������� �����. 
		$rustowun="���� �����";//- �������� ������.
	break;
	
	case 601: 
		$map_name="����������";// - �������� �����.
		$x=mt_rand(0,50);
		$y=mt_rand(0,50);// - �����. ���������� �������.
	break;
	
	case 7: 
		$name="������ ����";
		//�������� ������� ��������
	break;
	
	case 801: 
		$name="����� ��� ��� ��� �������";
	break;
	
	case 802: 
		$shop_name="����";// - ��� ��������. 
		$type_id=mt_rand(1,12);// ����� ��� �������� ������� ���� � ������� "������� ������". 
		$num=mt_rand(2,6); //- ����������� ���-�� ���������.    
	break;
	
	case 803: 
		$name="���� �� 2 � �������� �� 1";			
		$num=mt_rand(2,6); //- ����������� ���-�� ���������. 
	break;
	
	case 804: 
		$wname="����������� ���"; //- �������� ������������ ������. 
		$top=mt_rand(50,80);//- �����, ������� ������� �������� ���������. 
		$bottom=mt_rand(20,40); //- �����, ����� ������� �������� ���������.
	break;
}
?>