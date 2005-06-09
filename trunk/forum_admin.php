<?php
##############################################################################################
#                                                                                            #
#                                forum_admin.php
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


// Forum AddOn 1.0
// By Thomas Müller (thomas@fivedigital.net)
// Five Digital (http://www.fivedigital.net)

////////////////////////////////////// PROGRAM START ////////////////////////////////////////////////////////////////////

    $root = dirname(__FILE__);



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
         require_once ( 'newgame.php');
    require_once( 'connectdb.php');

    /* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
    fixOldPHPVersions();

    /* check session status */
    require_once('sessioncheck.php');

    /* if this page is accessed directly (ie: without going through login), */
    /* player is logged off by default */
    if (!isset($_SESSION['playerID']))
        $_SESSION['playerID'] = -1;

    /* check if loading game */
    if (isset($_POST['gameID']))
        $_SESSION['gameID'] = $_POST['gameID'];

    /* debug flag */
    define ("DEBUG", 0);

    /* connect to database */
    require_once( 'connectdb.php');

    /* Language selection */
    require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");

    require "forum_functions.php";

$action = (!empty($_POST['action'])) ? $_POST['action'] : $_GET['action'];

$pl = mysql_query("SELECT * FROM players WHERE playerID='".$_SESSION['playerID']."'");
$me = mysql_fetch_array($pl);

$firstName = $me['firstName'];

?>

<html>
<head>
<title>Forum-Admin</title>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>

<? require_once('header.inc.php');?>
<BR>

<?PHP

if (!$me['admin']) {

    echo "Access only for Admins";
}

else {

?>

<table>
<tr>
<th>Forum Admin</th>
</tr>
<tr>
<td>
    <a href="forum_admin.php?action=new">Create New Forum</a>&nbsp;|&nbsp;
    <a href="forum_admin.php?action=forums">Edit Forum</a>
</td>
</tr>
</table>

<br>

<?PHP

switch ($action) {

default:

?>

<table>
<tr>
<td>
<b>Welcome to the forum-Admin</b>
</td>
</tr>
</table>

<?PHP

break; // default

case 'new':

?>

<table>
<tr>
<th>
Create new forum
</th>
</tr>
<tr>
<td>
<br>
<form action="forum_admin.php" method="post">
<input type="hidden" name="action" value="create">
<b>Name of the forum:</b>&nbsp;<input type="text" name="title" maxlength="70"><br>
<b>Description:</b>&nbsp;<input type="text" name="forum_text" maxlength="255" size="80">
<br><br>
<input type="submit">
</form>
</td>
</tr>
</table>

<?PHP

break; // case new

case 'create':

if (empty($_POST['title'])) {

    echo "You must input a name of the forum!";
}

else {

    $title = db_input($_POST['title']);
    $forum_text = db_input($_POST['forum_text']);

    mysql_query("INSERT INTO forums VALUES (NULL, '$title', '$forum_text', 0)");

    echo "Forum Created!";

} // title

break; // case create

case 'forums':

?>

<table>
<tr>
<th colspan="3">Survey</th>
</tr>
<tr>
<td><b>Name</b></td>
<td><b>Edit</b></td>
<td><b>Delete</b></td>
</tr>

<?PHP

$f1 = mysql_query("SELECT * FROM forums WHERE chessforum = 0");

while ($f = mysql_fetch_array($f1)) {

echo "<tr>";
echo "<td>".$f['forum_title']."</td>";
echo '<td><a href="forum_admin.php?action=edit&id='.$f['forum_id'].'">Edit</a></td>';
echo '<td><a href="forum_admin.php?action=delete&id='.$f['forum_id'].'">Delete</a></td>';
echo "</tr>";

}

echo "</table>";

break; // forums

case 'edit':

if (!$_GET['id']) {

echo "Mistake";
exit;

}

$f1 = mysql_query("SELECT * FROM forums WHERE forum_id='".$_GET['id']."'");
$f = mysql_fetch_array($f1);

$forum_title = db_output($f['forum_title']);
$text = db_output($f['forum_text']);

?>

<table>
<tr>
<th>
Forum Created
</th>
</tr>
<tr>
<td>
<br>
<form action="forum_admin.php" method="post">
<input type="hidden" name="action" value="update">
<input type="hidden" name="id" value="<?=$_GET['id']?>">
<b>Name of Forum:</b>&nbsp;<input typse="text" name="title" maxlength="70" value="<?=$forum_title?>"><br>
<b>Discription:</b>&nbsp;<input type="text" name="forum_text" maxlength="255" value="<?=$text?>" size="80">
<br><br>
<input type="submit">
</form>
</td>
</tr>
</table>

<?PHP

break; // case edit

case 'update':

if (empty($_POST['title'])) {

    echo "You must input a name for this forum!";
}

else {

    $title = db_input($_POST['title']);
    $forum_text = db_input($_POST['forum_text']);

    $query = "UPDATE forums
                SET forum_title = '$title',
                forum_text = '$forum_text'
                WHERE forum_id = '".$_POST['id']."'";

    mysql_query($query);
    echo mysql_error();
    echo "Forum Created!";

} // title

break; // case create

case 'delete':

if ($_GET['confirm'] != true) {

$f1 = mysql_query("SELECT * FROM forums WHERE forum_id='".$_GET['id']."'");
$f = mysql_fetch_array($f1);

$forum_title = db_output($f['forum_title']);

?>

    <table width="100%">
    <tr>
    <td>
    <div align="left" width="100%">
    <b>Forum Delete:</b><br><br>
    <?=$forum_title?>
    <br><br>
    <font color="#FF0000">ATTENTION:</font> You are about to delete this post permanently!
    This will delete the first post of the subject  and all replies following
    <br><br>
    <a href="forum_admin.php?action=delete&id=<?=$_GET['id']?>&confirm=true<?=$reply?>">Delete this forum!</a><br>
    <a href="javascript:history.back()">NO, back</a>
    </div>
    </td>
    </tr>
    </table>

<?PHP

} else {

mysql_query("DELETE FROM forum_topics WHERE forum_id = '".$_GET['id']."'");
mysql_query("DELETE FROM forums WHERE forum_id = '".$_GET['id']."'");

echo "Forum gelöscht";

}

break; // delete


}

} // admin

?>

<form name="logout" action="mainmenu.php" method="post">
<input type="hidden" name="ToDo" value="Logout">
</form>

</font>
<!-- Please levae Credits -->
<font face=verdana size=1>Forum Mod 1.0 (c) 2004 by <a href="http://www.fivedigital.net" target="_blank">FiveDigital</a>
</body>
</html>