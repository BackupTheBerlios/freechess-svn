<?php
##############################################################################################
#                                                                                            #
#                               export_all_pgn.php                                                
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
	//require_once('gui.php');

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
if (isset($_POST['pagina'])) {
    $pagina = $_POST['pagina'];
}
      
//get user id
$player=$_GET['cod'];
#$player=1;
$pgnString = "";
//get list of games for this user
$games = mysql_query("SELECT * from games where (whitePlayer='$player' OR blackPlayer='$player') AND gameMessage<>'' AND  gameMessage<>'playerInvited' AND gameMessage<>'inviteDeclined' ORDER BY lastMove");

//for each game, write to file

    if (mysql_num_rows($games)==0){

    }
    else{
       while ($row = mysql_fetch_array($games)){
          //write headers for this game
          $game_id = $row['gameID'];
          $white=$row['whitePlayer'];
           $white = mysql_fetch_array(mysql_query("SELECT firstName from players WHERE playerID='$white'"));
          $black=$row['blackPlayer'];
          $black = mysql_fetch_array(mysql_query("SELECT firstName from players WHERE playerID='$black'"));
          $date=substr($row['lastMove'],0,10);
          $date = str_replace('-','.',$date);
		  if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "white")
              $situacao = "0-1";
            else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "black")
              $situacao = "1-0";
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "white")
              $situacao = "1-0";
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "black")
              $situacao = "0-1";
            else if ($row['gameMessage'] == "draw")
              $situacao = "1/2-1/2";
            else
                $situacao = "";
          
$pgnString .= ('

[Event "gameID '.$row['gameID'].'"]
[Site "http://www.Webmaster"]
[Date "'.$date.'"]
[Round ""]
[White "'.$white[0].'"]
[Black "'.$black[0].'"]
[TimeControl "normal"]
[Result "'.$situacao.'"]
[ECO "oft "]
');
            
         //write pgn for this game
#         $pgnString = "";
         $turn = 1;
         $allMoves = mysql_query("SELECT * FROM history WHERE gameID='$game_id' ORDER BY timeOfMove");
         
         //for each move write to string
         while ($rowG = mysql_fetch_array($allMoves)){
            if ($rowG[curColor] == "white"){
               $pgnString .= ("$turn.");
            }
            else{
               $turn++;
            }
            
            /* check for castling */
            if (($rowG[curPiece] == "king") && (abs($rowG[toCol] - $rowG[fromCol]) == 2)){
               /* if king-side castling */
               if (($rowG[toCol] - $rowG[fromCol]) == 2)
                  $pgnString .= ("O-O");
               else
                  $pgnString .= ("O-O-O");
            }
            else{
               /* PNG code for moving piece */
               $pgnString .= getPGNCode($rowG[curPiece]);

               /* source square */
#               $pgnString .= chr($rowG[fromCol] + 97).($rowG[fromRow] + 1);
               if ($rowG[curPiece]!='pawn' and $rowG[curPiece]!='king' and $rowG[curPiece]!='queen'){$pgnString .= chr($rowG[fromCol] + 97);}

               /* check for captured pieces */
               if ($rowG[replaced] != ""){
                  if ($rowG[curPiece]=='pawn'){$pgnString .= chr($rowG[fromCol] + 97);}
                  $pgnString .= "x";
               }
#               else
#                  $pgnString .= "-";

               /* destination square */
               $pgnString .= chr($rowG[toCol] + 97).($rowG[toRow] + 1);

               /* check for pawn promotion */
               if ($rowG[promotedTo] != "")
                  $pgnString .= "=".getPGNCode($rowG[promotedTo]);
            }

            /* check for CHECK */
            if ($rowG[isInCheck])
               $pgnString .= "+";

            /* if checkmate, $pgnString .= "#"; */
            
            $pgnString .= " ";
         }
         //add result
         $pgnString .= "$situacao";
         

       }
   //print pgn
//   echo "$pgnString<br><br>";
   $firstName = mysql_fetch_array(mysql_query("SELECT firstName from players WHERE playerID='$player'"));
   $filename='pgndata/'.$firstName[0].'.pgn';
   
   //write to file
   $fp = fopen("$filename",'w');
   fwrite($fp,$pgnString);
   fclose($fp);
    }
//show download link for file created
?>
<html>
<head>
<title>Webmaster</title>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>
<?
//replace any spaces in the file name so that the link to the newly created file will work.
$filename = str_replace(' ','%20',$filename);

include("header.inc.php");
if($filename){ 
echo "<p><p align=center>Player <b>".$firstName[0]."</b> has played <b>".mysql_num_rows($games)." </b>games in total on Webmaster. A file containing all these games has now been created in pgn format readable by many chess programs. Download the file and save on your PC by using the link below.";

echo "<p><p align=center><a href=$filename>Download file here</a><br><br>";
}
else{
   echo "<p>This player has not yet completed any games.";
} 
include("footer.inc.php");
echo "</body></html>";

?> 