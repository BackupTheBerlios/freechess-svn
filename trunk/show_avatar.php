<?php
##############################################################################################
#                                                                                            #
#                                show_avatar.php                                                
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


    require_once ('config.php');
    require_once ( 'chessconstants.php');
    require_once('chessdb.php');
    require_once ( 'chessutils.php');
    require_once('gui.php');

    fixOldPHPVersions();

 	/* check session status */
	require_once('sessioncheck.php');

    require_once( 'connectdb.php');

$player= $_GET['player'];
$p = mysql_query("select avatar from players where playerID='$player'");
$row = mysql_fetch_array($p);


header("Content-type: application/$type");
header("Pragma: ");
header("Cache-Control: cache");
header("Expires: 0");
echo $row[avatar];


?>
