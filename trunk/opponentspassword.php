<?php
##############################################################################################
#                                                                                            #
#                                opponentspassword.php                                                
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

	if (!isset($_CHESSUTILS))
		require_once ( 'chessutils.php');
		
	fixOldPHPVersions();
	
	/* check session status */
	require_once('sessioncheck.php');
	
	/* connect to database */
	require_once( 'connectdb.php');

	/* invalid password flag */
	$isInvalidPassword = false;
	
	/* check if submitting opponents login information */
	if (isset($_POST['opponentsID']))
	{
		$opponentsID = $_POST['opponentsID'];
		$opponentsNick = $_POST['opponentsNick'];

		/* get opponents password from DB */
		$tmpQuery = "SELECT password FROM players WHERE playerID = ".$opponentsID;
		$tmpPassword = mysql_query($tmpQuery);
		$dbPassword = mysql_result($tmpPassword, 0);

		/* check to see if supplied password matched that of the DB */
		if ($dbPassword == $_POST['pwdPassword'])
		{
			$_SESSION['isSharedPC'] = true;

			/* load game */
			require 'chess.php';
			die();
		}
		/* else password is invalid */
		else
			/* set flag to true */
			$isInvalidPassword = true;
		
	}
	/* else user is arriving here for the first time */
	else
	{
		/* get the players associated with this game */
		$tmpQuery = "SELECT whitePlayer, blackPlayer FROM games WHERE gameID = ".$_POST['gameID'];
		$tmpGameData = mysql_query($tmpQuery);
		$tmpPlayers = mysql_fetch_array($tmpGameData, MYSQL_ASSOC);
	
		/* determine which one is the opponent of the player logged in */
		if ($tmpPlayers['whitePlayer'] == $_SESSION['playerID'])
			$opponentsID = $tmpPlayers['blackPlayer'];
		else
			$opponentsID = $tmpPlayers['whitePlayer'];
	
		/* get the opponents information */
		$tmpQuery = "SELECT nick FROM players WHERE playerID = ".$opponentsID;
		$tmpNick = mysql_query($tmpQuery);
		$opponentsNick = mysql_result($tmpNick, 0);
	}

	mysql_close();
?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>WebChess Login</title>
</head>

<body>

<?
	if ($isInvalidPassword)
		echo("<h2>INVALID PASSWORD!!!  TRY AGAIN!</h2>\n");
?>

<h2>Enter password for <? echo($opponentsNick); ?>:</h2>

<form method="post" action="opponentspassword.php">
<p>
	Password: <input name="pwdPassword" type="password" size="15" />
	
	<input name="opponentsNick" type="hidden" value="<? echo($opponentsNick); ?>" />
	<input name="opponentsID" type="hidden" value="<? echo($opponentsID); ?>" />
	<input name="gameID" value="<? echo ($_POST['gameID']); ?>" type="hidden" />
</p>

<p>
	<input value="Continue" type="submit" />
	<input value="Cancel" type="button" onClick="window.open('mainmenu.php', '_self')"/>
</p>
</form>

</body>
</html>
