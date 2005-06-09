<?php
##############################################################################################
#                                                                                            #
#                                ranking_1400.php                                                
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


#########################
#      Page control     #
#########################

$perpage = $CFG_PERPAGE_LIST;
if (isset($_GET['pagina'])) 
   $pagina = $_GET['pagina'];

if ($pagina=="")
        $pagina=1;

$inicio = $perpage * $pagina - $perpage;
$fim = $perpage * $pagina;

if ($CFG_RANKING_LIMIT > 0)
	$limit = "limit $CFG_RANKING_LIMIT";
else
	$limit = "";
	
//$rs = mysql_query("SELECT playerID from games,players where ativo<>'0' and oficial='1' AND (gameMessage <> 'playerInvited' AND gameMessage <> 'inviteDeclined' AND gameMessage <> '') AND (blackPlayer=playerID OR whitePlayer=PlayerID) group by playerID order by rating DESC,rating_month DESC $limit");
$tempo = time()-1209600;
$rs = mysql_query("SELECT playerID,lastUpdate from players where lastUpdate>='$tempo' and ativo<>'0' and rating <= 1400 and rating>=900 and pontos>0 order by rating DESC,rating_month DESC $limit");


$tot = mysql_num_rows($rs);
$ultima = ceil($tot/$perpage);

if ($tot <= $fim)$proxima=FALSE;
else $proxima=TRUE;

if ($CFG_RANKING_LIMIT == 0)
	$CFG_RANKING_LIMIT = $tot;
	
########################
?>


<html>
<head>
	<title>ChessManiac</title>

<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">

</head>

<body bgcolor=white text=black>
<font face=verdana size=2>
<? require_once('header.inc.php');?>
<? include("Rating_Ladder_Buttons.inc.php");?>
        <font face=arial size=2><?=$MSG_LANG['ranking']?>:

	<?
        if ($pagina-1 >0)
                echo "<a href='".$_SERVER['PHP_SELF']."?pagina=".($pagina-1)."'>[&laquo;]</a>";
        else
                echo "<font color=#bbbbbb>[&laquo;]</font>";

		$b=1;
        for ($pg=1 ; $pg<=$ultima ; $pg++){

				if ($CFG_PERPAGE_LIST*$pg > $CFG_RANKING_LIMIT)
				    $fim = $CFG_RANKING_LIMIT;
				else $fim = $CFG_PERPAGE_LIST*$pg;

                if ($pg != $pagina)echo " <a href='".$_SERVER['PHP_SELF']."?pagina=$pg'>[".((($pg-1)*$CFG_PERPAGE_LIST)+1)."-".($fim)."]</a>";
                else echo " [".((($pg-1)*$CFG_PERPAGE_LIST)+1)."-".($fim)."] ";
                
           		if (floor($pg/10) == $b){
               	echo "<BR>";
               	$b++;
		}

		}
        if ($proxima)
                echo "<a href='".$_SERVER['PHP_SELF']."?pagina=".($pagina+1)."'>[&raquo;]</a>";
        else
                echo "<font color=#bbbbbb>[&raquo;]</font>";

        ?>
	</font>
        <BR>
<!--
    <table border="1" width="600">
	<tr><th colspan=4><?=$MSG_LANG["monthlyranking"]?></th>
	</tr>
	<tr>
	<th>&nbsp;</th>
        <th><?=$MSG_LANG["rating"]?></th>
        <th><?=$MSG_LANG["punctuation"]?></th>
	<th><?=$MSG_LANG["player"]?></th>
	</tr>
<?
    $n=1;
	$p=mysql_query("SELECT rating_month,firstName,playerID,rating_max,pontos,rating,lastUpdate from players where lastUpdate>='$tempo' and rating <= 1400 and rating>=900 ativo<>'0' order by rating_month DESC,pontos DESC limit $CFG_RANKING_LIMIT");
        while ($row = mysql_fetch_array($p)){
		echo "
		<tr><td width=1>$n</td>
        	<td>$row[0]</td>
	        <td>$row[pontos]</td>
		<td><a href='stats_user.php?cod=$row[2]'>$row[1]</a></td>
		</tr>";
		$n++;
	}


?>
</table>

<BR>
-->
<table border="1" width="600">
	<tr><th colspan=4>
	<?
		echo $MSG_LANG["ranking"]." ".$MSG_LANG["official"];
		if ($limit != "")
			echo "Top $CFG_RANKING_LIMIT";
	?>
	</th></tr>
	<tr>
	<th>&nbsp;</th>
	<th><?=$MSG_LANG["rating"]?> (<?=$MSG_LANG["max"]?>)</th>
	<th><?=$MSG_LANG["punctuation"]?></th>
	<th><?=$MSG_LANG["player"]?></th>
	</tr>
<?
    $n=$inicio+1;
    
    if ($inicio+$perpage > $CFG_RANKING_LIMIT)
        $perpage = $CFG_RANKING_LIMIT-$inicio;
  // Old Ranking
	
	/*
	// 	
	$p = mysql_query("SELECT rating,firstName,playerID,rating_max,pontos from games,players where ativo<>'0' and pontos>0
	AND (gameMessage='') AND (blackPlayer=playerID OR
	whitePlayer=PlayerID) group by playerID order by rating DESC,rating_month DESC limit $inicio,$perpage");
    */ 
	
$p = mysql_query("SELECT rating,firstName,playerID,rating_max,pontos,lastUpdate from players where lastUpdate>='$tempo' and ativo<>'0' and
		 rating <= 1400 and rating>=900 and pontos>0 order by rating DESC,rating_month DESC limit $inicio,$perpage");
		
		
		while ($row = mysql_fetch_array($p)){
			$subrank = "";
			if ($CFG_ENABLE_SUBRANKING){
				$p2 = mysql_query("select * from medals where playerID='$row[2]'");
				while($row2 = mysql_fetch_array($p2))
	    				$subrank .= "<img alt='".$row2[medal]."' src='images/rank/$row2[medal].gif'>";
			}
			if ($row[2] == $_SESSION['playerID'])
			$cor = "#FFFF00";
			else
			$cor = "#FFFFFF";
			echo "
			<tr><td width=1>$n</td>
	        	<td style='background:$cor'>$row[0] ($row[3])</td>
	        	<td style='background:$cor'>$row[pontos]</td>
			<td style='background:$cor'><a href='stats_user.php?cod=$row[2]'>$row[1]</a> $subrank</td>
			</tr>";
			$n++;
	}


?>
</table>


<form name="logout" action="mainmenu.php" method="post">
	<input type="hidden" name="ToDo" value="Logout">
</form>
<br>
<? include("footer.inc.php");?>
</body>
</html>

<? //mysql_close(); ?>

