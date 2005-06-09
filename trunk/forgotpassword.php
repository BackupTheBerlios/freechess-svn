<?php
##############################################################################################
#                                                                                            #
#                                forgotpassword.php                                                
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

        /* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
        fixOldPHPVersions();

        /* connect to database */
        require_once( 'connectdb.php');


        if ($_COOKIE["cookie_language"] != "")
                $language = $_COOKIE["cookie_language"];

        if ($_GET["language"] != "")
                $language = $_GET["language"];

        if ($language=="")
                $language=$CFG_DEFAULT_LANGUAGE;

        /* Language selection */
        require "languages/".$language."/strings.inc.php";

function erro($msg){

         echo "<BR><BR><center><font size=4 face=arial>";
         echo $msg;
         echo "<BR><BR><input type=button value=Retornar onClick='history.go(-1)'></center></font>";
         exit;
}


$email = strip_tags($_POST[email]);
$nick = strip_tags($_POST[nick]);
$action = $_POST[action];

if ($action == '1'){
	$v1 = strpos($email,"@");
	$v2 = strpos($email,".");

	if (!(($v1>0)&&($v2>0)))
		erro("Invalid e-mail address");

	$RA=getenv("X_FORWARDED_FOR");
	if($RA=="")
		$RA=getenv("REMOTE_ADDR");

	$p = mysql_query("SELECT * FROM players WHERE email='$email' AND nick='$nick'")
	     or die("select error");

	if (mysql_num_rows($p) == 0)
	    erro("There isn´t any user with this e-mail.");

	$row  =  mysql_fetch_array($p);

	mail($email,"Webmaster","

You requested your password to login to Webmaster

Username: $row[nick]
Password: $row[password]

The e-mail is not a secure form ... Because of this,
we ask you to change your password as soon as possible.

$CFG_SITE_URL

","from: $CFG_MAILADDRESS");


	echo "
	$MSG_LANG[passwordsent].<BR>
	<BR><BR><BR>
	<a href=index.php>$MSG_LANG[back]</a>";

}
else{
?>
<head>
<LINK rel="stylesheet" href="themes/<?=$CFG_DEFAULT_COLOR_THEME?>/styles.css" type="text/css">
</head>
<body bgcolor=white>

<h1><font face="Verdana"><?=$MSG_LANG["forgotpassword"]?></font></h1>

<font face=verdana size=2>
	<form method=post action=forgotpassword.php>
	<input type=hidden name=action value=1>
	<table border=0>
	<tr><td><?=$MSG_LANG["nick"]?></td><td><input type=text name=nick></td></tr>
	<tr><td>E-mail</td><td><input type=text name=email></td></tr>
	<tr><td colspan=2>
	<input type=button onClick="history.go(-1)" value="<?=$MSG_LANG["back"]?>">&nbsp;&nbsp;
	<input type=submit value="<?=$MSG_LANG[sendmypassword]?>"></td></tr>
	</table>
	</form>
</body>

 <table border=0 cellspacing=0 cellpadding=0 width=400 style="border-collapse: collapse" bordercolor="#111111">	<tr><td colspan=3></td></tr>
        <tr>
        <td align=center>
                <a target="_blank" href="http://www.inf.pucpcaldas.br/">
                <img border="0" src="images/brasao_novo1.jpg"></a></td>
        <td align=center>
                <font face=verdana size=1>
                <a target="_blank" href="http://www.inf.pucpcaldas.br/">
                <img src="images/logo_comp.gif" border=0 width="146" height="41"></a></font></td>
        <td align=center>
                <i><b>
                <font face=Verdana size=2>
		<a style="text-decoration: none; color: black"
		href="http://www.comp.pucpcaldas.br" target="_BLANK">comp.pucpcaldas.br</a></font><font face="Verdana">
                </font></b></i>
        </td>
        </tr>
</table></center>
</div>
      </td></tr>
    </table>
    
</html>

<?
}
?>
