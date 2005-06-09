<?php
##############################################################################################
#                                                                                            #
#                                /admin/mainmenu.php                                                
# *                            -------------------                                           #
# *   begin                : Wednesday, January 25, 2005                                     
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://www.compwebchess.com/forums                              #
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

	/* cleanup dead games */
	/* determine threshold for oldest game permitted */
	$targetDate = date("Y-m-d H:i:s", mktime(date('H'),date('i'),0, date('m'), date('d') - $CFG_EXPIREGAME,date('Y'))); 
	/* find out which games are older */
	$tmpQuery = "SELECT * FROM games WHERE lastMove < '".$targetDate."' AND (gameMessage='inviteDeclined' OR gameMessage='playerInvited' OR gameMessage='')";
	$tmpOldGames = mysql_query($tmpQuery);

	/* for each older game... */
	
    while($tmpOldGame = mysql_fetch_array($tmpOldGames, MYSQL_ASSOC))
    {
        if ($tmpOldGame['gameMessage'] == ''){

                //Abandoned game. User will lose points
                $p = mysql_query("SELECT * FROM history WHERE gameID='".$tmpOldGame['gameID']."' ORDER BY timeOfMove DESC");
                $row = mysql_fetch_array($p);

                if ($row['curColor'] != ""){
                    if ($row['curColor'] == "white")$playersColor = "black";
                    else if ($row['curColor'] == "black")$playersColor = "white";
                }
                else
                    $playersColor = "white";

                saveRanking($tmpOldGame[gameID],"resign",$playersColor);
                mysql_query("UPDATE games SET lastMove = NOW() WHERE gameID = ".$tmpOldGame[gameID]);
                
                //echo "Deleting old games Game: ".$tmpOldGame[gameID]."<BR>";
                $log = "\"Deleting old games\" \"Game: $tmpOldGame[gameID]\"";
        }
	else{
		  
		  /* ... clear the history... */
		  mysql_query("DELETE FROM history WHERE gameID = ".$tmpOldGame['gameID']);

		  /* ... and the board... */
		  mysql_query("DELETE FROM pieces WHERE gameID = ".$tmpOldGame['gameID']);

		  /* ... and the messages... */
		  mysql_query("DELETE FROM messages WHERE gameID = ".$tmpOldGame['gameID']);

		  /* ... and the chat... */
		  mysql_query("DELETE FROM chat WHERE gameID = ".$tmpOldGame['gameID']);
		
		  /* ... and finally the game itself from the database */
		  mysql_query("DELETE FROM games WHERE gameID = ".$tmpOldGame['gameID']);
		  
          	  $log = "\"Deleting refused games\" \"Game: $tmpOldGame[gameID]\"";	
	   }
	   grava_log("webchess.log",$log,$CFG_LOG_PATH);
    }
	
	$tmpNewUser = false;
	$errMsg = "";
	switch($_POST['ToDo'])
	{
		case 'NewUser':
			/* create new player */
			$tmpNewUser = true;
			
			/* sanity check: empty nick */
			if ($_POST['txtNick'] == "")
				die("Enter a valid Nick!");

            if ($_POST['txtFirstName'] == "")
                die("Enter a valid Name");				
                
			if ($_POST['email'] == "")
		          $_POST['email'] = "Undefined";
			if ($_POST['cidade'] == "")
		          $_POST['cidade'] = "Undefined";
			

			/* check for existing user with same nick */
			$tmpQuery = "SELECT playerID FROM players WHERE firstName='".$_POST['txtFirstName']."' OR nick = '".$_POST['txtNick']."'";
			$existingUsers = mysql_query($tmpQuery);
			if (mysql_num_rows($existingUsers) > 0)
			{
				require 'newuser.php';
				die();
			}
			
			$nascimento = "$nano-$nmes-$ndia";
			$tmpQuery = "INSERT INTO players (password, firstName, nick, email, nascimento, sexo, rua, bairro, cidade, cep, uf, pais)
		        VALUES ('".$_POST['pwdPassword']."', '".$_POST['txtFirstName']."', '".$_POST['txtNick']."', '".$_POST['email']."', '".$nascimento."', '".$_POST['sexo']."', '".$_POST['rua']."', '".$_POST['bairro']."', '".$_POST['cidade']."', '".$_POST['cep']."', '".$_POST['uf']."','".$_POST['pais']."')";

			mysql_query($tmpQuery);

			/* get ID of new player */
			$_SESSION['playerID'] = mysql_insert_id();

			/* set History format preference */
			$tmpQuery = "INSERT INTO preferences (playerID, preference, value) VALUES (".$_SESSION['playerID'].", 'history', 'pgn')";
			mysql_query($tmpQuery);
			
			/* set Theme preference */
			$tmpQuery = "INSERT INTO preferences (playerID, preference, value) VALUES (".$_SESSION['playerID'].", 'theme', 'beholder')";
			mysql_query($tmpQuery);

   			/* Board Size */
			$tmpQuery = "INSERT INTO preferences (playerID, preference, value) VALUES (".$_SESSION['playerID'].", 'boardSize', '50')";
			mysql_query($tmpQuery);

   			/* Language */
			$tmpQuery = "INSERT INTO preferences (playerID, preference, value) VALUES (".$_SESSION['playerID'].", 'language', '".$_POST[language]."')";
			mysql_query($tmpQuery);


			/* set auto-reload preference */
			if (is_numeric($_POST['txtReload']))
			{
				if (intval($_POST['txtReload']) >= $CFG_MINAUTORELOAD)
					$tmpQuery = "INSERT INTO preferences (playerID, preference, value) VALUES (".$_SESSION['playerID'].", 'autoreload', ".$_POST['txtReload'].")";
				else
					$tmpQuery = "INSERT INTO preferences (playerID, preference, value) VALUES (".$_SESSION['playerID'].", 'autoreload', ".$CFG_MINAUTORELOAD.")";
				
				mysql_query($tmpQuery);
			}

			/* set email notification preference */
			if ($CFG_USEEMAILNOTIFICATION)
			{
				$tmpQuery = "INSERT INTO preferences (playerID, preference, value) VALUES (".$_SESSION['playerID'].", 'emailnotification', '".$_POST['txtEmailNotification']."')";
				mysql_query($tmpQuery);
			}
			
			/* no break, login user */
			
		case 'Login':
			/* check for a player with supplied nick and password */
			$tmpQuery = "SELECT * FROM players WHERE nick = '".$_POST['txtNick']."' AND password = '".$_POST['pwdPassword']."'";
			$tmpPlayers = mysql_query($tmpQuery);
			$tmpPlayer = mysql_fetch_array($tmpPlayers, MYSQL_ASSOC);

			/* if such a player exists, log him in... otherwise die */
			if ($tmpPlayer)
			{
				$_SESSION['playerID'] = $tmpPlayer['playerID'];
				$_SESSION['lastInputTime'] = time();
				$_SESSION['playerName'] = $tmpPlayer['firstName'];
				$_SESSION['firstName'] = $tmpPlayer['firstName'];
				$_SESSION['lastName'] = $tmpPlayer['lastName'];
				$_SESSION['nick'] = $tmpPlayer['nick'];
				$_SESSION['localization'] = $tmpPlayer['cidade'];

			}
			else
				die("Invalid Nick/Password");

	       if (isset($_POST['txtNick'])){	
                $ipx = $HTTP_X_FORWARDED_FOR;
                $ip = getenv("REMOTE_ADDR");
                $log = "\"Login Accepted WEBCHESS\" \"$_POST[txtNick]\" \"$ip\" \"$ipx\"";
                grava_log("webchess.log",$log,$CFG_LOG_PATH);
	       }

			/* load user preferences */
			$tmpQuery = "SELECT * FROM preferences WHERE playerID = ".$_SESSION['playerID'];
			$tmpPreferences = mysql_query($tmpQuery);

			$isPreferenceFound['history'] = false;
			$isPreferenceFound['theme'] = false;
			$isPreferenceFound['autoreload'] = false;
			$isPreferenceFound['emailnotification'] = false;
			$isPreferenceFound['boardSize'] = false;
			$isPreferenceFound['language'] = false;
			
			while($tmpPreference = mysql_fetch_array($tmpPreferences, MYSQL_ASSOC))
			{
				switch($tmpPreference['preference'])
				{
					case 'history':
					case 'theme':
						/* setup SESSION var of name pref_PREF, like pref_history */
						$_SESSION['pref_'.$tmpPreference['preference']] = $tmpPreference['value'];
						break;
					
					case 'emailnotification':
						if ($CFG_USEEMAILNOTIFICATION)
							$_SESSION['pref_emailnotification'] = $tmpPreference['value'];
						break;

					case 'boardSize':
                        $_SESSION['pref_boardSize'] = $tmpPreference['value'];
						break;

					case 'language':
    					$_SESSION['pref_language'] = $tmpPreference['value'];
						break;
                        						
					case 'autoreload':
						if (is_numeric($tmpPreference['value']))
						{
							if (intval($tmpPreference['value']) >= $CFG_MINAUTORELOAD)
								$_SESSION['pref_autoreload'] = intval($tmpPreference['value']);
							else
								$_SESSION['pref_autoreload'] = $CFG_MINAUTORELOAD;
						}
						else
							$_SESSION['pref_autoreload'] = $CFG_MINAUTORELOAD;
						break;
				}

				$isPreferenceFound[$tmpPreference['preference']] = true;
			}

			/* look for missing preference and fix */
			foreach (array_keys($isPreferenceFound, false) as $missingPref)
			{
				$defaultValue = "";
				switch($missingPref)
				{
					case 'history':
						$defaultValue = "pgn";
						break;
					case 'theme':
						$defaultValue = "beholder";
						break;
					case 'autoreload':
						$defaultValue = $CFG_MINAUTORELOAD;
						break;
					case 'boardSize':
						$defaultValue = "50";
						break;
					case 'language':
						$defaultValue = $CFG_DEFAULT_LANGUAGE;
						break;
					case 'emailnotification':
						$defaultValue = "";
						break;
				}
				$tmpQuery = "INSERT INTO preferences (playerID, preference, value) VALUES (".$_SESSION['playerID'].", '".$missingPref."', '".$defaultValue."')";
				mysql_query($tmpQuery);
				
				/* setup SESSION var of name pref_PREF, like pref_history */
				if ($CFG_USEEMAILNOTIFICATION || ($missingPref != 'emailnotification'))
					$_SESSION['pref_'.$missingPref] =  $defaultValue;
			}
			
			break;

		case 'Logout':
			$_SESSION['playerID'] = -1;
			session_unregister($_SESSION);
			echo "<script>window.location='index.php'</script>";
            exit;
			break;

		case 'InvitePlayer':

			/* prevent multiple pending requests between two players with the same originator */
			#$tmpQuery = "SELECT gameID FROM games WHERE gameMessage = 'playerInvited'";
			#$tmpQuery .= " AND ((messageFrom = 'white' AND whitePlayer = ".$_SESSION['playerID']." AND blackPlayer = ".$_POST['opponent'].")";
			#$tmpQuery .= " OR (messageFrom = 'black' AND whitePlayer = ".$_POST['opponent']." AND blackPlayer = ".$_SESSION['playerID']."))";
			
			$tmpQuery = "SELECT gameID FROM games WHERE (gameMessage = '' OR gameMessage='playerInvited')";
			$tmpQuery .= "AND ((whitePlayer = ".$_SESSION['playerID']." AND blackPlayer= ".$_POST['opponent'].")";
			$tmpQuery .= " OR (whitePlayer=".$_POST['opponent']." AND blackPlayer=".$_SESSION['playerID']."))";
			$tmpExistingRequests = mysql_query($tmpQuery);

			if (mysql_num_rows($tmpExistingRequests) == 0)
			{
				if (!minimum_version("4.2.0"))
					init_srand();
			
				if ($_POST['color'] == 'random')
					$tmpColor = (rand(0,1) == 1) ? "white" : "black";
				else
					$tmpColor = $_POST['color'];

				$tmpQuery = "INSERT INTO games (whitePlayer, blackPlayer, gameMessage, messageFrom, dateCreated, lastMove, ratingWhite, ratingBlack,ratingWhiteM, ratingBlackM,oficial,PVBlack,PVWhite) VALUES (";
				if ($tmpColor == 'white'){
				    $white = $_SESSION['playerID'];
				    $black = $_POST['opponent'];
				}else{
					$white = $_POST['opponent'];
                    $black = $_SESSION['playerID'];
                }
				$tmpQuery .= "$white, $black, 'playerInvited', '".$tmpColor."', NOW(), NOW(),".getRating($white).",".getRating($black).",".getRatingMonth($white).",".getRatingMonth($black).",'".$_POST['oficial']."',".getPV($black).",".getPV($white).")";
				mysql_query($tmpQuery);

				/* if email notification is activated... */
				if ($CFG_USEEMAILNOTIFICATION)
				{
					/* if opponent is using email notification... */
					$tmpOpponentEmail = mysql_query("SELECT value FROM preferences WHERE playerID = ".$_POST['opponent']." AND preference = 'emailNotification'");
					if (mysql_num_rows($tmpOpponentEmail) > 0)
					{
						$opponentEmail = mysql_result($tmpOpponentEmail, 0);
						if ($opponentEmail != '')
						{
							/* notify opponent of invitation via email */
							webchessMail('invitation', $opponentEmail, '', $_SESSION['nick']);
						}
					}
				}
			}
			break;

		case 'ResponseToInvite':
			if ($_POST['response'] == 'accepted')
			{
				/* update game data */
				$tmpQuery = "UPDATE games SET gameMessage = '', messageFrom = '' WHERE gameID = ".$_POST['gameID'];
				mysql_query($tmpQuery);

				/* setup new board */
				$_SESSION['gameID'] = $_POST['gameID'];
				createNewGame($_POST['gameID']);
				saveGame();
			}
			else
			{
				
				$tmpQuery = "UPDATE games SET gameMessage = 'inviteDeclined', messageFrom = '".$_POST['messageFrom']."' WHERE gameID = ".$_POST['gameID'];
				mysql_query($tmpQuery);
			}
			
			break;

		case 'WithdrawRequest':
				
			/* get opponent's player ID */
			$tmpOpponentID = mysql_query("SELECT whitePlayer FROM games WHERE gameID = ".$_POST['gameID']);
			if (mysql_num_rows($tmpOpponentID) > 0)
			{
				$opponentID = mysql_result($tmpOpponentID, 0);

				if ($opponentID == $_SESSION['playerID'])
				{
					$tmpOpponentID = mysql_query("SELECT blackPlayer FROM games WHERE gameID = ".$_POST['gameID']);
					$opponentID = mysql_result($tmpOpponentID, 0);
				}
			
				$tmpQuery = "DELETE FROM games WHERE gameID = ".$_POST['gameID'];
				mysql_query($tmpQuery);
			
				/* if email notification is activated... */
				if ($CFG_USEEMAILNOTIFICATION)
				{
					/* if opponent is using email notification... */
					$tmpOpponentEmail = mysql_query("SELECT value FROM preferences WHERE playerID = ".$opponentID." AND preference = 'emailNotification'");
					if (mysql_num_rows($tmpOpponentEmail) > 0)
					{
						$opponentEmail = mysql_result($tmpOpponentEmail, 0);
						if ($opponentEmail != '')
						{
							/* notify opponent of invitation via email */
							webchessMail('withdrawal', $opponentEmail, '', $_SESSION['nick']);
						}
					}
				}
			}
			break;

		case 'UpdatePersonalInfo':
			$tmpQuery = "SELECT password FROM players WHERE playerID = ".$_SESSION['playerID'];
			$tmpPassword = mysql_query($tmpQuery);
			$dbPassword = mysql_result($tmpPassword, 0);

			if ($dbPassword != $_POST['pwdOldPassword'])
				$errMsg = "Sorry, incorrect old password!";
			else
			{
				$tmpDoUpdate = true;

				if ($CFG_NICKCHANGEALLOWED)
				{
					$tmpQuery = "SELECT playerID FROM players WHERE nick = '".$_POST['txtNick']."' AND playerID <> ".$_SESSION['playerID'];
					$existingUsers = mysql_query($tmpQuery);
				
					if (mysql_num_rows($existingUsers) > 0)
					{
						$errMsg = "Sorry, that nick is already in use.";
						$tmpDoUpdate = false;
					}
				}
				
				if ($tmpDoUpdate)
				{
					/* update DB */
					$tmpQuery = "UPDATE players SET firstName = '".$_POST['txtFirstName']."', lastName = '".$_POST['txtLastName']."', password = '".$_POST['pwdPassword']."', cidade='".$_POST['txtLocalization']."'";
					
					if ($CFG_NICKCHANGEALLOWED && $_POST['txtNick'] != "")
						$tmpQuery .= ", nick = '".$_POST['txtNick']."'";
					
					$tmpQuery .= " WHERE playerID = ".$_SESSION['playerID'];
					mysql_query($tmpQuery);

					/* update current session */
					$_SESSION['playerName'] = $_POST['txtFirstName']." ".$_POST['txtLastName'];
					$_SESSION['firstName'] = $_POST['txtFirstName'];
					$_SESSION['lastName'] = $_POST['txtLastName'];
					$_SESSION['localization'] = $_POST['txtLocalization'];

					if ($CFG_NICKCHANGEALLOWED && $_POST['txtNick'] != "")
						$_SESSION['nick'] = $_POST['txtNick'];
				}
			}
			
			break;
		
		case 'UpdatePrefs':
			/* Theme */
			$tmpQuery = "UPDATE preferences SET value = '".$_POST['rdoTheme']."' WHERE playerID = ".$_SESSION['playerID']." AND preference = 'theme'";
			mysql_query($tmpQuery);
			
			/* History format */
			$tmpQuery = "UPDATE preferences SET value = '".$_POST['rdoHistory']."' WHERE playerID = ".$_SESSION['playerID']." AND preference = 'history'";
			mysql_query($tmpQuery);

            /* Board Size */
			$tmpQuery = "UPDATE preferences SET value = '".$_POST['boardSize']."' WHERE playerID = ".$_SESSION['playerID']." AND preference = 'boardSize'";
			mysql_query($tmpQuery);

            /* Board Size */
			$tmpQuery = "UPDATE preferences SET value = '".$_POST['language']."' WHERE playerID = ".$_SESSION['playerID']." AND preference = 'language'";
			mysql_query($tmpQuery);

			/* Auto-Reload */
			if (is_numeric($_POST['txtReload']))
			{
				if (intval($_POST['txtReload']) >= $CFG_MINAUTORELOAD)
					$tmpQuery = "UPDATE preferences SET value = ".$_POST['txtReload']." WHERE playerID = ".$_SESSION['playerID']." AND preference = 'autoreload'";
				else
					$tmpQuery = "UPDATE preferences SET value = ".$CFG_MINAUTORELOAD." WHERE playerID = ".$_SESSION['playerID']." AND preference = 'autoreload'";
				
				mysql_query($tmpQuery);
			}

			/* Email Notification */
			if ($CFG_USEEMAILNOTIFICATION)
			{
				$tmpQuery = "UPDATE preferences SET value = '".$_POST['txtEmailNotification']."' WHERE playerID = ".$_SESSION['playerID']." AND preference = 'emailnotification'";
				mysql_query($tmpQuery);
			}

			/* update current session */
			$_SESSION['pref_history'] = $_POST['rdoHistory'];
			$_SESSION['pref_theme'] =  $_POST['rdoTheme'];
			$_SESSION['pref_boardSize'] = $_POST['boardSize'];
			$_SESSION['pref_language'] = $_POST['language'];

			if (is_numeric($_POST['txtReload']))
			{
				if (intval($_POST['txtReload']) >= $CFG_MINAUTORELOAD)
				{
					$_SESSION['pref_autoreload'] = intval($_POST['txtReload']);
				}
				else
					$_SESSION['pref_autoreload'] = $CFG_MINAUTORELOAD;
			} else
				$_SESSION['pref_autoreload'] = $CFG_MINAUTORELOAD;

			if ($CFG_USEEMAILNOTIFICATION)
				$_SESSION['pref_emailnotification'] = $_POST['txtEmailNotification'];
			break;

		case 'TestEmail':
			if ($CFG_USEEMAILNOTIFICATION)
				webchessMail('test', $_SESSION['pref_emailnotification'], '', '');
			break;
	}

	/* check session status */
	require_once('sessioncheck.php');

	/* set default playing mode to different PCs (as opposed to both players sharing a PC) */
	$_SESSION['isSharedPC'] = false;

	/* Language selection */
	require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");
?>

<html>
<head>
	<title>WebChess</title>
	<script type="text/javascript">
		function validatePersonalInfo()
		{
			if (document.PersonalInfo.txtFirstName.value == ""
				|| document.PersonalInfo.txtLastName.value == ""
			<?
				if ($CFG_NICKCHANGEALLOWED)
					echo ('|| document.PersonalInfo.txtNick.value == ""');
			?>
				|| document.PersonalInfo.pwdOldPassword.value == ""
				|| document.PersonalInfo.pwdPassword.value == "")
			{
				alert("Sorry, all personal info fields are required and must be filled out.");
				return;
			}

			if (document.PersonalInfo.pwdPassword.value == document.PersonalInfo.pwdPassword2.value)
				document.PersonalInfo.submit();
			else
				alert("Sorry, the two password fields don't match.  Please try again.");
		}
		
		function sendResponse(responseType, messageFrom, gameID)
		{
			document.responseToInvite.response.value = responseType;
			document.responseToInvite.messageFrom.value = messageFrom;
			document.responseToInvite.gameID.value = gameID;
			document.responseToInvite.submit();
		}

		function loadGame(gameID)
		{
			//if (document.existingGames.rdoShare[0].checked)
			//	document.existingGames.action = "opponentspassword.php";

			document.existingGames.gameID.value = gameID;
			document.existingGames.submit();
		}

		function withdrawRequest(gameID)
		{
			document.withdrawRequestForm.gameID.value = gameID;
			document.withdrawRequestForm.submit();
		}

		function loadEndedGame(gameID)
		{
			document.existingGames.gameID.value = gameID;
			document.existingGames.submit();
		}

<? if ($CFG_USEEMAILNOTIFICATION) { ?>
		function testEmail()
		{
			document.preferences.ToDo.value = "TestEmail";
			document.preferences.submit();
		}
<? } ?>
	</script>

<style>
TABLE   {font-size: 11px; font-family: verdana; background: #cfcfbb;}
TD      {background: white; text-align: center;}
.BOTOES {width:100; background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;}
</style>

<?
	echo("<meta HTTP-EQUIV='Pragma' CONTENT='no-cache'>\n");
    echo "<META HTTP-EQUIV=Refresh CONTENT='";
    if ($_SESSION['pref_autoreload'] >= $CFG_MINAUTORELOAD)
        echo ($_SESSION['pref_autoreload']);
	else
        echo ($CFG_MINAUTORELOAD);
	echo ("; URL=mainmenu.php'>\n");
?>


</head>

<body bgcolor=white text=black>
<font face=verdana size=2>

<? require_once('header.inc.php');?>
<BR>

<?
	if ($errMsg != "")
		echo("<p><h2><font color='red'>".$errMsg."</font></h2><p>\n");
?>

<?
	$tmpQuery = "SELECT * FROM games WHERE gameMessage = 'playerInvited' AND ((whitePlayer = ".$_SESSION['playerID']." AND messageFrom = 'black') OR (blackPlayer = ".$_SESSION['playerID']." AND messageFrom = 'white')) ORDER BY dateCreated";
	$tmpGames = mysql_query($tmpQuery);
	if (mysql_num_rows($tmpGames) > 0){
    	echo '<form name="responseToInvite" action="mainmenu.php" method="post">
	    <table border="1" width="100%">
	    <tr>
		  <th colspan=6>'.$MSG_LANG["therearechallenges"].'</th>
	    </tr>
	    <tr>
		  <th>'.$MSG_LANG["challenger"].'</th>
		  <th>'.$MSG_LANG["yourcolor"].'</th>
		  <th>'.$MSG_LANG["type"].'</th>
		  <th>'.$MSG_LANG["challengerate"].'</th>
		  <th>'.$MSG_LANG["punctuation"].'</th>
		  <th>'.$MSG_LANG["reply"].'</th>
	    </tr>';
		while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
		{
			/* Opponent */
			echo("<tr><td>");
			/* get opponent's nick */
			if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
				$tmpOpponent = mysql_query("SELECT firstName,lastName,playerID FROM players WHERE playerID = ".$tmpGame['blackPlayer']);
			else
				$tmpOpponent = mysql_query("SELECT firstName,lastName,playerID FROM players WHERE playerID = ".$tmpGame['whitePlayer']);
			$row = mysql_fetch_array($tmpOpponent);
			$opponent = $row[0];
			$id = $row[2];
			echo "<a href='stats_user.php?cod=$id'>$opponent</a>";

			/* Your Color */
			echo ("</td><td>");
			if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
			{
				echo $MSG_LANG["white"];
				$tmpFrom = "white";
				$ratingW = $tmpGame['ratingWhite'];
				$ratingL = $tmpGame['ratingBlack'];
			}
			else
			{
				echo $MSG_LANG["black"];
				$tmpFrom = "black";
				$ratingL = $tmpGame['ratingWhite'];
				$ratingW = $tmpGame['ratingBlack'];
			}

            if ($tmpGame['oficial'] == "1")
                $oficial = $MSG_LANG["official"];
            else $oficial = $MSG_LANG["notofficial"];

            echo "<td>".$oficial."</td>";

            if ($tmpGame['oficial'] == "1"){
               $xpw = getXPW($ratingW,$ratingL,getPV($id));
               $xpl = getXPL($ratingL,$ratingW,getPV($id));
            }else{
                $xpl=0;
                $xpw=0;
            }

			$dificuldade = getDifficult($_SESSION['playerID'],$id);
			
            echo "<td>$dificuldade</td>\n";
			echo "<td><i>+$xpw / -$xpl</i></td>\n";

            /* Response */
			echo ("</td><td align='center'>");
			echo ("<input type='button' value='".$MSG_LANG["accept"]."' onclick=\"sendResponse('accepted', '".$tmpFrom."', ".$tmpGame['gameID'].")\">");
			echo ("<input type='button' value='".$MSG_LANG["reject"]."' onclick=\"sendResponse('declined', '".$tmpFrom."', ".$tmpGame['gameID'].")\">");

			echo("</td></tr>\n");
		}
	   echo '</table>
	   <input type="hidden" name="response" value="">
	   <input type="hidden" name="messageFrom" value="">
	   <input type="hidden" name="gameID" value="">
	   <input type="hidden" name="ToDo" value="ResponseToInvite">
	   </form>';
	}
?>

	<form name="existingGames" action="chess.php" method="post">
	<table border="1" width="100%">
	<tr>
		<th colspan=7><?=$MSG_LANG["gamesinprogress"]?></th>
	</tr>
	<tr>
        <th>&nbsp;</th>
		<th><?=$MSG_LANG["opponent"]?></th>
		<th><?=$MSG_LANG["yourcolor"]?></th>
		<th><?=$MSG_LANG["turn"]?></th>
		<th><?=$MSG_LANG["start"]?></th>
		<th><?=$MSG_LANG["lastmove"]?></th>
        <th><?=$MSG_LANG["official"]?></th>
	</tr>
<?
	$tmpGames = mysql_query("SELECT games.*,DATE_FORMAT(dateCreated, '%d/%m/%y %H:%i') as created, DATE_FORMAT(lastMove, '%d/%m/%y %H:%i') as lastm FROM games WHERE gameMessage = '' AND (whitePlayer = ".$_SESSION['playerID']." OR blackPlayer = ".$_SESSION['playerID'].") ORDER BY dateCreated");
	
	if (mysql_num_rows($tmpGames) == 0)
		echo("<tr><td colspan='7'>".$MSG_LANG["youdonthavegames"]."</td></tr>\n");
	else
	{
		while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
		{

			/* get opponent's nick */
			if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
				$tmpOpponent = mysql_query("SELECT firstName,lastName,lastUpdate,engine FROM players WHERE playerID = ".$tmpGame['blackPlayer']);
			else
				$tmpOpponent = mysql_query("SELECT firstName,lastName,lastUpdate,engine FROM players WHERE playerID = ".$tmpGame['whitePlayer']);
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
                        $t = $MSG_LANG["day"];
                    }
                }
                $txt = $img." $m $t";
            }
            echo "<tr><td><img src='images/$img.gif' alt='$txt'></td>";

    		/* Opponent */
			echo("<td>");
			echo("<a href='javascript:loadGame(".$tmpGame['gameID'].")'>".$opponent."</a>");

			/* Your Color */
			echo ("</td><td>");
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
			echo ("</td><td nowrap>".$tmpGame['created']);

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

			if ($d>=13)$cor="red";
			elseif ($d>=10)$cor="orange";
			else $cor ="black";
			if ($d ==1)$txt = $MSG_LANG['day'];
			else $txt = $MSG_LANG['days'];

			echo ("</td><td nowrap><font color='$cor'>".$tmpGame['lastm']." ($d $txt)</td>");

            if ($tmpGame['oficial'] == "1")
                $oficial = $MSG_LANG["yes"];
            else $oficial = $MSG_LANG["no"];

            echo "<td>".$oficial."</td></tr>\n";
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


<?
        flush();
        $p = mysql_query("SELECT count(*) FROM games WHERE gameMessage=''");
        $row = mysql_fetch_array($p);
        $games = $row[0];
        $p = mysql_query("SELECT count(*) FROM games WHERE gameMessage<>'' and gameMessage<>'playerInvited' and gameMessage<>'inviteDeclined'");
        $row = mysql_fetch_array($p);
        $terminados = $row[0];
        $p = mysql_query("select count(*),gameID from history group by gameID order by 1 desc limit 1");
        $row = mysql_fetch_array($p);
        $longa = ceil($row[0]/2);
        $longa_id = $row[1];

        $p = mysql_query("SELECT  distinct playerID FROM games, players WHERE (whitePlayer = playerID OR blackPlayer = playerID) AND gameMessage=''");
        $jogadores = mysql_num_rows($p);
        
        $p = mysql_query("SELECT * FROM players ORDER BY playerId DESC");
        $row = mysql_fetch_array($p);
        $users = mysql_num_rows($p);
        $lastuser = $row["firstName"];
        $lastuserid = $row["playerID"];

        $p = mysql_query("SELECT * FROM players WHERE engine='0' and lastUpdate > '".(time()-300)."'");
        $row = mysql_fetch_array($p);
        $online = mysql_num_rows($p);


?>

	<table border="1" width="100%">
	<tr>
		<th colspan=5><?=$MSG_LANG["statisticiansofthewebchess"] ?></th>
	</tr>
    <tr><td width=40% style="background: #cfcfbb"><B><?=$MSG_LANG["onlineplayers"] ?></B></td><td><a href="inviteplayer.php?ponline=1"><?=$online?></a></td></tr>
	<tr><td style="background: #cfcfbb"><B><?=$MSG_LANG["activeplayers"] ?></B></td><td><?=$jogadores?></td></tr>
    <tr><td style="background: #cfcfbb"><B><?=$MSG_LANG["activegames"] ?></B></td><td><?=$games?></td></tr>
    <tr><td style="background: #cfcfbb"><B><?=$MSG_LANG["finishedgames"] ?></B></td><td><?=$terminados?></td></tr>
    <tr><td style="background: #cfcfbb"><B><?=$MSG_LANG["users"] ?></B></td><td><?=$users?></td></tr>
    <tr><td style="background: #cfcfbb"><B><?=$MSG_LANG["longergame"] ?></B></td><td><?=$longa?> <?=$MSG_LANG['turns']?></td></tr>
    <tr><td style="background: #cfcfbb"><B><?=$MSG_LANG["lastuserregistered"] ?></B></td><td><a href="stats_user.php?cod=<?=$lastuserid?>"><?=$lastuser?></a></td></tr>
<?
    $p = mysql_query("SELECT games.*,DATE_FORMAT(dateCreated, '%d/%m/%y') as inicio,DATE_FORMAT(lastMove, '%d/%m/%y %H:%i') as fim from games where gameMessage<>'' AND  gameMessage<>'playerInvited' AND gameMessage<>'inviteDeclined' ORDER BY lastMove DESC LIMIT 5");
    if (mysql_num_rows($p)>0){
        echo "<tr><td colspan=2 style='background: #cfcfbb'><B>".$MSG_LANG["gamesfinishedrecently"]."</B></td></tr>";
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
				
				
            echo "<tr><td colspan=2 style='text-align:left'>[$row[fim]] $whitewin <a href='stats_user.php?cod=$idw'>$white</a> x <a href='stats_user.php?cod=$idb'>$black</a> $blackwin </td></tr>";
        }
    }
?>
	</table>



<form name="logout" action="mainmenu.php" method="post">
	<input type="hidden" name="ToDo" value="Logout">
</form>

<font face=verdana size=1>
<a href="http://kymera.comp.pucpcaldas.br/projects/compwebchess/" target="_blank">CompWebChess</a> <?=$VERSION?> © 2003,
<a href="http://www.inf.pucpcaldas.br/~rayel" target="_blank">Felipe Rayel</a>, <a href="credits.php?back=mainmenu">Collaborators</a>
<hr width=100% align=left>
	<table border=0 style="background:white"><tr><Td>
	<? include("footer.inc.php");?>
	</td></tr>
</table>
</font>


</body>
</html>

<? //mysql_close(); ?>

