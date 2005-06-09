<?php
##############################################################################################
#                                                                                            #
#                               teams_all.php                                                
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

<body>
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
	$tms = mysql_query("SELECT * FROM team");
	
	while ($tm = mysql_fetch_array($tms)){

	echo "<table border='1'><tr><th>Team Name</th><th>Team Description</th></tr><tr><td style='width:50%;'><font size=+1><b>".$tm['name']."<b></font></td><td>".$tm['description']."</td></tr>";
		$p = mysql_query("SELECT * FROM players WHERE playerID='$row[fk_creator]'");
		$r = mysql_fetch_array($p);
		$creator = $r[firstName];
		
		$p2 = mysql_query("SELECT * FROM team_members,players WHERE fk_team='$tm[teamID]' AND fk_player=playerID AND level > 0");
		$members = mysql_num_rows($p2);

		//echo "<tr><td>";
		
		while ($row2 = mysql_fetch_array($p2)){
			echo "<tr><td style='width:50%'><a href='stats_user.php?cod=$row2[playerID]'>$row2[firstName]</a></font></td><td style='width:50%'>Rating: $row2[rating]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Initial Rating: $row2[init_rating]</td>";

			//echo "</tr>";
		}
		echo "<tr><td colspan=3>";
		$jt = mysql_query("SELECT * FROM team_members WHERE fk_player='$_SESSION[playerID]'");
		$num_rows = mysql_fetch_array($jt);

		if ($num_rows =="0"){
?>
<form name="thisteam" action="teams.php" method="post">

<?
if ($_POST['action'])
	$action = $_POST['action'];
else if ($_GET['action'])
	$action = $_GET['action'];

if ($action == 'choose'){

	mysql_query("INSERT INTO team_members (fk_player,fk_team,date,init_rating,level) VALUES ('$_SESSION[playerID]','$_POST[teamid]','".time()."','".getRating($_SESSION[playerID])."','0')");
	echo "<script>window.location='teams.php'</script>";

}

?>
		<input type='hidden' name='action' value='choose'>
		<input type='hidden' name='teamid' value='<?=$tm[teamID]?>'>
	<input style='cursor: hand' type=submit value='<?=$MSG_LANG['asktojoin']?>'>
</form>
<?
}
		echo "</table><br>";

	}

	//echo "</table>";

?>
<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>
</body>
</html>
