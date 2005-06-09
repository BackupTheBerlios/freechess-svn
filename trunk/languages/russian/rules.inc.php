<head>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=KOI8-R"></head>
<body bgcolor=white text=black>
<p><font face="verdana" size="2"> 
  <? require_once('header.inc.php');?>
  <table border=0><tr><td style='text-align: left'>
  <BR>
  </font><font face="verdana" size="2"><font face="Verdana" size="2"><strong>Руководство 
  по подсчету рейтинга / Информация</strong><br>
  -------------------------------------------------------------------------------- 
  <br>
  - Все игроки начинают с рейтинга 1500.<br>
  - Рейтинг игрока не может быть ниже 1300.<br>
  - Для того, чтобы игра была на счет, разница между рейтингами игроков не должна 
  быть более 300 очков.<br>
  - Рейтинг указанный в начале игры используется для подсчета выигранных или проигранных 
  очков.<br>
  - Игра, ход в которой не был сделан в течении 
  <?=$CFG_EXPIREGAME?>
  dдней будет засчитана как проигранная для игрока, чей ход является текущим. 
  - Не принятые игры будут автоматически удалены в течении 
  <?=$CFG_EXPIREGAME?>
  дней.<BR>
  - Игры, с менее чем 
  <?=$CFG_MIN_ROUNDS?>
  ходов не учитываются при подсчете рейтинга.<BR>
  <br>
  <br>
  <b>Подсчет рейтинга</b><br>
  <BR>
  Рейтинг каждого игрока подсчитывается при помощи формулы:<br>
  <br>
  XP = ( %V + Dif ) / 10 <br>
  <br>
  XP = Опыт оппонента<br>
  %V = Процент побед<br>
  Dif = Рейтинг проигравшего - Рейтинг победителя<br>
  <BR>
  <br>
  В случае если Мат:<br>
  Победитель получает: (XPp + 30) / 2<br>
  Проигравший теряет: (XPv + 30) / 2<br>
  <br>
  В случае непредвиденного окончания игры:<br>
  Оба получают: (XP + 15) / 2<br>
  <br>
  В сулчае если один из игроков сдался:<br>
  </font><font face="verdana" size="2"><font face="Verdana" size="2">Победитель 
  получает</font></font><font face="Verdana" size="2">: (XPp + 30) / 2<br>
  </font><font face="verdana" size="2"><font face="Verdana" size="2">Проигравший 
  теряет</font></font><font face="Verdana" size="2">: (XPv + 30) / 2<br>
  <br>
  XPv = Опыт победителя<br>
  XPp = Опыт проигравшего</font></font></p>
<p><font face="Verdana" size="2"> Пример:<br>
  Игрок (Рейтинг: 1400 и 50% побед)<br>
  Игрок B (Рейтинг: 1500 и 80% of побед)<br>
  Игрок B выигрывает игру и ставит Мат.<br>
  XPv = (80 - 100)/10 = -5<br>
  XPp = (50 - 100)/10 = -2<br>
  Игрок A потеряет 12 очков, а игрок B получит 14 очков.</font></p>
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
