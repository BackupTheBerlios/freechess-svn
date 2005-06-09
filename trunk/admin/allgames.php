<?php
##############################################################################################
#                                                                                            #
#                                allgames.php                                                
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

	/* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
	fixOldPHPVersions();

	/* if this page is accessed directly (ie: without going through login), */
	/* player is logged off by default */
	if (!isset($_SESSION['playerID']))
		$_SESSION['playerID'] = -1;
	
	/* connect to database */
	require '../connectdb.php';


	/* check session status */
	require '../sessioncheck.php';

	/* Language selection */
	require "../languages/".$_SESSION['pref_language']."/strings.inc.php";
	
if (!isset($_GET['voltar']))
    $voltar = "index.php";
else $voltar = $_GET['voltar'];

if (!isset($_POST['action'])){
    $_POST['search_status_a'] =1;
    $_POST['search_status_f'] =1;
}

$search = "WHERE (gameMessage<>'playerInvited' && gameMessage<>'inviteDeclined')";

if ($_POST['search_game'] != '')
    $search .= " AND gameID='".$_POST['search_game']."'";


if ($_POST['search_status_a'] == '1' && $_POST['search_status_f'] == '1')
    $search .= "";
else if ($_POST['search_status_a'] == '1')
    $search .= " AND gameMessage = ''";
else if ($_POST['search_status_f'] == '1')
    $search .= " AND gameMessage <> ''";

if ($_POST['search_status_a'] =='1')
    $status0="checked";
if ($_POST['search_status_f'] =='1')
    $status1="checked";
$playerID = $_SESSION['playerID'];

 //function verify_Admin($admin, $playerID){
		//$p = mysql_query("SELECT admin FROM players WHERE admin= $admin AND $playerID='$_SESSION['playerID']'");
	//	$r = mysql_fetch_array($p);

		//if ($r[admin] == 0){
		//    echo "ERROR: You aren´t an Administrator!";
		//	exit;
		//}
   
//}
?>

<html>
<head>
	<title>ChessManiac</title>

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

if ($_POST['search_player'] != '')
    $rs = mysql_query("SELECT count(*) from games,players ".$search." AND firstName like '%".$_POST['search_player']."%' AND (whitePlayer=playerID OR blackPlayer=playerID)");
else
    $rs = mysql_query("SELECT count(*) from games ".$search);

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
<form method=post action=allgames.php>
<input type=hidden name=action value='search'>
<table border="1" width="700">
<TR><Th colspan=3><?=$MSG_LANG["search"]?></th></tr>
<TR>
    <td align=center>Game#: <input type=text name=search_game size=5 value="<?=$_POST['search_game']?>"></td>
    <td align=center>Player: <input type=text name=search_player value="<?=$_POST['search_player']?>"></td>
    <td align=center>Status: <input type=checkbox name=search_status_a value='1' <?=$status0?>>Active <input type=checkbox name=search_status_f value='1' <?=$status1?>>Finished</td>
</tr>
<TR><Td align=center colspan=3><input type=submit value="search"></td></tr>
</table>
</form>


<table border="1" width="700">
<TR><Th colspan=7>All Games</th></tr>
<tr>
    <th><?=$MSG_LANG["gameno"]?></th>
    <th><?=$MSG_LANG["players"]?></th>
    <th><?=$MSG_LANG["start"]?></th>
    <th><?=$MSG_LANG["lastmove"]?></th>
    <th><?=$MSG_LANG["rounds"]?></th>
    <th><?=$MSG_LANG["status"]?></th>
    <th>&nbsp;</th>
</tr>
<?
if ($_POST['search_player'] != '')
    $p = mysql_query("SELECT games.*,DATE_FORMAT(dateCreated, '%d/%m/%y') as inicio,DATE_FORMAT(lastMove, '%d/%m/%y') as fim from games,players $search AND firstName like '%".$_POST['search_player']."%' AND (whitePlayer=playerID OR blackPlayer=playerID) ORDER BY gameMessage,dateCreated DESC LIMIT $inicio,$perpage");
else
    $p = mysql_query("SELECT games.*,DATE_FORMAT(dateCreated, '%d/%m/%y') as inicio,DATE_FORMAT(lastMove, '%d/%m/%y') as fim from games $search ORDER BY gameMessage,dateCreated DESC LIMIT $inicio,$perpage");

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

        if ($row['gameMessage'] == "")
            $status = $MSG_LANG['active'];
        else
            $status = $MSG_LANG['finished'];

        echo "<tr>
                <td align='center'>$row[gameID]</td>
                <td align='center'>$white x $black</td>";
        //<td align='center'><a href='../stats_user.php?cod=$idw&voltar=adm/allgames.php'>$white</a> x <a href='../stats_user.php?cod=$idb&voltar=adm/allgames.php'>$black</a></td>
        echo "<td align='center'>$row[inicio]</td>
                <td align='center'>$row[fim]</td>
                <td align='center'>$rounds</td>
                <td align='center'>$status</td>
                <td align='center'><input style='font-size:11' type=button value='".$MSG_LANG["edit"]."' onClick=\"window.location='editgame.php?gameID=$row[gameID]'\"></td>
              </tr>";
    }
    }



?>
</table>
<form name="existingGames" action="chess.php" method="post">
        <input type='hidden' name='rdoShare' value='no'>
		<input type="hidden" name="gameID" value="">
		<input type="hidden" name="sharePC" value="no">
</form>

<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>


</body>
</html>

<? //mysql_close(); ?>

