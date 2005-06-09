<?php
##############################################################################################
#                                                                                            #
#                                thematics_functions.php
# *                            -------------------                                           #
# *   begin                : Tuesday, February 1, 2005
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

include_once('global_includes.php');

                     //re-do this to a simple function- remove the numeric, make it named, use name for language variable
                //saves us several hundred lines of code.
 /* thematics- move to a function              if ($tmpGame['thematic'] == 1)
                {
                     $player_games_list['thematic'] = $MSG_LANG['alekhine'];
                }
                elseif ($tmpGame['thematic'] == 2)
                {
                 $player_games_list['thematic'] = $MSG_LANG['birds'];
                }
                elseif ($tmpGame['thematic'] == 3)
                {
                 $player_games_list['thematic'] = $MSG_LANG['budapest'];
                }
                elseif ($tmpGame['thematic'] == 4)
                {
                 $player_games_list['thematic'] = $MSG_LANG['catalan'];
                }
                elseif ($tmpGame['thematic'] == 5)
                {
                 $player_games_list['thematic'] = $MSG_LANG['carokann'];
                }
                elseif ($tmpGame['thematic'] == 6)
                {
                 $player_games_list['thematic'] = $MSG_LANG['cochranegambit'];
                }
                elseif ($tmpGame['thematic'] == 7)
                {
                 $player_games_list['thematic'] = $MSG_LANG['dutchdefense'];
                }
                elseif ($tmpGame['thematic'] == 8)
                {
                 $player_games_list['thematic'] = $MSG_LANG['leningraddutch'];
                }
                elseif ($tmpGame['thematic'] == 9)
                {
                 $player_games_list['thematic'] = $MSG_LANG['fourknights'];
                }
                elseif ($tmpGame['thematic'] == 10)
                {
                 $player_games_list['thematic'] = $MSG_LANG['frenchdefense'];
                }
                elseif ($tmpGame['thematic'] == 11)
                {
                 $player_games_list['thematic'] = $MSG_LANG['frenchadvance'];
                }
                elseif ($tmpGame['thematic'] == 12)
                {
                 $player_games_list['thematic'] = $MSG_LANG['frenchclassical'];
                }
                elseif ($tmpGame['thematic'] == 13)
                {
                 $player_games_list['thematic'] = $MSG_LANG['frenchrubinstein'];
                }
                elseif ($tmpGame['thematic'] == 14)
                {
                 $player_games_list['thematic'] = $MSG_LANG['frenchwinawer'];
                }
                elseif ($tmpGame['thematic'] == 15)
                {
                 $player_games_list['thematic'] = $MSG_LANG['frenchtarrasch'];
                }
                elseif ($tmpGame['thematic'] == 16)
                {
                 $player_games_list['thematic'] = $MSG_LANG['grob'];
                }
                elseif ($tmpGame['thematic'] == 17)
                {
                 $player_games_list['thematic'] = $MSG_LANG['grunfeld'];
                }
                elseif ($tmpGame['thematic'] == 18)
                {
                 $player_games_list['thematic'] = $MSG_LANG['kingsgambit'];
                }
                elseif ($tmpGame['thematic'] == 19)
                {
                 $player_games_list['thematic'] = $MSG_LANG['kingsindian'];
                }
                elseif ($tmpGame['thematic'] == 20)
                {
                 $player_games_list['thematic'] = $MSG_LANG['italiangame'];
                }
                elseif ($tmpGame['thematic'] == 21)
                {
                 $player_games_list['thematic'] = $MSG_LANG['larsens'];
                }
                elseif ($tmpGame['thematic'] == 22)
                {
                 $player_games_list['thematic'] = $MSG_LANG['modernbenoni'];
                }
                elseif ($tmpGame['thematic'] == 23)
                {
                 $player_games_list['thematic'] = $MSG_LANG['muziogambit'];
                }
                elseif ($tmpGame['thematic'] == 24)
                {
                 $player_games_list['thematic'] = $MSG_LANG['nimzoindian'];
                }
                elseif ($tmpGame['thematic'] == 25)
                {
                 $player_games_list['thematic'] = $MSG_LANG['petroff'];
                }
                elseif ($tmpGame['thematic'] == 26)
                {
                 $player_games_list['thematic'] = $MSG_LANG['philidor'];
                }
                elseif ($tmpGame['thematic'] == 27)
                {
                 $player_games_list['thematic'] = $MSG_LANG['queensgambit1'];
                }
                elseif ($tmpGame['thematic'] == 28)
                {
                 $player_games_list['thematic'] = $MSG_LANG['queensgambit2'];
                }
                elseif ($tmpGame['thematic'] == 29)
                {
                 $player_games_list['thematic'] = $MSG_LANG['queensindian'];
                }
                elseif ($tmpGame['thematic'] == 30)
                {
                 $player_games_list['thematic'] = $MSG_LANG['ruylopez'];
                }
                elseif ($tmpGame['thematic'] == 31)
                {
                 $player_games_list['thematic'] = $MSG_LANG['scandinavian'];
                }
                elseif ($tmpGame['thematic'] == 32)
                {
                 $player_games_list['thematic'] = $MSG_LANG['scotch'];
                }
                elseif ($tmpGame['thematic'] == 33)
                {
                 $player_games_list['thematic'] = $MSG_LANG['sicilian'];
                }
                elseif ($tmpGame['thematic'] == 34)
                {
                 $player_games_list['thematic'] = $MSG_LANG['sicilianalapin'];
                }
                elseif ($tmpGame['thematic'] == 35)
                {
                 $player_games_list['thematic'] = $MSG_LANG['sicilianclosed'];
                }
                elseif ($tmpGame['thematic'] == 36)
                {
                 $player_games_list['thematic'] = $MSG_LANG['siciliansveshnikov'];
                }
                elseif ($tmpGame['thematic'] == 37)
                {
                 $player_games_list['thematic'] = $MSG_LANG['siciliansimagin'];
                }
                elseif ($tmpGame['thematic'] == 38)
                {
                 $player_games_list['thematic'] = $MSG_LANG['sicilianpaulsen'];
                }
                elseif ($tmpGame['thematic'] == 39)
                {
                 $player_games_list['thematic'] = $MSG_LANG['sicilianrichterrauzer'];
                }
                elseif ($tmpGame['thematic'] == 40)
                {
                 $player_games_list['thematic'] = $MSG_LANG['siciliandragon'];
                }
                elseif ($tmpGame['thematic'] == 41)
                {
                 $player_games_list['thematic'] = $MSG_LANG['sicilianscheveningen'];
                }
                elseif ($tmpGame['thematic'] == 42)
                {
                 $player_games_list['thematic'] = $MSG_LANG['siciliansozin'];
                }
                elseif ($tmpGame['thematic'] == 43)
                {
                 $player_games_list['thematic'] = $MSG_LANG['siciliannajdorf'];
                }
                elseif ($tmpGame['thematic'] == 44)
                {
                 $player_games_list['thematic'] = $MSG_LANG['slav'];
                }
                elseif ($tmpGame['thematic'] == 45)
                {
                 $player_games_list['thematic'] = $MSG_LANG['sokolsky1'];
                }
                elseif ($tmpGame['thematic'] == 46)
                {
                 $player_games_list['thematic'] = $MSG_LANG['tarrasch'];
                }
                elseif ($tmpGame['thematic'] == 47)
                {
                 $player_games_list['thematic'] = $MSG_LANG['vienna'];
                }
                elseif ($tmpGame['thematic'] == 48)
                {
                 $player_games_list['thematic'] = $MSG_LANG['volgagambit'];
                }
                else
                {
                 $player_games_list['thematic'] = "No";
                }
                     */

 //PUTTING FORUM STUFF HERE FOR NOW
 if($allow_forums == true)
{
     //include_module('forums');
        include_once('forum_post_recent.php');


    ##FORUM STUFF- MOVE TO MODULE
    $smarty->assign('lang_forum_date',$MSG_LANG['date']);
    $smarty->assign('lang_forum_author',$MSG_LANG['autor']);//what's autor mean? need this in english
    //config option $forums_live == 1 or 0 and use an if
    $forum_module_active = true;
    $smarty->assign('lang_main_menu_forum1',$MSG_LANG['forum1']);//forums let's make it a config option
    $smarty->assign('main_messages_per_page',$main_perpage);
    $smarty->assign('lang_main_menu_forum2',$MSG_LANG['forum2']);
    $smarty->assign('lang_main_menu_forum3',$MSG_LANG['forum3']);
    $smarty->assign('forums_module_active',$forum_module_active);

include_once ("forum_functions.php");

        $t1 = mysql_query("SELECT t.*, p.firstName, p.playerID from forum_topics t LEFT JOIN players p ON p.playerID = t.userid ORDER BY time DESC LIMIT $main_perpage");
$forums_data = array();
        while ($forum_data = mysql_fetch_array($t1))
        {

            $title = db_output($forum_data['title']);
            $title = strip_tags($title);
            $text = db_output($forum_data['text'], true);
            $text = strip_tags($text, "<br>");
            $text = bbcode($text);
            $text = forum_smilies($text);
            $date = date("d.m.y, H:i", $forum_data['time']);
            $topic = ($forum_data['replyto'] > 0) ? $forum_data['replyto'] : $forum_data['topic_id'];

            $count = getcount('forum_topics', "WHERE replyto = '".$topic."'");

            $lp1 = mysql_query("SELECT t.topic_id, t.userid, p.firstName FROM forum_topics t
                        LEFT JOIN players p ON p.playerID = t.userid
                        WHERE t.replyto = '".$topic."'
                        AND t.time = '".$t['lastreply']."'");

            $lp = mysql_fetch_array($lp1);

            $total_pages = ceil($count/$topics_perpage);
            $start = ($total_pages - 1) * $topics_perpage;
            if ($start < 0)
            {
                 $start = 0;
             }
            $link = 'forum.php?action=viewtopic&id='.$topic.'&start='.$start.'#'.$lp['topic_id'];
         $forum_data['date'] = $date;
         $forum_data['topic'] = $topic;
         $forum_data['title'] = $title;
         $forum_data['link'] = $link;
         array_push($forums_data,$forum_data);

        } // while
$smarty->assign('forums_data',$forums_data);

##END FORUM STUFF - REMOVE AND MAKE A MODULE
}

// PUTTING TEAM STUFF HERE FOR NOW

  //check for team invitations- see if this player was invited to a team
     $sql_array = array($_SESSION['player_id']);
 //load user data
 $sql = $db->Prepare("SELECT name,team_id FROM cwc_team_invites WHERE player_id = ?'");
 $query = $db->Execute($sql,$sql_array);
 db_op_result($query,__LINE__,__FILE__);
 $row = $query->fields;
 if(!empty($row))//rule is, if a player is under invitation, no other team may invite that player until the player rejects them
 {
     //if a team is rejected, or date expires, invite is removed from db,
     //a team cannot invite more players than they have room for, so..
     //well we need to re-think it a little bit, malicious player could keep someone from joining a team
     //so we need to also indicate how many times a player rejects a team 3 strikes, you're out
     $myteam = $row['name'];
     $smarty->assign('team_invited_player',$myteam);
     $smarty->assign('lang_team_invited_player',$MSG_LANG["youwasinvitedtojoin"]);
     $smarty->assign('invited_by_team',$row['name']);
     $smarty->assign('lang_button_join_team',$MSG_LANG["join"]);
     $smarty->assign('lang_button_reject_team',$MSG_LANG["cancel"]);
     //$smarty->assign('lang_button_inspect',$MSG_LANG['inspect_team']);
 }




 //BEGIN TEAM CHALLENGES
$sql = $db->Prepare("select name,level from team_members where fk_player='$player_id' and level='100'");
$my_team = mysql_fetch_array($p);
$my_team=$my_team['fk_team'];


   $tmpQuery = "SELECT * FROM matches WHERE gameMessage = 'TeamInvited' AND ((team1 = '".$my_team."' AND messageFrom = 'black') OR (team2 = '".$my_team."' AND messageFrom = 'white')) ORDER BY dateCreated";
   $tmpMatches = mysql_query($tmpQuery);
   if (mysql_num_rows($tmpMatches) > 0)
   {
      $smarty->assign('lang_team_challenged',$MSG_LANG['therearechallenges']);
      $smarty->assign('lang_team_challenger',$MSG_LANG['challenger']);
      $smarty->assign('lang_team_players',$MSG_LANG['tournamentplayers']);
      $smarty->assign('lang_team_your_color',$MSG_LANG['yourcolor']);
      $smarty->assign('lang_team_move_timeout',$MSG_LANG['movetimeout']);
      $smarty->assign('lang_team_reply',$MSG_LANG['reply']);

      $team_data = array();
      while($tmpMatch = mysql_fetch_array($tmpMatches, MYSQL_ASSOC))
      {
         /* Opponent */

         /* get opponent's team name */
         if ($tmpMatch['team1'] == $my_team)
         {
            $tmpOpponent = mysql_query("SELECT * FROM team WHERE teamID = ".$tmpMatch['team2']);
        }
         else
         {
            $tmpOpponent = mysql_query("SELECT * FROM team WHERE teamID = ".$tmpMatch['team1']);
        }
         $row = mysql_fetch_array($tmpOpponent);
         $opponent = $row[1]; //this is stupid- NEVER EVER USE NUMERICAL INDICES GETTING DATA FROM A DB QUERY!
         $id = $row[0];
         $team_challenge_data['opponent'] = $opponent;//when we get this unholy mess straightened out, use assoc indices directly or push data
         $team_challenge_data['id'] = $id;
         $team_challenge_data['boards'] = $tmpMatch['boards'];


         /* Your Color */

         if ($tmpMatch['team1'] == $my_team)
         {
            $team_challenge_data['color'] = "White";
            $tmpFrom = "white";

         }
         else
         {
            $team_challenge_data['color'] = "Black";
            $tmpFrom = "black";

         }

             if ($tmpMatch['adj_time'] == 0)
             {
                 $team_challenge_data['time_limit'] = 0;
                $team_challenge_data['game_length'] = $MSG_LANG['unlimited'];
             }
            elseif ($tmpMatch['adj_time'] <60)
            {
                $team_challenge_data['time_limit'] = $tmpMatch['adj_time']." min.";
                $team_challenge_data['game_length'] = $MSG_LANG['min'];

            }
            elseif($tmpMatch[adj_time] < 1440)
            {
                $team_challenge_data['time_limit'] = ($tmpMatch['adj_time']/60)." hrs.";
                $team_challenge_data['game_length'] = $MSG_LANG['hs'];

            }
            else
            {
                $team_challenge_data['time_limit'] = $MSG_LANG['unlimited'];
                $team_challenge_data['game_length'] = ($tmpMatch['adj_time']/24/60);

            }
        /* Response */
         $team_challenge_data['accept'] = $MSG_LANG["accept"];
         $team_challenge_data['reject'] = $MSG_LANG["reject"];
         $team_challenge_data['from'] = $tmpFrom;
         $team_challenge_data['match_id'] = $tmpMatch['match_id'];
         array_push($team_data,$team_challenge_data);

      }
      $smarty->assign('team_challenge_data',$team_data);

   }//END TEAM CHALLENGES

 //MORE TEAM STUFF move to teams module
        $p1 = mysql_query("SELECT * FROM team_members,team WHERE fk_team=teamID AND fk_player='$_SESSION[player_id]'");
        $row1 = mysql_fetch_array($p1);
        $myteam = $row1['fk_team'];
        $team_membership = $row1['level'];

        $p2 = mysql_query("SELECT * FROM team_members,players WHERE fk_player=player_id AND fk_team='$myteam' AND level = 0");
        if (mysql_num_rows($p2)>0 && $team_membership >= 100)
        {
            $smarty->assign('teams_members',"LIST");
            $smarty->assign('lang_member_pending',$MSG_LANG["membershippending"]);
            $smarty->assign('lang_member_name',$MSG_LANG['name']);
            $smarty->assign('lang_member_rating',$MSG_LANG['rating']);
            $smarty->assign('lang_member_max_rating',$MSG_LANG['max']);

           $team_stats = array();
            while ($row2 = mysql_fetch_array($p2))
            {
                $row2['myteam'] = $myteam;
                $row2['accept_player'] = $MSG_LANG['accept'];
                $row2['reject_player'] = $MSG_LANG['reject'];
                array_push($team_stats,$row2);
            }
            $smarty->assign('member_stats',$team_stats);

        }
        //END MORE TEAMS STUFF
?>
