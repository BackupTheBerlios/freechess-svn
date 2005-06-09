<?php
##############################################################################################
#                                                                                            #
#                                groups.php
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

// Groups AddOn 1.5
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

    require_once "groups_functions.php";

$action = (!empty($_POST['action'])) ? $_POST['action'] : $_GET['action'];
$id = (!empty($_POST['id'])) ? $_POST['id'] : $_GET['id'];

$pl = mysql_query("SELECT * FROM players WHERE playerID='".$_SESSION['playerID']."'");
$me = mysql_fetch_array($pl);

$firstName = $me['firstName'];
if (getRating($_SESSION['playerID']) == 0)
    displayError("You can't join a group until you have official rating.  To establish a rating you need to finish 5 games.","mainmenu.php");

?>



<html>
<head>
<title>WebChess</title>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>

<? require_once('header.inc.php');?>
<BR>

<table border="1" style="width: 100%">
<tr><th>
<input type="button" class="BOTOES" value="Original Teams" onClick="window.location='teams.php'">
</th>
</tr>
<tr>
<td>
<a href="groups.php"><?=$MSG_LANG["team"]?></a>&nbsp;|&nbsp;
<a href="groups.php?action=new"><?=$MSG_LANG["createteam"]?></a>&nbsp;|&nbsp;
<a href="groups.php?action=list"><?=$MSG_LANG["jointeam"]?></a>&nbsp;|&nbsp;
<a href="groups.php?action=list"><?=$MSG_LANG["teams"]?></a>&nbsp;|&nbsp;
<a href="tournaments.php?action=groupranking&groups=true"><?=$MSG_LANG["rating"]?></a>
</td>
</tr>
</table>

<br>

<?PHP

////////////////////////////////////// SWITCH START ////////////////////////////////////////////////////////////////////

switch($action) {

default:

?>

<table>
<tr><th><?=$MSG_LANG["team"]?></th></tr>
<tr><td>

<?
$h = get_group($me['playerID']);

if ($h == false)
{
?>
<?=$MSG_LANG["noteam"]?> <br>
<a href="groups.php?action=list"><b><?=$MSG_LANG["join1team"]?></b></a>
</td></tr></table>
<?PHP

} else {

$num = 0;

while (list($key,$val) = each($h))
{

//echo ''.$MSG_LANG[teamleader].' ';

echo ($val['creator'] == $me['playerID']) ? '<b>'.$MSG_LANG[teamleader2].'</b>' : 'in';

echo ' '.$MSG_LANG[teamleader3].' <a href="groups.php?action=view&id='.$val['group_id'].'">'.$val['title'].'</a><br>';

$num++;

$toid = $val['group_id'];
} // while

if ($num == 1)
{
?>

<meta http-equiv="refresh" content="0;url=groups.php?action=view&id=<?=$toid?>">
</td></tr></table>
<?PHP
}

}

break; // case default

case 'new':

$query = "SELECT count(*) as anz FROM groups WHERE creator = '".$me['playerID']."'";
$c1 = mysql_query($query);
$c = mysql_fetch_array($c1);
$c = $c['anz'];

if ($c >= 10 && !in_array($me['playerID'], $ag_leiter))
{
    echo "$MSG_LANG[noteam1]";
    exit;
}

?>

<table>

<tr><th><?=$MSG_LANG["createteam"]?></th></tr>

<tr><td>

<form action="groups.php" method="post">
<input type="hidden" name="action" value="create">

<b><?=$MSG_LANG["nameofteam"]?>: </b>&nbsp;&nbsp;<br><input type="text" name="title"><br><br>

<b><?=$MSG_LANG["description"]?>:</b>&nbsp;&nbsp;<br><textarea name="text" rows="7" cols="30"></textarea><br><br>

<b><?=$MSG_LANG["accesslevel2"]?>:<b>&nbsp;&nbsp;<br><select name="ag"><option value="0">Normal Team</option><option value="1">SubTeam This feature is not working yet.</option></select><br><br>

<input type="submit">

</form>

</td></tr></table>

<?PHP

break; // case new

case 'create':

if (is_creator($me['id']))
{
    echo "$MSG_LANG[noteam2]";
    exit;
}

if (empty($_POST['title']))
{
    echo "$MSG_LANG[nonameofteam]";
    exit;
}

if (empty($_POST['text']))
{
    echo "$MSG_LANG[nodescription]";
    exit;
}

$title = db_input($_POST['title']);
$text = db_input($_POST['text']);
$ag = $_POST['ag'];
$time = time();

$query = "INSERT INTO groups VALUES (NULL, '$title', '$text', '".$me['playerID']."', '$time', '$ag')";

if (mysql_query($query))
{
    $gid = mysql_insert_id();
    $query = "INSERT INTO group_members VALUES ('".$me['playerID']."', '$gid', '$time')";

    if (mysql_query($query))
    {
        echo "$MSG_LANG[teamcreated]";
    }
    else
    {
        echo mysql_error()."<br>".$query;
    }
}
else
{
    echo mysql_error()."<br>".$query;
}

break; // case create

case 'list':

?>

<table>
<tr><th colspan="3"><?=$MSG_LANG["teams"]?></th><tr>
<tr><td><b><?=$MSG_LANG["tteam"]?></b></td><td><b><?=$MSG_LANG["teamleader2"]?></b></td><td><b><?=$MSG_LANG["users"]?></b></td><td><b>AG</b></td></tr>

<?PHP

$g1 = mysql_query("SELECT g.*, p.firstName FROM groups g
                    LEFT JOIN players p on p.playerID = g.creator");
echo mysql_error();
while($g = mysql_fetch_array($g1))
{
    $c = getcount("group_members", "WHERE group_id = '".$g['group_id']."' AND joined > 0");
    $title = db_output($g['title']);
    $text = db_output($g['text']);

    echo "<tr>";
    echo '<td><a href="groups.php?action=view&id='.$g['group_id'].'"><b>'.$title.'</b></a><br>'.$text.'</td>';
    echo '<td><a href="stats_user.php?cod='.$g['creator'].'">'.$g['firstName'].'</a>';
    echo '<td>'.$c.'</td><td>';
    echo ($g['ag'] == 1) ? ''.$MSG_LANG[yes].'' : ''.$MSG_LANG[no].'';
    echo '</td></tr>';

}

echo '</table>';

break; // case list

case 'view':

$g1 = mysql_query("SELECT g.*, p.firstName FROM groups g
                    LEFT JOIN players p on p.playerID = g.creator
                    WHERE g.group_id = '$id'");
echo mysql_error();
$g = mysql_fetch_array($g1);
$c = getcount("group_members", "WHERE group_id = '$id' AND joined > 0");
$title = db_output($g['title']);
$text = db_output($g['text']);

?>

<font size="+1">
<?PHP

echo $title;

echo ($g['ag'] == 1) ? ' - Chess AG' : '';

?>
</font>
<br><br>
<?=$MSG_LANG["teamleader4"]?>: <?PHP echo '<a href="stats_user.php?cod='.$g['creator'].'">'.$g['firstName'].'</a>'; ?>
<?=$MSG_LANG["teamleader5"]?>
 <?=$c?> <?=$MSG_LANG["users"]?>.
<br><br>

<table>
<tr><th colspan="4"><?=$MSG_LANG["teammembers2"]?></th></tr>
<tr><td><b>Name</b></td><td><b><?=$MSG_LANG["experience"]?></b></td><td><b><?=$MSG_LANG["jointeam1"]?></b></td><td><b>Action</b></td></tr>
<?PHP

$p1 = mysql_query("SELECT p.*, g.joined FROM players p
                    LEFT JOIN group_members g
                    ON g.playerID = p.playerID
                    WHERE g.group_id = '$id'
                    AND g.joined > 0
                    ORDER BY g.joined ASC");
echo mysql_error();
while($p = mysql_fetch_array($p1)) {

echo '<tr><td><a href="stats_user.php?cod='.$p['playerID'].'">'.$p['firstName'].'</a></td><td>'.$p['rating'].'</td>';
echo '<td>'.date("d.m.y", $p['joined']).'</td><td>';

if (is_groupcreator($id, $me['playerID']) && $p['playerID'] != $me['playerID'])
{
    echo '<a href="groups.php?action=kick&id='.$p['playerID'].'&group='.$id.'">'.$MSG_LANG[kicken].'</a>';

    $c = getcount("groups", "WHERE creator = '".$me['playerID']."' AND group_id != '$id'");

    if ($c > 0)
    {
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="groups.php?action=move&id='.$p['playerID'].'&group='.$id.'">'.$MSG_LANG[moveotherteam].'</a>';
    }
}
else
{
    echo '&nbsp;';
}

echo '</td></tr>';

}

echo '</table><br>';

if (is_groupcreator($id, $me['playerID']))
{
    echo "<b>$MSG_LANG[teamleader6]</b>";

    $w = getcount("group_members", "WHERE group_id = '$id' AND joined = 0");

    if ($w > 0)
    {
        echo "<br><br>";
        echo "<table><tr><th colspan=3>$MSG_LANG[waitingteam] ".$w." $MSG_LANG[waitingteam1]</th></tr>";
        echo "<tr><td><b>Name</b></td><td><b>$MSG_LANG[experience]</b></td><td><b>Optionen</b></td></tr>";

        $s1 = mysql_query("SELECT p.* FROM players p
                           LEFT JOIN group_members g
                           ON g.playerID = p.playerID
                           WHERE g.group_id = '$id'
                           AND joined = 0");
        echo mysql_error();
        while ($s = mysql_fetch_array($s1))
        {
            echo "<tr>";
            echo '<td><a href="stats_user.php?cod='.$s['playerID'].'">'.$s['firstName'].'</a></td><td>'.$s['rating'].'</td>';
            echo '<td><a href="groups.php?action=response&id='.$s['playerID'].'&group='.$id.'&feedback=1">'.$MSG_LANG[accept].'</a>&nbsp;|&nbsp;';
            echo '<a href="groups.php?action=kick&id='.$s['playerID'].'&group='.$id.'&feedback=0">'.$MSG_LANG[reject].'</a></td></tr>';
        }

        echo "</table>";
    }
}

else {

$h = get_group($me['playerID']);

if ($h == false)
{
    echo ''.$MSG_LANG[jointeam2].'<br>
          <a href="groups.php?action=join&id='.$id.'"><b>'.$MSG_LANG[join2team].'</b></a>';
}

elseif ($id == $h[0]['group_id'])
{
    echo ''.$MSG_LANG[join3team].'<br>';
    echo '<a href="groups.php?action=leave&id='.$id.'">'.$MSG_LANG[join4team].'</a>';
}

else
{
    echo ''.$MSG_LANG[join5team].'';
}

} //creator

break; // case view

case 'join':

$h = get_group($me['playerID']);

if ($h != false)
{
    echo 'You already are in the group '.$h['title'].'.';
    exit;
}

if (mysql_query("INSERT INTO group_members VALUES ('".$me['playerID']."', '$id', 0)"))
{
    // Mail an den Gruppenleiter
    $g = get_groupdata($id);
    $tq = mysql_query("SELECT email, firstName FROM players WHERE playerID = '".$g['creator']."'");
    $toer = mysql_fetch_array($tq);
    $tomail = $toer['email'];
    $toname = $g['firstName'];

    $totitle = 'Bitte um Aufnahme in deine Gruppe';

    $text = 'Hallo '.$toname.',

You received this email, because '.$me['firstName'].' would like to join the group \''.$g['title'].'\'.';

    $header = 'From: ' . $CFG_MAILADDRESS . "\nReturn-Path: " . $CFG_MAILADDRESS . "\n";

    mail($tomail, $totitle, $text,$header);

    echo "$MSG_LANG[yourrequest]";
}
else { echo mysql_error(); }

break; // case join

case 'response':

$group = $_GET['group'];
$feedback = $_GET['feedback'];
$time = time();

if (empty($id))
{
    echo "No player selected.";
    exit;
}

if (empty($group))
{
    echo "No group selected.";
    exit;
}

if (empty($feedback))
{
    echo "No answer selected.";
    exit;
}

if (!is_groupcreator($group, $me['playerID']))
{
    echo "You are not the director/conductor of the group.";
    exit;
}

// Mail an den Gruppenleiter
    $g = get_groupdata($group);
    $tq = mysql_query("SELECT email, firstName FROM players WHERE playerID = '$id'");
    $toer = mysql_fetch_array($tq);
    $tomail = $toer['email'];
    $toname = $toer['firstName'];

    $totitle = 'Bitte um Aufnahme in deine Gruppe';

if ($feedback == 1)
{
    $query = "UPDATE group_members SET joined = '$time' WHERE group_id = '$group' AND playerID = '$id'";

    $text = "Hallo ".$toname.",

You have been accepted in the ".$g['title']."";

}
else
{
    $query = "DELETE FROM group_members WHERE group_id = '$group' AND playerID = '$id'";

    $text = "Hallo ".$toname.",

You were not accepted into the group ".$g['title']."";
}

if (mysql_query($query))
{

$totile = "Admission in a group";

$header = 'From: ' . $CFG_MAILADDRESS . "\nReturn-Path: " . $CFG_MAILADDRESS . "\n";

mail($tomail, $totitle, $text,$header);

echo $toname ." $MSG_LANG[mitgeteilt].";

}
else { echo mysql_error(); }

break; // case repsonse

case 'leave':

$g = $_GET['id'];

if (empty($g))
{
    echo "$MSG_LANG[error]";
    exit;
}

mysql_query("DELETE FROM group_members WHERE playerID = '".$me['playerID']."' AND group_id = '$g'");

echo "MSG_LANG[exitteam]";

break; // case leave

case 'kick':

$g = $_GET['group'];

if (!is_groupcreator($g, $me['playerID']))
{
    echo "$MSG_LANG[offlimits]";
    exit;
}

if (empty($g))
{
    echo "$MSG_LANG[error]";
    exit;
}

if (empty($id))
{
    echo "$MSG_LANG[error]";
    exit;
}

mysql_query("DELETE FROM group_members WHERE playerID = '$id' AND group_id = '$g'");
echo mysql_error();
echo "$MSG_LANG[kicken2]";

break; // case kick

case 'move':

$g = $_GET['group'];
$to = $_GET['to'];

if (!is_groupcreator($g, $me['playerID']))
{
    echo "$MSG_LANG[offlimits]";
    exit;
}

if (empty($g))
{
    echo "$MSG_LANG[error]";
    exit;
}

if (empty($id))
{
    echo "$MSG_LANG[error]";
    exit;
}


$usersd = mysql_query("SELECT * FROM players WHERE playerID='$id'");
$he = mysql_fetch_array($usersd);

?>

<table>
<tr><th><?=$MSG_LANG[moveuser]?></th></tr>
<tr>
<td>

<?PHP

if (empty($to))
{

?>

<?=$MSG_LANG[moveuser1]?>: <b><?=$he['firstName']?></b>
<br><br>

<form action="groups.php" method="GET">
<input type="hidden" name="action" value="move">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="group" value="<?=$g?>">

<?=$MSG_LANG[moveuser2]?>: <select name="to">

<?PHP

    $q1 = mysql_query("SELECT * FROM groups WHERE creator = '".$me['playerID']."'");

    while ($q = mysql_fetch_array($q1))
    {
        echo '<option value="'.$q['group_id'].'">'.$q['title'].'</option>';
    }
}

else
{
    mysql_query("UPDATE group_members SET group_id = '$to' WHERE group_id = '$g' AND playerID = '$id'");
    echo "$MSG_LANG[moveuser3]";
}

?>

</select>
<br><br>
<?PHP if (empty($to)) { ?><input type="submit"><?PHP } ?>
</form>
</td>
</tr>
</table>

<?PHP

break; // case move

} // case

?>

<form name="logout" action="mainmenu.php" method="post">
<input type="hidden" name="ToDo" value="Logout">
</form>

</font>
<!-- Please levae Credits -->
<font face=verdana size=1>Groups Mod 1.5 (c) 2004 by <a href="http://www.fivedigital.net" target="_blank">FiveDigital</a><br><br>



</font>

</body>
</html>