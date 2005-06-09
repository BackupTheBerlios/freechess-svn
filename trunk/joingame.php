<?php
##############################################################################################
#                                                                                            #
#                                joingame.php.php                                                
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
    require_once("languages/english/strings.inc.php");

        if (!isset($_GET['voltar']))
    $voltar = "mainmenu.php";
else $voltar = $_GET['voltar'];


        switch($_POST['ToDo']){

                case 'InvitePlayer':

                        $ngames = 0;
                        
                        if ($ngames == 0)
                        {
                                if (!minimum_version("4.2.0"))
                                        init_srand();
                        
                                if ($_POST['color'] == 'random')
                                        $tmpColor = (rand(0,1) == 1) ? "white" : "black";
                                else
                                        $tmpColor = $_POST['color'];

                                $tmpQuery = "INSERT INTO games (timelimit, whitePlayer, blackPlayer, gameMessage, messageFrom, dateCreated, lastMove,oficial,postGame,thematic) VALUES (";
                                if ($tmpColor == 'white'){
                                    $white = $_SESSION['playerID'];
                                    $black = "0";
                                }else{
                                        $white = "0";
                    $black = $_SESSION['playerID'];
                }
                                $tmpQuery .= "'".$_POST['timelimit']."', $white, $black, 'playerInvited', '".$tmpColor."', NOW(), NOW(),'".$_POST['oficial']."','".$_POST['postGame']."','".$_POST['thematic']."')";
                                mysql_query($tmpQuery);


                        }
                        break;



                case 'responseToInvite':
                        if ($_POST['response'] == 'accepted'){
                                /* update game data */


        $playb = mysql_query("SELECT * FROM games WHERE gameID='".$_POST['gameID']."'");

        $bl = mysql_fetch_array($playb);

        if ($bl['whitePlayer'] == "0"){

   $white = $_SESSION['playerID'];
   $black = $bl['blackPlayer'];

   $tmpQuery = "UPDATE games SET ratingWhite = ".getRating($white).", ratingBlack = ".getRating($black).", ratingWhiteM = ".getRatingMonth($white).", ratingBlackM = ".getRatingMonth($black).", PVWhite = ".getPV($white).", PVBlack = ".getPV($black).", gameMessage = '', messageFrom = '', whitePlayer = '".$_SESSION['playerID']."' WHERE gameID = ".$_POST['gameID'];
   mysql_query($tmpQuery);

       }elseif ($bl['blackPlayer'] == "0"){

   $white = $bl['whitePlayer'];
   $black = $_SESSION['playerID'];

   $tmpQuery = "UPDATE games SET ratingWhite = ".getRating($white).", ratingBlack = ".getRating($black).", ratingWhiteM = ".getRatingMonth($white).", ratingBlackM = ".getRatingMonth($black).", PVWhite = ".getPV($white).", PVBlack = ".getPV($black).", gameMessage = '', messageFrom = '', blackPlayer = '".$_SESSION['playerID']."' WHERE gameID = ".$_POST['gameID'];
   mysql_query($tmpQuery);

       }else{

       }

                                /* setup new board */
                                $_SESSION['gameID'] = $_POST['gameID'];
                                createNewGame($_POST['gameID']);
                                saveGame();
                        }
                        else
                        {

                                $tmpQuery = "UPDATE games SET gameMessage = 'inviteDeclined', messageFrom = '".$_POST['messageFrom']."' WHERE gameID = ".$_POST['gameID'];
                                mysql_query($tmpQuery);
                        }

                        break;

                case 'WithdrawRequest':
                                
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
                        break;

}


?>
<html>
<head>
    <title>Webmaster - Join / Create Game</title>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
<script>

        function withdrawRequest(gameID)
                {
                        document.withdrawRequestForm.gameID.value = gameID;
                        document.withdrawRequestForm.submit();
                }

        function MessagePlayer(playerID)
                {
                        var where="sendmessage.php?<?=$ssname?>=<?=$ssid?>&to="+playerID;
                        var height=320;
                        var width=470;
                        var left=(screen.availWidth/2)-(width/2);
                        var top=(screen.availHeight/2)-(height/2)-100;
                        window.open(where,"","height="+height+",width="+width+",left="+left+",top="+top);
                }

        function sendResponse(responseType, messageFrom, gameID)
                {
                        document.reply.response.value = responseType;
                        document.reply.messageFrom.value = messageFrom;
                        document.reply.gameID.value = gameID;
                        document.reply.submit();
                }

        function loadGame(gameID)
                {
                        //if (document.existingGames.rdoShare[0].checked)
                        //        document.existingGames.action = "opponentspassword.php";
                        document.existingGames.gameID.value = gameID;
                        document.existingGames.submit();
                }

</script>
</head>
<body bgcolor=white text=black>
<? require_once('header.inc.php');?>
<br>

<table align="center" border="1">
<tr>
<td>
<h3>Join / Create Games</h3>
You can create a game for others to join in the form below.  Select your color, the time limit, and a thematic if you wish to play a specific opening.  All games are set as rated games. When you have decided on your game settings, click the 'Create Game' button.

<br><br>

<form name="invite" action="joingame.php" method="post">
            <input type="hidden" name="ToDo" value="InvitePlayer">
        <input type="hidden" name="postGame" value="1">
        <input type="hidden" name="oficial" value="1">

<table align="center" class="none" width="100%" border="0" cellpadding="4" cellspacing="0">

        <tr>
            <td width="40%" align="left"><b><?=$MSG_LANG["yourcolor"]?>: </b></td>
            <td align="left">
            <select name="color">
            <option value="random" SELECTED><?=$MSG_LANG["random"]?></option>
            <option value="white"><?=$MSG_LANG["white"]?></option>
            <option value="black"><?=$MSG_LANG["black"]?></option>
            </select></td></tr>
        <TR>
            <TD align="left"><b><?=$MSG_LANG["isofficial"]?>:</b></TD>
            <TD align="left">
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
		
		<tr>
            <td width="30%" align="left"><b><?=$MSG_LANG["timeforeach"]?>: </b></td>
            
			<td width="70%" align="left">
            <select name="timelimit">
            <option value="0" SELECTED>14 Days per Move</option>
        <?
            for ($x=1; $x<$CFG_EXPIREGAME; $x++)
            echo "<option value='".($x*1440)."'>$x Days per move</option>\n";
        ?>
           </select></td></tr>
           
         <tr>
            <td width="30%" align="left"><b><?=$MSG_LANG["thematic"]?>: </b></TD>
            <td width="70%"><select name="thematic" align="center">
            <option value="0" SELECTED><?=$MSG_LANG["no"]?></option>
            <option value="1"><?=$MSG_LANG["alekhine"]?></option>
            <option value="2"><?=$MSG_LANG["birds"]?></option>
            <option value="3"><?=$MSG_LANG["budapest"]?></option>
            <option value="4"><?=$MSG_LANG["catalan"]?></option>
            <option value="5"><?=$MSG_LANG["carokann"]?></option>
            <option value="6"><?=$MSG_LANG["cochranegambit"]?></option>
            <option value="7"><?=$MSG_LANG["dutchdefense"]?></option>
            <option value="8"><?=$MSG_LANG["leningraddutch"]?></option>
            <option value="9"><?=$MSG_LANG["fourknights"]?></option>
            <option value="10"><?=$MSG_LANG["frenchdefense"]?></option>
            <option value="11"><?=$MSG_LANG["frenchadvance"]?></option>
            <option value="12"><?=$MSG_LANG["frenchclassical"]?></option>
            <option value="13"><?=$MSG_LANG["frenchrubinstein"]?></option>
            <option value="14"><?=$MSG_LANG["frenchwinawer"]?></option>
            <option value="15"><?=$MSG_LANG["frenchtarrasch"]?></option>
            <option value="16"><?=$MSG_LANG["grob"]?></option>
            <option value="17"><?=$MSG_LANG["grunfeld"]?></option>
            <option value="18"><?=$MSG_LANG["kingsgambit"]?></option>
            <option value="19"><?=$MSG_LANG["kingsindian"]?></option>
            <option value="20"><?=$MSG_LANG["italiangame"]?></option>
            <option value="21"><?=$MSG_LANG["larsens"]?></option>
            <option value="22"><?=$MSG_LANG["modernbenoni"]?></option>
            <option value="23"><?=$MSG_LANG["muziogambit"]?></option>
            <option value="24"><?=$MSG_LANG["nimzoindian"]?></option>
            <option value="25"><?=$MSG_LANG["petroff"]?></option>
            <option value="26"><?=$MSG_LANG["philidor"]?></option>
            <option value="27"><?=$MSG_LANG["queensgambit1"]?></option>
            <option value="28"><?=$MSG_LANG["queensgambit2"]?></option>
            <option value="29"><?=$MSG_LANG["queensindian"]?></option>
            <option value="30"><?=$MSG_LANG["ruylopez"]?></option>
            <option value="31"><?=$MSG_LANG["scandinavian"]?></option>
            <option value="32"><?=$MSG_LANG["scotch"]?></option>
            <option value="33"><?=$MSG_LANG["sicilian"]?></option>
            <option value="34"><?=$MSG_LANG["sicilianalapin"]?></option>
            <option value="35"><?=$MSG_LANG["sicilianclosed"]?></option>
            <option value="36"><?=$MSG_LANG["siciliansveshnikov"]?></option>
            <option value="37"><?=$MSG_LANG["siciliansimagin"]?></option>
            <option value="38"><?=$MSG_LANG["sicilianpaulsen"]?></option>
            <option value="39"><?=$MSG_LANG["sicilianrichterrauzer"]?></option>
            <option value="40"><?=$MSG_LANG["siciliandragon"]?></option>
            <option value="41"><?=$MSG_LANG["sicilianscheveningen"]?></option>
            <option value="42"><?=$MSG_LANG["siciliansozin"]?></option>
            <option value="43"><?=$MSG_LANG["siciliannajdorf"]?></option>
            <option value="44"><?=$MSG_LANG["slav"]?></option>
            <option value="45"><?=$MSG_LANG["sokolsky1"]?></option>
            <option value="46"><?=$MSG_LANG["tarrasch"]?></option>
            <option value="47"><?=$MSG_LANG["vienna"]?></option>
            <option value="48"><?=$MSG_LANG["volgagambit"]?></option>
            </select></TD></TR>

         <tr>
             <td colspan="2"><input type='button' style='cursor:hand; font-size:10pt' value='Create Game' onClick='document.invite.submit();' id='flashit'></td></tr>
         </table>
         </form>
         </td>
         </tr>
         </table><BR><BR>

         <?

                    echo "<table border='1' width='100%'><tr><th colspan='8'>Games Created by Other Players - Join Now!</th></tr><tr><td align='center'>Time Posted</td><td>".$MSG_LANG["opponent"]."</td><td align='center'>".$MSG_LANG["rating"]."</td><td align='center'>".$MSG_LANG["sendmessage"]."</td><td  align='center'>".$MSG_LANG["thematic"]."</td><td  align='center'>".$MSG_LANG["yourcolor"]."</td><td  align='center'>Days per Move</td><td  align='center'>Join</td></tr>";

        $tmpQuery = "SELECT * FROM games WHERE gameMessage = 'playerInvited' AND postGame = '1' AND whitePlayer != '".$_SESSION['playerID']."' AND blackPlayer != '".$_SESSION['playerID']        ."' ORDER BY dateCreated";
        $tmpGames = mysql_query($tmpQuery);
        if (mysql_num_rows($tmpGames) == 0){

        echo "<tr><td colspan='9' align='center'>No other players have created a game at this time - Why not Create your Own?</td></tr></table>";

        }else{

        while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC)){

        if ($tmpGame['whitePlayer'] != $_SESSION['playerID'] && $tmpGame['blackPlayer'] != $_SESSION['playerID']){
                        echo "<form name='reply' action='joingame_two.php' method='post'>";



                        if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
                                $tmpFrom = "white";
                        else
                                $tmpFrom = "black";

                                echo ("<tr><td align='center'>".$tmpGame['dateCreated']."</td>");

                                if ($tmpGame['blackPlayer'] == 0){

                                $tbl = mysql_query("SELECT * FROM players WHERE playerID='".$tmpGame['whitePlayer']."'");
                                $opl = mysql_fetch_array($tbl);

                                echo "<td align='center'>".$opl['firstName']."</td><td bgcolor='FFFFFF' align='center'>".$opl['rating']."</td><td bgcolor='FFFFFF' align='center'><input style='font-size:7pt; cursor:hand' type='button' name='mspl' value='".$MSG_LANG["sendmessage"]."' onClick='MessagePlayer({$opl['playerID']})'></td>";

                                }else{

                                $vbl = mysql_query("SELECT * FROM players WHERE playerID='".$tmpGame['blackPlayer']."'");
                                $wpl = mysql_fetch_array($vbl);

                                echo "<td align='center'>".$wpl['firstName']."</td><td bgcolor='FFFFFF' align='center'>".$wpl['rating']."</td><td bgcolor='FFFFFF' align='center'><input style='font-size:7pt; cursor:hand' type='button' name='nspl' value='".$MSG_LANG["sendmessage"]."' onClick='MessagePlayer({$wpl['playerID']})'></td>";

                                }
                                if ($tmpGame[thematic] == 1){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[alekhine]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 2){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[birds]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 3){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[budapest]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 4){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[catalan]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 5){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[carokann]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 6){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[cochranegambit]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 7){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[dutchdefense]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 8){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[leningraddutch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 9){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[fourknights]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 10){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[frenchdefense]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 11){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[frenchadvance]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 12){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[frenchclassical]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 13){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[frenchrubinstein]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 14){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[frenchwinawer]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 15){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[frenchtarrasch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 16){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[grob]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 17){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[grunfeld]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 18){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[kingsgambit]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 19){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[kingsindian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 20){
                                        echo "<td align='center'><font color='800000'>$MSG_LANG[italiangame]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 21){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[larsens]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 22){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[modernbenoni]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 23){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[muziogambit]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 24){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[nimzoindian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 25){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[petroff]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 26){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[philidor]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 27){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[queensgambit1]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 28){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[queensgambit2]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 29){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[queensindian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 30){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[ruylopez]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 31){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[scandinavian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 32){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[scotch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 33){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[sicilian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 34){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[sicilianalapin]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 35){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[sicilianclosed]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 36){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[siciliansveshnikov]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 37){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[siciliansimagin]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 38){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[sicilianpaulsen]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 39){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[sicilianrichterrauzer]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 40){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[siciliandragon]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 41){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[sicilianscheveningen]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 42){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[siciliansozin]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 43){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[siciliannajdorf]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 44){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[slav]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 45){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[sokolsky1]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 46){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[tarrasch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 47){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[vienna]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 48){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[volgagambit]</font></td>\n";
                                }else{
                                        echo "<td align='center' ><font color='800000'>No</font></td>\n";
                                }
                                echo ("<td bgcolor='FFFFFF' align='center'>");
                                /* Your Color */
                                
                                if ($tmpGame['whitePlayer'] == $opl['playerID'])
                                {
                                        echo $MSG_LANG["black"];
                                        $tmpFrom = "black";
                                }
                                else
                                {
                                        echo $MSG_LANG["white"];
                                        $tmpFrom = "white";
                                }

        if ($tmpGame[timelimit] == 0)
            echo "<td bgcolor='#FFFFFF' align='center'>14</td>\n";
            else
            echo "<td bgcolor='FFFFFF' align='center'>".($tmpGame['timelimit']/24/60)."</td>";

           /* Response  */

            echo ("<td width=10% bgcolor='FFFFFF' align='center'>");
            echo ("<input type='button' style='font-size:8pt; cursor:hand' value='".$MSG_LANG["accept"]."' onclick=\"sendResponse('accepted', '".$tmpFrom."', ".$tmpGame['gameID'].")\">");
            echo("</td></tr>\n");
                }
        }
}



        echo "</table>
              <input type='hidden' name='response' value='accepted'>
              <input type='hidden' name='messageFrom' value='".$tmpGame['messageFrom']."'>
              <input type='hidden' name='gameID' value='".$tmpGame['gameID']."'>
              <input type='hidden' name='responseType' value='oficial'>
              <input type='hidden' name='ToDo' value='responseToInvite'>
              </form>";
       echo "<br>";

    /* if game is marked playerInvited and the invite is from the current player */
    $tmpQuery = "SELECT * FROM games WHERE (gameMessage = 'playerInvited' AND postGame = '1' AND ((whitePlayer = ".$_SESSION['playerID']." AND messageFrom = 'white') OR (blackPlayer = ".$_SESSION['playerID']." AND messageFrom = 'black'))";
    /* OR game is marked inviteDeclined and the response is from the opponent */
    $tmpQuery .= ") OR (gameMessage = 'inviteDeclined' AND postGame = '1' AND ((whitePlayer = ".$_SESSION['playerID']." AND messageFrom = 'black') OR (blackPlayer = ".$_SESSION['playerID']." AND messageFrom = 'white')))  ORDER BY dateCreated";
    $tmpGames = mysql_query($tmpQuery);

?>
    <table border="1" width="750">
        <tr><th colspan='9'>You have Created the Following Games</th></tr>

    <tr>
        <td  align="center">Time Posted</td>
        <td  align="center"><?=$MSG_LANG["yourcolor"]?></td>
        <td  align="center">Days per Move</td>
        <td  align="center">Thematic</td>
        <td  align="center"><?=$MSG_LANG["astatusa"]?></td>
        <td  align="center"><?=$MSG_LANG["action"]?></td>
    </tr>
<?

    
    if (mysql_num_rows($tmpGames) == 0){

        echo "<tr><td colspan='7' bgcolor='FFFFFF' align='center'>You have not Created any Games or they have all Expired (14 days)</td></tr></table>";

        }else{

?>
    <form name="withdrawRequestForm" action="joingame.php" method="post">

    <?
        while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC)){
        if ($tmpGame['postGame'] == 1){

            /* Opponent */
            echo("<tr><td bgcolor='FFFFFF' align='center'>");
            /* get opponent's firstName */
            if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
                $tmpOpponent = mysql_query("SELECT firstName,lastName,playerID FROM players WHERE playerID = ".$tmpGame['blackPlayer']);
            else
                $tmpOpponent = mysql_query("SELECT firstName,lastName,playerID FROM players WHERE playerID = ".$tmpGame['whitePlayer']);

            $row = mysql_fetch_array($tmpOpponent);
            $opponent = $row[0];
            $id = $row[2];

            //echo "<a href='stats_user.php?cod=$id'>$opponent</a>";

        echo "".$tmpGame['dateCreated']."";
            
            /* Your Color */
            echo ("</td><td bgcolor='FFFFFF' align='center'>");
            if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
                echo ($MSG_LANG["white"]);
            else
                echo ($MSG_LANG["black"]);

            if ($tmpGame['oficial'] == "1")
                $oficial = $MSG_LANG["official"];
            else $oficial = $MSG_LANG["notofficial"];
            if ($tmpGame[timelimit] == 0)
            echo "<td bgcolor='#FFFFFF' align='center'>14</td>\n";
            else
            echo "<td bgcolor='FFFFFF' align='center'>".($tmpGame['timelimit']/24/60)."</td>";
            if ($tmpGame[thematic] == 1){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[alekhine]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 2){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[birds]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 3){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[budapest]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 4){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[catalan]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 5){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[carokann]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 6){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[cochranegambit]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 7){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[dutchdefense]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 8){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[leningraddutch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 9){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[fourknights]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 10){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[frenchdefense]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 11){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[frenchadvance]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 12){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[frenchclassical]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 13){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[frenchrubinstein]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 14){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[frenchwinawer]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 15){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[frenchtarrasch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 16){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[grob]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 17){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[grunfeld]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 18){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[kingsgambit]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 19){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[kingsindian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 20){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[italiangame]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 21){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[larsens]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 22){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[modernbenoni]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 23){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[muziogambit]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 24){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[nimzoindian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 25){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[petroff]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 26){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[philidor]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 27){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[queensgambit1]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 28){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[queensgambit2]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 29){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[queensindian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 30){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[ruylopez]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 31){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[scandinavian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 32){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[scotch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 33){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[sicilian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 34){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[sicilianalapin]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 35){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[sicilianclosed]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 36){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[siciliansveshnikov]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 37){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[siciliansimagin]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 38){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[sicilianpaulsen]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 39){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[sicilianrichterrauzer]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 40){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[siciliandragon]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 41){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[sicilianscheveningen]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 42){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[siciliansozin]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 43){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[siciliannajdorf]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 44){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[slav]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 45){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[sokolsky1]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 46){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[tarrasch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 47){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[vienna]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 48){
                                        echo "<td align='center' ><font color='800000'>$MSG_LANG[volgagambit]</font></td>\n";
                                }else{
                                        echo "<td align='center' ><font color='800000'>No</font></td>\n";
                                }
            /* Status */
            echo ("</td><td align='center'>");
            if ($tmpGame['gameMessage'] == 'playerInvited')
                echo $MSG_LANG["pendingreply"];
            else if ($tmpGame['gameMessage'] == 'inviteDeclined'){
                echo $MSG_LANG["invitedeclined"];
                if (substr($tmpGame['reason'],0,1)=="#")
                    echo ":<BR>".$MSG_LANG[$tmpGame['reason']];
            }    
            /* Withdraw Request */
            echo ("</td><td align='center' bgcolor='FFFFFF'>");
            echo ("&nbsp;<img src='images/stop.gif'>&nbsp;<input type='button' style='font-size:8pt; cursor:hand' value='".$MSG_LANG['cancel']."' onclick=\"withdrawRequest(".$tmpGame['gameID'].")\">");

            echo("&nbsp;</td></tr>\n");
                }
        }

        echo '<input type="hidden" name="gameID" value="">
        <input type="hidden" name="ToDo" value="WithdrawRequest">
        </form>';
        }

        echo "</table><br>";

?>

<script language="JavaScript1.2">
// distributed by http://www.hypergurl.com
if (document.all&&document.all.flashit){

var flashelement=document.all.flashit
if (flashelement.length==null)
flashelement[0]=document.all.flashit

function changecolor(which){
if (flashelement[which].style.color=='')
flashelement[which].style.color="red"
else
flashelement[which].style.color=""
}


if (flashelement.length==null)
setInterval("changecolor(0)",1000)
else
for (i=0;i<flashelement.length;i++){
var tempvariable='setInterval("changecolor('+i+')",'+'1000)'
eval(tempvariable)
}

}
</script>


<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>
<BR>
<?include("footer.inc.php")?>
<BR>
</body>
</html>
