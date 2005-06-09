<?php
##############################################################################################
#                                                                                            #
#                                tournaments.php
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

// Tournaments AddOn 2.0
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



$action = (!empty($_POST['action'])) ? $_POST['action'] : $_GET['action'];

$pl = mysql_query("SELECT * FROM players WHERE playerID='".$_SESSION['playerID']."'");
$me = mysql_fetch_array($pl);

$firstName = $me['firstName'];
if (getRating($_SESSION['playerID']) == 0)
    displayError("You can't participate in a tournament until you have an official rating.  To establish a rating you need to finish 5 games.","mainmenu.php");

?>



<html>
<head>
<title>Webmaster</title>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>

<? require_once('header.inc.php');?>
<br>

<table border="1" style="width: 100%">
<tr>
<td>
<a href="tournaments.php?action=list&view=my"><?=$MSG_LANG["mytournaments"]?></a>&nbsp;|&nbsp;
<a href="tournaments.php?action=new"><?=$MSG_LANG["createtournament"]?></a>&nbsp;|&nbsp;
<a href="tournaments.php?action=list&view=join"><?=$MSG_LANG["jointournaments"]?></a>&nbsp;|&nbsp;
<a href="tournaments.php?action=list&view=history"><?=$MSG_LANG["tournamenthistory"]?></a>&nbsp;|&nbsp;
<a href="tournaments.php?action=ranking"><?=$MSG_LANG["tournamentrankings"]?></a>
</td>
</tr>
</table>

<br>


<?PHP

////////////////////////////////////// SWITCH START ////////////////////////////////////////////////////////////////////

switch($action) {

default:

?>

<table border="1" style="width: 100%">
<tr>
<td>
<h1>Tournaments</h1><br>
<br>
You can play mini-tournaments consisting of 4 players. All players play a game with white and a game with
black with all of the other players to give a total of 6 games. All 6
games are played simultaneously. These games are rated like your normal games.<br>
Furthermore, you can play a bit tournament consisting of 16 playsers. The players will be divided into 4 groups
of 4 players. The winners of each group play out the tournament in a mini-tournemnt.<br>
<br>
Click on the Join a Tournament button above to see a list of tournaments you can join. If you click on the
Create a Tournament button you can create own tournament. Have fun!
</td>
</tr>
</table>

<?PHP

break; // case default

case 'new':

check_banned($_SESSION['playerID']);

?>

<table border="1" style="width: 100%">
<tr>
<td>
<h1><?=$MSG_LANG["createtournament"]?></h1>
<?=$MSG_LANG["createtournamenttext"]?>

<br><br>

<form action="tournaments.php" method="post">
<input type="hidden" name="action" value="create">

<table class="none" width="100%" border="0" cellpadding="4" cellspacing="0">

<tr>
<td width="30%" align="left"><b><?=$MSG_LANG["nameoftournament"]?>: </b></td>
<td width="70%" align="left"><input name="name" type="text"  value="<?=$firstName ?><?=$MSG_LANG["nameoftournament2"]?>">
</td>
</tr>

<tr>
<td width="30%" align="left"><b><?=$MSG_LANG["description"]?>: </b></td>
<td width="70%" align="left"><textarea name="text"></textarea>
</td>
</tr>

<tr>
<td width="30%" align="left"><b><?=$MSG_LANG["tournamentplayers"]?>: </b></td>
<td width="70%" align="left">
<select name="players">
<option value="4"><?=$MSG_LANG["4players"]?></option>
<?PHP

if ((!$t_admin_only) || ($t_admin_only && $me['admin'] == 1))

echo "<option value=\"16\">16 Players</option>";

else

echo "<option value=\"4\">16 Players - Admin only</option>";

?>
</select>

<?PHP

if ($t_admin_only && $me['admin'] == 0)

echo "<br><font size=1>Only admins can create 16player tournaments</font>";
?>
</td>
</tr>


<tr>
<td width="30%" align="left"><b><img src="images/newsm.gif" width="25" height="23">
  <?=$MSG_LANG["timeforeach2"]?>:</b></td>
<td width="70%" align="left"><select name="days">
                <option value="0" SELECTED>14 <?=$MSG_LANG["days"]?></option>

<?



 for ($x=1; $x<$CFG_EXPIREGAME; $x++)

if ( $x == 1 )
      echo "<option value='".($x*1440)."'>$x $MSG_LANG[day]</option>\n";

else

echo "<option value='".($x*1440)."'>$x $MSG_LANG[days]</option>\n";


?>

</select>
</td>
</tr>




<tr>
<td width="30%" align="left"><b>Minimum Rating:</b></td>
<td width="70%" align="left"><select name="min_rating">
<?PHP

for ($i = 1; $i < count($t_min_rating); $i++)
{
    echo '<option value="'.$t_min_rating[$i].'">'.$t_min_rating[$i].'</option>';
}

?>
</select>
</td>
</tr>

<tr>
<td width="30%" align="left"><b>Maximum Rating:</b></td>
<td width="70%" align="left"><select name="max_rating">
<?PHP

for ($i = 1; $i < count($t_max_rating); $i++)
{
    echo '<option value="'.$t_max_rating[$i].'">'.$t_max_rating[$i].'</option>';
}

?>
</select>
</td>
</tr>

<tr>
<td width="30%" align="left"><b><?=$MSG_LANG["tournamentofficialrating"]?>: </b></td>
<td width="70%" align="left"><input type="checkbox" name="official" value="1" checked></input>
</tr>

<tr>
<td width="30%" align="left"><b><img src="images/newsm.gif" width="25" height="23">
  <?=$MSG_LANG["thematic"]?>: </b></TD>
            <td width="70%"><select name="thematic" align="center">
            <option value="0" SELECTED><?=$MSG_LANG["no"]?></option>
            <option value="1"><?=$MSG_LANG["alekhine_2"]?></option>
            <option value="2"><?=$MSG_LANG["birds_2"]?></option>
            <option value="3"><?=$MSG_LANG["budapest_2"]?></option>
            <option value="4"><?=$MSG_LANG["catalan_2"]?></option>
            <option value="5"><?=$MSG_LANG["carokann_2"]?></option>
            <option value="6"><?=$MSG_LANG["cochranegambit_2"]?></option>
            <option value="7"><?=$MSG_LANG["dutchdefense_2"]?></option>
            <option value="8"><?=$MSG_LANG["leningraddutch_2"]?></option>
            <option value="9"><?=$MSG_LANG["fourknights_2"]?></option>
            <option value="10"><?=$MSG_LANG["frenchdefense_2"]?></option>
            <option value="11"><?=$MSG_LANG["frenchadvance_2"]?></option>
            <option value="12"><?=$MSG_LANG["frenchclassical_2"]?></option>
            <option value="13"><?=$MSG_LANG["frenchrubinstein_2"]?></option>
            <option value="14"><?=$MSG_LANG["frenchwinawer_2"]?></option>
            <option value="15"><?=$MSG_LANG["frenchtarrasch_2"]?></option>
            <option value="16"><?=$MSG_LANG["grob_2"]?></option>
            <option value="17"><?=$MSG_LANG["grunfeld_2"]?></option>
            <option value="18"><?=$MSG_LANG["kingsgambit_2"]?></option>
            <option value="19"><?=$MSG_LANG["kingsindian_2"]?></option>
            <option value="20"><?=$MSG_LANG["italiangame_2"]?></option>
            <option value="21"><?=$MSG_LANG["larsens_2"]?></option>
            <option value="22"><?=$MSG_LANG["modernbenoni_2"]?></option>
            <option value="23"><?=$MSG_LANG["muziogambit_2"]?></option>
            <option value="24"><?=$MSG_LANG["nimzoindian_2"]?></option>
            <option value="25"><?=$MSG_LANG["petroff_2"]?></option>
            <option value="26"><?=$MSG_LANG["philidor_2"]?></option>
            <option value="27"><?=$MSG_LANG["queensgambit1_2"]?></option>
            <option value="28"><?=$MSG_LANG["queensgambit2_2"]?></option>
            <option value="29"><?=$MSG_LANG["queensindian_2"]?></option>
            <option value="30"><?=$MSG_LANG["ruylopez_2"]?></option>
            <option value="31"><?=$MSG_LANG["scandinavian_2"]?></option>
            <option value="32"><?=$MSG_LANG["scotch_2"]?></option>
            <option value="33"><?=$MSG_LANG["sicilian_2"]?></option>
            <option value="34"><?=$MSG_LANG["sicilianalapin_2"]?></option>
            <option value="35"><?=$MSG_LANG["sicilianclosed_2"]?></option>
            <option value="36"><?=$MSG_LANG["siciliansveshnikov_2"]?></option>
            <option value="37"><?=$MSG_LANG["siciliansimagin_2"]?></option>
            <option value="38"><?=$MSG_LANG["sicilianpaulsen_2"]?></option>
            <option value="39"><?=$MSG_LANG["sicilianrichterrauzer_2"]?></option>
            <option value="40"><?=$MSG_LANG["siciliandragon_2"]?></option>
            <option value="41"><?=$MSG_LANG["sicilianscheveningen_2"]?></option>
            <option value="42"><?=$MSG_LANG["siciliansozin_2"]?></option>
            <option value="43"><?=$MSG_LANG["siciliannajdorf_2"]?></option>
            <option value="44"><?=$MSG_LANG["slav_2"]?></option>
            <option value="45"><?=$MSG_LANG["sokolsky1_2"]?></option>
            <option value="46"><?=$MSG_LANG["tarrasch_2"]?></option>
            <option value="47"><?=$MSG_LANG["vienna_2"]?></option>
            <option value="48"><?=$MSG_LANG["volgagambit_2"]?></option>
            </select></TD></TR>



<tr>
<td width="30%" align="left"><b><?=$MSG_LANG["displayplayers"]?>:</b> </td>
<td widht="70%" align="left">
<select name="display">
<option value="users"><?=$MSG_LANG["showusersonly"]?></option>
<option value="group"><?=$MSG_LANG["showusersandgroups"]?></option>
<option value=""><?=$MSG_LANG["dontshowusers"]?></option>
</select>
</td>
</tr>

<tr>
<td width="30%" align="left"><b><?=$MSG_LANG["accesslevel"]?>:</b> </td>
<td widht="70%" align="left">
<select name="access">
<option value=""><?=$MSG_LANG["allplayers"]?></option>
<option value="group"><?=$MSG_LANG["playersinusergroups"]?></option>
<?PHP

include_once ("groups_functions.php");

$h = get_group($me['playerID']);

if ($h != false)
{
?>
<option value="<?=$h['group_id']?>"><?=$MSG_LANG["playersinmyusergroup"]?></option>
<?PHP } ?>
<option value="group_ag"><?=$MSG_LANG["playersinschachags"]?></option>
</select>
</td>
</tr>

<tr>
<td colspan="2"><input type="submit"></input></td>
</tr>

</table>

</input>
</form>

</td>
</tr>
</table>

<?PHP

break; // case new

case 'create':

if (!$me['ativo'])
{
    echo "<b>$MSG_LANG[inactive].</b>";
         exit;
}

$name = db_input($_POST['name']);
$text = db_input($_POST['text']);
$min_rating = $_POST['min_rating'];
$max_rating = $_POST['max_rating'];
$players = $_POST['players'];
$days = $_POST['days'];


if ($players != 4 && $players != 16)
{
    echo "<b>Invalid Playercount: ".$players."</b>";
         exit;
}

if ($min_rating > $max_rating)
{
    echo "<b>The maximum rating has to be higher than the minimum rating.</b>";
         exit;
}

while ($min_rating > $max_rating) $max_rating = $max_rating + 100;

if (!isset($name) || !isset($text))
{
    echo "<b>Please enter a name and a description for yout tournament.</b>";
         exit;
}

$time = time();

if ($players == 4){
   $firstplayer = $_SESSION['playerID'];
   //check rating is set correctly for host player
   $tmpQuery = "SELECT * FROM players WHERE playerID = '$firstplayer' ";
   $tmpPlayers = mysql_query($tmpQuery);
   $tmpPlayer = mysql_fetch_array($tmpPlayers, MYSQL_ASSOC);
   $my_rating = $tmpPlayer['rating'];
   if($my_rating > $max_rating){$max_rating = ($my_rating+200);}
   if($my_rating < $min_rating){$min_rating = ($my_rating-200);}
}
else{
   $firstplayer = '';
}
$_POST['official'] = (!empty($_POST['official'])) ? $_POST['official'] : '0';

$display = $_POST['display'];
$access = $_POST['access'];

$query = "INSERT INTO tournaments (id,time,creator,min_rating,max_rating,text,name,players,player1,player2,player3,player4,official,thematic,big,winner,display,access,days) VALUES (NULL, '$time', '".$_SESSION['playerID']."', '$min_rating', '$max_rating', '$text', '$name', '$players', '".$firstplayer."', '', '', '', ".$_POST['official'].", ".$_POST['thematic'].", '', '', '$display', '$access', '$days')";

if (mysql_query($query)) echo "<b>Tournament '".db_output($name)."' successfully created.</b>";

else echo mysql_error() . "<br>" . $query."<br><b>Cannot create tournament.</b>";

if ($players == 16)
{
        // Yummi, we have a BIG tournament, let's create the other 5 Tournaments:
         // Group1, Group2, Group3, Group4 and the tournaments for the winners

$tname = $name;

$name = $tname." Group 1";

$t = mysql_query("SELECT id FROM tournaments WHERE time='$time'");
$big = mysql_fetch_array($t);

$big = $big['id'];

$query = "INSERT INTO tournaments (id,time,creator,min_rating,max_rating,text,name,players,player1,player2,player3,player4,official,thematic,big,winner,display,access,days) VALUES (NULL, '$time', '".$_SESSION['playerID']."', '$min_rating', '$max_rating', '$text', '$name', '4', '".$_SESSION['playerID']."', '', '', '', ".$_POST['official'].", ".$_POST['thematic'].", '$big', '', '$display', '$access', '$days')";

if (mysql_query($query)) echo "<br><b>Tournament '".db_output($name)."' successfully created.</b>";

else echo mysql_error() . "<br>" . $query."<br><b>Cannot create tournament.</b>";

$name = $tname." Group 2";

$query = "INSERT INTO tournaments (id,time,creator,min_rating,max_rating,text,name,players,player1,player2,player3,player4,official,thematic,big,winner,display,access,days) VALUES (NULL, '$time', '".$_SESSION['playerID']."', '$min_rating', '$max_rating', '$text', '$name', '4', '', '', '', '', ".$_POST['official'].", ".$_POST['thematic'].", '$big', '', '$display', '$access', '$days')";

if (mysql_query($query)) echo "<br><b>Tournament '".db_output($name)."' successfully created.</b>";

else echo mysql_error() . "<br>" . $query."<br><b>Cannot create tournament.</b>";

$name = $tname." Group 3";

$query = "INSERT INTO tournaments (id,time,creator,min_rating,max_rating,text,name,players,player1,player2,player3,player4,official,thematic,big,winner,display,access,days) VALUES (NULL, '$time', '".$_SESSION['playerID']."', '$min_rating', '$max_rating', '$text', '$name', '4', '', '', '', '', ".$_POST['official'].", ".$_POST['thematic'].", '$big', '', '$display', '$access', '$days')";

if (mysql_query($query)) echo "<br><b>Tournament '".db_output($name)."' successfully created.</b>";

else echo mysql_error() . "<br>" . $query."<br><b>Cannot create tournament.</b>";

$name = $tname." Group 4";

$query = "INSERT INTO tournaments (id,time,creator,min_rating,max_rating,text,name,players,player1,player2,player3,player4,official,thematic,big,winner,display,access,days) VALUES (NULL, '$time', '".$_SESSION['playerID']."', '$min_rating', '$max_rating', '$text', '$name', '4', '', '', '', '', ".$_POST['official'].", ".$_POST['thematic'].", '$big', '', '$display', '$access', '$days')";

if (mysql_query($query)) echo "<br><b>Tournament '".db_output($name)."' successfully created.</b>";

else echo mysql_error() . "<br>" . $query."<br><b>Cannot create tournament.</b>";

$name = $tname." Finals";

$query = "INSERT INTO tournaments (id,time,creator,min_rating,max_rating,text,name,players,player1,player2,player3,player4,official,thematic,big,winner,display,access,days) VALUES (NULL, '$time', '".$_SESSION['playerID']."', '$min_rating', '$max_rating', '$text', '$name', '4', '', '', '', '', ".$_POST['official'].", ".$_POST['thematic'].", '$big', '', '$display', '$access', '$days')";

if (mysql_query($query)) echo "<br><b>Tournament '".db_output($name)."' successfully created.</b>";

else echo mysql_error() . "<br>" . $query."<br><b>Cannot create tournament.</b>";

}

break; // case create

case 'list':

$view = $_GET['view'];

if ($view == 'join')
{
        $title = $MSG_LANG['jointournaments'];
}
elseif ($view == 'history')
{
        $title = $MSG_LANG['tournamenthistory'];
}

elseif ($view == 'my')
{
        $title = $MSG_LANG['mytournaments'];
}

else
{
    $title = 'Alle Turniere';
}

?>

<table border="1" style="width: 100%">
<tr>
<td>
<h1><?=$title?></h1><br>
<br>

<table border="0">

<tr>
<td><b>ID</b></td>
<td><b><?=$MSG_LANG["nameoftournament"]?></b></td>
<td><b><?=$MSG_LANG["player"]?></b></td>
<td><b><?=$MSG_LANG["tournamentrankings3"]?></b></td>
<td><b><?=$MSG_LANG["timeforeach"]?></b></td>
<td><b><?=$MSG_LANG["thematic"]?></b></td>
<td><b><?=$MSG_LANG["created"]?></b></td>
<td><b><?=$MSG_LANG["winner"]?></b></td>
</tr>

<?PHP

$query = "SELECT * FROM tournaments ";

if ($view == 'history' || empty($view))
{
    $query .= "WHERE name NOT LIKE '%Group%' and name NOT LIKE '% Finals'";
}

if ($view == 'join' || $view == 'my')
{
    $query .= "WHERE big = 0 AND winner = 0";
}

$query .= " ORDER BY id DESC";

$query = mysql_query($query);
while($t = mysql_fetch_array($query))
{

if ( ($view == 'my' && joined($t['id'], $_SESSION['playerID']) && get_winner($t['id']) == 0) ||
     ($view == 'join' && !joined($t['id'], $_SESSION['playerID']) && get_num($t['id']) < $t['players']) ||
     ($view == 'history' && get_winner($t['id'])) ||
     (empty($view))
   )
{

    $name = ($t['players'] == 4) ? db_output($t['name']) : "<b>".db_output($t['name'])."</b>";

    $num = 0;
    $num = get_num($t['id']);
    if ($t[thematic] == 1)
    {
    $thematic = $MSG_LANG[alekhine];
    }
    elseif ($t[thematic] == 2)
    {
    $thematic = $MSG_LANG[birds];
    }
    elseif ($tmpGame[thematic] == 3)
    {
    $thematic = $MSG_LANG[budapest];
    }
    elseif ($t[thematic] == 4)
    {
    $thematic = $MSG_LANG[catalan];
    }
    elseif ($tmpGame[thematic] == 5)
    {
    $thematic = $MSG_LANG[carokann];
    }
    elseif ($t[thematic] == 6)
    {
    $thematic = $MSG_LANG[cochranegambit];
    }
    elseif ($tmpGame[thematic] == 7)
    {
    $thematic = $MSG_LANG[dutchdefense];
    }
    elseif ($t[thematic] == 8)
    {
    $thematic = $MSG_LANG[leningraddutch];
    }
    elseif ($tmpGame[thematic] == 9)
    {
    $thematic = $MSG_LANG[fourknights];
    }
    elseif ($t[thematic] == 10)
    {
    $thematic = $MSG_LANG[frenchdefense];
    }
    elseif ($tmpGame[thematic] == 11)
    {
    $thematic = $MSG_LANG[frenchadvance];
    }
    elseif ($t[thematic] == 12)
    {
    $thematic = $MSG_LANG[frenchclassical];
    }
    elseif ($tmpGame[thematic] == 13)
    {
    $thematic = $MSG_LANG[frenchrubinstein];
    }
    elseif ($t[thematic] == 14)
    {
    $thematic = $MSG_LANG[frenchwinawer];
    }
    elseif ($tmpGame[thematic] == 15)
    {
    $thematic = $MSG_LANG[frenchtarrasch];
    }
    elseif ($t[thematic] == 16)
    {
    $thematic = $MSG_LANG[grob];
    }
    elseif ($tmpGame[thematic] == 17)
    {
    $thematic = $MSG_LANG[grunfeld];
    }
    elseif ($t[thematic] == 18)
    {
    $thematic = $MSG_LANG[kingsgambit];
    }
    elseif ($tmpGame[thematic] == 19)
    {
    $thematic = $MSG_LANG[kingsindian];
    }
    elseif ($t[thematic] == 20)
    {
    $thematic = $MSG_LANG[italiangame];
    }
    elseif ($tmpGame[thematic] == 21)
    {
    $thematic = $MSG_LANG[larsens];
    }
    elseif ($t[thematic] == 22)
    {
    $thematic = $MSG_LANG[modernbenoni];
    }
    elseif ($tmpGame[thematic] == 23)
    {
    $thematic = $MSG_LANG[muziogambit];
    }
    elseif ($t[thematic] == 24)
    {
    $thematic = $MSG_LANG[nimzoindian];
    }
    elseif ($tmpGame[thematic] == 25)
    {
    $thematic = $MSG_LANG[petroff];
    }
    elseif ($t[thematic] == 26)
    {
    $thematic = $MSG_LANG[philidor];
    }
    elseif ($tmpGame[thematic] == 27)
    {
    $thematic = $MSG_LANG[queensgambit1];
    }
    elseif ($t[thematic] == 28)
    {
    $thematic = $MSG_LANG[queensgambit2];
    }
    elseif ($tmpGame[thematic] == 29)
    {
    $thematic = $MSG_LANG[queensindian];
    }
    elseif ($t[thematic] == 30)
    {
    $thematic = $MSG_LANG[ruylopez];
    }
    elseif ($tmpGame[thematic] == 31)
    {
    $thematic = $MSG_LANG[scandinavian];
    }
    elseif ($t[thematic] == 32)
    {
    $thematic = $MSG_LANG[scotch];
    }
    elseif ($tmpGame[thematic] == 33)
    {
    $thematic = $MSG_LANG[sicilian];
    }
    elseif ($t[thematic] == 34)
    {
    $thematic = $MSG_LANG[sicilianalapin];
    }
    elseif ($tmpGame[thematic] == 35)
    {
    $thematic = $MSG_LANG[sicilianclosed];
    }
    elseif ($t[thematic] == 36)
    {
    $thematic = $MSG_LANG[siciliansveshnikov];
    }
    elseif ($tmpGame[thematic] == 37)
    {
    $thematic = $MSG_LANG[siciliansimagin];
    }
    elseif ($t[thematic] == 38)
    {
    $thematic = $MSG_LANG[sicilianpaulsen];
    }
    elseif ($tmpGame[thematic] == 39)
    {
    $thematic = $MSG_LANG[sicilianrichterrauzer];
    }
    elseif ($t[thematic] == 40)
    {
    $thematic = $MSG_LANG[siciliandragon];
    }
    elseif ($tmpGame[thematic] == 41)
    {
    $thematic = $MSG_LANG[sicilianscheveningen];
    }
    elseif ($t[thematic] == 42)
    {
    $thematic = $MSG_LANG[siciliansozin];
    }
    elseif ($tmpGame[thematic] == 43)
    {
    $thematic = $MSG_LANG[siciliannajdorf];
    }
    elseif ($t[thematic] == 44)
    {
    $thematic = $MSG_LANG[slav];
    }
    elseif ($tmpGame[thematic] == 45)
    {
    $thematic = $MSG_LANG[sokolsky1];
    }
    elseif ($t[thematic] == 46)
    {
    $thematic = $MSG_LANG[sokolsky1];
    }
    elseif ($tmpGame[thematic] == 47)
    {
    $thematic = $MSG_LANG[vienna];
    }
    elseif ($t[thematic] == 48)
    {
    $thematic = $MSG_LANG[volgagambit];
    }
    else
    {
    $thematic = no;
    }

    if ($t['days'] == 0)
    {
    $days = 14;
    }
    else
    {
    $days = (($t['days']/60)/24);
    }
    $rating = $t['min_rating'] . " - " . $t['max_rating'];
    $date = date("m.d.y", $t['time']);
    $winn = get_userdata(get_winner($t['id']));

    echo "<tr>
    <td>".$t['id']."</td>
    <td><a href=\"tournaments.php?action=view&id=".$t['id']."\">".$name."</a></td>
    <td>".$num." / ".$t['players']."</td>
    <td>".$rating."</td>
    <td>".$days." days</td>
    <td>".$thematic."</td>
    <td>".$date."</td>
    <td>".$winn['firstName']."</td>
    </tr>";

} // join check

} // while

?>

</table>

</td>
</tr>
</table>

<?PHP

break; // case list

case 'view':

$id = $_GET['id'];

$t = mysql_fetch_array(mysql_query("SELECT * FROM tournaments WHERE id = '$id'"));

$name = db_output($t['name']);
$text = db_output($t['text']);
$rating = $t['min_rating'] . " - " . $t['max_rating'];
$date = date("m.d.y", $t['time']);
$official = ($t['official'] == 1) ? $MSG_LANG["yes"] : $MSG_LANG["no"];
$thematic = db_output($t['thematic']);
$days = $t['days'];

$players = get_players($id);

$creator = get_userdata($t['creator']);

?>

<table border="1" style="width: 100%">
<tr>
<td>
<h1><?=$name?></h1>

<?PHP

if (($t['big'] != 0) || ($t['big'] == 0 && $t['players'] == 16))
{
         if ($t['big'] != 0)
         {
            $query = "SELECT * FROM tournaments WHERE id='".$t['big']."'";
            $b = mysql_fetch_array(mysql_query($query));

            echo "<b>Part of <a href=\"tournaments.php?action=view&id=".$b['id']."\">".$b['name']."</a></b><br><br>";

                 $query = "SELECT winner FROM tournaments WHERE big = '".$t['big']."' AND name LIKE '%Finals'";
         }
    else
         {
            $query = "SELECT winner FROM tournaments WHERE big = '".$t['id']."' AND name LIKE '%Finals'";
         }

         $f = mysql_fetch_array(mysql_query($query));

         if ($f['winner'] != 0)
    {
            $winner = get_userdata($f['winner']);

                 echo "<h2>".$winner['firstName']." $MSG_LANG[winsthetournament]</h2>";
         }

}

else
{
    if ($t['winner'] != 0)
    {
            $winner = get_userdata($t['winner']);

                 echo "<h2>".$winner['firstName']." $MSG_LANG[winsthetournament]</h2>";
         }
}

if (!started($id))
{
         if (!joined($id, $_SESSION['playerID']))
         {
        echo "<b>$MSG_LANG[tournamentwhy]</b>";
         }
         else
         {
            echo "<b>$MSG_LANG[tournamentwhy2]</b>";
         }

}

else
{
    echo "<b>$MSG_LANG[tournamentcross1] <a href=\"tournaments.php?action=crosstable&id=".$id."\">$MSG_LANG[tournamentcross2]</a>$MSG_LANG[tournamentcross3]</b>";

         $game2join = '';
         $query = "SELECT * FROM tournaments WHERE big='".$id."' AND name like '%Finals'";
         $b = mysql_query($query);
         $game2join = mysql_fetch_array($b);
         echo mysql_error();

         if ($game2join['player4'] != 0)
         {
            echo "<br><b>$MSG_LANG[finalsstarted] <a href=\"tournaments.php?action=view&id=".$game2join['id']."\">$MSG_LANG[tournamentcross2]</a> $MSG_LANG[finalsstarted2]</b>";
         }

}

?>

<br><br>


<table border="0" style="width: 100%">

<tr>
<td><b><?=$MSG_LANG["creator"]?>: </b></td>
<td><a href="stats_user.php?cod=<?=$creator['playerID']?>"><?=$creator['firstName']?></a></td>
</tr>

<tr>
<td><b><?=$MSG_LANG["created"]?>: </b></td>
<td><?=$date?></td>
</tr>

<tr>
<td><b><?=$MSG_LANG["users"]?>: </b></td>
<td><?=$t['players']?></td>
</tr>

<tr>
<td><b><?=$MSG_LANG["tournamentrankings3"]?>: </b></td>
<td><?=$rating?></td>
</tr>

<tr>
<td><b>Official Ranking: </b></td>
<td><?=$official?></td>
</tr>

<tr>
<td><b><?=$MSG_LANG["description"]?>: </b></td>
<td><?=$text?></td>
</tr>

<tr>
<td><b><?=$MSG_LANG["accesslevel"]?>: </b></td>
<td>
<?PHP

include("groups_functions.php");

if ($t['access'] == "")
{
    echo "$MSG_LANG[allplayers]";
}
elseif ($t['access'] == "group")
{
    echo "$MSG_LANG[playersinusergroups]";
}
elseif ($t['access'] == "group_ag")
{
    echo "$MSG_LANG[playersinschachags]";
}
else
{
    $h = get_groupdata($t['access']);

    echo ''.$MSG_LANG[playersinthegroup].' <a href="groups.php?action=view&id='.$t['access'].'">'.$h['title'].'</a>';
}

?>
</td>
</tr>

<tr>
<td><b>Thematic Tournament: </b></td>
<td>
<?
   if ($thematic > "0")
                $thematic = $MSG_LANG["yes"];
            else $thematic = $MSG_LANG["no"];

echo "$thematic";

?>
</td>
</tr>

<tr>
<td align="center"><b><?=$MSG_LANG["timeforeach2"]?>: </b></td>
<td align="center">
<?
if ($t[days] == 0)
            echo "14 $MSG_LANG[days]";
else if ($t[days] == 1440)
            echo "<b><font color='red'>".($t['days']/24/60)." $MSG_LANG[day]</font></b>";
            else
            echo "<b><font color='red'>".($t['days']/24/60)." $MSG_LANG[days]</font></b>";

?>
</td>
</tr>


</table>

<br>
<br>

<table border="0" style="width: 100%">

<?PHP

$cspan = ($t['players'] == 16) ? 3 : 2;

?>

<tr>
<td colspan="<?=$cspan?>"><b><?=$MSG_LANG["tournamentlistof"]?></b></td>
</tr>

<?PHP

if ($t['display'] != '')
{
?>
<tr>
<td><b><?=$MSG_LANG["player"]?></b></td>
<td><b><?=$MSG_LANG["tournamentrankings2"]?></b></td>
<?PHP
}

else {

?>

<tr>
<td colspan="<?=$cspan?>"><b><?=$MSG_LANG["hidden"]?>Hidden</b></td>
</tr>

<?PHP

}

if ($t['players'] == 16 && $t[''] == 'group')
{
    echo "<td><b>Game</b></td>";
}

?>
</tr>

<?PHP

if ($t['display'] == 'users')
{
    asort($players);
}

while (list($key, $val) = each($players))
{
    if ($t['display'] != '')
         {
            $player = get_userdata($val);
            echo "<tr><td><a href=\"stats_user.php?cod=".$player['playerID']."\"><b>".$player['firstName'] ."</b></a></td>
            <td>" . $player['rating'] . "</td>";
         }

         if ($t['players'] == 16 && $t['display'] == 'group')
         {
            $g = mysql_query("SELECT * FROM tournaments WHERE
                        (player1 = '".$player['playerID']."' OR player2 = '".$player['playerID']."' OR
                                  player3 = '".$player['playerID']."' OR player4 = '".$player['playerID']."')
                             AND big = '$id'"); echo mysql_error();

                 $game = mysql_fetch_array($g);

                 echo "<td><a href=\"tournaments.php?action=view&id=".$game['id']."\">".$game['name']."</a></td>";
         }

         echo "</tr>";

} // while

$free = $t['players'] - get_num($id);

// get the game's status

if (started($id))
{
    if (joined($id, $_SESSION['playerID']) && !get_winner($id))
         {
        if ($_SESSION['playerID'] != $t['creator'])
        {
            echo "<tr><td colspan=\"$cspan\">$MSG_LANG[tournamentalready]";
            }
            else
            {
                echo "<tr><td colspan=\"$cspan\">$MSG_LANG[tournamentcreated]";
            }

                 echo "<br>";
                 $gid = joined($id, $_SESSION['playerID']);
                 $g2 = mysql_query("SELECT name FROM tournaments WHERE id = '$gid'");
                 $g = mysql_fetch_array($g2);

                 echo ''.$MSG_LANG[yourareplayingin].' <a href="tournaments.php?action=view&id='.$gid.'">'.$g['name'].'</a>';

         }
         else
         {
                 if (!get_winner($id))
                    echo "<tr><td colspan=\"$cspan\"><b>$MSG_LANG[tournamentalready2]</b>";
         }
}
else
{
    if (joined($id, $_SESSION['playerID']))
    {
        echo "<tr><td colspan=\"$cspan\">$MSG_LANG[tournamentalready]<br>";

        $gid = joined($id, $_SESSION['playerID']);
        $g2 = mysql_query("SELECT name FROM tournaments WHERE id = '$gid'");
        $g = mysql_fetch_array($g2);

        echo ''.$MSG_LANG[yourareplayingin].' <a href="tournaments.php?action=view&id='.$gid.'">'.$g['name'].'</a>';

    }
    else
    {
        echo "<tr>
             <td colspan=\"$cspan\">$MSG_LANG[tournamentinvite1] <b>".$free."</b> $MSG_LANG[tournamentinvite2]</td></tr>
             <tr><td colspan=\"$cspan\">";

        $uh = get_group($me['playerID']);
        $ug = get_groupdata($uh['group_id']);

        if (($t['access'] == "") ||($t['access'] == "group" && $uh != false) ||($t['access'] == "group_ag" && $ug['ag'] == "1") ||(is_numeric($t['access']) && $t['access'] == $uh['group_id']))

        {
            echo "<b><a href=\"tournaments.php?action=join&id=".$t['id']."\">
             $MSG_LANG[tournamentclick]</a></b>";
        }
        else
        {
            echo "$MSG_LANG[youmaynotjoin]";
        }
    }
}

echo "</td></tr></table>";

if (started($id))
{

?>

<br>

<h2><?=$MSG_LANG["liveranking"]?></h2>

<?PHP

$players = get_ranking($id);
draw_ranking($players);

} // started

break; // case view

case 'join':

check_banned($_SESSION['playerID']);

$id = $_GET['id'];

$t = mysql_fetch_array(mysql_query("SELECT * FROM tournaments WHERE id = '$id'"));

$name = db_output($t['name']);

if (strpos($name, "Finals") > 0)
{
    echo "<b>Cannot join Finals</b>";
        exit;
}

if (joined($id, $_SESSION['playerID']))
{
    echo "<b>$MSG_LANG[tournamentalready]</b>";
         exit;
}

if (get_num($id) == $t['players'])
{
    echo "<b>$MSG_LANG[tournament44]</b>";
         exit;
}

if ($me['rating'] < $t['min_rating'] || $me['rating'] > $t['max_rating'])
{
    echo "<b>$MSG_LANG[tournamentsorry]</b>";
         exit;
}

include("groups_functions.php");

$uh = get_group($me['playerID']);
$ug = get_groupdata($uh['group_id']);

if (($t['access'] != "") &&
    ($t['access'] == "group" && $uh == false) &&
    ($t['access'] == "group_ag" && $ug['ag'] == 0) &&
    (is_numeric($t['access']) && $t['access'] =! $uh['group_id']))
{
    echo "You may not join this tournament";
    exit;
}

// find out where to join

if ($t['players'] == 4)
{
    // Thank god, we got a small tournament

         $field = '';

         for ($i = 2; $i < 5; $i++)
         {
            if ($t['player'.$i] == 0 && $field == '')
                 {
                    $field = 'player'.$i;
                 }
         }

         mysql_query("UPDATE tournaments set ".$field." = '".$_SESSION['playerID']."' WHERE id = '$id'");

         // That was the easy part, now create the games

         if ($field == 'player4')
         {
            create_games($id);
         }

} elseif($t['players'] == 16)
{
    // Evil BIG tournament from hell

         // Okay, lets get the 4 Group Games

         $game2join = '';

         $b = mysql_query("SELECT * FROM tournaments WHERE big='$id' AND name NOT like '%Finals' ORDER BY id ASC");
         while ($big = mysql_fetch_array($b))
         {
                if (get_num($big['id']) < $big['players'] && $game2join == '')
                 {
                    $game2join = $big;
                         $game2join['gamenum'] = $i;
                 }

         } // while

         if ($game2join == '')
         {
                 echo "<b>Tournament full</b>";
                exit;
         }

         // Now we got the game 2 join, yeah, now it's gettin' way easier

         $field = '';

         for ($i = 1; $i < 5; $i++)
         {
            if ($game2join['player'.$i] == 0 && $field == '')
                 {
                    $field = 'player'.$i;
                 }
         }

         mysql_query("UPDATE tournaments set ".$field." = '".$_SESSION['playerID']."' WHERE id = '".$game2join['id']."'");

         // That was the easy part, now create the games

         if ($field == 'player4')
         {
            create_games($game2join['id']);
         }

         // That was the las one, the tournament CAN start

         if ($game2jojn['gamenum'] == 4)
         {
            // Mail to all players
                 // Problem: All other Group Games (1-3) already started
         }

         // That's it we've joined the tournament
}

?>

<b><?=$MSG_LANG["tournamentsuccess"]?> <a href="tournaments.php?action=view&id=<?=$id?>"><?=$name?></a></b>

<?PHP

break; // case join

case 'crosstable':

$id = $_GET['id'];

$t = mysql_query("SELECT * FROM tournaments WHERE id = '$id'");
$t = mysql_fetch_array($t);

if ($t['players'] == 4)
{
    crosstable($id);
}
else
{
         $b = mysql_query("SELECT * FROM tournaments WHERE big='$id' AND name NOT like '%Finals' ORDER BY id ASC");
         while ($big = mysql_fetch_array($b))
         {
            crosstable($big['id']);
                 echo "<br><br>";
         }
}

break; // case crosstable

case 'alltournaments':

echo "<table style='width: 100%'><tr><th colspan='10'>All Tournaments</th></tr><tr>
<th style='background: url(); background-color:FFFFFF'>ID</th>
<th style='background: url(); background-color:FFFFFF'>Tournament Name</th>
<th style='background: url(); background-color:FFFFFF'>Rating Range</th>
<th style='background: url(); background-color:FFFFFF'>Created By</th>
<th style='background: url(); background-color:FFFFFF'>Created</th>
<th style='background: url(); background-color:FFFFFF'>Winner</th>
<th style='background: url(); background-color:FFFFFF'>Games</th>
</tr>";



    $tw = mysql_query("SELECT id,time,creator,min_rating,max_rating,name,winner,players
                        FROM tournaments
                        WHERE players = 4
                        AND name NOT LIKE '%Group%' and name NOT LIKE '% Finals'
                        ORDER BY id DESC");

    while($dis = mysql_fetch_array($tw)){



        echo "<tr><td>".$dis['id']."<br></td>";
        //echo "<td>".$dis['name']."<br></td>";
        echo "<td><a href='tournaments.php?action=view&id=".$dis['id']."'>".stripslashes($dis['name'])."</a><br></td>";
        echo "<td>".$dis['min_rating']." - ".$dis['max_rating']."<br></td>";

            $crtr = get_userdata($dis['creator']);

        echo "<td>".$crtr['firstName']."<br></td>";

    $datst = date("m.d.y", $dis['time']);

        echo "<td>".$datst."<br></td>";

            $winn = get_userdata($dis['winner']);

        echo "<td>".$winn['firstName']."<br></td>";

        $games = get_games($dis['id']);

        echo "<td>";

            if (count($games) == 0)
            {
                echo "0 / 1";
            }

            else
            {
                $count = 0;

                while (list($key,$val) = each($games))
                {
                    $gs = mysql_query("SELECT gameMessage FROM games WHERE gameID = '$val'");
                    $g = mysql_fetch_array($gs);

                    if ($g['gameMessage'] != '')
                    {
                        $count++;
                    }
                }

                echo $count . " / 12";
            }


        echo "</td></tr>";

}

echo "</table>";

break; // case alltournaments


case 'ranking':

$players = get_ranking();

draw_ranking($players, true);

break; // case ranking

case 'groupranking':

include("groups_functions.php");

$_GET['groups'] = true;

$players = get_ranking();

?>

<table border="0" style="width: 100%">
<tr>
<td><b>Points</b></td>
<td><b>Group</b></td>
<td><b>Chess-AG</b></td>
</tr>

<?PHP

$list = array();
$teams = array();

while(list($key, $value) = each($players))
{

$val = $players[$key];

if ($val['wins'] == "") $val['wins'] = 0;
if ($val['draws'] == "") $val['draws'] = 0;
if ($val['losses'] == "") $val['losses'] = 0;

// Calculate Points: Wins = 2 Points, Draws = 1 Point, Losses = 0 Points

$points = 0;

$points = $points + ($val['wins'] * 2) + $val['draws'];

$list[$key] = $points;

$h = get_group($key);

$teams[$h[0]['group_id']] += $points;

} // while

arsort($teams);

while(list($key, $value) = each($teams))
{

if ($g = mysql_fetch_array(mysql_query("SELECT * FROM groups WHERE group_id = '$key'")))
{

echo "<tr><td>".$value."</td>";
echo '<td><a href="groups.php?action=view&id='.$key.'">'.$g['title'].'</a></td><td>';
echo ($g['ag'] == 1) ? 'Ja' : 'Nein';
echo "</td></tr>";

}  // if

} // while

echo "</table>";

break;

}

?>

<form name="logout" action="mainmenu.php" method="post">
<input type="hidden" name="ToDo" value="Logout">
</form>

</font>
<!-- Please levae Credits -->
<font face=verdana size=1>Tournaments Mod 3.0 (c) 2004 by <a href="http://www.fivedigital.net" target="_blank">FiveDigital</a><br><br>



</font>
<? include("footer3.inc.php");?>
</body>
</html>