<?php
##############################################################################################
#                                                                                            #
#                                newuserchallenge.php
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
        //load settings
include_once ('global_includes.php');

//start ob_start(); stuff
//this is some kludgy login checking shit at best .. but for the time being, it's the least of the worries.
//check user login and load user data if not set in session.
if(empty($_SESSION) || check_login($_SESSION['player_id'],$_COOKIE['PHPSESSID']) == false)
{
    if($_SESSION)
    {
        session_destroy();
    }
    header("Location: index.php");
}


    // load external functions for setting up new game */
    require_once ('chessutils.php');
    require_once ('newgame.php');
    require_once ('chessdb.php');

    // Language selection */
include_once ("languages/".$_SESSION['pref_language']."/strings.inc.php");

//Put language file entries for new scripts here at the top- if there's only a few, add to strings.inc.php

$MSG_LANG_CHALLENGE['thank_you'] ="Thank you. Your challenge has been sent. ";
$MSG_LANG_CHALLENGE['redirect'] = "<br>Your Browser will redirect you to the main menu in 10 seconds, or you can <a href='mainmenu.php'> CLICK HERE</a> if you do not wish to wait";
    // connect to database

    $id=$_SESSION['player_id'];

    $thankyou_message = $MSG_LANG_CHALLENGE['thank_you'];
    $redirect_message = $MSG_LANG_CHALLENGE['redirect'];

    if(!empty($_POST['ToDo']))  //kind of shitty, it will do for now, we need to fix it in the temnplate
    {
        //run the challenge sent stuff- why on earth is it sent via separate script?
        //challenmges should be all one script anyway
        //this can be used for all challenges and get rid of a BUNCH of redundant code
        if (!$allow_multiple)
          {
                $tmpQuery = "SELECT game_id FROM {$db_prefix}games WHERE (status = '' OR status='playerInvited')";
                $tmpQuery .= "AND ((white_player = ".$_SESSION['player_id']." AND black_player= ".$_POST['opponent'].")";
                $tmpQuery .= " OR (white_player=".$_POST['opponent']." AND black_player=".$_SESSION['player_id']."))";
                $tmpExistingRequests = mysql_query($tmpQuery);
                $ngames = mysql_num_rows($tmpExistingRequests);
            }
            else
            {
               $ngames = 0;
            }

            if ($enable_trial_games)
            {
                    /* Check if newuser playing newuser */
                    //if (getRating($_SESSION['player_id']) == 0 && getRating($_POST['opponent']) == 0){
                        //displayError("New Users are not allowed to play eachother!", "newuserchallenge.php");
                        //}
                    /* Check if the number of games don´t reached 5*/
                    if ($_SESSION['rating'] == 0)
                    {
                         //TODO
                        //HERE we get trial games left, total games completed, total invitations outstanding
                        // and issue an error for THIS
                        //player that they have not completed  the minimum trial games
                        echo "Trial Games Output - SEE TODO IN THIS FILE<br>";
                    }
                    if ($_POST['opponent'] != 0)
                    {
                        //TODO Find out if opponent has trials left, how many completed, how many invitations
                        //and require the trial games to be completed before they can be invited further
                        //
                        echo "Opponent Trial Games warning -- SEE TODO IN THIS FILE<br>";
                    }

            }


            if ($ngames == 0)
            {


                if ($_POST['color'] == 'random')
                {
                    $tmpColor = (rand(0,1) == 1) ? "white" : "black";
                }
                else
                {
                    $tmpColor = $_POST['color'];
                }

                $tmpQuery = "INSERT INTO {$db_prefix}games (time_limit, white_player, black_player, status, message_from, date_created, last_move,rated,thematic) VALUES (";
                if ($tmpColor == 'white')
                {
                    $white = $_SESSION['player_id'];
                    $black = $_POST['opponent'];
                }
                else
                {
                    $white = $_POST['opponent'];
                    $black = $_SESSION['player_id'];
                }

                $tmpQuery .= "'".$_POST['time_limit']."', $white, $black, 'playerInvited', '".$tmpColor."', NOW(), NOW(),'".$_POST['rated']."','".$_POST['thematic']."')";
                mysql_query($tmpQuery);

                /* if email notification is activated... */
                if ($allow_email_notify)
                {

                    $tmpOpponentEmail = mysql_query("SELECT notify_invite FROM {$db_prefix}player_preference WHERE player_id = ".$_POST['opponent']);
                    $res = mysql_fetch_array($tmpOpponentEmail);

                      if ($res['notify_invite'] == 'E')
                      {
                            //Send Email notification via email function
                            //TODO- apply email function here
                            //webchessMail('invitation', $opponentEmail, '', $_SESSION['firstName']);
                            echo "SEE TODO on THIS FILE - email function required<br>";
                      }
                      if($res['notify_invite'] == "M")
                      {
                           //TODO: Create and store an IM message for this player for their next log on
                           echo "SEE TODO on THIS FILE - IM notification function required<br>";
                      }
                      else
                      {
                            //PASS THROUGH- ASSUME 'N" , no notification desired
                            //TODO: create a error log item if not 'N', if we get here
                            // no biggie, just should be done ...
                      }

                }
            }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta name="Keywords" content="chess,ajedrez,échecs,echecs,scacchi,schach,check,check mate,jaque,jaque mate,queenalice,queen alice,queen,alice,play,game,games,turn based,correspondence,correspondence chess,online chess,play chess online">
        <META HTTP-EQUIV=Refresh CONTENT="10; URL=mainmenu.php">
        <title>Challenge</title>

<LINK rel="stylesheet" href="themes/<?=$_SESSION["theme_set"]?>/mainstyles.css" type="text/css">

    </head>

    <body>
</TD></TR>
<table width="100%" border="1">
  <tr>
    <td><font color="#FF0000" size="4">
      <strong>
<?php
echo $thankyou_message;
echo $redirect_message;
?>

      </strong>    </font>      <div align="center"></div>
    <div align="center"></div></td>
  </tr>
</table>
</table>
    </div>
        </body>

</html>
<?php
exit();
die();

    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta name="Keywords" content="chess,ajedrez,échecs,echecs,scacchi,schach,check,check mate,jaque,jaque mate,queenalice,queen alice,queen,alice,play,game,games,turn based,correspondence,correspondence chess,online chess,play chess online">

        <title>Challenge</title>

<LINK rel="stylesheet" href="themes/<?php echo $_SESSION["theme_set"]; ?>/mainstyles.css" type="text/css">


</head>

    <body>
<table width="600">
    <form name="invite" action="" method="post">
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
                <input type='hidden' name='rated' value='0'>
                <select name="rateddisabled" disabled=true>
                <option value="1"><?=$MSG_LANG["yes"]?></option>
                <option value="0" SELECTED><?=$MSG_LANG["no"]?></option>
                </select>
          </TD></TR>
<TR>
            <TD style="text-align:left">              <?=$MSG_LANG["thematic"]?>
              <img src="images/new.gif" width="40" height="36"></TD>
            <TD><select name="thematic" align="right">
            <option value="0" SELECTED><?=$MSG_LANG["no"]?> TODO: Move this to database driven stuff</option>
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
        <TD style="text-align:left"><?=$MSG_LANG["timeforeach"]?>:</TD>
        <TD style="text-align:left">
          <select name="time_limit">
        <option value="0" SELECTED><?=$MSG_LANG["movetimeout"]?> SEE TODO!</option>
<?php
   //TODO- Set up a bunch of configurable time limits for game challenges and give it to smarty as an array
   //or possibly just a language file - we can set time limit automatically :)
  //  for ($x=1; $x<$CFG_EXPIREGAME; $x++)
     //   echo "<option value='".($x*1440)."'>$x days per move</option>\n";
    //for ($y=1; $y<$CFG_EXPIREGAME; $y++)
        //echo "<option value='".($y)."'>$y days per move</option>\n";
?>
        </select>
        <?=$MSG_LANG["movetimeout"]?>:<?=$expire_game?> <?=$MSG_LANG["days"]?>

        <TR>
        <TD style="text-align:left"><?=$MSG_LANG["chooseplayertojoin"]?>:</TD>
        <TD style="text-align:left">

                <select name="opponent" size="1">
<?php



                //$tmpQuery="SELECT * FROM {$db_prefix}players WHERE engine='0' and player_id <> ".$id." AND active ='1' and rating>0 ORDER BY username ASC";

                $tmpQuery="SELECT player_id, username FROM {$db_prefix}players WHERE last_update >= DATE_SUB(now(),interval 14 day) and player_id != ".$id." AND active ='1' ORDER BY username ASC";
                $tmpPlayers = mysql_query($tmpQuery);
                while($tmpPlayer = mysql_fetch_array($tmpPlayers, MYSQL_ASSOC))
                {
                     if ($tmpPlayer['username'])
                     {
                           if($tmpPlayer['player_id']==$_GET['opponent'])
                           {
                                 echo("<option value='".$tmpPlayer['player_id']."' selected>".$tmpPlayer['username']."</option>\n");
                            }
                            else
                            {
                                 echo("<option value='".$tmpPlayer['player_id']."'> ".$tmpPlayer['username']."</option>\n");
                            }
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
    // if game is marked playerInvited and the invite is from the current player
    $tmpQuery = "SELECT white_player,black_player,status,game_id,rated  FROM {$db_prefix}games WHERE (status = 'playerInvited' AND ((white_player = ".$_SESSION['player_id']." AND message_from = 'white') OR (black_player = ".$_SESSION['player_id']." AND message_from = 'black'))";
    // OR game is marked inviteDeclined and the response is from the opponent
    $tmpQuery .= ") OR (status = 'inviteDeclined' AND ((white_player = ".$_SESSION['player_id']." AND message_from = 'black') OR (black_player = ".$_SESSION['player_id']." AND message_from = 'white')))  ORDER BY date_created";
    $tmpGames = mysql_query($tmpQuery);

    if (mysql_num_rows($tmpGames) > 0)
    {
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
            // Opponent
            echo("<tr><td>");
            // get opponent's nick
            if ($tmpGame['white_player'] == $_SESSION['player_id'])
                $tmpOpponent = mysql_query("SELECT username,player_id FROM {$db_prefix}players WHERE player_id = ".$tmpGame['black_player']);
            else
                $tmpOpponent = mysql_query("SELECT username,player_id FROM {$db_prefix}players WHERE player_id = ".$tmpGame['white_player']);

            $row = mysql_fetch_array($tmpOpponent);
            $opponent = $row['username'];
            $id = $row['player_id'];
            echo "<a href='stats_user.php?cod=$id'>$opponent</a>";

            // Your Color
            echo ("</td><td>");
            if ($tmpGame['white_player'] == $_SESSION['player_id'])
                echo ($MSG_LANG["white"]);
            else
                echo ($MSG_LANG["black"]);

            if ($tmpGame['rated'] == "1")

                $rated = $MSG_LANG["official"];
            else $rated = $MSG_LANG["notofficial"];

            echo "<td>".$rated."</td>";

            // Status
            echo ("</td><td>");
            if ($tmpGame['status'] == 'playerInvited')
                echo $MSG_LANG["pendingreply"];
            else if ($tmpGame['status'] == 'inviteDeclined'){
                echo $MSG_LANG["invitedeclined"];
                if (substr($tmpGame['reason'],0,1)=="#")
                    echo ":<BR>".$MSG_LANG[$tmpGame['reason']];
            }
            // Withdraw Request
            echo ("</td><td align='center'>");
            echo ("<input type='button' value='".$MSG_LANG['cancel']."' onclick=\"withdrawRequest(".$tmpGame['game_id'].")\">");

            echo("</td></tr>\n");
        }
        echo '
        </table>
        <input type="hidden" name="game_id" value="">
        <input type="hidden" name="ToDo" value="WithdrawRequest">
        </form><BR>';
    }
?>


</body>

</html>
