<?php
##############################################################################################
#                                                                                            #
#                                forumanalyze.php                                                
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

  This analyze script is an adaptation of n8chessnet analyze board from Nathan Kelley (n8osapi)
  More information: http://www.n8osapi.com

*/



/* Language selection */

?>
<HTML>
<HEAD><TITLE>Analyzing Game</TITLE>
</HEAD>
<SCRIPT language="javascript" src="javascript/chessutils.php"></SCRIPT>
<SCRIPT language="javascript" src="javascript/analyze.php"></SCRIPT>
<SCRIPT language="javascript">

var board = new Array();

<?
        /* load settings */
        include_once ('config.php');

	require_once ( 'chessconstants.php');
	require_once('chessdb.php');

        /* include outside functions */
        if (!isset($_CHESSUTILS))
                require_once ( 'chessutils.php');
     #   require_once('gui.php');
     #   require 'move.php';
     #   require 'undo.php';
     #   require_once 'blocks.php';

        fixOldPHPVersions();
	
        require_once( 'connectdb.php');

	echo("//game=$game\n");
	$_SESSION['gameID'] = $game;
	//session_register($_SESSION);
    loadHistory();

	global $history, $numMoves;
	#echo("var theme=\""+$_SESSION['uiTheme']+"\";"); 
        /* write out constants */
        for ($i = 0; $i <= $numMoves; $i++)
        {
              #echo ("chessHistory[$i] = new Array();\n");
              #echo ("chessHistory[$i][CURPIECE] = '".$history[$i]['curPiece']."';\n");
              #echo ("chessHistory[$i][CURCOLOR] = '".$history[$i]['curColor']."';\n");
              #echo ("chessHistory[$i][FROMROW] = ".$history[$i]['fromRow'].";\n");
              #echo ("chessHistory[$i][FROMCOL] = ".$history[$i]['fromCol'].";\n");
              #echo ("chessHistory[$i][TOROW] = ".$history[$i]['toRow'].";\n");
              #echo ("chessHistory[$i][TOCOL] = ".$history[$i]['toCol'].";\n");
	      $fR = $history[$i]['fromRow'];
	      $fC = $history[$i]['fromCol'];
	      $tR = $history[$i]['toRow'];
	      $tC = $history[$i]['toCol'];
	      $p = $history[$i]['replaced'];
	      $p2 = $history[$i]['promotedTo'];
	      if ($history[$i]['curColor']=="white"){$c="BLACK";}else{$c="WHITE";}
	      if ($history[$i]['curColor']=="white"){$mc="WHITE";}else{$mc="BLACK";}
	      $pT = $c."|".strtoupper($p);
	      $pR = $mc."|".strtoupper($p2);
		  if (!$p)$pT = "0";
		  if (!$p2)$pR = "0";
		  echo ("StoreMove($fR,$fC,$tR,$tC,$pT,$pR);\n");
        }
?>

InitializeBoard();
</SCRIPT>
</HEAD>
<BODY bgcolor="#DDDDDD">
<font face=verdana size=2><center>
<?
$gameID = $_REQUEST['game'];

$result = mysql_query("SELECT p1.firstName AS player1_name, p2.firstName AS
player2_name FROM games g LEFT JOIN players p1 ON (g.whitePlayer =
p1.playerID) LEFT JOIN players p2 ON (g.blackPlayer = p2.playerID) WHERE
g.gameID = $gameID");
if(mysql_error()) die(mysql_error());

$row = mysql_fetch_array($result);
if(mysql_error()) die(mysql_error());


echo"<table border='0' cellspacing='0' cellpadding='1' width='100%'>";
echo"<tr><td><font face=verdana size=2 color=blue><center><h5>Game No.
$gameID</h5></td></tr></table>";

//echo substr($row['player1_name'],0,25) . ' <b>('.$MSG_LANG[white].')</b> X ' . substr($row['player2_name'],0,25) . '<b>('.$MSG_LANG[black].')</b> ';
echo substr($row['player1_name'],0,25) . '(<img src="images/smwhitepawn.gif">) VS. ' . substr($row['player2_name'],0,25) . '(<img src="images/smblackpawn.gif">)';

?></center></font><BR>

<TABLE width="100%"><TR><TD align="center">
<SCRIPT language="javascript">
who="<?=$whocolor?>";
DisplayBoard();
MoveEnd();
</SCRIPT>
</TD></TR></TABLE>
</BODY>
</HTML>

