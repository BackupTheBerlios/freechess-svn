<html>
<head>
    <title>{$title}</title>
    <LINK rel="stylesheet" href="themes/{$theme_set}/mainstyles.css" type="text/css">
    <meta HTTP-EQUIV='Pragma' CONTENT='no-cache'>
    <META HTTP-EQUIV=Refresh CONTENT="{$reload_speed}; URL=mainmenu.php">
</head>

<body bgcolor=white text=black>
<!-- THE ABOVE GOES TO HEADER.PHP, AND COLORS, ETC SHUOLD BE IN STYLESHEET -->
<font face=verdana size=2>
</font>
<!-- $Id$ -->
<table width="100%" border="1">
  <tr>
    <td width="15%" height="40"><div align="center"><font face=verdana size=2>
        <input name="button22" type="button" onClick="window.open('mailform.php','_blank','toolbar=no,status=no,menubar=no,scrollbars=yes,width=500,height=500', '_blank')" value="Contact Admin">
    </font></div></td>
    <td width="15%"><div align="center"><font face=verdana size=2>
        <input name="button522" border-width= "5"  type="button"  onClick="window.location='my_games.php'" value="{$lang_main_mygames}">
    </font></div></td>
    <td width="15%"><div align="center"><font face=verdana size=2>
        <input name="button52" type="button"  onClick="window.location='chess.php'" value="Chessboard">
    </font></div></td>
    <td width="55%">{if $paypal}<!-- PAYPAL FORM BUTTON PLACEHOLDER - $paypal is the email address of admin -->{/if}


    </form>
    </td>
  </tr>
</table>
<font face=verdana size=2>
<table border="1" width="100%">



        <tr>
          <td width="40%" rowspan="2"><div align="center">Welcome, <strong>{$player_name}</strong>,
          {if $on_trial eq "true"}
          <table style='width: 100%' border='1' style='background:red'><tr><td  style='background:red'>
        <font color=white><B>{$lang_trial_process}</B><BR>{$lang_trial_message}
        </font>
        </td></tr></table>
        {else}
          {$lang_your_rating} : <B>{$rating_value}</b> {$lang_your_ranking} : <B>{$ranking_value}</B><BR>

        {/if}
        </P>
         </div>
          </td>
          <td width="29%" rowspan="2"><div align="center">
            <div align="center"><a href="inviteplayer.php?ponline=1"> </a><b> </b><a href="stats_user.php?cod={$last_user_id}"> </a><b>
  {if $lang_private_message_sound}
  <input type="image" src="images/icons/emailenvelope.jpg" onClick="window.location='messages.php'">
  <br>{$lang_private_message_sound}
  {elseif $lang_private_message_send}
  <input type="image" src="images/icons/emailenvelope.jpg" onClick="window.location='messages.php'"><br>{$lang_private_message_send}
  {else}
  {$lang_private_message_none}
  {/if}
            </div></td>
          <td width="31%">
          {$lang_players_online}
            :&nbsp;<a href="inviteplayer.php?ponline=1"><font size=+1>{$invite_online}</font></a></td>
        </tr>
        <tr>
          <td>
          {$lang_last_user_registered}
      :&nbsp;<a href="stats_user.php?cod={$newest_user_id}">{$newest_user_name}
          </a></td>
        </tr>
      </table>
</font><font face=verdana size=2></font>
<table width="100%" border="1">
  <tr>
    <td width="20%"><div align="center">
      <input name="button" type="button" class="BOTOES" onClick="window.location='recommend.php'" value="Recommend Us">
    </div></td>
    <td width="20%"><div align="center">
      <input name="button2" type="button" class="BOTOES" onclick="location.href='forum.php';" value="Forums">
    <font face=verdana size=2><font face=verdana size=2 color=black><img src="images/newsm.gif" width="25" height="23"></font></font> </div></td>
    <td width="20%"><div align="center">
      <input name="button4" type="button"  class="BOTOES" onClick="window.location='annotatedgames.php'" value="Annotations">
    </div></td>
    <td width="20%"><div align="center">
    <input name="button14" type="button" class="BOTOES"   onClick="window.location='stats_user.php?cod={$player_id}'" value="{$lang_button_statistics}">
      <img src="images/newsm.gif" width="25" height="23"></div></td>
    <td width="20%"><div align="center">
      <input name="button8"class="BOTOES" type="button"    onClick="window.location='mainmenu.php'" value="{$lang_button_main_menu}">
    </div></td>
  </tr>
  <tr>
    <td width="20%"><div align="center">
      <input name="button3" type="button" class="BOTOES"   onClick="window.location='ranking.php'" value="{$lang_button_ranking}">
    </div></td>
    <td width="20%"><div align="center">
      <input name="button6" type="button" class="BOTOES"   onClick="window.location='inviteplayer.php'" value="{$lang_button_challenges}">
    </div></td>
    <td width="20%"><div align="center">
      <input name="button12" type="button" class="BOTOES"   onClick="window.location='allgames.php'" value="{$lang_button_all_games}">
    </div></td>
    <td width="20%"><div align="center">
      <input name="button13" type="button" class="BOTOES"   onClick="window.location='configure.php'" value="{$lang_button_configurations}">
    </div></td>
    <td width="20%"><div align="center">
      <input name="button7" type="button"   class="BOTOES" onClick='document.logout.submit()' value="{$lang_button_logout}">
    </div></td>
  </tr>
  <tr>
    <td><div align="center">
      <input name="button32" type="button"   class="BOTOES" onClick="window.location='groups.php'" value="{$lang_button_teams}">
    </div></td>
    <td><div align="center">
      <input name="button15" type="button" class="BOTOES" onClick="window.location='tournaments.php?action=list&view=join'" value="{$lang_button_tournaments_header}">
      <img src="images/newsm.gif" width="25" height="23">    </div></td>
    <td><div align="center">
      <input name="button5" class="BOTOES"   type="button"  onClick="window.location='RecentGames.php'" value="{$lang_button_recent_games}">
    </div></td>
    <td><div align="center">
      <input name="button10" type="button" class="BOTOES"   onClick="window.location='messages.php'" value="{$lang_button_messages}">
    </div></td>
    <td><div align="center">
      <input name="button11" type="button" class="BOTOES"   onClick="window.location='regras.php'" value="{$lang_button_help}">
    </div></td>
  </tr>
  <tr>
    <td height="83" colspan="3"><div align="center">    <iframe src="chat_window.php?sec={$chat_refresh_rate}" width="100%" height="75" name="chat_window" frameborder="1"></iframe>

    </div></td>
    <td><div align="center">
        <input name="button9" type="button" class="BOTOES" onClick="window.open('chat.php','chatpopup','toolbar=no,status=no,menubar=no,scrollbars=no,width=350,height=150');" value="{$lang_button_enter_chat}">
        <input name="button82"class="BOTOES" type="button"    onClick="window.location='mainmenu.php'" value="{$lang_button_click_refresh}">
    </div></td>
    <td></td>
  </tr>
</table>
<font face=verdana size=2><font face=verdana size=2 color=black></font></font>
{if $error_message}<p><h2><font color='red'>{$error_message}</font></h2><p>{/if}

        {if $random_quote}
        <table border="1" width="100%">
        <tr>
        <th>
        {$lang_quote_header}
        </th>
        </tr>
        <tr>
        <td style='text-align:center'>
        <p><b><i>{$random_quote}</i></b></p>
        </td></tr>
        {/if}
        {if $news_item}
        <tr><th>{$lang_news_header}</th></tr>
        <tr><td  style='text-align:justify'>
        <B>{$news_date} - {$news_title}
        </B><BR>
        {$news_text}
        </td></tr></table>
        {/if}


 {if $lang_medals_header}
<table border="1" width="100%">
<tr><th>{$lang_medals_header}</th></tr>
    echo "</table>";
    <tr><td  style='text-align:center'><B>
    {$medals_date} - {$lang_medal_awarded}
    </B></td></tr>
    {/if}


    {if $forums_module_active}
    <table border="1" width="100%">
        <tr>
        <th><b>
        {$lang_forum_date}
        </b></th>
          <th><b>
          {$lang_forum_author}
          </b></th>
        <th><b>{$lang_main_menu_forum1}
         {$main_messages_per_page}
         <a href="forum.php">
         {$lang_main_menu_forum2}
         </a> {$lang_main_menu_forum3}
         </b></th>
        </tr>
        {section name=a loop=$forums_data}
        <tr>
        <td>{$forums_data[a].date}</td>
        <td><a href="stats_user.php?cod={$forums_data[a].player_id}">{$forums_data[a].username}</a></td>
        <td><a href="forum.php?action=viewtopic&id={$forums_data[a].topic}">{$forums_data[a].title}</a>&nbsp;&nbsp;
        <a href="{$forums_data[a].link}">>></a>
        </td>
        </tr>
        {/section}
        </table>

    {/if}
    <br>
    {if $team_invited_player}
  <form name="team" action="teams.php" method="post">
  <input type='hidden' name='action' value=''>
        <input type='hidden' name='teamid' value='{$team_invited_player}'>
        <table border='1' width='100%'>
        <tr><th>
        {$lang_team_invited_player} {$invited_by_team}
                </th></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>
        <input onClick='confirma(\"acceptinvite\")' style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='{$lang_button_join_team}'>
        &nbsp;
        <input onClick='confirma(\"leaveteam\")' style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button value='{$lang_button_reject_team}'>
        </td></tr>
        </table>
        </form>
        {/if}

{if $lang_team_challenged}
<form name="responseToInviteTeam" action="teams.php" method="post">
       <table border="1" width="100%">
       <tr>
        <th colspan=5>{$lang_team_challenged} <strong>TEAM MATCH</strong></th>
       </tr>
       <tr>
        <th>{$lang_team_challenger}</th>

        <th>{$lang_team_challenger}</th>
        <th>{$lang_team_your_color}</th>
        <th>{$lang_team_move_timeout}</th>
        <th>{$lang_team_reply}</th>
       </tr>


         {section name=b loop=$team_challenge_data}
         <tr><td>
         <a href='stats.php?cod={$team_challenge_data[b].id}'>{$team_challenge_data[b].opponent}</a>
         <td>{$team_challenge_data[b].boards}</td>
         </td><td>
         {$team_challenge_data[b].color}
         </td>
         {if $team_challenge_data[b].time_limit eq "0"}
         <td bgcolor="white">14 {$team_challenge_data[b].game_length}</td>
         {else}
         <td>
         {$team_challenge_data[b].game_length} {$team_challenge_data[b].time_limit}
         </td>
         </td><td>
         <input type='button' value='{$team_challenge_data[b].accept}' onclick="sendResponse2('accepted', '$team_challenge_data[b].from','$team_challenge_data[b].match_id')">
         <input type='button' value='{$team_challenge_data[b].reject}' onclick="sendResponse2('declined', '$team_challenge_data[b].from','$team_challenge_data[b].match_id')">
         </td></tr></table>
         {/if}
         {/section}


      <input type="hidden" name="response" value="">
      <input type="hidden" name="messageFrom" value="">
      <input type="hidden" name="match_id" value="">
      <input type="hidden" name="timelimit" value="">
      <input type="hidden" name="ToDo" value="ResponseToInviteTeam">

      </form>
      {/if}

      {if $lang_player_type}

          <table border="1" width="100%">
          {if $lang_sound_file}
          <tr>
           <th colspan=9>{$lang_sound_file}</th>
          </tr>
          {/if}
          <tr>
           <th>{$lang_player_challenger}</th>
           <th>{$lang_player_color}</th>
           <th>{$lang_player_type}</th>
           <th>{$lang_player_challenger_rating}</th>
           <th>{$lang_player_punctuation}</th>
           <th>{$lang_player_time_limit}</th>
           <th>{$lang_player_thematic}</th>
           <th>{$lang_player_response}</th>

         <th>...</th>
          </tr>
          {section name=c loop=$player_games_list}
          <tr><td>
          <a href='stats_user.php?cod={$player_games_list[c].id}'>{$player_games_list[c].opponent}</a>
          </td><td>
          {$player_games_list[c].my_color}
          </td>
          <td>{$player_games_list[c].official}</td>
          <td>{$player_games_list[c].difficulty}</td>
          <td><i>+{$player_games_list[c].points_win} / -{$player_games_list[c].points_lose}</i></td>
          {if $player_games_list[c].font_flag neq "RED"}
          <td bgcolor=white>{$player_games_list[c].time_limit_value} {$player_games_list[c].game_limit_text}</td>
          {else}
          <td bgcolor=white><strong><font size=3 color=red>{$player_games_list[c].time_limit_value} {$player_games_list[c].game_limit_text}</font></strong></td>
          {/if}
          <td align='center' bgcolor=#FFFFFF>
          <font color='800000'>
          {$player_games_list[c].thematic}</font>
          </td>
          </td><td width=10%>
          <form name="responseToInvite" action="mainmenu.php" method="post"><input type="hidden" name="response" value="accepted"><input type="hidden" name="ToDo" value="ResponseToInvite"><input type="hidden" name="game_id" value="{$player_games_list[c].game_id}">
          <input type='button' value='{$player_games_list[c].lang_accept}' onclick="submit()"></form>
           <form name="responseToInvite" action="mainmenu.php" method="post"><input type="hidden" name="response" value="declined"><input type="hidden" name="ToDo" value="ResponseToInvite"><input type="hidden" name="game_id" value="{$player_games_list[c].game_id}">
            <input type='button' value='{$player_games_list[c].lang_reject}' onclick="submit()"></form>
            </td>
           <td width=33>
           <input type="image" src="images/icons/emailenvelope.jpg" onClick="javascript:MessagePlayer({$player_games_list[c].message_player})">
           </td>
            </tr>
            {/section}
            </table>
            {if $autoaccept eq "false"}

               <input type="hidden" name="response" value="">
               <input type="hidden" name="messageFrom" value="">
               <input type="hidden" name="game_id" value="">
               <input type="hidden" name="ToDo" value="ResponseToInvite">
               </form>
               {/if}


            {/if}

            {if $teams_members eq "LIST"}
            <BR><table border='1' width='100%'>
            <tr><th colspan=3>{$lang_member_pending}</th></tr>
            <tr><th>{$lang_member_name}</th><th>{$lang_member_rating}/{$lang_member_max_rating}</th><th>&nbsp;</th></tr>
            {$section name=d loop=$member_stats}

<tr><td><a href='stats_user.php?cod={$member_stats[d].player_id}'>{$member_stats[d].username}</a></td><td>{$member_stats[d].rating}/{$member_stats[d].rating_max}</td><td><input onClick="window.location='teams.php?action=acceptuser&playerid={$member_stats[d].player_id}&teamid={$member_stats[d].myteam}'" style='font-size:11' type=button value='{$member_stats[d].accept_player}'>&nbsp;
<input onClick="window.location='teams.php?action=rejectuser&playerid={$member_stats[d].player_id}&teamid={$member_stats[d].myteam}'" style='font-size:11' type=button value='{$member_stats[d].reject_player}'></td></tr>
    <tr><td colspan=3>&nbsp;</td></tr>
    </table>
    {/if}

</font>
<td><table width="50%" border="1" align="left">
          <tr>
            <td height="405" valign="top" style="width: 320px; height: 400px;">
              <div style="width: 100%; height: 100%; overflow : auto;">
                <table width="100%" border="1" align="left">
                  <tr>
                    <th colspan="3"><div align="center"><strong><font color="#000000">
                     <img src="images/online.gif" width="14" height="14">
                     {$lang_players_online} ({$lang_player_country}) ({$lang_player_rating})
                    </font> <font color="#000000"><br>
                     <img alt='{$lang_play_with_computer}' src='images/icons/bots.gif' width="22" height="22">={$lang_play_with_computer}
                      </font></strong></div>
                    </th>
                  </tr>
        {if $lang_no_opponent}
        <tr><td colspan='3'>{$lang_no_opponent}</td></tr>
        {else}
        {section name=e loop=$can_challenge}
            {if $can_challenge[e].newuser}
                {if $can_challenge[e].is_me neq "YES"}
            <tr>
                <td width=5%>
                <form action='challenge.php' method='post'><input type='hidden' name='player_id' value="{$can_challenge[e].player_id}">
                <input type='button' style='font-size:11' value='{$can_challenge[e].invite}' onClick="submit()"> </form>
                </td>
            <td width=5%>
            <form action='sendmessage.php' method='post'><input type='hidden' name='player_id' value="{$can_challenge[e].player_id}">
                <input type='button' style='font-size:11' value='{$can_challenge[e].sendmessage}' onClick="submit()">   </form>
                </td>

                <td width=50% style='text-align:left'>
                <a href='stats_user.php?cod={$can_challenge[e].player_id}'>{$can_challenge[e].username}</a>&nbsp;({$can_challenge[e].country})(<strong><font color=red>{$can_challenge[e].newuser}</strong></font>)</td>
                {/if}

            {else}
            {if $can_challenge[e].is_bot eq "NOT_AVAILABLE"}
            <tr>
            <td width="15%" colspan="3">
            Bot Not Available
            {/if}
            {if $can_challenge[e].is_bot eq "YES"}
            <tr>
                        <td width=5%>
                        <input type='button' style='font-size:11' value='{$can_challenge[e].invite}' onClick="botchallenge.php?bid={$can_challenge[e].player_id}">                 </td>
                        <td width=5%>{$can_challenge[e].playwiththecomputer}</td>
                        <td width=50%  style='text-align:left'><a href='stats_user.php?cod={$can_challenge[e].player_id}'>{$can_challenge[e].username}</a>&nbsp;<img alt='{$can_challenge[e].playwiththecomputer}' src='images/icons/bots.gif' width='22' height='22'>({$can_challenge[e].rating})</td>
            </tr>
            {/if}
            {if $can_challenge[e].is_me neq "YES"}
            <tr>
                <td width=5%>
                <input type='button' style='font-size:11' value="{$can_challenge[e].invite}" onClick="challenge.php?pid={$can_challenge[e].player_id}">
                </td>
                <td width=5%>
                <form action='sendmessage.php' method='post'><input type='hidden' name='player_id' value="{$can_challenge[e].player_id}">
                <input type='button' style='font-size:11' value='{$can_challenge[e].sendmessage}' onClick="submit()">   </form>
                </td>

                <td width=50% style='text-align:left'>
                <a href='stats_user.php?cod={$can_challenge[e].player_id}'>{$can_challenge[e].username}</a>&nbsp;({$can_challenge[e].country})({$can_challenge[e].rating})
                </td>
               </tr>
               {/if}
             {/if}
        {/section}
        {/if}
  </table>
              </div>
            </td>
          </tr>
        </table>

            <table width="50%" border="1">
              <!--DWLayoutTable-->
              <tr>
                <td width="100%" valign="top" style="width: 320px; height: 400px;">
                  <div style="width: 100%; height: 100%; overflow : auto;">
                    <table width="100%" border="1" align="left">
                      <tr>
                        <th colspan=5>{$lang_games_in_progress}
                        &nbsp; </th>
                      </tr>
                      <tr>
                        <th>&nbsp;</th>
                        <th>{$lang_name_opponent}
                        </th>
                        <th>{$lang_games_whose_turn}
                        </th>
                        <th>{$lang_time_last_move}
                        </th>
                        </tr>
      {if $lang_no_games}
      <tr><td colspan='5'>{$lang_no_games}</td></tr>
      {/if}
      {section name=f loop=$running_games}
      <tr>
      <td width="5%">
      <img src='images/{$running_games[f].time_image}.gif' alt='{$running_games[f].time_text}' title='{$running_games[f].time_text}'>
      </td>
      <td width="10%">
      <a href='stats_user.php?cod={$running_games[f].player_id}'>{$running_games[f].opponent}</a>
      </td><td>
      {if $running_games[f].your_turn}
      <B><font color=red>{$running_games[f].your_turn}</font></B>
      {else}
      {$running_games[f].waiting_message}
      {/if}
      </td>
      <td>
      <font color='{$running_games[f].game_turn_color}'>{$running_games[f].lastm} <br>({$running_games[f].game_turn_days} {$running_games[f].game_turn_text})</td>
      <td>
      <form name="existingGames" action="chess.php" method="post"> <input type="hidden" name="game_id" value="{$running_games[f].game_id}">
      <input style='font-size:11' type=button value='{$running_games[f].game_turn_play}' onClick='submit()'></form></td></tr>
      {/section}
      </table>
                  </div>
                </td>
              </tr>
            </table>
            <input type='hidden' name='rdoShare' value='no'>
            <input type="hidden" name="game_id" value="">
            <input type="hidden" name="sharePC" value="no">
          </form>
  </tr>
</table>
<form name="logout" action="mainmenu.php" method="post">
    <input type="hidden" name="ToDo" value="Logout">
</form>

</font>
<font face=verdana size=1>
<hr width=100%>
<p align=center>
    <font face=verdana size=1>
    Copyright &copy; 2004-2005 <a href="http://sourceforge.net/projects/compwebchess/" target="_blank">CompWebChess</a> {$game_version},

    <a href="credits.php">Comp WebChess Development Team</a>
</p>
</font>

</body>
</html>

