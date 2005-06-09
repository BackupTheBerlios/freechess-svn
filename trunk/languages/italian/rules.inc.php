<head>
<style>
TABLE   {font-size: 11px; font-family: verdana; background: #cfcfbb;}
TD      {background: white; text-align: center;}
.BOTOES {width:110; background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;}
</style>
</head>
<body bgcolor=white text=black>

<font face="verdana" size="2">
<? require_once('header.inc.php');?>
<BR>
<font face="Verdana" size="2"><b>Guida al punteggio / Informazioni</b><br>

--------------------------------------------------------------------------------
<br>
- Tutti i giocatori iniziano con un punteggio  di 1500. <br>
- Nessun giocatore avr&agrave; un punteggio minore di 1300. <br>
- Per essere calcolata nelle statistiche, la differenza di punteggio dei giocatori deve essere al massimo di 300 punti.<br>
- Il punteggio di base del calcolo sar&agrave; quello che i giocatori hanno all'inizio della partita.<BR>
- La partita in cui non si fa una mossa per <?=$CFG_EXPIREGAME?> giorni sar&agrave; considerata persa sul tempo per il giocatore che aveva la mossa.<br>
- Le sfide che non sono state accettate saranno cancellate automaticamente dopo <?=$CFG_EXPIREGAME?> giorni.<BR>
- Le partite con meno di <?=$CFG_MIN_ROUNDS?> mosse non saranno calcolate.<BR>
<br>
<br>
<b>Calcolo del punteggio</b><br><BR>

Il punteggio per ciascun giocatore &egrave; calcolato attraverso la formula:<br>
<br>
XP = ( %V + Dif ) / 10 <br>
<br>
XP = Esperienza dell'avversario<br>
%V = Percentuale di vittorie<br>
Dif = Punteggio del perdente - Punteggio del vincitore<br>
<BR>
<br>
In caso di Scaccomatto:<br>
Il vincitore guadagna: (XPp + 30) / 2<br>
Lo sconfitto perde: (XPv + 30) / 2<br>
<br>
In caso di Patta:<br>
Entrambi guadagnano: (XP + 15) / 2<br>
<br>
In caso di Abbandono:<br>
Il vincitore guadagna: (XPp + 30) / 2<br>
Lo sconfitto perde:  (XPv + 30) / 2<br>
<br>
XPv = Esperienza del vincitore <br>
XPp = Esperienza dello sconfitto</font></font><p><font face="Verdana" size="2">
Esempio:<br>
Giocatore A (Punteggio:  1400 e 50% di vittorie)<br>
Giocatore B (Punteggio:  1500 e 80% di vittorie)<br>
Il giocatore B vince per Scaccomatto.<br>
XPv = (80 - 100)/10 = -5<br>
XPp = (50 - 100)/10 = -2<br>
Il giocatore A perde 12 punti e il giocatore B guadagna 14 punti.</font></p>
<p>

<font face="verdana" size="2">
<font face="Verdana" size="2">
<br>
&nbsp;</font> </p>

<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>


</body>
