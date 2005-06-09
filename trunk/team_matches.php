<?php
##############################################################################################
#                                                                                            #
#                                team_matches.php                                                
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

function verify_leader($teamID,$playerID){
		$p = mysql_query("SELECT * FROM team_members WHERE fk_team='$teamID' AND fk_player='$playerID'");
		$r = mysql_fetch_array($p);

		if ($r[level] < 100){
		    echo "ERROR: You aren´t the Team Leader!";
			exit;
		}
}

?>

<html>
<head>
    <title>ChessManiac Teams</title>
	<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
	<script>
	function confirma(action){
		if (action != "")
		    document.team.action.value=action;
		if (confirm('<?=$MSG_LANG['areyousure']?>'))
			document.team.submit();
	}
	</script>
</head>

<body bgcolor=white text=black>
<? require_once('header.inc.php');?>
<font face=verdana size=2>
<table border="1" width="100%">
  <th><?=$MSG_LANG["teams"]?>
    </th>
  <tr>
    <td align="center">
      <input name="button33" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='teams.php'" value="<?=$MSG_LANG["team"]?>">
      <input name="button33" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='teams_all.php'" value="<?=$MSG_LANG["teams"]?>">
      <input name="button15" type="button" class="BOTOES" style="cursor: hand"onClick="window.location='team_matches.php'" value="Matches">
      <input name="button62" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='teamranking.php'" value="<?=$MSG_LANG["teamranking"]?>">
      <input name="button15" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='team_matches_finished.php'" value="Finished Matches">
    </td>
  </tr>
</table>
</font><br>

<?

	$mf = mysql_query("SELECT * FROM games WHERE team > 0 AND gameMessage !=''");

	$fm = mysql_num_rows($mf);

	if ($fm > 0){	

	echo "<table border='1'><tr><th colspan='6'><font size=+1>All Active Matches</font></th></tr>";

	$tgm = mysql_query("SELECT * FROM matches WHERE fk_player = ".$_SESSION['playerID']." and gameMessage = ''");

	while($tm=mysql_fetch_array($tgm)){

	$tmp_tmmtch = mysql_query("SELECT * FROM team WHERE teamID = '$tm[team1]'"); 
	$tmatch = db_result_to_array($tmp_tmmtch); 
	$team1 = $tmatch[0][1]; 

	$tmp_tteams = mysql_query("SELECT * FROM team WHERE teamID = '$tm[team2]'"); 
	$matcht = db_result_to_array($tmp_tteams); 
	$team2 = $matcht[0][1]; 

	echo "<tr>
	<th><br><a href='stats_team.php?cod=".$tm[team1]."'><b>".$team1."</b></a> vs <a href='stats_team.php?cod=".$tm[team2]."'><b>".$team2."</b></a><br>&nbsp;</th><th><b>Match # ".$tm[match_id]."</b></font></th><th><b>Result</b></font></th><th colspan='2'><b>Match Score</b><br>".$team1.":&nbsp;&nbsp;";

	$team1 = $tmatch[0][0];

	$cv = mysql_query("SELECT * FROM matches WHERE team1 = '$team1' AND match_id = '$tm[match_id]'");

	$mn = mysql_fetch_array($cv);

	echo "<b>".$mn[match_points1]."</b><br>".$team2.":&nbsp;&nbsp;";

	$team2 = $matcht[0][0]; 

	$qw = mysql_query("SELECT * FROM matches WHERE team2 = '$team2' AND match_id = '$tm[match_id]'");

	$kp = mysql_fetch_array($qw);

	echo "<b>".$kp[match_points2]."</b></font></th></tr>";

	$color1 = "#E5F2FF"; 
    	$color2 = "#DDDDDD"; 
    	$row_count = 0; 

	$mt = mysql_query("SELECT * FROM games WHERE team = '$tm[match_id]'");

	while($rw = mysql_fetch_array($mt)){


	$w = mysql_query("SELECT * FROM players WHERE playerID = '$rw[whitePlayer]'"); 
	$pt = db_result_to_array($w); 
	$opt = $pt[0][2]; 

	$s = mysql_query("SELECT * FROM players WHERE playerID = '$rw[blackPlayer]'"); 
	$vp = db_result_to_array($s); 
	$yls = $vp[0][2]; 

	$row_color = ($row_count % 2) ? $color1 : $color2;

	echo "<tr><td>".$opt." vs ".$yls."</td><td><input cursor:hand' type=button value='".$MSG_LANG['analyze']."' onClick=window.open('analyze.php?game=".$rw[gameID]."','_blank','toolbar=no,status=no,menubar=no,scrollbars=no,width=800,height=625')></td>";

$gameID = $rw[gameID];

$result = mysql_query("SELECT p1.firstName AS player1_name, p2.firstName AS
player2_name FROM games g LEFT JOIN players p1 ON (g.whitePlayer =
p1.playerID) LEFT JOIN players p2 ON (g.blackPlayer = p2.playerID) WHERE
g.gameID = $gameID");
if(mysql_error()) die(mysql_error());

$row = mysql_fetch_array($result);
if(mysql_error()) die(mysql_error());

$result2 = mysql_query("SELECT gameMessage, messageFrom FROM games WHERE gameID = '".$rw[gameID]."'"); 
$p2 = mysql_query("SELECT count(*) from history WHERE gameID='".$rw[gameID]."'");
$row3 = mysql_fetch_array($p2); 
$rounds = floor(($row3[0]+1)/2);  
$row2 = mysql_fetch_array($result2); 
$result_msg = ""; 
if ($row2[0] == "checkMate" AND $row2[1] == "white") $result_msg="".$opt." Checkmates ".$yls.""; 
if ($row2[0] == "checkMate" AND $row2[1] == "black") $result_msg="".$yls." Checkmates ".$opt.""; 
if ($row2[0] == "playerResigned" AND $row2[1] == "white") $result_msg="".$opt." Resigns";
if ($row2[0] == "playerResigned" AND $row2[1] == "black") $result_msg="".$yls." Resigns"; 
if ($row2[0] == "draw") $result_msg="Draw"; 

$result_score = ""; 
if ($row2[0] == "checkMate" AND $row2[1] == "white") $result_score="".$opt." Wins 2 Points"; 
if ($row2[0] == "checkMate" AND $row2[1] == "black") $result_score="".$yls." Wins 2 Points"; 
if ($rounds < $CFG_MIN_ROUNDS){
		if ($row2[0] == "playerResigned" AND $row2[1] == "white") $result_score="0 Points: Less than ".$CFG_MIN_ROUNDS." Moves Completed"; 
		}
		else{
			if ($row2[0] == "playerResigned" AND $row2[1] == "white") $result_score="".$yls." Wins 2 Points";
		}
 
if ($rounds < $CFG_MIN_ROUNDS){
		if ($row2[0] == "playerResigned" AND $row2[1] == "black") $result_score="0 Points: Less than ".$CFG_MIN_ROUNDS." Moves Completed"; 
		}
		else{
		if ($row2[0] == "playerResigned" AND $row2[1] == "black") $result_score="".$opt." Wins 2 Points"; 
		}
if ($row2[0] == "draw" AND $row2[1] == "white")$result_score="".$yls." Wins 1 Point";
if ($row2[0] == "draw" AND $row2[1] == "black")$result_score="".$opt." Wins 1 Point";

	echo "<td>".$result_msg."</td><td>".$result_score."</td></tr>";

	$row_count++; 

	}

	}

	echo "</table>";

	}

	else {

	echo "<table><tr><td>There are currently no active matches.</td></tr></table>";

	}

?>

<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>

</body>
</html>
