<?php
##############################################################################################
#                                                                                            #
#                                avatar.php
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



    // Avatar Upload
    // By Thomas Müller (thomas@fivedigital.net)
    // Five Digital (http://www.fivedigital.net)

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



// Laufvariable

$action = (!empty($_POST['action'])) ? $_POST['action'] : $_GET['action'];

// Userdaten

$pl = mysql_query("SELECT * FROM players WHERE playerID='".$_SESSION['playerID']."'");
$me = mysql_fetch_array($pl);

$firstName = $me['firstName'];


switch ($action) {

// avatar ausgeben

//global $firstName;

default:

show_avatar($_GET['avatar']);

break; // case default

// Upload-Seite anzeigen

case 'edit':

?>

<html>
<head>
<meta HTTP-EQUIV='Pragma' CONTENT='no-cache'>
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="-1">

<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">

<title>AVATAR UPLOAD</title>
</head>

<body>

<table border="0" width="750" height="253" bgcolor="black" cellspacing="1" cellpading="1">
    <tr>
    <th bgcolor="beige">CHESSMANIAC AVATAR UPLOAD </th>
    </tr>
    <tr>
    <td bgcolor="white" align="center">
    <b><?=$MSG_LANG["welcomeavatar"]?> <?PHP echo $firstName; ?></b><br>
    <br><?=$MSG_LANG["avatarinfo"]?>
    <br>
    <br>
    <?=$MSG_LANG["avatar1"]?><br>
    <?PHP show_avatar($_SESSION['playerID']); ?><br><br>
    <a href="avatar.php?action=delete">[ <?=$MSG_LANG["avatardelete"]?> ]</a><br>
    <br>
    <hr>
    <?=$MSG_LANG["avatar2"]?><br>
    <br>
    <form enctype="multipart/form-data" action="avatar.php" method="post">
    <input type="hidden" name="action" value="upload"></input>
    <input type="file" name="userfile"></input><br><br>
    <input type="submit"></input>
    </form><br>
    </td>
    </tr>
</table>
<font face="verdana" size="1">Avatar AddOn by <a target="_blank" href="http://www.fivedigital.net">FiveDigital</a>
</body>
</html>

<?PHP

break; // case edit

// Avatar uploaden

case 'upload':

 $file = $_FILES['userfile']['tmp_name'];

 // Dateinendung

 $ext = strtolower(substr($_FILES['userfile']['name'], strrpos( $_FILES['userfile']['name'], "." )+1 ));

 // Fehler: Dateiendung

 if (!in_array($ext, $extensions)) {
     echo "$MSG_LANG[avatar3]";
     exit;
 }

 if ($_FILES['userfile']['size'] > $max_size * 1024) {

 echo "$MSG_LANG[avatar4]" . $max_size . "KB";
 exit;

 }

 list($width, $height, $type, $attr) = getimagesize($file);
 if ($width > $max_width || $height > $max_height) {
    echo "$MSG_LANG[avatar4]: ".$max_width." x ".$max_height." Pixels";
    exit;
 }

 $base = basename($_FILES['userfile']['name'], $ext);

 $name = $_SESSION['playerID'] . '.' . $ext;

 $dest = $root . '/images/avatars/' . $name;

 // Alte Files löschen

 while (list($key, $val) = each($extensions)) {
    $imgpath = $root . '/images/avatars/' . $_SESSION['playerID'] . '.' . $val;
    if (file_exists($imgpath)) {
        @unlink($imgpath);
    }

 }

 move_uploaded_file($file, $dest);

 header("Location: avatar.php?action=edit");

break;

case 'delete':

 while (list($key, $val) = each($extensions)) {

    $imgpath = $root . '/images/avatars/' . $_SESSION['playerID'] . '.' . $val;

    if (file_exists($imgpath)) {

        @unlink($imgpath);

    }

 }

 header("Location: avatar.php?action=edit");

break; // case delete

} // switch

?>
