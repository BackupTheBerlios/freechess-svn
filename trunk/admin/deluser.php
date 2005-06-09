<?php
##############################################################################################
#                                                                                            #
#                                deluser.php                                                
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
        require '../newgame.php';
        require '../chessdb.php';

        /* connect to database */
        require '../connectdb.php';

	$user = $_POST['playerID'];

    if ($user == ""){
			echo "Error: No user especified";
			exit;
    }
        
	$p = mysql_query("select * from games where whitePlayer=$user OR blackPlayer=$user");

	while($row = mysql_fetch_array($p)){
		$q = mysql_query("select * from games where gameID=".$row[gameID]);
		$player = $user;
		
		if ($player == $row[whitePlayer])
		  $opponent = $row[blackPlayer];
        else
          $opponent = $row[whitePlayer];
          
        while($row2 = mysql_fetch_array($q)){

            if ($row2['gameMessage'] == "playerResigned" && $row2['messageFrom'] == "white" && $player == $row2['whitePlayer'])
			     $situacao = "win";
            else if ($row2['gameMessage'] == "playerResigned" && $row2['messageFrom'] == "black" && $player == $row2['blackPlayer'])
			     $situacao = "win";
            else if ($row2['gameMessage'] == "playerResigned" && $row2['messageFrom'] == "white" && $player == $row2['blackPlayer'])
			     $situacao = "lost";
            else if ($row2['gameMessage'] == "playerResigned" && $row2['messageFrom'] == "black" && $player == $row2['whitePlayer'])
			     $situacao = "lost";
            else if ($row2['gameMessage'] == "checkMate" && $row2['messageFrom'] == "white" && $player == $row2['whitePlayer'])
			     $situacao = "lost";
            else if ($row2['gameMessage'] == "checkMate" && $row2['messageFrom'] == "black" && $player == $row2['blackPlayer'])
			     $situacao = "lost";
            else if ($row2['gameMessage'] == "checkMate" && $row2['messageFrom'] == "white" && $player == $row2['blackPlayer'])
			     $situacao = "win";
            else if ($row2['gameMessage'] == "checkMate" && $row2['messageFrom'] == "black" && $player == $row2['whitePlayer'])
			     $situacao = "win";
            else if ($row2['gameMessage'] == "draw")
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
              if ($row2['messageFrom'] == "white"){
                   $loser = $row2[whitePlayer];
                   $winner = $row2[blackPlayer];
              }else{
                   $winner = $row2[whitePlayer];
                   $loser = $row2[blackPlayer];
              }
          }

          if ($winner && $loser){
            //mysql_query("update players set rating=rating-".$row2['xpw']." WHERE playerID=$winner");
            //mysql_query("update players set rating=rating+".$row2['xpl']." WHERE playerID=$loser");
            }
        }

  	    mysql_query("DELETE FROM history WHERE gameID=".$row[gameID]);
	    mysql_query("DELETE FROM pieces WHERE gameID = ".$row[gameID]);
	    mysql_query("DELETE FROM messages WHERE gameID = ".$row['gameID']);
	    mysql_query("DELETE FROM games WHERE gameID = ".$row['gameID']);
	    mysql_query("DELETE FROM chat WHERE gameID = ".$row['gameID']);
	}
	
        mysql_query("DELETE FROM preferences WHERE playerID=$user");
        mysql_query("DELETE FROM players WHERE playerID=$user");
?>
