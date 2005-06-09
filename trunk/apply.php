<?php
##############################################################################################
#                                                                                            #
#                                apply.php                                                
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
        require_once ( 'newgame.php');

        /* connect to database */
        require_once( 'connectdb.php');
 
        /* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
        fixOldPHPVersions();

	   $playersColor = $_GET['playersColor'];
	   $action = $_GET['action'];

       $p = mysql_query("SELECT * from games WHERE gameID='".$_SESSION['gameID']."'");
       $row = mysql_fetch_array($p);
       $white = $row['whitePlayer'];
       $black = $row['blackPlayer'];

       if (($white != $_SESSION['playerID']) && ($black != $_SESSION['playerID']))
            die("Você não tem permissão.");

        if ($playersColor == "white")
        	$opponentColor = "black";
        else
            $opponentColor = "white";

       if (!$playersColor || !$action)
			displayError("Fatal Error at apply.php","index.php");

       if ($CFG_LOG_PATH =! ""){
                $log = "\"Apply bot game\" \"Game: $_SESSION[gameID]\" \"Winner: $playersColor\"";
                grava_log("webchess.log",$log,$CFG_LOG_PATH);
        }


        saveRanking($_SESSION['gameID'],$action,$playersColor);
        updateTimestamp();

?>
<form name="existingGames" action="chess.php" method="post">
        <input type='hidden' name='rdoShare' value='no'>
	<input type="hidden" name="gameID" value="">
	<input type="hidden" name="sharePC" value="no">
</form>

<script>
	document.existingGames.gameID.value=<?=$_SESSION['gameID']?>;
	document.existingGames.submit();
</script>
