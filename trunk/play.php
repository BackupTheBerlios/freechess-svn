<?php
##############################################################################################
#                                                                                            #
#                                play.php                                                
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

    /*
        WebChessBot V1.0.4
        Interface Script by Felipe Rayel
    */
    
	# Where the chess bot bin is located?
	# We also will write some text files in this directory.

	$bot_path = "/scripts/chess/computer";      	/* at Linux */
	//$bot_path = "c:\\scripts\\chess\\computer";   /* at Windows */
	
	$engine_name = "engine";                    /* at Linux */
	// $engine_name = "engine.exe";             /* at Windows */
	$webchess_path = "/scripts/chess";
    
	/*Chess bot playerID to use, or 0 to play with all bots*/
	$chess_bot_id = "0";
    
    define ("DEBUG", 0);

    function getPieceFileCode($piecename)
	{
		switch($piecename)
		{
			case 'pawn':
				$pgnCode = "p";
				break;
			case 'knight':
				$pgnCode = "n";
				break;
			case 'bishop':
				$pgnCode = "b";
				break;
			case 'rook':
				$pgnCode = "r";
				break;
			case 'queen':
				$pgnCode = "q";
				break;
			case 'king':
				$pgnCode = "k";
				break;
		}

		return $pgnCode;
	}

    function invertFileCode($piecename)
	{
		switch($piecename)
		{
			case 'P':
				$pgnCode = PAWN;
				break;
			case 'N':
				$pgnCode = KNIGHT;
				break;
			case 'B':
				$pgnCode = BISHOP;
				break;
			case 'R':
				$pgnCode = ROOK;
				break;
			case 'Q':
				$pgnCode = QUEEN;
				break;
			case 'K':
				$pgnCode = KING;
				break;

			case 'p':
				$pgnCode = BLACK | PAWN;
				break;
			case 'n':
				$pgnCode = BLACK | KNIGHT;
				break;
			case 'b':
				$pgnCode = BLACK | BISHOP;
				break;
			case 'r':
				$pgnCode = BLACK | ROOK;
				break;
			case 'q':
				$pgnCode = BLACK | QUEEN;
				break;
			case 'k':
				$pgnCode = BLACK | KING;
				break;
		}

		return $pgnCode;
	}


	/* load settings */
	if (!isset($_CONFIG))
		require "$webchess_path/config.php";
	
	/* define constants */
	require "$webchess_path/chessconstants.php";

	/* include outside functions */
	if (!isset($_CHESSUTILS))
		require "$webchess_path/chessutils.php";
	require "$webchess_path/gui.php";
	require "$webchess_path/chessdb.php";
	require "$webchess_path/move.php";
	require "$webchess_path/undo.php";
	require "$webchess_path/newgame.php";

	/* connect to database */
	require "$webchess_path/connectdb.php";

    $now = date("d/m/Y H:i:s");

	// For each player (Lower PLY first):
	if ($chess_bot_id>0)
		$p1 = mysql_query("select * from players WHERE ativo='1' AND playerID='$chess_bot_id'");
	else
    	$p1 = mysql_query("select * from players WHERE ativo='1' AND (engine='2' OR engine='1') ORDER BY engine_flag");

    while($computer = mysql_fetch_array($p1)){

    $JOGADOR = $computer['playerID'];
    $JOGADOR_NAME = $computer['firstName'];
    $PLY = $computer['engine_flag'];
    if (strlen($PLY) == 1)
        $PLY = "0".$PLY;
    if ($PLY>99){
	echo "$now # $PLY for $JOGADOR_NAME is too high!";
	exit;
	}

    $agora = time();
    mysql_query("update players set lastUpdate='$agora' WHERE playerID=".$JOGADOR);

    //Accepting the games
    $p = mysql_query("select * from games WHERE gameMessage = 'playerInvited' AND ((whitePlayer = $JOGADOR AND messageFrom = 'black') OR (blackPlayer = $JOGADOR AND messageFrom = 'white'))");
    while($row = mysql_fetch_array($p)){

		$oficial = $row[oficial];

        ####################################
        # Only play 1x with each player
        ####################################
        if ($JOGADOR != $row[blackPlayer]){
            $opponent = $row[blackPlayer];
            $JOGADOR_COR = "white";
        }
        else{
            $opponent = $row[whitePlayer];
            $JOGADOR_COR = "black";
        }

        $q = mysql_query("SELECT count(*) FROM games WHERE gameMessage='' AND
        ((whitePlayer = ".$JOGADOR." AND blackPlayer = ".$opponent.")
        OR (whitePlayer = ".$opponent." AND blackPlayer = ".$JOGADOR."))
        ");

        $mult = mysql_fetch_array($q);

        ####################################
    	//$opLevel = getPlayerLevel($opponent);
    	//$myLevel = getPlayerLevel($JOGADOR);
    	//$diff = $myLevel - $opLevel;
    
    $RatingG = getRating($opponent);
    $RatingP = getRating($JOGADOR);
    $diff = $RatingP - $RatingG;
    

    if ($mult[0]>0 && timelimit > 1800){
		echo "$now # $JOGADOR_NAME ignoring multiple games: ". $row['gameID']."\n";
        mysql_query("UPDATE games SET reason='#multiplegames',gameMessage = 'inviteDeclined', messageFrom = '".$JOGADOR_COR."' WHERE gameID = ".$row['gameID']);
	}
   else if ($oficial && timelimit > 1800 && (($diff >= 250 && $PLY == 5) || ($diff >= 200 && $PLY == 6) ||
             ($diff >= 150 && $PLY == 7) || ($diff >= 100 && $PLY == 8) ||
             ($diff >= 50 && $PLY >= 9))){
		echo "$now # $JOGADOR_NAME ignoring weak player: ". $row['gameID']."\n";
        mysql_query("UPDATE games SET reason='#weakplayer',gameMessage = 'inviteDeclined', messageFrom = '".$JOGADOR_COR."' WHERE gameID = ".$row['gameID']);
	}	
    else if ($mult[0] == 0){

            // Open initBoard.txt
            $fp = fopen("$bot_path/initBoard.txt","rb");
            $lastBoard = base64_encode(fread($fp,filesize("$bot_path/initBoard.txt")));
            fclose($fp);	

            $tmpQuery = "UPDATE games SET lastBoard='$lastBoard', gameMessage = '', messageFrom = '' WHERE gameID = '".$row['gameID']."'";
            mysql_query($tmpQuery);
            createNewGame($row['gameID']);

            // Saving game
            // Flag the game 0
            // mysql_query("UPDATE games set gameok='0' WHERE gameID = ".$_row['gameID']);
        
    		mysql_query("DELETE FROM pieces WHERE gameID = ".$row['gameID']);
    		for ($i = 0; $i < 8; $i++)
    		{
    			for ($j = 0; $j < 8; $j++)
    			{
    				if ($board[$i][$j] != 0)
    				{
    					if ($board[$i][$j] & BLACK)
    						$tmpColor = "black";
    					else
    						$tmpColor = "white";
        				$tmpPiece = getPieceName($board[$i][$j]);
    				    mysql_query("INSERT INTO pieces (gameID, color, piece, row, col) VALUES (".$row['gameID'].", '$tmpColor', '$tmpPiece', $i, $j)");
    				}
    			}
    		}
    		echo "$now # $JOGADOR_NAME accepting game: ". $row['gameID']."\n";
            // Flag the game 1
            //mysql_query("UPDATE games set gameok='1' WHERE gameID = ".$_row['gameID']);
        }
    }

    //Playing
    $p = mysql_query("select * from games WHERE gameMessage = '' AND (whitePlayer = $JOGADOR OR blackPlayer = $JOGADOR)");
    while($row = mysql_fetch_array($p)){
		$PLAY = TRUE;
		$gameID = $row['gameID'];
        unlink("$bot_path/boardIn.txt");
        unlink("$bot_path/boardOut.txt");
        unlink("$bot_path/move.txt");
        //Draw the board

		/* get current player's color */
		$tmpQuery = "SELECT whitePlayer, blackPlayer FROM games WHERE gameID = ".$gameID;
		$tmpTurns = mysql_query($tmpQuery);
		$tmpTurn = mysql_fetch_array($tmpTurns, MYSQL_ASSOC);

		if ($tmpTurn['whitePlayer'] == $JOGADOR){
			$playersColor = "white";
			$opponentColor="black";
			$file = "0";
		}
		else{
			$playersColor = "black";
			$opponentColor="white";
			$file = "1";
		}
		
        /* get number of moves from history */
        $tmpNumMoves = mysql_query("SELECT COUNT(gameID) FROM history WHERE gameID = ".$gameID);
        $numMoves = mysql_result($tmpNumMoves,0);

        /* get number of pieces */
        $tmpNumPieces = mysql_query("SELECT COUNT(gameID) FROM pieces WHERE gameID = ".$gameID);
        $numPieces = mysql_result($tmpNumPieces,0);

        // Draws
        $q = mysql_query("select * from messages WHERE gameID=$gameID");
        $msg = mysql_fetch_array($q);

        if ($msg[msgType] == "draw" && $msg[msgStatus]!= "denied" && $numMoves<200 && $numPieces >3){
			echo "$now # $JOGADOR_NAME ($playersColor) draw denied at game: ". $row['gameID']."\n";
            mysql_query("UPDATE messages SET msgStatus = 'denied', destination = '".$opponentColor."' WHERE gameID = $gameID");
		}
        else if ($msg[msgType] == "draw" && ($numMoves>=200 || $numPieces <=3)){
	    	echo "$now # $JOGADOR_NAME ($playersColor) drawing the game: ". $row['gameID']."\n";
            $_POST['isDrawResponseDone'] = "yes";
            $_POST['drawResponse'] = "yes";
            $_SESSION["gameID"] = $row['gameID'];
            processMessages();
        }

		// Check if the game is not blocked by a unfinished promotion process:
		if ($playersColor == "white"){
			$promotionRow = 7;
			$otherRow = 0;
		}
		else{
			$promotionRow = 0;
			$otherRow = 7;
		}
		$promo = mysql_query("SELECT * FROM pieces WHERE color='$opponentColor' AND piece='pawn' AND row='$promotionRow' AND gameID = ".$gameID);
		if (mysql_num_rows($promo) >0){
			$PLAY = FALSE;
            echo "$now # $JOGADOR_NAME ($playersColor) PLY: $PLY playing at game: ". $row['gameID']."\n";
		}
        // Resign
            // Never?
            
		/* based on number of moves, output current color's turn */
		if (($numMoves % 2) == 0)
			$tmpCurMove = "white";
		else
			$tmpCurMove = "black";

		if ($tmpCurMove != $playersColor){
			#echo "$now # $JOGADOR_NAME Aguardando jogada: ". $row['gameID']."\n";
        }
        else if ($PLAY){
            echo "$now # $JOGADOR_NAME ($playersColor) PLY: $PLY playing at game: ". $row['gameID']."\n";

    		for ($i = 0; $i < 8; $i++)
                for ($j = 0; $j < 8; $j++)
                    $board[$i][$j] = ".";

    		for ($i = 0; $i < 8; $i++)
                for ($j = 0; $j < 8; $j++)
                    $tboard[$i][$j] = 0;

            $pieces = mysql_query("SELECT * FROM pieces WHERE gameID = ".$gameID);
            while ($thisPiece = mysql_fetch_array($pieces, MYSQL_ASSOC))
            {
                $board[$thisPiece["row"]][$thisPiece["col"]] = getPieceFileCode($thisPiece["piece"]);
                $tboard[$thisPiece["row"]][$thisPiece["col"]] = getPieceCode($thisPiece["color"], $thisPiece["piece"]);

                if ($thisPiece["color"] == "white")
                    $board[$thisPiece["row"]][$thisPiece["col"]] = strtoupper($board[$thisPiece["row"]][$thisPiece["col"]]);

            }

            $nu2ch[0]='a';
            $nu2ch[1]='b';
            $nu2ch[2]='c';
            $nu2ch[3]='d';
            $nu2ch[4]='e';
            $nu2ch[5]='f';
            $nu2ch[6]='g';
            $nu2ch[7]='h';
            
            $hist = mysql_query("SELECT * FROM history WHERE gameID = ".$gameID." ORDER BY timeOfMove DESC");
            if (mysql_num_rows($hist) >0){
                $thisHist = mysql_fetch_array($hist);
                $thisHist['fromRow']++;
                $thisHist['toRow']++;
                $lastMove = $nu2ch[$thisHist['fromCol']].$thisHist['fromRow'].$nu2ch[$thisHist['toCol']].$thisHist['toRow'];

                $thisHist = mysql_fetch_array($hist);
                $thisHist['fromRow']++;
                $thisHist['toRow']++;
                $lastMove2 = $nu2ch[$thisHist['fromCol']].$thisHist['fromRow'].$nu2ch[$thisHist['toCol']].$thisHist['toRow'];
            }
            else
                $lastMove = "";

            $row['lastBoard'] = base64_decode($row['lastBoard']);

            $file = substr($row['lastBoard'],6,strlen($row['lastBoard']));

            if ($lastMove != ""){
	           if ($playersColor == "white")
                	$file = "1move1".$file;
	           else if ($playersColor == "black")
         	        $file = "0move0".$file;
               $file = str_replace("move",$lastMove,$file);
	    }
	    else{
	        if ($playersColor == "white")
                	$file = "@move0".$file;
	        else if ($playersColor == "black")
         	        $file = "@move1".$file;
	    }		


	    // Move check:
	    $move="";
        $timeS = time();
	    while (TRUE){
		  $fp = fopen("$bot_path/boardIn.txt","w");
            	  fwrite($fp,$PLY.$file);
                  fclose($fp);

            	  // call the engine
            	  $out = `$bot_path/$engine_name`;

				  //read the move
                  $fp = fopen("$bot_path/move.txt","r");
                  $move = fread($fp,filesize("$bot_path/move.txt"));
                  fclose($fp);

		  if ($move == $lastMove || $move == $lastMove2 || $move == ""){
		  	$PLY--;
		 	if (strlen($PLY) == 1)
				$PLY = "0".$PLY;
		  }
         	 elseif (($move == "f6g4" && $lastMove == "e4e5" && $lastMove2 == "g8f6") ||
                    ($move == "c8b7" && $lastMove == "d1d6" && $lastMove2 == "f8g8") ||
                    ($move == "f8h7" && $lastMove == "d2f4" && $lastMove2 == "g7f6") ||
  					($move == "e7e6" && $lastMove == "h4g6" && $lastMove2 == "b8d7")
					 ){
			//($move == "e8e7" && $lastMove == "f6g6" && $lastMove2 == "e7e8") ||
			//($move == "d8f8" && $lastMove == "f6f8" && $lastMove2 == "d7d5")){
				$PLY++;
				if (strlen($PLY) == 1)
                	$PLY = "0".$PLY;
				echo "$now # WARNING: $JOGADOR_NAME Cheat Detected!\n";
		  }
		  else break;

		  if ($PLY <2){
            $timeT = round((time()-$timeS)/60,2);
  	  	  	echo "$now # $JOGADOR_NAME INVALID MOVE: $move Time: $timeT min.\n";
			exit;
		  }
  	  	  
 		  echo "$now # $JOGADOR_NAME Trying again with PLY: $PLY.\n";
	    }

            $timeT = round((time()-$timeS)/60,2);
            echo "$now # $JOGADOR_NAME moved: $move Time: $timeT min.\n";
            
            $ch2nu['a']=0;
            $ch2nu['b']=1;
            $ch2nu['c']=2;
            $ch2nu['d']=3;
            $ch2nu['e']=4;
            $ch2nu['f']=5;
            $ch2nu['g']=6;
            $ch2nu['h']=7;

            $POST['fromCol'] = abs($ch2nu[substr($move,0,1)]);
            $POST['fromRow'] = abs(substr($move,1,1)-1);
            $POST['toCol']   = abs($ch2nu[substr($move,2,1)]);
            $POST['toRow']   = abs(substr($move,3,1)-1);

            // apply the move
			$board = $tboard;
			$_SESSION['gameID'] = $gameID;
            $numMoves --;

            // Saving game
            // Saving board.txt
            $fp = fopen("$bot_path/board.txt","r");
            $lastBoard = fread($fp,filesize("$bot_path/board.txt"));
	        $lastBoard = base64_encode(substr($lastBoard,2,strlen($lastBoard)));
            fclose($fp);
            mysql_query("UPDATE games SET lastBoard='$lastBoard' where gameID='$gameID'");

            // Save the new board:
            $fp = fopen("$bot_path/boardOut.txt","r");
            $newBoardv = fread($fp,filesize("$bot_path/boardOut.txt"));
            fclose($fp);

            $v = explode("\n",$newBoardv);
            $newBoardv="";
            for ($x=count($v)-1;$x>=0;$x--)
                $newBoardv .= $v[$x];

            for ($i = 0; $i < 8; $i++)
		      for ($j = 0; $j < 8; $j++)
		          $newBoard[$i][$j] = 0;
		          
            $x=0;
            for ($i = 0; $i <8; $i++)
		      for ($j = 0; $j <8; $j++){
		          if (substr($newBoardv,$x,1) != ".")
                        $newBoard[$i][$j] = invertFileCode(substr($newBoardv,$x,1));
                  $x++;
              }

            // Flag the game 0
            // mysql_query("UPDATE games set gameok='0' WHERE gameID = ".$gameID);

            mysql_query("DELETE FROM pieces WHERE gameID = ".$gameID);
            for ($i = 0; $i < 8; $i++)
            {
                for ($j = 0; $j < 8; $j++)
                {

                    if ($newBoard[$i][$j] != 0)
                    {
                        if ($newBoard[$i][$j] & BLACK)
                            $tmpColor = "black";
                        else
                            $tmpColor = "white";
                        $tmpPiece = getPieceName($newBoard[$i][$j]);
                        mysql_query("INSERT INTO pieces (gameID, color, piece, row, col) VALUES ($gameID, '$tmpColor', '$tmpPiece', $i, $j)");
				    }
                }
            }
            /*  Flag the game 1*/
            // mysql_query("UPDATE games set gameok='1' WHERE gameID = ".$gameID);
            saveHistory($POST);
            doMove($POST);
            mysql_query("UPDATE games SET lastMove = NOW() WHERE gameID = '$gameID'");

            // Update players time
	        $ptime = mysql_query("SELECT * from history where curColor<>'$playersColor' AND gameID=".$_SESSION['gameID']." ORDER BY timeOfMove DESC limit 1");
            $rowtime = mysql_fetch_array($ptime);
            $cor = $rowtime['curColor'];
            $lastmove = $rowtime['timeOfMove'];

			// Length:
            $v = explode(" ",$lastmove);
            $hora = explode(":",$v[1]);
            $data = explode("-",$v[0]);

            if ($lastmove == 0)
                $inicio = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
            else
                $inicio = mktime($hora[0],$hora[1],$hora[2],$data[1],$data[2],$data[0]);
            $fim = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));

            $dif = $fim-$inicio;

		    if ($playersColor == "white")
                mysql_query("UPDATE games set timeWhite=timeWhite+$dif WHERE gameID=".$_SESSION['gameID']);
            else
                mysql_query("UPDATE games set timeBlack=timeBlack+$dif WHERE gameID=".$_SESSION['gameID']);
        }


    }
}

?>
