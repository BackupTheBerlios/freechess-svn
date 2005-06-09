<?php
##############################################################################################
#                                                                                            #
#                                recommend.php                                                
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
<title>Recommend Us</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
</head>

<body>
<? require_once('header.inc.php');?>
<br>
<? include("ads/google.inc.php");?>
<br>
<?php include("inc.recommend.php"); ?>
<br>
<? include("footer.inc.php");?>
</body>
</html>
<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>

<? //mysql_close(); ?> 