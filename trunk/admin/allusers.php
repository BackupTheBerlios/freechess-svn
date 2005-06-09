<?php
##############################################################################################
#                                                                                            #
#                               allusers.php                                                
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
	//require '../chessdb.php';
	/* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
	fixOldPHPVersions();

	/* if this page is accessed directly (ie: without going through login), */
	/* player is logged off by default */
	if (!isset($_SESSION['playerID']))
		$_SESSION['playerID'] = -1;
	
	/* connect to database */
	require '../connectdb.php';

	/* chessdb */
	require '../chessdb.php';


	/* check session status */
	require '../sessioncheck.php';

	/* Language selection */
	require "../languages/".$_SESSION['pref_language']."/strings.inc.php";
	
if (!isset($_GET['voltar']))
    $voltar = "index.php";
else $voltar = $_GET['voltar'];

$search = "WHERE 1";
$search_player = $_POST['search_player'];

?>

<html>
<head>
	<title>WebChess</title>

<style>
TABLE   {font-size:11; font-family: verdana; background: #cfcfbb;}
.BOTOES {width:100; background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;}
TD      {background:white;}
</style>
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>
<? require_once('header.inc.php');?>
<BR>
	<font face=arial size=2><?=$MSG_LANG['page']?>:
	<?
#########################
#      Page control     #
#########################

$perpage = 30;

if ($pagina=="")
	$pagina=1;

$inicio = $perpage * $pagina - $perpage;
$fim = $perpage * $pagina;

if ($search_player != "")
	$search = "WHERE firstName like '%$search_player%'";
	
$rs = mysql_query("SELECT count(*) from players ".$search);

$row = mysql_fetch_array($rs);
$ultima = ceil($row[0]/$perpage);

if ($row[0] <= $fim)$proxima=FALSE;
else $proxima=TRUE;

########################
	
	if ($pagina-1 >0)
		echo "<a href='".$_SERVER['PHP_SELF']."?cod=$player&voltar=$voltar&pagina=".($pagina-1)."'>[&laquo;]</a>";
	else
		echo "<font color=#bbbbbb>[&laquo;]</font>";

	$b=1;
    for ($pg=1 ; $pg<=$ultima ; $pg++){
		if ($pg != $pagina)echo " <a href='".$_SERVER['PHP_SELF']."?cod=$player&voltar=$voltar&pagina=$pg'>[$pg]</a>";
		else echo " [$pg] ";
		if (floor($pg/25) == $b){
            echo "<BR>";
            $b++;
        }
	}

	if ($proxima)
		echo "<a href='".$_SERVER['PHP_SELF']."?cod=$player&voltar=$voltar&pagina=".($pagina+1)."'>[&raquo;]</a>";
	else
		echo "<font color=#bbbbbb>[&raquo;]</font>";

	?>
	</font>
<BR><BR>
<form method=post action=allusers.php>
<input type=hidden name=action value='search'>
<table border="1" width="100%">
<TR><Th colspan=3><?=$MSG_LANG["search"]?></th></tr>
<TR>
    <td align=center>Player Name: <input type=text name=search_player size=5 value="<?=$_POST['search_player']?>"></td>
</tr>
<TR><Td align=center colspan=3><input type=submit value="search"></td></tr>
</table>
</form>


<table border="1" width="100%">
<TR><Th colspan=5><?=$MSG_LANG["allusers"]?></th></tr>
<tr>
    <th><?=$MSG_LANG["name"]?></th>
    <th><?=$MSG_LANG["nick"]?></th>
    <th><?=$MSG_LANG["rating"]?></th>
    <th>&nbsp;</th>
</tr>
<?

    $p = mysql_query("SELECT * from players $search  ORDER BY firstName LIMIT $inicio,$perpage");

    if (mysql_num_rows($p)>0){
    while ($row = mysql_fetch_array($p)){

		$truerating = countRating($row[playerID]);

		echo "<tr>
                <td align='center'  style='text-align: left'><a href='../stats_user.php?cod=$row[playerID]'>$row[firstName]</a></td>
                <td align='center'>$row[nick]</td>";

		if ($row[rating] != $truerating)
                echo "<td align='center'  style='text-align: left'><B><a title='The rating would be $truerating'><font color='$cor'>$row[rating]</font></B> <a href='fixuser.php?player=$row[playerID]'>fix</a></td>";
		else
				echo "<td align='center' style='text-align: left'>$row[rating]</td>";
		echo "
		        <td align='center'><input style='font-size:11' type=button value='".$MSG_LANG["edit"]."' onClick=\"window.location='editplayer.php?player=$row[playerID]'\"></td>
              </tr>";
    }
}


?>
</table>
<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>


</body>
</html>

<? //mysql_close(); ?>

