<?php
##############################################################################################
#                                                                                            #
#                                teams.php                                                
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

/* Get Posted Variables */ 
   $my_team = $_POST['myteam']; 
   $invitedteam = $_POST['opponent']; 
   $size = $_POST['size']; 
   $time = $_POST['timelimit']; 
   
function verify_leader($teamID,$playerID){
		$p = mysql_query("SELECT * FROM team_members WHERE fk_team='$teamID' AND fk_player='$playerID'");
		$r = mysql_fetch_array($p);

		if ($r[level] < 100){
		    echo "ERROR: You aren´t the Team Leader!";
			exit;
		}
}

switch($_POST['ToDo']){ 
      case 'InviteTeam': 

         /* prevent multiple pending requests between two players with the same originator */ 
         $tmpQuery = "SELECT match_id FROM matches WHERE (gameMessage = '' OR gameMessage='TeamInvited')"; 
         $tmpQuery .= "AND ((team1 = ".$my_team." AND team2= ".$_POST['opponent'].")"; 
         $tmpQuery .= " OR (team1=".$_POST['opponent']." AND team2=".$my_team."))"; 
         $tmpExistingRequests = mysql_query($tmpQuery); 
         if (mysql_num_rows($tmpExistingRequests) == 0) 
         { 

            //generate random color for board one 
            $tmpColor = (rand(0,1) == 1) ? "white" : "black"; 
            if ($tmpColor == 'white'){ 
                $white = $my_team; 
                $black = $invitedteam; 
            }else{ 
               $white = $invitedteam; 
                    $black = $my_team; 
                } 
            if ($white!=$black){ 
            $result = mysql_query( "INSERT INTO matches (team1, team2, gameMessage, messageFrom, dateCreated, boards, adj_time) 
                        VALUES ('$white', '$black', 'TeamInvited', '$tmpColor', NOW(), '$size', '$time')"); 
               if (!$result){ 
                  $warning=2; 
                  } 
               else{ 
                  $warning=3; 
                  } 
            } 
            else{$warning=1;} 

            /* if email notification is activated... */ 
#            if ($CFG_USEEMAILNOTIFICATION) 
#            { 
#               /* if opponent is using email notification... */ 
#               $tmpOpponentEmail = mysql_query("SELECT value FROM ch_preferences WHERE playerID = ".$_POST['opponent']." AND preference = 'emailNotification'"); 
#               if (mysql_num_rows($tmpOpponentEmail) > 0) 
#               { 
#                  $opponentEmail = mysql_result($tmpOpponentEmail, 0); 
#                  if ($opponentEmail != '') 
#                  { 
#                     /* notify opponent of invitation via email */ 
#                     webchessMail('invitation', $opponentEmail, '', $_SESSION['nick']); 
#                  } 
#               } 
#            } 
      } 
      else{$warning=4;}; 
         break; 
      case 'ResponseToInviteTeam': 
         if ($_POST['response'] == 'accepted') 
         { 
            /* update game data */ 
            $tmpQuery = "UPDATE matches SET gameMessage = 'waiting', messageFrom = '' WHERE match_id = ".$_POST['match_id']; 
            mysql_query($tmpQuery); 
         } 
         else 
         { 

            $tmpQuery = "UPDATE matches SET gameMessage = 'inviteDeclined', messageFrom = '".$_POST['messageFrom']."' WHERE match_id = ".$_POST['match_id']; 
            mysql_query($tmpQuery); 
         } 

         break; 

      case 'JoinMatch': 
         $check = mysql_query("SELECT * FROM match_players WHERE playerID = '".$_SESSION['playerID']."' AND match_id = ".$_POST['match_id']); 
            if (mysql_num_rows($check) == 0){ 
            // add player to match list 
            $tmpQuery = "INSERT INTO match_players (match_id, playerID, teamID) 
                        VALUES ('".$_POST['match_id']."', '".$_SESSION['playerID']."', '".$_POST['teamID']."')"; 
            mysql_query($tmpQuery); 
            //now check to see if match filled 
            $tmpboards = mysql_query("SELECT boards FROM matches WHERE match_id = ".$_POST['match_id']); 
            $boards = mysql_result($tmpboards, 0); 
            $tmpplayers = mysql_query("SELECT * FROM match_players WHERE match_id = ".$_POST['match_id']); 
            if (mysql_num_rows($tmpplayers) == 2*$boards){ 
               //create all games 
               create_match_games($_POST['match_id']); 
            } 
         } 
         break; 

      case 'WithdrawRequest': 

         /* get opponent's player ID */ 
         $tmpOpponentID = mysql_query("SELECT team1 FROM matches WHERE match_id = ".$_POST['match_id']); 
         if (mysql_num_rows($tmpOpponentID) > 0) 
         { 
            $opponentID = mysql_result($tmpOpponentID, 0); 
            if ($opponentID == $_SESSION['playerID']) 
            { 
               $tmpOpponentID = mysql_query("SELECT team2 FROM games WHERE gameID = ".$_POST['gameID']); 
               $opponentID = mysql_result($tmpOpponentID, 0); 
            } 
            $tmpQuery = "DELETE FROM games WHERE gameID = ".$_POST['gameID']; 
            mysql_query($tmpQuery); 
         } 
         break; 
}//end of switch statement 

if (getRating($_SESSION['playerID']) == 0)
	displayError("You can't join a team until you have official rating.  To establish a rating you need to finish 5 games.","mainmenu.php");

?>
<html>
<head>
    <title>ChessManiac</title>
	<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
	<script>
	function confirma(action){
		if (action != "")
		    document.team.action.value=action;
		if (confirm('<?=$MSG_LANG['areyousure']?>'))
			document.team.submit();
	}
	 
            function sendResponse(responseType, messageFrom, match_id) 
                { 
                        document.responseToInvite.response.value = responseType; 
                        document.responseToInvite.messageFrom.value = messageFrom; 
                        document.responseToInvite.match_id.value = match_id; 
                        document.responseToInvite.submit(); 
                } 

                function withdrawRequest(match_id) 
                { 
                        document.withdrawRequestForm.match_id.value = match_id; 
                        document.withdrawRequestForm.submit(); 
                } 
                function joinmatch(match_id, teamID) 
                { 
                        document.JoinMatchForm.match_id.value = match_id; 
                        document.JoinMatchForm.teamID.value = teamID;
                        document.JoinMatchForm.submit(); 
                } 
        </script> 
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>
<? require_once('header.inc.php');?>

</font>
<table border="1" width="100%">
  <th><font size="2" face="verdana">
      <?=$MSG_LANG["teams"]?>
    </font></th>
  <tr>
    <td align="center"><font size="2" face="verdana">
      <input name="button33" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='teams.php'" value="<?=$MSG_LANG["team"]?>">
      <input name="button33" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='teams_all.php'" value="<?=$MSG_LANG["teams"]?>">
      <input name="button15" type="button" class="BOTOES" style="cursor: hand"onClick="window.location='team_matches.php'" value="Matches">
      <input name="button62" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='teamranking.php'" value="<?=$MSG_LANG["teamranking"]?>">
      <input name="button15" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='team_matches_finished.php'" value="Finished Matches">
    </font></td>
  </tr>
</table>
<font face=verdana size=2>
<? 
 //load user data 
$player_id = $_SESSION['playerID']; 
$p = mysql_query("select fk_team,level from team_members where fk_player='$player_id'"); 
$my_team = mysql_fetch_array($p); 
$level=$my_team[1];
$my_team=$my_team[0]; 

if ($level > 0){
display_matches_waiting($my_team); 
}

?>


<form name="team" action="teams.php" method="post">
  <?
if ($_POST['action'])
	$action = $_POST['action'];
else if ($_GET['action'])
	$action = $_GET['action'];


if ($action == 'leaveteam'){
	mysql_query("DELETE FROM team_members WHERE fk_player='$_SESSION[playerID]'");
	echo "<script>window.location='teams.php'</script>";
}	
elseif ($action == 'deluser'){
	verify_leader($_GET[teamid],$_SESSION[playerID]);
	mysql_query("DELETE FROM team_members WHERE fk_team='$_GET[teamid]' AND fk_player='$_GET[playerid]'");
	echo "<script>window.location='teams.php'</script>";
}
elseif ($action == 'acceptinvite'){

	$p = mysql_query("SELECT 1 FROM team_members WHERE fk_team='$_GET[teamid]' AND level > 0");
	$members = mysql_num_rows($p);
	if ($members >= $TEAM_LIMIT)
	    displayError($MSG_LANG['teamfull']);

	mysql_query("UPDATE team_members SET level='10' WHERE fk_player='$_SESSION[playerID]'");
	echo "<script>window.location='teams.php'</script>";
}
elseif ($action == 'acceptuser'){
	verify_leader($_GET[teamid],$_SESSION[playerID]);
	$p = mysql_query("SELECT 1 FROM team_members WHERE fk_team='$_GET[teamid]' AND level > 0");
	$members = mysql_num_rows($p);
	if ($members >= $TEAM_LIMIT)
	    displayError($MSG_LANG['teamfull']);
	
	mysql_query("UPDATE team_members SET level='10' WHERE fk_team='$_GET[teamid]' AND fk_player='$_GET[playerid]'");
	echo "<script>window.location='teams.php'</script>";
}
elseif ($action == 'rejectuser'){
	verify_leader($_GET[teamid],$_SESSION[playerID]);
	mysql_query("UPDATE team_members SET level='-1' WHERE fk_team='$_GET[teamid]' AND fk_player='$_GET[playerid]'");
	echo "<script>window.location='teams.php'</script>";
}
else if ($action == 'delteam'){
	verify_leader($_POST[teamid],$_SESSION[playerID]);
	mysql_query("DELETE FROM team WHERE teamID='$_POST[teamid]'");
	mysql_query("DELETE FROM team_members WHERE fk_team='$_POST[teamid]'");
	echo "<script>window.location='teams.php'</script>";
}
else if ($action == 'choose'){
	if ($_POST[teamid] == 0)
	    displayError("Error: Team does not exists.");
	
	$p = mysql_query("SELECT 1 FROM team_members WHERE fk_team='$_GET[teamid]' AND level > 0");
	$members = mysql_num_rows($p);
	if ($members >= $TEAM_LIMIT)
    	displayError($MSG_LANG['teamfull']);

	mysql_query("INSERT INTO team_members (fk_player,fk_team,date,init_rating,level) VALUES ('$_SESSION[playerID]','$_POST[teamid]','".time()."','".getRating($_SESSION[playerID])."','0')");
	echo "<script>window.location='teams.php'</script>";
}
else if ($action == 'invite'){
	if ($_POST[playerid]  == 0)
	    displayError("Error: Player does not exists.");

	$p = mysql_query("SELECT 1 FROM team_members WHERE fk_team='$_POST[teamid]' AND level > 0");
	$members = mysql_num_rows($p);
	if ($members >= $TEAM_LIMIT)
    	displayError($MSG_LANG['teamfull']);

	mysql_query("INSERT INTO team_members (fk_player,fk_team,date,init_rating,level) VALUES ('$_POST[playerid]','$_POST[teamid]','".time()."','".getRating($_POST[playerid])."','-2')");
	echo "<script>window.location='teams.php'</script>";
}
else if ($action == 'transfer'){
	verify_leader($_POST[teamid],$_SESSION[playerID]);
	if ($_POST[playerid]  == 0)
	    displayError("Error: Player does not exists.");

	mysql_query("UPDATE team_members SET level='100' WHERE fk_player='$_POST[playerid]' AND fk_team='$_POST[teamid]'");
	mysql_query("UPDATE team_members SET level='10' WHERE fk_player='$_SESSION[playerID]' AND fk_team='$_POST[teamid]'");

	echo "<script>window.location='teams.php'</script>";
}
else if ($action == 'create'){

	$_POST['name'] = strip_tags($_POST['name']);
	$p = mysql_query("SELECT * FROM team WHERE name='$_POST[name]'");
	if (mysql_num_rows($p)>0)
	    displayError("Error:  $_POST[name] aready exists.");

	$p = mysql_query("INSERT INTO team (fk_creator,name,created,description) VALUES ('$_SESSION[playerID]','$_POST[name]','".time()."','$_POST[description]')");
	$id = mysql_insert_id();
	mysql_query("INSERT INTO team_members (fk_player,fk_team,date,init_rating,level) VALUES ('$_SESSION[playerID]','$id','".time()."','".getRating($_SESSION[playerID])."','100')");
	echo "<script>window.location='teams.php'</script>";
}
else if ($action == 'edit'){

	$_POST['name'] = strip_tags($_POST['name']);
	$p = mysql_query("SELECT * FROM team WHERE name='$_POST[name]' AND teamID<>'$_POST[teamid]'");
	if (mysql_num_rows($p)>0)
	    displayError("Error:  $_POST[name] aready exists.");

	$p = mysql_query("UPDATE team set name='$_POST[name]',description='$_POST[description]' WHERE teamID='$_POST[teamid]'");
	echo "<script>window.location='teams.php'</script>";
}
else if ($action == 'prechoose'){
	echo "<font size=3><B>".$MSG_LANG['chooseateamtojoin']."</B></font><BR><BR>";
	echo "<input type='hidden' name='action' value='choose'>";
	echo $MSG_LANG['team'].": <select name='teamid' style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;'>";

    $p = mysql_query("SELECT * FROM team ORDER BY name");
	if (mysql_num_rows($p) ==0)
	    echo "<option value=''>---</option>";
	else
		while ($row = mysql_fetch_array($p))
			echo "<option value='".$row[teamID]."'>$row[name]</option>";
	
	echo "</select><BR><BR>";
	echo "
		<input style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=submit value='".$MSG_LANG["asktojoin"]."'>&nbsp;
		<input onClick=\"window.location='teams.php'\" style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["cancel"]."'>";
}
else if ($action == 'preinvite'){
	echo "<font size=3><B>".$MSG_LANG['chooseplayertojoin']."</B></font><BR><BR>";
	echo "<input type='hidden' name='action' value='invite'>";
	echo "<input type='hidden' name='teamid' value='$_POST[teamid]'>";
	echo $MSG_LANG['player'].": <select name='playerid' style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;'>";

    $p = mysql_query("SELECT * FROM players where ativo='1' and engine<>'1' ORDER BY firstName");
	if (mysql_num_rows($p) ==0)
	    echo "<option value=''>---</option>";
	else
		while ($row = mysql_fetch_array($p)){
	        $p2 = mysql_query("SELECT * FROM team_members where fk_player=$row[playerID]");
	        if (mysql_num_rows($p2)==0 && $row[playerID] != $_SESSION[playerID])
				echo "<option value='".$row[playerID]."'>$row[firstName]</option>";
	}
	echo "</select><BR><BR>";
	echo "
		<input style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=submit value='".$MSG_LANG["invite"]."'>&nbsp;
		<input onClick=\"window.location='teams.php'\" style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["cancel"]."'>";
}
else if ($action == 'pretransfer'){
	echo "<font size=3><B>".$MSG_LANG['transferleadership']."</B></font><BR><BR>";
	echo "<input type='hidden' name='action' value='transfer'>";
	echo "<input type='hidden' name='teamid' value='$_POST[teamid]'>";
	echo $MSG_LANG['player'].": <select name='playerid' style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;'>";

    $p = mysql_query("SELECT * FROM team_members,players WHERE level>0 AND ativo='1' and playerID<>'$_SESSION[playerID]' AND fk_player=playerID AND fk_team='$_POST[teamid]' ORDER BY firstName");
	if (mysql_num_rows($p) ==0)
	    echo "<option value=''>---</option>";
	else
		while ($row = mysql_fetch_array($p))
			echo "<option value='".$row[playerID]."'>$row[firstName]</option>";

	echo "</select><BR><BR>";
	echo "
		<input style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=submit value='".$MSG_LANG["transferleadership"]."'>&nbsp;
		<input onClick=\"window.location='teams.php'\" style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["cancel"]."'>";
}
else if ($action == 'precreate'){
	echo "<font size=3><B>".$MSG_LANG['createateam']."</B></font><BR><BR>";
	echo "<input type='hidden' name='action' value='create'>";
	echo $MSG_LANG['name'].":<BR><input size=50 type=text name='name' style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;'><BR>";
	echo $MSG_LANG['description'].":<BR><textarea rows=5 cols=48 name='description' style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;'></textarea>";
	echo "<BR><BR>";
	echo "
		<input style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=submit value='   ".$MSG_LANG["create"]."   '>&nbsp;
		<input onClick=\"window.location='teams.php'\" style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["cancel"]."'>";
}
else if ($action == 'preedit'){
    $p = mysql_query("SELECT * FROM team WHERE teamID='$_POST[teamid]'");
    $row = mysql_fetch_array($p);

	echo "<font size=3><B>".$MSG_LANG['edit']."</B></font><BR><BR>";
	echo "<input type='hidden' name='action' value='edit'>";
	echo "<input type='hidden' name='teamid' value='$row[teamID]'>";
	echo $MSG_LANG['name'].":<BR><input value='$row[name]' size=50 type=text name='name' style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;'><BR>";
	echo $MSG_LANG['description'].":<BR><textarea rows=5 cols=48 name='description' style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;'>$row[description]</textarea>";
	echo "<BR><BR>";
	echo "
		<input style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=submit value='   ".$MSG_LANG["changeinformations"]."   '>&nbsp;
		<input onClick=\"window.location='teams.php'\" style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["cancel"]."'>";
}
else{
    $p = mysql_query("SELECT * FROM team_members,team WHERE fk_team=teamID AND fk_player='$_SESSION[playerID]'");
    $row = mysql_fetch_array($p);
    $myteam = $row['fk_team'];
    
    if (mysql_num_rows($p) == 0){
		/* No team */
		echo "<table border='1' width='100%'>";
		echo "<tr><th>".$MSG_LANG["noteam"]."</th></tr>";
		echo "<tr><td>&nbsp;</td></tr>";
		echo "<tr><td>
		<input onClick=\"window.location='teams.php?action=precreate'\" style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["createteam"]."'>&nbsp;
		<input onClick=\"window.location='teams_all.php'\" style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["chooseteam"]."'>
		</td></tr>";
		echo "</table>";

	}
	else if($row[level] == -1){
		/* Membership Rejected*/

		echo "<input type='hidden' name='action' value=''>";
		echo "<table border='1' width='100%'>";
		echo "<tr><th>$row[name]: ".$MSG_LANG["membershiprejected"]."</th></tr>";
		echo "<tr><td>&nbsp;</td></tr>";
		echo "<tr><td>
		<input onClick='confirma(\"leaveteam\")' style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["exitteam"]."'>
		</td></tr>";
		echo "</table>";
	}
	else if($row[level] == -2){
		/* Player Invited*/

		echo "<input type='hidden' name='action' value=''>";
		echo "<input type='hidden' name='teamid' value='$myteam'>";
		echo "<table border='1' width='100%'>";
		echo "<tr><th>".$MSG_LANG["youwasinvitedtojoin"]."$row[name]</th></tr>";
		echo "<tr><td>&nbsp;</td></tr>";
		echo "<tr><td>
		<input onClick='confirma(\"acceptinvite\")' style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["join"]."'>
		&nbsp;
		<input onClick='confirma(\"leaveteam\")' style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["cancel"]."'>
		</td></tr>";
		echo "</table>";
	}
	else if($row[level] == 0){
		/* Team Member Pending*/

		echo "<input type='hidden' name='action' value=''>";
		echo "<table border='1' width='100%'>";
		echo "<tr><th>".$MSG_LANG["membershippending"]." $row[name]</th></tr>";
		echo "<tr><td>&nbsp;</td></tr>";
		echo "<tr><td>
		<input onClick='confirma(\"leaveteam\")' style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["exitteam"]."'>
		</td></tr>";
		echo "</table>";
	}
	else if($row[level] > 0 && $row[level] < 100){
		/* Team Member*/
		$p = mysql_query("SELECT * FROM players WHERE ativo='1' and playerID='$row[fk_creator]'");
		$r = mysql_fetch_array($p);
		$creator = $r[firstName];

		$p = mysql_query("SELECT * FROM team_members,players WHERE fk_player=playerID AND fk_team='$myteam' AND level > 0");
		$members = mysql_num_rows($p);
		$p = mysql_query("SELECT * FROM team_members,players WHERE fk_player=playerID AND fk_team='$myteam' AND level = 100 and ativo='1'");
		$r = mysql_fetch_array($p);
		$leader = $r[firstName];

		echo "<input type='hidden' name='action' value=''>";
		echo "<table border='1' width='100%'>";
		echo "<tr><th colspan=2>".$MSG_LANG["teammember"]." $row[name]</th></tr>";

		echo "<tr><th>".$MSG_LANG['rating'].":</th><td>".getTeamRating($row[teamID])."</td></tr>";
		echo "<tr><th>".$MSG_LANG['members'].":</th><td>".$members."</td></tr>";
		echo "<tr><th>".$MSG_LANG['creator'].":</th><td>".$creator."</td></tr>";
		echo "<tr><th>".$MSG_LANG['leader'].":</th><td>".$leader."</td></tr>";
		echo "<tr><th>".$MSG_LANG['created'].":</th><td>".date("d/m/Y H:i",$row[created])."</td></tr>";
		echo "<tr><th>".$MSG_LANG['description'].":</th><td>".nl2br($row[description])."</td></tr>";
		echo "<tr><td colspan=2>&nbsp;</td></tr>";
		echo "<tr><td colspan=2>";
		$rg = mysql_query("SELECT * FROM match_players WHERE (playerID = '$_SESSION[playerID]')");
		//$rg = mysql_query("SELECT * FROM matches WHERE (team1 = '".$row[fk_team]."' OR team2 = '".$row[fk_team]."') AND (gameMessage = '' OR gameMessage = 'waiting' OR gameMessage = 'TeamInvited')");
		if (mysql_num_rows($rg) == 0)

		
		echo "<input onClick='confirma(\"leaveteam\")' style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["exitteam"]."'>
		</td></tr>";
		echo "</table>";
		
		}
	else if($row[level] >= 100){
		/* Team Leader */
		$p = mysql_query("SELECT * FROM players WHERE playerID='$row[fk_creator]'");
		$r = mysql_fetch_array($p);
		$creator = $r[firstName];
		
		$p2 = mysql_query("SELECT * FROM team_members,players WHERE ativo='1' and fk_player=playerID AND fk_team='$myteam' AND level > 0");
		$members = mysql_num_rows($p2);
		
		echo "<input type='hidden' name='action' value=''>";
		echo "<input type='hidden' name='teamid' value='$row[teamID]'>";
		
		echo "<table border='1' width='100%'>";
		echo "<tr><th colspan=2>".$MSG_LANG["teamleader"]." $row[name]</th></tr>";
		
		echo "<tr><th>".$MSG_LANG['rating'].":</th><td>".getTeamRating($row[teamID])."</td></tr>";
		echo "<tr><th>".$MSG_LANG['members'].":</th><td>".$members."</td></tr>";
		echo "<tr><th>".$MSG_LANG['creator'].":</th><td>".$creator."</td></tr>";
		echo "<tr><th>".$MSG_LANG['created'].":</th><td>".date("d/m/Y H:i",$row[created])."</td></tr>";
		echo "<tr><th>".$MSG_LANG['description'].":</th><td>".nl2br($row[description])."</td></tr>";
		echo "<tr><td colspan=2>&nbsp;</td></tr>";
		echo "<tr><td colspan=2>
		<input onClick=\"document.team.action.value='preedit';document.team.submit()\" style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='   ".$MSG_LANG["edit"]."   '>&nbsp;
		<input onClick=\"document.team.action.value='pretransfer';document.team.submit()\" style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["transferleadership"]."'>&nbsp;
		<input onClick=\"document.team.action.value='preinvite';document.team.submit()\" style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["invite"]." ".$MSG_LANG["player"]."'>&nbsp;
		<input onClick=\"window.open('challenge_team.php', '_self')\" style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='Team Challenges'>";
		$rg = mysql_query("SELECT * FROM matches WHERE (team1 = '".$row[fk_team]."' OR team2 = '".$row[fk_team]."') AND (gameMessage = '' OR gameMessage = 'waiting' OR gameMessage = 'TeamInvited')");
		if (mysql_num_rows($rg) == 0)
		echo "<input onClick=\"confirma('delteam')\" style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='".$MSG_LANG["deleteteam"]."'>
		</td></tr>";
		echo "</table>";

		echo "<BR>";
		echo "<table border='1' width='100%'>";
		echo "<tr><th colspan=3>".$MSG_LANG["teammembers"]."</th></tr>";
		
		echo "<tr><th>".$MSG_LANG[name]."</th><th>".$MSG_LANG['rating']."/".$MSG_LANG['max']." (".$MSG_LANG['initial'].")</th><th>&nbsp;</th></tr>";
		while ($row2 = mysql_fetch_array($p2)){
			echo "<tr><td><a href='stats_user.php?cod=$row2[playerID]'>$row2[firstName]</a></td><td>$row2[rating]/$row2[rating_max] ($row2[init_rating])</td><td>";
			if ($_SESSION[playerID] != $row2[playerID])
				echo "<input onClick=\"window.location='teams.php?action=deluser&playerid=$row2[playerID]&teamid=$row[teamID]'\" style='font-size:11' type=button value='".$MSG_LANG['delete']."'>";
			else echo "&nbsp;";
			echo "</td></tr>";
		}
		echo "<tr><td colspan=3>&nbsp;</td></tr>";
		echo "</table>";
		echo "<BR>";

		$p2 = mysql_query("SELECT * FROM team_members,players WHERE fk_player=playerID AND ativo='1' and fk_team='$myteam' AND (level = 0 OR level=-2)");
		if (mysql_num_rows($p2)>0){
			echo "<table border='1' width='100%'>";
			echo "<tr><th colspan=3>".$MSG_LANG["membershippending"]."</th></tr>";
			echo "<tr><th>".$MSG_LANG[name]."</th><th>".$MSG_LANG['rating']."/".$MSG_LANG['max']."</th><th>&nbsp;</th></tr>";

			while ($row2 = mysql_fetch_array($p2)){
				echo "<tr><td><a href='stats_user.php?cod=$row2[playerID]'>$row2[firstName]</a></td><td>$row2[rating]/$row2[rating_max]</td>";
				if ($row2[level] == 0)
					echo "<td><input onClick=\"window.location='teams.php?action=acceptuser&playerid=$row2[playerID]&teamid=$row[teamID]'\" style='font-size:11' type=button value='".$MSG_LANG['accept']."'>&nbsp;<input onClick=\"window.location='teams.php?action=rejectuser&playerid=$row2[playerID]&teamid=$row[teamID]'\" style='font-size:11' type=button value='".$MSG_LANG['reject']."'></td></tr>";
				else
					echo "<td><input onClick=\"window.location='teams.php?action=deluser&playerid=$row2[playerID]&teamid=$row[teamID]'\" style='font-size:11' type=button value='".$MSG_LANG['cancel']."'></td></tr>";
			}
			echo "<tr><td colspan=3>&nbsp;</td></tr>";
			echo "</table>";
		}
	}
}

?>
</form>
</font>
<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>
<? include("footer.inc.php");?>
</body>
</html>
