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
        {$confirm_message}
<table border="0" cellspacing="1" cellpadding="1" bgcolor="#cfcfbb" align="center">
        <tr bgcolor="#cfcfbb">
            <td width="48%" style="text-align:right">
                <b>
                    <font face="Verdana" size="2">{$lang_select_language}:
                    </font>
                </b>
            </td>
            <td width="52%" style="text-align:left">
                <form action="confirm.php" method="post">
                    <select name="language" onChange="submit()">
                    {html_options values=$language_options output=$language_options selected=$selected}
                    </select>
                
            </td>
        </tr>
        <tr bgcolor="#cfcfbb">
            <td align="center" colspan="2">
                <div align= "center">
        <br>
                        
                        <b>
                        <font face="Verdana" size="2">{$lang_username}:
                        </font>
                        </b>
                            <input name="username" type="text" size="15" maxlength="32"/>

                           
        <br>
                        <b>
                        <font face="Verdana" size="2">{$lang_password}:&nbsp;
                        </font>
                        </b>
                        <font size=1 face="Verdana">
                            <input name="password" type="password" size="15" maxlength="30" />
        <br>
         <b><font face="Verdana" size="2">{$lang_confirm}:
                        </font></b>
                        <input type="text" name="code" value="{$confirm_code}" size="15" maxlength="16"><br>
                            <input name="login" value="{$lang_submit_login}" type="submit" />
        <br><br>
                               
                        </font>
                        </form>
                </div>
            </td>
        </tr>
    </table>
    </td
    </tr>
</div>

<hr width= "100%">
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