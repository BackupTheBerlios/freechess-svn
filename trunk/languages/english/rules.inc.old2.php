<head>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
<title>ChessManiac Help</title></head>
<body bgcolor=white text=black>

<p><font face="verdana" size="2">
  <? require_once('header.inc.php');?>
</font><font face="verdana" size="2">
</font></p>
<table width="100%" border="1">
  <tr>
    <th colspan="2">Site Guidelines (Not current with the site at this time but
    for the most part will help you with what you need)</th>
  </tr>
  <tr>
    <td width="11%"  valign="top"><p align="left"><script type="text/javascript"><!--
google_ad_client = "pub-9606600691278870";
google_ad_width = 120;
google_ad_height = 600;
google_ad_format = "120x600_as";
google_ad_channel ="2208456598";
google_color_border = "DDAAAA";
google_color_bg = "ECF8FF";
google_color_link = "0033FF";
google_color_url = "0033FF";
google_color_text = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></p>
    </td>
    <td width="89%" rowspan="2"><p align="left"><font face="verdana" size="2"><font face="Verdana" size="2"><b>Rating
              Guidelines / Information</b><br>
  -------------------------------------------------------------------------------- <br>
  <br>
  - No player will have a rating less than 900. <br>
  - To be rated, ratings of players must be within 300 points of one another.<br>
  - The rating used to calculate points won or lost in a game will be the average
  of the players rating at the start of the game and the finish of the game.<BR>
  - Games in which no moves have been made for a period of
  <?=$CFG_EXPIREGAME?>
  days will be considered lost on time for the player on move. - Challenges that
  are not accepted will be deleted automatically in
  <?=$CFG_EXPIREGAME?>
  days.<BR>
  - Games with less than
  <?=$CFG_MIN_ROUNDS?>
  moves will not be rated.<BR>
    </font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2"><b>Rating
              Calculation</b><br>
                <BR>
  The Rating of each player is calculated through the formula:<font color="#FF0000">**</font><br>
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
  The loser loses: (XPv + 30) / 2<br>
  <br>
  XPv = Experience of the winner <br>
  XPp = Experience of the loser</font></font></p>
      <p align="left"><font face="Verdana" size="2"> Example:<br>
  Player (The Rating: 1400 and 50% of victories)<br>
  Player B (Rating: 1500 and 80% of victories)<br>
  Player B wins the game for Checkmate.<br>
  XPv = (80 - 100)/10 = -5<br>
  XPp = (50 - 100)/10 = -2<br>
  The player A will lose 12 points and player B will gain 14 points.</font></p>
      <p align="left"><font face="verdana" size="2"><font color="#FF0000" size="2" face="Verdana">**NOTE:
            As of 04/09/04 the rating that will be used to calculate your rating
            change will be the average of the rating when you started the game
            and the rating when you finished the game. </font><font face="Verdana" size="2"><br>
              <strong>Topics of Aid</strong> </font></font></p>
      <p align="left"><font face="verdana" size="2">Two ways to challenge someone
          to a game</font></p>
      <p align="left"><font face="verdana" size="2">P<font face="Verdana" size="2">layers
            can challenge in two possible ways: </font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2">1.
            Official games will be shown in the Ranking; </font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2">2.
            Friendly games that will not count for points and inexperienced players
            will feel comfortable playing and so that more experienced players
            will have the fear of losing rating points.</font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2">To
            invite somebody to a game, click on the Challenges button. Select
            the color that you desire to play and the type of game<font color="#FF0000">*</font>:
            To play an official rated game, you would select the option &quot;YES&quot; in
            the list of options that will appear to the side. In case you do
            not want to dispute points select, &quot;NO.&quot; Choose a player
            to be challenged and click in the button shown in the figure. Indicating
            OnLine The Pointer of OnLine Status serves you to know if an opponent
            is connected to the system at that moment. This will allow you to
            be able to challenge someone who is online, or to concentrate your
            game on the adversaries who are online, leaving challenges to offline
            opponents to think later. To know who he is online is very simple:
            In the first column of the main screen, there is a LED that will
            be a certian color, indicating the current status of a member. The
            LED can assume the following colors: </font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2">Red
            Color: This indicates that the member is not connected.</font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2">Green
            Color: This indicates that the member is connected. </font></font></p>
      <p align="left"><font face="verdana" size="2">This helps to <font face="Verdana" size="2">facilitate
            the communication between the members, by allowing you to find opponents
            online at the same time as you.</font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2">Tip:
            It places the cursor of mouse on the LED to know has how much time
            was the last access of the adversary.<br>
              <img src="images/help1.gif" width="93" height="58"> <br>
  Tip: You can also challenge someone to a game by looking at their statistics
  page. Note: when you challenge someone from this page a random color and a
  the defualt of 14 days per move is selected.<br>
    <font color="#FF0000"><br>
  *</font>When challenging someone to a game the time limits are total time for
  each player. This means that if you chose a playing time of 2 days, 3 days,
  4 days, 5 days, etc...you must complete the game within that time. This confuses
  a lot of players who are used to playing on sites that offer time limits of
  days per move. If you like to play days per move it is suggested that you choose
  the unlimited option. This will allow you to make a minimum of one move every
  14 days and of course you can always move faster if you like.</font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2"><strong>Chess
              Bots, who is?<font color="#FF0000">(This feature has not yet been
              activated yet)</font></strong></font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2">Bot
            is a shortened term of Robot. The Robot is an artificial person (computer
            program), that does not exist in the real world. The Chess Bots are
            permanent players on the system and are programmed computers to play
            chess. Each Bot has a <font face="verdana" size="2">different </font></font><font face="Verdana" size="2">level
            of difficulty, however, this difficulty will not be disclosed, similar
            in that &quot;more users can challenge to determine which is easy&quot; and &quot;most
            difficult&quot; of if being successful, proceeding in the same way
            as if the Chess Bots were &quot;humans&quot;. To play against the
            Chess Bots, select: to &quot;Play against computer&quot; inside of
            the section of challenges, select your opponent, your color, etc.
            Currently the players are Chess Bots: * Jellyfish; * Cerberus; *
            Harpia. The Chess Bots had been baptized with names of mythological
            creatures. If you know the story and saga of these creatures, you
            will be able to evaluate which is Chess Bot will be more difficult
            to beat. Remember that Chess Bots can refuse to play with you in
            the case that your is inferior to the minimum required for the Bot.</font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2"><strong>Last
              movement and stated periods </strong></font></font></p>
      <p align="left"><font face="verdana" size="2">G<font face="Verdana" size="2">ames
            have determined stated period of duration. However, <font face="verdana" size="2">to
            be considered in our databases, a game must </font></font><font face="Verdana" size="2"> have
            active movement within the stated period or 14 days. After this time,
            the last player to move will receive the victory and the adversary
            will suffer the same effect as if they had lost the game. On the
            main menu the column &quot;Last Movement&quot; will show the number
            of days that an opponent has not moved between parentheses. From
            10 days without movement, the pointer will be shown in the color
            Orange and after 12 days in the Red color. </font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2"><strong>System
              of Medals</strong> </font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2">The
            system of medals functions in the following way: </font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2"><strong>Medal
              of Honor:</strong><img src="images/rank/honour.gif" width="14" height="15"> <br>
  - This is given to the player who reachs first place in the rankings. All players
  who have reached first place at some point in their history will receive this
  medal. </font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2"><strong>Medals
              of Merit: <br>
  (&nbsp;<img src="images/rank/merit20.gif" width="8" height="15">&nbsp;Merit
  20) <br>
  (&nbsp;<img src="images/rank/merit50.gif" width="8" height="15">&nbsp;Merit
  50) <br>
  (&nbsp;<img src="images/rank/merit100.gif" width="8" height="15">&nbsp;Merit
  100) </strong><br>
  - These are given to the player who reachs definitive number of games with
  90% or more victories.<br>
  - Merit 20, 50, 100: All player who complete 20, 50 or 100 games with 90% or
  more victories will receive one from these medals (<font face="verdana" size="2">It
  is possible to get all three, but not more than one of the same medal at a
  time</font>). </font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2"><strong>Medals
              of Courage: <br>
  (&nbsp;<img src="images/rank/courage70.gif" width="7" height="15">&nbsp;Merit
  70) <br>
  (&nbsp;<img src="images/rank/courage80.gif" width="7" height="15">&nbsp;Merit
  80) <br>
  (&nbsp;<img src="images/rank/courage90.gif" width="7" height="15">&nbsp;Merit
  90) <br>
  (&nbsp;<img src="images/rank/courage100.gif" width="7" height="15">&nbsp;Merit
  100) </strong><br>
  - These are given to the player who initiates 10 or more games in the same
  day and wins at least 70% of them. - Courage 70, 80, 90, 100: All players who
  initiated 10 games in the same day and won 70%, 80%, 90% or 100% of the games
  will receive one of these medals (It is possible to get all four, but not more
  than one of the same medal at a time). </font></font></p>
      <p align="left"><font face="verdana" size="2"><font face="Verdana" size="2">Purple
            Heart:<img src="images/rank/purpleheart.gif" width="10" height="15"> <br>
  - This is given to a player who achieves the minimum rating (1300). All players
  who get this rating at some time in their history will receive this medal.</font></font></p>
      <p align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Public
            Chat Rules/Guidelines:</strong> <strong><font color="#FF0000">(Chat
            is not intended for use by individuals under the age of 13.)</font></strong></font></p>
      <p align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> Users
          are to be responsible and to respect our chess community. Your conduct
          should be guided by common sense, basic &quot;netiquette&quot;, and
          these chat guidelines.</font></p>
      <p align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">We
          discourage any of the following activity that:</font></p>
      <p align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> *
          Contains vulgarities directed toward another individual or group.<br>
  * Depicts violence in a gratuitous manner, without journalistic or artistic
  merit, primarily intended to agitate or cause emotional distress.<br>
  * Is intended to victimize, harass, degrade or intimidate an individual or
  group of individuals on the basis of age, disability, ethnicity, gender, race,
  religion or sexual orientation. Hate speech is unacceptable anywhere on the
  service.<br>
  * Solicits personal information from a minor (under 18 years old). Personal
  information includes full name, home address, home telephone number, or other
  identifying information that would enable &quot;offline&quot; contact.<br>
  * Contains or facilitates the transfer of software viruses or any other computer
  code, files or programs designed to interrupt, destroy or limit the functionality
  of any computer software or hardware or telecommunications equipment.<br>
  * Contains material that defames, abuses, threatens, promotes, or instigates
  physical harm or death to others, or oneself.<br>
  * Is illegal or incites illegal activity, such as instructional information
  on how to build a bomb and/or make counterfeit money.<br>
  * Solicits for exchange, sale or purchase of sexually explicit images, and/or
  material harmful to minors; including, but not limited to, any photograph,
  film, video, or picture or computer generated image or picture (actual or simulated).<br>
  * Infringes anyone else's intellectual property rights, including, but not
  limited to, any copyright, trademark, rights of publicity, rights of privacy,
  or other proprietary rights.<br>
  * Attempts to harvest or collect member information, including screen names.<br>
  * Impersonates or represents any person or entity in an attempt to deceive,
  harass or otherwise mislead another member. You may not pretend to be an employee
  or representative of this website, or any of the other affiliated web sites.<br>
  * Attempts to get a password, or other private information from a user. Remember:
  Administrators of this site will NEVER ask for your password.<br>
  * Links to and/or references content not allowed under these guidelines.<br>
      </font></p>
      <p align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Note:
          It is important to remember safety while online. Always use caution
          when providing any personal information about yourself anywhere online.
          It's also a good rule-of-thumb to check the Privacy Policies of any
          unfamiliar or new web sites you visit. When communicating in a chat
          room be mindful that many people will be able to view it and the inclusion
          of information such as your name, your address or telephone number
    is never recommended.</font></p></td>
  </tr>
  <tr>
    <td valign="top"><script type="text/javascript"><!--
google_ad_client = "pub-9606600691278870";
google_ad_width = 120;
google_ad_height = 600;
google_ad_format = "120x600_as";
google_ad_channel ="2208456598";
google_color_border = "DDAAAA";
google_color_bg = "ECF8FF";
google_color_link = "0033FF";
google_color_url = "0033FF";
google_color_text = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></td>
    
  </tr>
</table>
<p><font face="verdana" size="2">  <BR>
</font></p>
<p>

<font face="verdana" size="2">
<font face="Verdana" size="2">
<br>
&nbsp;</font> </p>

<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>


</body>
