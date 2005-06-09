<?php
##############################################################################################
#                                                                                            #
#                                exportpgn.php                                                
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

    header("Content-type: text/plain; charset=\"us-ascii\"");
    //header("Content-type: application/octetstream");
    header("Content-disposition: attachment; filename=\"game$_SESSION[gameID].pgn\"");
    header("Pragma: ");
    header("Cache-Control: cache");
    header("Expires: 0");
    require_once ('config.php');
    require_once ( 'chessconstants.php');
    require_once('chessdb.php');
    require_once ( 'chessutils.php');
    require_once('gui.php');

    fixOldPHPVersions();

   	/* check session status */
	require_once('sessioncheck.php');

    require_once( 'connectdb.php');
    loadHistory();

	$gameID = $_SESSION[gameID];
	$r = mysql_query("SELECT p1.firstName AS player1_name, p2.firstName AS
	player2_name,p1.rating as r1,p2.rating as r2 FROM games g LEFT JOIN players p1 ON (g.whitePlayer =
	p1.playerID) LEFT JOIN players p2 ON (g.blackPlayer = p2.playerID) WHERE
	g.gameID = $gameID");
	$row1 = mysql_fetch_array($r);

	$result = "*";          //In progress

	$r = mysql_query("SELECT * FROM games WHERE gameID='$gameID'");

	while ($row = mysql_fetch_array($r)){
            if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "white")
			     $result = "0-1";		// Black Wins
            else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "black" && $player == $row['blackPlayer'])
			     $result = "1-0";		// White Wins
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "white")
			     $result = "1-0";		// White Wins
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "black")
			     $result = "0-1";		// Black Wins
            else if ($row['gameMessage'] == "draw")
			     $result = "1/2-1/2";    // Draw
            else
                 $result = "*";          //In progress
	}
	
	$date = date("Y.m.d");
	echo "[Site \"http://www.Webmaster play free chess\"]\n";
	echo "[Event gameID ".$_SESSION[gameID]."]\n";
	//echo "[Event gameID \'$gameID'\]\n";
	//echo "[Site \"http://www.Webmaster play free chess"\"]\n";
	echo "[Date \"$date\"]\n";
	echo "[Round \"-\"]\n";
	echo "[White \"".$row1['player1_name']." $row1[r1]\"]\n";
	echo "[Black \"".$row1['player2_name']." $row1[r2]\"]\n";
	echo "[Result \"$result\"]\n";

	writeHistoryPGN("plain");

	echo $result;
?>
