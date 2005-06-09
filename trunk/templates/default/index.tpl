<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <title>WebChess Login Page- Select a language to change language
        </title>
            <LINK rel="stylesheet" href="templates/default/styles.css" type="text/css">
</head>
<body bgcolor=white text=black>

<!-- THE ABOVE GOES TO HEADER.PHP, AND COLORS, ETC SHUOLD BE IN STYLESHEET -->

<center>

<!-- CONVERT TO SITE SPECIFIC LOGO, PUT IN CONFIG FILE -->

<font face="Verdana">
    <img src="images/compwebchess.gif" alt="compwebchess_logo">
</font>
</center>
<div align="center">
    <tr bgcolor= "#cfcfbb">
        <td width="250" align="center">
        {if $errors}
        <font color="red"><b>
        {section name=a loop=$errors}
        {$errors[a]}<br>
        {/section}
        </b></font>
        {/if}
<table border="0" cellspacing="1" cellpadding="1" bgcolor="#99CCFF" align="center">
        <tr bgcolor="#99CCFF">
            <td width="60%" style="text-align:right">
                <b>
                    <font face="Verdana" size="2">{$lang_select_language}:
                    </font>
                </b>
            </td>
            <td width="65%" style="text-align:left">
                <form action="index.php" method="post">
                    <select name="language" onChange="submit()">
                    {html_options values=$language_options output=$language_options selected=$selected}
                    </select>
                </form>
            </td>
        </tr>
        <tr bgcolor="#99CCFF">
            <td align="center" colspan="2">
                <div align= "center">
        <br>
                        <form method="post" action="login.php" name="login">
                        <b>
                        <font face="Verdana" size="2">{$lang_username}:
                        </font>
                        </b>
                            <input name="username" type="text" size="15" />
                            <input type="hidden" name="language" value="{$selected}">
        <br>
                        <b>
                        <font face="Verdana" size="2">{$lang_password}:&nbsp;
                        </font>
                        </b>
                        <font size=1 face="Verdana">
                            <input name="password" type="password" size="15" />
        <br>
                            <input name="login" value="{$lang_submit_login}" type="submit" />
        <br><br>
                                <a href="forgot_password.php?language={$selected}">{$lang_forgot_password}</a>
                        </font>
                        </form>
                </div>
            </td>
        </tr>
    </table>
    </td
    </tr>
</div>
<p align="center">
    <font face="Verdana" size="2">
        <a href="new_player.php?language={$selected}">{$lang_create_user}
        </a>
    </font>
</p>
<p align="center">
    <font face="Verdana" size="2">
Total Registered Users: {$registered}  --  Active in the Past Week: {$actives} Players  -- Active Games: {$active_games}
    </font>
</p>
<hr width= "100%">
<div align="center">
<table class="ptable">
<tr>
<th width="20%" align="center">Player Name</th>
<th width="10%" align="center">Rating</th>
<th width="10%" align="center">Wins</th>
<th width="10%" align="center">Losses</th>
<th width="10%" align="center">Draws</th>
</tr>
{section name=a loop=$onlinep}
<tr class="ptable{cycle values="odd,even"}">
<td width="20%">{$onlinep[a].username}</td>
<td width="10%">{$onlinep[a].rating}</td>
<td width="10%">{$onlinep[a].victories}</td>
<td width="10%">{$onlinep[a].defeats}</td>
<td width="10%">{$onlinep[a].draws}</td>
</tr>
{/section}
</table>

    </div></td>
<p align=center>
    <font face=verdana size=1>
    Copyright &copy; 2004-2005
        <a href="http://sourceforge.net/projects/compwebchess/" target="_blank">CompWebChess Project
        </a>
            <?=$VERSION?>,
        <a href="credits.php">Comp WebChess Development Team</a>
    </font>
</p>
<p align="center">
    <font face="Verdana" size="1" color="#AAAAAA">
        CompWebChess is based on GNU
            <a href="http://webchess.sourceforge.net/">WebChess 0.8.3</a> and is published under the GNU GPL (GNU General Public License).
<BR>
        To read the full license, go to http://www.opensource.org/licenses/gpl-license.html. Basically, the GPL says you are free
<BR>
        to distribute and edit the sourcecode, as long as you keep the rightful credits intact. Please read the full documents if
<BR>
        you have any questions.
<BR>
    </font>
</p>
<br>
<br>
<p align="center">
    <a href="http://validator.w3.org/check?uri=referer"><img border="0"
        src="http://www.w3.org/Icons/valid-html401"
        alt="Valid HTML 4.01!" height="31" width="88"></a>
</p>
</body>
</html>