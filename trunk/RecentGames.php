<?php
##############################################################################################
#                                                                                            #
#                                RecentGames.php                                                
# *                            -------------------                                           #
# *   begin                : Wednesday, January 25, 2005                                     
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://developer.berlios.de/projects/chess/                              #
# *   VERSION:             : $Id$                                           
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


    /* load settings */
    include_once ('config.php');

    /* load external functions for setting up new game */
    require_once ( 'chessutils.php');
    require_once ( 'chessconstants.php');
    require_once ( 'newgame.php');
    require_once('chessdb.php');
    
    
    /* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
    fixOldPHPVersions();

    /* if this page is accessed directly (ie: without going through login), */
    /* player is logged off by default */
    if (!isset($_SESSION['playerID']))
        $_SESSION['playerID'] = -1;
    
    /* connect to database */
    require_once( 'connectdb.php');
    
	/* check session status */
	require_once('sessioncheck.php');

	/* Language selection */
	require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");
?>
<html>
<head>
    <title>ChessManiac</title>
	<meta name="Keywords" content="chess,ajedrez,échecs,echecs,scacchi,schach,check,check mate,jaque,jaque mate,queenalice,queen alice,queen,alice,play,game,games,turn based,correspondence,correspondence chess,online chess,play chess online">
	
<script type="text/javascript">
		function loadGame(gameID)
		{
			//if (document.existingGames.rdoShare[0].checked)
			//	document.existingGames.action = "opponentspassword.php";

			document.existingGames.gameID.value = gameID;
			document.existingGames.submit();
		}
		function loadEndedGame(gameID)
		{
			document.existingGames.gameID.value = gameID;
			document.existingGames.submit();
		}
		
	</script>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
</head>
<?
	//echo("<meta HTTP-EQUIV='Pragma' CONTENT='no-cache'>\n");
    //echo "<META HTTP-EQUIV=Refresh CONTENT='";
    //if ($_SESSION['pref_autoreload'] >= 120)
        //echo ($_SESSION['pref_autoreload']);
	//else
        //echo ($CFG_MINAUTORELOAD);
	//echo ("; URL=MyGames.php'>\n");
?>
<body bgcolor=white text=black>
<font face=verdana size=2> 
<? require_once('header.inc.php');?> 
<br>
<? include("ads/chess.inc.php");?>
<br>
<table border="1" width="100%">
<?
    $p = mysql_query("SELECT games.*,DATE_FORMAT(dateCreated, '%d/%m/%y') as inicio,DATE_FORMAT(lastMove, '%d/%m/%y %H:%i') as fim from games where gameMessage<>'' AND  gameMessage<>'playerInvited' AND gameMessage<>'inviteDeclined' ORDER BY lastMove DESC LIMIT 100");
    if (mysql_num_rows($p)>0){
        echo "<tr><th colspan=2><B>".$MSG_LANG["gamesfinishedrecently"]."</B></td></tr>";
        while ($row = mysql_fetch_array($p)){
            $p2 = mysql_query("SELECT firstName,playerID from players WHERE playerID='$row[whitePlayer]'");
            $row2 = mysql_fetch_array($p2);
            $white = $row2[0];
            $idw = $row2[1];

            $p2 = mysql_query("SELECT firstName,playerID from players WHERE playerID='$row[blackPlayer]'");
            $row2 = mysql_fetch_array($p2);
            $black = $row2[0];
            $idb = $row2[1];

            $whitewin="";
            $blackwin="";

            if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "white")
			     $blackwin = "#";
            else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "black")
			     $whitewin = "#";
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "white")
			     $whitewin = "#";
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "black")
			     $blackwin = "#";
            else if ($row['gameMessage'] == "draw")
			     $draw=true;


            //echo "<tr><td colspan=2 style='text-align:left'>[$row[fim]] $whitewin <a href='stats_user.php?cod=$idw'>$white</a> x <a href='stats_user.php?cod=$idb'>$black</a> $blackwin </td></tr>";
			echo "<tr><td colspan=2 style='text-align:left'>[$row[fim]] $whitewin <a href='#' onClick='document.showGames.gameID.value=$row[gameID];document.showGames.submit()'>$white x $black</a> $blackwin </td></tr>";
        }
    }
?>
</table>
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
