<?php
##############################################################################################
#                                                                                            #
#                                allgames.php                                                
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


if (!isset($_GET['voltar']))
    $voltar = "mainmenu.php";
else $voltar = $_GET['voltar'];
if ($_GET['rdoFilter'] <> "0" AND $_GET['rdoFilter'] <> "") 
     $playerfilter = $_GET['rdoFilter'];
switch($_POST['ToDo']) 
   {case 'FilterGames': 
            $playerfilter = $_POST['rdoFilter']; 
   } 

?>

<html>
<head>
	<title>ChessManiac</title>

<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
</head>

<body bgcolor=white text=black>
<form name="preferences" action="allgames.php" method="post">
<font face=verdana size=2>
<? require_once('header.inc.php');?>
<BR>
<? require_once("ads/chess.inc.php");?>

<br>
<?=$MSG_LANG["thegamesstillhadbelownotbeenfinished"]?>
<BR><BR> 
<? 
echo "<select name='rdoFilter'>"; 
echo "<option value=0>All Games</option>"; 
$p = mysql_query("SELECT * FROM players WHERE ativo = '1' ORDER BY firstName ASC"); 
while($row = mysql_fetch_array($p)){ 
                if ($playerfilter == $row[playerID]) 
                $s = "SELECTED"; 
                else $s=""; 
                echo "<option value='$row[playerID]' $s>".$row[firstName]."</option>"; 
                } 
 
?> 
</select> 
<tr> 
         <td colspan="2"> 
            <input type="submit" value="Filter"> 
         </td> 
      </tr> 

<BR><BR>
	<font face=arial size=2><?=$MSG_LANG['page']?>:
	<?
#########################
#      Page control     #
#########################

$perpage = $CFG_PERPAGE_LIST;

if ($pagina=="")
	$pagina=1;

$inicio = $perpage * $pagina - $perpage;
$fim = $perpage * $pagina;
if ($playerfilter <> "0" AND $playerfilter <> "") 
$rs_add = "AND (whitePlayer = '".$playerfilter."' OR blackPlayer = '".$playerfilter."')"; 
$rs = mysql_query("SELECT count(*) from games where gameMessage='' $rs_add"); 
$row = mysql_fetch_array($rs);
$ultima = ceil($row[0]/$perpage);

if ($row[0] <= $fim)$proxima=FALSE;
else $proxima=TRUE;

########################
	
	if ($pagina-1 >0) 
      echo "<a href='".$_SERVER['PHP_SELF']."?rdoFilter=$playerfilter&cod=$player&voltar=$voltar&pagina=".($pagina-1)."'>[&laquo;]</a>"; 
   else 
      echo "<font color=#bbbbbb>[&laquo;]</font>"; 
   $b=1; 
   for ($pg=1 ; $pg<=$ultima ; $pg++){ 
      if ($pg != $pagina)echo " <a href='".$_SERVER['PHP_SELF']."?rdoFilter=$playerfilter&cod=$player&voltar=$voltar&pagina=$pg'>[$pg]</a>"; 
      else echo " [$pg] "; 
      if (floor($pg/23) == $b){ 
                   echo "<BR>"; 
                   $b++; 
      } 
   } 

   if ($proxima) 
      echo "<a href='".$_SERVER['PHP_SELF']."?rdoFilter=$playerfilter&cod=$player&voltar=$voltar&pagina=".($pagina+1)."'>[&raquo;]</a>"; 
   else 
      echo "<font color=#bbbbbb>[&raquo;]</font>"; 

   ?> 
   </font>
<BR><BR>
<table border="1" width="100%">
<TR><Th colspan=5><?=$MSG_LANG["alltheactivegames"]?></td></tr>
<tr>
    <th><?=$MSG_LANG["players"]?></th>
    <th><?=$MSG_LANG["start"]?></th>
    <th><?=$MSG_LANG["lastmove"]?></th>
    <th><?=$MSG_LANG["rounds"]?></th>
    <th><?=$MSG_LANG["analyze"]?></th>
</tr>
<?
if ($playerfilter <> "") 
      $p = mysql_query("SELECT games.*,DATE_FORMAT(dateCreated, '%m/%d/%y') as inicio,DATE_FORMAT(lastMove, '%m/%d/%y') as fim from games where gameMessage='' AND (whitePlayer=".$playerfilter." OR blackPlayer=".$playerfilter.") ORDER BY dateCreated DESC LIMIT $inicio,$perpage"); 
    if ($playerfilter == "0" OR $playerfilter == "") 
      $p = mysql_query("SELECT games.*,DATE_FORMAT(dateCreated, '%m/%d/%y') as inicio,DATE_FORMAT(lastMove, '%m/%d/%y') as fim from games where gameMessage='' ORDER BY dateCreated DESC LIMIT $inicio,$perpage"); 

    if (mysql_num_rows($p)>0){
    while ($row = mysql_fetch_array($p)){

        $p2 = mysql_query("SELECT count(*) from history WHERE gameID=$row[gameID]");
        $row2 = mysql_fetch_array($p2);
        $rounds = ceil($row2[0]/2);
        
        $p2 = mysql_query("SELECT firstName,playerID from players WHERE playerID='$row[whitePlayer]'");
        $row2 = mysql_fetch_array($p2);
        $white = $row2[0];
        $idw = $row2[1];
        
        $p2 = mysql_query("SELECT firstName,playerID from players WHERE playerID='$row[blackPlayer]'");
        $row2 = mysql_fetch_array($p2);
        $black = $row2[0];
        $idb = $row2[1];

        $p2 = mysql_query("SELECT count(*) from history WHERE gameID=$row[gameID]");
        $row2 = mysql_fetch_array($p2);
        $rounds = floor($row2[0]/2);

        echo "<tr>
                <td align='center'><a href='stats_user.php?cod=$idw&voltar=allgames.php'>$white</a> x <a href='stats_user.php?cod=$idb&voltar=allgames.php'>$black</a></td>
                <td align='center'>$row[inicio]</td>
                <td align='center'>$row[fim]</td>
                <td align='center'>$rounds</td>
                <td align='center'><a href='#' onClick=window.open('analyze.php?game=$row[gameID]','_blank','toolbar=no,status=no,menubar=no,scrollbars=yes,width=850,height=600')>$row[gameID]</a></td>
              </tr>";
    }
    }



?>
<input type="hidden" name="ToDo" value="FilterGames"> 
</table> 
</form> 
<form name="existingGames" action="chess.php" method="post">
        <input type='hidden' name='rdoShare' value='no'>
		<input type="hidden" name="gameID" value="">
		<input type="hidden" name="sharePC" value="no">
</form>

<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>
<br>
<? include("footer.inc.php");?>
</body>
</html>

<? //mysql_close(); ?>

