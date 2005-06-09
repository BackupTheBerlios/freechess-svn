<?php
##############################################################################################
#                                                                                            #
#                                rules.inc.php                                                #
# *                            -------------------                                           #
# *   begin                : Friday, January 15, 2005                                        #
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://www.compwebchess.com/forums                              #
# *   VERSION:             : $Id: rules.inc.php,v 1.1 2005/01/31 03:04:11 trukfixer Exp $                                           
#                                                                                            #
##############################################################################################
#    This program is free software; you can redistribute it and/or modify it under the       #
#    terms of the GNU General Public License as published by the Free Software Foundation;   #
#    either version 2 of the License, or (at your option) any later version.                 #
#                                                                                            #
#    This program is distributed in the hope that it will be useful, but                     #
#    WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS   #
#    FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.          #
#                                                                                            #
#    You should have received a copy of the GNU General Public License along with this       #
#    program; if not, write to:                                                              #
#                                                                                            #
#                        Free Software Foundation, Inc.,                                     #
#                        59 Temple Place, Suite 330,                                         #
#                        Boston, MA 02111-1307 USA                                           #
##############################################################################################
?>
<head>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
<title>ChessManiac.com Help</title></head>
<body bgcolor=white text=black>
<font face="verdana" size="2">
<? require_once('header.inc.php');?>


--------------------------------------------------------------------------------
<table border="1"><tr><td><div width="100%" align="left">
<BR><font face="Verdana" size="2"><center><b>HELP SYSTEM FOR CHESSMANIAC.COM</b><br><font face="Verdana" size="1">Version 0.1 of Dec. 19th b2004<br></font>

</center><br>
<font face="Verdana" size="2">

<br>
<b>Basics</b><br>

<li><a name="oben"><a href="#allgemein">General Rules</a></li>
<li><a name="obenb1"><a href="#zugrueck">timeout after 14 days</a></li>
<li>tournaments</li>
<li><a name="oben"><a href="#pt">award tournaments</a></li>

<br>
<b>Starting a New Game</b><br>
<li><a name="obena1"><a href="#standard">Starting a New Game</a></li>
<li><a name="obena1"><a href="#blitz">Live Games</a> (games with very short time allotment per player)</li> 

<br>
<b>Configuration</b><br>
<li>changing personal data</li>
<li>change design</li>
<li><a name="oben"><a href="#figuren">change chess pieces</a></li>
<li><a name="oben"><a href="#lz">show last move</a></li>
<li><a name="oben"><a href="#email">e-mail message</a></li>
<li><a name="oben"><a href="#pn">message to partner</a></li>
<li>statistics</li>

<br>
<b>Calculation of the Ranking List on ChessManiac.com</b><br>
<li><a name="oben1"><a href="#elo">General Information about the Elo System</a><br>
<li><a name="oben2"><a href="#eloberechnung">Calculation of Elo Figures</a><br>
<li><a name="oben3"><a href="#medaillen">medals</a></li>

<br>
<b>Groups</b><br>
<li>establish a group</li>
<li>join a group</li>
<li>establish a Chess Club</li>
<li><a name="oben"><a href="#leitung">leading a Chess Club</a></li>


<br>
<b>Practising on ChessManiac.com</b><br>
<li><a name="oben"><a href="#anleitung">Instruction</a></li>


<br>
<b>Others</b><br>
<li><a name="oben"><a href="#kommentieren">commenting chess games</a></li>
<li><a name="oben"><a href="#export">saving PGN files</a></li>
<li>forum</li>
<li>chat</li>
<li>Journal</li>
<li><a name="oben"><a href="#datenschutz">data protection</a></li>
<li><a name="oben"><a href="#account">delete an account</a></li>




<br><br>
<hr>

<a name="allgemein"></a>
<b>General Rules</b>
<br>
- On ChessManiac.com chess, general <a href="http://www.chessmaniac.com/chessrules.shtml" target="_blank">chess rules</a> apply. In case, deviations are made necessary because of technical reasons, the responsible  <a href="mailto:webmaster@chessmaniac.com">webmaster</a> will have to make decisions.<br>
- All players begin with a ranking of 1500 points. <br>
- Challenges not accepted within <?=$CFG_EXPIREGAME?> days will be deleted automatically.<BR>
-  Games with less than <?=$CFG_MIN_ROUNDS?> moves are not calculated.<BR>
- On ChessManiac.com, general chess rules apply. This platform offers a number of functions; still users are asked to consider that the program is still in the stage of development; it is open source software, which is developed by interested players in their free time. If you want to participate, click here: <a href="http://compwebchess.com/forums/" target="_blank">Compwebchess</a>!<br>
- All players are asked to treat their partnrs courteously. Offensive, obscene, vulgar, racist and sexist utterances are forbdden and will result in the immediate exclusion of a player and the blocking of his/her account. <br>
- Please try not to start more than 50 games.  <br>
- The webmaster obliges himself not to distribute personal data of players to others

<br>
<p><a href="#oben">back to the top</a></p>
<br>
<hr>


<a name="zugrueck"></a>
<b>TIMEOUT AFTER 14 DAYS</b>
<br><br>
- Games in which over a period of <?=$CFG_EXPIREGAME?> days no move has been made, automatically are regarded as
lost by the player who failed to move - loss because of extension of time limit. <br>
- This time limit of <?=$CFG_EXPIREGAME?> days, in regular games as well as in tournaments, can be reduced by using the menu, to a smaller number of days. 

<br>
<br><center><img src="help/zugrrueck2.JPG" width="370" height="65">
</center>
<br>

- You can recognize a time limit of less than 14 days <br>
a) in the challenge message sent by a prospective opponent.<br>
b) in the history table on the right of the chessboard.<br>


<br><center><img src="help/zugrueckgabe.JPG" width="346" height="243">
</center>

<br><br>
Hint : The chess clock refers to the total consumd playing time. Concerning the reduced timeout, please refer to the time indicated at "last move" on the overview page.<br>
As member, you are entitled to take <?=$CFG_EXPIREGAME?> days for your move. We kindly ask to use this time limit only in exceptional situations (vacation, absence). Generally you should counter an opponent's move within 24 to 48 hours <br>

<p><a href="#oben">back to the top</a></p>
<br>


<hr>



<br><br><a name="pt"><b>Award Tournaments</b><br><BR><br>
From time to time, on ChessManiac.com award tournaments are held. Prizes generally consist of software given to us by sponsors. In case of all tournamnts : should, for whatever reasons (f.i. technical reasons) problems regarding the establishment of a winner occur, the prizes are allocated by lottery.
Court rulings are not accepted.
<br><br>




<p><a href="#oben">back to the top</a></p>
<br>



<hr>



<br><br><a name="standard"><b>REGULAR GAMES</b><br><BR><br>
 There are various options to create a game. Via the button "New Game" you reach the respective forms. Invite an opponent directly or create a game which any player can accept. Alternatively, you can play against a computer opponent (BOT). If you just have registered, among the BOTs you only can challenge the Minotaur.
<br><br>

<br><center><img src="help/voreinstellungen.JPG" width="460" height="122"><br> 
First decide on <br>the desired configurations. </center><br><br><br>

<br><center><img src="help/einladen.JPG" width="274" height="73"><br>
click on "invite", in order, <br>for example, to invite player Wenzel.</center><br>


<p><a href="#oben">back to the top</a></p>
<br>





<hr>



<br><br><a name="blitz"><b>Live Game</b> (Game with severe time limitation per move)<br><BR><br>
 These games can be played with or without having an influence on the ranking.<br>
 Any challenge to such a game, which is not accepted within an hour, will be deleted automatically.<br>
 Please consider that ChessManiac.com is not real time chess. The necessary refresh-function can cause delays in a game. When you configure such a game under severe time restriction, choose an appropriate time limitation (30 minutes is rather short and requires you to refresh the page manually (button "Reload"), in order to quickly react on moves. We have a separate ranking list for games under severe time limitation. For a victory you receive always two live points. You can play with or without ELO ranking. 
<p><a href="#oben">back to the top</a></p>
<br>


<hr>



<br><br><a name="figuren"><b>Change (the Design of) Chess Pieces</b><br><BR><br>
Please change the default-configuration (beholder) via the button Configurations. Following sets of chess pieces are offered on ChessManiac.com. We recommend beholder and fun.
<br><br>
<table border="0">
<tr><td>Beholder</td><td><img src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/beholder/white_pawn.gif"></td><td><img src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/beholder/white_king.gif"></td></tr>
<tr><td>Condal</td><td><img width="50px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/condal/white_pawn.gif"></td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/condal/white_king.gif"></td></tr>
<tr><td>Fun</td><td><img src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/fun/white_pawn.gif"></td><td><img src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/fun/white_king.gif"></td></tr>

<tr><td>Harlaquine</td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/harlaquine/white_pawn.gif"></td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/harlaquine/white_king.gif"></td></tr>

<tr><td>Liepzig</td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/liepzig/white_pawn.gif"></td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/liepzig/white_king.gif"></td></tr>

<tr><td>Lucena</td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/lucena/white_pawn.gif"></td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/lucena/white_king.gif"></td></tr>

<tr><td>Magnetic</td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/magnetic/white_pawn.gif"></td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/magnetic/white_king.gif"></td></tr>

<tr><td>Mark</td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/mark/white_pawn.gif"></td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/mark/white_king.gif"></td></tr>

<tr><td>Maya</td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/maya/white_pawn.gif"></td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/maya/white_king.gif"></td></tr>

<tr><td>Medievel</td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/medievel/white_pawn.gif"></td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/medievel/white_king.gif"></td></tr>

<tr><td>Montreal3d</td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/montreal3d/white_pawn.gif"></td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/montreal3d/white_king.gif"></td></tr>

<tr><td>Orig</td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/orig/white_pawn.gif"></td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/orig/white_king.gif"></td></tr>

<tr><td>Plain</td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/plain/white_pawn.gif"></td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/plain/white_king.gif"></td></tr>

<tr><td>Scrabble</td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/scrabble/white_pawn.gif"></td><td><img width="45px" height="45px" src="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/images/pieces/scrabble/white_king.gif"></td></tr>

</table>

<br><br>

<p><a href="#oben">back to the top</a></p>
<br>


<hr>



<br><br><a name="lz"><b>Show last move</b><br><BR><br>
PSM offers the option to emphasize the last move by showing it in a different colour.<br>  Under Configurations, mark the last move you can choose among various colours. <br>If you feel this option too distractive, you can deactivate it under Configurations, mark the last move.


<p><a href="#oben">back to the top</a></p>
<br>



<hr>



<br><br><a name="email"><b>E-mail message</b><br><BR><br>
 ChessManiac.com offers the option to inform players per e-mail about invitations or opponents' moves.<br> This function (Configurations, scroll down) is normally deactivated. In case you play many games and play daily on ChessManiac.com, it does not make sense to activate this function.
<br><br>



<br><center><img src="help/benachrichtigung.JPG" width="380" height="230"><br>
Enter your e-mail address in order to receive messages informing you about invitations and opponents' moves.</center><br>


<p><a href="#oben">back to the top</a></p>
<br>



<hr>



<br><br><a name="pn"><b>sending private messages</b><br><BR><br>
PSM has an internal mailing system. You can send a private message to your opponent. This message is displayed on the main menu; optionally you can have these messages sent to you by e-mail.
<br><br>
In order to end a private message, click on the button by that name in the navigation section, and then on the link "click here to send a private message". Please consider that your browseer must accept pop-ups.


<p><a href="#oben">back to the top</a></p>
<br>




<hr>


<br><br>
<a name="elo"></a>
<b>Basis of Calculation of Ranking</b><br><BR>

<p>The ELO system is designed to establish the level of a player. ELO permits to classify all players and can roughly establish their level.</p>

<p>In 1970 the World Chess Federation <a href="http://www.fide.com/" target="_blank">FIDE</a> accepted the system developed by Arpad Elo (1903-1992), Hungarian-born professor for theoretical physics at the University of Milwaukee. The system is based on scientific methods of statistical science and the theory of probability, and presently used internationally.
</p>

<p><a href="#oben">back to the top</a></p>
<br>
<hr>

<a name="eloberechnung"></a>
<p><b>Calculation of ELO Figures</b></p>


<p>The consequence of a game result is easily calculated if both players already have been ranked according to ELO. In that case, every player can calculate the change of his ELO figure. According to the <a href="#tabe">&#8595;&nbsp;table</a> or <a href="#form">&#8595;&nbsp;formula</a> below, three data are required : 
</p>

<pre><p class="tabw">1) your own ELO (before the game in question)
2) your opponent's ELO (before the game in question)
3) result 1, 0.5, 0
</p></pre>

<p>The change in the ELO figure is calculatede as follows:</p>

<pre><p class="tabw">1) factor = [3400 - own previous ELO] / 100000
2) difference = own previous ELO - opponent's previous ELO
3) expectation according to <a href="#tabe">&#8595; table</a> or <a href="#form">&#8595;&nbsp;formula</a>
4) change = (result - expectation) x factor
</p></pre>

<p>Note:</p>
<ul class="norm" type="disc">
<li>Normally, after a certain number of played games a reduction of the factor applies. As the number of games played on ChessManiac.com is comparatively high, on December 13th 2004 the factor reduction was deactivated.
</li>
</ul>

<p><u>First Example:</u></p>

<pre><p class="tabw">1) own previous ELO = 1830
2) opponent's previous ELO = 1980
3) result = 0.5
</p></pre>

<p>Calculation:</p>

<pre><p class="tabw">1) factor = (3400 - 1830)² / 100000 = 24.6
2) difference = 1830-1980 = -150
3) expectation = according to <a href="#tabe">&#8595;table</a> 0.30
4) change = (0.5 - 0.30) x 24.6 = +5
</p></pre>

<p>for the draw  5 points were awarded.</p>

<p><u>Second Example:</u></p>

<pre><p class="tabw">1) own previous ELO = 1950
2) opponent's previous ELO = 1820
3) result = 0
</p></pre>

<p>Calculation:</p>

<pre><p class="tabw">1) factor = (3400 - 1950)² / 100000 = 21.0
2) difference = 1950-1820 = + 130
3) expectation according to <a href="#tabe">&#8595;table</a>   = 0.68
4) change  = (0 - 0.68) x 21.0       = -14
</p></pre>

<p>For the loss in this game, 14 points were subtracted.</p>

<p>Every game is calculated separately, and except for the change discused here, has no other on the ELO figure.</p>

<a name="tabe"></a>
<p><b>Table:</b> Expectation, according to Prof. Arpad Elo</p>

<pre><p class="tabw">difference   (+)   (-)      difference   (+)   (-)

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
<p>The official ELO figures are calculated according to the following <b>formula</b>:</p>

<pre><p class="tabw">Expectation = 0,5 + 1,4217 x 10^-3  x D
                - 2,4336 x 10^-7  x D x |D|
                - 2,5140 x 10^-9  x D x |D|^2
                + 1,9910 x 10^-12 x D x |D|^3
</p></pre>

<p>the following applies:</p>

<pre><p class="tabw"> D = difference (own previous ELO - pponent's previous ELO)
|D| = absolute figure of D
</p></pre>

<p>
Note: The ELO System on ChessManiac.com was installed by user Dennis Steele. ChessManiac.com wishes to express her sincere gratitude. Informations on the website of <a href="http://www.schachklub-hietzing.at/elosystem.html#fakt" target="_blank">Schachclub Hietzing</a> formed the basis for the calculation. The calculation is used on ChessManiac.com with permission.



<p><a href="#oben">back to the top</a></p>


<hr>
<p><a name="medaillen"></a>
<br>
<b>Medals</b><br><BR>
<img src="images/rank/honour.gif" width="13" height="14"> = <u>Honour</u><br>
<i>any player on first rank receives this medal</i>
<br><br>
<u>For Merit</u><br>
<i>this medal is awarded as follows:</i><br><br>
<img src="images/rank/merit100.gif" width="8" height="15"> = Merite100<br>
This medal is awarded to players who played<br> 100 or more games winning 90 % of the games or more.<br><br>
<img src="images/rank/merit50.gif" width="8" height="15"> = Merit50<br>
This medal is awarded to players who played<br> 50 or more games winning 90 % of the games or more.<br><br>
<img src="images/rank/merit20.gif" width="8" height="15"> = Merit20<br>
This medal is awarded to players who played<br> 20 or more games winning 90 % of the games or more.<br><br>

<u>Courage</u><br>
<i>the courage medal is awarded to a player who begins 10 games or more on a single day and</i>
<br><br>
wins 100 % of the games: <img src="images/rank/courage100.gif" width="7" height="15"> = Courage100<br><br>
wins 90 % of the games: <img src="images/rank/courage90.gif" width="7" height="15"> = Courage90<br><br>
wins 80 % of the gamest: <img src="images/rank/courage80.gif" width="7" height="15"> = Courage80<br><br>
wins 70 % of the games: <img src="images/rank/courage70.gif" width="7" height="15"> = Courage70<br><br>

Winner of a tournament of 4 players: <img src="images/rank/tournament4.gif" width="9" height="15"> = Tournament4<br><br>
Winner of a tournament of 16 players: <img src="images/rank/tournament16.gif" width="9" height="15"> = Tournament16<br><br>
Purple Heart:<img src="images/rank/purpleheart.gif" width="10" height="15"> <br>
- This is given to a player who achieves the minimum rating (900). All players
who get this rating at some time in their history will receive this medal.
<p><a href="#oben">back to the top</a></p>


<hr>


<a name="leitung"></a>
<b>Leading a Chess Club</b>
<br>
<p>Leaders of Chess clubs, upon request, can receive extended group privileges. <br>
a) several groups may be established
b) players can be moved from group to group
<br>

For this function, please contact the <a href="mailto:webmaster@chessmaniac.com">webmaster</a>.
<br><br>
<p><a href="#oben">back to the top</a></p>

<hr>


<a name="anleitung"></a>
<b>Training Instruction</b><br>
<br>
<li> Select the desired training mode (checkmate in one move / checkmate in two moves / strategy)
 and select one of the chessboard positions suggested to you by the </li><br>

<li> click on the field 'start game' below</li><br><br><br>
<center><img src="help/neuesspiel1.JPG" width="265" height="245"><br>
<br></center>

<li> According to your selection, you have one or two moves to checkmate the opponent.
 If you succeed, you are credited one point respectively 3 points in the ranking. If you do
not succeed, you can try again, but you can not get any points for this specific task. In strategy training you are given a written task you have to solve; you find the task in the following two positions:</li>

<br><br>
<center>
<img src="help/taktik2.jpg" width="452" height="200"><br>
<br>

<br><br>or<br><br>

<img src="help/taktik1.jpg" width="476" height="247">
</center>



<p><a href="#oben">back to the top</a></p>


<hr>
<p><a name="kommentieren"></a>
<br>
<b>Commenting Games</b><br><BR>

- On ChessManiac.com you can comment any game. There are two options:<br>
1) you opt for classic annotation. Press button game analysis and use the analysis board in order to comment individual moves. This function has to be enabled by the webmaster.<br> Under the button annotations you find all annotated games.<br><br>

<center><img src="help/kommentieren2.JPG" width="300" height="84">
</center>

<br><br>
2) under the chessboard you find a field which can be used to discuss a game. Comments given here are mirrored, as is the game itself, in the forum.<br><br>

<center><img src="help/kommentieren.JPG" width="438" height="74">
</center>

<br><br>
The field 'table' also indicates if comments to this specific game have already been given.<br><br>
<p><a href="#oben">back to the top</a></p>


<hr>
<p><a name="export"></a>
<br>
<b>Exporting the PGN format</b><br><BR>
<br><br>
Every game can be exported or saved in PGN format. Press button game analysis above course of the game. Then you see the analysis field. Under the chessboard you find the button "PGN Export". Click it and save the game on your hard drive or open it with another program.<br><br>

<center><img src="help/export.JPG" width="159" height="117">
</center>

<br>
An actual PGN file with all ChessManiac.com games can be purchased for 15 Euro (CD 20 Euro, including shipment in Europe). Please contact the <a href="mailto:webmaster@chessmaniac.com">Webmaster</a>!
<br><br>
Note : It is forbidden, to manipulate the result of official gams by using chess engines. PSM implements random controls. <br><br>

<p><a href="#oben">back to the top</a></p>

<hr>


<p><a name="datenschutz"></a>
<br>
<b>Data Protection</b><br><BR>
<br><br>


ChessManiac.com takes upon itself the obligation not to publish personal data of players to third persons. <br>Private messages, personal chat using ChessManiac.com can be read and controlled by the webmaster. He will normally not make use of this possibility. In case of complaints, ChessManiac.com
insists on the right to look into personal messages and chat messages.
<br><br>

<p><a href="#oben">back to the top</a></p>

<hr>



<p><a name="account"></a>
<br>
<b>Deleting an Account</b><br><BR>
<br><br>
 Upon request an account can be deactivated. This leads to the player no longer being listed in the list of players and in the ranking. Complete deletion of an account as well as of games already played is not possible.

<br><br>

<p><a href="#oben">back to the top</a></p>

<hr>






<BR><font face="Verdana" size="1"><center>
Help System Version 0.1 of Dec. 19th b2004<br><br>
The Help System is under construction. In case you have questions or suggestions, or if you want to volunteer working on it, please send a message to <a href="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/messages.php">GMondwurf</a>. I look forward to your contributions. <br><br>
</font>
</p>

</td></tr></table>
<font face="verdana" size="2">
<font face="Verdana" size="2">
<br>
&nbsp;</font> </p>

<form name="logout" action="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI54.156/mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>


</body>
