<?php
##############################################################################################
#                                                                                            #
#                                fixuser.php                                                
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

	/* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
	fixOldPHPVersions();

	/* if this page is accessed directly (ie: without going through login), */
	/* player is logged off by default */
	if (!isset($_SESSION['playerID']))
		$_SESSION['playerID'] = -1;
	
	/* connect to database */
	require '../connectdb.php';

	/* chessdb */
	require '../chessdb.php';


	/* check session status */
	require '../sessioncheck.php';

	/* Language selection */
	require "../languages/".$_SESSION['pref_language']."/strings.inc.php";
	
	if (!isset($_GET['voltar']))
    	$voltar = "index.php";
	else $voltar = $_GET['voltar'];

	$player = $_GET['player'];
	if ($player == "")
	    $player = $_POST['player'];
	$action = $_POST['action'];
?>

<html>
<head>
	<title>WebChess</title>

<style>
TABLE   {font-size:11; font-family: verdana; background: #cfcfbb;}
.BOTOES {width:100; background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;}
TD      {background:white;}
</style>
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>
<? require_once('header.inc.php');?>
<BR>
<? if ($action == "" ){
	echo "Are you sure that you want to fix this user rating?";
	echo "<form method=post action='fixuser.php'>";
	echo "<input type=hidden name=player value=$player>";
	echo "<input type=hidden name=action value='APPLY'>";
	echo "<input type=submit value='".$MSG_LANG['yes']."'> <input type=button value='".$MSG_LANG['no']."' onClick=\"window.location='allusers.php'\">";
	echo "</form>";
	exit;
}
	$truerating = countRating($player);
    $p = mysql_query("UPDATE players SET rating='$truerating' WHERE playerID='$player'");

?>
<BR>
<script>
	alert('The user has been fixed!');
	window.location='allusers.php';
</script>

</font>

<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>


</body>
</html>

<? //mysql_close(); ?>

