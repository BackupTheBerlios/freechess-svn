<?php
##############################################################################################
#                                                                                            #
#                                my_games.php                                                #
# *                            -------------------                                           #
# *   begin                : Friday, January 15, 2005                                        #
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://developer.berlios.de/projects/chess/                              #
# *   VERSION:             : $Id: my_games.php,v 1.1 2005/01/28 03:05:52 trukfixer Exp $
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

include_once('global_includes.php');

    require_once ( 'chessutils.php');

    require_once ( 'newgame.php');
    require_once('chessdb.php');



    /* Language selection */
    require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");
?>
<html>
<head>
    <title>WebChess</title>
    <meta name="Keywords" content="chess,ajedrez,échecs,echecs,scacchi,schach,check,check mate,jaque,jaque mate,queenalice,queen alice,queen,alice,play,game,games,turn based,correspondence,correspondence chess,online chess,play chess online">

<script type="text/javascript">
        function loadGame(game_id)
        {
            //if (document.existingGames.rdoShare[0].checked)
            //    document.existingGames.action = "opponentspassword.php";

            document.existingGames.game_id.value = game_id;
            document.existingGames.submit();
        }
        function loadEndedGame(game_id)
        {
            document.existingGames.game_id.value = game_id;
            document.existingGames.submit();
        }

    </script>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["style"]?>/styles.css" type="text/css">
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>


<form name="existingGames" action="chess.php" method="post">

<table border="1" width="100%">
    <tr>
        <th colspan=8><?=$MSG_LANG["gamesinprogress"]?></th>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <th><?=$MSG_LANG["opponent"]?></th>
                 <th><?=$MSG_LANG["tournamentmain"]?></th>
        <th><?=$MSG_LANG["yourcolor"]?></th>
        <th><?=$MSG_LANG["turn"]?></th>
        <!--
        <th><?=$MSG_LANG["start"]?></th>
        -->
        <th><?=$MSG_LANG["lastmove"]?></th>
        <th><?=$MSG_LANG["official"]?></th>
        <th>&nbsp;</th>
    </tr>
<?
         $query = "SELECT games.*,DATE_FORMAT(dateCreated, '%d/%m/%y %H:%i') as created, DATE_FORMAT(lastMove, '%d/%m/%y %H:%i') as lastm FROM games WHERE (whitePlayer = ".$_SESSION['playerID']." OR blackPlayer = ".$_SESSION['playerID'].") ORDER BY dateCreated";

         $tmpGames = mysql_query($query);

    if (mysql_num_rows($tmpGames) == 0)
        echo("<tr><td colspan='8'>".$MSG_LANG["youdonthavegames"]."</td></tr>\n");

         else
    {
        while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
        {

                         if (!$tmpGame['gameMessage'])
                         {

            /* get opponent's nick */
            if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
                $tmpOpponent = mysql_query("SELECT firstName,lastName,lastUpdate,engine,playerID FROM players WHERE playerID = ".$tmpGame['blackPlayer']);
            else
                $tmpOpponent = mysql_query("SELECT firstName,lastName,lastUpdate,engine,playerID FROM players WHERE playerID = ".$tmpGame['whitePlayer']);
            $row = mysql_fetch_array($tmpOpponent);
            $opponent = substr($row[0],0,25);

            if ($row[2] >= (time()-300))
                $img="online";
            else
                 $img="offline";

            if ($row[2] == "0"){
                $txt = $img;
            }
            else{
                $m = floor((time()-$row[2])/60);
                $t = $MSG_LANG["min"];
                if ($m>60){
                    $m = floor($m/60);
                    $t = $MSG_LANG["hs"];
                    if ($m>24){
                        $m = floor($m/24);
                        $t = $MSG_LANG["days"];
                    }
                }
                $txt = $img." $m $t";
            }
            echo "<tr><td><img src='images/$img.gif' alt='$txt' title='$txt'></td>";

            /* Opponent */
            echo("<td>");
            echo("<a href='stats_user.php?cod=".$row['playerID']."'>".$opponent."</a>");
            //echo $opponent;

            /* Your Color */
            echo ("</td>");

            if ($tmpGame['tournament'] != 0)
            {
                $t = mysql_fetch_array(mysql_query("SELECT * FROM tournaments WHERE id = '".$tmpGame['tournament']."'"));

                 echo "<td><a href=\"tournaments.php?action=view&id=".$tmpGame['tournament']."\">$MSG_LANG[number]".stripslashes($t['id'])."</a></td>";
            }
            else
            {
                echo "<td>$MSG_LANG[no]</td>";
            }

            echo "<td>";


            if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
            {
                echo $MSG_LANG["white"];
                $tmpColor = "white";
            }
            else
            {
                echo $MSG_LANG["black"];
                $tmpColor = "black";
            }

            /* Current Turn */
            echo ("</td><td>");
            /* get number of moves from history */
            $tmpNumMoves = mysql_query("SELECT COUNT(gameID) FROM history WHERE gameID = ".$tmpGame['gameID']);
            $numMoves = mysql_result($tmpNumMoves,0);

            /* based on number of moves, output current color's turn */
            if (($numMoves % 2) == 0)
                $tmpCurMove = "white";
            else
                $tmpCurMove = "black";

            if ($tmpCurMove == $tmpColor)
                echo("<B><font color=red>".$MSG_LANG["yourturn"]."</font></b>");
            else
                echo $MSG_LANG["waiting"];

            /* Start Date */
            //echo ("</td><td nowrap>".$tmpGame['created']);

            /* Last Move */
                //duracao:
                $agora = mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y"));
                $v = explode(" ",$tmpGame[lastm]);
                $hora = explode(":",$v[1]);
                $data = explode("/",$v[0]);
                $lastmove = mktime($hora[0],$hora[1],0,$data[1],$data[0],$data[2]);

                $d = floor(($agora-$lastmove)/60/60/24);
                $h = floor(($agora-$lastmove)/60/60) - 24*$d;
                $m = round(($agora-$lastmove)/60) - 60*$h - $d*24*60;

            if ($d>=($CFG_EXPIREGAME-1))$cor="#FF000";
            elseif ($d>=($CFG_EXPIREGAME-2))$cor="#CC3300";
            elseif ($d>=($CFG_EXPIREGAME-3))$cor="#CC6600";
            elseif ($d>=($CFG_EXPIREGAME-4))$cor="#FF9900";
            else $cor ="black";

            if ($d ==1)$txt = $MSG_LANG['day'];
            else $txt = $MSG_LANG['days'];

            echo ("</td><td nowrap><font color='$cor'>".$tmpGame['lastm']." ($d $txt)</td>");

            if ($tmpGame['oficial'] == "1")
                $oficial = $MSG_LANG["yes"];
            else $oficial = $MSG_LANG["no"];

            echo "<td>".$oficial."</td>";

            echo "<td><input style='font-size:11' type=button value='".$MSG_LANG['play']."' onClick='loadGame(".$tmpGame['gameID'].")'></td></tr>\n";
                  }
        }

        /* share PC */
        //echo ("<tr><td colspan='3'>Will both players play from the same PC?</td>");
        //echo ("<td><input type='radio' name='rdoShare' value='yes'> Yes</td>");
        //echo ("<td><input type='radio' name='rdoShare' value='no' checked> No</td></tr>\n");
    }
?>
  </table>

        <input type='hidden' name='rdoShare' value='no'>
        <input type="hidden" name="gameID" value="">
        <input type="hidden" name="sharePC" value="no">
</form>

<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>
<form name="showGames" action="chess.php" method="post">
        <input type='hidden' name='rdoShare' value='no'>
        <input type="hidden" name="gameID" value="">
        <input type="hidden" name="sharePC" value="no">
</form><br>

<? include("footer.inc.php");?>
</body>
</html>
