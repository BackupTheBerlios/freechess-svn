<?php
##############################################################################################
#                                                                                            #
#                                stats_user.php                                                
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
	require_once('gui.php');

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
?>
<script>
function MessagePlayer(playerID)
		{
				var where="sendmessage.php?<?=$ssname?>=<?=$ssid?>&to="+playerID;
				var height=450;
				var width=500;
				var left=(screen.availWidth/2)-(width/2);
				var top=(screen.availHeight/2)-(height/2)-100;
				window.open(where,"","height="+height+",width="+width+",left="+left+",top="+top,scrollbars="yes");
		}
		function challenge(playerID)
				{
						var where="challenge.php?<?=$ssname?>=<?=$ssid?>&opponent="+playerID;
						var height=500;
						var width=650;
						var left=(screen.availWidth/2)-(width/2);
						var top=(screen.availHeight/2)-(height/2)-100;
						window.open(where,"","height="+height+",width="+width+",left="+left+",top="+top,scrollbars="yes");
				}
				function newuserchallenge(playerID)
				{
						var where="newuserchallenge.php?<?=$ssname?>=<?=$ssid?>&opponent="+playerID;
						var height=500;
						var width=650;
						var left=(screen.availWidth/2)-(width/2);
						var top=(screen.availHeight/2)-(height/2)-100;
						window.open(where,"","height="+height+",width="+width+",left="+left+",top="+top,scrollbars="yes");
				}
</script>

<html>
<head>
	<title>ChessManiac</title>

<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>
<? require_once('header.inc.php');?>
<BR>
<? include("ads/chess.inc.php");?>
<br>



    <table border="1" width="100%">
	<tr><th colspan=3><?=$MSG_LANG["statistics"]?></th>
	</tr>
<?
    $player = $_GET['cod'];
	$gender["m"] = $MSG_LANG["male"];
	$gender["f"] = $MSG_LANG["female"];


        $stats_user = getStatsUser($player,1);
		$stats_user2= getStatsUser($player,0);
		$vitorias = $stats_user[0]+$stats_user2[0];
        $derrotas = $stats_user[1]+$stats_user2[1];
        $empates = $stats_user[2]+$stats_user2[2];
        $ativos = $stats_user[3]+$stats_user2[3];
        $invites = $stats_user[4]+$stats_users2[4];
		$total = $vitorias+$derrotas+$empates;
		$botlimit = $ativos+$invites;

        $p = mysql_query("SELECT * from players where playerID='".$_GET['cod']."'");
        $row = mysql_fetch_array($p);
		$t2 = mysql_query("select * from team_members where fk_player='".$_GET['cod']."' and level>'0'");
		$rt = mysql_fetch_array($t2);
		$rt2= mysql_query("SELECT name from team WHERE teamID='$rt[fk_team]'");
		$rtn = mysql_fetch_array($rt2);
		$teamName =$rtn['name'];

        $nivel = getPlayerLevel($_GET['cod']);
        $PV = getPV($_GET['cod']);

        $mylevel = getPlayerLevel($_SESSION['playerID']);
        $dificuldade = getDifficult($_SESSION['playerID'],$_GET['cod']);

		if ($row['playerID'] == $_SESSION['playerID'])
		    $isme=1;
		else $isme=0;

		echo "
		<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["name"]."</td><td style='text-align:left' width=50%>$row[firstName]</td><td valign='center' rowspan='17' width=30%>";
		show_avatar($row['playerID']);
		echo "</td></tr>";
		echo "
		<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["bio"]."</td><td style='text-align:left' width=50%>$row[bio]</td>";
		echo "
		<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["localization"]."</td><td style='text-align:left' width=50%>$row[cidade], $row[uf] - $row[pais]</td></tr>
		<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["gender"]."</td><td style='text-align:left' width=50%>".$gender[$row[sexo]]."</td></tr>
		";
		//$email = str_replace("@"," at ",$row[email]);
		//$email = str_replace("."," dot ",$email);
		//echo "<tr><td width='20%' style='text-align:left'><B>E-mail</td><td colspan=2 style='text-align:left' width=50%>".$email."</td></tr>";
		echo "<tr><td width='20%' style='text-align:left'><B>Export Games<img src='images/newsm.gif'></td><td style='text-align:left' width=50%><a href=export_all_pgn.php?cod=".$_GET['cod'].">Export all games as a pgn file.</a> </td></tr>";
		if ($row['DisplayBot']  == 1)
		echo "<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["sendmessage"]."</td><td style='text-align:left'><font size=+1 font color='RED'>".$MSG_LANG['playwiththecomputer']."</font><img alt='".$MSG_LANG['playwiththecomputer']."' src='images/icons/bots.gif' width='22' height='22'> </td>
		</td></tr>";
		else if (!$isme)
		echo "<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["sendmessage"]."</td><td style='text-align:left'><input type='button' style='font-size:11' value='$MSG_LANG[sendmessage]' onClick=\"javascript:MessagePlayer(". $row['playerID'].")\">                 </td>
		</td></tr>";
		if (getRating($_SESSION['playerID']) == 0 && !$isme)
 			{
			echo "<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["challengethisplayer"]."</td><td style='text-align:left'><input type='button' style='font-size:11' value='$MSG_LANG[invite]' onClick=\"javascript:newuserchallenge(".$row['playerID'].")\">                 </td></td></tr>";				
            }
 		elseif ($row['DisplayBot']  == 1 && $botlimit<10)
			{
			echo "<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["challengethisplayer"]."</td><td style='text-align:left'><input type='button' style='font-size:11' value='$MSG_LANG[challengethisplayer]' onClick=\"javascript:newuserchallenge(".$row['playerID'].")\">                 </td></td></tr>";				
            }		
		elseif ($row['DisplayBot']  == 1 && $botlimit>=10)
			{
			echo "<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["challengethisplayer"]."</td><td style='text-align:left'>I have reached my 10 active game limit!</td></td></tr>";				
            }

		elseif (getRating($row['playerID']) == 0 && !$isme)
 			{
			echo "<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["challengethisplayer"]."</td><td style='text-align:left'><input type='button' style='font-size:11' value='$MSG_LANG[challengethisplayer]' onClick=\"javascript:newuserchallenge(".$row['playerID'].")\">                 </td></td></tr>";				
            }				
		elseif (!$isme)
			{
			echo "<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["challengethisplayer"]."</td><td style='text-align:left'><input type='button' style='font-size:11' value='$MSG_LANG[challengethisplayer]' onClick=\"javascript:challenge(".$row['playerID'].")\">                 </td></td></tr>";
			}
		echo "<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["level"]."</td><td style='text-align:left'>".$nivel."</td></tr>";
		echo "<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["rating"]."/".$MSG_LANG["max"]."</td><td style='text-align:left'>$row[rating]/$row[rating_max] (<B>".$MSG_LANG["nextlevel"].":</b> ".getXPmax($nivel).")</td></tr>";

        if (!$isme){

            $xpw = getXPW(getRating($_SESSION['playerID']),$row['rating'],getPV($_SESSION['playerID']));
            $xpl = getXPL($row['rating'],getRating($_SESSION['playerID']),getPV($_GET['cod']));

            echo "<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["challengerate"]."</td><td style='text-align:left'>$dificuldade (<i>".$MSG_LANG["ifwin"].": +$xpw ".$MSG_LANG["iflose"].": -$xpl</i>)</td></tr>";
			echo "<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["lastseen"]."</td><td style='text-align:left' width=50%>".date("d/m/y H:i",$row[lastUpdate])."</td></tr>";
        }
		echo "<tr><td nowrap width='20%' style='text-align:left'><B>".$MSG_LANG["finishedgames"]."</td><td style='text-align:left'>$total</td></tr>
		<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["victories"]."</td><td style='text-align:left'>$vitorias ($PV%)</td></tr>
		<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["defeats"]."</td><td style='text-align:left'>$derrotas</td></tr>
		<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["draw"]."</td><td style='text-align:left'>$empates</td></tr>
		<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["activegames"]."</td><td style='text-align:left'><a href=games_user.php?cod=$player>$ativos games</a></td></tr>";
		if (mysql_num_rows($t2) >0)
		echo "<tr><td width='20%' style='text-align:left'><B>TEAM</td><td style='text-align:left'><a href='stats_team.php?cod=".$rt[fk_team]."'><b>".$teamName."</b></a></td></tr><br>";
		else 		
		echo "<tr><td width='20%' style='text-align:left'><B>TEAM</td><td style='text-align:left'><a href='teams.php'><b>".$MSG_LANG['chooseateamtojoin']."</b></a></td></tr><br>";

		
		                                                             
		if ($CFG_ENABLE_SUBRANKING){
			 $subrank = "";
                         $p2 = mysql_query("select * from medals where playerID='$_GET[cod]'");
                         while($row2 = mysql_fetch_array($p2))
 	                        $subrank .= "<img alt='".$row2[medal]."' src='images/rank/$row2[medal].gif'> ";

			echo "<tr><td width='20%' style='text-align:left'><B>".$MSG_LANG["medals"]."</td><td style='text-align:left'>$subrank</td></tr></table><br>
<table><tr><td><img width=400 height=160 src='show_user_".$CFG_GRAPH_SHOW."_graph.php?player=$player&language=".$_SESSION['pref_language']."'></td><td>
<img width=400 height=160 src='show_user_".$CFG_GRAPH_SHOW2."_graph.php?player=$player&language=".$_SESSION['pref_language']."'></td></tr></table><br>"; } 



?>


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

$rs = mysql_query("SELECT count(*) from games where (whitePlayer='".$_GET['cod']."' OR blackPlayer='".$_GET['cod']."') AND gameMessage<>'' AND  gameMessage<>'playerInvited' AND gameMessage<>'inviteDeclined'");

$row = mysql_fetch_array($rs);
$ultima = ceil($row[0]/$perpage);

if ($row[0] <= $fim)$proxima=FALSE;
else $proxima=TRUE;

########################

	if ($pagina-1 >0)
		echo "<a href='".$_SERVER['PHP_SELF']."?cod=$player&voltar=$voltar&pagina=".($pagina-1)."'>[&laquo;]</a>";
	else
		echo "<font color=#bbbbbb>[&laquo;]</font>";

	for ($pg=1 ; $pg<=$ultima ; $pg++)
		if ($pg != $pagina)echo " <a href='".$_SERVER['PHP_SELF']."?cod=$player&voltar=$voltar&pagina=$pg'>[$pg]</a>";
		else echo " [$pg] ";

	if ($proxima)
		echo "<a href='".$_SERVER['PHP_SELF']."?cod=$player&voltar=$voltar&pagina=".($pagina+1)."'>[&raquo;]</a>";
	else
		echo "<font color=#bbbbbb>[&raquo;]</font>";

	?>
	</font>

         <BR><BR>


<table border="1" width="100%">
<TR><Th colspan=11><?=$MSG_LANG["history"]?></td></tr>
<tr>
    <th><?=$MSG_LANG["gameno"]?></th>
    <th><?=$MSG_LANG["tournament"]?></th>
    <th><?=$MSG_LANG["opponent"]?></th>
    <th><?=$MSG_LANG["status"]?></th>
    <th><?=$MSG_LANG["situation"]?></th>
    <th><?=$MSG_LANG["turns"]?></th>
    <th><?=$MSG_LANG["ending"]?></th>
    <th><?=$MSG_LANG["duration"]?></th>
    <th><?=$MSG_LANG["punctuation"]?></th>
    <th><?=$MSG_LANG["official"]?></th>
    <th>&nbsp;</th>
</tr>
<?
    $status["draw"] = $MSG_LANG["draw2"];
    $status["playerResigned"] = $MSG_LANG["resigned"];
    $status["checkMate"] = $MSG_LANG["checkmate"];
    $status["flagFall"] = $MSG_LANG["theflaghasfallen"];

    $calcRating = 0;

    $p = mysql_query("SELECT games.*,DATE_FORMAT(dateCreated, '%d/%m/%y %H:%i') as inicio, DATE_FORMAT(lastMove, '%d/%m/%y %H:%i') as fim from games where (whitePlayer='".$_GET['cod']."' OR blackPlayer='".$_GET['cod']."') AND gameMessage<>'' AND  gameMessage<>'playerInvited' AND gameMessage<>'inviteDeclined' ORDER BY lastMove DESC LIMIT $inicio,$perpage");
    if (mysql_num_rows($p)==0){
        echo "<tr><td colspan=10 align=center>".$MSG_LANG['nogames']."</td></tr>";
    }else{
    while ($row = mysql_fetch_array($p)){

        if ($row['whitePlayer'] != $_GET['cod']){
            $player = $row['whitePlayer'];
            $cor = $MSG_LANG["black"];
        }
        else{
            $player = $row['blackPlayer'];
            $cor = $MSG_LANG["white"];
        }

        $p2 = mysql_query("SELECT count(*) from history WHERE gameID=$row[gameID]");
        $row2 = mysql_fetch_array($p2);
        $rounds = ceil($row2[0]/2);

        //duracao:
        $v = explode(" ",$row[inicio]);
        $hora = explode(":",$v[1]);
        $data = explode("/",$v[0]);
        $inicio = mktime($hora[0],$hora[1],0,$data[1],$data[0],$data[2]);

        $v = explode(" ",$row[fim]);
        $hora = explode(":",$v[1]);
        $data = explode("/",$v[0]);
        $fim = mktime($hora[0],$hora[1],0,$data[1],$data[0],$data[2]);

        $d = floor(($fim-$inicio)/60/60/24);
        $h = floor(($fim-$inicio)/60/60) - 24*$d;
        $m = round(($fim-$inicio)/60) - 60*$h - $d*24*60;

        $duracao="";

        if ($d==1)
            $duracao .= "$d $MSG_LANG[day] ";
        else if ($d>1)
            $duracao .= "$d $MSG_LANG[days] ";
        if ($h>0)
             $duracao .= "$h hrs. ";
        if ($m>0)
            $duracao .= "$m mins.";

            if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "white" && $player == $row['whitePlayer'])
			     $situacao = "win";
            else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "black" && $player == $row['blackPlayer'])
			     $situacao = "win";
            else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "white" && $player == $row['blackPlayer'])
			     $situacao = "lost";
            else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "black" && $player == $row['whitePlayer'])
			     $situacao = "lost";
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "white" && $player == $row['whitePlayer'])
			     $situacao = "lost";
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "black" && $player == $row['blackPlayer'])
			     $situacao = "lost";
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "white" && $player == $row['blackPlayer'])
			     $situacao = "win";
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "black" && $player == $row['whitePlayer'])
			     $situacao = "win";
            else if ($row['gameMessage'] == "draw")
			     $situacao = "draw";
            else
                $situacao = $row[gameID]." ".$row['gameMessage'];


        $p2 = mysql_query("SELECT firstName from players WHERE playerID='$player'");
        $row2 = mysql_fetch_array($p2);

        $xp=0;

        if($situacao == "win"){
            $situacao_text = "<font color=green>".$MSG_LANG["win"]."</font>";

		$xp = $row[xpw];
        }
        else if($situacao == "lost"){
            $situacao_text = "<font color=red>".$MSG_LANG["lost"]."</font>";
            if ($row[xpl] > 0)$xp = "-$row[xpl]";
	    else if ($row[xpl] == 0)$xp = $row[xpl];
	    else $xp = abs($row[xpl]);
        }
        else if($situacao == "draw"){
            $situacao_text = "<font color=blue>".$MSG_LANG["draw2"]."</font>";
            if ($row['messageFrom'] == "white" && $player == $row['whitePlayer'])
                $xp = $row[xpl];
            else if ($row['messageFrom'] == "white" && $player == $row['blackPlayer'])
                $xp = $row[xpw];
            else if ($row['messageFrom'] == "black" && $player == $row['blackPlayer'])
                $xp = $row[xpl];
            else if ($row['messageFrom'] == "black" && $player == $row['whitePlayer'])
                $xp = $row[xpw];

        }
        if ($row[xpw]=="" || $row[xpl] =="")
            $xp="0";

	if ($xp > 0)
		$xp = "+$xp";


        if ($row['oficial'] == "1")
            $oficial = $MSG_LANG["yes"];
        else $oficial = $MSG_LANG["no"];

        $calcRating = $calcRating + $xp;

		if ($row['flagFall'])
			$row['gameMessage'] = "flagFall";

		if ($row['tournament'] != 0)
        {
        		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
                $t['name'] = stripslashes($t['name']);
                $t['name'] = '<a href="tournaments.php?action=view&id='.$t['id'].'">'.$t['name'].'</a>';
        }
        else
        {
        		$t['name'] = "No";
        }

        echo "<tr>
                <td align='center'>$row[gameID]</td>
                <td aling='center'>$t[name]</td>
                <td align='center'>$row2[firstName]</td>
                <td align='center'>".$status[$row['gameMessage']]."</td>
                <td align='center'>$situacao_text</td>
                <td align='center'>$rounds</td>
                <td align='center'>$row[fim]</td>
                <td align='center'>$duracao</td>
                <td align='center'>$xp</td>
                <td align='center'>$oficial</td>
                <td align='center'>
				<input style='font-size:11' type=button value='".$MSG_LANG["details"]."' onClick='document.existingGames.gameID.value=$row[gameID];document.existingGames.submit()'>
				</td>
              </tr>";
    }
    }
?>
</table>

<font color=#DDDDDD>Page Rating: <?=$calcRating?></font>

<form name="existingGames" action="chess.php" method="post">
        <input type='hidden' name='rdoShare' value='no'>
		<input type="hidden" name="gameID" value="">
		<input type="hidden" name="sharePC" value="no">
        <input type="hidden" name="statsUser" value="<?=$cod?>">
</form>


<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>
<? include("footer.inc.php");?>
</body>
</html>

<? //mysql_close(); ?>
