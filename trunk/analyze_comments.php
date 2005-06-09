<?php
##############################################################################################
#                                                                                            #
#                                analyze_comments.php                                                
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
        require_once('gui.php');

?>
<html>
<head>
<style>
<!--
a { 	color: #000000;
	text-decoration: none;
}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><title>Annotations</title></head>
<BODY bgcolor="#FFFFFF">
<font face=verdana size=2>
<div align="justify">
<?PHP

$act = $_GET['timeOfMove'];
$gameID = $_GET['gameID'];
$showall = $_GET['showall'];

if ($act == 'undefined') $act = 'Beginning of Game';
if ($act == '') $act = 'Spielende';

if ($act != 'Beginning of Game' && $act != 'Spielende')
{

$query = "SELECT * FROM history WHERE gameID = '".$gameID."' AND timeOfMove = '$act'";
$z2 = mysql_query($query);
$z = mysql_fetch_array($z2);

verifyGameFlag();
$numMoves = 0;
$history[$numMoves] = $z;

echo "<b>Current Move: </b>"; writeHistoryPGN("plain", 'single');

}
else
echo "<b>".$act."</b>";
/*
if(!$showall)

echo "<a href=\"analyze_comments.php?gameID=".$gameID."&timeOfMove=".$act."&showall=true\"><b>Alle Kommentare zeigen</b></a>";

else

echo "<a href=\"analyze_comments.php?gameID=".$gameID."&timeOfMove=".$act."\"><b>Keine Kommentare zeigen</b></a>";

*/

echo "<br><br>\n\n";

$query = "SELECT * FROM history WHERE gameID = '".$gameID."' ORDER BY timeOfMove";
$i = 1;
$j = 1;
$newline = true;

$z2 = mysql_query($query);       echo mysql_error();
$count = mysql_num_rows($z2);

//loadHistory();

while ($z = mysql_fetch_array($z2))
{
         // PGN
	verifyGameFlag();
         $numMoves = 0;
	$history[$numMoves] = $z;

         ?>
         <a name="goto<?=$i?>"></a>
         <?PHP

         echo "<b>";

         if ($act == $z['timeOfMove'])
         {
         	echo "<font color='#FF0000'>";
                 if ($newline) echo $j.".&nbsp;";
                 writeHistoryPGN("plain", 'single'); echo "</b>";
                 echo "</font>";
         }
	else
         {
                 if ($newline) echo $j.".";
                 writeHistoryPGN("plain", true); echo "</b>";
         }

         ?>
         </a>

         <?PHP

         $g2 = mysql_query("SELECT * FROM comments WHERE gameID = '$gameID'
               		  AND timeOfMove = '".$z['timeOfMove']."' AND text <> ''");
         $count = mysql_num_rows($g2);

         if (($act == $z['timeOfMove'] || $showall == true) && ($count > 0))
         {

         // get quick comment
         $q2 = mysql_query("SELECT * FROM comments WHERE gameID = '$gameID' AND timeOfMove = '".$z['timeOfMove']."'
               		   AND quick = '1'
                            GROUP BY text
                            ORDER BY time ASC");
         while ($q = mysql_fetch_array($q2))
         {
          	//writeHistoryPGN("plain", 'single');

                 $file = dirname(__FILE__) . "/images/analyze/comments/" . $q['text'];

                 if(file_exists($file))
                 {
                 	echo '<img src="images/analyze/comments/'.$q['text'].'" border="0">&nbsp;';
                 }
                 else
                 {
			echo $q['text']."&nbsp;\n";
                 }
         }

         // get comments
         $g2 = mysql_query("SELECT * FROM comments WHERE gameID = '$gameID' AND timeOfMove = '".$z['timeOfMove']."'
               		   AND quick = '0' AND text <> ''
                            ORDER BY time ASC");
         while ($g = mysql_fetch_array($g2))
         {

                 $text = stripslashes($g['text']);
                 $text = strip_tags($text, '<a><b><i><u><br>');
                 $text = str_replace("\n", "<br>", $text);

                 $p = mysql_query("SELECT * FROM players WHERE playerID = '".$g['playerID']."'");
 		$player = mysql_fetch_array($p);

                 echo "<i>".$player['firstName'].":</i> " . $text . "&nbsp;\n";

         }

         }

         $i++;
         $newline = false;

         if(!is_integer($i/2))
         {
                 $newline = true;
                 $j++;
         }



}
         echo "<hr noshade size='1'>";
         // Result

         $result = "*";          //In progress

	$r = mysql_query("SELECT gameMessage,messageFrom FROM games WHERE gameID='$gameID'");

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

         echo "<b>[Result \"$result\"]</b>\n";

?>
<br>
</div>
</font>
</body>
</html>