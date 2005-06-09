<?php
##############################################################################################
#                                                                                            #
#                                challenge_team.php                                                
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
		  	displayError("You are not the team leader and are not allowed to challenge other teams.","mainmenu.php");
			exit;
		}
} 


      
//load user data 
$player_id = $_SESSION['playerID']; 
$p = mysql_query("select fk_team from team_members where fk_player='$player_id'"); 
$my_team = mysql_fetch_array($p); 
$my_team=$my_team[0]; 

$p2 = mysql_query("SELECT * from team_members WHERE level>0 AND fk_team='$my_team'"); 

if (verify_leader($my_team,$_SESSION['playerID']))
	displayError("You are not the team leader and are not allowed to challenge other teams.","mainmenu.php");
 
if (mysql_num_rows($p2)<2)
			displayError("You can't participate in a team match until your team has at least 2 players.","mainmenu.php");
		

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
      case 'ResponseToInvite': 
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
	displayError("You can't participate in a tournament until you have an official rating.  To establish a rating you need to finish 5 games.","mainmenu.php");
 
?> 
<head> 
        <title>ChessManiac Teams</title> 

<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css"> 

        <script type="text/javascript"> 

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
<BR> 
   <? 
      if ($errMsg != "") 
      echo("<p><h2><font color='red'>".$errMsg."</font></h2><p>\n"); 

//load user data 
$player_id = $_SESSION['playerID']; 
$p = mysql_query("select fk_team from team_members where fk_player='$player_id'"); 
$my_team = mysql_fetch_array($p); 
$my_team=$my_team[0]; 
   ?>
   <? 

display_matches_waiting($my_team); 

########################################### SHOW INVITATIONS ############## 
   $tmpQuery = "SELECT * FROM matches WHERE gameMessage = 'TeamInvited' AND ((team1 = ".$my_team." AND messageFrom = 'black') OR (team2 = ".$my_team." AND messageFrom = 'white')) ORDER BY dateCreated"; 
   $tmpMatches = mysql_query($tmpQuery); 
   if (mysql_num_rows($tmpMatches) > 0){ 
      //change this next line for processing [done] 
       echo '<form name="responseToInvite" action="challenge_team.php" method="post"> 
       <table border="1" width="74%"> 
       <tr> 
        <th colspan=5>'.$MSG_LANG["therearechallenges"].'</th> 
       </tr> 
       <tr> 
        <th>'.$MSG_LANG["challenger"].'</th> 

        <th>'.$MSG_LANG["tournamentplayers"].'</th> 
        <th>'.$MSG_LANG["yourcolor"].'</th> 
        <th>'.$MSG_LANG["movetimeout"].'</th>
		<th>'.$MSG_LANG["reply"].'</th> 
       </tr>'; 
      while($tmpMatch = mysql_fetch_array($tmpMatches, MYSQL_ASSOC)) 
      { 
         /* Opponent */ 
         echo("<tr><td>"); 
         /* get opponent's team name */ 
         if ($tmpMatch['team1'] == $my_team) 
            $tmpOpponent = mysql_query("SELECT * FROM team WHERE teamID = ".$tmpMatch['team2']); 
         else 
            $tmpOpponent = mysql_query("SELECT * FROM team WHERE teamID = ".$tmpMatch['team1']); 
         $row = mysql_fetch_array($tmpOpponent); 
         $opponent = $row[1]; 
         $id = $row[0]; 
         echo "<a href='stats.php?cod=$id'>$opponent</a>"; 

         echo "<td>$tmpMatch[boards]</td>\n"; 
          
         /* Your Color */ 
         echo ("</td><td>"); 
         if ($tmpMatch['team1'] == $my_team) 
         { 
            echo "White"; 
            $tmpFrom = "white"; 

         } 
         else 
         { 
            echo "Black"; 
            $tmpFrom = "black"; 

         } 
         echo "</td>"; 
			if ($tmpMatch[adj_time] == 0)
					echo "<td bgcolor=white>14 $MSG_LANG[unlimited]</td>\n";
				//else if ($tmpGame[adj_time] < 29)
					//echo "<td bgcolor=white>$tmpGame[adj_time] $MSG_LANG[unlimited]</td>\n";
				//else if ($tmpGame[timelimit] >29 && <60)
					//echo "<td>$tmpGame[timelimit] $MSG_LANG[min].</td>\n";
				else if ($tmpMatch[adj_time] <60)
					echo "<td>$MSG_LANG[min] $tmpMatch[adj_time] mins.</td>\n";
				else if($tmpMatch[adj_time] < 1440)
					echo "<td>$MSG_LANG[hs] ".($tmpMatch[adj_time]/60)." hrs.</td>\n";
				else
					echo "<td>".($tmpMatch[adj_time]/24/60)." $MSG_LANG[unlimited]</td>\n";

            /* Response */ 
         echo ("</td><td>"); 
         echo ("<input type='button' value='".$MSG_LANG["accept"]."' onclick=\"sendResponse('accepted', '".$tmpFrom."', ".$tmpMatch['match_id'].")\">"); 
         echo ("<input type='button' value='".$MSG_LANG["reject"]."' onclick=\"sendResponse('declined', '".$tmpFrom."', ".$tmpMatch['match_id'].")\">"); 

         echo("</td></tr>\n"); 
      } 
      echo '</table> 
      <input type="hidden" name="response" value=""> 
      <input type="hidden" name="messageFrom" value=""> 
      <input type="hidden" name="match_id" value=""> 
      <input type="hidden" name="timelimit" value=""> 
	  <input type="hidden" name="ToDo" value="ResponseToInvite"> 
      
	  </form>'; 
   } 
########################################### END OF SHOW INVITATIONS ########## 
?> 

   <form name="InviteTeam" action="challenge_team.php" method="post"> 
    <input type="hidden" name="ToDo" value="InviteTeam"> 
    <input type="hidden" name="myteam" value="<?=$my_team?>"> 
    <input type="hidden" name="opponent" value=""> 


    <table border="1"> 
    <tr> 
        <th colspan="8"><p align=center><BR> 
            <?=$MSG_LANG["players"]?>: 
            <select name="size"> 
            <option value="2" SELECTED>2</option> 
            <option value="4">4</option> 
            <option value="6">6</option> 
            <option value="8">8</option> 
            </select>&nbsp; 

            
            <?=$MSG_LANG["timeforeach"]?>: 
            <select name="timelimit">
		<option value="0" SELECTED><?=$MSG_LANG["movetimeout"]?></option>
		
<?
 foreach ($CFG_TIME_ARRAY as $t){
 	if ($t <= 1440){
		if ($t>=30 && $t<60)
			echo "<option value='$t'>$MSG_LANG[min]$t min.</option>\n";
		elseif ($t>=60){
			$tm = $t/60;
	    	echo "<option value='$t'>$MSG_LANG[hs]$tm hrs.</option>\n";
	    }
	}
}
	for ($x=1; $x<$CFG_EXPIREGAME; $x++)
		echo "<option value='".($x*1440)."'>$x Days per move</option>\n";
	//for ($y=1; $y<$CFG_EXPIREGAME; $y++)
	    //echo "<option value='".($y)."'>$y days per move</option>\n";
?>
		</select>&nbsp;<?=$MSG_LANG["movetimeout"]?> = <?=$CFG_EXPIREGAME?> <?=$MSG_LANG["unlimited"]?></th> 
    </tr></table> 


   <? 
   if ($warning==1){ 
   echo "<p><font color=red><b>WARNING: You cannot play against yourself!</b></font><br>"; 
   } 
   if ($warning==2){ 
   echo "<p><font color=red><b>WARNING: Match not added to database, please contact admin!</b></font><br>"; 
   } 
   if ($warning==3){ 
   echo "<p><font color=red><b>Match Invitation added to database, you now need to wait for a response.</b></font><br>"; 
   } 
   if ($warning==4){ 
   echo "<p><font color=red><b>WARNING: Match not added to database, you already have a pending match against this team!</b></font><br>"; 
   } 



//display teams ranking list 
?>
   <br> 
 <table border="1"> 
   <tr><th colspan=6><?=$MSG_LANG["teamranking"]?></th> 
   </tr> 
   <tr> 
   <th>&nbsp;</th> 
   <th><?=$MSG_LANG["invite"]?></th> 
   <th><?=$MSG_LANG["punctuation"]?></th> 
   <th><?=$MSG_LANG["rating"]?></th> 
   <th><?=$MSG_LANG["name"]?></th> 
   </tr> 
<? 
     $n=$inicio+1; 
   $p = mysql_query("SELECT * from team order by points DESC"); 
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
                <td><input type='button' style='cursor:hand' value='$MSG_LANG[invite]' onClick='document.InviteTeam.opponent.value=".$row[0].";document.InviteTeam.submit();'></td> 
                <td>$row[points]</td> 
                <td>$rating</td> 
                <td><a href='stats_team.php?cod=$id'>$name</a></td> 
                </tr></form>"; 
                $n++; 
        } 
?> 
 </table> 
<form name="logout" action="mainmenu.php" method="post"> 
        <input type="hidden" name="ToDo" value="Logout"> 
</form> 

<? 

echo "</body></html>"; 


//mysql_close(); 

################################################################################################ 


?>