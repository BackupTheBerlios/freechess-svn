<?php
##############################################################################################
#                                                                                            #
#                                analyze.php                                                
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

$_SESSION['gameID'] = ($_GET['game']) ? $_GET['game'] : $_SESSION['gameID'];
$gameID = ($_GET['game']) ? $_GET['game'] : $_REQUEST['game'];

/* Language selection */ 
//require "languages/".$_SESSION['pref_language'].".inc.php"; 

$images = "images/analyze/";  

?>
<HTML>
<HEAD><TITLE>Analyzing Game</TITLE>
<style>
<!--
a { 	color: #000000;
	text-decoration: none;

table.noclass {
	background: url('images/analyze/board.gif');
}
}
-->
</style>

<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/board.css" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></HEAD>

<SCRIPT language="javascript" src="javascript/chessutils<?=$JAVASCRIPT_EXT?>"></SCRIPT>
<SCRIPT language="javascript" src="javascript/analyze_test<?=$JAVASCRIPT_EXT?>"></SCRIPT>
<SCRIPT language="javascript">


var board = new Array();

<?PHP 

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

	echo("//game=".$_REQUEST['game']."\n");
	$_SESSION['gameID'] = $_REQUEST['game'];
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
               $t =  $history[$i]['timeOfMove'];
	      if ($history[$i]['curColor']=="white"){$c="BLACK";}else{$c="WHITE";}
	      if ($history[$i]['curColor']=="white"){$mc="WHITE";}else{$mc="BLACK";}
	      $pT = $c."|".strtoupper($p);
	      $pR = $mc."|".strtoupper($p2);
		  if (!$p)$pT = "0";
		  if (!$p2)$pR = "0";
		  echo ("StoreMove($fR,$fC,$tR,$tC,$pT,$pR,\"$t\");\n");
        }

        echo "set_gameID(".$gameID.");\n";

?>
who="<?=$_REQUEST['whocolor']?>";
InitializeBoard();
</SCRIPT>
</HEAD>
<BODY link="#0033FF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<font face=verdana size=2>
<center>
<font face=verdana size=2><font face=verdana size=2>
</font></font>
<table width="100%">
  <tr>
    <td bgcolor="#CCCCCC"><div align="center"><font face=verdana size=2><font face=verdana size=2><font face=verdana size=2>
        <?PHP

$result = mysql_query("SELECT p1.firstName AS player1_name, p2.firstName AS
player2_name FROM games g LEFT JOIN players p1 ON (g.whitePlayer =
p1.playerID) LEFT JOIN players p2 ON (g.blackPlayer = p2.playerID) WHERE
g.gameID = $gameID");
if(mysql_error()) die(mysql_error());

$row = mysql_fetch_array($result);
if(mysql_error()) die(mysql_error());


//echo"<table border='0' cellspacing='0' cellpadding='1' width='100%'>";
//echo"<tr><td><font face=verdana size=2 color=blue><center><h5>Game No.
//$gameID</h5></td></tr></table>";

echo"Game No. <b>$gameID</b>&nbsp;&nbsp; ";

//echo substr($row['player1_name'],0,25) . ' <b>('.$MSG_LANG[white].')</b> X ' . substr($row['player2_name'],0,25) . '<b>('.$MSG_LANG[black].')</b> ';
echo substr($row['player1_name'],0,25) . '(<img src="images/smwhitepawn.gif">) VS. ' . substr($row['player2_name'],0,25) . '(<img src="images/smblackpawn.gif">)&nbsp;&nbsp;';
echo "<a href='http://www.Webmaster/webchess/annotatedgames.php'><font color='#0000FF'><u><b>View Annotated Games!</b></u></font></a>";

?>
    </font></font></font></div></td>
  </tr>
</table>
<font face=verdana size=2><font face=verdana size=2>
</font></font>
<TABLE align="center" cellpadding="0" cellspacing="0" border="0">
  <TR><TD align="center" valign="top">
<SCRIPT language="javascript">

DisplayBoard();

</SCRIPT>
</TD>
<td align="center" valign="top">
<font size="2"><b>Annotations:</b></font><br>
<script>

            document.write("<input type=\"button\" value=\"Insert a comment!\" onclick=\"if ((ts[iCurMove]!='') && (ts[iCurMove]!=undefined)){ window.open('analyze_write.php?gameID='+gameID+'&timeOfMove='+ts[iCurMove],'_new','toolbar=no,scrollbars=yes,resizable=no,width=400,height=415');} else { alert('Start the game and then choose which move you would like to insert a comment after!'); }\">");

         document.write("<input type=\"button\" value=\"Symbol\" onclick=\"if ((ts[iCurMove]!='') && (ts[iCurMove]!=undefined)){ window.open('analyze_write.php?quick=true&gameID='+gameID+'&timeOfMove='+ts[iCurMove],'_new2','toolbar=no,scrollbars=yes,resizable=no,width=250,height=250');} else { alert('Start the game and then choose which move you would like to insert a symbol after!'); }\">");

         document.write("<input type=\"button\" value=\"View Comments\" onclick=\"javascript: if(showall==false){ showall='true'; this.value='Hide Comments' } else { showall=''; this.value='View Comments'; } analyze_comments.location.href='analyze_comments.php?gameID='+gameID+'&timeOfMove='+ts[iCurMove]+'&showall='+showall;\">");



</script>



<br>
<iframe src="analyze_comments.php?gameID=<?=$gameID?>&timeOfMove=undefined" width="400" height="400" name="analyze_comments" frameborder="0"></iframe>
</td>
</tr>
</TABLE>
<font face=verdana size=2></font><br>
<script type="text/javascript"><!--
google_ad_client = "pub-9606600691278870";
google_ad_width = 728;
google_ad_height = 90;
google_ad_format = "728x90_as";
google_ad_channel ="0893192378";
google_color_border = "003366";
google_color_bg = "000000";
google_color_link = "FFFFFF";
google_color_url = "FF6600";
google_color_text = "FF6600";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script><br>
<SCRIPT language="javascript">

UpdateBoard();

</SCRIPT>
<!-- Please levae Credits -->
<font face=verdana size=1>Analyze Mod (c) 2004 by <a href="http://www.fivedigital.net" target="_blank"><b>FiveDigital</b></a>
</center></font>
</BODY>
</HTML>