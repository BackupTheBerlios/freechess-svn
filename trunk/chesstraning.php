<?php
##############################################################################################
#                                                                                            #
#                                chesstraining.php                                                
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

include_once 'class_reload.php';
$f =& new formreload;

if (($f->checktoken())==($f->tokinhalt())) {
    /**
     * If posted token and session token is the same a reload is active!
     */
   
     /*   print "Sie haben mit der F5 oder dem Browserbutton einen Reload gemacht!"."<BR>"."Dies sollten Sie vermeiden, weil sonst fehlerhafte Daten entstehen können."."<BR>"."<b>Bitte drücken Sie so lange den Zurück-Knopf des Browsers bis Sie auf die Hauptseite gelangen!</b>"."<BR><BR>"."Please don't press the reload button of the browser, because this can produce bad data."."<BR>"."<b>Please press the back-button of the browser to reach the mainpage!</b>";
    */
   $chatdata = false;
} else {
   $chatdata = true;

    /**
     * write chatdata
     */
}
	/* load settings */
	include_once ('config.php');
	
	require('ob.lib.php');
	if ($COMPRESSION) {
		$ob_mode = PMA_outBufferModeGet();
	        if ($ob_mode) {
        	        PMA_outBufferPre($ob_mode);
	        }
        }
                    

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
	/* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
	fixOldPHPVersions();

	/* check session status */
	require_once('sessioncheck.php');

	/* Language selection */
	require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");
switch($_POST['ToDo'])
	{

case 'ResponseToInvite':
			if ($_POST['response'] == 'accepted')
			{
				
				/* update game data */
				$tmpQuery = "UPDATE games SET gameMessage = '', messageFrom = '' WHERE gameID = ".$_POST['gameID'];
				mysql_query($tmpQuery);

				/* setup new board */
				$_SESSION['gameID'] = $_POST['gameID'];
				createNewGame($_POST['gameID']);
				saveGame();
			}
			else
			{
				
				$tmpQuery = "UPDATE games SET gameMessage = 'inviteDeclined', messageFrom = '".$_POST['messageFrom']."' WHERE gameID = ".$_POST['gameID'];
				mysql_query($tmpQuery);
				

			}
			
			break;
			}


    /* if this page is accessed directly (ie: without going through login), */
	/* player is logged off by default */
	if (!isset($_SESSION['playerID']))
		$_SESSION['playerID'] = -1;
		
	/* check if loading game */
	if (isset($_POST['gameID']) && $_POST['response'] != 'declined')
		$_SESSION['gameID'] = $_POST['gameID'];

	/* debug flag */
	define ("DEBUG", 0);

	/* connect to database */
	require_once( 'connectdb.php');

    require "EPDutils.php";
	
	/* load game */
	$isInCheck = ($_POST['isInCheck'] == 'true');
	$isCheckMate = false;
	$isPromoting = false;
	$isUndoing = false;

	loadHistory();
	loadGame();

    $p = mysql_query("SELECT * from games WHERE gameID='".$_SESSION['gameID']."'");
    if (mysql_num_rows($p) ==0){
	   //The game was deleted
       echo "<script>window.location='mainmenu.php'</script>";	
	   exit;
    }
    $row = mysql_fetch_array($p);
    $white = $row['whitePlayer'];
    $black = $row['blackPlayer'];
    $timeLimit = $row['timelimit'];
    $flagFall = $row['flagFall'];
    $oficial = $row['oficial'];
	$teamMatch = $row['teamMatch'];

	if ($row['whitePlayer'] == $_SESSION['playerID'])
	{
		$MyRating = getRating($row['whitePlayer']);
		$OpponentRating = getRating($row['blackPlayer']);
		$MyPV = $row['PVWhite'];
		$OpponentPV = $row['PVBlack'];
		
	}
	else
	{
		$MyRating = getRating($row['blackPlayer']);
		$OpponentRating = getRating($row['whitePlayer']);
		$MyPV = $row['PVBlack'];
		$OpponentPV = $row['PVWhite'];

	}

	processMessages();

    /* Verify permission */
    if (!isBoardDisabled()){
        if (($white != $_SESSION['playerID']) && ($black != $_SESSION['playerID']))
            die($MSG_LANG["youdonthavepermission"]);
    }

        if (($numMoves == -1) || ($numMoves % 2 == 1)){
			$mycolor2 = "white";
		}
		else{
			$mycolor2 = "black";
		}

		/* find out if it's the current player's turn */
		if (( (($numMoves == -1) || (($numMoves % 2) == 1)) && ($playersColor == "white"))
			|| ((($numMoves % 2) == 0) && ($playersColor == "black")))
			$isPlayersTurn = true;
		else
			$isPlayersTurn = false;

		if ($white == $_SESSION['playerID'])
			$opponent = $black;
		else $opponent = $white;


		if (!isBoardDisabled() && !$isCheckMate && $timeLimit > 0)
			if (tempoEsgotado($mycolor2)){
			    saveRanking($_SESSION['gameID'],"resign",$mycolor2,1);
			    updateTimestamp();
			    
			    // Update the opponent time
				if ($mycolor2 == "white")
					mysql_query("UPDATE games set timeWhite=$timeLimit*60 WHERE gameID=".$_SESSION['gameID']);
			    else
					mysql_query("UPDATE games set timeBlack=$timeLimit*60 WHERE gameID=".$_SESSION['gameID']);
					
				echo "<script>
				alert('".$MSG_LANG["theflaghasfallen"]." $mycolor2 $MSG_LANG[lost]');
				window.location='chess.php';
				</script>\n";
				exit;
			}

	if ($isUndoing)
	{
		doUndo();
		saveGame();
	}
    
	else if ($CFG_ENABLE_CHAT && isset($_POST['chat_msg']) && $chatdata){
        if ($_POST[chat_msg] != ""){
	    if (!get_magic_quotes_gpc())
		$_POST[chat_msg] = addslashes($_POST[chat_msg]);
		$_POST[chat_msg] = htmlspecialchars($_POST[chat_msg]); 
		$_POST[chat_msg] = wordwrap($_POST[chat_msg], 35, " ", 1);
        mysql_query("insert into chat (fromID,msg,gameID) VALUES ('".$_SESSION[playerID]."','".$_POST[chat_msg]."','".$_SESSION[gameID]."')");
	}
    }
	else if ($_POST[note_msg] != ""){ 
       if (!get_magic_quotes_gpc()) 
      $_POST[note_msg] = addslashes($_POST[note_msg]); 
            $p2 = mysql_query("SELECT count(*) from history WHERE gameID='".$_SESSION[gameID]."'"); 
        $row2 = mysql_fetch_array($p2); 
        $rounds = floor(($row2[0]+1)/2); 
        $color = "b"; 
        if ($rounds == (($row2[0]+1)/2)) $color = "w"; 
        if ($rounds != 0) $rounds = $rounds.$color; 
            $notemsg = $_POST[note_msg]; 
            $temparray = array($notemsg, $rounds); 
            $_POST[note_msg] = implode(" - MOVE #", $temparray); 
        mysql_query("insert into notes (fromID,note,gameID) VALUES ('".$_SESSION[playerID]."','".$_POST[note_msg]."','".$_SESSION[gameID]."')"); 
   } 

	elseif (($_POST['promotion'] != "") && ($_POST['toRow'] != "") && ($_POST['toCol'] != ""))
	{
		savePromotion();
		$board[$_POST['toRow']][$_POST['toCol']] = $_POST['promotion'] | ($board[$_POST['toRow']][$_POST['toCol']] & BLACK);
		saveGame();
	}
	elseif (($_POST['fromRow'] != "") && ($_POST['fromCol'] != "") && ($_POST['toRow'] != "") && ($_POST['toCol'] != ""))
	{
		/* ensure it's the current player moving				 */
		/* NOTE: if not, this will currently ignore the command...               */
		/*       perhaps the status should be instead?                           */
		/*       (Could be confusing to player if they double-click or something */
		$tmpIsValid = true;
		
		if (($numMoves == -1) || ($numMoves % 2 == 1))
		{
			/* White's move... ensure that piece being moved is white */
			if ((($board[$_POST['fromRow']][$_POST['fromCol']] & BLACK) != 0) || ($board[$_POST['fromRow']][$_POST['fromCol']] == 0))
				/* invalid move */
				$tmpIsValid = false;
		}
		else
		{
			/* Black's move... ensure that piece being moved is black */
			if ((($board[$_POST['fromRow']][$_POST['fromCol']] & BLACK) != BLACK) || ($board[$_POST['fromRow']][$_POST['fromCol']] == 0))
				/* invalid move */
				$tmpIsValid = false;
		}

		if ($tmpIsValid)
		{
			saveHistory();
			doMove();
			saveGame();
		}
	        $g1 = mysql_query("SELECT * FROM games WHERE gameID='".$_SESSION['gameID']."'");
        $g = mysql_fetch_array($g1);

        $p1 = mysql_query("SELECT p.*, t.moves_done, t.id AS trainings_id FROM training_games t
                	  				LEFT JOIN game_positions p
                                    ON p.id = t.id
                					WHERE t.gameID = '".$_SESSION['gameID']."'
                                    AND t.playerID = '".$_SESSION['playerID']."' ");
        echo mysql_error();
        $p = mysql_fetch_array($p1);

        $gameID = $_SESSION['gameID'];

        if (($g['whitePlayer'] == 1) || ($g['blackPlayer'] == 1))
        {
            if ($p['moves'] == 3)
            {
                $str = $_SESSION['gameID'];

                if (($_POST['fromRow'] != $p['fromRow']) ||
                   ($_POST['fromCol'] != $p['fromCol']) ||
                   ($_POST['toRow'] != $p['toRow']) ||
                   ($_POST['toCol'] != $p['toCol']))
                {
                	$str .= '|0';
                }

                else
                {
					$str .= '|1';
                }

                $str = base64_encode($str);

           	 	mysql_query("DELETE FROM games WHERE gameID = '$gameID'");
		  		mysql_query("DELETE FROM pieces WHERE gameID = '$gameID'");
		   		mysql_query("DELETE FROM history WHERE gameID = '$gameID'");

           	 	header("Location: matt.php?action=result&code=".$str);

            }

            elseif ($p['moves'] == 1)
            {
            	$player = ($g['whitePlayer'] == 1) ? 'black' : 'white';

            	$str = $_SESSION['gameID'] . '|';

				if (($g['gameMessage'] == 'checkMate') && ($g['messageFrom'] == $player))
				{
					$str .= '1';
		   		}
		   		else
		  		{
		  			$str .= '0';
		 		}

           	 	$str = base64_encode($str);

           	 	mysql_query("DELETE FROM games WHERE gameID = '$gameID'");
		  		mysql_query("DELETE FROM pieces WHERE gameID = '$gameID'");
		   		mysql_query("DELETE FROM history WHERE gameID = '$gameID'");

           	 	header("Location: matt.php?action=result&code=".$str);
            }

            else
            {
                /*echo "<br>";
                echo $_POST['fromRow']."&nbsp;".$p['fromRow']."<br>";
                echo $_POST['fromCol']."&nbsp;".$p['fromCol']."<br>";
                echo $_POST['toRow']."&nbsp;".$p['toRow']."<br>";
                echo $_POST['toCol']."&nbsp;".$p['toCol']."<br>";
                //exit;*/

                $moves = $p['moves_done'];

                if ($moves == 0)
                {

            		if (($_POST['fromRow'] != $p['fromRow']) ||
                		($_POST['fromCol'] != $p['fromCol']) ||
                    	($_POST['toRow'] != $p['toRow']) ||
                    	($_POST['toCol'] != $p['toCol']))
                	{
                 		$str = $_SESSION['gameID'] . '|0';
                    	$str = base64_encode($str);

           	 			mysql_query("DELETE FROM games WHERE gameID = '$gameID'");
		  				mysql_query("DELETE FROM pieces WHERE gameID = '$gameID'");
		   				mysql_query("DELETE FROM history WHERE gameID = '$gameID'");

           	 			header("Location: matt.php?action=result&code=".$str);
                	}

                	else
                	{
                   		// Move Valid :D
                    	mysql_query("DELETE FROM pieces WHERE gameID = '$gameID'");
		   				mysql_query("DELETE FROM history WHERE gameID = '$gameID'");
                        mysql_query("DELETE FROM games WHERE gameID = '$gameID'");

                    	// Get the new EPD Position

                        $m1 = mysql_query("SELECT epd, epd2, zug FROM game_positions_results WHERE pos_id = '".$p['id']."' ORDER BY rand(".rand().")");
                        echo mysql_error();
                        $m = mysql_fetch_array($m1);

                        $whitePlayer = $g['whitePlayer'];
						$blackPlayer = $g['blackPlayer'];
						$EPD = $m['epd'];
                        $EPD2 = (!empty($m['epd2'])) ? $m['epd2'] : $m['epd'];

                        $tmpQuery = "INSERT INTO games(whitePlayer, blackPlayer, dateCreated, lastMove, gameMessage, messageFrom, oficial)
    							 	VALUES ('".$whitePlayer."','".$blackPlayer."', now(), now(), '', 'white', 0)";
    					mysql_query($tmpQuery);
    					echo mysql_error();
    					// Get the ID of the game we just inserted..
    					$insertedGameId = mysql_insert_id();

    					mysql_query("UPDATE training_games SET moves_done = 1, gameID = '$insertedGameId '
                        			 WHERE id = '".$p['trainings_id']."'");
                        echo mysql_error();

						$sq = EPD2Board($EPD);

    					insertPieces($sq, $insertedGameId);

						$tomove = EPDCurColor($EPD);
						if($tomove == 'black')
						{
   							$tmpQuery = "INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck)
   													VALUES   (Now(), '".$insertedGameId ."', 'king', '$tomove', '0',     '0',  '0',   '0',     null,       null, '0')";
   					 		mysql_query($tmpQuery);
   						}

                        //header("Location: chess.php?gameID=".$insertedGameId);
                        ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<style type="text/css">
	<!--
	body {
	    font-family: verdana, arial, helvetica, sans-serif;
        font-size: small;
        color: #000;
		background-color: #fff;
	}
	-->
	</style>
	<title>WebChess :: Insert a new game</title>
	<script language="JavaScript" type="text/javascript" src="javascript/chessutils.js"></script>
	<script language="JavaScript" type="text/javascript" src="javascript/EPDutils.js"></script>
	<script type="text/javascript"><!--
		//window.onload = function () {
		//	getObject('displaypos').onclick = function(){EPDToBoard(getObject('EPD').value);};
	   //	}

       function weiter() {

       	newEPD = "<?=$EPD?>";
        EPDToBoard(newEPD);
       }

       setTimeout("weiter()","5000");
	//-->
    </script>
<?PHP
$url = 'chess.php?gameID='.$insertedGameId;
echo '<meta http-equiv="Refresh" content="10;url='.$url.'">'; ?>
</head>

<body>
<div align="center">
<h3>Aktuelles Trainingsspiel<br>..:: MATT IN Zwei Zügen ::..</h3>

<b>Dies war die richtige Eröffnung</b>

<div id=EL_1>
<br><br>Der Schachtrainer macht nun den Zug <b><?=$m['zug']?></b>
</div>

<br><br>

<?PHP displayBoard(EPD2Board($EPD2));  ?>

<br><br>

<a href="<?=$url?>"><b>Weiter zum nächsten Zug >></b></a>

</body>
</html>

                        <?PHP

                        exit;

                    } // move valid
                } // moves == 0

                elseif ($moves == 1)
                {

                	$player = ($g['whitePlayer'] == 1) ? 'black' : 'white';

            		$str = $_SESSION['gameID'] . '|';

					if (($g['gameMessage'] == 'checkMate') && ($g['messageFrom'] == $player))
					{
						$str .= '1';
		   			}
		   			else
		  			{
		  				$str .= '0';
		 			}

           	 		$str = base64_encode($str);

           	 		mysql_query("DELETE FROM games WHERE gameID = '$gameID'");
		  			mysql_query("DELETE FROM pieces WHERE gameID = '$gameID'");
		   			mysql_query("DELETE FROM history WHERE gameID = '$gameID'");

           	 		header("Location: matt.php?action=result&code=".$str);

                } // moves == 1

            } // 1 / 2 moves game
        } // training?
	}

?>

<html>
<head>
<!-- gameID: <?=$_SESSION[gameID]?> -->

<?

		/* find out if it's the current player's turn */
		if (( (($numMoves == -1) || (($numMoves % 2) == 1)) && ($playersColor == "white"))
			|| ((($numMoves % 2) == 0) && ($playersColor == "black")))
			$isPlayersTurn = true;
		else
			$isPlayersTurn = false;

		if ($white == $_SESSION['playerID'])
			$opponent = $black;
		else $opponent = $white;
		
	if ($_SESSION['isSharedPC'])
		echo("<title>ChessManiac</title>\n");
	else if ($isPlayersTurn)
		echo("<title>Chess - ".$MSG_LANG["yourturn"]."</title>\n");
	else
		echo("<title>Chess - ".$MSG_LANG["opponentturn"]."</title>\n");
	
	echo("<meta HTTP-EQUIV='Pragma' CONTENT='no-cache'>\n");
	echo '<meta http-equiv="cache-control" content="no-cache">';
	echo '<meta http-equiv="expires" content="-1">';
	
	/* if it's not the player's turn, enable auto-refresh */
	/*
    if (!$isPlayersTurn && !isBoardDisabled() && !$_SESSION['isSharedPC'])
	{
		echo ("<META HTTP-EQUIV=Refresh CONTENT='");

		if ($_SESSION['pref_autoreload'] >= $CFG_MINAUTORELOAD)
			echo ($_SESSION['pref_autoreload']);
		else
			echo ($CFG_MINAUTORELOAD);

		echo ("; URL=chess.php?autoreload=yes'>\n");
	}
    */	
?>
<meta name="Keywords" content="chess,ajedrez,échecs,echecs,scacchi,schach,schachmatt, schachturnier, schachspiel, check,check mate,jaque,jaque mate,queenalice,queen alice,queen,alice,play,game,games,turn based,correspondence,correspondence chess,online chess,play chess online">
<script type="text/javascript">
<? writeJSboard(); ?>
<? writeJShistory(); ?>

if (DEBUG)
	alert("Game initilization complete!");

stopTimer = 0;
function refreshwindow(){
    if (stopTimer == 0)
        window.location='chess.php';
}
		function loadnextGame(gameID)
		{
			//if (document.allhisgames.rdoShare[0].checked)
			//	document.allhisgames.action = "opponentspassword.php";

			document.allhisgames.gameID.value = gameID;
			document.allhisgames.submit();
		}
		function sendResponse(responseType, messageFrom, gameID)
		{
			document.responseToInvite.response.value = responseType;
			document.responseToInvite.messageFrom.value = messageFrom;
			document.responseToInvite.gameID.value = gameID;
			document.responseToInvite.submit();
		}
		
		
</script>

<script type="text/javascript" src="javascript/chessutils<?=$JAVASCRIPT_EXT?>">
 /* these are utility functions used by other functions */
</script>

<script type="text/javascript" src="javascript/commands<?=$JAVASCRIPT_EXT?>">
// these functions interact with the server
</script>

<script type="text/javascript" src="javascript/validation<?=$JAVASCRIPT_EXT?>">
// these functions are used to test the validity of moves
</script>

<script type="text/javascript" src="javascript/isCheckMate<?=$JAVASCRIPT_EXT?>">
// these functions are used to test the validity of moves
</script>

<script type="text/javascript" src="javascript/squareclicked<?=$JAVASCRIPT_EXT?>">
// this is the main function that interacts with the user everytime they click on a square
</script>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/board.css" type="text/css">

</head>

<BODY bgcolor=white text=black marginheight=10 marginwidth=10 topmargin=10 leftmargin=10>


<table border="0" width=100% align=left>
   <?PHP

if ($row['tournament'] != 0 AND $row['thematic'] == 0)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>
		<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td>&nbsp;</td></tr>
<?
}


elseif ($row['tournament'] != 0 AND $row['thematic'] == 1)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[alekhine_2]?></font></td></tr></table></td><td></td></tr>

<?
}


elseif ($row['tournament'] != 0 AND $row['thematic'] == 2)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[birds_2]?></font></td></tr></table></td><td></td></tr>

<?
}


elseif ($row['tournament'] != 0 AND $row['thematic'] == 3)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[budapest_2]?></font></td></tr></table></td><td></td></tr>


<?
}


elseif ($row['tournament'] != 0 AND $row['thematic'] == 4)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[catalan_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 5)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[carokann_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 6)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[cochranegambit_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 7)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[dutchdefense_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 8)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[leningraddutch_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 9)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[fourknights_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 10)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[frenchdefense_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 11)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[frenchadvance_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 12)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[frenchclassical_2]?></font></td></tr></table></td><td></td></tr>



<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 13)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[frenchrubinstein_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 14)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[frenchwinawer_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 15)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[frenchtarrasch_2]?></font></td></tr></table></td><td></td></tr>



<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 16)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[grob_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 17)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[grunfeld_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 18)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[kingsgambit_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 19)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[kingsindian_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 20)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>



<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[italiangame_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 21)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[larsens_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 22)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[modernbenoni_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 23)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[muziogambit_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 24)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[nimzoindian_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 25)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[petroff_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 26)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[philidor_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 27)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[queensgambit1_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 28)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[queensgambit2_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 29)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[queensindian_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 30)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[ruylopez_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 31)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[scandinavian_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 32)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[scotch_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 33)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[sicilian_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 34)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[sicilianalapin_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 35)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[sicilianclosed_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 36)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[siciliansveshnikov_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 37)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

	<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[siciliansimagin_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 38)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[sicilianpaulsen_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 39)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>
		<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[sicilianrichterrauzer_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 40)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[siciliandragon_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 41)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[sicilianscheveningen_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 42)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[siciliansozin_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 43)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[siciliannajdorf_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 44)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[slav_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 45)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[sokolsky1_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 46)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[tarrasch_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 47)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[vienna_2]?></font></td></tr></table></td><td></td></tr>


<?
}



elseif($row['blitz'] == 1)
{

echo "<td align=center><table width='40%' border='0'><tr><td align='center'><input type='button' name='blitz' value='$MSG_LANG[livegame]' onClick=\"window.open('invite_blitz.php', '_self')\"></td></tr></table>";


}


elseif($row['thematic'] == 1){

                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[alekhine_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 2){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[birds_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 3){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[budapest_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 4){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[catalan_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 5){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[carokann_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 6){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[cochranegambit_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 7){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[dutchdefense_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 8){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[leningraddutch_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 9){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[fourknights_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 10){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[frenchdefense_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 11){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[frenchadvance_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 12){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[frenchclassical_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 13){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[frenchrubinstein_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 14){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[frenchwinawer_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 15){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[frenchtarrasch_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 16){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[grob_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 17){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[grunfeld_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 18){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[kingsgambit_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 19){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[kingsindian_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 20){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[italiangame_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 21){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[larsens_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 22){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[modernbenoni_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 23){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[muziogambit_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 24){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[nimzoindian_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 25){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[petroff_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 26){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[philidor_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 27){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[queensgambit1_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 28){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[queensgambit2_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 29){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[queensindian_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 30){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[ruylopez_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 31){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[scandinavian_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 32){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[scotch_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 33){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[sicilian_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 34){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[sicilianalapin_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 35){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[sicilianclosed_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 36){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[siciliansveshnikov_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 37){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[siciliansimagin_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 38){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[sicilianpaulsen_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 39){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[sicilianrichterrauzer_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 40){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[siciliandragon_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 41){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[sicilianscheveningen_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 42){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[siciliansozin_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 43){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[siciliannajdorf_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 44){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[slav_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 45){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[sokolsky1_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 46){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[tarrasch_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 47){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[vienna_2]</font></td></tr></table>\n";
                                }elseif ($row[thematic] == 48){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[volgagambit_2]</font></td></tr></table>\n";



                                }

else
{
echo "<td align=center><table width='22%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic3]</font></td></tr></table>\n";



}
   echo "</td></tr>";

?>


<tr>
	<td align=center>
	<?
        $p = mysql_query("SELECT firstName,playerID,engine FROM players WHERE playerID='$white'");
        $row = mysql_fetch_array($p);
        $p = mysql_query("SELECT firstName,playerID,engine FROM players WHERE playerID='$black'");
        $row2 = mysql_fetch_array($p);
		$row[0] = $row[0]." (".$MSG_LANG["white"].")";
		$row2[0] = $row2[0]." (".$MSG_LANG["black"].")";
        echo "<input type='button' name='btnWhiteUser' value='$row[0]' onClick=\"window.open('stats_user.php?cod=$row[1]&voltar=chess.php', '_self')\">";
        echo " <B>X</B> ";
        echo "<input type='button' name='btnBlackUser' value='$row2[0]' onClick=\"window.open('stats_user.php?cod=$row2[1]&voltar=chess.php', '_self')\">";
        #echo "<font face=verdana size=2><a href='stats_user.php?cod=$row[1]&voltar=chess.php'>$row[0]</a> <B>x</B> <a href='stats_user.php?cod=$row2[1]&voltar=chess.php'>$row2[0]</a></font>";
        if ($row["engine"] >= 1 || $row2["engine"] >= 1)
            $isEngine=true;

        $p = mysql_query("SELECT value FROM preferences WHERE preference='language' AND playerID=$opponent");
        $r = mysql_fetch_array($p);
	if ($_SESSION['pref_language'] != $r[0])
		$opponent_language = strtoupper($r[0]);
	else $opponent_language = "";
	?>
	</td>
	<td align=center>&nbsp;</td>
</tr>
<tr>
	<td colspan=2>&nbsp;</td>
</tr>
<tr valign="top" align="center">
<td>
	<form name="gamedata" method="post" action="chess.php">
    <input type=hidden name="gameID" value="<?=$_SESSION["gameID"]?>">
	<?

		/* Verify if the promotion process was completed */
		if ($playersColor == "white"){
			$promotionRow = 7;
			$otherRow = 0;
		}
		else{
			$promotionRow = 0;
			$otherRow = 7;
		}

		for ($i = 0; $i < 8; $i++)
			if (($board[$promotionRow][$i] & COLOR_MASK) == PAWN){
				$isPromoting = true;
				$p = mysql_query("SELECT * FROM history where toCol='$i' AND toRow='$promotionRow' AND curPiece='pawn' and curColor='$playersColor' and gameID='$_SESSION[gameID]' ORDER BY timeOfMove DESC limit 1");
				$row = mysql_fetch_array($p);
				$_POST['fromRow'] = $row[fromRow];
				$_POST['fromCol'] = $row[fromCol];
				$_POST['toRow'] = $row[toRow];
				$_POST['toCol'] = $row[toCol];
			}

		for ($i = 0; $i < 8; $i++)
			if (($board[$otherRow][$i] & COLOR_MASK) == PAWN){
				writeWaitPromotion();		
				$isPromoting = 1;
				$otherTurn = 1;
			}

		if ($isPromoting && !$otherTurn)
			writePromotion();
	?>

	<?
		if ($isUndoRequested)
			writeUndoRequest();
	?>

	<?
		if ($isDrawRequested)
			writeDrawRequest();

	?>

	<? drawboard(); ?>

	<!-- table border="0">
	<tr><td -->
	<BR>
	<nobr>
	<input type="button" name="btnReload" value="<?=$MSG_LANG["refresh2"]?>" onClick="window.open('chess.php', '_self')">
	<? if ($CFG_ENABLE_UNDO){ ?>
    <input type="button" name="btnUndo" value="<?=$MSG_LANG["undomove"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); else echo ("onClick='undo(\"".$MSG_LANG["undowarning"]."\")'"); ?>>
	<?}?>
	<? if (($numMoves<3) && ($team ==0 && $teamMatch ==0) && ($tournament ==0)){?>
	<!-- <input type="button" name="btnAbort" value="Abort Game" onClick="window.location='delgame.php?gameId=<?=$gameID?>'"> -->
	<?}?>

	<input type="button" name="btnDraw" value="<?=$MSG_LANG["askdraw"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); elseif ($isPlayersTurn == true && $_SESSION['pref_language'] == "english") echo("onClick='drawrequestwithoutmove(\"".$MSG_LANG["drawrequestwithoutmovingfirst"]."\")'"); elseif ($_SESSION['pref_language'] == "english") echo ("onClick='englishdraw(\"".$MSG_LANG["Englishroundwarning"]."\",$CFG_MIN_ROUNDS)'"); else echo ("onClick='draw($CFG_MIN_ROUNDS,\"".$MSG_LANG["roundwarning"]."\")'");?>>
	<input type="button" name="btnResign" value="<?=$MSG_LANG["resign"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); elseif ($_SESSION['pref_language'] == "english") echo ("onClick='englishresigngame(\"".$MSG_LANG["Englishroundwarning"]."\",$CFG_MIN_ROUNDS)'"); else echo ("onClick='resigngame($CFG_MIN_ROUNDS,\"".$MSG_LANG["roundwarning"]."\")'"); ?>>
	<?if (isset($_POST['statsUser'])){ ?>
        <input type="button" name="btnMainMenu" value="<?=$MSG_LANG["back"]?>" onClick="window.open('stats_user.php?cod=<?=$_POST['statsUser']?>', '_self')">
    <?}else{ ?>
        <input type="button" name="btnMainMenu" value="<?=$MSG_LANG["main"]?>" onClick="window.open('mainmenu.php', '_self')">
        <br><br>

        <input type="button" name="btnMainMenu2" value="Export PGN" onClick="window.open('exportpgn.php','_self')">
    <?}?>	
    <!-- <input type="button" name="btnLogout" value="Sair" onClick="logout()">
    -->
	<input type="hidden" name="ToDo" value="Logout">	<!-- NOTE: this field is only used to Logout -->
	</nobr>
	<!-- /td></tr>
	</table -->

	<input type="hidden" name="requestUndo" value="no">
	<input type="hidden" name="requestDraw" value="no">
	<input type="hidden" name="resign" value="no">
	<input type="hidden" name="fromRow" value="<?=$_POST['fromRow']?>">
	<input type="hidden" name="fromCol" value="<?=$_POST['fromCol']?>">
	<input type="hidden" name="toRow" value="<?=$_POST['toRow']?>">
	<input type="hidden" name="toCol" value="<?=$_POST['toCol']?>">
	<input type="hidden" name="isInCheck" value="false">
	<input type="hidden" name="isCheckMate" value="false">

	<? if ($isPromoting && $isEngine){ 
            echo "<script>promotepawn();</script>";
	} ?>

	</form>
    <? global $t_banned_users;
if(in_array($_SESSION['playerID'], $t_banned_users))
 {
    $CFG_ENABLE_CHAT=FALSE;	
	echo "<b>Privileges have been revoked due chat abuse.  Please contact webmaster@Webmaster</b>";
 }
	if ($CFG_ENABLE_CHAT && !isBoardDisabled()){ ?>
    <script type="text/javascript"><!--
google_ad_client = "pub-9606600691278870";
google_ad_width = 234;
google_ad_height = 60;
google_ad_format = "234x60_as";
google_ad_channel ="2162144154";
google_color_border = "CCCCCC";
google_color_bg = "FFFFFF";
google_color_link = "000000";
google_color_url = "666666";
google_color_text = "333333";
//--></script>
    <script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script>
<form method=POST action="chess.php" name=chess>
<?print($f->get_formtoken());?>    
    
      <div align="center">
          <input type=hidden name="gameID" value="<?=$_SESSION["gameID"]?>">
   	        <table width=420 border='0' cellspacing=1  bgcolor=black cellpading=1>
    	      <tr bgcolor="beige">
    	        <th>PRIVATE CHAT
		      <? if ($opponent_language != ""){
		$language = $MSG_LANG[strtolower($opponent_language)];
		echo " (".$MSG_LANG['opponentlanguage'].": ".$language.")";
		}
		?>
		      <?
	//$p = mysql_query("SELECT * FROM players WHERE lastUpdate > '".(time()-300)."'");
        //$row = mysql_fetch_array($p);
        //$online = mysql_num_rows($p);
?>
    	      </th>
    	      </tr>
    	      <tr><td align=left bgcolor=white><?= writeChat($_SESSION['gameID'])?></td></tr>
    	      <tr><td align=center bgcolor=white><input type=text name=chat_msg size=50 onClick="stopTimer=1"><input type=submit value="<?=$MSG_LANG["write"]?>"></td></tr>
    	      <tr>
    	        <td align=center bgcolor=white><strong><a href="chatrules.php" target="_blank">Please
    	              Read the Chat Rules	Before Posting Here! </a><br>
      Violation of the rules will result in loss of chat privileges.</strong> </td>
  	        </tr>
    	      
            </table>
      </div>
</form>
  <table width="420" border="0" cellspacing=1 cellpadding=1 bgcolor=black>
    <th bgcolor="beige">Discuss This Game.
    </th>
    </tr>
    <tr>
    <td bgcolor="white">



    <?PHP

    require "forum_functions.php";


    $cnum = getcount("comments", "WHERE gameID = '".$_SESSION['gameID']."'");


if ($cnum > 0) {

?>
Go to this game <a href="analyze.php?game=<?=$_SESSION['gameID']?>"><?=$cnum?></a> Comments!<br>
<?PHP

}




    $num = getcount("forum_topics", "WHERE replyto = 0 AND gameid = '".$_SESSION['gameID']."'");

    if ($num == 0) {

    ?>

     No discussion available.<br>
     <br>
     <a href="forum.php?action=newtopic&id=<?=$spiele_forum?>&game=<?=$_SESSION['gameID']?>">Click to start a discussion about this game</a>


    <?PHP

    } else {

$g1 = mysql_query("SELECT topic_id FROM forum_topics WHERE replyto = 0 AND gameid = '".$_SESSION['gameID']."'");
$g = mysql_fetch_array($g1);

$t1 = mysql_query("SELECT t.*, p.firstName, p.playerID, p.lastUpdate from forum_topics t
					LEFT JOIN players p ON p.playerID = t.userid
                    WHERE t.topic_id = '".$g['topic_id']."'
                    OR t.replyto = '".$g['topic_id']."'
                    ORDER BY time DESC
                    LIMIT ".$replies_perpage);
//echo mysql_error();
while ($t = mysql_fetch_array($t1)) {

$title = db_output($t['title']);
$title = strip_tags($title);
$text = db_output($t['text'], true);
$text = strip_tags($text, "<br>");
$text = bbcode($text);
$text = forum_smilies($text);

$date = date("d.m.y, H:i", $t['time']);



?>

<table>
<tr>
<tr>
<td width="100" valign="top">

<b><?=$t['firstName']?></b>,<br>
<?=$date?>

</td>

<td valign="top"><div width="100%" align="left"><?=$text?></div></td>
</tr>
</table>
<hr>

<?PHP

} // while

?>
<input type="button" class="BOTOES" value="Reply" onClick="location.href='forum.php?action=newtopic&replyto=<?=$g['topic_id']?>';">
&nbsp;&nbsp;
<input type="button" class="BOTOES" value="View Topic" onClick="location.href='forum.php?action=viewtopic&id=<?=$g['topic_id']?>';">
&nbsp;&nbsp;
<input type="button" class="BOTOES" value="Chess Forums" onClick="location.href='forum.php?action=viewforum&id=<?=$spiele_forum?>';">
<?PHP

}

?>
     </td>
    </tr>
  </table>
   <table width="420" border="0" cellspacing="1" bgcolor="#000000">
  <th bgcolor="beige"><?=$MSG_LANG["hints"]?></th>
  <tr>
    <td bgcolor="#FFFFFF">Use 
      <input name="button4" type="button" class="BOTOES" onClick="window.location='configure.php'" value="<?=$MSG_LANG["configurations"]?>">      
      to change <?=$MSG_LANG["currentpreferences"]?><br>
      1.  <?=$MSG_LANG["boardsize"]?>
      <br>
      2. <?=$MSG_LANG["theme"]?><br>
      3. <?=$MSG_LANG["refresh"]?><br>
      4. <?=$MSG_LANG["e-mailnotification"]?><br>
      5. <?=$MSG_LANG["language"]?><br>
      <br>
      <strong>To move the pieces:</strong> Click on the piece you want to move
      and it will be highlighted. Then click the square you want to move it to.
      Confirm your
      move and the page will update.<br>      <br>
      To castle click on your King and move to desired square. The Rook will move
      automatically.</td>
  </tr>
</table>	
   <br>
  <br>  
  <?}?>
</td>

    <td>
	
   <input name="button2" type="button" class="BOTOES" onClick='document.logout.submit()' value="<?=$MSG_LANG["logoff"]?>"><input name="button3" type="button" onClick="window.open('analyze.php?whocolor=<?=$playersColor?>&game=<?=$_SESSION['gameID']?>', '_blank','toolbar=no,status=no,menubar=no,scrollbars=yes,width=850,height=600')" value="<?=$MSG_LANG["analyze"]?>"><input name="button5" border-width= "5"  type="button" class="BOTOES" onClick="window.location='MyGames.php'" value="<?=$MSG_LANG["mygames"]?>">
<br>
<?
$m = ("SELECT ack,toID FROM communication WHERE (toID = ".$_SESSION['playerID']." OR toID = NULL) AND ack = 0 and listed <> 1");

$numresults=mysql_query($m);
$numrows=mysql_num_rows($numresults);

if ($numrows != 0){

echo '<table width="300" border="0" cellspacing="1" bgcolor="#000000">
  <th bgcolor="beige">Messages</th>
  <tr>
    <td bgcolor="#FFFFFF" align="center">';
echo ("<input type=\"image\" src=\"images/icons/emailenvelope.jpg\" onClick=\"window.location='messages.php'\"><br>".$MSG_LANG["sendpmtext3"]."</td>
  </tr>
</table>");
}
?>
<?
	$tmpQuery = "SELECT * FROM games WHERE gameMessage = 'playerInvited' AND ((whitePlayer = ".$_SESSION['playerID']." AND messageFrom = 'black') OR (blackPlayer = ".$_SESSION['playerID']." AND messageFrom = 'white')) ORDER BY dateCreated";
	$tmpGames = mysql_query($tmpQuery);
	if (mysql_num_rows($tmpGames) > 0){

		if (mysql_num_rows($tmpGames) > 0 && $_SESSION['pref_sound'] == 'on'){
			echo '<form name="responseToInvite" action="chess.php" method="post">
	    	<table border="0" width="300" bgcolor=black cellspacing=1 cellpading=1>
	    	<tr>
		  	<th colspan=7 bgcolor=beige>'.$MSG_LANG["therearechallenges-sound"].'</th>
	    	</tr>
	    	<tr>
		  <th bgcolor=white>'.$MSG_LANG["challenger"].'</th>
		  	<th bgcolor=white>'.$MSG_LANG["yourcolor"].'</th>
		  	<th bgcolor=white>'.$MSG_LANG["type"].'</th>
		  	<th bgcolor=white>'.$MSG_LANG["challengerate"].'</th>
		  	<th bgcolor=white>'.$MSG_LANG["punctuation"].'</th>
		  	<th bgcolor=white>'.$MSG_LANG["timeforeach"].'</th>
			<th bgcolor=white>'.$MSG_LANG["thematic"].'</th>
	    	</tr>';
	    }
		elseif ($_SESSION["pref_autoaccept"] == "0"){
			echo '<form name="responseToInvite" action="chess.php" method="post">
	    	<table border="0" width="300" bgcolor=black cellspacing=1 cellpading=1>
	    	<tr>
		  	<th colspan=7 bgcolor=beige>'.$MSG_LANG["therearechallenges"].'</th>
	    	</tr>
	    	<tr>
		  	<th bgcolor=white>'.$MSG_LANG["challenger"].'</th>
		  	<th bgcolor=white>'.$MSG_LANG["yourcolor"].'</th>
		  	<th bgcolor=white>'.$MSG_LANG["type"].'</th>
		  	<th bgcolor=white>'.$MSG_LANG["challengerate"].'</th>
		  	<th bgcolor=white>'.$MSG_LANG["punctuation"].'</th>
		  	<th bgcolor=white>'.$MSG_LANG["timeforeach"].'</th>
			<th bgcolor=white>'.$MSG_LANG["thematic"].'</th>
	    	</tr>';
	    }

		while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
		{
			if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
				$tmpFrom = "white";
			else
				$tmpFrom = "black";

			if ($_SESSION["pref_autoaccept"] == "-1"){
				/*Autoreject*/

				$tmpQuery = "UPDATE games SET gameMessage = 'inviteDeclined', messageFrom = '".$tmpFrom."' WHERE gameID = ".$tmpGame['gameID'];
				mysql_query($tmpQuery);

			}
			else if ($_SESSION["pref_autoaccept"] == "1"){
				/*Autoaccept*/

				/* update game data */
				$tmpQuery = "UPDATE games SET gameMessage = '', messageFrom = '' WHERE gameID = ".$tmpGame['gameID'];
				mysql_query($tmpQuery);

				/* setup new board */
				$_SESSION['gameID'] = $tmpGame['gameID'];
				createNewGame($tmpGame['gameID']);
				saveGame();

			}
			else{

				/* Opponent */
				echo("<tr><td bgcolor=white>");
				/* get opponent's nick */
				if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
					$tmpOpponent = mysql_query("SELECT firstName,lastName,playerID FROM players WHERE playerID = ".$tmpGame['blackPlayer']);
				else
					$tmpOpponent = mysql_query("SELECT firstName,lastName,playerID FROM players WHERE playerID = ".$tmpGame['whitePlayer']);
				$row = mysql_fetch_array($tmpOpponent);
				$opponent = $row[0];
				$id = $row[2];
				echo "<a href='stats_user.php?cod=$id'>$opponent</a>";
				/* Response */
				echo ("</td><td bgcolor=white>");
				/* Your Color */
				if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
				{
					echo $MSG_LANG["white"];
					//$tmpFrom = "white";
					$ratingW = $tmpGame['ratingWhite'];
					$ratingL = $tmpGame['ratingBlack'];
				}
				else
				{
					echo $MSG_LANG["black"];
					//$tmpFrom = "black";
					$ratingL = $tmpGame['ratingWhite'];
					$ratingW = $tmpGame['ratingBlack'];
				}

            	if ($tmpGame['oficial'] == "1")
	                $oficial = $MSG_LANG["official"];
    	        else $oficial = $MSG_LANG["notofficial"];

        	    echo "<td bgcolor=white>".$oficial."</td>";

            	if ($tmpGame['oficial'] == "1"){
               		$xpw = getXPW($ratingW,$ratingL,getPV($_SESSION['playerID']));
               		$xpl = getXPL($ratingL,$ratingW,getPV($id));
            	}else{
                	$xpl=0;
                	$xpw=0;
            	}

				$dificuldade = getDifficult($_SESSION['playerID'],$id);

		            	echo "<td bgcolor=white>$dificuldade</td>\n";
				echo "<td bgcolor=white><i>+$xpw / $xpl</i></td>\n";

				if ($tmpGame[timelimit] == 0)
					echo "<td bgcolor=white>$CFG_EXPIREGAME $MSG_LANG[unlimited]</td>\n";
				else if ($tmpGame[timelimit] <60)
					echo "<td bgcolor=white> <strong><font color=red>$MSG_LANG[min] $tmpGame[timelimit] min.</font></strong></td>\n";
				else if($tmpGame[timelimit] < 1440)
					echo "<td bgcolor=white><strong><font color=red>$MSG_LANG[hs] ".($tmpGame[timelimit]/60)." hrs.</font></strong></td>\n";
				else
				echo "<td bgcolor=white>".($tmpGame[timelimit]/24/60)." $MSG_LANG[unlimited]</td>\n";
if ($tmpGame[thematic] == 1){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[alekhine]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 2){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[birds]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 3){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[budapest]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 4){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[catalan]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 5){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[carokann]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 6){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[cochranegambit]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 7){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[dutchdefense]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 8){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[leningraddutch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 9){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[fourknights]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 10){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[frenchdefense]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 11){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[frenchadvance]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 12){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[frenchclassical]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 13){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[frenchrubinstein]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 14){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[frenchwinawer]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 15){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[frenchtarrasch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 16){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[grob]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 17){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[grunfeld]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 18){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[kingsgambit]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 19){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[kingsindian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 20){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[italiangame]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 21){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[larsens]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 22){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[modernbenoni]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 23){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[muziogambit]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 24){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[nimzoindian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 25){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[petroff]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 26){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[philidor]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 27){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[queensgambit1]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 28){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[queensgambit2]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 29){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[queensindian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 30){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[ruylopez]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 31){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[scandinavian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 32){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[scotch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 33){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[sicilian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 34){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[sicilianalapin]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 35){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[sicilianclosed]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 36){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[siciliansveshnikov]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 37){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[siciliansimagin]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 38){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[sicilianpaulsen]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 39){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[sicilianrichterrauzer]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 40){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[siciliandragon]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 41){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[sicilianscheveningen]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 42){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[siciliansozin]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 43){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[siciliannajdorf]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 44){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[slav]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 45){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[sokolsky1]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 46){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[tarrasch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 47){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[vienna]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 48){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[volgagambit]</font></td>\n";
                                }else{
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>No</font></td>\n";
                                }
				echo("<tr><td bgcolor=white colspan=7> <div align='center'>");
				echo ("<input type='button' value='".$MSG_LANG["accept"]."' onclick=\"sendResponse('accepted', '".$tmpFrom."', ".$tmpGame['gameID'].")\">");
				echo ("&nbsp;");
				echo ("<input type='button' value='".$MSG_LANG["reject"]."' onclick=\"sendResponse('declined', '".$tmpFrom."', ".$tmpGame['gameID'].")\">");
				echo ("</div></td>");
            	
			}
		}

		if ($_SESSION["pref_autoaccept"] == "0"){
	   		echo '</table>
	   		<input type="hidden" name="response" value="">
	   		<input type="hidden" name="messageFrom" value="">
	   		<input type="hidden" name="gameID" value="">
	   		<input type="hidden" name="ToDo" value="ResponseToInvite">
	   		</form>';
	   	}
	}
		$p1 = mysql_query("SELECT * FROM team_members,team WHERE fk_team=teamID AND fk_player='$_SESSION[playerID]'");
    	$row1 = mysql_fetch_array($p1);
    	$myteam = $row1['fk_team'];
    	$team_membership = $row1[level];

		$p2 = mysql_query("SELECT * FROM team_members,players WHERE fk_player=playerID AND fk_team='$myteam' AND level = 0");
		if (mysql_num_rows($p2)>0 && $team_membership >= 100){
			echo "<table border='0' width='300' bgcolor=black cellspacing=1 cellpading=1>";
			echo "<tr><th colspan=3 bgcolor=beige>".$MSG_LANG["membershippending"]."</th></tr>";
			echo "<tr><th bgcolor=white>".$MSG_LANG[name]."</th><th bgcolor=white>".$MSG_LANG['rating']."/".$MSG_LANG['max']."</th><th bgcolor=white>&nbsp;</th></tr>";

			while ($row2 = mysql_fetch_array($p2)){
				echo "<tr><td bgcolor=white><a href='stats_user.php?cod=$row2[playerID]'>$row2[firstName]</a></td><td bgcolor=white>$row2[rating]/$row2[rating_max]</td><td bgcolor=white><input onClick=\"window.location='teams.php?action=acceptuser&playerid=$row2[playerID]&teamid=$myteam'\" style='font-size:11' type=button value='".$MSG_LANG['accept']."'>&nbsp;<input onClick=\"window.location='teams.php?action=rejectuser&playerid=$row2[playerID]&teamid=$myteam'\" style='font-size:11' type=button value='".$MSG_LANG['reject']."'></td></tr>";
			}
			echo "<tr><td colspan=3 bgcolor=white>&nbsp;</td></tr>";
			echo "</table>";
		}

?>
<br>
<table width="300" border="0" cellspacing="1" bgcolor="#000000">
  <th bgcolor="beige">Our Sponsors</th>
  <tr>
    <td><div align="center">
      <script type="text/javascript"><!--
google_ad_client = "pub-9606600691278870";
google_ad_width = 234;
google_ad_height = 60;
google_ad_format = "234x60_as";
google_ad_channel ="2162144154";
google_color_border = "DDB7BA";
google_color_bg = "FFF5F6";
google_color_link = "0000CC";
google_color_url = "008000";
google_color_text = "6F6F6F";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
    </div></td>
  </tr>
</table>
<br>
<table width=300 align=center border=0 bgcolor=black cellspacing=1 cellpading=1>
		
        <td bgcolor="beige" align=center><b><font color="black">Game # <? echo $_SESSION['gameID']; if ($teamMatch == 1) echo ("<font size=+1 font color='RED'><b> TEAM MATCH!");?></font></b>
	</td>
		</tr>
		<?
		echo ("<tr><td bgcolor='green' align='center' style='font-weight: normal'><font color='white'><b>Last moves");
	    for ($i = $numMoves-$i+7; $i <= $numMoves; $i+=1)
                {
                        //echo ("<tr><td bgcolor='green' align='center' style='font-weight: normal'><font color='white'><b>Last 2 Moves - ");

                        $tmpReplaced = "";
                        if (!is_null($history[$i]['replaced']))
                                $tmpReplaced = $history[$i]['replaced'];

                        $tmpPromotedTo = "";
                        if (!is_null($history[$i]['promotedTo']))
                                $tmpPromotedTo = $history[$i]['promotedTo'];

                        $tmpCheck = ($history[$i]['check'] == 1);
						echo("-(");
                        echo(moveToPGNString($history[$i]['curColor'], $history[$i]['curPiece'], $history[$i]['fromRow'], $history[$i]['fromCol'], $history[$i]['toRow'], $history[$i]['toCol'], $tmpReplaced, $tmpPromotedTo, $tmpCheck));
                        echo(")");
					   //echo ("</b></td></tr>\n");
                }
        //echo("]");
		echo ("</b></font></td></tr>\n");
		?>
</table>
		<? writeStatus(); ?>
		<BR> 
		

				<form method=POST action="chess.php" name=chess> 
    <input type=hidden name="gameID" value="<?=$_SESSION["gameID"]?>"> 
    <table border='0' width=300  bgcolor=black cellspacing=1 cellpading=1> 
    <tr><th bgcolor='beige' colspan='3'>NOTES TO SELF 
   <? if ($opponent_language != ""){ 
      $language = $MSG_LANG[strtolower($opponent_language)]; 
      //echo " (".$MSG_LANG['opponentlanguage'].": ".$language.")"; 
      } 
   ?> 
    </th></tr> 
    <tr><td bgcolor=white align=left><?= writeNote($_SESSION['gameID'])?></td></tr> 
    <tr><td align=center bgcolor=white><input type=text name=note_msg size=30 onClick="stopTimer=1"><input type=submit value="<?=$MSG_LANG["write"]?>"></td></tr> 
      </table>     
      </form>
		<? writeTime(); ?>
		<BR>
		<?
		    if (!isBoardDisabled() && mysql_num_rows($tmpGames) == 0)
				writePoints();
		?>
		<BR>
		<!-- <input type="button" style="font-size: 8pt; font-weight: bold; font-family: verdana; cursor: hand" value="<?=$MSG_LANG["resign"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); else echo ("onClick='resigngame($CFG_MIN_ROUNDS,\"".$MSG_LANG["roundwarning"]."\")'"); ?>>&nbsp;&nbsp;
		<input type="button" style="font-size: 8pt; font-weight: bold; font-family: verdana; cursor: hand" value="<?=$MSG_LANG["askdraw"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); else echo ("onClick='draw($CFG_MIN_ROUNDS,\"".$MSG_LANG["roundwarning"]."\")'"); ?>>&nbsp;&nbsp; -->

		
		<table width="300" cellspacing=1 bordercolor="black" bgcolor=black boarder="0" cellpading=1>
		<?

    //Pieces out:

    $black = StartPieces();
    $white = StartPieces();

    $p = mysql_query("SELECT * FROM pieces WHERE gameID = ".$_SESSION['gameID']." order by color,piece");
    $wmaterial = 0;
      $bmaterial = 0;
      while ($row = mysql_fetch_array($p)){
        if ($row['color'] == "white"){
            $white[$row['piece']]--;
                  $tempmaterial=0;
                        if ($row['piece']=="pawn" AND $row['color']=="white")
                        $tempmaterial=1;
                        if ($row['piece']=="knight" AND $row['color']=="white")
                        $tempmaterial=3;
                        if ($row['piece']=="bishop" AND $row['color']=="white")
                        $tempmaterial=3;
                        if ($row['piece']=="rook" AND $row['color']=="white")
                        $tempmaterial=5;
                        if ($row['piece']=="queen" AND $row['color']=="white")
                        $tempmaterial=9;
                        $wmaterial=$wmaterial+$tempmaterial;}
        else {
            $black[$row['piece']]--;
                  $tempmaterial=0;
                        if ($row['piece']=="pawn" AND $row['color']=="black")
                        $tempmaterial=1;
                        if ($row['piece']=="knight" AND $row['color']=="black")
                        $tempmaterial=3;
                        if ($row['piece']=="bishop" AND $row['color']=="black")
                        $tempmaterial=3;
                        if ($row['piece']=="rook" AND $row['color']=="black")
                        $tempmaterial=5;
                        if ($row['piece']=="queen" AND $row['color']=="black")
                        $tempmaterial=9;
                        $bmaterial=$bmaterial+$tempmaterial;}
    }
echo "<tr><td align=center bgcolor=beige>";
        echo "Captured Pieces";
            echo "</td>";
            echo "<td align=center bgcolor=beige style='width: 4em'>";
            echo "Material";
      echo "</td></tr>";

    echo "<tr><td align=left bgcolor=white>";
    while(list($piece,$num) = each($white))
            if ($num > 0)
            for ($y=0; $y<$num; $y++)         
                echo "<img src='images/pieces/".$_SESSION['pref_theme']."/white_".$piece.".gif' height='25'> ";
    $wmaterial = 39 - $wmaterial;
      echo "</td><td align=center bgcolor=white>";
      echo "<font size=+1>".$wmaterial."</font>";   
      echo "</td></tr>";
    echo "<tr><td align=left bgcolor=white>";
    while(list($piece,$num) = each($black))
        if ($num > 0)
            for ($y=0; $y<$num; $y++)
                echo "<img src='images/pieces/".$_SESSION['pref_theme']."/black_".$piece.".gif' height='25'> ";

    $bmaterial = 39 - $bmaterial;
      echo "</td><td align=center bgcolor=white>";
      echo "<font size=+1>".$bmaterial."</font>";   
      echo "</td></tr>";
    echo "</table>"; 
	?>
		<BR>
		
<table align=center border="1" cellspacing="0" cellpadding="0"><tr><td></td></tr> 
<tr><td style="width: 300px; height: 230px;"> 
<div style="width: 100%; height: 100%; overflow : auto;"> 
<? writeHistory();?> </div></td></tr><tr><td></td></tr></table>	<br>
<table width="300" border="0" cellspacing="1" bgcolor="red">
  <th bgcolor="beige">Our Sponsors</th>
  <tr>
    <td><div align="center"><script type="text/javascript"><!--
google_ad_client = "pub-9606600691278870";
google_ad_width = 234;
google_ad_height = 60;
google_ad_format = "234x60_as";
google_ad_channel ="2162144154";
google_color_border = "578A24";
google_color_bg = "CCFF99";
google_color_link = "00008B";
google_color_url = "00008B";
google_color_text = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div></td>
  </tr>
</table>    		
		<form name="allhisgames" action="chess.php" method="post">
		<table border="0" width="300" bgcolor=black cellspacing=1 cellpading=1>
		<tr>
		<th bgcolor="beige" colspan=7><?=$MSG_LANG["quickgame"]?></th>
		</tr>
		<?
		$tmpGames = mysql_query("SELECT games.*,DATE_FORMAT(dateCreated, '%d/%m/%y %H:%i') as created, DATE_FORMAT(lastMove, '%d/%m/%y %H:%i') as lastm FROM games WHERE gameMessage = '' AND (whitePlayer = ".$_SESSION['playerID']." OR blackPlayer = ".$_SESSION['playerID'].") ORDER BY lastMove");
		if (mysql_num_rows($tmpGames) > 0){
		$l = 0;
		while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
		{
			/* get opponent's nick */
			if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
				$tmpOpponent = mysql_query("SELECT firstName,lastName,lastUpdate,engine,playerID FROM players WHERE playerID = ".$tmpGame['blackPlayer']);
			else
				$tmpOpponent = mysql_query("SELECT firstName,lastName,lastUpdate,engine,playerID FROM players WHERE playerID = ".$tmpGame['whitePlayer']);

			$row = mysql_fetch_array($tmpOpponent);
            $opponent = substr($row[0],0,25);
			if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
				$tmpColor = "white";
			else
				$tmpColor = "black";

           	if ($row[2] >= (time()-300))
               	$img = "online";
           	else
               	$img = "offline";

			$tmpNumMoves = mysql_query("SELECT COUNT(gameID) FROM history WHERE gameID = ".$tmpGame['gameID']);
			$numMoves_2 = mysql_result($tmpNumMoves,0);
			$yourturn = false;
			if (($numMoves_2 % 2) == 0){
				$tmpCurMove = "white";
				if ($tmpColor == "white")
				    $yourturn = true;
			}else{
				$tmpCurMove = "black";
				if ($tmpColor == "black")
					$yourturn = true;
			}

			if (($yourturn || $img == "online") && $tmpGame['gameID']){
			    $l++;
			    
				if ($l == 1){
					?>
					<tr>
	        		<th bgcolor="white">&nbsp;</th>
					<th bgcolor="white">&nbsp;</th>
					<th bgcolor="white"><?=$MSG_LANG["opponent"]?></th>
					<th bgcolor="white"><?=$MSG_LANG["turn"]?></th>
					</tr>
					<?
				}
			    
            	echo "<tr><td bgcolor='white' ><img src='images/$img.gif' alt='$txt'></td>";
				echo "<td bgcolor='white' align=center><input style='font-size:11' type=button value='".$MSG_LANG['play']."' onClick='loadnextGame(".$tmpGame['gameID'].")'></td>";
    			/* Opponent */
				echo("<td bgcolor='white' >");
			
				$agora = mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y"));
				$v = explode(" ",$tmpGame[lastm]);
		        $hora = explode(":",$v[1]);
		        $data = explode("/",$v[0]);
		        $lastmove = mktime($hora[0],$hora[1],0,$data[1],$data[0],$data[2]);

				$d = floor(($agora-$lastmove)/60/60/24);
				$d = floor(($agora-$lastmove)/60/60/24);
		        $h = floor(($agora-$lastmove)/60/60) - 24*$d;
		        $m = round(($agora-$lastmove)/60) - 60*$h - $d*24*60;
				
				if ($d>=($CFG_EXPIREGAME-1))$cor="#FF000";
				elseif ($d>=($CFG_EXPIREGAME-2))$cor="#CC3300";
            	elseif ($d>=($CFG_EXPIREGAME-3))$cor="#CC6600";
            	elseif ($d>=($CFG_EXPIREGAME-4))$cor="#FF9900";
				else $cor ="black";
				
				if ($d ==1)$txt = $MSG_LANG['day'];
				else $txt = $MSG_LANG['days'];
			
				$lasttouch = date("d.m.y, H:i", $row[2]);
				echo("<a href='stats_user.php?cod=".$row['playerID']."'><b>".$opponent."</b>"."</a>"."<br> ".$MSG_LANG["lastmove"]."($d $txt)"."<br>".$lasttouch);
				//echo $opponent;

				/* Your Color */
				echo ("</td><td bgcolor='white' align=center>");
				if ($yourturn)
					echo ("<strong><font color=red>".$MSG_LANG["yourturn"]."</font></strong>");
				else
					echo $MSG_LANG["waiting"];
				echo ("</td>");
				echo "</tr>\n";
			}
		}

		if ($l ==0)
			echo("<tr><td bgcolor='white' align=center>".$MSG_LANG["noquickgames"]."</td></tr>\n");

		/* share PC */
		//echo ("<tr><td colspan='3'>Will both players play from the same PC?</td>");
		//echo ("<td><input type='radio' name='rdoShare' value='yes'> Yes</td>");
		//echo ("<td><input type='radio' name='rdoShare' value='no' checked> No</td></tr>\n");
	}
	?>
	</table>
	
	<input type='hidden' name='rdoShare' value='no'>
	<input type="hidden" name="gameID" value="">
	<input type="hidden" name="sharePC" value="no">
	</form>
		
	</td>
</tr>
<tr>
	<td>
	<p align=left>
	<font face=verdana size=1 color=#EEEEEE>
	<script>
    curDir = -1;

    if (isInCheck('<?=$mycolor2?>')){
        document.write("<?=$mycolor2?> is in Check<BR>");
	}
    curDir = -1;
    if (isXequeMate('<?=$mycolor2?>')){
        document.write("<?=$mycolor2?> is in Check-Mate<BR>");
	}
    if (isDraw('<?=$mycolor2?>')&&!isXequeMate('<?=$mycolor2?>')){
	document.write('Javascript result: The game ends in a Draw!');
    }
	</script>
	<?
    if (!$isPlayersTurn && !isBoardDisabled() && !$_SESSION['isSharedPC'])
	{
		if ($_SESSION['pref_autoreload'] >= $CFG_MINAUTORELOAD)
			$autoreload = $_SESSION['pref_autoreload'];
		else
			$autoreload = $CFG_MINAUTORELOAD;

		echo "<script>
                setTimeout(\"refreshwindow()\",".($autoreload*1000).")
              </script>";
	}
	?>
	</font>
	</p>
	</td>
	<td>&nbsp;</td>
</tr>
</table>

</body>
</html>
<form name="logout" action="mainmenu.php" method="post">
	<input type="hidden" name="ToDo" value="Logout">
</form>

<?
if (isset($COMPRESSION) && $COMPRESSION && isset($ob_mode) && $ob_mode) {
         PMA_outBufferPost($ob_mode);
}
?>
