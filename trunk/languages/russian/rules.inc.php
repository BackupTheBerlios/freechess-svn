<head>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=KOI8-R"></head>
<body bgcolor=white text=black>
<p><font face="verdana" size="2"> 
  <? require_once('header.inc.php');?>
  <table border=0><tr><td style='text-align: left'>
  <BR>
  </font><font face="verdana" size="2"><font face="Verdana" size="2"><strong>����������� 
  �� �������� �������� / ����������</strong><br>
  -------------------------------------------------------------------------------- 
  <br>
  - ��� ������ �������� � �������� 1500.<br>
  - ������� ������ �� ����� ���� ���� 1300.<br>
  - ��� ����, ����� ���� ���� �� ����, ������� ����� ���������� ������� �� ������ 
  ���� ����� 300 �����.<br>
  - ������� ��������� � ������ ���� ������������ ��� �������� ���������� ��� ����������� 
  �����.<br>
  - ����, ��� � ������� �� ��� ������ � ������� 
  <?=$CFG_EXPIREGAME?>
  d���� ����� ��������� ��� ����������� ��� ������, ��� ��� �������� �������. 
  - �� �������� ���� ����� ������������� ������� � ������� 
  <?=$CFG_EXPIREGAME?>
  ����.<BR>
  - ����, � ����� ��� 
  <?=$CFG_MIN_ROUNDS?>
  ����� �� ����������� ��� �������� ��������.<BR>
  <br>
  <br>
  <b>������� ��������</b><br>
  <BR>
  ������� ������� ������ �������������� ��� ������ �������:<br>
  <br>
  XP = ( %V + Dif ) / 10 <br>
  <br>
  XP = ���� ���������<br>
  %V = ������� �����<br>
  Dif = ������� ������������ - ������� ����������<br>
  <BR>
  <br>
  � ������ ���� ���:<br>
  ���������� ��������: (XPp + 30) / 2<br>
  ����������� ������: (XPv + 30) / 2<br>
  <br>
  � ������ ��������������� ��������� ����:<br>
  ��� ��������: (XP + 15) / 2<br>
  <br>
  � ������ ���� ���� �� ������� ������:<br>
  </font><font face="verdana" size="2"><font face="Verdana" size="2">���������� 
  ��������</font></font><font face="Verdana" size="2">: (XPp + 30) / 2<br>
  </font><font face="verdana" size="2"><font face="Verdana" size="2">����������� 
  ������</font></font><font face="Verdana" size="2">: (XPv + 30) / 2<br>
  <br>
  XPv = ���� ����������<br>
  XPp = ���� ������������</font></font></p>
<p><font face="Verdana" size="2"> ������:<br>
  ����� (�������: 1400 � 50% �����)<br>
  ����� B (�������: 1500 � 80% of �����)<br>
  ����� B ���������� ���� � ������ ���.<br>
  XPv = (80 - 100)/10 = -5<br>
  XPp = (50 - 100)/10 = -2<br>
  ����� A �������� 12 �����, � ����� B ������� 14 �����.</font></p>
<p>

<font face="verdana" size="2">
<font face="Verdana" size="2">
<br>
&nbsp;</font> </p>
</td></tr></table>

<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>


</body>
