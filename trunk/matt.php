<?php
##############################################################################################
#                                                                                            #
#                                matt.php
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


// Matt AddOn 1.0
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

    require "groups_functions.php";

$action = (!empty($_POST['action'])) ? $_POST['action'] : $_GET['action'];
$id = (!empty($_POST['id'])) ? $_POST['id'] : $_GET['id'];

$pl = mysql_query("SELECT * FROM players WHERE playerID='".$_SESSION['playerID']."'");
$me = mysql_fetch_array($pl);

$firstName = $me['firstName'];

?>



<html>
<head>
<title>PSM Schach - <?=$MSG_LANG["training"]?></title>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>

<? require_once('header.inc.php');?>
<BR>

<table border="1" style="width: 650">
<tr><th>
Training
</th>
</tr>
<tr>
<td>
<a href="matt.php?action=new">Matt in einem Zug</a>&nbsp;|&nbsp;
<a href="matt.php?action=new&moves=2">Matt in zwei Zügen</a>&nbsp;|&nbsp;
<a href="matt.php?action=new&moves=3">Taktik-Training</a>&nbsp;|&nbsp;
<a href="matt.php?action=list">Meine Spiele</a>&nbsp;|&nbsp;
<a href="matt.php?action=ranking">Ranglisten</a>&nbsp;|&nbsp;
<a href="matt.php?action=anleitung">Hilfe</a>
</td>
</tr>
</table>

<br>

<table>
<tr>
<td>

<?PHP

////////////////////////////////////// SWITCH START ////////////////////////////////////////////////////////////////////

switch($action) {

default:

?><br>

<b>==> PSM-SCHACH TRAINING <== </b><br><br>
Hier können Sie Ihre Fertigkeiten im königlichen Spiel verbessern.<br><br>

<img src="images/mattzug.JPG">
<br><br>
<br>


<?PHP

break; // default

case 'new':

?>

<iframe src="insertEPDGame.php?moves=<?=$_GET['moves']?>" frameborder="0" width="640" height="780"></iframe>

<?PHP

break;

case 'result':

$str = base64_decode($_GET['code']);

if (empty($str))
{
    echo "Fehler";
    exit;
}

$str = explode("|", $str);
$gameID = $str[0];
$res = $str[1];

if (empty($gameID))
{
    echo "Fehler";
    exit;
}

$query = "SELECT p.*, t.id as trainings_id FROM game_positions p
         LEFT JOIN training_games t
         ON t.id = p.id
         WHERE t.gameID = '$gameID'
         AND playerID = '".$_SESSION['playerID']."'";

$t1 = mysql_query($query);
echo mysql_error();
$t = mysql_fetch_array($t1);

mysql_query("DELETE FROM training_games WHERE gameID='$gameID' AND playerID = '".$_SESSION['playerID']."'");

if ($res == 0)
{
    ?>
    <b>Sie haben die Aufgabe leider nicht gelöst!</b><br><br>
    Versuchen sie es doch noch einmal.

    <?PHP

    $c = getcount("training_ranking", "WHERE playerID='".$me['playerID']."' AND id = '".$t['id']."'");

    if ($c == 0)
    {
        mysql_query("INSERT INTO training_ranking VALUES('".$me['playerID']."','".$t['id']."', 0)");
    }

}

else
{
    if ($t['moves'] < 3)
    {
        echo "<b>Sie haben die Aufgabe gelöst!</b><br><br>";
    }
    else
    {
        echo "<b>Sie haben die Aufgabe gelöst!</b><br><br>";
    }

    $c = getcount("training_ranking", "WHERE playerID='".$me['playerID']."' AND id = '".$t['id']."'");

    if ($c == 0)
    {
        mysql_query("INSERT INTO training_ranking VALUES('".$me['playerID']."','".$t['id']."', 1)");

        echo "Ihr Punktestand hat sich erhöht.";
    }
    else
    {
        echo "Sie haben dieses Spiel jedoch bereits einmal gespielt; Ihr Punktestand bleibt gleich.";
    }

    if ($t['moves'] < 3)
    {
        echo "<br><br>Die gelöste Aufgabe entstammt der Partie: <br><br><b>".$t['title']."</b>";
    }
    else
    {
        echo "<br><br><b>".$t['title']."</b>";
    }

} // res

echo "<br><br>";

if ($t['moves'] > 1)
{
    $p1 = mysql_query("SELECT text, epd3 FROM game_positions_results WHERE pos_id = '".$t['trainings_id']."'");
    echo mysql_error();
    $p = mysql_fetch_array($p1);

    $EPD = ($res == 1) ? $p['epd3'] : $p['epd'];

    ?>
    <iframe src="matt_board.php?EPD=<?=$EPD?>" frameborder="0" width="640" height="480"></iframe>
    <?PHP

    echo "<br><br>".stripslashes($p['text']);

}

break; // case result

case 'list':

echo "<b>Meine Trainingsspiele</b><br><br>";

$g1 = mysql_query("SELECT * FROM training_games WHERE playerID = '".$me['playerID']."'");
$i = 0;
while ($g = mysql_fetch_array($g1))
{
    echo '<a href="chess.php?gameID='.$g['gameID'].'">'.$g['gameID'].'</a><br>';
    $i++;
}

if ($i == 0) echo "Keine laufenden Trainingsspiele vorhanden.";

break; // case list

case 'ranking':

$num = 0;

$moves = ($_GET['moves'] == "2" || $_GET['moves'] == "3") ? $_GET['moves'] : 1;

$n1 = mysql_query("SELECT moves FROM game_positions WHERE moves = '$moves'");
while ($n = mysql_fetch_array($n1))
{
    $num++;
}

$rank = array();

$r1 = mysql_query("SELECT p.moves, r.playerID FROM training_ranking r
                   LEFT JOIN game_positions p
                   ON p.id = r.id
                   WHERE r.won = 1
                   AND p.moves = '$moves'");
while ($r = mysql_fetch_array($r1))
{
    $rank[$r['playerID']]++;
}

arsort($rank);

?>

<a href="matt.php?action=ranking"><b>1-Zug Rangliste</b></a>&nbsp;|&nbsp;
<a href="matt.php?action=ranking&moves=2"><b>2-Zug Rangliste</b></a>&nbsp;|&nbsp;
<a href="matt.php?action=ranking&moves=3"><b>Taktik Rangliste</b></a><br><br>

<table>
<tr>
<th colspan="2">Rangliste - Maximal <?=$num?> Punkte möglich</th>
</tr>
<tr><td><b>Punkte</b></td><td><b>Spieler</b></td></tr>

<?PHP

while (list($key,$val) = each($rank))
{
    echo "<tr><td>".$val."</td><td>";

    $pl = mysql_query("SELECT * FROM players WHERE playerID='$key'");
    $he = mysql_fetch_array($pl);

    echo '<a href="stats_user.php?cod='.$key.'">'.$he['firstName'].'</a></td></tr>';
}

?>

</table>

<?PHP

break; // case ranking


case 'anleitung':

echo "<b>Anleitung:</b><br><br>
<li>Wählen Sie oben den gewünschten Trainingsmodus (Matt in einem Zug/Matt in zwei Zügen/Taktik) und wählen Sie zunächst eine der Brettpositionen aus, <br>die Ihnen per Zufallsgenerator vorgeschlagen werden. </li><br><br><br>



<li>Klicken Sie unterhalb des Feldes auf 'Spiel starten'.</li><br><br><br>
<img src='images/neuesspiel1.JPG'><br><br>

<li>Sie haben entsprechend Ihrer Wahl einen oder zwei Züge, um den Gegner Schachmatt zu setzen (Matt in einem Zug |  Matt in zwei Zügen). Gelingt Ihnen dies, erhalten Sie einen Punkt (drei Punkte) in den Ranglisten. Klappt es nicht, haben Sie weitere Versuche; Sie können jedoch für diese Aufgabe keine Gewinnpunkte mehr erhalten. Im Taktik-Trainer gibt es eine schriftliche Aufgabe, die Sie lösen müssen. Sie finden die Aufgabe in den beiden folgenden Positionen:

<br><br>

<img src='../test/taktik2.jpg'><br><br>

<br><br>oder<br><br>

<img src='../test/taktik1.jpg'><br><br>



<br><br><br><br>

Viel Spaß!<br><br><br><br>";



break; // case anleitung




} // case

?>

</td>
</tr>
</table>

<form name="logout" action="mainmenu.php" method="post">
<input type="hidden" name="ToDo" value="Logout">
</form>

</font>
<!-- Please levae Credits -->
<font face=verdana size=1>Matt Mod 1.0 (c) 2004 by <a href="http://www.fivedigital.net" target="_blank">FiveDigital</a><br><br>



</font>
<? include("footer2.inc.php");?>
</body>
</html>