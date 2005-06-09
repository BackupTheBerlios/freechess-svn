<?php
##############################################################################################
#                                                                                            #
#                                botchallenge.php                                                
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
    $id=$_SESSION['playerID'];
	$thankyou_message = "<p>Thankyou. Your challenge has been sent. </p>";
	/* check session status */
	require_once('sessioncheck.php');

	/* Language selection */
    require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");
?>
<?php
function backslash(&$arr, $escape)
{
   $magic_on = get_magic_quotes_gpc();

   if($escape && !$magic_on):
       foreach($arr as $k => $v):
           switch(gettype($v)):
               case 'string' : 
                   $arr[$k] = addslashes($v);
                   break;
               case 'array' :
                   backslash($arr[$k], true);
           endswitch;
       endforeach;
   endif;

   if(!$escape && $magic_on): 
       foreach($arr as $k => $v):
           switch(gettype($v)):
               case 'string' : 
                   $arr[$k] = stripslashes($v);
                   break;
               case 'array' :
                   backslash($arr[$k], false);
           endswitch;
       endforeach;
   endif;        
}

//examples
backslash($_POST, true); // force all $_POST values to be  properly escaped with backslashes backslash($_GET, false); // force all $_GET values to NOT be escaped with backslashes  
/* get opponent's player ID */
			$tmpOpponentID = mysql_query("SELECT whitePlayer FROM games WHERE gameID = ".$_POST['gameID']);
			if (mysql_num_rows($tmpOpponentID) > 0)
			{
				$opponentID = mysql_result($tmpOpponentID, 0);

				if ($opponentID == $_SESSION['playerID'])
				{
					$tmpOpponentID = mysql_query("SELECT blackPlayer FROM games WHERE gameID = ".$_POST['gameID']);
					$opponentID = mysql_result($tmpOpponentID, 0);
				}
			
				$tmpQuery = "DELETE FROM games WHERE gameID = ".$_POST['gameID'];
				mysql_query($tmpQuery);
			
				/* if email notification is activated... */
				if ($CFG_USEEMAILNOTIFICATION)
				{
					/* if opponent is using email notification... */
					$tmpOpponentEmail = mysql_query("SELECT value FROM preferences WHERE playerID = ".$opponentID." AND preference = 'emailNotification'");
					if (mysql_num_rows($tmpOpponentEmail) > 0)
					{
						$opponentEmail = mysql_result($tmpOpponentEmail, 0);
						if ($opponentEmail != '')
						{
							/* notify opponent of invitation via email */
							webchessMail('withdrawal', $opponentEmail, '', $_SESSION['nick']);
						}
					}
				}
			}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta name="Keywords" content="chess,ajedrez,échecs,echecs,scacchi,schach,check,check mate,jaque,jaque mate,queenalice,queen alice,queen,alice,play,game,games,turn based,correspondence,correspondence chess,online chess,play chess online">
		
		<title>Challenge</title>

<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/mainstyles.css" type="text/css">

<script type="text/javascript">
function withdrawRequest(gameID)
		{
			document.withdrawRequestForm.gameID.value = gameID;
			document.withdrawRequestForm.submit();
		}
</script>		
	</head>

	<body>
<table width="600">
	<form name="invite" action="challengesent.php" method="post">    
    <input type="hidden" name="ToDo" value="InvitePlayer">
    <input type="hidden" name="opponent" value="">
	<TR></TR><TH colspan=2><?=$MSG_LANG["gamesettings"]?></TH></TR>
	    <TR>
            <TD style="text-align:left" width=40%><?=$MSG_LANG["yourcolor"]?>:</TD>
            <TD style="text-align:left">
			<select name="color">
            <option value="random" SELECTED><?=$MSG_LANG["random"]?></option>
            <option value="white"><?=$MSG_LANG["white"]?></option>
            <option value="black"><?=$MSG_LANG["black"]?></option>
            
			</select></TD></TR>


	    <TR>
            <TD style="text-align:left"><?=$MSG_LANG["isofficial"]?>:</TD>
            <TD style="text-align:left">
			<? if ($newuser ==1 || ($computer == 1 && !$CFG_RANK_COMPUTER) || getRating($_SESSION[playerID]) == 0){?>
                <input type='hidden' name='oficial' value='0'>
                <select name="oficialdisabled" disabled=true>
                <option value="1"><?=$MSG_LANG["yes"]?></option>
                <option value="0" SELECTED><?=$MSG_LANG["no"]?></option>
                </select>
            <?}else{?>
                <select name="oficial">
                <option value="1" SELECTED><?=$MSG_LANG["yes"]?></option>
                <option value="0"><?=$MSG_LANG["no"]?></option>
                </select>
            <?}?>
          </TD></TR>
<TR>
            <TD style="text-align:left">              <?=$MSG_LANG["thematic"]?>              <br>              <img src="images/new.gif" width="40" height="36"></TD>
            <TD><div align="left">
              <select name="thematic" align="right">
                <option value="0" SELECTED>
                <?=$MSG_LANG["no"]?>
                </option>
              </select>
            Thematic not available for chessbots yet.</div></TD></TR>
</td>
</tr>
		<TR>
		<TD style="text-align:left"><?=$MSG_LANG["timeforeach"]?>:</TD>
		<TD style="text-align:left">
  		<select name="timelimit">
		<option value="0" SELECTED><?=$MSG_LANG["movetimeout"]?></option>
		
<?
 foreach ($CFG_TIME_ARRAY as $t)
 //{
 //	if ($t <= 1440){
		//if ($t>=30 && $t<60)
			//echo "<option value='$t'>$MSG_LANG[min]$t min.</option>\n";
		//elseif ($t>=60){
			//$tm = $t/60;
	    //	echo "<option value='$t'>$MSG_LANG[hs]$tm hrs.</option>\n";
	    //}
	//}
//}
	for ($x=1; $x<$CFG_EXPIREGAME; $x++)
		echo "<option value='".($x*1440)."'>$x Days per move</option>\n";
	//for ($y=1; $y<$CFG_EXPIREGAME; $y++)
	    //echo "<option value='".($y)."'>$y days per move</option>\n";
?>
		</select>
		<?=$MSG_LANG["movetimeout"]?>:<?=$CFG_EXPIREGAME?> <?=$MSG_LANG["days"]?>
		
		<TR>
		<TD style="text-align:left"><?=$MSG_LANG["chooseplayertojoin"]?>:</TD>
		<TD style="text-align:left">
		
				<select name="opponent" size="1">
<?	
				 
				
				
				//$tmpQuery="SELECT * FROM players WHERE engine='0' and playerID <> ".$id." AND ativo='1' and rating>0 ORDER BY firstName ASC";
				$tempo = time()-1209600;
				$tmpQuery="SELECT playerID, firstName FROM players WHERE lastUpdate>='$tempo' and playerID <> ".$id." AND ativo='1' and rating>0 and displaybot='1' ORDER BY firstName ASC";
	            $tmpPlayers = mysql_query($tmpQuery);
    					while($tmpPlayer = mysql_fetch_array($tmpPlayers, MYSQL_ASSOC))
                                        {
                                                if ($tmpPlayer['firstName']){
														if($tmpPlayer['playerID']==$_GET['opponent'])
        	                                        			echo("<option value='".$tmpPlayer['playerID']."' selected>".$tmpPlayer['firstName']."</option>\n");
														else
                	                                			echo("<option value='".$tmpPlayer['playerID']."'> ".$tmpPlayer['firstName']."</option>\n");
						}
                                        }
?>
</select>
<?
echo"<input type='button' style='font-size:11' value='$MSG_LANG[invite]' onClick='document.invite.submit();'>";		
//echo"<input style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button onClick='document.challenge.submit()' value='".$MSG_LANG[invite]."'>"."";
//<input type="submit" name="newMessage" style="cursor:hand" value="Send&nbsp;Message" border="0"> 
				//<input type="button" name="btnCancel" value="Cancel" style="cursor:hand" border="0" onClick="javascript:window.close();">
?>
</form>		
</TD></TR>
</table>
			
		   <?    
    /* if game is marked playerInvited and the invite is from the current player */
    $tmpQuery = "SELECT whitePlayer,blackPlayer,gameMessage,gameID,oficial  FROM games WHERE (gameMessage = 'playerInvited' AND ((whitePlayer = ".$_SESSION['playerID']." AND messageFrom = 'white') OR (blackPlayer = ".$_SESSION['playerID']." AND messageFrom = 'black'))";
    /* OR game is marked inviteDeclined and the response is from the opponent */
    $tmpQuery .= ") OR (gameMessage = 'inviteDeclined' AND ((whitePlayer = ".$_SESSION['playerID']." AND messageFrom = 'black') OR (blackPlayer = ".$_SESSION['playerID']." AND messageFrom = 'white')))  ORDER BY dateCreated";
    $tmpGames = mysql_query($tmpQuery);
    
    if (mysql_num_rows($tmpGames) > 0){
        //echo("<tr><td colspan='5'>".$MSG_LANG["noinvations"].".</td></tr>\n");
    ?>
    
	<form name="withdrawRequestForm" action="challenge.php" method="post">
    <table border="1" width="500">
    <tr>
        <th colspan="5"><?=$MSG_LANG["currentinvitations"]?></th>
    </tr>

    <tr>
        <th><?=$MSG_LANG["opponent"]?></th>
        <th><?=$MSG_LANG["yourcolor"]?></th>
        <th><?=$MSG_LANG["type"]?></th>
        <th><?=$MSG_LANG["status"]?></th>
        <th><?=$MSG_LANG["action"]?></th>
    </tr>
    <?
        while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
        {
            /* Opponent */
            echo("<tr><td>");
            /* get opponent's nick */
            if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
                $tmpOpponent = mysql_query("SELECT firstName,lastName,playerID FROM players WHERE playerID = ".$tmpGame['blackPlayer']);
            else
                $tmpOpponent = mysql_query("SELECT firstName,lastName,playerID FROM players WHERE playerID = ".$tmpGame['whitePlayer']);

            $row = mysql_fetch_array($tmpOpponent);
            $opponent = $row[0];
            $id = $row[2];
            echo "<a href='stats_user.php?cod=$id'>$opponent</a>";
            
            /* Your Color */
            echo ("</td><td>");
            if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
                echo ($MSG_LANG["white"]);
            else
                echo ($MSG_LANG["black"]);

            if ($tmpGame['oficial'] == "1")

                $oficial = $MSG_LANG["official"];
            else $oficial = $MSG_LANG["notofficial"];

            echo "<td>".$oficial."</td>";

            /* Status */
            echo ("</td><td>");
            if ($tmpGame['gameMessage'] == 'playerInvited')
                echo $MSG_LANG["pendingreply"];
            else if ($tmpGame['gameMessage'] == 'inviteDeclined'){
                echo $MSG_LANG["invitedeclined"];
                if (substr($tmpGame['reason'],0,1)=="#")
                    echo ":<BR>".$MSG_LANG[$tmpGame['reason']];
            }    
            /* Withdraw Request */
            echo ("</td><td align='center'>");
            echo ("<input type='button' value='".$MSG_LANG['cancel']."' onclick=\"withdrawRequest(".$tmpGame['gameID'].")\">");

            echo("</td></tr>\n");
        }
        echo '
        </table>
        <input type="hidden" name="gameID" value="">
        <input type="hidden" name="ToDo" value="WithdrawRequest">
        </form><BR>';
    }
?>

</body>

</html>
