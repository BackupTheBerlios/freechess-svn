<?php
##############################################################################################
#                                                                                            #
#                                teamranking.php                                                
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
	<title>ChessManiac Teams</title>

<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">

</head>

<body bgcolor=white text=black>
<font face=verdana size=2>
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
	</font><BR>

    <table border="1">
      <tr>
        <th>Ranking By:</th>
      </tr>
      <tr>
        <td align="center"><input name="button92" class="BOTOES" type="button" style="cursor: hand"onClick="window.location='teamranking.php'" value="Team Points">
            <input name="button93" class="BOTOES" type="button" style="cursor: hand" onClick="window.location='teamranking_wins.php'" value="Team Wins">
            <input name="button9" class="BOTOES" type="button" style="cursor: hand" onClick="window.location='teamranking_rating.php'" value="Team Avg. Rating">
        </td>
      </tr>
    </table>
    <br>

 	<?=//$MSG_LANG['teamrankingmessage']?>

    <table border="1" width="600">
	<tr><th colspan=4><font size=+1>Ranking by Total Team Points - All Matches</font></th>
	</tr>
	<tr>
	<th>&nbsp;</th>
	<th><?=$MSG_LANG["punctuation"]?></th>
    <th><?=$MSG_LANG["rating"]?></th>
	<th><?=$MSG_LANG["name"]?></th>
	</tr>
<?
	$n=$inicio+1;
	$p = mysql_query("SELECT * from team WHERE points>0 order by points DESC");
 	$ratings = array();
	while ($row = mysql_fetch_array($p)){
		$p2 = mysql_query("SELECT * from team_members WHERE level>0 AND fk_team='$row[teamID]'");
		if (mysql_num_rows($p2)>=2){
			$ratings[$row[teamID]] = $row[points].":".getTeamRating($row['teamID']);
		}
	}
		
	natsort($ratings);
	$ratings = array_reverse($ratings,true);
	
	while (list($id,$rating) = each($ratings)){
		$p = mysql_query("SELECT * from team WHERE teamID = '$id'");
		$row = mysql_fetch_array($p);
		$name = $row[name];
		$r = explode(":",$rating);
		$rating = number_format($r[1],2,".","");

		echo "
		<tr><td width=1>$n</td>
		<td>$row[points]</td>
		<td>$rating</td>
		<td><a href='stats_team.php?cod=$id'>$name</a></td>
		</tr>";
		$n++;
	}


?>
</table>


<form name="logout" action="mainmenu.php" method="post">
	<input type="hidden" name="ToDo" value="Logout">
</form>

</body>
</html>

<? //mysql_close(); ?>
