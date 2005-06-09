<?php
##############################################################################################
#                                                                                            #
#                                annotatedgames.php                                                
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
?>
<HTML>    
<head>
<title>Chess Maniac Annotated Games</title>
	<meta name="Keywords" content="chess,ajedrez,échecs,echecs,scacchi,schach,check,check mate,jaque,jaque mate,queenalice,queen alice,queen,alice,play,game,games,turn based,correspondence,correspondence chess,online chess,play chess online">
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
</HEAD>
<body bgcolor=white text=black>
<font face=verdana size=2> 
<? require_once('header.inc.php');?> 

<br>
<? include("ads/chess.inc.php");?>

<br>
<!-- Kommentierte Spiele -->
<br>
         <table width="100%" border="1">
         <tr>
         <th><b>Game No.: </b></th><th><b>Annotated By</b></th>
         </tr>

         <?PHP

         $cnum = 0;

$s2 = mysql_query("SELECT playerID,gameID, count(*) as anzahl FROM comments WHERE quick = '0' GROUP BY gameID ORDER BY time DESC");
echo mysql_error();
while ($s = mysql_fetch_array($s2))
{
    if ($cnum < $anz_spiele && $s['anzahl'] >= $min_kommentare)
    {

    $g2 = mysql_query("SELECT * FROM games WHERE gameID = '".$s['gameID']."'");
         $g = mysql_fetch_array($g2);

 	$pl1 = mysql_query("SELECT * FROM players WHERE playerID = '".$g['whitePlayer']."'");
         $p1 = mysql_fetch_array($pl1);

 	$pl2 = mysql_query("SELECT * FROM players WHERE playerID = '".$g['blackPlayer']."'");
         $p2 = mysql_fetch_array($pl2);

         $commentators = '';

         $s3 = mysql_query("SELECT playerID FROM comments WHERE gameID = '".$s['gameID']."' GROUP BY playerID ORDER BY time DESC");
	echo mysql_error();
	while ($ss = mysql_fetch_array($s3))
         {
                 $cm2 = mysql_query("SELECT * FROM players WHERE playerID = '".$ss['playerID']."'");
        	 	$cm = mysql_fetch_array($cm2);

                 $commentators .= $cm['firstName'] . ", ";
         }

         $commentators .= ",,";

         $commentators = str_replace(", ,,", "", $commentators);

         echo "<tr><td align=\"left\">";
         echo "<a href=\"analyze.php?whocolor=white&game=".$s['gameID']."\"><b>".$s['gameID']."</b></a>&nbsp;";
         echo $p1['firstName'] . " x " . $p2['firstName'];
         echo "&nbsp;(".$s['anzahl'].")</td><td>". $commentators;
         echo "</td></tr>";

         $cnum++;

        }
}

?>

         </table>
<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>
<? include("footer.inc.php");?>
</body>
</html>