<?php
##############################################################################################
#                                                                                            #
#                                sanity.php                                                
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
        require_once ( 'chessconstants.php');
        require_once ( 'newgame.php');
        require_once('chessdb.php');

        /* connect to database */
        require_once( 'connectdb.php');
$q = mysql_query("select * from games WHERE gameMessage = '' OR gameMessage='playerInvited'");
while ($row = mysql_fetch_array($q)){
    $p = mysql_query("select rating from players where playerID = $row[whitePlayer]");
    $row2 = mysql_fetch_array($p);
    $whiterating = $row2[0];
    $p = mysql_query("select rating from players where playerID = $row[blackPlayer]");
    $row2 = mysql_fetch_array($p);
    $blackrating = $row2[0];
    //echo "Update game $row[gameID] with: $row[whitePlayer]: $whiterating, $row[blackPlayer]: $blackrating\n";
    //mysql_query("update games set ratingWhite=$whiterating,ratingBlack=$blackrating,ratingWhiteM=$whiterating,ratingBlackM=$blackrating WHERe gameID=$row[gameID]");
}

	$q = mysql_query("select * from games WHERE gameMessage='draw' OR gameMessage='playerResigned'");
	while ($row = mysql_fetch_array($q)){
        $p = mysql_query("SELECT COUNT(gameID) FROM history WHERE gameID = ".$row['gameID']);
        $numMoves = ceil(mysql_result($p,0)/2);
        $p = mysql_query("SELECT COUNT(gameID) FROM pieces WHERE gameID = ".$row['gameID']);
        $pieces = mysql_result($p,0);

        if ($numMoves == 0 && $pieces == 0)
            echo "Jogo $row[gameID] sem Movimento!\n";
            mysql_query("DELETE FROM history WHERE gameID=".$row['gameID']);
			mysql_query("DELETE FROM pieces WHERE gameID = ".$row['gameID']);
			mysql_query("DELETE FROM messages WHERE gameID = ".$row['gameID']);
			mysql_query("DELETE FROM games WHERE gameID = ".$row['gameID']);
    }


exit;

	$q = mysql_query("select * from games WHERE gameMessage='draw' OR gameMessage='playerResigned'");
	while ($row = mysql_fetch_array($q)){
		$tmpNumMoves = mysql_query("SELECT COUNT(gameID) FROM history WHERE gameID = ".$row['gameID']);
                $numMoves = ceil(mysql_result($tmpNumMoves,0)/2);
		if ($numMoves < 2){
			echo "Remover $row[gameID]\n";

            if ($row['messageFrom'] == "")
                $player = $row['messageFrom'] = "white";
                
            $player = $row[whitePlayer];
            $opponent = $row[blackPlayer];

        if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "white" && $player == $row['whitePlayer'])
			     $situacao = "win";
        else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "black" && $player == $row['blackPlayer'])
			     $situacao = "win";
        else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "white" && $player == $row['blackPlayer'])
			     $situacao = "lost";
        else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "black" && $player == $row['whitePlayer'])
			     $situacao = "lost";
        else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "white" && $player == $row['whitePlayer'])
			     $situacao = "lost";
        else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "black" && $player == $row['blackPlayer'])
			     $situacao = "lost";
        else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "white" && $player == $row['blackPlayer'])
			     $situacao = "win";
        else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "black" && $player == $row['whitePlayer'])
			     $situacao = "win";
        else if ($row['gameMessage'] == "draw")
			     $situacao = "draw";
        else
                $situacao = "empty";
		
	$winner="";
	$loser="";
		
        if ($situacao == "win"){
            $winner = $opponent;
            $loser = $player;
        }else if($situacao == "lost"){
            $loser = $opponent;
            $winner = $player;
        }else if($situacao == "draw"){
              if ($row['messageFrom'] == "white"){
                   $loser = $row[whitePlayer];
                   $winner = $row[blackPlayer];
              }else{
                   $winner = $row[whitePlayer];
                   $loser = $row[blackPlayer];
              }
        }

        if ($winner && $loser){
            mysql_query("update players set rating=rating-".$row['xpw']." WHERE playerID=$winner");
            mysql_query("update players set rating=rating+".$row['xpl']." WHERE playerID=$loser");
        }

			mysql_query("DELETE FROM history WHERE gameID=".$row[gameID]);
			mysql_query("DELETE FROM pieces WHERE gameID = ".$row[gameID]);
			mysql_query("DELETE FROM messages WHERE gameID = ".$row['gameID']);
			mysql_query("DELETE FROM games WHERE gameID = ".$row['gameID']);
		}
	}	

?>
