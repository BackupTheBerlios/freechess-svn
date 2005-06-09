<?php
##############################################################################################
#                                                                                            #
#                                gui.php
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
/* functions for outputting to html and javascript */

$smilie = array(
"/" . preg_quote(":b)", "/") . "/",
"/" . preg_quote(":)", "/") . "/",
"/" . preg_quote(":(", "/") . "/",
"/" . preg_quote(";)", "/") . "/",
"/" . preg_quote(":P", "/") . "/",
"/" . preg_quote(":e", "/") . "/",
"/" . preg_quote("8)", "/") . "/",
"/" . preg_quote(":bad:", "/") . "/",
"/" . preg_quote(":good:", "/") . "/"
);

$img = array (
'<img src="images/smilies/space.gif" border="0" width="3" height="0"><img src="images/smilies/biggrin.gif" border="0">',
'<img src="images/smilies/space.gif" border="0" width="3" height="0"><img src="images/smilies/smiley.gif" border="0">',
'<img src="images/smilies/space.gif" border="0" width="3" height="0"><img src="images/smilies/sad.gif" border="0">',
'<img src="images/smilies/space.gif" border="0" width="3" height="0"><img src="images/smilies/wink.gif" border="0">',
'<img src="images/smilies/space.gif" border="0" width="3" height="0"><img src="images/smilies/tongue.gif" border="0">',
'<img src="images/smilies/space.gif" border="0" width="3" height="0"><img src="images/smilies/eek.gif" border="0">',
'<img src="images/smilies/space.gif" border="0" width="3" height="0"><img src="images/smilies/cool.gif" border="0">',
'<img src="images/smilies/space.gif" border="0" width="3" height="0"><img src="images/smilies/bad.gif" border="0">',
'<img src="images/smilies/space.gif" border="0" width="3" height="0"><img src="images/smilies/good.gif" border="0">',
);


function show_avatar($avatar) {
    global  $extensions, $def_avatar, $AVATAR_LOCATION;
    if ($AVATAR_LOCATION == "directory"){
        $img = $def_avatar;
        $root = dirname(__FILE__);
        while (list($key, $val) = each($extensions)) {
            $imgpath = $root . '/images/avatars/' . $avatar . '.' . $val;
            if (file_exists($imgpath)) {
                $img = $avatar . '.' . $val;
            }
        }
        echo '<img src="images/avatars/' . $img . '">';
    }
    else{
        echo '<img src="show_avatar.php?id=' . $avatar . '">';
    }
}


function smilies($str) {

    global $smilie, $img;

    $str = preg_replace($smilie, $img, $str);

    return $str;
}

function smilielist() {

    global $smilie, $img;

    $list = '';

    for ($i = 0; $i < count($smilie); $i++) {
        $smile = ereg_replace("/", "", $smilie[$i]);
        $list .= '<a href="#" onclick="chess.chat_msg.value+= \'' . $smile . '\'">' . $img[$i] . '</a>';
    } // for

    return $list;

}


function riphtml ($out) {

    $out=str_replace ("<", "&lt;", $out);
    $out=str_replace (">", "&gt;", $out);

    return $out;
}



function writepublicChat($gameID){

        global $_SESSION, $MSG_LANG,$db,$db_prefix;

        $p = mysql_query("SELECT p.*, c.*, UNIX_TIMESTAMP(c.stamp) AS stamp from {$db_prefix}chat c
                               LEFT JOIN {$db_prefix}players p ON p.player_id = c.from_id
                          order BY c.stamp DESC LIMIT 15");
        $chat = "";

        while($row = mysql_fetch_array($p))
        {
            if ($_SESSION['playerID'] == $row['from_id'])
                $cor="#0000FF";
            else
                $cor="brown";

            $zeit = $row['stamp'];
            $msg = strip_tags($row['message'], '<a><b><i><u>');
            $msg = stripslashes($msg);
            $msg = smilies($msg);

            if ($row['from_id'] == 0) {

                $row['username'] = "Chat";

                 $msg = str_replace("event-enter", $MSG_LANG["entersthechat"], $msg);
                 $msg = str_replace("event-leave", $MSG_LANG["leavesthechat"], $msg);
                 $msg = "<b>" . $msg . "</b>";
            }
            $chat .= "<font size=-10><font color=$cor><B>$row[username]</B>:</font><font color=green><br> " . $msg . "</font> <i><font size=-10><font color=silver>" . $zeit . "</font></font></i><BR>";
        }
       return $chat;
}

    function writeChat($game_id){
        global $_SESSION,$db,$db_prefix;
        $sql = "SELECT * from {$db_prefix}chat,{$db_prefix}players where {$db_prefix}players.player_id={$db_prefix}chat.from_id AND {$db_prefix}chat.game_id=$game_id order BY stamp DESC LIMIT 5";
        $p = mysql_query($sql);
        $chat = "";

        while($row = mysql_fetch_array($p))
        {
            if ($_SESSION['player_id'] == $row['from_id'])
                $cor="black";
            else
                $cor="red";
            $row['message'] = strip_tags(stripslashes($row['message']));
            $chat .= "<font color=$cor><B>$row[username]</B>: $row[message]</font><BR>";
        }

       return $chat;
    }
function writeNote($game_id)
{
       global $_SESSION,$db,$db_prefix;
       $p = mysql_query("SELECT game_id,from_id,note from {$db_prefix}notes where from_id='".$_SESSION['player_id']."' AND game_id=$game_id order BY stamp DESC LIMIT 10");
        $note = "";

        while($row = mysql_fetch_array($p))
        {
             $cor="black";
            $note .= "<font color=$cor>-- ".stripslashes($row['note'])."</font><BR>";
        }

       return $note;
   }


function drawboard()
{
global $board, $playersColor, $numMoves, $MSG_LANG, $confirm_move,$history,$db,$db_prefix;
//OK for this shit here, let's do it a little more elegantly-
//we have to add pref_confirm_move in player preference table first
//and fix the get_player_data query to reflect same
//maybe we should just load the whole nine yards....

if ($confirm_move == FALSE || $_SESSION['pref_confirm_move'] == 0)
        $confirm_move = 0;
elseif ($confirm_move == FALSE || $_SESSION['pref_confirm_move'] == 1)
        $confirm_move = 1;
elseif ($confirm_move == TRUE || $_SESSION['pref_confirm_move'] == 0)
        $confirm_move = 0;
else
        $confirm_move = 1;


        /* find out if it's the current player's turn */
        if (( (($numMoves == -1) || (($numMoves % 2) == 1)) && ($playersColor == "white"))
                || ((($numMoves % 2) == 0) && ($playersColor == "black")) )
            $isPlayersTurn = true;
        else
            $isPlayersTurn = false;

        /* determine who's perspective of the board to show */
        if ($_SESSION['isSharedPC'] && !$isPlayersTurn)
        {
            if ($playersColor == "white")
                $perspective = "black";
            else
                $perspective = "white";
        }
        else
        {
            $perspective = $playersColor;
        }

        /* NOTE: if both players are using the same PC, in a sense it's always the players turn */
        if ($_SESSION['isSharedPC'])
            $isPlayersTurn = true;

        /* determine if board is disabled */
        $isDisabled = isBoardDisabled();

        echo ("<table border='1' style='border-collapse: collapse' bordercolor='#111111' cellpadding='0' cellspacing='0'>\n");

        if ($isDisabled)
            echo ("<tr bgcolor='#DDDDDD'>");
        else
            echo ("<tr bgcolor='white'>");

        /* setup vars to show player's perspective of the board */
        if ($perspective == "white")
        {
            $topRow = 7;
            $bottomRow = 0;
            $rowStep = -1;

            $leftCol = 0;
            $rightCol = 7;
            $colStep = 1;
        }
        else
        {
            $topRow = 0;
            $bottomRow = 7;
            $rowStep = 1;

            $leftCol = 7;
            $rightCol = 0;
            $colStep = -1;
        }

        /* column headers */
        echo ("<th>&nbsp;</th>");

        /* NOTE: end condition is ($rightCol + $colStep) since we want to output $rightCol */
        for ($i = $leftCol; $i != ($rightCol + $colStep); $i += $colStep)
            echo ("<th>".chr($i + 97)."</th>");

        echo ("</tr>\n");

        /* for each row... */
        /* NOTE: end condition is ($bottomRow + $rowStep) since we want to output $bottomRow */
        for ($i = $topRow; $i != ($bottomRow + $rowStep); $i += $rowStep)
        {
            echo ("<tr>\n");
            if ($isDisabled)
                echo ("<th width='20' bgcolor='#DDDDDD'>".($i+1)."</th>\n");
            else
                echo ("<th width='20' bgcolor='white'>".($i+1)."</th>\n");

            /* for each col... */
            /* NOTE: end condition is ($rightCol + $colStep) since we want to output $rightCol */
            for ($j = $leftCol; $j != ($rightCol + $colStep); $j += $colStep)
            {
                echo ("   <td ");

                /* if board is disabled, show board in grayscale */
                if ($isDisabled)
                {
                    if (($j + ($i % 2)) % 2 == 0)
                        echo ("class='disabled-dark' bgcolor='#444444'>");
                    else
                        echo ("class='disabled-light' bgcolor='#BBBBBB'>");
                }
                else
                {
                    //#772222
                    //#CCBBBB
// Highlight last move:
$p = mysql_query("select highlight_last,highlight_color from {$db_prefix}player_preference where player_id = '".$_SESSION['player_id']."'");
$row = mysql_fetch_array($p);
$showHLight = $row['highlight_last'];
if ($showHLight == '1')
{

$showHLcolor = $row['highlight_color'];
if (($numMoves >=0)&&(($i == $history[$numMoves]['from_row'] && $j == $history[$numMoves]['from_col']) || ($i == $history[$numMoves]['to_row'] && $j == $history[$numMoves]['to_col'])))
{
$cor = "bgcolor='".$showHLcolor."'";
}
else
{
if (($j + ($i % 2)) % 2 == 0)
{
$cor = "class='enabled-dark' bgcolor='#955F22'";
}
else
{
$cor = "class='enabled-light' bgcolor='#E3C58C'";
}
}

echo ("$cor>");
}
else
{
if (($j + ($i % 2)) % 2 == 0)
echo ("class='enabled-dark' bgcolor='#955F22'>");
else
echo ("class='enabled-light' bgcolor='#E3C58C'>");
}
}


                /* if disabled or not player's turn, can't click pieces */
                if (!$isDisabled && $isPlayersTurn)
                {
                    echo ("<a href='JavaScript:squareClicked($confirm_move, $i, $j, ");
                    if ($board[$i][$j] == 0)
                        echo ("true,\"".$MSG_LANG["youdontplaywith"]."\",\"$MSG_LANG[confirmmove]\")'>");
                    else
                        echo ("false,\"".$MSG_LANG["youdontplaywith"]."\",\"$MSG_LANG[confirmmove]\")'>");
                }

                echo ("<img name='pos$i-$j' src='images/pieces/".$_SESSION['theme_set']."/");

                /* if position is empty... */
                if ($board[$i][$j] == 0)
                {
                    /* draw empty square */
                    $tmpALT="blank";
                }
                else
                {
                    /* draw correct piece */
                    if ($board[$i][$j] & BLACK)
                        $tmpALT = "black_";
                    else
                        $tmpALT = "white_";

                    $tmpALT .= getPieceName($board[$i][$j]);
                }

                echo($tmpALT.".gif' height='".$_SESSION['pref_board_size']."' width='".$_SESSION['pref_board_size']."' border='0' alt='".$tmpALT."'>");

                if (!$isDisabled && $isPlayersTurn)
                    echo ("</a>");

                echo ("</td>\n");
            }

            echo ("</tr>\n");
        }

        echo ("</table>\n\n");
    }

    function writeJSboard()
    {
        global $board, $numMoves;


        /* write out constants */
        echo ("var DEBUG = true;\n");
        echo ("var EMPTY = 0;\n");
        echo ("var CURRENTTHEME = '".$_SESSION['theme_set']."';\n");
        echo ("var PAWN = ".PAWN.";\n");
        echo ("var KNIGHT = ".KNIGHT.";\n");
        echo ("var BISHOP = ".BISHOP.";\n");
        echo ("var ROOK = ".ROOK.";\n");
        echo ("var QUEEN = ".QUEEN.";\n");
        echo ("var KING = ".KING.";\n");
        echo ("var BLACK = ".BLACK.";\n");
        echo ("var WHITE = ".WHITE.";\n");
        echo ("var COLOR_MASK = ".COLOR_MASK.";\n");

        /* write code for array */
        echo ("var board = new Array();\n");
        for ($i = 0; $i < 8; $i++)
        {
            echo ("board[$i] = new Array();\n");

            for ($j = 0; $j < 8; $j++)
            {
                echo ("board[$i][$j] = ".$board[$i][$j].";\n");
            }
        }

        echo("var numMoves = $numMoves;\n");
        echo("var errMsg = '';\n"); /* global var used for error messages */
    }

    /* provide history data to javascript function */
    /* NOTE: currently, only pawn validation script uses history */
    function writeJSHistory()
    {
        global $history, $numMoves;

        /* write out constants */
        echo ("var CURPIECE = 0;\n");
        echo ("var CURCOLOR = 1;\n");
        echo ("var FROMROW = 2;\n");
        echo ("var FROMCOL = 3;\n");
        echo ("var TOROW = 4;\n");
        echo ("var TOCOL = 5;\n");

        /* write code for array */
        echo ("var chessHistory = new Array();\n");
        for ($i = 0; $i <= $numMoves; $i++)
        {
            echo ("chessHistory[$i] = new Array();\n");
            echo ("chessHistory[$i][CURPIECE] = '".$history[$i]['cur_piece']."';\n");
            echo ("chessHistory[$i][CURCOLOR] = '".$history[$i]['cur_color']."';\n");
            echo ("chessHistory[$i][FROMROW] = ".$history[$i]['from_row'].";\n");
            echo ("chessHistory[$i][FROMCOL] = ".$history[$i]['from_col'].";\n");
            echo ("chessHistory[$i][TOROW] = ".$history[$i]['to_row'].";\n");
            echo ("chessHistory[$i][TOCOL] = ".$history[$i]['to_col'].";\n");
        }
}

    function writeVerbousHistory()
    {
        global $history, $numMoves, $MSG_LANG;

        echo ("<table width='300' border='1'>\n");
        echo ("<tr><th bgcolor='beige' colspan='2'>".$MSG_LANG["history"]."</th></tr>\n");

        for ($i = $numMoves; $i >= 0; $i--)
        {
            if ($i % 2 == 1)
            {
                echo ("<tr bgcolor='black'>");
                echo ("<td width='20'><font color='white'>".($i + 1)."</font></td><td><font color='white'>");
            }
            else
            {
                echo ("<tr bgcolor='white'>");
                echo ("<td width='20'>".($i + 1)."</td><td><font color='black'>");
            }

            $tmpReplaced = "";
            if (!is_null($history[$i]['replaced']))
                $tmpReplaced = $history[$i]['replaced'];

            $tmpPromotedTo = "";
            if (!is_null($history[$i]['promotedTo']))
                $tmpPromotedTo = $history[$i]['promotedTo'];

            $tmpCheck = ($history[$i]['is_in_check'] == 1);

            echo(moveToVerbousString($history[$i]['cur_color'], $history[$i]['cur_piece'], $history[$i]['from_row'], $history[$i]['from_col'], $history[$i]['to_row'], $history[$i]['to_col'], $tmpReplaced, $tmpPromotedTo, $tmpCheck));

            echo ("</font></td></tr>\n");
        }

        echo ("<tr bgcolor='#BBBBBB'><td>0</td><td>".$MSG_LANG["newgame"]."</td></tr>\n");
        echo ("</table>\n");
    }

    function writeHistoryPGN($format = "color", $single = 'none')
    {
        global $history, $numMoves, $MSG_LANG; $_SESSION;

        if ($format == "color"){
              echo ("<table border='0' width=100%  bgcolor=black cellspacing=1 cellpading=1>\n");
          echo ("<tr><th bgcolor='beige' colspan='5'>".strtoupper($MSG_LANG["history"])."</th></tr>\n");
          echo ("<tr><th bgcolor='#BBBBBB' width='50'>".$MSG_LANG["movement"]."</th>");
          echo ("<th bgcolor='white' colspan='2' align='center'><font color='black'>".$MSG_LANG["white"]."</font></th>");
          echo ("<th bgcolor='silver' colspan='2' align='center'><font color='black'>".$MSG_LANG["black"]."</font></th></tr>\n");
        }

        for ($i = 0; $i <= $numMoves; $i+=2)
        {


            if ($format == "color")
                $export = false;
            else $export = true;

                if ($format == "color")
                        echo ("<tr><td align='center' bgcolor='#BBBBBB'>".(($i/2) + 1)."</td><td bgcolor='white' align='center'><font color='black'>");

            $tmpReplaced = "";
            if (!is_null($history[$i]['replaced']))
                $tmpReplaced = $history[$i]['replaced'];

            $tmpPromotedTo = "";
            if (!is_null($history[$i]['promoted_to']))
                $tmpPromotedTo = $history[$i]['promoted_to'];

            $tmpCheck = ($history[$i]['is_in_check'] == 1);

            if ($format == "plain" && $single != 'single')
                         {
                              if (ceil(($i+1)/2) > 1 && $single == 'chess')
                              {
                                echo "<br>";
                              }

                              echo ceil(($i+1)/2).". ";
                         }


                echo moveToPGNString($history[$i]['cur_color'], $history[$i]['cur_piece'], $history[$i]['from_row'], $history[$i]['from_col'], $history[$i]['to_row'], $history[$i]['to_col'], $tmpReplaced, $tmpPromotedTo, $tmpCheck, $export, $single)." ";

                         if ($format == "color")
            {
                echo "</td><td align='center' bgcolor=white height=18>";
                if ($tmpReplaced!="")
                    echo "<img src='images/pieces/".$_SESSION['theme_set']."/black_".$tmpReplaced.".gif' height='15' align='left'> ";
                echo ("</font></td><td bgcolor='silver' align='center'><font color='black'>");
            }

            if ($i == $numMoves){
                if ($format == "color")
                    echo ("&nbsp;");
            }
            else
            {
                $tmpReplaced = "";
                if (!is_null($history[$i+1]['replaced']))
                    $tmpReplaced = $history[$i+1]['replaced'];

                $tmpPromotedTo = "";
                if (!is_null($history[$i+1]['promoted_to']))
                    $tmpPromotedTo = $history[$i+1]['promoted_to'];

                $tmpCheck = ($history[$i+1]['is_in_check'] == 1);

                //if ($format == "plain")
                //      echo ($i+2).". ";

                echo moveToPGNString($history[$i+1]['cur_color'], $history[$i+1]['cur_piece'], $history[$i+1]['from_row'], $history[$i+1]['from_col'], $history[$i+1]['to_row'], $history[$i+1]['to_col'], $tmpReplaced, $tmpPromotedTo, $tmpCheck,$export,$single)." ";
            if ($format == "color")
                {
                    echo "</td><td align='center' bgcolor='silver' height=18>";
                    if ($tmpReplaced!="")
                        echo "<img src='images/pieces/".$_SESSION['theme_set']."/white_".$tmpReplaced.".gif' height='15' align='left'> ";
                }
            }

            if ($format == "color")
                 echo ("</font></td></tr>\n");
            //else{
            //   if (floor(($i+1)/8) == ($i)/8 && $i != 0)
            //      echo "\n";
            //}
        }
        if ($format == "color")
              echo ("</table>\n");

    }
    function writeHistory()
    {
                 global $MSG_LANG,$db,$db_prefix;



        /* based on player's preferences, display the history */
        switch($_SESSION['pref_history'])
        {
            case 'verbose':
                writeVerbousHistory();
                break;

            case 'pgn':
                writeHistoryPGN();
                break;
            case 'graphic':

                echo ("<table border='0' width=300  bgcolor=black cellspacing=1 cellpading=1>\n");
                echo ("<tr><th bgcolor='beige'>".strtoupper($MSG_LANG["history"])."</th></tr>\n");
                echo ("<tr><td bgcolor=white>");
                writeHistoryPGN("plain", 'chess');
                echo ("</td></tr></table>");
            break;
        }
    }


    function writeStatus()
    {
        global $MSG_LANG, $numMoves, $history, $isCheckMate, $statusMessage, $isPlayersTurn;

        ?>
        <table border="0" width="300" align="center" cellspacing=1 cellpadding=1 bgcolor=black>
        <tr bgcolor="beige">
            <th><font size=2>
            <?=$MSG_LANG["status"]?> -

            <?
            if($_SESSION['pref_sound'] == 'on' && !isBoardDisabled())
                    {
                    if ($isPlayersTurn) echo $MSG_LANG["yourturn-sound"]; else echo $MSG_LANG["opponentturn"];
                }
            else{
                    if ($isPlayersTurn) echo $MSG_LANG["yourturn"]; else echo $MSG_LANG["opponentturn"];
            }
            ?> </th>
        </tr>

        <?
        if (($numMoves == -1) || ($numMoves % 2 == 1)){
            $cur_color = $MSG_LANG["white"];
            $mycolor = "white";
            $oppcolor = "black";
        }
        else{
            $cur_color = $MSG_LANG["black"];
            $mycolor = "black";
            $oppcolor = "white";
        }

        if (!$isCheckMate && ($history[$numMoves]['is_in_check'] == 1))
            echo("<TR><td align='center' bgcolor='red'>\n<font size=2><b>".$cur_color." ".$MSG_LANG["isincheck"]."</b><br>\n".$statusMessage."</td></tr>\n");
        else{
            echo("<TR><td align='center' bgcolor='white'>".$statusMessage."&nbsp;</td></tr>\n");
            echo "<script>
                if (is_in_check('$mycolor')){
                            document.write('<TR><td align=center bgcolor=red><font size=2><b>$cur_color ".$MSG_LANG["isincheck"]."!</TD></tr>');
                        }
                        </script>\n";
        }
        if (!isBoardDisabled()){
            if (!$isCheckMate){
                echo "<script>
                curDir=-1;
                if (isXequeMate('$mycolor')){
                    alert('".$MSG_LANG["endsincheckmate"]."');
                    window.location='apply.php?playersColor=$oppcolor&action=checkmate';
                }
                </script>\n";
            }
            echo "<script>
            curDir=-1;
            if (isDraw('$mycolor')&&!isXequeMate('$mycolor')){
                alert('".$MSG_LANG["endsindraw"]."');
                window.location='apply.php?playersColor=$oppcolor&action=draw';
            }
            </script>\n";
        }
        ?>
        </table>
        <?
    }

function writePoints()
{
        global $MSG_LANG, $opponent, $_SESSION, $oficial, $MyRating, $OpponentRating, $MyPV, $OpponentPV;

        if ($oficial == "1")
        {
                    //$MyRealPV = ($MyPV+getPV($OpponentPV)/2;
                    //$OpponentRealPV = ($MyPV+getPV($OpponentPV))/2;
                    //$xpw = getXPW($MyRating,$OpponentRating,$OpponentRealPV);
                    //$xpl = getXPL($OpponentRating,$MyRating,$OpponentRealPV);


                $xpw = getXPW($MyRating,$OpponentRating,$OpponentPV);
                $xpl = getXPL($OpponentRating,$MyRating,$OpponentPV);
            }
            else
            {
                $xpl=0;
                $xpw=0;
            }


echo '     <table border="0" width="300" align="center" cellspacing=1 cellpadding=1 bgcolor=black>
        <tr bgcolor="beige">
            <th><font size=2>  ';
            echo $MSG_LANG["punctuation"];
            echo '
            </th>
<TR><td align="center" bgcolor="white"> ';

            if ($playersColor == "white")
            {
            echo ("".$MSG_LANG["ifwin"]." +$xpl, ".$MSG_LANG["iflose"]." -$xpw");
            }
            else
            {
            echo ("".$MSG_LANG["ifwin"]." +$xpw, ".$MSG_LANG["iflose"]." -$xpl");
            }
            echo "</td></tr></table>";
            return;
            }


function writeTime()
    {
        global $MSG_LANG, $_SESSION, $isPromoting, $otherTurn,$db_prefix,$db;
        $p = mysql_query("SELECT * from {$db_prefix}history where game_id=".$_SESSION['game_id']." ORDER BY time_of_move DESC limit 1");
        $row = mysql_fetch_array($p);

        $cor = $row['cur_color'];

        if(empty($row['time_of_move']))
        {
              $row['time_of_move'] = "0000-00-00 00:00:00";
        }
        $lastmove = $row['time_of_move'];
        //duracao:
        $v = explode(" ",$lastmove);
        $hora = explode(":",$v[1]);
        $data = explode("-",$v[0]);

        if ($lastmove == 0)
            $inicio = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
        else
            $inicio = mktime($hora[0],$hora[1],$hora[2],$data[1],$data[2],$data[0]);
        $fim = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));

        //$d = floor(($fim-$inicio)/60/60/24);
        //$h = floor(($fim-$inicio)/60/60) - 24*$d;
        //$m = floor(($fim-$inicio)/60) - 60*$h - $d*24*60;
        //$s = ($fim-$inicio) - $m*60 - $h*60*60 - $d*24*60;
        $somawhite = "";
        $somablack = "";
        if ($cor == "white")
        {
        if ($isPromoting)
            $somawhite = $fim-$inicio;
        else
            $somablack = $fim-$inicio;
        }
        else
        {
        if ($isPromoting)
            $somablack = $fim-$inicio;
        else
            $somawhite = $fim-$inicio;
        }

        $p = mysql_query("SELECT * from {$db_prefix}games where game_id=".$_SESSION['game_id']);
        $row = mysql_fetch_array($p);
        if ($row['status'] == "")
        {
            $row['time_black'] += $somablack;
            $row['time_white'] += $somawhite;
        }

        //$d = floor(($fim-$inicio)/60/60/24);
        //$h = floor(($fim-$inicio)/60/60) - 24*$d;
        //$m = round(($fim-$inicio)/60) - 60*$h - $d*24*60;

        $blackd = floor($row['time_black']/60/60/24);
        $whited = floor($row['time_white']/60/60/24);
        $blackh = floor($row['time_black']/60/60) - 24*$blackd;
        $whiteh = floor($row['time_white']/60/60) - 24*$whited;
        $blackm = floor($row['time_black']/60) - 60*$blackh - $blackd*24*60;
        $whitem = floor($row['time_white']/60) - 60*$whiteh - $whited*24*60;
        $blacks = $row['time_black'] - 60*$blackm - 60*60*$blackh - $blackd*24*60*60;
        $whites = $row['time_white'] - 60*$whitem - 60*60*$whiteh - $whited*24*60*60;

        if ($row['time_limit'] >= 1440)
                $timelimit_txt = ($row['time_limit']/1440)." ".$MSG_LANG['unlimited'];
        else if ($row['time_limit'] >= 60)
                $timelimit_txt = ($MSG_LANG['hs']." ".$row['time_limit']/60)." ".'hrs.';
        else if ($row['time_limit'] >=30)
                $timelimit_txt = ($MSG_LANG['min'])." ".$row['time_limit']." ".'min.';
        else
                $timelimit_txt = (14)." ".$MSG_LANG['unlimited'];

        ?>
        <table border="0" width="300" align="center" cellspacing=1 cellpadding=1 bgcolor=black>

        <? if ($row['time_limit'] >= 0){?>
        <tr bgcolor="beige">
            <th  colspan=4><font size=2><?=$MSG_LANG["timeforeach"]?></th>
        </tr>
        <TR bgcolor='white'>
            <td colspan=2 nowrap align=center><?=$timelimit_txt?></td>
        </tr>
        <?}?>
        <tr bgcolor="beige">
            <th  colspan=4><font size=2><?=$MSG_LANG["timeofgame"]." [".$MSG_LANG["dhms"]."]"?></th>
        </tr>

        <?
            echo("<TR bgcolor='white'>
                  <td nowrap align=center width=50%>".$MSG_LANG["white"].": $whited:$whiteh:$whitem:$whites</td>
                  <td nowrap align=center width=50%>".$MSG_LANG["black"].": $blackd:$blackh:$blackm:$blacks</td>
                  </tr>\n");
        ?>

        </table>
        <?
    }

    function writePromotion()
    {
       GLOBAL $MSG_LANG,$_SESSION;
    ?>
        <p>
        <script>onClick="stopTimer=1";</script>
        <table width="435" border="1" bgcolor=#FF0000>
        <tr><td align=center valign=top>
            <?=$MSG_LANG["promotepawnto"]?>:
            <br>
            <input style="background-color: red" type="radio" name="promotion" value="<? echo (QUEEN); ?>" checked="checked"><img alt='<?=$MSG_LANG[queen]?>' src='images/pieces/<?=$_SESSION['pref_theme']?>/black_queen.gif' height='25'>
            <input style="background-color: red" type="radio" name="promotion" value="<? echo (ROOK); ?>"><img alt='<?=$MSG_LANG[rook]?>' src='images/pieces/<?=$_SESSION['pref_theme']?>/black_rook.gif' height='25'>
            <input style="background-color: red" type="radio" name="promotion" value="<? echo (KNIGHT); ?>"><img alt='<?=$MSG_LANG[knight]?>' src='images/pieces/<?=$_SESSION['pref_theme']?>/black_knight.gif' height='25'>
            <input style="background-color: red" type="radio" name="promotion" value="<? echo (BISHOP); ?>"><img alt='<?=$MSG_LANG[bishop]?>' src='images/pieces/<?=$_SESSION['pref_theme']?>/black_bishop.gif' height='25'>
            &nbsp;&nbsp;&nbsp;<input type="button" name="btnPromote" value="<?=$MSG_LANG["promote"]?>" onClick="promotepawn()" /><BR>&nbsp;
        </td></tr>
</table>
        </p>
    <?
    }

    function writeWaitPromotion()
    {
       GLOBAL $MSG_LANG;
    ?>
        <p>
        <script>onClick="stopTimer=1";</script>
        <table width="435" border="1" bgcolor=white>
        <tr><td align=center>
            <?=$MSG_LANG["waitingpromotion"]?>
        </td></tr>
        </table>
        </p>
    <?
    }

    function writeUndoRequest()
    {
        GLOBAL $MSG_LANG;
    ?>
        <p>
        <table width="435" border="1">
        <tr><td>
            <?=$MSG_LANG["requestundo"]?>
            <br>
            <input type="radio" name="undoResponse" value="yes"> <?=$MSG_LANG["yes"]?>
            <input type="radio" name="undoResponse" value="no" checked="checked"> <?=$MSG_LANG["no"]?>
            <input type="hidden" name="isUndoResponseDone" value="no">
            <input type="button" value="Responder" onClick="this.form.isUndoResponseDone.value = 'yes'; this.form.submit()">
        </td></tr>
        </table>
        </p>
    <?
    }

    function writeDrawRequest()
    {
       GLOBAL $MSG_LANG;
    ?>
        <p>
        <table width="435" border="1">
        <tr><td>
            <?=$MSG_LANG["drawrequest"]?>
            <br>
            <input type="radio" name="drawResponse" value="yes"> <?=$MSG_LANG["yes"]?>
            <input type="radio" name="drawResponse" value="no" checked="checked"> <?=$MSG_LANG["no"]?>
            <input type="hidden" name="isDrawResponseDone" value="no">
            <input type="button" value="Responder" onClick="this.form.isDrawResponseDone.value = 'yes'; this.form.submit()">
        </td></tr>
        </table>
        </p>
    <?
    }
?>