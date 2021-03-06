<head>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
</head>
<body bgcolor=white text=black>
<font face="verdana" size="2">
<? require_once('header.inc.php');?>


--------------------------------------------------------------------------------
<table border="1"><tr><td><div width="100%" align="left">
<BR><font face="Verdana" size="2"><center><b>DAS HILFESYSTEM AUF PSM SCHACH</b><br><font face="Verdana" size="1">Version 0.1 vom 19.12.2004<br></font>

</center><br>
<font face="Verdana" size="2">

<br>
<b>Grundlagen</b><br>

<li><a name="oben"><a href="#allgemein">Allgemeine Regeln</a></li>
<li><a name="obenb1"><a href="#zugrueck">Zugr�ckgabe</a></li>
<li>Turniere</li>
<li><a name="oben"><a href="#pt">Preisturniere</a></li>

<br>
<b>Neue Partie erstellen</b><br>
<li><a name="obena1"><a href="#standard">Regul�re Partien</a></li>
<li><a name="obena1"><a href="#blitz">Blitzpartien</a></li>


<br>
<b>Einstellungen</b><br>
<li><a name="oben"><a href="#pdaten">Pers�nliche Daten �ndern</a></li>
<li>Design �ndern</li>
<li><a name="oben"><a href="#figuren">Spielfiguren �ndern</a></li>
<li><a name="oben"><a href="#lz">Letzten Zug anzeigen</a></li>
<li><a name="oben"><a href="#email">E-Mail Benachrichtigung</a></li>
<li><a name="oben"><a href="#pn">Nachricht an Mitspieler</a></li>
<li>Statistik</li>

<br>
<b>Berechnung der Rangliste auf PSM Schach</b><br>
<li><a name="oben1"><a href="#elo">Allgemeines zum Elosystem</a><br>
<li><a name="oben2"><a href="#eloberechnung">Berechnung der Elozahlen</a><br>
<li><a name="oben3"><a href="#medaillen">Medaillen</a></li>

<br>
<b>Gruppen</b><br>
<li>Gruppe anlegen</li>
<li>Gruppe beitreten</li>
<li>Schach-AG einrichten</li>
<li><a name="oben"><a href="#leitung">Leitung Schach-AG</a></li>


<br>
<b>Training auf PSM Schach</b><br>
<li><a name="oben"><a href="#anleitung">Anleitung</a></li>


<br>
<b>Sonstiges</b><br>
<li><a name="oben"><a href="#kommentieren">Partien kommentieren</a></li>
<li><a name="oben"><a href="#export">PGN File speichern</a></li>
<li>Forum</li>
<li>Chat</li>
<li>Journal</li>
<li><a name="oben"><a href="#datenschutz">Datenschutz</a></li>
<li><a name="oben"><a href="#account">Account l�schen</a></li>




<br><br>
<hr>

<a name="allgemein"></a>
<b>ALLGEMEINE REGELN</b>
<br>
- Auf PSM Schach gelten die allgemeinen <a href="http://www.ilo.de/schachkreis/fidereg.htm" target="_blank">Schachregeln</a>. Sollten aus programmtechnischen Gr�nden Abweichungen notwendig werden, entscheidet der zust�ndige <a href="mailto:schach@zum.de">Webmaster.</a><br>
- Alle Spieler beginnen mit einer Bewertung von 1500 Punkten. <br>
- Herausforderungen, die nicht angenommen wurden, werden automatisch nach <?=$CFG_EXPIREGAME?> Tagen gel�scht.<BR>
- Spiele mit weniger als <?=$CFG_MIN_ROUNDS?> Z�gen werden nicht gewertet.<BR>
- Auf PSM Schach gelten die �blichen Schachregeln! Diese Plattform bietet eine Reihe von Funktionen, dennoch sollten Sie ber�cksichtigen, dass sich das Programm in der Entwicklung befindet; es handelt sich um Opensource-Software, die von interessierten Spielern in ihrer Freizeit entwickelt wird. Wenn Sie sich daran beteiligen m�chten, klicken Sie <a href="http://compwebchess.com/forums/" target="_blank">hier</a>!<br>
- Alle Spieler sind angehalten, gegen�ber Mitspielern den guten Ton zu wahren. Beleidigungen, Beschimpfungen sowie obsz�ne, vulg�re, rassistische oder sexistische �u�erungen sind strengstens untersagt und f�hren zum sofortigen Ausschluss des Spielers sowie der Sperrung seines Accounts.<br>
- Bitte versuchen Sie, nicht mehr als 50 Partien zu starten. <br>
- Der Webmaster verpflichtet sich, keine pers�nlichen Daten von Spielern an Dritte weiterzugeben.

<br>
<p><a href="#oben">nach oben</a></p>
<br>
<hr>


<a name="zugrueck"></a>
<b>ZUGR�CKGABE</b>
<br><br>
- Partien, in denen w�hrend <?=$CFG_EXPIREGAME?> Tagen kein Zug erfolgte, werden automatisch f�r den ziehenden Spieler als "Verloren wegen
Zeit�berschreitung" gewertet. <br>
- Die standardm��ige Zugr�ckgabe von <?=$CFG_EXPIREGAME?> Tagen kann in regul�ren sowie in Turnierspielen mithilfe eines Men�s tageweise reduziert werden.  

<br>
<br><center><img src="images/help/zugrrueck2.JPG"></center>
<br>

- Ob die Zugr�ckgabe in einem Spiel fr�her erfolgen muss, erkennen Sie <br>
a) in der Einladung, die ein anderer User Ihnen schickt.<br>
b) in der History-Tabelle, die sich rechts neben dem Schachbrett befindet.<br>


<br><center><img src="images/help/zugrueckgabe.JPG"></center>

<br><br>
Hinweise: Die Schachuhr bezieht sich auf die insgesamt verbrauchte Spielzeit. Bitte orientieren Sie sich bei der eingeschr�nkten Zugr�ckgabe anhand der Zeitangabe "Lezter Zug" auf der �bersichtsseite.<br>
Als Mitglied haben Sie <?=$CFG_EXPIREGAME?> Tage Zeit f�r die Zugr�ckgabe. Wir bitten jedoch darum, nur in absoluten Ausnahmef�llen (Urlaub oder Abwesenheit) von dem Zeitlimit Gebrauch zu machen. In der Regel sollten Sie innerhalb von 24-48 Stunden einen gegnerischen Zug erwidern. <br>

<p><a href="#oben">nach oben</a></p>
<br>


<hr>



<br><br><a name="pt"><b>PREISTURNIERE</b><br><BR><br>
In unregelm��igen Abst�nden finden auf PSM Schach Preisturniere statt. Zu gewinnen gibt es i.d.R. Software, die von Sponsoren zur Verf�gung gestellt wird. Grunds�tzlich gilt bei allen Preisturnieren: Sollte es auf irgendwelchen (v.a. technischen ) Gr�nden zu Problemen bei der Feststellung der Sieger kommen, werden die Preise verlost. Der Rechtsweg ist ausgeschlossen.
<br><br>




<p><a href="#oben">nach oben</a></p>
<br>



<hr>



<br><br><a name="standard"><b>REGUL�RE PARTIEN</b><br><BR><br>
Es gibt verschiedene M�glichkeiten, Partien zu erstellen. �ber den Button "Neue Partie" erreichen Sie die entsprechenden Formulare. Laden Sie einen Gegner direkt ein oder erstellen Sie eine Partie, die ein beliebiger User annehmen kann. Alternativ k�nnen Sie auch gegen einen Computergegner (BOT) spielen. Wenn Sie sich neu registriert haben, k�nnen Sie nur Minotaur fordern. 
<br><br>

<br><center><img src="images/help/voreinstellungen.JPG"><br>W�hlen Sie zun�chst <br>die gew�nschten Voreinstellungen. </center><br><br><br>

<br><center><img src="images/help/einladen.JPG"><br>Klicken Sie auf "einladen", um z.B. <br>den Spieler Wenzel einzuladen.</center><br>


<p><a href="#oben">nach oben</a></p>
<br>





<hr>



<br><br><a name="blitz"><b>BLITZPARTIE</b><br><BR><br>
Die Blitzpartien k�nnen optional mit bzw. ohne Wertung gespielt werden; d.h. sie m�ssen nicht zwingend in die ELO-Ranglistenpunkte der regul�ren Spiele eingerechnet werden.<br>
Jede angebotene Blitzpartie, die nicht innerhalb einer Stunde von einem Gegner angenommen wurde, wird ebenfalls nach einer Stunde automatisch gel�scht. Bitte beachten Sie: PSM Schach ist kein Echtzeitschacht. Durch den notwendigen Refresh der Seite kann es zu Verz�gerungen in Ihrer Partie kommen. W�hlen Sie - um sicher zu gehen - m�glichst eine ausreichende Zeit f�r die Blitzpartie. (30 min. ist sehr knapp und erfordert, dass Sie die Seite manuell (Button: Neu laden) refreshen, um auf m�gliche Z�ge schnell zu reagieren.
<br>
Es gibt eine separate Rangliste f�r Blitzpartien. F�r eine gewonnene Partie erhalten Sie zwei Punkte, f�r ein Remis einen Punkt! <br>
Auch wenn Sie ohne ELO-Wertung blitzen: Blitzpunkte werden immer vergeben.<br>

<p><a href="#oben">nach oben</a></p>
<br>


<hr>




<br><br><a name="pdaten"><b>PERS�NLICHE DATEN �NDERN</b><br><BR><br>
�ber den Button "Einstellungen" im Men� k�nnen Sie Ihre pers�nlichen Daten �ndern. Das beinhaltet im Moment <br>
a) den Benutzernamen (also der Nick, mit dem Sie sich einloggen)<br>
b) Ihren Wohnort und Ihr Land<br>
c) Ihr Passwort. <br><br>
Bitte ber�cksichtigen Sie, dass Sie bei jeder �nderung 3x Passw�rter eingegeben m�ssen. <br><br>
Beispiel: Sie wollen Ihren Wohnort �ndern, aber ihr altes Passwort behalten: Geben Sie Ihren neuen Wohnort und 3x ihr altes Passwort (1x als neues Passwort).<br>
Beispiel2: Sie wollen Ihr Passwort �ndern: Geben Sie 2x Ihr altes Passwort ein und beim dritten Mal das neue Passwort.
<br>
<br>
In begr�ndeten F�llen kann der Spielername ge�ndert werden. Kontaktieren Sie hierf�r den <a href="mailto:schach@zum.de">Webmaster</a>. Teilen Sie bitte auch mit, wenn sich Ihre E-Mail Anschrift �ndern sollte, damit der den Eintrag korrigiert werden kann. <br>
Die restriktive Handhabung der pers�nlichen Daten dient allein Ihrem Schutz. Standardm��ig erlaubt <a href="http://kymera.comp.pucpcaldas.br/projetos/compwebchess/" target="_blank">Compwebchess</a> den Spielern einen beliebigen Wechsel der Namen und unterbindet noch nicht einmal Doupletten. Es ist leicht vorstellbar, welche Verwirrung damit ausgel�st werden kann.
<br><br>


<br><br>


<p><a href="#oben">nach oben</a></p>
<br>


<hr>






<br><br><a name="figuren"><b>SPIELFIGUREN �NDERN</b><br><BR><br>
Bitte �ndern Sie die Default-Einstellung (Beholder) ggf. unter dem Button "Einstellungen". Folgende Schachfiguren stehen auf PSM Schach zur Verf�gung. Wir empfehlen Beholder oder Fun.
<br><br>
<table border="0">
<tr><td>Beholder</td><td><img src="images/pieces/beholder/white_pawn.gif"></td><td><img src="images/pieces/beholder/white_king.gif"></td></tr>
<tr><td>Condal</td><td><img width="50px" height="45px" src="images/pieces/condal/white_pawn.gif"></td><td><img width="45px" height="45px" src="images/pieces/condal/white_king.gif"></td></tr>
<tr><td>Fun</td><td><img src="images/pieces/fun/white_pawn.gif"></td><td><img src="images/pieces/fun/white_king.gif"></td></tr>

<tr><td>Harlaquine</td><td><img width="45px" height="45px" src="images/pieces/harlaquine/white_pawn.gif"></td><td><img width="45px" height="45px" src="images/pieces/harlaquine/white_king.gif"></td></tr>

<tr><td>Liepzig</td><td><img width="45px" height="45px" src="images/pieces/liepzig/white_pawn.gif"></td><td><img width="45px" height="45px" src="images/pieces/liepzig/white_king.gif"></td></tr>

<tr><td>Lucena</td><td><img width="45px" height="45px" src="images/pieces/lucena/white_pawn.gif"></td><td><img width="45px" height="45px" src="images/pieces/lucena/white_king.gif"></td></tr>

<tr><td>Magnetic</td><td><img width="45px" height="45px" src="images/pieces/magnetic/white_pawn.gif"></td><td><img width="45px" height="45px" src="images/pieces/magnetic/white_king.gif"></td></tr>

<tr><td>Mark</td><td><img width="45px" height="45px" src="images/pieces/mark/white_pawn.gif"></td><td><img width="45px" height="45px" src="images/pieces/mark/white_king.gif"></td></tr>

<tr><td>Maya</td><td><img width="45px" height="45px" src="images/pieces/maya/white_pawn.gif"></td><td><img width="45px" height="45px" src="images/pieces/maya/white_king.gif"></td></tr>

<tr><td>Medievel</td><td><img width="45px" height="45px" src="images/pieces/medievel/white_pawn.gif"></td><td><img width="45px" height="45px" src="images/pieces/medievel/white_king.gif"></td></tr>

<tr><td>Montreal3d</td><td><img width="45px" height="45px" src="images/pieces/montreal3d/white_pawn.gif"></td><td><img width="45px" height="45px" src="images/pieces/montreal3d/white_king.gif"></td></tr>

<tr><td>Orig</td><td><img width="45px" height="45px" src="images/pieces/orig/white_pawn.gif"></td><td><img width="45px" height="45px" src="images/pieces/orig/white_king.gif"></td></tr>

<tr><td>Plain</td><td><img width="45px" height="45px" src="images/pieces/plain/white_pawn.gif"></td><td><img width="45px" height="45px" src="images/pieces/plain/white_king.gif"></td></tr>

<tr><td>Scrabble</td><td><img width="45px" height="45px" src="images/pieces/scrabble/white_pawn.gif"></td><td><img width="45px" height="45px" src="images/pieces/scrabble/white_king.gif"></td></tr>

</table>

<br><br>

<p><a href="#oben">nach oben</a></p>
<br>


<hr>



<br><br><a name="lz"><b>LETZTEN ZUG ANZEIGEN</b><br><BR><br>
PSM Schach bietet die M�glichkeit, den letzten Zug farblich hervorzuheben. Sie k�nnen unter "Einstellungen => Letzten Zug markieren" verschiedene Farben w�hlen. Sollte Sie die Einblendung zu sehr ablenken, k�nnen Sie sie unter "Einstellungen => Letzten Zug markieren" deaktivieren.


<p><a href="#oben">nach oben</a></p>
<br>



<hr>



<br><br><a name="email"><b>E-MAIL BENACHRICHTIGUNG</b><br><BR><br>
PSM Schach bietet die M�glichkeit, dass Spieler sich �ber Einladungen oder gegnerische Z�ge per E-Mail informieren lassen. Diese Funktion (Klick auf "Einstellungen => scroll down) ist standardm��ig deaktiviert. Sollten Sie zahlreiche Spiele haben und t�glich auf PSM Schach online sein, ist es ohnehin nicht sinnvoll, sie zu aktivieren.
<br><br>



<br><center><img src="images/help/benachrichtigung.JPG"><br>Tragen Sie Ihre E-Mail ein, um sich <br>�ber Einladungen oder gegnerische Z�ge informieren zu lassen.</center><br>


<p><a href="#oben">nach oben</a></p>
<br>



<hr>



<br><br><a name="pn"><b>PRIVATE NACHRICHT VERSCHICKEN</b><br><BR><br>
PSM Schach verf�gt �ber ein internes Mailsystem. Sie k�nnen einem Mitspieler eine private Nachricht senden. Die Benachrichtigung erfolgt �ber eine Einblendung auf der �bersichts-Seite (mainmenu); optional k�nnen Sie sich auch per E-Mail benachrichtigen lassen.
<br><br>
Um eine private Nachricht zu versenden, klicken Sie bitte auch den gleichnamigen Button in der Navigation und dann auf den Link "Hier klicken, um eine Private Nachricht zu verschicken!". Bitte ber�cksichtigen Sie, dass Ihr Browser zwingend Popups unterst�tzen muss. 


<p><a href="#oben">nach oben</a></p>
<br>




<hr>


<br><br>
<a name="elo"></a>
<b>BEWERTUNGSGRUNDLAGE</b><br><BR>

<p>Das Elo-System ist ein Wertungssystem, das ein Ma�
f�r die Spielst�rke der Spieler geben soll. Durch die
Elo-Zahlen ist eine Klassifizierung aller Schachspieler m�glich
und die Spielst�rke (ann�hernd) feststellbar.</p>

<p>Im Jahre 1970 wurde das von <b>Arpad Elo</b> (1903-1992),
einem aus Ungarn stammenden Professor f�r Theoretische Physik
an der Universit�t Milwaukee, ausgearbeitete Koeffizientensystem
vom Weltschachbund <a href="http://www.fide.com/" target="_blank">
FIDE</a> angenommen. Das System ist nach wissenschaftlichen
Methoden der Statistik und der Wahrscheinlichkeitstheorie
aufgebaut und heute international gebr�uchlich.</p>

<p><a href="#oben">nach oben</a></p>
<br>
<hr>

<a name="eloberechnung"></a>
<p><b>BERECHNUNG DER ELO-ZAHLEN</b></p>


<p>Die Auswirkung eines Spielresultates auf die Elo-Bewertung
ist dann sehr leicht zu berechnen, wenn beide Spieler bereits
Elo-Zahlen haben. Jeder Spieler kann sich in diesem Fall selbst
die �nderung seiner Elo-Zahl ermitteln. Man braucht dazu
au�er der nachstehenden <a href="#tabe">&#8595;&nbsp;Tabelle</a>
oder der entsprechenden <a href="#form">&#8595;&nbsp;Formel</a>
folgende drei Daten:</p>

<pre><p class="tabw">1) Elo(eigene, alt)   zuletzt ver�ffentlichte Elo-Zahl
2) Elo(Gegner, alt)   zuletzt ver�ffentlichte Elo-Zahl
3) Resultat           1 oder 0,5 oder 0
</p></pre>

<p>Der Einfluss auf die Elo-Zahl wird so berechnet:</p>

<pre><p class="tabw">1) Faktor    = [3400 - Elo(eigene, alt)]� / 100000)
2) Differenz = Elo(eigene, alt) - Elo(Gegner, alt)
3) Erwartung = laut <a href="#tabe">&#8595; Tabelle</a> oder <a href="#form">&#8595;&nbsp;Formel</a>
4) �nderung  = (Resultat - Erwartung) x Faktor
</p></pre>

<p>Hinweis:</p>
<ul class="norm" type="disc">
<li>Normalerweise wird bei einer bestimmten Anzahl von gespielten Partien
eine <a href="#fakt"><nobr>&#8595;&nbsp;Faktorreduktion</nobr></a> wirksam. 
Da auf PSM Schach die Anzahl der Spiele vergleichsweise hoch ist, wurde 
die Faktorreduktion am 13.12.2004 deaktiviert.
</li>
</ul>

<p><u>1. Beispiel:</u></p>

<pre><p class="tabw">1) Elo(eigene, alt) = 1830
2) Elo(Gegner, alt) = 1980
3) Resultat         = 0,5
</p></pre>

<p>Berechnung:</p>

<pre><p class="tabw">1) Faktor    = (3400 - 1830)� / 100000 = 24,6
2) Differenz = 1830 - 1980             = -150
3) Erwartung = laut <a href="#tabe">&#8595;Tabelle</a>           = 0,30
4) �nderung  = (0,5 - 0,30) x 24,6     = +5
</p></pre>

<p>Das Remis bringt 5 Punkte.</p>

<p><u>2. Beispiel:</u></p>

<pre><p class="tabw">1) Elo(eigene, alt) = 1950
2) Elo(Gegner, alt) = 1820
3) Resultat         = 0
</p></pre>

<p>Berechnung:</p>

<pre><p class="tabw">1) Faktor    = (3400 - 1950)� / 100000 = 21,0
2) Differenz = 1950 - 1820             = +130
3) Erwartung = laut <a href="#tabe">&#8595;Tabelle</a>           = 0,68
4) �nderung  = (0 - 0,68) x 21,0       = -14
</p></pre>

<p>Der Verlust dieser Partie kostet 14 Punkte.</p>

<p>Jede Partie wird f�r sich bewertet und hat au�er
der hier berechneten �nderung keinen weiteren Einfluss
auf die Elo-Zahl.</p>

<a name="tabe"></a>
<p><b>Tabelle:</b> Erwartung nach Prof. Arpad Elo</p>

<pre><p class="tabw">Differenz   (+)   (-)      Differenz   (+)   (-)

  0 -   3  0,50  0,50      198 - 206  0,76  0,24
  4 -  10  0,51  0,49      207 - 215  0,77  0,23
 11 -  17  0,52  0,48      216 - 225  0,78  0,22
 18 -  25  0,53  0,47      226 - 235  0,79  0,21
 26 -  32  0,54  0,46      236 - 245  0,80  0,20
 33 -  39  0,55  0,45      246 - 256  0,81  0,19
 40 -  46  0,56  0,44      257 - 267  0,82  0,18
 47 -  53  0,57  0,43      268 - 278  0,83  0,17
 54 -  61  0,58  0,42      279 - 290  0,84  0,16
 62 -  68  0,59  0,41      291 - 302  0,85  0,15
 69 -  76  0,60  0,40      303 - 315  0,86  0,14
 77 -  83  0,61  0,39      316 - 328  0,87  0,13
 84 -  91  0,62  0,38      329 - 344  0,88  0,12
 92 -  98  0,63  0,37      345 - 357  0,89  0,11
 99 - 106  0,64  0,36      358 - 374  0,90  0,10
107 - 113  0,65  0,35      375 - 391  0,91  0,09
114 - 121  0,66  0,34      392 - 411  0,92  0,08
122 - 129  0,67  0,33      412 - 432  0,93  0,07
130 - 137  0,68  0,32      433 - 456  0,94  0,06
138 - 145  0,69  0,31      457 - 484  0,95  0,05
146 - 153  0,70  0,30      485 - 517  0,96  0,04
154 - 162  0,71  0,29      518 - 559  0,97  0,03
163 - 170  0,72  0,28      560 - 619  0,98  0,02
171 - 179  0,73  0,27      620 - 735  0,99  0,01
180 - 188  0,74  0,26          &gt; 735  1,00  0,00
189 - 197  0,75  0,26
</p></pre>

<a name="form"></a>
<p>Die offiziellen Elo-Zahlen werden nach
folgender <b>Formel</b> errechnet:</p>

<pre><p class="tabw">Erwartung = 0,5 + 1,4217 x 10^-3  x D
                - 2,4336 x 10^-7  x D x |D|
                - 2,5140 x 10^-9  x D x |D|^2
                + 1,9910 x 10^-12 x D x |D|^3
</p></pre>

<p>wobei gilt:</p>

<pre><p class="tabw">D   = Differenz = Elo(eigene, alt) - Elo(Gegner, alt)
|D| = Absolutbetrag von D
</p></pre>

<p>Hinweis: Das ELO-System auf PSM Schach wurde von dem User Winterfrost implementiert. Herzlichen Dank an dieser Stelle. Grundlage f�r
die Berechnung waren die Angaben auf der Seite des <a href="http://www.schachklub-hietzing.at/elosystem.html#fakt" target="_blank">Schachklubs Hietzing</a>. Der 
Abdruck der Berechnung auf PSM SCHACH erfolgt mit freundlicher Genehmigung.


<p><a href="#oben">nach oben</a></p>


<hr>
<p><a name="medaillen"></a>
<br>
<b>MEDAILLEN</b><br><BR>
<img src="images/rank/honour.gif"> = <u>Honour</u><br>
<i>Jeder Spieler auf dem ersten Rang erh�lt diese Medaille</i>
<br><br>
<u>Pour le Merite</u><br>
<i>Der Merite-Orden wird folgenderma�en vergeben:</i><br><br>
<img src="images/rank/merit100.gif"> = Merite100<br>
Dieser Orden ist f�r Spieler, die 100 und mehr Spiele <br>mit einer Prozentzahl von �ber 90 Siegen gespielt haben.<br><br>
<img src="images/rank/merit50.gif"> = Merite50<br>
Dieser Orden ist f�r Spieler, die 50 und mehr Spiele <br>mit einer Prozentzahl von �ber 90 Siegen gespielt haben.<br><br>
<img src="images/rank/merit20.gif"> = Merite20<br>
Dieser Orden ist f�r Spieler, die 20 und mehr Spiele <br>mit einer Prozentzahl von �ber 90 Siegen gespielt haben.<br><br>

<u>Courage</u><br>
<i>Den Courage-Orden erh�lt der Spieler, der an einem Tag 10 oder mehr Spiele beginnt und </i>
<br><br>
100% der Spiele gewinnt: <img src="images/rank/courage100.gif"> = Courage100<br><br>
90% der Spiele gewinnt: <img src="images/rank/courage90.gif"> = Courage90<br><br>
80% der Spiele gewinnt: <img src="images/rank/courage80.gif"> = Courage80<br><br>
70% der Spiele gewinnt: <img src="images/rank/courage70.gif"> = Courage70<br><br>

Sieger eines 4erTurniers gewinnt: <img src="images/rank/tournament4.gif"> = Tournament4<br><br>
Sieger eines 16erTurniers gewinnt: <img src="images/rank/tournament16.gif"> = Tournament16<br><br>
<p><a href="#oben">nach oben</a></p>


<hr>


<a name="leitung"></a>
<b>LEITUNG SCHACH AG</b>
<br>
<p>Leiter einer Schach-AG k�nnen auf Wunsch erweiterte Gruppenberechtigungen erhalten. <br>
a) Es k�nnen mehrere Gruppen angelegt werden.
b) Es k�nnen Spieler zwischen den jeweiligen Gruppen verschoben werden.
<br>

Kontaktieren Sie f�r diese Funktion bitte den <a href="mailto:schach@zum.de">Webmaster</a>.
<br><br>
<p><a href="#oben">nach oben</a></p>

<hr>


<a name="anleitung"></a>
<b>TRAINING ANLEITUNG</b>
<br>
- W�hlen Sie oben den gew�nschten Trainingsmodus (Matt in einem Zug/Matt in zwei Z�gen/Taktik) und w�hlen Sie zun�chst eine der Brettpositionen aus, <br>die Ihnen per Zufallsgenerator vorgeschlagen werden. </li><br>

- Klicken Sie unterhalb des Feldes auf 'Spiel starten'.</li><br><br><br>
<center><img src="images/neuesspiel1.JPG"><br><br></center>

- Sie haben entsprechend Ihrer Wahl einen oder zwei Z�ge, um den Gegner Schachmatt zu setzen (Matt in einem Zug |  Matt in zwei Z�gen). Gelingt Ihnen dies, erhalten Sie einen Punkt (drei Punkte) in den Ranglisten. Klappt es nicht, haben Sie weitere Versuche; Sie k�nnen jedoch f�r diese Aufgabe keine Gewinnpunkte mehr erhalten. Im Taktik-Trainer gibt es eine schriftliche Aufgabe, die Sie l�sen m�ssen. Sie finden die Aufgabe in den beiden folgenden Positionen:

<br><br>
<center>
<img src="images/help/taktik2.jpg"><br><br>

<br><br>oder<br><br>

<img src="images/help/taktik1.jpg"></center>



<p><a href="#oben">nach oben</a></p>


<hr>
<p><a name="kommentieren"></a>
<br>
<b>PARTIEN KOMMENTIEREN</b><br><BR>

- Auf PSM Schach k�nnen Sie jede Partie kommentieren. Sie haben dazu zwei M�glichkeiten:<br>
1) Sie entscheiden sich f�r eine klassische Annotation. Dr�cken Sie oberhalb des Spielverlaufs den Button Spielanalyse und benutzen Sie das Analysebrett um einzelne Z�ge zu kommentieren. Diese Funktion muss durch den Webmaster f�r Sie freigeschaltet werden. <br>Alle annotierten Partien finden Sie unter dem Button "Annotationen".<br>

<center><img src="images/help/kommentieren2.JPG"></center>


2) Unter jedem Spielfeld finden Sie ein Feld, das zur Diskussion einer Partie benutzt werden kann. Kommentare, die hier erstellt werden, werden - inkl. der Partie - automatisch im Forum gespiegelt.<br><br>

<center><img src="images/help/kommentieren.JPG"></center>

<br><br>
Das Tabellenfeld zeigt Ihnen ebenfalls an, ob bereits Kommentare zu dieser Partie vorliegen. <br><br>
<p><a href="#oben">nach oben</a></p>


<hr>
<p><a name="export"></a>
<br>
<b>PGN FORMAT EXPORTIEREN</b><br><BR>
<br><br>
Jede Partie l�sst sich im PGN-Format exportieren oder abspeichern. Dr�cken Sie oberhalb des Spielverlaufs den Button Spielanalyse. Sie sehen dann das Analysebrett. Unterhalb des Brettes finden Sie den Button "PGN-Export". Klicken Sie darauf und speichern Sie die Partie auf Ihrer Festplatte oder �ffnen Sie sie mit einem anderen Programm.

<center><img src="images/help/export.JPG"></center>

<br>
Ein aktuelles PGN-File s�mtlicher PSM SCHACH Partien ist f�r einen Unkostenbeitrag von 15� (CD 20�, inkl. Versand) erh�ltlich. Bitte sprechen Sie bei Bedarf den <a href="mailto:schach@zum.de">Webmaster</a> an!
<br><br>
Hinweis: Es ist strengstens untersagt, in Wertungspartien das Ergebnis mit Schachcomputern (sog. Engines) zu manipulieren. Es werden stichprobenartig Kontrollen durchgef�hrt. <br><br>

<p><a href="#oben">nach oben</a></p>

<hr>


<p><a name="datenschutz"></a>
<br>
<b>DATENSCHUTZ</b><br><BR>
<br><br>
PSM Schach verpflichtet sich, keine pers�nlichen Daten von Spielern an dritte Personen weiterzugeben.
<br>
Der gesamte Datenverkehr (Private Nachrichten, pers�nlicher Chat) kann durch den Webmaster kontrolliert und eingesehen werden. Von dieser M�glichkeit wird i.d.R. kein Gebrauch gemacht. Im Beschwerdefall beh�lt sich PSM Schach jedoch ausdr�cklich das Recht vor, Nachrichten und Chatmitteilungen einzusehen.
<br><br>

<p><a href="#oben">nach oben</a></p>

<hr>



<p><a name="account"></a>
<br>
<b>ACCOUNT L�SCHEN</b><br><BR>
<br><br>
Auf Wunsch kann ein Account deaktiviert werden. Dies bedeutet, dass der User nicht mehr im Spielerverzeichnis sowie im Ranking gelistet wird. Das vollst�ndige L�schen eines Accounts sowie bereits gespielter Partien ist nicht m�glich.

<br><br>

<p><a href="#oben">nach oben</a></p>

<hr>






<BR><font face="Verdana" size="1"><center>
Hilfesystem Version 0.1 vom 19.12.2004<br><br>
Das Hilfesystem ist im Aufbau! Haben Sie noch Fragen oder Verbesserungsvorschl�ge? M�chten Sie selber am Hilfesystem mitarbeiten? Bitte schreiben Sie eine <a href="messages.php">Nachricht</a> an GMondwurf!<br>Ich freue mich �ber Ihre Beitr�ge!<br><br>
</font>
</p>

</td></tr></table>
<font face="verdana" size="2">
<font face="Verdana" size="2">
<br>
&nbsp;</font> </p>

<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>


</body>
