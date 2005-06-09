<?php
##############################################################################################
#                                                                                            #
#                                stats_team.php                                                
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
	//require_once ( 'chessconstants.php');
	//require_once ( 'newgame.php');
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



if (!isset($_GET['voltar']))
    $voltar = "mainmenu.php";
else $voltar = $_GET['voltar'];

?>

<html>
<head>
	<title>ChessManiac</title>

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
<BR>
<?
    	$p = mysql_query("SELECT * FROM team_members,team WHERE fk_team=teamID AND fk_team='$_GET[cod]'");
    	$row = mysql_fetch_array($p);
	    $myteam = $row['fk_team'];

		$p = mysql_query("SELECT * FROM players WHERE playerID='$row[fk_creator]'");
		$r = mysql_fetch_array($p);
		$creator = $r[firstName];

		$p2 = mysql_query("SELECT * FROM team_members,players WHERE ativo='1' and fk_player=playerID AND fk_team='$myteam' AND level > 0");
		$members = mysql_num_rows($p2);
		
		$p = mysql_query("SELECT * FROM team_members,players WHERE ativo='1' and fk_player=playerID AND fk_team='$myteam' AND level = 100");
		$r = mysql_fetch_array($p);
		$leader = $r[firstName];

		echo "<input type='hidden' name='action' value='leaveteam'>";
		echo "<table border='1' width='100%'>";
		echo "<tr><th colspan=2>$row[name]</th></tr>";

		echo "<tr><th>".$MSG_LANG['rating'].":</th><td>".getTeamRating($row[teamID])."</td></tr>";
		echo "<tr><th>".$MSG_LANG['members'].":</th><td>".$members."</td></tr>";
		echo "<tr><th>".$MSG_LANG['creator'].":</th><td>".$creator."</td></tr>";
		echo "<tr><th>".$MSG_LANG['leader'].":</th><td>".$leader."</td></tr>";
		echo "<tr><th>".$MSG_LANG['created'].":</th><td>".date("d/m/Y H:i",$row[created])."</td></tr>";
		echo "<tr><th>".$MSG_LANG['description'].":</th><td>".nl2br($row[description])."</td></tr>";
		echo "<tr><td colspan=2>&nbsp;</td></tr>";
		echo "</table>";

		echo "<BR>";

		echo "<table border='1' width='100%'>";
		echo "<tr><th colspan=2>".$MSG_LANG["teammembers"]."</th></tr>";

		echo "<tr><th>".$MSG_LANG[name]."</th><th>".$MSG_LANG['rating']."/".$MSG_LANG['max']." (".$MSG_LANG['initial'].")</th></tr>";
		while ($row2 = mysql_fetch_array($p2)){
			echo "<tr><td align=center><a href='stats_user.php?cod=$row2[playerID]'>$row2[firstName]</a></td><td align=center>$row2[rating]/$row2[rating_max] ($row2[init_rating])</td></tr>";
		}
		echo "<tr><td colspan=2>&nbsp;</td></tr>";
		echo "</table>";
		echo "<BR>";



?>

<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>

</body>
</html>

<? //mysql_close(); ?>

