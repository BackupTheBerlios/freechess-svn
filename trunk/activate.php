<?php
##############################################################################################
#                                                                                            #
#                                activate.php                                                #
# *                            -------------------                                           #
# *   begin                : Friday, January 15, 2005                                        #
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

        //load settings 
include_once ('config.php');


        ///* load external functions for setting up new game */
        //relapce comments with /* like the above with the style below /* is reserved for large blocks of code
        //load external functions for setting up new game 
include_once('chessutils.php');

        ///* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
        //this may not really be necessary- or at least use a version check and put this function in a file by itself
        //it should be optional
        //if ($old_php == true)//config variable to allow admin to specify? 
        // TODO: check this function, clean it up, and do a version check. no sense using it where it isnt needed.
        fixOldPHPVersions();

        ///* connect to database */
        //this is OK, but should really be included into a gl;obal file that also includes config, global functions, etc.
include_once('connectdb.php');


        if ($_COOKIE['cookie_language'] != "")//TODO: move this to player table, load it into session at login time. 
        {
            $language = $_COOKIE['cookie_language'];
        }

        if ($_GET["language"] != "")//TODO: this will be obsolete when we get it to sessions
        {
            $language = $_GET['language'];
        }

        if ($language=="")//this should be set in config as default simply using $language when webmaster sets up the game
        {
                $language=$CFG_DEFAULT_LANGUAGE;
        }

include_once("languages/".$language."/strings.inc.php");// TODO: needs to be broken into smaller specific files or groups

$key = $_GET['key'];

if ($_GET['player'] != "")
{
	$player = $_GET['player'];
}
elseif ($_POST['player'] != "")
{
     $player = $_POST['player'];
}

if ($_GET['action'] != "")
{
	$action = $_GET['action'];
}
elseif ($_POST['action'] != "")
{
	$action = $_POST['action'];
}
if ($action == 'resend')
{
    //TODO: SECURITY EXPLOIT- Unchecked user input. insert '/* for username, login as anybody you want.
	$p = mysql_query("select * from players where playerID='$player'");
	$row = mysql_fetch_array($p);
	$key = $row[validation];
	include_once ('mailvalidation.php');
	mail("$row[firstName] <$row[email]>",$mailsubject,$mailmsg,"From: $CFG_MAILADDRESS");
//TODO: MAILER - Above should be using a mail function utilizing phpmailer class.- portable to windows machines 
			echo "
			<html>
			<head><LINK rel='stylesheet' href='themes/$CFG_DEFAULT_COLOR_THEME/styles.css' type='text/css'></head>
			<body>
			<p align=center><BR><font face=verdana size=3>
			$MSG_LANG[validatiommsg]
			</font>
			<BR><BR><input type=button value=$MSG_LANG[back] onClick=\"window.location='index.php'\">
			</body>
			</html>";
			exit;
			//$smarty->assign('validation_message',$MSG_LANG['validationmsg']);
			//$smarty->assign('color_theme',$CFG_DEFAULT_COLOR_THEME);
			//SMARTYIZE
}
if ($action == "saveemail")
{


			$_POST['firstname'] = strip_tags($_POST['firstname']);
            if ($_POST['firstname'] == "")
            {
                die("Enter a valid Name");//TODO: Lang Files
            }

	    	$v1 = strpos($_POST['email'],"@");
            $v2 = strpos($_POST['email'],".");

            if ($_POST['email'] == "" || $v1 == 0 || $v2 == 0)
            {
                die("Enter a valid Email Address");//TODO: lang files
            }

	// Key generation
	//TODO: This can be re-done a it better with less code using md5 and a randomized value
	list($usec, $sec) = explode(" ", microtime());
	$seed = (float) $sec + ((float) $usec * 100000);
	srand($seed);
	$key = "";
	for ($x=0;$x<16;$x++)
	{
    	$letra = rand(65,90);
		$key .= chr($letra);
	}
//TODO unchecked sql inputs risk of injection. needs prepared and bind vars
	$p = mysql_query("select * from players where playerID='$player' AND password='$_POST[txtpassword]'");

	if (mysql_num_rows($p) == 0)
	{
		die("Invalid Password!");//TODO lang files
	}

	$p = mysql_query("UPDATE players SET firstName='$_POST[firstname]',email='$_POST[email]',ativo='0',validation='$key' WHERE playerID='$player' AND password='$_POST[txtpassword]'");

	$p = mysql_query("select * from players where playerID='$player'");
	$row = mysql_fetch_array($p);

include_once('mailvalidation.php');
	mail("$row[firstName] <$row[email]>",$mailsubject,$mailmsg,"From: $CFG_MAILADDRESS");
//TODO mail should use mail function and phpmailer
			echo "
			<html>
			<head><LINK rel='stylesheet' href='themes/$CFG_DEFAULT_COLOR_THEME/styles.css' type='text/css'></head>
			<body>
			<p align=center><BR><font face=verdana size=3>
			$MSG_LANG[validatiommsg]
			</font>
			<BR><BR><input type=button value=$MSG_LANG[back] onClick=\"window.location='index.php'\">
			</body>
			</html>";
			exit;
}
if ($action == "askemail")
{
	$p = mysql_query("select * from players where playerID='$player'");
	$row = mysql_fetch_array($p);

?>
<LINK rel="stylesheet" href="themes/<?=$CFG_DEFAULT_COLOR_THEME?>/styles.css" type="text/css">
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>
<form name="PersonalInfo" action="activate.php" method="post">
<input type="hidden" name="action" value="saveemail">
<input type="hidden" name="player" value="<?=$player?>">

	<table border="1" width="600">
		<tr>
			<th colspan="2"><?=$MSG_LANG["personalinformation"]?></th>
		</tr>

		<tr>
			<td width="200">
				<?=$MSG_LANG["name"]?>:
			</td>

			<td>
				<input name="firstname" type="text" size="30" value="<? echo($row['firstName']); ?>">
			</td>
		</tr>
		<tr>
			<td width="200">
				E-Mail:
			</td>

			<td>
				<input name="email" type="text" size="30" value="<? //echo($row['email']); ?>">
			</td>
		</tr>
		<tr>
			<td>
				<?=$MSG_LANG["oldpassword"]?>:
			</td>

			<td>
				<input name="txtpassword" size="30" type="password">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" value="<?=$MSG_LANG["changeinformations"]?>">
			</td>
		</tr>
	</table>
	</form>
	
<?
	exit;
}//END askemail HTML to smarty templates
else
{
	if ($key == "")
	{
		echo "Key not found!";//TODO: lang files
		exit;
	}

	$p = mysql_query("select * from players where validation='$key'");
	$row = mysql_fetch_array($p);
	if (mysql_num_rows($p) == 0)
	{
		echo "Account already validated.";//TODO lang files
		exit;
	}
 
	mysql_query("update players set ativo='1',validation='' WHERE validation='$key'");

	mail("$row[firstName] <$row[email]>","WebChess - Account activated","$row[firstName], welcome to WebChess Your account was activated and you can log in trough the url: $CFG_SITE_URL Your username is: $row[nick]","From: $CFG_MAILADDRESS");//TODO - MAIL FUNCTION
}


?>
<head>
<LINK rel="stylesheet" href="themes/<?=$CFG_DEFAULT_COLOR_THEME?>/styles.css" type="text/css">
</head>
<body bgcolor=white>
<BR><p align=center>
<font face=verdana size=3><B>Account Activated!</B><BR><BR>
<font size=2>Your account was activated and you can log in WebChess.<BR><BR>
<input type=button value="WebChess" onClick="window.location='index.php'">
</p>
</body>
