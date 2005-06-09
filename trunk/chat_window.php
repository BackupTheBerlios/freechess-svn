<?php
##############################################################################################
#                                                                                            #
#                                chat_window.php
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


    /* include outside functions */
    if (!isset($_CHESSUTILS))
    require_once ( 'chessutils.php');
    require_once('gui.php');
    require_once('chessdb.php');
    require 'move.php';
    require 'undo.php';


    /* Language selection */
    require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");

    /* if this page is accessed directly (ie: without going through login), */
    /* player is logged off by default */
    if (!isset($_SESSION['playerID']))
        $_SESSION['playerID'] = -1;





?>
<html>
<head>
<META HTTP-EQUIV="Expires" CONTENT="0">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">

<!-- Automatischer Refresh in Sekunden -->

<?

//$sec = (!empty($_GET['sec'])) ? $_GET['sec'] : $_POST['sec'];

//if ($sec > 0)
//{
 //echo '<META HTTP-EQUIV=Refresh CONTENT="'. $sec .'; URL=chat_window.php?sec='. $sec .'">';
//}


?>

<title>Public Message Room</title>

</head>
<body leftmargin="0" topmargin="0">
<table border="1" width="100%" height="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="left" valign="top">
<font face="Verdana" size="2">

<?
//this causes a undefined index error when there are no games in the db-
if (empty($_SESSION['gameID']))
{
    $_SESSION['gameID'] = 0;
}
    echo writepublicChat($_SESSION['gameID'], $MSG_LANG);

?>

</font>
</td>
</tr>
</table>
</body>
</html>
