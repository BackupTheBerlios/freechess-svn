<?php
##############################################################################################
#                                                                                            #
#                               /admin/index.php                                                
# *                            -------------------                                           #
# *   begin                : Wednesday, January 25, 2005                                     
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
	require("../config.php");

	if ($_COOKIE["cookie_language"] != "")
		$language = $_COOKIE["cookie_language"];

	if ($_GET["language"] != "")
		$language = $_GET["language"];

	if ($language=="")
		$language=$CFG_DEFAULT_LANGUAGE;
        
	/* Language selection */
        require "../languages/".$language."/strings.inc.php";

	setcookie("cookie_language",$language,time()+60*60*24*30);
?>
<html>
<head>
	<title>WebChess Admin</title>
    <style>
    TABLE   {font-size: 11px; font-family: verdana; background: #cfcfbb;}
    TD      {background: white; text-align: center;}
    .BOTOES {width:100; background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;}
    </style>

</head>

<body bgcolor=white text=black>
<? require_once('header.inc.php');?>
<BR>
<form name="logout" action="mainmenu.php" method="post">
	<input type="hidden" name="ToDo" value="Logout">
</form>

<form method="post" action="mainmenu.php" name="login">
  <table width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#000000">
    <tr bgcolor=#cfcfbb>
    <td width="86" align="right"><b>
    <font face="Verdana" size="2"><?=$MSG_LANG["language"]?>:</font></b></td>
    <td width="207" align="left"><font face="Verdana">
	<select name="language" onChange="window.location='index.php?language='+document.login.language.value">
	<option value="english"><?=$MSG_LANG["english"]?>
	<option value="portuguese"><?=$MSG_LANG["portuguese"]?>
	<option value="french"><?=$MSG_LANG["french"]?>
	<option value="german"><?=$MSG_LANG["german"]?>
	<option value="spanish"><?=$MSG_LANG["spanish"]?>
	</select>
	</font></td>
    </tr></table>
</form>


<BR><BR><BR>





<script>
	document.login.language.value="<?=$language?>";
</script>

<font face=verdana size=1>
<a href="http://kymera.comp.pucpcaldas.br/projects/compwebchess/" target="_blank">CompWebChess</a> <?=$VERSION?> © 2003,
<a href="http://www.inf.pucpcaldas.br/~rayel" target="_blank">Felipe Rayel</a>, <a href="credits.php?back=mainmenu">Collaborators</a>
<hr width=100% align=left>
</font>

</body>
</html>
