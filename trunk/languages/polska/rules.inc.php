<head>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
</head>
<body bgcolor=white text=black>

<font face="verdana" size="2">
<? include 'header.inc.php'; ?>
<table border=0><tr><td style='text-align: left'>
<BR>
<font face="Verdana" size="2"><b>Rating Guidelines / Information</b><br>
--------------------------------------------------------------------------------
<br>
- Wszyscy gracze begin with a 1500 rating. <br>
- No player will have a rating less than 1300. <br>
- To be rated, ratings of players must be within 300 points of one another.<br>
- The rating used to calculate points won or lost in a game will be the rating each player has at the beginning of the game.<BR>
- Games in which no moves have been made for a period of <?=$CFG_EXPIREGAME?> days will be considered lost on time for the player on move.
- Challenges that are not accepted will be deleted automatically in <?=$CFG_EXPIREGAME?> days.<BR>
- Games with less than <?=$CFG_MIN_ROUNDS?> moves will not be rated.<BR>
<br>
<br>
<b>Rating Calculation</b><br><BR>

The Rating of each player is calculated through the formula:<br>
<br>
XP = ( %V + Dif ) / 10 <br>
<br>
XP = Experience of the Opponent<br>
%V = Percentage of victories<br>
Dif = Rating of the loser - Rating of the winner<br>
<BR>
<br>
W przypadku szachu:<br>
The winner earns: (XPp + 30) / 2<br>
The loser loses: (XPv + 30) / 2<br>
<br>
W przypadku remisu:<br>
Both earn: (XP + 15) / 2<br>
<br>
In surrender case:<br>
The winner earns: (XPp + 30) / 2<br>
The loser loses:  (XPv + 30) / 2<br>
<br>
XPv = Experience of the winner <br>
XPp = Experience of the loser</font></font><p><font face="Verdana" size="2">
Example:<br>
Player (The Rating:  1400 and 50% of victories)<br>
Player B (Rating:  1500 and 80% of victories)<br>
Player B wins the game for Checkmate.<br>
XPv = (80 - 100)/10 = -5<br>
XPp = (50 - 100)/10 = -2<br>
The player A will lose 12 points and player B will gain 14 points.</font></p>
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
