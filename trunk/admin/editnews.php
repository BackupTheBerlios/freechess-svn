<?php
##############################################################################################
#                                                                                            #
#                                editnews.php                                                
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

/* load settings */
include_once ('config.php');

/* define constants */
require_once ( 'chessconstants.php');

/* include outside functions */
if (!isset($_CHESSUTILS))
require_once ( 'chessutils.php');
require_once('gui.php');
require_once('chessdb.php');
require 'move.php';
require 'undo.php';

/* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
fixOldPHPVersions();

/* check session status */
require_once('sessioncheck.php');

/* Language selection */
require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");

/* if this page is accessed directly (ie: without going through login), */
/* player is logged off by default */
if (!isset($_SESSION['playerID']))
$_SESSION['playerID'] = -1;

/* debug flag */
define ("DEBUG", 0);

/* connect to database */
require_once( 'connectdb.php');

//header("Cache-Control: Public");


?>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
<?
if ($errMsg != "")
echo("<p><h2><font color='red'>".$errMsg."</font></h2><p>\n");


$p = mysql_query("SELECT * from news WHERE language like '$_SESSION[pref_language]%' ORDER BY date DESC limit 10");
if (mysql_num_rows($p) > 0 || $CFG_USE_RANDOM_QUOTES){

echo '<table border="1" width="730">';

if (mysql_num_rows($p) > 0){
echo '<tr>';
echo '<th colspan=7>'.$MSG_LANG["editnews"].'</th>';
echo '</tr>';

while($row = mysql_fetch_array($p))
{
$d = explode("-",$row[date]);

$date="$d[1]/$d[2]/$d[0]";

echo "<tr><td style='text-align:justify'>$row[idNews] : $date - $row[title]<BR>".nl2br($row[description])." - ".nl2br($row[author])."</td></tr>";

}
}

echo "</table>";

}

if ($_POST[news_title] != "" && $_POST[news_msg] != "")
{

$theDate = date('Y').'-'.date('m').'-'.date('d');
mysql_query("INSERT INTO news (title, date, description, language, author)
VALUES ('".$_POST[news_title]."', '$theDate','".$_POST[news_msg]."', '".$_SESSION['pref_language']."', '".$_SESSION['firstName']."')");
$_POST[news_title] = "";
$_POST[news_msg] = "";
}

if ($_POST[news_id] != "" )
{


mysql_query("DELETE FROM `news` WHERE `idNews` = '$_POST[news_id]'");
$_POST[news_id] = "";

}
?>


<tr>
<td style="background-color:#DDDDDD" align="center" valign="top">
<form method=POST action="editnews.php">
Title:
<input type="text" name="news_title" size="50" >
<br>News:
<textarea name="news_msg" cols="30" rows="3"></textarea>
<input type="submit" value="<?=$MSG_LANG["write"]?>">&nbsp;
</form>

<form method=POST action="editnews.php">
Delete:
<input type="text" name="news_id" size="5" >
<input type="submit" value="<?=$MSG_LANG["delete"]?>">&nbsp;
<input type="button" value="<?=$MSG_LANG["refresh2"]?>" onClick=javascript:history.go(0)>&nbsp;
</form>
</td></tr>
