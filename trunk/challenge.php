<?php
##############################################################################################
#                                                                                            #
#                                challenge.php
# *                            -------------------                                           #
# *   begin                : Wednesday, January 25, 2005
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://developer.berlios.de/projects/chess/                              #
# *   VERSION:             : $Id:$
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
$MSG_LANG_CHALLENGE['days_per_move'] = " days per move";
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
                //TODO - fix this crap pay attention to the post values- set a var for thematic and if not set in post set it to a ddefault
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

    } //end challenge action

    //TODO - determine if this can be a rated game or not- check if either player has trials_left
    //if either does, it cannot be a rated game
    $sql = $db->Prepare("select trials_left from {$db_prefix}player_stats where player_id = ?");
    $query = $db->Execute($sql,array($_SESSION['player_id']));
    db_op_result($query,__LINE__,__FILE__);
    $trials = $query->fields;
    $my_trials = $trials['trials_left'];
    if(!empty($_POST['player_id']))
    {
          $query = $db->Execute($sql,array($_POST['player_id']));
          db_op_result($query,__LINE__,__FILE__);
          $trials = $query->fields;
          $their_trials = $trials['trials_left'];
    }

    if($my_trials > 0 || $their_trials > 0)
    {
        $smarty->assign('is_trial',"true");
    }

$smarty->assign('lang_game_settings',$MSG_LANG['gamesettings']);
$smarty->assign('lang_your_color',$MSG_LANG["yourcolor"]);
$smarty->assign('lang_random',$MSG_LANG["random"]);
$smarty->assign('lang_white',$MSG_LANG["white"]);
$smarty->assign('lang_black',$MSG_LANG["black"]);
$smarty->assign('lang_is_rated',$MSG_LANG["isofficial"]);
$smarty->assign('lang_yes',$MSG_LANG["yes"]);
$smarty->assign('lang_no',$MSG_LANG["no"]);
if($allow_thematics)
{
     include_once("languages/".$_SESSION['pref_language']."/thematics.inc.php");
     $thematic_names = array();
     $thematic_values = array();
    $query = $db->Execute("SELECT id,key FROM {$db_prefix}thematics WHERE 1");
    db_op_result($query,__LINE__,__FILE__);
    while(!$query->EOF)
    {
         $values = $query->fields;
         $key = $values['key'];
         $name = $MSG_LANG_THEMATIC[$key];
         if(empty($name))
         {
               //we have no name, so no value either
               $skip = "skip";
         }
         else
         {
               array_push($thematic_names,$name);
               array_push($thematic_values,$values['id']);
         }
         $query->MoveNext();
    }
     $smarty->assign('lang_thematic',$MSG_LANG["thematic"]);
     $smarty->assign('thematic_names',$thematic_names);
     $smarty->assign('thematic_values',$thematic_values);
}//end thematics assignments

$smarty->assign('lang_time_each',$MSG_LANG["timeforeach"]);
$smarty->assign('lang_time_limit',$MSG_LANG["movetimeout"]);

//stick these in a DB table as well, make it admin configurable
$time_limit_ids = array('0');
$time_limit_values = array($expire_game);

$smarty->assign('time_limit_values',$time_limit_values);
$smarty->assign('time_limit_ids',$time_limit_ids);
$smarty->assign('lang_days_move',$MSG_LANG_CHALLENGE['days_per_move']);
   //TODO- Set up a bunch of configurable time limits for game challenges and give it to smarty as an array
   //or possibly just a language file - we can set time limit automatically :)
  //  for ($x=1; $x<$CFG_EXPIREGAME; $x++)
     //   echo "<option value='".($x*1440)."'>$x days per move</option>\n";
    //for ($y=1; $y<$CFG_EXPIREGAME; $y++)
        //echo "<option value='".($y)."'>$y days per move</option>\n";
$smarty->assign('lang_choose_player',$MSG_LANG['chooseplayertojoin']);

                $player_list = array();
                $player_ids = array();
                $sql = $db->Prepare("SELECT player_id, username FROM {$db_prefix}players WHERE last_update >= DATE_SUB(now(),interval 90 day) and player_id != ? AND active ='1' ORDER BY username ASC");
                $query = $db->Execute($sql,array($_SESSION['player_id']));
                db_op_result($query,__LINE__,__FILE__);
                while(!$query->EOF)
                {
                    $tmpPlayer = $query->fields;

                     if ($tmpPlayer['username'] != '')
                     {
                           if(!empty($_POST['player_id']))
                           {
                              if($tmpPlayer['player_id'] == $_POST['player_id'])
                              {
                                   $smarty->assign('selected_player',$tmpPlayer['player_id']);
                              }
                           }
                           array_push($player_list,$tmpPlayer['username']);
                           array_push($player_ids,$tmpPlayer['player_id']);
                     }
                    $query->MoveNext();
                }

                $smarty->assign('player_list',$player_list);
                $smarty->assign('player_ids',$player_ids);
$smarty->assign('lang_invite_button',$MSG_LANG['invite']);

    // if game is marked playerInvited and the invite is from the current player
    $sql = "SELECT white_player,black_player,status,game_id,rated  FROM {$db_prefix}games WHERE (status = 'playerInvited' AND ((white_player = ? AND message_from = 'white') OR (black_player = ? AND message_from = 'black'))";
    // OR game is marked inviteDeclined and the response is from the opponent
    $sql .= ") OR (status = 'inviteDeclined' AND ((white_player = ? AND message_from = 'black') OR (black_player = ? AND message_from = 'white')))  ORDER BY date_created";
    $sql_arr = array($_SESSION['player_id'],$_SESSION['player_id'],$_SESSION['player_id'],$_SESSION['player_id']);
    $query = $db->Execute($sql,$sql_arr);
    db_op_result($query,__LINE__,__FILE__);
    $number_games = $query->RecordCount();
    if ($number_games > 0)
    {
        $smarty->assign('lang_current_invites',$MSG_LANG["currentinvitations"]);
        //echo("<tr><td colspan='5'>".$MSG_LANG["noinvations"].".</td></tr>\n");
        $smarty->assign('lang_invited_opponent',$MSG_LANG["opponent"]);
        $smarty->assign('lang_invited_your_color',$MSG_LANG["yourcolor"]);
        $smarty->assign('lang_invited_type',$MSG_LANG["type"]);
        $smarty->assign('lang_invited_status',$MSG_LANG["status"]);
        $smarty->assign('lang_invited_action',$MSG_LANG["action"]);

        $invited_games = array();
        while(!$query->EOF)
        {
            $tmpGame = $query->fields;
            // Opponent

            // get opponent's nick
            if ($tmpGame['white_player'] == $_SESSION['player_id'])
            {
                $tmpOpponent = mysql_query("SELECT username,player_id FROM {$db_prefix}players WHERE player_id = ".$tmpGame['black_player']);
            }
            else
            {
                $tmpOpponent = mysql_query("SELECT username,player_id FROM {$db_prefix}players WHERE player_id = ".$tmpGame['white_player']);
            }
            $row = mysql_fetch_array($tmpOpponent);
            $invited['opponent'] = $row['username'];
            $invited['player_id'] = $row['player_id'];

            if ($tmpGame['white_player'] == $_SESSION['player_id'])
            {
                $invited['your_color'] = $MSG_LANG["white"];
            }
            else
            {
               $invited['your_color'] = $MSG_LANG["black"];
            }

            if ($tmpGame['rated'] == "1")
            {
               $invited['rated'] = $MSG_LANG["official"];
            }
            else
            {
                $invited['rated'] = $MSG_LANG["notofficial"];
            }

            if ($tmpGame['status'] == 'playerInvited')
            {
                 $invited['status'] = $MSG_LANG["pendingreply"];
            }
            else if ($tmpGame['status'] == 'inviteDeclined')
            {
                $invited['status'] = $MSG_LANG["invitedeclined"];
                if (substr($tmpGame['reason'],0,1)=="#")
                {
                    $invited['status'] .= ":<BR>".$MSG_LANG[$tmpGame['reason']];
                }
            }
            $invited['cancel_button'] = $MSG_LANG['cancel'];
            $invited['game_id'] = $tmpGame['game_id'];

             array_push($invited_games,$invited);
           $query->MoveNext();
        }
        $smarty->assign('invited_games',$invited_games);

    }
    $template_set = $_SESSION['template_set'];
    if(empty($template_set))
    {
         $template_set = "default";
    }
    $title = "CompWebChess";
    $title2 = "Challenge Players";
    $smarty->assign('title',$title);
    $smarty->assign('title2',$title2);
    $smarty->assign('template_set',$_SESSION['template_set']);
    $smarty->assign('theme_set',$_SESSION['theme_set']);
    $smarty->display("$template_set/challenge.tpl");
    exit();
?>