<?php
##############################################################################################
#                                                                                            #
#                                editgame.php                                                
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
	if (!isset($_CONFIG))
		require '../config.php';

	/* load external functions for setting up new game */
	require '../chessutils.php';
	require '../chessconstants.php';
	require '../chessdb.php';

	/* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
	fixOldPHPVersions();

	/* if this page is accessed directly (ie: without going through login), */
	/* player is logged off by default */
	if (!isset($_SESSION['playerID']))
		$_SESSION['playerID'] = -1;
	
	/* connect to database */
	require '../connectdb.php';

	/* check session status */
	require '../sessioncheck.php';

	/* Language selection */
	require "../languages/".$_SESSION['pref_language']."/strings.inc.php";

	$gameID = $_GET[gameID];
    $rs = mysql_query("SELECT * from games WHERE gameID =  $gameID");
	$row = mysql_fetch_array($rs);

	$rs = mysql_query("SELECT firstName from players WHERE playerID =  $row[whitePlayer]");
	$r = mysql_fetch_array($rs);
	$whitePlayer = $r[0];

	$rs = mysql_query("SELECT firstName from players WHERE playerID =  $row[blackPlayer]");
	$r = mysql_fetch_array($rs);
	$blackPlayer = $r[0];

?>

<html>
<head>
	<title>WebChess</title>
<style>
TABLE   {font-size:11; font-family: verdana; background: #cfcfbb;}
TD      {background: white; text-align:center;}
.BOTOES {width:100; background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;}
</style>
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>
<? require_once('header.inc.php');?>
<BR><BR>

	<form name=game action="altgame.php" method="post">
	<input type=hidden name=gameID value="<?=$gameID?>">
	<table border="1" width="600">
		<tr>
			<th width="200">
				<?=$MSG_LANG["white"]?>:
			</th>
			<td>
				<?= $whitePlayer ?>
			</td>
		</tr>
		<tr>
			<th width="200">
				<?=$MSG_LANG["black"]?>:
			</th>
			<td>
				<?= $blackPlayer ?>
			</td>
		<tr>
			<th width="200">
				<?=$MSG_LANG["status"]?>:
			</th>
			<td>
				<select name="messageFrom">
				<option value='white'>White</option>
				<option value='black'>Black</option>
				<option value=''>-</option>
				</select>&nbsp;
				<select name="gameMessage">
				<option value='checkMate'>vence por chequemate</option>
				<option value='playerResigned'>se rende</option>
				<option value='draw'>empata</option>
				<option value=''>Jogo não terminado</option>
				</select>
			</td>
		</tr>
		<tr>
			<th width="200">
				<?=$MSG_LANG["official"]?>:
			</th>
			<td>
				<select name="oficial">
				<option value='1'>Sim</option>
				<option value='0'>Não</option>
				</select>
			</td>
		</tr>
		<tr>
			<th width="200">
				<?=$MSG_LANG["timeofgame"]?>:
			</th>
			<td>
			Black: <input size=5 type=text name=timeBlack value="<?=$row[timeBlack]?>">&nbsp;
			White: <input size=5 type=text name=timeWhite value="<?=$row[timeWhite]?>">
			</td>
		</tr>

		<tr>
			<th width="200">
				<?=$MSG_LANG["rating"]?>:
			</th>
			<td>
			Winner: <input size=5 type=text name=xpw value="<?=$row[xpw]?>">&nbsp;
			Loser: <input size=5 type=text name=xpl value="<?=$row[xpl]?>">
			</td>
		</tr>

		<tr><td colspan=2>
		<input type="button" value="Cancelar" onClick="window.location='allgames.php'">&nbsp;
		<input type="button" value="Excluir" onClick="window.location='delgame.php?gameId=<?=$gameID?>'">&nbsp;
		<input type="submit" value="Alterar"></td></td>
	</table>
</form>

<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>

<script>
	document.game.messageFrom.value = "<?=$row[messageFrom]?>";
	document.game.gameMessage.value = "<?=$row[gameMessage]?>";
	document.game.oficial.value = "<?=$row[oficial]?>";
</script>

</body>
</html>

<? //mysql_close(); ?>

