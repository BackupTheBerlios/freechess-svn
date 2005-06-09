<head>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
</head>
<body bgcolor=white text=black>

<font face="verdana" size="2">
<? require_once('header.inc.php');?>
<table border=0><tr><td style='text-align: left'>
<BR>
<font face="Verdana" size="2"><b>RULES OF THE WEBCHESS</b><br>

--------------------------------------------------------------------------------
<br>
- All the players initiate with 1500 points. <br>
- No player will have rating lesser that 1300. <br>
- The difference enter rating of the loser and of the 300 winner it could not be bigger that nor lesser that -300.<br>
- The rating used to calculate the punctuation will be allways the rating that the both players had at the beginning of the game.<BR>
- Games that will not have no movement in the period of <?=$CFG_EXPIREGAME?> days will be considered abandoned and the player that abandoned the game in its time, will be considered the loser.
- The challenges that will not be accepted will be extinguished automatically in <?=$CFG_EXPIREGAME?> days.<BR>
- Games with less than <?=$CFG_MIN_ROUNDS?> rounds will not be considered in ranking.<BR>
<br>
<br>
<b>System of Punctuation</b><br><BR>

The Rating of each player is calculated through the formula:<br>
<br>
XP = ( %V + Dif ) / 10 <br>
<br>
XP = Experience of the Opponent<br>
%V = Percentage of victories<br>
Dif = Rating of the loser - Rating of the winner<br>
<BR>
<br>
In case of Checkmate:<br>
The winner earns: (XPp + 30) / 2<br>
The loser loses: (XPv + 30) / 2<br>
<br>
In case of tie up to:<br>
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
