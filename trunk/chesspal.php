<?php
##############################################################################################
#                                                                                            #
#                                chesspal.php                                                
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
	
	require('ob.lib.php');
	if ($COMPRESSION) {
		$ob_mode = PMA_outBufferModeGet();
	        if ($ob_mode) {
        	        PMA_outBufferPre($ob_mode);
	        }
        }
                    

	/* define constants */
	require_once ( 'chessconstants.php');

	/* include outside functions */
	if (!isset($_CHESSUTILS))
		require_once ( 'chessutils.php');
	require_once('gui.php');
	require_once('chessdb.php');
	require 'move.php';
	require 'undo.php';
    require_once ( 'newgame.php');
	/* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
	fixOldPHPVersions();

	/* check session status */
	require_once('sessioncheck.php');

	/* Language selection */
	require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");


    /* if this page is accessed directly (ie: without going through login), */
	/* player is logged off by default */
	if (!isset($_SESSION['playerID']))
		$_SESSION['playerID'] = -1;
		
	/* check if loading game */
	if (isset($_POST['gameID']))
		$_SESSION['gameID'] = $_POST['gameID'];

	/* debug flag */
	define ("DEBUG", 0);

	/* connect to database */
	require_once( 'connectdb.php');

	/* load game */
	$isInCheck = ($_POST['isInCheck'] == 'true');
	$isCheckMate = false;
	$isPromoting = false;
	$isUndoing = false;

	loadHistory();
	loadGame();

    $p = mysql_query("SELECT * from games WHERE gameID='".$_SESSION['gameID']."'");
    if (mysql_num_rows($p) ==0){
	   //The game was deleted
       echo "<script>window.location='mainmenu.php'</script>";	
	   exit;
    }
    $row = mysql_fetch_array($p);
    $white = $row['whitePlayer'];
    $black = $row['blackPlayer'];
    $timeLimit = $row['timelimit'];
    $flagFall = $row['flagFall'];
    $oficial = $row['oficial'];

	if ($row['whitePlayer'] == $_SESSION['playerID'])
	{
		$MyRating = $row['ratingWhite'];
		$OpponentRating = $row['ratingBlack'];
		$MyPV = $row['PVWhite'];
		$OpponentPV = $row['PVBlack'];
	}
	else
	{
		$MyRating = $row['ratingBlack'];
		$OpponentRating = $row['ratingWhite'];
		$MyPV = $row['PVBlack'];
		$OpponentPV = $row['PVWhite'];

	}

	processMessages();

    /* Verify permission */
    if (!isBoardDisabled()){
        if (($white != $_SESSION['playerID']) && ($black != $_SESSION['playerID']))
            die($MSG_LANG["youdonthavepermission"]);
    }

        if (($numMoves == -1) || ($numMoves % 2 == 1)){
			$mycolor2 = "white";
		}
		else{
			$mycolor2 = "black";
		}

		/* find out if it's the current player's turn */
		if (( (($numMoves == -1) || (($numMoves % 2) == 1)) && ($playersColor == "white"))
			|| ((($numMoves % 2) == 0) && ($playersColor == "black")))
			$isPlayersTurn = true;
		else
			$isPlayersTurn = false;

		if ($white == $_SESSION['playerID'])
			$opponent = $black;
		else $opponent = $white;


		if (!isBoardDisabled() && !$isCheckMate && $timeLimit > 0)
			if (tempoEsgotado($mycolor2)){
			    saveRanking($_SESSION['gameID'],"resign",$mycolor2,1);
			    updateTimestamp();
			    
			    // Update the opponent time
				if ($mycolor2 == "white")
					mysql_query("UPDATE games set timeWhite=$timeLimit*60 WHERE gameID=".$_SESSION['gameID']);
			    else
					mysql_query("UPDATE games set timeBlack=$timeLimit*60 WHERE gameID=".$_SESSION['gameID']);
					
				echo "<script>
				alert('".$MSG_LANG["theflaghasfallen"]." $mycolor2 $MSG_LANG[lost]');
				window.location='chess.php';
				</script>\n";
				exit;
			}

	if ($isUndoing)
	{
		doUndo();
		saveGame();
	}
    
	else if ($CFG_ENABLE_CHAT && isset($_POST['chat_msg'])){
        if ($_POST[chat_msg] != ""){
	    if (!get_magic_quotes_gpc())
		$_POST[chat_msg] = addslashes($_POST[chat_msg]);
		$_POST[chat_msg] = wordwrap($_POST[chat_msg], 35, " ", 1);
        mysql_query("insert into chat (fromID,msg,gameID) VALUES ('".$_SESSION[playerID]."','".$_POST[chat_msg]."','".$_SESSION[gameID]."')");
	}
    }
	elseif (($_POST['promotion'] != "") && ($_POST['toRow'] != "") && ($_POST['toCol'] != ""))
	{
		savePromotion();
		$board[$_POST['toRow']][$_POST['toCol']] = $_POST['promotion'] | ($board[$_POST['toRow']][$_POST['toCol']] & BLACK);
		saveGame();
	}
	elseif (($_POST['fromRow'] != "") && ($_POST['fromCol'] != "") && ($_POST['toRow'] != "") && ($_POST['toCol'] != ""))
	{
		/* ensure it's the current player moving				 */
		/* NOTE: if not, this will currently ignore the command...               */
		/*       perhaps the status should be instead?                           */
		/*       (Could be confusing to player if they double-click or something */
		$tmpIsValid = true;
		
		if (($numMoves == -1) || ($numMoves % 2 == 1))
		{
			/* White's move... ensure that piece being moved is white */
			if ((($board[$_POST['fromRow']][$_POST['fromCol']] & BLACK) != 0) || ($board[$_POST['fromRow']][$_POST['fromCol']] == 0))
				/* invalid move */
				$tmpIsValid = false;
		}
		else
		{
			/* Black's move... ensure that piece being moved is black */
			if ((($board[$_POST['fromRow']][$_POST['fromCol']] & BLACK) != BLACK) || ($board[$_POST['fromRow']][$_POST['fromCol']] == 0))
				/* invalid move */
				$tmpIsValid = false;
		}

		if ($tmpIsValid)
		{
			saveHistory();
			doMove();
			saveGame();
		}
	}

?>
<html>
<head>
<!-- gameID: <?=$_SESSION[gameID]?> -->

<?

		/* find out if it's the current player's turn */
		if (( (($numMoves == -1) || (($numMoves % 2) == 1)) && ($playersColor == "white"))
			|| ((($numMoves % 2) == 0) && ($playersColor == "black")))
			$isPlayersTurn = true;
		else
			$isPlayersTurn = false;

		if ($white == $_SESSION['playerID'])
			$opponent = $black;
		else $opponent = $white;
		
	if ($_SESSION['isSharedPC'])
		echo("<title>WebChess</title>\n");
	else if ($isPlayersTurn)
		echo("<title>WebChess - ".$MSG_LANG["yourturn"]."</title>\n");
	else
		echo("<title>WebChess - ".$MSG_LANG["opponentturn"]."</title>\n");
	
	echo("<meta HTTP-EQUIV='Pragma' CONTENT='no-cache'>\n");
	echo '<meta http-equiv="cache-control" content="no-cache">';
	echo '<meta http-equiv="expires" content="-1">';
	
	/* if it's not the player's turn, enable auto-refresh */
	/*
    if (!$isPlayersTurn && !isBoardDisabled() && !$_SESSION['isSharedPC'])
	{
		echo ("<META HTTP-EQUIV=Refresh CONTENT='");

		if ($_SESSION['pref_autoreload'] >= $CFG_MINAUTORELOAD)
			echo ($_SESSION['pref_autoreload']);
		else
			echo ($CFG_MINAUTORELOAD);

		echo ("; URL=chess.php?autoreload=yes'>\n");
	}
    */	
?>
<script type="text/javascript">
<? writeJSboard(); ?>
<? writeJShistory(); ?>

if (DEBUG)
	alert("Game initilization complete!");

stopTimer = 0;
function refreshwindow(){
    if (stopTimer == 0)
        window.location='chess.php';
}
		function loadnextGame(gameID)
		{
			//if (document.allhisgames.rdoShare[0].checked)
			//	document.allhisgames.action = "opponentspassword.php";

			document.allhisgames.gameID.value = gameID;
			document.allhisgames.submit();
		}
		function sendResponse(responseType, messageFrom, gameID)
		{
			document.responseToInvite.response.value = responseType;
			document.responseToInvite.messageFrom.value = messageFrom;
			document.responseToInvite.gameID.value = gameID;
			document.responseToInvite.submit();
		}
		
		
</script>
<script language="JavaScript">
<!-- Hide the script from old browsers --
function loadalert () 
{alert("Don't forget to bookmark our site Javascript-library.com")
}
// --End Hiding Here -->
</script>



<script type="text/javascript" src="javascript/chessutils<?=$JAVASCRIPT_EXT?>">
 /* these are utility functions used by other functions */
</script>

<script type="text/javascript" src="javascript/commands<?=$JAVASCRIPT_EXT?>">
// these functions interact with the server
</script>

<script type="text/javascript" src="javascript/validation<?=$JAVASCRIPT_EXT?>">
// these functions are used to test the validity of moves
</script>

<script type="text/javascript" src="javascript/isCheckMate<?=$JAVASCRIPT_EXT?>">
// these functions are used to test the validity of moves
</script>

<script type="text/javascript" src="javascript/squareclicked<?=$JAVASCRIPT_EXT?>">
// this is the main function that interacts with the user everytime they click on a square
</script>

<script language="JavaScript" type="text/javascript" src="http://vhost.oddcast.com/vhost_embed_functions.php?acc=10552&js=1"></script>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/board.css" type="text/css">

</head>

<BODY bgcolor=white text=black marginheight=10 marginwidth=10 topmargin=10 leftmargin=10>


<table border="0" width=100% align=left>
	<?PHP
	if ($row['tournament'] != 0)
	{
	    echo "<tr><td align=center><font face=verdana>";
		$t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
		$t['name'] = stripslashes($t['name']);
		?>
		<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
		</td><td>&nbsp;</td></tr>
	<?}?>
<tr>
	<td align=center>
	<?
        $p = mysql_query("SELECT firstName,playerID,engine FROM players WHERE playerID='$white'");
        $row = mysql_fetch_array($p);
        $p = mysql_query("SELECT firstName,playerID,engine FROM players WHERE playerID='$black'");
        $row2 = mysql_fetch_array($p);
		$row[0] = $row[0]." (".$MSG_LANG["white"].")";
		$row2[0] = $row2[0]." (".$MSG_LANG["black"].")";
        echo "<input type='button' name='btnWhiteUser' value='$row[0]' onClick=\"window.open('stats_user.php?cod=$row[1]&voltar=chess.php', '_self')\">";
        echo " <B>X</B> ";
        echo "<input type='button' name='btnBlackUser' value='$row2[0]' onClick=\"window.open('stats_user.php?cod=$row2[1]&voltar=chess.php', '_self')\">";
        #echo "<font face=verdana size=2><a href='stats_user.php?cod=$row[1]&voltar=chess.php'>$row[0]</a> <B>x</B> <a href='stats_user.php?cod=$row2[1]&voltar=chess.php'>$row2[0]</a></font>";
        if ($row["engine"] >= 1 || $row2["engine"] >= 1)
            $isEngine=true;

        $p = mysql_query("SELECT value FROM preferences WHERE preference='language' AND playerID=$opponent");
        $r = mysql_fetch_array($p);
	if ($_SESSION['pref_language'] != $r[0])
		$opponent_language = strtoupper($r[0]);
	else $opponent_language = "";
	?>
	</td>
	<td align=center>&nbsp;</td>
</tr>
<tr>
	<td colspan=2>&nbsp;</td>
</tr>
<tr valign="top" align="center">
<td>
	<form name="gamedata" method="post" action="chess.php">
    <input type=hidden name="gameID" value="<?=$_SESSION["gameID"]?>">
	<?

		/* Verify if the promotion process was completed */
		if ($playersColor == "white"){
			$promotionRow = 7;
			$otherRow = 0;
		}
		else{
			$promotionRow = 0;
			$otherRow = 7;
		}

		for ($i = 0; $i < 8; $i++)
			if (($board[$promotionRow][$i] & COLOR_MASK) == PAWN){
				$isPromoting = true;
				$p = mysql_query("SELECT * FROM history where toCol='$i' AND toRow='$promotionRow' AND curPiece='pawn' and curColor='$playersColor' and gameID='$_SESSION[gameID]' ORDER BY timeOfMove DESC limit 1");
				$row = mysql_fetch_array($p);
				$_POST['fromRow'] = $row[fromRow];
				$_POST['fromCol'] = $row[fromCol];
				$_POST['toRow'] = $row[toRow];
				$_POST['toCol'] = $row[toCol];
			}

		for ($i = 0; $i < 8; $i++)
			if (($board[$otherRow][$i] & COLOR_MASK) == PAWN){
				writeWaitPromotion();		
				$isPromoting = 1;
				$otherTurn = 1;
			}

		if ($isPromoting && !$otherTurn)
			writePromotion();
	?>

	<?
		if ($isUndoRequested)
			writeUndoRequest();
	?>

	<?
		if ($isDrawRequested)
			writeDrawRequest();

	?>

	<? drawboard(); ?>

	<!-- table border="0">
	<tr><td -->
	<BR>
	<nobr>
	<input name="button2" type="button" class="BOTOES" onClick='document.logout.submit()' value="<?=$MSG_LANG["logoff"]?>"">
	<input type="button" name="btnReload" value="<?=$MSG_LANG["refresh2"]?>" onClick="window.open('chess.php', '_self')">
	<input name="button3" type="button" onClick="window.open('analyze.php?whocolor=<?=$playersColor?>&game=<?=$_SESSION['gameID']?>', '_blank','location=no,toolbar=no,directories=no,menubar=no,resizable=no,status=no,scrollbars=yes,width=600,height=550')" value="<?=$MSG_LANG["analyze"]?>">
	<? if ($CFG_ENABLE_UNDO){ ?>
    <input type="button" name="btnUndo" value="<?=$MSG_LANG["undomove"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); else echo ("onClick='undo(\"".$MSG_LANG["undowarning"]."\")'"); ?>>
	<?}?>
	<input type="button" name="btnResign" value="<?=$MSG_LANG["resign"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); else echo ("onClick='resigngame($CFG_MIN_ROUNDS,\"".$MSG_LANG["roundwarning"]."\")'"); ?>>
	<input type="button" name="btnDraw" value="<?=$MSG_LANG["askdraw"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); else echo ("onClick='draw($CFG_MIN_ROUNDS,\"".$MSG_LANG["roundwarning"]."\")'"); ?>>
	<?if (isset($_POST['statsUser'])){ ?>
        <input type="button" name="btnMainMenu" value="<?=$MSG_LANG["back"]?>" onClick="window.open('stats_user.php?cod=<?=$_POST['statsUser']?>', '_self')">
    <?}else{ ?>
        <input type="button" name="btnMainMenu" value="<?=$MSG_LANG["main"]?>" onClick="window.open('mainmenu.php', '_self')">
    <?}?>	
    <!-- <input type="button" name="btnLogout" value="Sair" onClick="logout()">
    -->
	<input type="hidden" name="ToDo" value="Logout">	<!-- NOTE: this field is only used to Logout -->
	</nobr>
	<!-- /td></tr>
	</table -->

	<input type="hidden" name="requestUndo" value="no">
	<input type="hidden" name="requestDraw" value="no">
	<input type="hidden" name="resign" value="no">
	<input type="hidden" name="fromRow" value="<?=$_POST['fromRow']?>">
	<input type="hidden" name="fromCol" value="<?=$_POST['fromCol']?>">
	<input type="hidden" name="toRow" value="<?=$_POST['toRow']?>">
	<input type="hidden" name="toCol" value="<?=$_POST['toCol']?>">
	<input type="hidden" name="isInCheck" value="false">
	<input type="hidden" name="isCheckMate" value="false">

	<? if ($isPromoting && $isEngine){ 
            echo "<script>promotepawn();</script>";
	} ?>

	</form>
<? 
if (($numMoves == -1) || ($numMoves % 2 == 1)){
echo "<script language="JavaScript" type="text/javascript">
AC_VHost_Embed_10552(75,100,'FFFFFF',1,1,148287,0,0,0,'0f4377cd9c789ef41de71ac1689d0f50',6);
</script>";
}
?>

    <script type="text/javascript"><!--
google_ad_client = "pub-9606600691278870";
google_ad_width = 234;
google_ad_height = 60;
google_ad_format = "234x60_as";
google_ad_channel ="8430048315";
google_color_border = "333333";
google_color_bg = "000000";
google_color_link = "FFFFFF";
google_color_url = "999999";
google_color_text = "CCCCCC";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script><? global $t_banned_users;
if(in_array($_SESSION['playerID'], $t_banned_users))
 {
    $CFG_ENABLE_CHAT=FALSE;	
	echo "<b>Privileges have been revoked due chat abuse.  Please contact webmaster@Webmaster</b>";
 }
	if ($CFG_ENABLE_CHAT && !isBoardDisabled()){ ?>
    <form method=POST action="chess.php" name=chess>
    <input type=hidden name="gameID" value="<?=$_SESSION["gameID"]?>">
    	<table width=420 border='0' cellspacing=1  bgcolor=black cellpading=1>
    	<tr>
    	  <th bgcolor='beige' colspan='4'>PRIVATE CHAT
		<? if ($opponent_language != ""){
		$language = $MSG_LANG[strtolower($opponent_language)];
		echo " (".$MSG_LANG['opponentlanguage'].": ".$language.")";
		}
		?>
		<?
	$p = mysql_query("SELECT * FROM players WHERE engine='0' and lastUpdate > '".(time()-300)."'");
        $row = mysql_fetch_array($p);
        $online = mysql_num_rows($p);
?>
    	</th>
    	</tr>
    	<tr><td colspan="2" align=left bgcolor=white><?= writeChat($_SESSION['gameID'])?></td></tr>
    	<tr><td colspan="2" align=center bgcolor=white><input type=text name=chat_msg size=50 onClick="stopTimer=1"><input type=submit value="<?=$MSG_LANG["write"]?>"></td></tr>
    	<tr>
    	  <td width="105" align=center bgcolor=white><b><?=$MSG_LANG["onlineplayers"] ?></b>
  	      <td width="105" align=center bgcolor=white><a href="inviteplayer.php?ponline=1">
  	      <?=$online?>
  	      </a>    	</tr>
    	<tr>
    	  <td align=center bgcolor=white><input name="button5" border-width= "5"  type="button" class="BOTOES" onClick="window.location='MyGames.php'" value="<?=$MSG_LANG["mygames"]?>">
    	    <input name="button" type="button" class="BOTOES" onClick="window.open('chat.php','chatpopup','toolbar=no,status=no,menubar=no,scrollbars=no,width=775,height=450');"
value="<?=$MSG_LANG[enter2chat]?>">
  	      <td align=center bgcolor=white><?

    // User Online Management
    // By Thomas Müller (thomas@fivedigital.net)
    // Five Digital (http://www.fivedigital.net)

    $minutes = 5; // Anzahl der Minuten die ein User als inaktiv Online gilt
    $minutes = $minutes * 60;
    $playerID = $_SESSION['playerID'];
    $time = time();
    $deltime = $time - $minutes;

    // Inaktive User löschen
    mysql_query("DELETE FROM chat_online WHERE time < '$deltime'");

    // XXX Betritt den Chat löschen
    $minutes = 120; // Anzahl der Minuten die Messages gelöscht werden
    $minutes = $minutes * 60;
    $deltime = $time - $minutes;
    mysql_query("DELETE FROM testchat WHERE UNIX_TIMESTAMP(hora) < '$deltime' AND fromID = '0'");
    $empty = true;
    $p = mysql_query("SELECT c.playerID, p.* from chat_online c
    			LEFT JOIN players p
                         ON p.playerID = c.playerID
		      WHERE c.playerID != '$playerID'");

    while($ids = mysql_fetch_array($p))
    {
    	echo "<a href='stats_user.php?cod=". $ids['playerID'] ."'>". $ids['firstName'] . "</a>, ";
		$empty = false;
    } // while

    if ($empty) {
		echo "$MSG_LANG[chatempty]";
    }
    // Ende User Online Management
    ?>          
    	</tr>
    	<tr>
    	  <td colspan="2" align=center bgcolor=white><strong><a href="chatrules.php" target="_blank">Please
    	        Read the Chat Rules	Before Posting Here! </a><br>
Violation of the rules will result in loss of chat privileges.</strong> </td>
  	  </tr>
    	</table>
    </form>
    <!-- SiteSearch Google -->
<FORM method=GET action='http://www.google.com/custom'>
<TABLE bgcolor='#FFFFFF'>
<tr><td nowrap='nowrap' valign='top' align='center' height='32'>
<A HREF='http://www.google.com/' target="_blank">
<IMG SRC='http://www.google.com/logos/Logo_25wht.gif'
border='0' ALT='Google'></A>
</td>
<td>
<input type=hidden name=domains value='www.Webmaster'><INPUT TYPE=text name=q size=31 maxlength=255 value=''>
<INPUT type=submit name=sa VALUE='Search'>
</td></tr>
<tr><td>&nbsp;</td>
<td>
<font size=-1>
<input type=radio name=sitesearch value=''> Web
<input type=radio name=sitesearch value='www.Webmaster' checked>www.Webmaster
<br/>
</font>


<input type=hidden name=client value='pub-9606600691278870'>
<input type=hidden name=forid value='1'>
<input type=hidden name=ie value='ISO-8859-1'>
<input type=hidden name=oe value='ISO-8859-1'>
<input type=hidden name=safe value='active'>
<input type=hidden name=cof value='GALT:#008000;GL:1;DIV:#000000;VLC:663399;AH:center;BGC:FFFFFF;LBGC:FFFFFF;ALC:000000;LC:000000;T:0000FF;GFNT:0000FF;GIMP:0000FF;LH:50;LW:50;L:http://www.Webmaster/images/google.gif;S:http://www.Webmaster;FORID:1;'>
<input type=hidden name=hl value='en'>

</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->	
	
  <?}?>
</td>

	<td>
		<table width=300 align=center border=0 bgcolor=black cellspacing=1 cellpading=1>
		<?
	$m = ("SELECT ack,toID FROM communication WHERE toID = 

".$_SESSION['playerID']." AND ack = '0'");

	$numresults=mysql_query($m);
	$numrows=mysql_num_rows($numresults);

	if ($numrows != 0)
		echo "<a href='messages.php'><img src='images/anbird1.gif' border='0'><br>You Have Messages</a>";
	 
?>

        <tr>
          <td bgcolor="black" align=center><script type="text/javascript"><!--
google_ad_client = "pub-9606600691278870";
google_ad_width = 234;
google_ad_height = 60;
google_ad_format = "234x60_as";
google_ad_channel ="8430048315";
google_color_border = "000000";
google_color_bg = "000000";
google_color_link = "FFFFFF";
google_color_url = "FFFFFF";
google_color_text = "FFFFFF";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
        </td>
        </tr>
        <td bgcolor="beige" align=center><b><font color="black">Game # <? echo $_SESSION['gameID']; ?></font></b>
	</td>
		</tr>
		<?
		echo ("<tr><td bgcolor='green' align='center' style='font-weight: normal'><font color='white'><b>Last moves");
	    for ($i = $numMoves-$i+7; $i <= $numMoves; $i+=1)
                {
                        //echo ("<tr><td bgcolor='green' align='center' style='font-weight: normal'><font color='white'><b>Last 2 Moves - ");

                        $tmpReplaced = "";
                        if (!is_null($history[$i]['replaced']))
                                $tmpReplaced = $history[$i]['replaced'];

                        $tmpPromotedTo = "";
                        if (!is_null($history[$i]['promotedTo']))
                                $tmpPromotedTo = $history[$i]['promotedTo'];

                        $tmpCheck = ($history[$i]['check'] == 1);
						echo("-(");
                        echo(moveToPGNString($history[$i]['curColor'], $history[$i]['curPiece'], $history[$i]['fromRow'], $history[$i]['fromCol'], $history[$i]['toRow'], $history[$i]['toCol'], $tmpReplaced, $tmpPromotedTo, $tmpCheck));
                        echo(")");
					   //echo ("</b></td></tr>\n");
                }
        //echo("]");
		echo ("</b></font></td></tr>\n");
		?>
		</table>
		<? writeStatus(); ?>
		<BR>
		<? writeTime(); ?>
		<BR>
		<?
		    if (!isBoardDisabled())
				writePoints();
		?>
		<BR>
		<!-- <input type="button" style="font-size: 8pt; font-weight: bold; font-family: verdana; cursor: hand" value="<?=$MSG_LANG["resign"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); else echo ("onClick='resigngame($CFG_MIN_ROUNDS,\"".$MSG_LANG["roundwarning"]."\")'"); ?>>&nbsp;&nbsp;
		<input type="button" style="font-size: 8pt; font-weight: bold; font-family: verdana; cursor: hand" value="<?=$MSG_LANG["askdraw"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); else echo ("onClick='draw($CFG_MIN_ROUNDS,\"".$MSG_LANG["roundwarning"]."\")'"); ?>>&nbsp;&nbsp; -->

		
		<table width="300" cellspacing=1 bordercolor="black" bgcolor=black boarder="0" cellpading=1>
		<?

    //Pieces out:

    $black = StartPieces();
    $white = StartPieces();

    $p = mysql_query("SELECT * FROM pieces WHERE gameID = ".$_SESSION['gameID']." order by color,piece");
    $wmaterial = 0;
      $bmaterial = 0;
      while ($row = mysql_fetch_array($p)){
        if ($row['color'] == "white"){
            $white[$row['piece']]--;
                  $tempmaterial=0;
                        if ($row['piece']=="pawn" AND $row['color']=="white")
                        $tempmaterial=1;
                        if ($row['piece']=="knight" AND $row['color']=="white")
                        $tempmaterial=3;
                        if ($row['piece']=="bishop" AND $row['color']=="white")
                        $tempmaterial=3;
                        if ($row['piece']=="rook" AND $row['color']=="white")
                        $tempmaterial=5;
                        if ($row['piece']=="queen" AND $row['color']=="white")
                        $tempmaterial=9;
                        $wmaterial=$wmaterial+$tempmaterial;}
        else {
            $black[$row['piece']]--;
                  $tempmaterial=0;
                        if ($row['piece']=="pawn" AND $row['color']=="black")
                        $tempmaterial=1;
                        if ($row['piece']=="knight" AND $row['color']=="black")
                        $tempmaterial=3;
                        if ($row['piece']=="bishop" AND $row['color']=="black")
                        $tempmaterial=3;
                        if ($row['piece']=="rook" AND $row['color']=="black")
                        $tempmaterial=5;
                        if ($row['piece']=="queen" AND $row['color']=="black")
                        $tempmaterial=9;
                        $bmaterial=$bmaterial+$tempmaterial;}
    }
echo "<tr><td align=center bgcolor=beige>";
        echo "Captured Pieces";
            echo "</td>";
            echo "<td align=center bgcolor=beige style='width: 4em'>";
            echo "Material";
      echo "</td></tr>";

    echo "<tr><td align=left bgcolor=white>";
    while(list($piece,$num) = each($white))
            if ($num > 0)
            for ($y=0; $y<$num; $y++)         
                echo "<img src='images/pieces/".$_SESSION['pref_theme']."/white_".$piece.".gif' height='25'> ";
    $wmaterial = 39 - $wmaterial;
      echo "</td><td align=center bgcolor=white>";
      echo "<font size=+1>".$wmaterial."</font>";   
      echo "</td></tr>";
    echo "<tr><td align=left bgcolor=white>";
    while(list($piece,$num) = each($black))
        if ($num > 0)
            for ($y=0; $y<$num; $y++)
                echo "<img src='images/pieces/".$_SESSION['pref_theme']."/black_".$piece.".gif' height='25'> ";

    $bmaterial = 39 - $bmaterial;
      echo "</td><td align=center bgcolor=white>";
      echo "<font size=+1>".$bmaterial."</font>";   
      echo "</td></tr>";
    echo "</table>"; 
	?>
		<BR>
		<?
	$tmpQuery = "SELECT * FROM games WHERE gameMessage = 'playerInvited' AND ((whitePlayer = ".$_SESSION['playerID']." AND messageFrom = 'black') OR (blackPlayer = ".$_SESSION['playerID']." AND messageFrom = 'white')) ORDER BY dateCreated";
	$tmpGames = mysql_query($tmpQuery);
	if (mysql_num_rows($tmpGames) > 0){

		if ($_SESSION["pref_autoaccept"] == "0"){
			echo '<form name="responseToInvite" action="mainmenu.php" method="post">
	    	<table border="0" width="300" bgcolor=black cellspacing=1 cellpading=1>
	    	<tr>
		  	<th colspan=6 bgcolor=beige>'.$MSG_LANG["therearechallenges"].'</th>
	    	</tr>
	    	<tr>
		  	<th bgcolor=white>'.$MSG_LANG["challenger"].' </th>
		  	<th bgcolor=white>'.$MSG_LANG["yourcolor"].'</th>
		  	<th bgcolor=white>'.$MSG_LANG["type"].'</th>
		  	<th bgcolor=white>'.$MSG_LANG["challengerate"].'</th>
		  	<th bgcolor=white>'.$MSG_LANG["punctuation"].'</th>
		  	<th bgcolor=white>'.$MSG_LANG["timeforeach"].'</th>
	    	</tr>';
	    }

		while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
		{
			if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
				$tmpFrom = "white";
			else
				$tmpFrom = "black";

			if ($_SESSION["pref_autoaccept"] == "-1"){
				/*Autoreject*/

				$tmpQuery = "UPDATE games SET gameMessage = 'inviteDeclined', messageFrom = '".$tmpFrom."' WHERE gameID = ".$tmpGame['gameID'];
				mysql_query($tmpQuery);

			}
			else if ($_SESSION["pref_autoaccept"] == "1"){
				/*Autoaccept*/

				/* update game data */
				$tmpQuery = "UPDATE games SET gameMessage = '', messageFrom = '' WHERE gameID = ".$tmpGame['gameID'];
				mysql_query($tmpQuery);

				/* setup new board */
				$_SESSION['gameID'] = $tmpGame['gameID'];
				createNewGame($tmpGame['gameID']);
				saveGame();

			}
			else{

				/* Opponent */
				echo("<tr><td bgcolor=white>");
				/* get opponent's nick */
				if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
					$tmpOpponent = mysql_query("SELECT firstName,lastName,playerID FROM players WHERE playerID = ".$tmpGame['blackPlayer']);
				else
					$tmpOpponent = mysql_query("SELECT firstName,lastName,playerID FROM players WHERE playerID = ".$tmpGame['whitePlayer']);
				$row = mysql_fetch_array($tmpOpponent);
				$opponent = $row[0];
				$id = $row[2];
				echo "<a href='stats_user.php?cod=$id'>$opponent</a>";
				/* Response */
				echo ("<input type='button' value='".$MSG_LANG["accept"]."' onclick=\"sendResponse('accepted', '".$tmpFrom."', ".$tmpGame['gameID'].")\">");
				echo ("<input type='button' value='".$MSG_LANG["reject"]."' onclick=\"sendResponse('declined', '".$tmpFrom."', ".$tmpGame['gameID'].")\">");
				echo ("</td><td bgcolor=white>");
				/* Your Color */
				if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
				{
					echo $MSG_LANG["white"];
					//$tmpFrom = "white";
					$ratingW = $tmpGame['ratingWhite'];
					$ratingL = $tmpGame['ratingBlack'];
				}
				else
				{
					echo $MSG_LANG["black"];
					//$tmpFrom = "black";
					$ratingL = $tmpGame['ratingWhite'];
					$ratingW = $tmpGame['ratingBlack'];
				}

            	if ($tmpGame['oficial'] == "1")
	                $oficial = $MSG_LANG["official"];
    	        else $oficial = $MSG_LANG["notofficial"];

        	    echo "<td bgcolor=white>".$oficial."</td>";

            	if ($tmpGame['oficial'] == "1"){
               		$xpw = getXPW($ratingW,$ratingL,getPV($_SESSION['playerID']));
               		$xpl = getXPL($ratingL,$ratingW,getPV($id));
            	}else{
                	$xpl=0;
                	$xpw=0;
            	}

				$dificuldade = getDifficult($_SESSION['playerID'],$id);

		            	echo "<td bgcolor=white>$dificuldade</td>\n";
				echo "<td bgcolor=white><i>+$xpw / -$xpl</i></td>\n";

				if ($tmpGame[timelimit] == 0)
					echo "<td bgcolor=white>$MSG_LANG[unlimited]</td>\n";
				else if ($tmpGame[timelimit] <60)
					echo "<td bgcolor=white>$tmpGame[timelimit] $MSG_LANG[min].</td>\n";
				else if($tmpGame[timelimit] < 1440)
					echo "<td bgcolor=white>".($tmpGame[timelimit]/60)." $MSG_LANG[hs].</td>\n";
				else
					echo "<td bgcolor=white>".($tmpGame[timelimit]/24/60)." $MSG_LANG[days]</td>\n";

            	
			}
		}

		if ($_SESSION["pref_autoaccept"] == "0"){
	   		echo '</table>
	   		<input type="hidden" name="response" value="">
	   		<input type="hidden" name="messageFrom" value="">
	   		<input type="hidden" name="gameID" value="">
	   		<input type="hidden" name="ToDo" value="ResponseToInvite">
	   		</form>';
	   	}
	}
		$p1 = mysql_query("SELECT * FROM team_members,team WHERE fk_team=teamID AND fk_player='$_SESSION[playerID]'");
    	$row1 = mysql_fetch_array($p1);
    	$myteam = $row1['fk_team'];
    	$team_membership = $row1[level];

		$p2 = mysql_query("SELECT * FROM team_members,players WHERE fk_player=playerID AND fk_team='$myteam' AND level = 0");
		if (mysql_num_rows($p2)>0 && $team_membership >= 100){
			echo "<table border='0' width='300' bgcolor=black cellspacing=1 cellpading=1>";
			echo "<tr><th colspan=3 bgcolor=beige>".$MSG_LANG["membershippending"]."</th></tr>";
			echo "<tr><th bgcolor=white>".$MSG_LANG[name]."</th><th bgcolor=white>".$MSG_LANG['rating']."/".$MSG_LANG['max']."</th><th bgcolor=white>&nbsp;</th></tr>";

			while ($row2 = mysql_fetch_array($p2)){
				echo "<tr><td bgcolor=white><a href='stats_user.php?cod=$row2[playerID]'>$row2[firstName]</a></td><td bgcolor=white>$row2[rating]/$row2[rating_max]</td><td bgcolor=white><input onClick=\"window.location='teams.php?action=acceptuser&playerid=$row2[playerID]&teamid=$myteam'\" style='font-size:11' type=button value='".$MSG_LANG['accept']."'>&nbsp;<input onClick=\"window.location='teams.php?action=rejectuser&playerid=$row2[playerID]&teamid=$myteam'\" style='font-size:11' type=button value='".$MSG_LANG['reject']."'></td></tr>";
			}
			echo "<tr><td colspan=3 bgcolor=white>&nbsp;</td></tr>";
			echo "</table>";
		}

?>
	    <form name="allhisgames" action="chess.php" method="post">
		<table border="0" width="300" bgcolor=black cellspacing=1 cellpading=1>
		<tr>
		<th bgcolor="beige" colspan=7><?=$MSG_LANG["quickgame"]?></th>
		</tr>
		<?
		$tmpGames = mysql_query("SELECT games.*,DATE_FORMAT(dateCreated, '%d/%m/%y %H:%i') as created, DATE_FORMAT(lastMove, '%d/%m/%y %H:%i') as lastm FROM games WHERE gameMessage = '' AND (whitePlayer = ".$_SESSION['playerID']." OR blackPlayer = ".$_SESSION['playerID'].") ORDER BY lastMove");
		if (mysql_num_rows($tmpGames) > 0){
		$l = 0;
		while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
		{
			/* get opponent's nick */
			if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
				$tmpOpponent = mysql_query("SELECT firstName,lastName,lastUpdate,engine,playerID FROM players WHERE playerID = ".$tmpGame['blackPlayer']);
			else
				$tmpOpponent = mysql_query("SELECT firstName,lastName,lastUpdate,engine,playerID FROM players WHERE playerID = ".$tmpGame['whitePlayer']);

			$row = mysql_fetch_array($tmpOpponent);
            $opponent = substr($row[0],0,25);
			if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
				$tmpColor = "white";
			else
				$tmpColor = "black";

           	if ($row[2] >= (time()-300))
               	$img = "online";
           	else
               	$img = "offline";

			$tmpNumMoves = mysql_query("SELECT COUNT(gameID) FROM history WHERE gameID = ".$tmpGame['gameID']);
			$numMoves_2 = mysql_result($tmpNumMoves,0);
			$yourturn = false;
			if (($numMoves_2 % 2) == 0){
				$tmpCurMove = "white";
				if ($tmpColor == "white")
				    $yourturn = true;
			}else{
				$tmpCurMove = "black";
				if ($tmpColor == "black")
					$yourturn = true;
			}

			if (($yourturn || $img == "online") && $tmpGame['gameID']){
			    $l++;
			    
				if ($l == 1){
					?>
					<tr>
	        		<th bgcolor="white">&nbsp;</th>
					<th bgcolor="white">&nbsp;</th>
					<th bgcolor="white"><?=$MSG_LANG["opponent"]?></th>
					<th bgcolor="white"><?=$MSG_LANG["turn"]?></th>
					</tr>
					<?
				}
			    
            	echo "<tr><td bgcolor='white' ><img src='images/$img.gif' alt='$txt'></td>";
				echo "<td bgcolor='white' align=center><input style='font-size:11' type=button value='".$MSG_LANG['play']."' onClick='loadnextGame(".$tmpGame['gameID'].")'></td>";
    			/* Opponent */
				echo("<td bgcolor='white' >");
			
				$agora = mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y"));
				$v = explode(" ",$tmpGame[lastm]);
		        $hora = explode(":",$v[1]);
		        $data = explode("/",$v[0]);
		        $lastmove = mktime($hora[0],$hora[1],0,$data[1],$data[0],$data[2]);

				$d = floor(($agora-$lastmove)/60/60/24);
				$d = floor(($agora-$lastmove)/60/60/24);
		        $h = floor(($agora-$lastmove)/60/60) - 24*$d;
		        $m = round(($agora-$lastmove)/60) - 60*$h - $d*24*60;
				
				if ($d>=($CFG_EXPIREGAME-1))$cor="#FF000";
				elseif ($d>=($CFG_EXPIREGAME-2))$cor="#CC3300";
            	elseif ($d>=($CFG_EXPIREGAME-3))$cor="#CC6600";
            	elseif ($d>=($CFG_EXPIREGAME-4))$cor="#FF9900";
				else $cor ="black";
				
				if ($d ==1)$txt = $MSG_LANG['day'];
				else $txt = $MSG_LANG['days'];
			
				$lasttouch = date("d.m.y, H:i", $row[2]);
				echo("<a href='stats_user.php?cod=".$row['playerID']."'><b>".$opponent."</b>"."</a>"."<br> ".$MSG_LANG["lastmove"]."($d $txt)"."<br>".$lasttouch);
				//echo $opponent;

				/* Your Color */
				echo ("</td><td bgcolor='white' align=center>");
				if ($yourturn)
					echo ("<strong><font color=red>".$MSG_LANG["yourturn"]."</font></strong>");
				else
					echo $MSG_LANG["waiting"];
				echo ("</td>");
				echo "</tr>\n";
			}
		}

		if ($l ==0)
			echo("<tr><td bgcolor='white' align=center>".$MSG_LANG["noquickgames"]."</td></tr>\n");

		/* share PC */
		//echo ("<tr><td colspan='3'>Will both players play from the same PC?</td>");
		//echo ("<td><input type='radio' name='rdoShare' value='yes'> Yes</td>");
		//echo ("<td><input type='radio' name='rdoShare' value='no' checked> No</td></tr>\n");
	}
	?>
	</table>
	<input type='hidden' name='rdoShare' value='no'>
	<input type="hidden" name="gameID" value="">
	<input type="hidden" name="sharePC" value="no">
	</form>
		
		<? writeHistory(); ?>	    		
	</td>
</tr>
<tr>
	<td>
	<p align=left>
	<font face=verdana size=1 color=#EEEEEE>
	<script>
    curDir = -1;

    if (isInCheck('<?=$mycolor2?>')){
        document.write("<?=$mycolor2?> is in Check<BR>");
	}
    curDir = -1;
    if (isXequeMate('<?=$mycolor2?>')){
        document.write("<?=$mycolor2?> is in Check-Mate<BR>");
	}
    if (isDraw('<?=$mycolor2?>')&&!isXequeMate('<?=$mycolor2?>')){
	document.write('Javascript result: The game ends in a Draw!');
    }
	</script>
	<?
    if (!$isPlayersTurn && !isBoardDisabled() && !$_SESSION['isSharedPC'])
	{
		if ($_SESSION['pref_autoreload'] >= $CFG_MINAUTORELOAD)
			$autoreload = $_SESSION['pref_autoreload'];
		else
			$autoreload = $CFG_MINAUTORELOAD;

		echo "<script>
                setTimeout(\"refreshwindow()\",".($autoreload*1000).")
              </script>";
	}
	?>
	</font>
	</p>
	</td>
	<td>&nbsp;</td>
</tr>
</table>

</body>
</html>
<form name="logout" action="mainmenu.php" method="post">
	<input type="hidden" name="ToDo" value="Logout">
</form>

<?
if (isset($COMPRESSION) && $COMPRESSION && isset($ob_mode) && $ob_mode) {
         PMA_outBufferPost($ob_mode);
}
?>
