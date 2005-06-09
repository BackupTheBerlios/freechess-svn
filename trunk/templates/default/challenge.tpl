<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta name="Keywords" content="chess,ajedrez,échecs,echecs,scacchi,schach,check,check mate,jaque,jaque mate,queenalice,queen alice,queen,alice,play,game,games,turn based,correspondence,correspondence chess,online chess,play chess online">

        <title>{$title} :: {$title2}</title>

<LINK rel="stylesheet" href="themes/{$theme_set}/mainstyles.css" type="text/css">

</head>

    <body>
<table width="600">
    <form name="invite" action="" method="post">
    <input type="hidden" name="ToDo" value="InvitePlayer">
    <input type="hidden" name="opponent" value="">
    <TR></TR><TH colspan=2>{$lang_game_settings}</TH></TR>
        <TR>

            <TD style="text-align:left" width=40%>{$lang_your_color}:</TD>
            <TD style="text-align:left">
            <select name="color">
            <option value="random" SELECTED>{$lang_random}</option>
            <option value="white">{$lang_white}</option>
            <option value="black">{$lang_black}</option>

            </select></TD></TR>


        <TR>
            <TD style="text-align:left">{$lang_is_rated}:</TD>
            <TD style="text-align:left">
            {if $is_trial}
                <input type='hidden' name='rated' value='0'>
                <select name="rateddisabled" disabled=true>
                <option value="1">{$lang_yes}</option>

                <option value="0" SELECTED>{$lang_no}</option>
                </select>
            {else}
            <select name="rated">
                <option value="1">{$lang_yes}</option>

                <option value="0">{$lang_no}</option>
                </select>
            {/if}
          </TD></TR>
          {if $lang_thematic}
          <TR>
            <TD style="text-align:left">{$lang_thematic}<img src="images/new.gif" width="40" height="36"></TD>
            <TD><select name="thematic" align="right">
            <option value="0" SELECTED>{$lang_no}</option>
            {html_options values=$thematic_values output=$thematic_names}
            </select>
            </TD>
         </TR>
         {/if}
        <TD style="text-align:left">{$lang_time_each}:</TD>
        <TD style="text-align:left">
        <select name="time_limit">
        {html_options values=$time_limit_ids output=$time_limit_values}
        </select> :{$lang_days_move}

        <TR>

        <TD style="text-align:left">{$lang_choose_player}:</TD>
        <TD style="text-align:left">

                <select name="opponent" size="1">
                 {html_options values=$player_ids output=$player_list selected=$selected_player}
                </select>
<input type='button' style='font-size:11' value="{$lang_invite_button}" onClick=submit();></form>
</TD></TR>
</table>

    <table border="1" width="500">
    <tr>
        <th colspan="5">{$lang_current_invites}</th>
    </tr>

    <tr>

        <th>{$lang_invited_opponent}</th>
        <th>{$lang_invited_your_color}</th>
        <th>{$lang_invited_type}</th>
        <th>{$lang_invited_status}</th>
        <th>{$lang_invited_action}</th>
    </tr>
    {section name=pl loop=$invited_games}
    <tr>
    <td>
    <a href='stats_user.php?cod={$invited_games[pl].player_id}'>{$invited_games[pl].opponent}</a></td>
    <td>{$invited_games[pl].your_color}</td>
    <td>{$invited_games[pl].rated}</td>
    <td>{$invited_games[pl].status}</td>
    <td align='center'>
    <form action="withdraw.php" method="post">
    <input type="hidden" name="game_id" value="{$invited_games[pl].game_id}">
    <input type='button' value='{$invited_games[pl].cancel_button}' onclick="submit()"></form>
    </td>
    </tr>
    {/section}

        </table>

        <BR>

</body>

</html>