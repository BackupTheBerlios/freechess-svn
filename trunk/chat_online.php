<?php
##############################################################################################
#                                                                                            #
#                                chat_online.php                                                
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

	/* define constants */
	require_once ( 'chessconstants.php');

	/* include outside functions */
	if (!isset($_CHESSUTILS))
		require_once ( 'chessutils.php');
	require_once('gui.php');
	require_once('chessdb.php');
	require 'move.php';
	require 'undo.php';

	/* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
	fixOldPHPVersions();

	/* check session status */
	require_once('sessioncheck.php');

    /* if this page is accessed directly (ie: without going through login), */
	/* player is logged off by default */
	if (!isset($_SESSION['playerID']))
		$_SESSION['playerID'] = -1;

	/* debug flag */
	define ("DEBUG", 0);

	/* connect to database */
	require_once( 'connectdb.php');

	/* Language selection */
	require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");

?>

<html>
<head>

<!-- Automatischer Refresh in Sekunden -->
<META HTTP-EQUIV=Refresh CONTENT="10; URL=chat_online.php">

<title>PSM CHESS CHAT</title>

</head>
<body leftmargin="0" topmargin="0">

<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="left">
<font face="Verdana" size="2">

<?

    // User Online Management
    // By Thomas Müller (thomas@fivedigital.net)
    // Five Digital (http://www.fivedigital.net)

    $minutes = 5; // Anzahl der Minuten die ein User als inaktiv Online gilt

    $minutes = $minutes * 60;

    $playerID = $_SESSION['playerID'];
    $time = time();

    $tmp = mysql_query("SELECT time FROM chat_online WHERE playerID='$playerID'");
    $tmp = mysql_fetch_array($tmp);
    $tmp = $tmp['time'];

    if ($tmp) {

    mysql_query("UPDATE chat_online SET time = '$time' WHERE playerID='$playerID'");

    }

    // Neuer User

    else {

    $pl = mysql_query("SELECT * FROM players WHERE playerID='".$_SESSION['playerID']."'");
    $me = mysql_fetch_array($pl);

    mysql_query("INSERT INTO chat_online VALUES ('$playerID', '$time')");

    $msg = $me['firstName'] . "event-enter";

    mysql_query("insert into testchat (fromID,msg,gameID) VALUES ('0','$msg','".$_SESSION[gameID]."')");

    }

    $deltime = $time - $minutes;

    // Inaktive User löschen

    mysql_query("DELETE FROM chat_online WHERE time < '$deltime'");

    // XXX Betrit den Chat löschen

    $minutes = 60; // Anzahl der Minuten die Messages gelöscht werden

    $minutes = $minutes * 60;

    $deltime = $time - $minutes;

    mysql_query("DELETE FROM testchat WHERE UNIX_TIMESTAMP(hora) < '$deltime' AND fromID = '0'");

    $p = mysql_query("SELECT c.playerID, p.* from chat_online c
    			LEFT JOIN players p
                         ON p.playerID = c.playerID");

    while($ids = mysql_fetch_array($p))
    {
    	echo "<b>" . $ids['firstName'] . "</b><br> ";
    } // while

    // Ende User Online Management

    ?>

</font>
</td>
</tr>
</table>
</body>
</html>
