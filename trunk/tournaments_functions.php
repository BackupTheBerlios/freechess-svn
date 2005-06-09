<?php
##############################################################################################
#                                                                                            #
#                                tournaments_functions.php
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


// Formatiert einen String
// fuer die DB Eingabe

function db_input($out, $nobr = true) {

 if ($nobr)
 {
         $out = str_replace ("\n", "<br />", $out);
 }

 $out = chop ($out);
 $out = trim ($out);
 $out = addslashes ($out);

 return $out;

}


// Formatiert einen String
// nach der DB Ausgbe

function db_output($out, $nobr = false) {

 $out = stripslashes($out);

 if ($nobr)
 {
         $out = str_replace ("<br />", "\n", $out);
 }

 return $out;

}

// Let's look if we have already joined a tournament

function joined($id, $player, $return = 0)
{

$query = "SELECT * FROM tournaments WHERE id = '$id'";

$t = mysql_query($query);
$t = mysql_fetch_array($t);

if ($t['players'] == 4)
{
    if ($t['player1'] == $player) $return = $t['id'];
         if ($t['player2'] == $player) $return = $t['id'];
         if ($t['player3'] == $player) $return = $t['id'];
         if ($t['player4'] == $player) $return = $t['id'];
}

elseif ($t['players'] == 16)
{
    // Holy shit, we got a BIG tournament
         $query2 = "SELECT * FROM tournaments WHERE big = '$id'";

         $b = mysql_query($query2);
         echo mysql_error();

         while ($big = mysql_fetch_array($b))
         {
                 if (!isset($return)) $return = 0;

                 $return = joined($big['id'], $player, $return);
         } // while
}

return $return;

}

// Get the amount of players already joined a tournament

function get_num($id, $num = 0) {

$t = mysql_query("SELECT * FROM tournaments WHERE id = '$id'");
$t = mysql_fetch_array($t);

if ($t['players'] == 4)
{

if ($t['player1'] != 0) $num++;
if ($t['player2'] != 0) $num++;
if ($t['player3'] != 0) $num++;
if ($t['player4'] != 0) $num++;

}

elseif ($t['players'] == 16)
{
    // Holy shit, we got a BIG tournament

         $b = mysql_query("SELECT * FROM tournaments WHERE big = '$id' AND name NOT LIKE'%Finals'");
         echo mysql_error();

         while ($big = mysql_fetch_array($b))
         {
                 if (!isset($num)) $num = 1;

                 $num += get_num($big['id'], 0);
         } // while
}

return $num;

}

// Fetches the players of a tournament, return an array

function get_players($id, $players = array()) {

$t = mysql_query("SELECT * FROM tournaments WHERE id = '$id'");
$t = mysql_fetch_array($t);

if ($t['players'] == 4)
{
    if ($t['player1'] != 0) $players[] = $t['player1'];
         if ($t['player2'] != 0) $players[] = $t['player2'];
         if ($t['player3'] != 0) $players[] = $t['player3'];
         if ($t['player4'] != 0) $players[] = $t['player4'];
}

elseif ($t['players'] == 16)
{
    // Holy shit, we got a BIG tournament

         $b = mysql_query("SELECT * FROM tournaments WHERE big = '$id' AND name NOT LIKE'%Finals' ORDER BY id ASC");
         echo mysql_error();

         while ($big = mysql_fetch_array($b))
         {
                 if (!isset($players)) $players = array();

                 $players = get_players($big['id'], $players);
         } // while
}

return $players;

}

// Gets the player data, return array

function get_userdata($id) {

 $p = mysql_query("SELECT * FROM {$db_prefix}players WHERE player_id = '$id'");
 $player = mysql_fetch_array($p);

 return $player;

}

// Gets the ranking, either for all or for one tournament

function get_ranking($id = '', $players = array()) {

global $_GET,$db_prefix;

$query = "SELECT g.* FROM {$db_prefix}games g
          LEFT JOIN tournaments t
          ON t.id = g.tournament
          WHERE ";

if ($id == '') {

$query .= "t.access ";

if ($_GET['groups'] == true)    $query .= "<> ''";
else                            $query .= "= ''";

$query .= " AND";

}

$query .= " g.tournament ";

if ($id == '') $query .= "<> 0";

else
{
    $query .= "='$id'";
    $t = mysql_query("SELECT * FROM tournaments WHERE id = '$id'");
    $t = mysql_fetch_array($t);

}

if ($t['players'] == 4 || !$t['players'])
{

$sql = mysql_query($query);
echo mysql_error();

while($row = mysql_fetch_array($sql))
{

$player = $row['whitePlayer'];

       if ($row['gameMessage']== '')
                $players[$player][actual]++;
            else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "white" && $player != $row['whitePlayer'])
                $players[$player][wins]++;
            else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "black" && $player != $row['blackPlayer'])
                $players[$player][wins]++;
            else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "white" && $player != $row['blackPlayer'])
                $players[$player][losses]++;
            else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "black" && $player != $row['whitePlayer'])
                $players[$player][losses]++;
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "white" && $player != $row['whitePlayer'])
                $players[$player][losses]++;
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "black" && $player != $row['blackPlayer'])
                $players[$player][losses]++;
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "white" && $player != $row['blackPlayer'])
                $players[$player][wins]++;
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "black" && $player != $row['whitePlayer'])
                $players[$player][wins]++;
            else if ($row['gameMessage'] == "draw")
               $players[$player][draws]++;

$player = $row['blackPlayer'];

       if ($row['gameMessage']== '')
                $players[$player][actual]++;
            else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "white" && $player != $row['whitePlayer'])
                $players[$player][wins]++;
            else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "black" && $player != $row['blackPlayer'])
                $players[$player][wins]++;
            else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "white" && $player != $row['blackPlayer'])
                $players[$player][losses]++;
            else if ($row['gameMessage'] == "playerResigned" && $row['messageFrom'] == "black" && $player != $row['whitePlayer'])
                $players[$player][losses]++;
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "white" && $player != $row['whitePlayer'])
                $players[$player][losses]++;
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "black" && $player != $row['blackPlayer'])
                $players[$player][losses]++;
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "white" && $player != $row['blackPlayer'])
                $players[$player][wins]++;
            else if ($row['gameMessage'] == "checkMate" && $row['messageFrom'] == "black" && $player != $row['whitePlayer'])
                $players[$player][wins]++;
            else if ($row['gameMessage'] == "draw")
               $players[$player][draws]++;

} // while

} elseif ($t['players'] == 16)
{
    // Holy shit, we got a BIG tournament

         $b = mysql_query("SELECT * FROM tournaments WHERE big = '$id' AND name not like '%Finals'");
         echo mysql_error();

         while ($big = mysql_fetch_array($b))
         {
                 if (!isset($players)) $players = array();

                 $players = get_ranking($big['id'], $players);
         } // while
}

return $players;

}

// Draws the ranking table

function draw_ranking($players, $special=false) {

global $MSG_LANG;

?>

<table border="0">
<tr>
<td><b><?=$MSG_LANG["points"]?></b></td>
<td><b><?=$MSG_LANG["tournamentrankings2"]?></b></td>
<td><b><?=$MSG_LANG["player"]?></b></td>
<?PHP

if ($special)
{
    echo "<td><b>$MSG_LANG[medals]</b></td>";
         echo "<td><b>Stats</b></td>";
}

?>
</tr>

<?PHP

$list = array();

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

} // while

arsort($list);

while(list($key, $value) = each($list))
{

if (!$special || ($value > 0 && $special))

if ($player = mysql_fetch_array(mysql_query("SELECT * FROM {$db_prefix}players WHERE player_id = '$key'")))
{

echo "<tr>
<td>".$value."</td>
<td>".$player['rating']." (".$player['rating_max'].")</td>
<td><a href=\"stats_user.php?cod=".$key."\">".$player['firstName']."</a></td>";

if ($special)
{
         echo "<td>&nbsp;";

         $subrank = "";
         $p2 = mysql_query("select * from medals where playerID='$key' and medal like 'tournament%'");
         while($row2 = mysql_fetch_array($p2))

                 $subrank .= "<img alt='".$row2[medal]."' src='images/rank/$row2[medal].gif'> ";

         echo $subrank;
         echo "</td>";

         $wins4 = 0;
         $wins16 = 0;

         $w2 = mysql_query("SELECT * FROM tournaments WHERE winner = '$key' and name not like '%Group%'");
         while ($w = mysql_fetch_array($w2))
         {
            if ($w['big'] == 0) $wins4++;
                 else           $wins16++;
         }

         echo "<td>Small: $wins4 | Big: $wins16</td>";
}

echo "</tr>";

}  // if

} // while

echo "</table>";

}

// Calculates the winner of a tournament

function calculate_winner($id)
{

$players = get_ranking($id);

$list = array();

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

} // while

arsort($list);

list($key,$val) = each($list);

return $key;

}

// Returns the winner of a tournament

function get_winner($id) {

/*$players = get_ranking($id);

$list = array();

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

} // while

arsort($list);

list($key,$val) = each($list);

return $key;*/

$winner = 0;

$t = mysql_query("SELECT * FROM tournaments WHERE id = '$id'");
$t = mysql_fetch_array($t);

if ($t['players'] == 4) $winner = $t['winner'];

else
{
         $f = mysql_query("SELECT * FROM tournaments WHERE big = '$id' AND name LIKE '% Finals'");
    $f = mysql_fetch_array($f);

         $winner = $f['winner'];
}

return $winner;

}

// Checks, if a tournament has alreadys started

function started($id) {

$t = mysql_query("SELECT * FROM tournaments WHERE id = '$id'");
$t = mysql_fetch_array($t);

$num = get_num($id);

if ($num == $t['players'])
{
    $started = true;
}
else
{
    $started = false;
}

return $started;

}

// Creates the game tables for a tournament

function create_games($id) {

global $_SESSION;

$t = mysql_query("SELECT * FROM tournaments WHERE id = '$id'");
$t = mysql_fetch_array($t);

if ($t['players'] == 4)
{

$games = array(
          1 => array('whitePlayer' => $t['player1'], 'blackPlayer' => $t['player2']),
          2 => array('whitePlayer' => $t['player1'], 'blackPlayer' => $t['player3']),
          3 => array('whitePlayer' => $t['player1'], 'blackPlayer' => $t['player4']),

          4 => array('whitePlayer' => $t['player2'], 'blackPlayer' => $t['player1']),
          5 => array('whitePlayer' => $t['player2'], 'blackPlayer' => $t['player3']),
          6 => array('whitePlayer' => $t['player2'], 'blackPlayer' => $t['player4']),

          7 => array('whitePlayer' => $t['player3'], 'blackPlayer' => $t['player1']),
          8 => array('whitePlayer' => $t['player3'], 'blackPlayer' => $t['player2']),
          9 => array('whitePlayer' => $t['player3'], 'blackPlayer' => $t['player4']),

         10 => array('whitePlayer' => $t['player4'], 'blackPlayer' => $t['player1']),
         11 => array('whitePlayer' => $t['player4'], 'blackPlayer' => $t['player2']),
         12 => array('whitePlayer' => $t['player4'], 'blackPlayer' => $t['player3']));

         while (list($key, $val) = each($games))
         {

                 $query = "INSERT INTO {$db_prefix}games (timelimit, gameMessage,messageFrom, whitePlayer, blackPlayer, dateCreated,
                 lastMove, ratingWhite, ratingBlack,ratingWhiteM,ratingBlackM,PVBlack,PVWhite,tournament,oficial,thematic)
                 VALUES (";

                 $white = $games[$key]['whitePlayer'];
        $black = $games[$key]['blackPlayer'];

                 $query .= "".$t['days'].", '', '', $white, $black, NOW(), NOW(),".getRating($white).",".getRating($black).",".getRatingMonth($white).",".getRatingMonth($black).",".getPV($black).",".getPV($white).", '$id', '".$t['official']."','".$t['thematic']."')";

                 mysql_query($query);

                 echo mysql_error();

         } // while

         $query = mysql_query("SELECT game_id FROM {$db_prefix}games WHERE tournament = '$id'");

    while ($g = mysql_fetch_array($query))
    {
                 if (!function_exists('createNewGame'))
                 {
                    include 'newgame.php';
                 }

                 $_SESSION['gameID'] = $g['gameID'];
        createNewGame($_SESSION['gameID']);
        saveGame();

         } // while

} // 4 players

}

// Show a Game an it's status in the corsstable

function show_game($nr, $games)
{

global $MSG_LANG;


if ($games[$nr]['gameMessage'] == 'draw')
{
    $status = $MSG_LANG["draw"];
}

elseif ($games[$nr]['winner'])
{
    $winner = mysql_fetch_array(mysql_query("SELECT * FROM {$db_prefix}players WHERE player_id = '".$games[$nr]['winner']."'"));

         $status = $winner['firstName'] . " won";
}

elseif ($games[$nr]['gameMessage'] == "playerResigned")
{
    if ($games[$nr]['messageFrom'] == "black")
         {
            $winner_id = $games[$nr]['blackPlayer'];
         }
         else
         {
            $winner_id = $games[$nr]['whitePlayer'];
         }

         $winner = mysql_fetch_array(mysql_query("SELECT * FROM {$db_prefix}players WHERE player_id = '$winner_id'"));

         $status = $winner['firstName'] . " resigned";

}

elseif($games[$nr]['gameMessage'] == 'theflaghasfallen')
{
    $status = $MSG_LANG["theflaghasfallen"];
}

elseif($games[$nr]['gameMessage'] == 'checkMate')
{
    $status = $MSG_LANG["checkmate"];
}

else
{
    $status = $MSG_LANG["stillplaying"];
}

echo "$MSG_LANG[stillplayinggame] <a href=\"chess.php?gameID=" . $games[$nr]['gameID'] . "\">".$games[$nr]['gameID']."</a><br><br>" . $status;

}

// Show the Crosstable of a tournament

function crosstable($id) {

global $MSG_LANG;

$t = mysql_fetch_array(mysql_query("SELECT * FROM tournaments WHERE id = '$id'"));

$name = db_output($t['name']);
$date = date("m.d.y", $t['time']);

$i = 1;

$query = mysql_query("SELECT * FROM games WHERE tournament = '$id' ORDER BY gameID ASC");

while ($g = mysql_fetch_array($query))
{

$games[$i] = $g;

$i++;

} // while

$p1 = mysql_fetch_array(mysql_query("SELECT * FROM {$db_prefix}players WHERE player_id = '".$t['player1']."'"));
$p2 = mysql_fetch_array(mysql_query("SELECT * FROM {$db_prefix}players WHERE player_id = '".$t['player2']."'"));
$p3 = mysql_fetch_array(mysql_query("SELECT * FROM {$db_prefix}players WHERE player_id = '".$t['player3']."'"));
$p4 = mysql_fetch_array(mysql_query("SELECT * FROM {$db_prefix}players WHERE player_id = '".$t['player4']."'"));

//print_r($games);

?>

<table border="1" style="width: 100%">
<tr>
<td>
<h1><?=$name?></h1><br>
<?=$MSG_LANG["start"]?>: <?=$date?>
<br>
<br>

<table width="500">

<tr>
<td width="100" height="100">&nbsp;</td>
<td width="100" height="100"><b><?PHP echo "<a href=\"stats_user.php?cod=" . $p1['playerID'] . "\">" . $p1['firstName'] . "</a>"; ?></b></td>
<td width="100" height="100"><b><?PHP echo "<a href=\"stats_user.php?cod=" . $p2['playerID'] . "\">" . $p2['firstName'] . "</a>"; ?></b></td>
<td width="100" height="100"><b><?PHP echo "<a href=\"stats_user.php?cod=" . $p3['playerID'] . "\">" . $p3['firstName'] . "</a>"; ?></b></td>
<td width="100" height="100"><b><?PHP echo "<a href=\"stats_user.php?cod=" . $p4['playerID'] . "\">" . $p4['firstName'] . "</a>"; ?></b></td>
</tr>

<tr>
<td width="100" height="100"><b><?PHP echo "<a href=\"stats_user.php?cod=" . $p1['playerID'] . "\">" . $p1['firstName'] . "</a>"; ?></b></td>
<td width="100" height="100">&nbsp;</td>
<td width="100" height="100"><b><?PHP show_game(4, $games); ?></b></td>
<td width="100" height="100"><b><?PHP show_game(7, $games); ?></b></td>
<td width="100" height="100"><b><?PHP show_game(10, $games); ?></b></td>
</tr>

<tr>
<td width="100" height="100"><b><?PHP echo "<a href=\"stats_user.php?cod=" . $p2['playerID'] . "\">" . $p2['firstName'] . "</a>"; ?></b></td>
<td width="100" height="100"><b><?PHP show_game(1, $games); ?></b></td>
<td width="100" height="100">&nbsp;</td>
<td width="100" height="100"><b><?PHP show_game(8, $games); ?></b></td>
<td width="100" height="100"><b><?PHP show_game(11, $games); ?></b></td>
</tr>

<tr>
<td width="100" height="100"><b><?PHP echo "<a href=\"stats_user.php?cod=" . $p3['playerID'] . "\">" . $p3['firstName'] . "</a>"; ?></b></td>
<td width="100" height="100"><b><?PHP show_game(2, $games); ?></b></td>
<td width="100" height="100"><b><?PHP show_game(5, $games); ?></b></td>
<td width="100" height="100">&nbsp;</td>
<td width="100" height="100"><b><?PHP show_game(12, $games); ?></b></td>
</tr>

<tr>
<td width="100" height="100"><b><?PHP echo "<a href=\"stats_user.php?cod=" . $p4['playerID'] . "\">" . $p4['firstName'] . "</a>"; ?></b></td>
<td width="100" height="100"><b><?PHP show_game(3, $games); ?></b></td>
<td width="100" height="100"><b><?PHP show_game(6, $games); ?></b></td>
<td width="100" height="100"><b><?PHP show_game(9, $games); ?></b></td>
<td width="100" height="100">&nbsp;</td>
</tr>

</table>

</td>
</tr>
</table>

<?PHP

}

// Give Tournament medal

function giveMedalt($medal,$player,$name){
    $date = date("Y-m-d");
    $p2 = mysql_query("select * from medals where playerID='$player' AND medal='$medal'");
        if (mysql_num_rows($p2) == 0){
        mysql_query("insert into medals (playerID,date,medal) values ('$player','$date','$medal')");
        }
}


// Run after a game is eneded

function save_tournament($id, $winner="") {

//echo get_winner($id); exit;

// Holy shiat :D

$query = "SELECT * FROM {$db_prefix}games WHERE game_id = '$id'";
$sql = mysql_query($query);
$g = mysql_fetch_array($sql);

if (!$g['tournament'])
{
         //echo "// Game not part of a tournament";
         return false;
}

else
{
    // Okay, we have a tournament Game, get the data
         $t = mysql_query("SELECT * FROM tournaments WHERE id = '".$g['tournament']."'");
    $t = mysql_fetch_array($t);

         if ($t['big'] == 0)
         {
                 // Small tournament, no danger
                 $lastgame = true;
                 $gs = mysql_query("SELECT * FROM {$db_prefix}games WHERE tournament = '".$t['id']."'");
                 while ($game = mysql_fetch_array($gs))
                 {
                    if ($game['gameMessage'] == '') $lastgame = false;
                 }

                 if ($lastgame)
                 {
                         $twinner = calculate_winner($t['id']);
                         mysql_query("UPDATE tournaments SET winner = '$twinner' WHERE id = '".$t['id']."'");
                         giveMedalt('tournament4',$twinner,'');
                 }
         }
         else
         {
            // Oh my god - we have a BIG tournament
                 // first, we have to see, if this game IS the LAST game of this group

                 //echo "big tournament ".$t['big'];

                 // Get the finalgame

                 $query = "SELECT id FROM tournaments WHERE big='".$t['big']."' AND name like '%Finals'";
            $finalgame = mysql_fetch_array(mysql_query($query));
                 $finalgame = $finalgame['id'];

                 $lastgame = true;

                 $gs = mysql_query("SELECT * FROM {$db_prefix}games WHERE tournament = '".$t['id']."'");
                 while ($game = mysql_fetch_array($gs))
                 {
                    if ($game['gameMessage'] == '') $lastgame = false;
                 }

                 if (($lastgame == true) && ($t['id'] != $finalgame))
                 {
                    // It was the lastgame, now we want to have the group winner
                         //echo "lastgame<br>";
                         // Put the winner in the finals group
                         // see if we ware fourth player
                         // start finals

                         // Okay, lets get the 4 Group Games

                $game2join = '';
                         $query = "SELECT * FROM tournaments WHERE big='".$t['big']."' AND name like '%Finals'";
                         //echo "<br>";
                $b = mysql_query($query);
                $game2join = mysql_fetch_array($b);
                         //echo mysql_error();
                         //echo "join finals " . $game2join['id'] . "<br>";
                // Now we got the game 2 join, yeah, now it's gettin' way easier

                $field = '';

                for ($i = 1; $i < 5; $i++)
                {       echo "checking " . $game2join['player'.$i] . "<br>";
                    if ($game2join['player'.$i] == 0 && $field == '')
                        {
                            $field = 'player'.$i; echo $field."<br>";
                        }
            }

                         //echo "Join Finals as " . $field . "<br>";

                         // Now let's get the winner
                         $twinner = calculate_winner($t['id']);
                         //echo "Winner ".$twinner."<br>";

                         mysql_query("UPDATE tournaments SET winner = '$twinner' WHERE id = '".$t['id']."'");
                mysql_query("UPDATE tournaments set ".$field." = '".$twinner."' WHERE id = '".$game2join['id']."'");

                // That was the easy part, now create the games

                if ($field == 'player4')
                {
                                 echo "Create games<br>";
                                 create_games($game2join['id']);
                                 send_mails($game2join['id']);
                }

                         if ($twinner == $_SESSION['playerID'])
                         {
                            // I am the Group winner and am going to play in the finals :D
                                 //mail()
                         }
                 }

                 elseif (($lastgame == true) && ($t['id'] == $finalgame))
                 {
                    $twinner = calculate_winner($t['id']);
                         mysql_query("UPDATE tournaments SET winner = '$twinner' WHERE id = '".$t['id']."'");
                         giveMedalt('tournament16',$twinner,'');
                 }
         }


}

}

// Sends e-mails to all Players of a tournament

function send_mails($id, $text="")
{
         global $CFG_MAILADDRESS;

         $players = get_players($id);

         $query = "SELECT * FROM tournaments WHERE id = '$id'";

    $t = mysql_query($query);
    $t = mysql_fetch_array($t);

         while (list($key, $vak) = each($players))
         {
            $row = get_userdata($val);

                 $mailsubject = "You hve entered the Finals";

                 $mailmsg = "Hello ". $row[firstName].",

                 you have entered the Finals of ". $t['name'] .".";

                 mail("$row[firstName] <$row[email]>",$mailsubject,$mailmsg,"From: $CFG_MAILADDRESS");
         }

         return;
}

// Checks if a user is allowed to create / join a tournament

function check_banned($id) {

global $t_banned_users;

 if (in_array($id, $t_banned_users))
 {
    echo "<b>You are not allowed to create/join tournaments</b>";
         exit;
 }

 else
 {
    return;
 }

}

// Gets the games of a tournament

function get_games($id = '', $games = array()) {

$query = "SELECT * FROM {$db_prefix}games WHERE tournament ";

if ($id == '') $query .= "<> 0";

else
{
    $query .= "='$id'";
    $t = mysql_query("SELECT * FROM tournaments WHERE id = '$id'");
    $t = mysql_fetch_array($t);

}

if ($t['players'] == 4 || !$t['players'])
{

$sql = mysql_query($query);

while($row = mysql_fetch_array($sql))
{

    $games[] = $row['gameID'];

} // while

} elseif ($t['players'] == 16)
{
    // Holy shit, we got a BIG tournament

         $b = mysql_query("SELECT * FROM tournaments WHERE big = '$id' AND name not like '%Finals'");
         echo mysql_error();

         while ($big = mysql_fetch_array($b))
         {
                 if (!isset($games)) $games = array();

                 $players = get_games($big['id'], $games);
         } // while
}

return $games;

}

//tournament stuff removed from chess.php- we'll sort through it later- removing tournaments and thematics form the code
//we can add it back later as configurable modules, but such code should be separate.
if(0)
{
if ($row['tournament'] != 0 AND $row['thematic'] == 0)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>
        <input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td>&nbsp;</td></tr>
<?
}


elseif ($row['tournament'] != 0 AND $row['thematic'] == 1)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[alekhine_2]?></font></td></tr></table></td><td></td></tr>

<?
}


elseif ($row['tournament'] != 0 AND $row['thematic'] == 2)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[birds_2]?></font></td></tr></table></td><td></td></tr>

<?
}


elseif ($row['tournament'] != 0 AND $row['thematic'] == 3)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[budapest_2]?></font></td></tr></table></td><td></td></tr>


<?
}


elseif ($row['tournament'] != 0 AND $row['thematic'] == 4)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[catalan_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 5)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[carokann_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 6)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[cochranegambit_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 7)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[dutchdefense_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 8)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[leningraddutch_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 9)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[fourknights_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 10)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[frenchdefense_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 11)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[frenchadvance_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 12)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[frenchclassical_2]?></font></td></tr></table></td><td></td></tr>



<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 13)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[frenchrubinstein_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 14)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[frenchwinawer_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 15)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[frenchtarrasch_2]?></font></td></tr></table></td><td></td></tr>



<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 16)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[grob_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 17)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[grunfeld_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 18)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[kingsgambit_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 19)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[kingsindian_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 20)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>



<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[italiangame_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 21)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[larsens_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 22)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[modernbenoni_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 23)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[muziogambit_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 24)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[nimzoindian_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 25)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[petroff_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 26)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[philidor_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 27)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[queensgambit1_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 28)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[queensgambit2_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 29)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[queensindian_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 30)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[ruylopez_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 31)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[scandinavian_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 32)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[scotch_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 33)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[sicilian_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 34)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[sicilianalapin_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 35)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[sicilianclosed_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 36)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[siciliansveshnikov_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 37)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

    <input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[siciliansimagin_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 38)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[sicilianpaulsen_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 39)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>
        <input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[sicilianrichterrauzer_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 40)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[siciliandragon_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 41)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[sicilianscheveningen_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 42)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[siciliansozin_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 43)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[siciliannajdorf_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 44)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[slav_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 45)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[sokolsky1_2]?></font></td></tr></table></td><td></td></tr>

<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 46)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>


<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[tarrasch_2]?></font></td></tr></table></td><td></td></tr>


<?
}

elseif ($row['tournament'] != 0 AND $row['thematic'] == 47)
    {
        echo "<tr><td align=center><font face=verdana>";
        $t = mysql_fetch_array(mysql_query("SELECT id,name FROM tournaments WHERE id = '".$row['tournament']."'"));
        $t['name'] = stripslashes($t['name']);
        ?>

<input type="button" name="btnTournament" value="<?=$t['name']?>" onClick="window.open('tournaments.php?action=view&id=<?=$t['id']?>', '_self')">
        </td><td></td></tr><tr><td align=center><table width='40%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'><?=$MSG_LANG[thematic]?>: <?=$MSG_LANG[vienna_2]?></font></td></tr></table></td><td></td></tr>


<?
}


/*
elseif($row['blitz'] == 1)//this doesnt even exist in the tables as yet
{

echo "<td align=center><table width='40%' border='0'><tr><td align='center'><input type='button' name='blitz' value='$MSG_LANG[livegame]' onClick=\"window.open('invite_blitz.php', '_self')\"></td></tr></table>";


}
*/

elseif($row['thematic'] == 1){

                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[alekhine_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 2){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[birds_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 3){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[budapest_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 4){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[catalan_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 5){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[carokann_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 6){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[cochranegambit_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 7){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[dutchdefense_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 8){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[leningraddutch_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 9){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[fourknights_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 10){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[frenchdefense_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 11){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[frenchadvance_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 12){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[frenchclassical_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 13){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[frenchrubinstein_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 14){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[frenchwinawer_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 15){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[frenchtarrasch_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 16){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[grob_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 17){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[grunfeld_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 18){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[kingsgambit_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 19){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[kingsindian_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 20){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[italiangame_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 21){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[larsens_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 22){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[modernbenoni_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 23){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[muziogambit_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 24){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[nimzoindian_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 25){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[petroff_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 26){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[philidor_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 27){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[queensgambit1_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 28){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[queensgambit2_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 29){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[queensindian_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 30){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[ruylopez_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 31){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[scandinavian_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 32){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[scotch_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 33){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[sicilian_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 34){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[sicilianalapin_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 35){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[sicilianclosed_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 36){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[siciliansveshnikov_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 37){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[siciliansimagin_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 38){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[sicilianpaulsen_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 39){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[sicilianrichterrauzer_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 40){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[siciliandragon_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 41){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[sicilianscheveningen_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 42){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[siciliansozin_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 43){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[siciliannajdorf_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 44){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[slav_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 45){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[sokolsky1_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 46){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[tarrasch_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 47){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[vienna_2]</font></td></tr></table>\n";
                                }elseif ($row['thematic'] == 48){
                                        echo "<td align=center><table width='50%' border='0'><tr><td align='center' width bgcolor=#000000><font face=verdana> <font color='FFFFFF'>$MSG_LANG[thematic2]: $MSG_LANG[volgagambit_2]</font></td></tr></table>\n";



                                }

else
{}
}
if(0)//This is some of the forums stuff - also removing forums - we'll work on integrating something like simpleboard perhaps..
{
    if(0)
    {

$g1 = mysql_query("SELECT topic_id FROM forum_topics WHERE replyto = 0 AND gameid = '".$_SESSION['game_id']."'");
$g = mysql_fetch_array($g1);

$t1 = mysql_query("SELECT t.*, p.firstName, p.player_id, p.lastUpdate from forum_topics t
                    LEFT JOIN players p ON p.player_id = t.userid
                    WHERE t.topic_id = '".$g['topic_id']."'
                    OR t.replyto = '".$g['topic_id']."'
                    ORDER BY time DESC
                    LIMIT ".$replies_perpage);
//echo mysql_error();
while ($t = mysql_fetch_array($t1)) {

$title = db_output($t['title']);
$title = strip_tags($title);
$text = db_output($t['text'], true);
$text = strip_tags($text, "<br>");
$text = bbcode($text);
$text = forum_smilies($text);

$date = date("d.m.y, H:i", $t['time']);



?>

<table>
<tr>
<tr>
<td width="100" valign="top">

<b><?=$t['firstName']?></b>,<br>
<?=$date?>

</td>

<td valign="top"><div width="100%" align="left"><?=$text?></div></td>
</tr>
</table>
<hr>

<?PHP

} // while

?>
<input type="button" class="BOTOES" value="Reply" onClick="location.href='forum.php?action=newtopic&replyto=<?=$g['topic_id']?>';">
&nbsp;&nbsp;
<input type="button" class="BOTOES" value="View Topic" onClick="location.href='forum.php?action=viewtopic&id=<?=$g['topic_id']?>';">
&nbsp;&nbsp;
<input type="button" class="BOTOES" value="Chess Forums" onClick="location.href='forum.php?action=viewforum&id=<?=$spiele_forum?>';">
<?PHP

}
else
{
    //passthru
}

}
 if(0)    //Thematics shit
{
if ($tmpGame[thematic] == 1){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[alekhine]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 2){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[birds]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 3){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[budapest]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 4){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[catalan]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 5){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[carokann]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 6){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[cochranegambit]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 7){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[dutchdefense]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 8){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[leningraddutch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 9){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[fourknights]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 10){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[frenchdefense]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 11){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[frenchadvance]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 12){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[frenchclassical]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 13){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[frenchrubinstein]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 14){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[frenchwinawer]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 15){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[frenchtarrasch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 16){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[grob]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 17){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[grunfeld]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 18){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[kingsgambit]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 19){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[kingsindian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 20){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[italiangame]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 21){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[larsens]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 22){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[modernbenoni]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 23){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[muziogambit]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 24){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[nimzoindian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 25){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[petroff]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 26){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[philidor]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 27){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[queensgambit1]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 28){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[queensgambit2]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 29){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[queensindian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 30){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[ruylopez]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 31){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[scandinavian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 32){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[scotch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 33){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[sicilian]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 34){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[sicilianalapin]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 35){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[sicilianclosed]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 36){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[siciliansveshnikov]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 37){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[siciliansimagin]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 38){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[sicilianpaulsen]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 39){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[sicilianrichterrauzer]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 40){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[siciliandragon]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 41){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[sicilianscheveningen]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 42){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[siciliansozin]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 43){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[siciliannajdorf]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 44){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[slav]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 45){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[sokolsky1]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 46){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[tarrasch]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 47){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[vienna]</font></td>\n";
                                }elseif ($tmpGame[thematic] == 48){
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>$MSG_LANG[volgagambit]</font></td>\n";
                                }else{
                                        echo "<td align='center' bgcolor=#FFFFFF><font color='800000'>No</font></td>\n";
                                }
 }
      if(0) //team stuff- might be useful, but probably junk
     {
        $p1 = mysql_query("SELECT * FROM team_members,team WHERE fk_team=teamID AND fk_player='$_SESSION[player_id]'");
        $row1 = mysql_fetch_array($p1);
        $myteam = $row1['fk_team'];
        $team_membership = $row1['level'];

        $p2 = mysql_query("SELECT * FROM team_members,players WHERE fk_player=player_id AND fk_team='$myteam' AND level = 0");
        if (mysql_num_rows($p2)>0 && $team_membership >= 100)
        {
            echo "<table border='0' width='300' bgcolor=black cellspacing=1 cellpading=1>";
            echo "<tr><th colspan=3 bgcolor=beige>".$MSG_LANG["membershippending"]."</th></tr>";
            echo "<tr><th bgcolor=white>".$MSG_LANG['name']."</th><th bgcolor=white>".$MSG_LANG['rating']."/".$MSG_LANG['max']."</th><th bgcolor=white>&nbsp;</th></tr>";

            while ($row2 = mysql_fetch_array($p2)){
                echo "<tr><td bgcolor=white><a href='stats_user.php?cod=$row2[player_id]'>$row2[firstName]</a></td><td bgcolor=white>$row2[rating]/$row2[rating_max]</td><td bgcolor=white><input onClick=\"window.location='teams.php?action=acceptuser&playerid=$row2[player_id]&teamid=$myteam'\" style='font-size:11' type=button value='".$MSG_LANG['accept']."'>&nbsp;<input onClick=\"window.location='teams.php?action=rejectuser&playerid=$row2[player_id]&teamid=$myteam'\" style='font-size:11' type=button value='".$MSG_LANG['reject']."'></td></tr>";
            }
            echo "<tr><td colspan=3 bgcolor=white>&nbsp;</td></tr>";
            echo "</table>";
        }
        }

//Training Module shit
  /* if(0)//if training moidule enabled - TODO move this out and work it as a module hook!
     {
      $g1 = mysql_query("SELECT * FROM {$db_prefix}games WHERE game_id='".$_SESSION['game_id']."'");
        $g = mysql_fetch_array($g1);

        $p1 = mysql_query("SELECT p.*, t.moves_done, t.id AS trainings_id FROM training_games t
                                      LEFT JOIN game_positions p
                                    ON p.id = t.id
                                    WHERE t.game_id = '".$_SESSION['game_id']."'
                                    AND t.player_id = '".$_SESSION['player_id']."' ");
        echo mysql_error();
        $p = mysql_fetch_array($p1);

        $game_id = $_SESSION['game_id'];

        if (($g['white_player'] == 1) || ($g['black_player'] == 1))
        {
            if ($p['moves'] == 3)
            {
                $str = $_SESSION['game_id'];

                if (($_POST['from_row'] != $p['from_row']) ||
                   ($_POST['from_col'] != $p['from_col']) ||
                   ($_POST['to_row'] != $p['to_row']) ||
                   ($_POST['to_col'] != $p['to_col']))
                {
                    $str .= '|0';
                }

                else
                {
                    $str .= '|1';
                }

                $str = base64_encode($str);

                    mysql_query("DELETE FROM {$db_prefix}games WHERE game_id = '$game_id'");
                  mysql_query("DELETE FROM pieces WHERE game_id = '$game_id'");
                   mysql_query("DELETE FROM history WHERE game_id = '$game_id'");

                    header("Location: matt.php?action=result&code=".$str);

            }

            elseif ($p['moves'] == 1)
            {
                $player = ($g['white_player'] == 1) ? 'black' : 'white';

                $str = $_SESSION['game_id'] . '|';

                if (($g['status'] == 'checkMate') && ($g['messageFrom'] == $player))
                {
                    $str .= '1';
                   }
                   else
                  {
                      $str .= '0';
                 }

                    $str = base64_encode($str);

                    mysql_query("DELETE FROM {$db_prefix}games WHERE game_id = '$game_id'");
                  mysql_query("DELETE FROM pieces WHERE game_id = '$game_id'");
                   mysql_query("DELETE FROM history WHERE game_id = '$game_id'");

                    header("Location: matt.php?action=result&code=".$str);
            }

            else
            {
                //echo "<br>";
                //echo $_POST['from_row']."&nbsp;".$p['from_row']."<br>";
                //echo $_POST['from_col']."&nbsp;".$p['from_col']."<br>";
                //echo $_POST['to_row']."&nbsp;".$p['to_row']."<br>";
               // echo $_POST['to_col']."&nbsp;".$p['to_col']."<br>";
                //exit;

                $moves = $p['moves_done'];

                if ($moves == 0)
                {

                    if (($_POST['from_row'] != $p['from_row']) ||
                        ($_POST['from_col'] != $p['from_col']) ||
                        ($_POST['to_row'] != $p['to_row']) ||
                        ($_POST['to_col'] != $p['to_col']))
                    {
                         $str = $_SESSION['game_id'] . '|0';
                        $str = base64_encode($str);

                            mysql_query("DELETE FROM {$db_prefix}games WHERE game_id = '$game_id'");
                          mysql_query("DELETE FROM pieces WHERE game_id = '$game_id'");
                           mysql_query("DELETE FROM history WHERE game_id = '$game_id'");

                            header("Location: matt.php?action=result&code=".$str);
                    }

                    else
                    {
                           // Move Valid :D
                        mysql_query("DELETE FROM pieces WHERE game_id = '$game_id'");
                           mysql_query("DELETE FROM history WHERE game_id = '$game_id'");
                        mysql_query("DELETE FROM {$db_prefix}games WHERE game_id = '$game_id'");

                        // Get the new EPD Position

                        $m1 = mysql_query("SELECT epd, epd2, zug FROM game_positions_results WHERE pos_id = '".$p['id']."' ORDER BY rand(".rand().")");
                        echo mysql_error();
                        $m = mysql_fetch_array($m1);

                        $white_player = $g['white_player'];
                        $black_player = $g['black_player'];
                        $EPD = $m['epd'];
                        $EPD2 = (!empty($m['epd2'])) ? $m['epd2'] : $m['epd'];

                        $tmpQuery = "INSERT INTO games(white_player, black_player, dateCreated, lastMove, status, messageFrom, rated)
                                     VALUES ('".$white_player."','".$black_player."', now(), now(), '', 'white', 0)";
                        mysql_query($tmpQuery);
                        echo mysql_error();
                        // Get the ID of the game we just inserted..
                        $insertedGameId = mysql_insert_id();

                        mysql_query("UPDATE training_games SET moves_done = 1, game_id = '$insertedGameId '
                                     WHERE id = '".$p['trainings_id']."'");
                        echo mysql_error();

                        $sq = EPD2Board($EPD);

                        insertPieces($sq, $insertedGameId);

                        $tomove = EPDCurColor($EPD);
                        if($tomove == 'black')
                        {
                               $tmpQuery = "INSERT INTO history (timeOfMove, game_id, curPiece, cur_color, from_row, from_col, to_row, to_col, replaced, promotedTo, is_in_check)
                                                       VALUES   (Now(), '".$insertedGameId ."', 'king', '$tomove', '0',     '0',  '0',   '0',     null,       null, '0')";
                                mysql_query($tmpQuery);
                           }

                        //header("Location: chess.php?game_id=".$insertedGameId);
                        ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <style type="text/css">
    <!--
    body {
        font-family: verdana, arial, helvetica, sans-serif;
        font-size: small;
        color: #000;
        background-color: #fff;
    }
    -->
    </style>
    <title>WebChess :: Insert a new game</title>
    <script language="JavaScript" type="text/javascript" src="javascript/chessutils.js"></script>
    <script language="JavaScript" type="text/javascript" src="javascript/EPDutils.js"></script>
    <script type="text/javascript"><!--
        //window.onload = function () {
        //    getObject('displaypos').onclick = function(){EPDToBoard(getObject('EPD').value);};
       //    }

       function weiter() {

           newEPD = "<?=$EPD?>";
        EPDToBoard(newEPD);
       }

       setTimeout("weiter()","5000");
    //-->
    </script>
<?PHP
$url = 'chess.php?game_id='.$insertedGameId;
echo '<meta http-equiv="Refresh" content="10;url='.$url.'">'; ?>
</head>

<body>
<div align="center">




</div>

<br><br>

<?PHP displayBoard(EPD2Board($EPD2));  ?>

<br><br>



</body>
</html>

<?PHP

                        exit;

                    } // move valid
                } // moves == 0

                elseif ($moves == 1)
                {

                    $player = ($g['white_player'] == 1) ? 'black' : 'white';

                    $str = $_SESSION['game_id'] . '|';

                    if (($g['status'] == 'checkMate') && ($g['messageFrom'] == $player))
                    {
                        $str .= '1';
                       }
                       else
                      {
                          $str .= '0';
                     }

                        $str = base64_encode($str);

                        mysql_query("DELETE FROM {$db_prefix}games WHERE game_id = '$game_id'");
                      mysql_query("DELETE FROM pieces WHERE game_id = '$game_id'");
                       mysql_query("DELETE FROM history WHERE game_id = '$game_id'");

                        header("Location: matt.php?action=result&code=".$str);

                } // moves == 1

            } // 1 / 2 moves game
        } // training?
        }//end if(0) - for allow training module   */
?>