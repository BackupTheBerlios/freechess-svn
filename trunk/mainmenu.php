<?php
##############################################################################################
#                                                                                            #
#                                mainmenu.php                                                #
# *                            -------------------                                           #
# *   begin                : Wednesday, January 25, 2005
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://developer.berlios.de/projects/chess/                              #
# *   VERSION:             : $Id: mainmenu.php,v 1.9 2005/03/10 02:25:22 trukfixer Exp $
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
//this is some kludgy shit at best .. but for the time being, it's the least of the worries.

##############################################################
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

    // set default playing mode to different PCs (as opposed to both players sharing a PC) */
$_SESSION['isSharedPC'] = false;
        // check if loading game */



$template_set = 'default';
   //if not set, let's default them
   if(empty($_SESSION['theme_set']))
   {
        $_SESSION['theme_set'] =   "SilverGrey";
   }
$smarty->assign('theme',$_SESSION['theme_set']);
$smarty->assign('template_set',$template_set);
//OK this is main menu, here we will put a check for the still experimental main_reload_allowed
//once we have removed the crap that must be reloaded frequently
 if(empty($_SESSION['pref_autoreload']))
 {
     $_SESSION['pref_autoreload'] = $auto_reload_min;
 }

    if ($_SESSION['pref_autoreload'] >= $auto_reload_min)
    {
        $smarty->assign('reload_speed',$_SESSION['pref_autoreload']);
    }
    else
    {
          $smarty->assign('reload_speed',$auto_reload_min);
    }
    if(empty($_SESSION['pref_board_size']))
    {
         $_SESSION['pref_board_size'] = "50";
    }




$smarty->assign('lang_main_mygames',$MSG_LANG["mygames"]);
$smarty->assign('paypal',$paypal);

//get list of ended games starting with most recently ended for display



        $sql = $db->Execute("SELECT username,player_id FROM {$db_prefix}players WHERE active='1' ORDER BY last_update DESC limit 15");
        db_op_result($sql,__LINE__,__FILE__);
        $row = $sql->fields;

        $lastuser = $row['username'];
        $lastuserid = $row['player_id'];

        $query = $db->Execute("SELECT count(*) as count FROM {$db_prefix}players WHERE last_update > DATE_SUB(NOW(),INTERVAL '15:0' MINUTE_SECOND)");
        db_op_result($query,__LINE__,__FILE__);

        $row = $query->fields;
        $online = $row['count'];

        $smarty->assign('player_name',$_SESSION['username']);

        //$ranking = getRanking($_SESSION['player_id']);
        //$rating = getRating($_SESSION['player_id']);
        $rating = $_SESSION['rating'];
        //$ranking = get_ranking($_SESSION['player_id']);
        //TODO fix get player data to get ranking as well as rating ?


        if ($enable_trial_games && $rating == 0)
        {
            //move to config options
            $sql_arr = array($_SESSION['player_id'],$_SESSION['player_id']);
            $sql = $db->Prepare("select count(*) as count from {$db_prefix}games where (white_player=? OR black_player=?) AND (status = 'playerResigned' OR status = 'checkMate' OR status = 'draw')");
            $query = $db->Execute($sql,$sql_arr);
            db_op_result($query,__LINE__,__FILE__);
            $row = $query->fields;
            $number_trials_left = $trial_games_required - $row['count'];
            $smarty->assign('lang_trial_process',$MSG_LANG['trialprocess']);
            $leftovers = str_replace("%n",$number_trials_left,$MSG_LANG['trialmessage']);
            $smarty->assign('lang_trial_message',$leftovers);
            $smarty->assign('on_trial',"true");

        }
        else
        {

            $smarty->assign('lang_your_rating',$MSG_LANG['yourrating']);
            $smarty->assign('rating_value',$rating);
            $smarty->assign('lang_your_ranking',$MSG_LANG['yourranking']);
            $smarty->assign('ranking_value',$ranking);
        }
$smarty->assign('newest_user_id',$lastuserid);
$sql_array = array($_SESSION['player_id']);
$sql = $db->Prepare("SELECT count(*) as count FROM communication WHERE (toID = ? OR toID = NULL) AND ack = 0 and listed != 1");
$query = $db->Execute($sql,$sql_array);
db_op_result($query,__LINE__,__FILE__);
$numrows = $query->Fields('count');
if ($numrows != 0 && $_SESSION['pref_sound'] == 'on')
{
    $smarty->assign('lang_private_message_sound',$MSG_LANG["sendpmtext3-sound"]);

}
elseif ($numrows != 0)
{
    $smarty->assign('lang_private_message_send',$MSG_LANG["sendpmtext3"]);
}
else
{

    $smarty->assign('lang_private_message_none',$MSG_LANG['no_new_message']);//needs translated
}

$smarty->assign('lang_players_online',$MSG_LANG["onlineplayers"]);
$smarty->assign('invite_online',$online);
$smarty->assign('lang_last_user_registered',$MSG_LANG["lastuserregistered"]);
$smarty->assign('newest_user_id',$lastuserid);
$smarty->assign('newest_user_name',$lastuser);
$smarty->assign('player_id',$_SESSION['player_id']);
$smarty->assign('lang_button_statistics',$MSG_LANG["statistics"]);
$smarty->assign('lang_button_main_menu',$MSG_LANG["main"]);
$smarty->assign('lang_button_ranking',$MSG_LANG["ranking"]);
$smarty->assign('lang_button_challenges',$MSG_LANG["challenges"]);
$smarty->assign('lang_button_all_games',$MSG_LANG["allgames"]);
$smarty->assign('lang_button_configurations',$MSG_LANG["configurations"]);
$smarty->assign('lang_button_logout',$MSG_LANG["logoff"]);
$smarty->assign('lang_button_teams',$MSG_LANG["teams"]);
$smarty->assign('lang_button_tournaments_header',$MSG_LANG["tournamentheader"]);
$smarty->assign('lang_button_recent_games',"Recent Games");//NEED LANG FILE ENTRY AND TRANSLATION
$smarty->assign('lang_button_messages',$MSG_LANG['message']);
$smarty->assign('lang_button_help',$MSG_LANG["help"]);
if (!empty($_POST['sec']))
{
     if($_POST['sec'] >= $shoutbox_refresh_min)
     {
          $chat_refresh_rate = $_POST['sec'];
     }
     else
     {
          $chat_refresh_rate = $shoutbox_refresh_min;
     }
}
else
{
    $chat_refresh_rate = $shoutbox_refresh_min;//MAKE THIS A CONFIG OPTION, ONCE SET IN CONFIG, REMOE THE ELSE
}

$smarty->assign('chat_refresh_rate',$chat_refresh_rate);
$smarty->assign('lang_button_enter_chat',$MSG_LANG['enter2chat']);
$smarty->assign('lang_button_click_refresh',$MSG_LANG["refresh2"]);


    if (!empty($errMsg))
    {
        $smarty->assign('error_message',$errMsg);
    }

     $sql_array = array($_SESSION['pref_language']."%");
    $sql = $db->Prepare("SELECT * from news WHERE language like ? ORDER BY date DESC limit 10");
    $query = $db->Execute($sql,$sql_array);
    db_op_result($query,__LINE__,__FILE__);

    while(!$query -> EOF)
    {
          $data = $query->fields;
          $smarty->assign('news_date',$data['date']);
          $smarty->assign('news_title',$data['title']);
          $smarty->assign('news_text',nl2br($data['description']));
          $query -> MoveNext();
    }
    if(!empty($data))
    {
          $smarty->assign('news_quotes_table',"true");
          $smarty->assign('lang_news_header',$MSG_LANG["news"]);
    }
    if ($allow_random_quotes)
    {//if we have random quotes turned on
        $smarty->assign('lang_quote_header',$MSG_LANG["quote"]);

       $file = "languages/".$_SESSION['pref_language']."/quote.inc.php";
       if (file_exists($file))
       {
            include "$file";
            $smarty->assign('random_quote',$quotes[$randomquote]);
       }
       else
       {
            include "languages/english/quote.inc.php";
            $smarty->assign('random_quote',$quotes[$randomquote]);
       }


    }

     $sql = "SELECT b.medal,b.date,a.username from {$db_prefix}players as a,{$db_prefix}medals as b WHERE b.player_id = a.player_id and b.medal!='purpleheart' ORDER BY b.date DESC , b.medal_id DESC LIMIT 1";

    $query = $db->Execute($sql);
    db_op_result($query,__LINE__,__FILE__);
        $row = $query->fields;
     if(!empty($row))
     {

        $smarty->assign('lang_medals_header',$MSG_LANG["medals"]);
        $smarty->assign('medals_date',$row['date']);

          $lang_medal_awarded = str_replace(array("%_NAME_%","%_MEDAL_%","%_MEDAL_%","%_MEDAL_%"),array($row['firstName'],$row['medal'],$row['medal'],$row['medal']),$MSG_LANG['medal_awarded']);
          $smarty->assign('lang_medal_awarded',$lang_medal_awarded);


    }
     if(empty($_SESSION['pref_sound']))
     {
         $_SESSION['pref_sound'] = 0;
     }
     if(empty($_SESSION['auto_accept']))
     {
          $_SESSION['auto_accept'] = 0;
     }
     if(empty($_SESSION['pref_history']))
     {
          $_SESSION['pref_history'] = 'pgn';
     }

  //BEGIN CHALLENGES
    $sql = $db->Prepare("SELECT * FROM {$db_prefix}games WHERE status = 'playerInvited' AND whose_turn = ? ORDER BY date_created");
    $sql_array = array($_SESSION['player_id']);
    $query = $db->Execute($sql,$sql_array);
    db_op_result($query,__LINE__,__FILE__);

            if($_SESSION['pref_sound'] == 1)
            {
                $smarty->assign('lang_sound_file',$MSG_LANG["therearechallenges-sound"]);
            }
            $smarty->assign('lang_player_challenger',$MSG_LANG["challenger"]);
            $smarty->assign('lang_player_color',$MSG_LANG["yourcolor"]);
            $smarty->assign('lang_player_type',$MSG_LANG["type"]);
            $smarty->assign('lang_player_challenger_rating',$MSG_LANG["challengerate"]);
            $smarty->assign('lang_player_punctuation',$MSG_LANG["punctuation"]);
            $smarty->assign('lang_player_time_limit',$MSG_LANG["timeforeach"]);
            $smarty->assign('lang_player_thematic',$MSG_LANG["thematic"]);
            $smarty->assign('lang_player_response',$MSG_LANG["reply"]);


        $player_game_data = array();
        while(!$query->EOF)
        {
            $tmpGame = $query->fields;
            if ($tmpGame['white_player'] == $_SESSION['player_id'])
            {
                $tmpFrom = "white";
            }
            else
            {
                $tmpFrom = "black";
            }

            if ($_SESSION["auto_accept"] == "-1")
            {
                //Autoreject
                $tmp_arr = array($tmpFrom,$tmpGame['game_id']);
                $tmpsql = $db->Prepare("UPDATE {$db_prefix}games SET status = 'inviteDeclined', message_from = ? WHERE game_id = ?");
                $result = $db->Execute($tmpsql,$tmp_arr);
                db_op_result($result,__LINE__,__FILE__);

            }
            else if ($_SESSION["auto_accept"] == "1")
            {
                //Autoaccept
                $tmp_arr = array($tmpGame['game_id']);
                $tmpsql = $db->Prepare("UPDATE {$db_prefix}games SET gameMessage = '', messageFrom = '' WHERE gameID = ?");
                $result = $db->Execute($tmpsql,$tmp_arr);
                db_op_result($result,__LINE__,__FILE__);


                // setup new board
                $_SESSION['game_id'] = $tmpGame['game_id'];
                //createNewGame($tmpGame['game_id']);
                //saveGame();
               //TODO- update these functions to pass in values, and RETURN a value and any errors for logging
            }
            else//make this a function- show_challenge_response_request($player_id,$game_id)
            {//challenge must be responded to

            //$result = show_challenge_response_request($_SESSION['player_id'],$tmpGame['game_id'])
                //Opponent

                //GET opp nick
                if ($tmpGame['white_player'] == $_SESSION['player_id'])
                {
                    $opp_arr = array($tmpGame['black_player']);
                    $opp_sql = $db->Prepare("SELECT a.username,a.player_id,b.rating,b.trials_left FROM {$db_prefix}players as a,{$db_prefix}player_stats as b WHERE a.player_id = b.player_id and a.player_id = ?");
                }
                else
                {
                    $opp_arr = array($tmpGame['white_player']);
                    $opp_sql = $db->Prepare("SELECT a.username,a.player_id,b.rating,b.trials_left FROM {$db_prefix}players as a,{$db_prefix}player_stats as b WHERE a.player_id = b.player_id and a.player_id = ?");
                }
                $opponent = $db->Execute($opp_sql,$opp_arr);
                db_op_result($opponent,__LINE__,__FILE__);
                $row = $opponent->fields;
                $player_games_list['opponent'] = $row['username'];
                $player_games_list['id'] = $row['player_id'];
                $id = $row['player_id'];

               //Your Color
                if ($tmpGame['white_player'] == $_SESSION['player_id'])
                {
                    $player_games_list['my_color'] = $MSG_LANG["white"];
                    //$tmpFrom = "white";
                    //because we're gonna re-do how ratings are done, we're gonna re-do how we display this stuff
                    $ratingW = "line 336 fix";
                    $ratingL = "line 337 fix this";
                }
                else
                {
                    $player_games_list['my_color'] = $MSG_LANG["black"];
                    //$tmpFrom = "black";
                    $ratingL = "line 344 fix this";
                    $ratingW = "line 345 fix this";
                }

                if ($tmpGame['rated'] == "1")
                {
                    $oficial = $MSG_LANG["official"];
                }
                else
                {
                    $oficial = $MSG_LANG["notofficial"];
                }
                $player_games_list['official'] = $oficial;

                //TODO - rating system overhaul
                if ($tmpGame['rated'] == "1")
                {
                       //$xpw = getXPW($ratingW,$ratingL,getPV($_SESSION['player_id']));
                       //$xpl = getXPL($ratingL,$ratingW,getPV($id));
                }
                else
                {
                    $xpl=0;
                    $xpw=0;
                }

                $dificuldade = getDifficult($_SESSION['player_id'],$id);
                $player_games_list['difficulty'] = $dificuldade;
                $player_games_list['points_win'] = $xpw;
                $player_games_list['points_lose'] = $xpl;


                if ($tmpGame['time_limit'] == 0)
                {
                    $player_games_list['game_limit_text'] = $MSG_LANG['unlimited'];
                    $player_games_list['time_limit_value'] = $expire_game;

                }
                else if ($tmpGame['time_limit'] <60)
                {
                    $player_games_list['font_flag'] = "RED";
                    $player_games_list['game_limit_text'] = $tmpGame['time_limit']." min.";
                    $player_games_list['time_limit_value'] = $MSG_LANG['min'];

                }
                else if($tmpGame['time_limit'] < 1440)
                {
                    $player_games_list['font_flag'] = "RED";
                    $player_games_list['game_limit_text'] = ($tmpGame['time_limit']/60)." hrs.";
                    $player_games_list['time_limit_value'] = $MSG_LANG['hs'];

                }
                else
                {
                    $player_games_list['game_limit_text'] = $MSG_LANG['unlimited'];
                    $player_games_list['time_limit_value'] = ($tmpGame['time_limit']/24/60);

                }
                ########################################### END OF SHOW INVITATIONS ##########

             // Response
            $player_games_list['lang_accept'] = $MSG_LANG['accept'];
            $player_games_list['lang_reject'] = $MSG_LANG['reject'];
            $player_games_list['from_player'] = $tmpFrom;
            $player_games_list['game_id'] = $tmpGame['gameID'];
            $player_games_list['message_player'] = $id;
     //  messagebutton
               array_push($player_game_data,$player_games_list);


            }//END CHALLENGE_RESPONSE FUNCTION
        }
$smarty->assign('player_games_list',$player_game_data);



        if ($_SESSION["auto_accept"] == "0")
        {
               $smarty->assign('autoaccept',"false");
        }
//end CHALLENGES




       //get count of users and also get last registered user-
       //TODO: add join_date to player table - it would be useful, then select where joindate = latest
        $query = $db->Execute("SELECT username,player_id FROM {$db_prefix}players WHERE active = '1' ORDER BY player_id DESC");
        db_op_result($query,__LINE__,__FILE__);
        $row = $query->fields;
        $users = $query->RecordCount();
        $lastuser = $row['username'];
        $lastuserid = $row['player_id'];

    //PLAYERS you can challenge LIST
     if($allow_rank_limit)
     {
        $max_rate = $_SESSION['rating'] + $rank_differential;
        $min_rate = $_SESSION['rating'] - $rank_differential;
        if($min_rate < 1300)
        {
            $min_rate = 1300;
        }
        if($max_rate < 1300)
        {
            $max_rate = 1400;
        }
        if($max_rate > 6000)
        {
            $max_rate = 6000;//TODO- Research this, what is the highest possible recognized rating in the World Chess Federation or whatever rules?
        }
        $limiter = "AND ((b.rating > ? AND b.rating < ?) OR rating = 0)";
        $sql_array = array($min_rate,$max_rate);
     }
     else
     {
        $limiter = "";
        $sql_array = array();
     }
     $possible_opponents = array();
     //TODO a select routine to get noobs (rating = 0) separately if rank limiting is on
    $sql = $db->Prepare("SELECT a.username,a.country,b.rating,a.player_id,a.last_update FROM {$db_prefix}players as a, {$db_prefix}player_stats as b  WHERE a.player_id = b.player_id AND a.active = '1' $limiter  ORDER BY last_update DESC");
    $query = $db->Execute($sql,$sql_array);
    db_op_result($query,__LINE__,__FILE__);
    while(!$query->EOF)
    {
        $tmpPlayer = $query->fields;

          if ($tmpPlayer['last_update'] >= (time()-300))
          {
               $img="online";
          }
          else
          {
               $img="offline";
          }
          if ($tmpPlayer['rating'] == 0 && $_SESSION['player_id'] != $tmpPlayer['player_id'])
          {   //it's a noob, anyone can invite, we'll check trials in the invitation routine.
               $tmpPlayer['invite'] = $MSG_LANG['invite'];
               $tmpPlayer['sendmessage'] = $MSG_LANG['sendmessage'];
               $tmpPlayer['newuser'] = $MSG_LANG['newuser'];

          }
          elseif ($_SESSION['player_id'] == $tmpPlayer['player_id'])
          {
          //it's my OWN id - Im ont gonna invite myself am I? hahaha!
               $tmpPlayer['is_me'] = "YES";

          }
          else
          {
               $tmpPlayer['invite'] = $MSG_LANG['invite'];
               $tmpPlayer['sendmessage'] = $MSG_LANG['sendmessage'];
          }
          array_push($possible_opponents,$tmpPlayer);

          $query -> MoveNext();
     }
      $smarty->assign('can_challenge',$possible_opponents);
    //end players can challenge loop

    //IF NO PLAYERS POSSIBLE TO CHALLENGE
    if (empty($tmpPlayer))
    {
        $smarty->assign('lang_no_opponents',$MSG_LANG['noopponent']);

    }
    //END IF NO POSSIBLE CHALLENGES


    $smarty->assign('lang_players_online',$MSG_LANG["onlineplayers"]);
     $smarty->assign('lang_player_country',$MSG_LANG["country"]);
     $smarty->assign('lang_player_rating',$MSG_LANG["rating"]);
     $smarty->assign('lang_play_with_computer',$MSG_LANG['playwiththecomputer']);

     $smarty->assign('lang_games_in_progress',$MSG_LANG["gamesinprogress"]);
     $smarty->assign('lang_name_opponent',$MSG_LANG["opponent"]);
     $smarty->assign('lang_games_whose_turn',$MSG_LANG["turn"]);
     $smarty->assign('lang_time_last_move',$MSG_LANG["lastmove"]);

$sql = $db->Prepare("SELECT game_id,white_player,black_player,status,last_move,SEC_TO_TIME(NOW() - UNIX_TIMESTAMP(last_move)) as prev_move FROM {$db_prefix}games WHERE whose_turn = ? GROUP BY game_id ORDER BY last_move DESC");
$sql_arr = array($_SESSION['player_id']);
$query = $db->Execute($sql,$sql_arr);
db_op_result($query,__LINE__,__FILE__);
        $running_games = array();
        while(!$query->EOF)
        {
             $tmpGame = $query->fields;
          if ($tmpGame['status'] == '')// || $tmpGame['gameMessage'] == "playerInvited")
          {

               // get opponent's nick
               $sql = $db->Prepare("SELECT username,last_update,player_id, SEC_TO_TIME(NOW() - UNIX_TIMESTAMP(last_update)) as time_since FROM {$db_prefix}players WHERE player_id = ? GROUP BY player_id");

               if ($tmpGame['white_player'] == $_SESSION['player_id'])
               {
                    $sql_arr = array($tmpGame['black_player']);
               }
               else
               {
                    $sql_arr = array($tmpGame['white_player']);
               }
               $query1 = $db->Execute($sql,$sql_arr);
               db_op_result($query1,__LINE__,__FILE__);
               $row = $query1->fields;

               $tmpGame['opponent'] = $row['username'];
               if ($row['time_since'] <= 300)
               {
                    $img="online";
               }
               else
               {
                    $img="offline";
               }




               $txt = $img." offline";

               $tmpGame['time_image'] = $img;
               $tmpGame['time_text'] = $txt;



               // Opponent

               if ($tmpGame['white_player'] == $_SESSION['player_id'])
               {

                    $tmpColor = "white";
               }
               else
               {

                    $tmpColor = "black";
               }

               //Current Turn
      $tmpGame['your_turn'] = $MSG_LANG['yourturn'];
      //for now, we only show games where it is your turn currently, ignore those that arent your turn
    //TODO re-structure this display to show games where it is your turn and a list of games waiting , separately
    //
                 // Last Move
             //last move is a timestamp sec_to_time  in HH:mm:ss format
             $days = 0;
             $working = explode(":",$tmpGame['last_move']);
             $minutes = $working[2];
             $hours = intval($working[1]);
             if($hours > 24)
             {
                   $days = floor($hours / 24);
                   $hours = $hours % 24;
             }

               if ($days >= ($expire_game-1))
               {
                    $cor="#FF000";
               }
               elseif ($days >= ($expire_game-2))
               {
                    $cor="#CC3300";
               }
               elseif ($days >= ($expire_game-3))
               {
                    $cor="#CC6600";
               }
               elseif ($days >= ($expire_game-4))
               {
                    $cor="#FF9900";
               }
               else
               {
                    $cor ="#000000";
               }

               if ($days == "1")
               {
                    $txt = $MSG_LANG['day'] .",$hours H : $minutes M";
               }
               else
               {
                    $txt = $MSG_LANG['days'].",$hours H : $minutes M";
               }
                    $tmpGame['game_turn_color'] = $cor;
                    $tmpGame['game_turn_text'] = $txt;
                    $tmpGame['game_turn_days'] = $days;
                    $tmpGame['game_turn_play'] = $MSG_LANG['play'];

                    array_push($running_games,$tmpGame);
             }//end of running games
         $query->MoveNext();
        }//end while loop

        $smarty->assign('running_games',$running_games);
    if (empty($tmpGame))
    {
        $smarty->assign('lang_no_games',$MSG_LANG['youdonthavegames']);

    }



$smarty->display("$template_set/mainmenu.tpl");

?>