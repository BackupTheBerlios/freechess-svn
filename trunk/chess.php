<?php
##############################################################################################
#                                                                                            #
#                                chess.php
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

//load settings
include_once ('global_includes.php');

//start ob_start(); stuff
//this is some kludgy shit at best .. but for the time being, it's the least of the worries.

##############################################################
//check user login and load user data if not set in session.
if(empty($_SESSION) || check_login($_SESSION['player_id'],$_COOKIE['PHPSESSID']) == false)
{
    if($_SESSION)
    {
        session_destroy();
    }
    header("Location: index.php");
}

//How much of these includes are actually needed or in use here?
//better to move these to be included only where required..
    include_once ('chessutils.php'); //some pieces handlers, error handler, misc tools
    include_once ('gui.php');     //contains write this, write that, draw board, and such shit
    include_once ('chessdb.php'); //required - numerous functions within
    include_once ('move.php');  //one function: doMove()
    include_once ('undo.php');  //one function: doUndo()
    include_once ('newgame.php');  //yeesh. huge file, contains one function to initialize board - tons of thematics shit here
    //locate initBoard function here and see how important it is to cheare new game
    //otherwise archive newgame.php for later modules features.

//if we dont have a session set, this sucker gonna break bad anyhow- the login should check,
//if there's no session
//data, it should force a re-login

// Language selection
include_once ("languages/".$_SESSION['pref_language']."/strings.inc.php");

//OK. if we click to go to a specific game, its via POST data and we get
//$_POST['game_id']  which is set further down to SESSION

//OK here's where we start the new stuff- total re-write, this.
echo "Cookies, Sessions, POST, globals <pre>";
print_r($_SESSION);
print_r($_POST);
print_r($GLOBALS);

//HolEEEEEEE SHIT! what an eye-opener! horrible, terrible pile of useless junk loaded already!?
//everything's global.. OMG!
//this is gonna take a lonnnnngggg time to fix up.. yeesh.


die();
if(!empty($_POST['ToDo']))
{
     //let's get rid of this "ToDo" junk- specific post values from specific forms will trigger specific functions
     switch($_POST['ToDo'])
     {
          //we'll set $_POST['respond'] as a hidden value in game invitations html
          //if (!empty($_POST['respond'])){ Handle response to a challenge }
          case 'ResponseToInvite':
          if ($_POST['response'] == 'accepted')
          {
               // update game data
               $tmpQuery = "UPDATE games SET status = '', messageFrom = '' WHERE game_id = ".$_POST['game_id'];
               mysql_query($tmpQuery);
               //setup new board
               $_SESSION['game_id'] = $_POST['game_id'];
               //NOTE all functions must be re-done to have return values that can be checked
               $create_new_game = createNewGame($_POST['game_id']);
               $save_this_game = saveGame();
          }
          else
          {
               $tmpQuery = "UPDATE games SET status = 'inviteDeclined', messageFrom = '".$_POST['messageFrom']."' WHERE game_id = ".$_POST['game_id'];
               mysql_query($tmpQuery);
          }

          break;
     }
}
//check if loading game - This should be re-done to only load a specific game

 //we want to look for a game_id, if it isnt set, load an error page

if(!empty($_POST['game_id']))
{
    $SESSION['game_id'] = $_POST['game_id'];
    $game_id = $_POST['game_id'];
}
else
{
    if(empty($_SESSION['game_id']))
    {
        //return to main menu or hand them an error page
        header("location: mainmenu.php");
    }
}

//after the above, at this point we can check specific POST keys and
//determine if to load a new game or re-load the current session[game_id]
if(!empty($_POST['response']))
{
    $response = $_POST['response'];
    if (!empty($_POST['game_id']) && $response != 'declined')
    {
        $_SESSION['game_id'] = $_POST['game_id'];
        $game_id = $_POST['game_id'];
    }
}
else
{
        $response = "ok";
}

include_once ("EPDutils.php");
//What is in this file? -
//it contains the stuff that converts a "pgn" data set into the board positions. it's quite nice
//probably want to update it a little bit however....

//OK let's get this sucker loaded.
if (!empty($_POST['is_in_check'])) //if it is set- it would be *better* to return a boolean, however. then we dont need the extra checking and work
{
     $is_in_check = $_POST['is_in_check'];
}
else
{
     $is_in_check = false;
}
//initialize the variables - let's keep em organized as well....
$isCheckMate = false;
$isPromoting = false;
$isUndoing = false;
if(empty($game_id))
{
   $game_id = $_SESSION['game_id'];
}
$load_history = loadHistory($game_id);//should be load_history($game_id) and return value is history array, better than globaling everything
$load_game = loadGame($game_id);//dont know - should this return a true or false we can check? or does it return specific data?

$sql = $db->Prepare("SELECT * from {$db_prefix}games WHERE game_id=?");
$query = $db->Execute($sql,array($game_id));
db_op_result($query,__LINE__,__FILE__);
$row = $query->fields;
if (empty($row))
{
       //this is rather silly, really .. let's instead show a notice "game deleted" and have done with it :)
       //offer options such as pgn download, review, kibbitz, etc - the game cleanup script will archive saved games
       //The game was deleted
       //echo "<script>window.location='mainmenu.php'</script>";
       //exit;
}
$white = $row['white_player'];
$black = $row['black_player'];
$timeLimit = $row['time_limit'];
$flag_fall = $row['flag_fall'];
$rated = $row['rated'];
$team_match = $row['team_match'];   //are we even using this right now or in the future?
if ($row['white_player'] == $_SESSION['player_id'])
{
        //let's show a realtime calculation "if you win "right now" you get x points, and show player's realtime rating
        //calculate points here plus show ratings
}
else
{
    //TODO: see above note
}
$game_messages = processMessages();
//Verify permission

//WHERE the hell does numMoves come from? throughout here it appears to use math
//to determine who's turn it is. how does the board update (pieces) without updating history and moves, etc?
//yeesh!
//solution- CONSISTENT use of whose_turn in game info- the hell with using history to determine whose turn?!
//White *ALWAYS* moves first so, number of moves will be odd when its black's turn
//Let's start using just the "whose turn" value instead of this silly calculation...
if (($numMoves == -1) || ($numMoves % 2 == 1))
{
     $mycolor2 = "white";
}
else
{
     $mycolor2 = "black";
}

// find out if it's the current player's turn    - See above- let's make it simple- use whose_turn
if (( (($numMoves == -1) || (($numMoves % 2) == 1)) && ($playersColor == "white")) || ((($numMoves % 2) == 0) && ($playersColor == "black")))
{
     $isPlayersTurn = true;
}
else
{
     $isPlayersTurn = false;
}
//this, we can keep- it's helpful , and we can "if" it for kibbitzing also..
if ($white == $_SESSION['player_id'])
{
     $opponent = $black;
}
else
{
     $opponent = $white;
}
//BROKEN POINT - below, it's all broke. SESSION data is fucked up
 //TODO: - I dont fully understand exactly what this code does, but appears it could use some optimization
 //perhaps a function- ########## RESEARCH MARKER START###########
var_dump($_SESSION);
//SHEESH!  where the hell is gameID and this  ["isSharedPC"]=>  bool(false) ["playerID"]=>  int(-1) ["gameID"]=>  int(0) }  shit coming from??

//if (!isBoardDisabled() && !$isCheckMate && $timeLimit > 0)
//{
//there's no else, this if ends at the bottom of script

    //this way we can allow kibbitzing, but not posting. in this way we can control what is loaded
    //and for certain games or player preferences, allow kibbitz may lock them out ..
     if (($white != $_SESSION['player_id']) && ($black != $_SESSION['player_id']))
          die($MSG_LANG["youdonthavepermission"]);

     if (tempoEsgotado($mycolor2))//what is this function for? document it and rename it.
     {
          //here we have to figure out teh time limit shit a little better- when the clock starts and stops..
          $do_rankings = saveRanking($game_id,"resign",$mycolor2,1);
          $update_stamp = updateTimestamp();
          $time_limmit = $timeLimit*60;
          // Update the opponent time
          if ($mycolor2 == "white")
          {
               $sql = $db->Prepare("UPDATE games set timeWhite=? WHERE game_id=?");
               $query = $db->Execute($sql,array($time_limit,$game_id));
               db_op_result($query,__LINE__,__FILE__);
          }
          else
          {
               $sql = $db->Prepare("UPDATE games set timeBlack=? WHERE game_id=?");
               $query = $db->Execute($sql,array($time_limit,$game_id));
               db_op_result($query,__LINE__,__FILE__);
          }
                $smarty->assign('js_checkmate_alert',"<script>alert('".$MSG_LANG['theflaghasfallen']." ".$mycolor2.$MSG_LANG['lost']."');  window.location='chess.php'; </script>");
                //we switch the display based on that. good reason to exit and halt execution here I guess
                exit;
     }
  ########## RESEARCH MARKER END###########
     if(!empty($_POST['from_row']))
     {
          //config value needed- allow_undo Hmm dont we have it already? find out
          if ($isUndoing)//where this stupid variable get set? what teh hell does it mean?
          {
               $do_undo = doUndo();//obviously this is teh undo move- we're eliminating it or making it optional
               $save_the_game = saveGame();//we should pass game data- screw setting stuff global
          }
          else if ($allow_chat && isset($_POST['chat_msg']) && $chatdata)
          {
               if ($_POST['chat_msg'] != "")
               {
                    //post a chat comment- public or private - depends on value of button..??
                    if (!get_magic_quotes_gpc()) //REMOVE once we've got adodb prepare
                    {
                         //Eh?/ Jeez! this is insanity - we now have function so we dont have to CARE about this crap
                         $_POST['chat_msg'] = addslashes($_POST['chat_msg']);
                         $_POST['chat_msg'] = htmlspecialchars($_POST['chat_msg']);
                         $_POST['chat_msg'] = wordwrap($_POST['chat_msg'], 35, " ", 1);
                    }
                    $sql = "";
                    mysql_query("insert into {$db_prefix}chat (from_id,message,game_id) VALUES ('".$_SESSION['player_id']."','".$_POST['chat_msg']."','".$_SESSION[game_id]."')");
                }
          }
          else if (!empty($_POST['note_msg'] ))
          {
               //personal notepad entry
               if (!get_magic_quotes_gpc())
                    $_POST['note_msg'] = addslashes($_POST['note_msg']);
               $p2 = mysql_query("SELECT count(*) from history WHERE game_id='".$game_id."'");
               $row2 = mysql_fetch_array($p2);
               $rounds = floor(($row2[0]+1)/2); //WTF??
               $color = "b";
               if ($rounds == (($row2[0]+1)/2))
                    $color = "w";
               if ($rounds != 0)
                    $rounds = $rounds.$color;
               $notemsg = $_POST['note_msg'];
               $temparray = array($notemsg, $rounds);
               $_POST['note_msg'] = implode(" - MOVE #", $temparray);  //Ahh I see now the purpose of colors
               mysql_query("insert into notes (fromID,note,game_id) VALUES ('".$_SESSION[player_id]."','".$_POST['note_msg']."','".$_SESSION[game_id]."')");
          }
          else if ((!empty($_POST['promotion'])) && ($_POST['to_row'] != "") && ($_POST['to_col'] != ""))
          {
               $promoting_pawn = savePromotion();
               $board[$_POST['to_row']][$_POST['to_col']] = $_POST['promotion'] | ($board[$_POST['to_row']][$_POST['to_col']] & BLACK);//Interesting shit- how does this work? better way to do it?
               $save_game_to_db = saveGame();
          }
          elseif (($_POST['from_row'] != "") && ($_POST['from_col'] != "") && ($_POST['to_row'] != "") && ($_POST['to_col'] != ""))
          {
               //DUMP the values here- this should be hit when submitting a move.

               // ensure it's the current player moving
               // NOTE: if not, this will currently ignore the command...
               // perhaps the status should be instead?
               // (Could be confusing to player if they double-click or something
               $tmpIsValid = true;
               //num moves comes from history - supposed to show whose move it is..
               //we have whose_turn available above. I'll work with this as-is for now til templating and
               //adodb are in, then get it *working* first before breaking it further.
               if (($numMoves == -1) || ($numMoves % 2 == 1))
               {
                    // White's move... ensure that piece being moved is white
                    if ((($board[$_POST['from_row']][$_POST['from_col']] & BLACK) != 0) || ($board[$_POST['from_row']][$_POST['from_col']] == 0))
                    // invalid move would be 0,0
                    $tmpIsValid = false;
                    //$validshit = "was whites move"; //debug string
               }
               else
               {
                    // Black's move... ensure that piece being moved is black
                    if ((($board[$_POST['from_row']][$_POST['from_col']] & BLACK) != BLACK) || ($board[$_POST['from_row']][$_POST['from_col']] == 0))
                    // invalid move
                    $tmpIsValid = false;
                    //$validshit = "was blacks move"; //debug string
               }

               if ($tmpIsValid)
               {
                    $save_game_result = saveHistory();
                    $move_done_result = doMove();
                    $game_saved_true = saveGame();
                    //note- these should be done together and fail safed- ability to roll back if any one part breaks
               }
               else
               {
                    /*echo "<PRE>";
                    var_dump($validshit);
                    var_dump($board[$_POST['from_row']][$_POST['from_col']]);    //int(0)
                    $whatres = ($board[$_POST['from_row']][$_POST['from_col']] & BLACK); //int(0)
                    var_dump($whatres);
                    var_dump($board);
                    var_dump(BLACK);
                    var_dump($_POST);
                    die("You Goofed Up, Brian HAHAHA! ".var_dump($tmpIsValid)."  Aint valid - you need a JS Guru!"); */
               }

          }//The workhorse loop - get data and check/validate,update game moves

     }//end $_POST handling

//Prepare for displaying the page.

$smarty->assign('game_id',$game_id);

//find out if it's the current player's turn
//Didnt we just DO that up above??  - Yep...
//
//        if (( (($numMoves == -1) || (($numMoves % 2) == 1)) && ($playersColor == "white"))
//            || ((($numMoves % 2) == 0) && ($playersColor == "black")))
//            $isPlayersTurn = true;
//        else
//            $isPlayersTurn = false;

//        if ($white == $_SESSION['player_id'])
//            $opponent = $black;
//        else $opponent = $white;

if ($_SESSION['isSharedPC'])
{
     $smarty->assign('title',"FUN GAME");
}
else if ($isPlayersTurn)
{
     $smarty->assign('title',"Chess - ".$MSG_LANG['yourturn']);
     $timer_switch=0;
}
else
{
     $smarty->assign('title',"Chess - ".$MSG_LANG['opponentturn']);
     $timer_switch=1;
}
$smarty->assign('javascript_start',"<script type=\"text/javascript\">");
$write_jsboard = writeJSboard();
$smarty->assign('javascript_write_board', $write_jsboard);
$write_jshistory =  writeJShistory();
$smarty->assign('javascript_write_history',$write_jshistory);
$stop_timer = "var stopTimer=".$timer_switch;
$smarty->assign('stop_timer',$stop_timer);
$smarty->assign('chessboard_refresh_rate',$_SESSION['pref_autoreload']);
$smarty->assign('template_set',$_SESSION['template_set']);

###BOOKMARK POINT

        $p = mysql_query("SELECT username,player_id FROM {$db_prefix}players WHERE player_id='$white'");
        $row = mysql_fetch_array($p);
        $p = mysql_query("SELECT username,player_id FROM {$db_prefix}players WHERE player_id='$black'");
        $row2 = mysql_fetch_array($p);
        $row['username'] = $row['username']." (".$MSG_LANG["white"].")";
        $row2['username'] = $row2['username']." (".$MSG_LANG["black"].")";
        echo "<input type='button' name='btnWhiteUser' value='".$row['username']."' onClick=\"window.open('stats_user.php?cod=$row[1]&voltar=chess.php', '_self')\">";
        echo " <B>X</B> ";
        echo "<input type='button' name='btnBlackUser' value='".$row2['username']."' onClick=\"window.open('stats_user.php?cod=$row2[1]&voltar=chess.php', '_self')\">";
        #echo "<font face=verdana size=2><a href='stats_user.php?cod=$row[1]&voltar=chess.php'>$row[0]</a> <B>x</B> <a href='stats_user.php?cod=$row2[1]&voltar=chess.php'>$row2[0]</a></font>";
        //if ($row["engine"] >= 1 || $row2["engine"] >= 1)
            $isEngine=false;

        $p = mysql_query("SELECT pref_language FROM {$db_prefix}player_preference WHERE player_id=$opponent");
        $r = mysql_fetch_array($p);
    if ($_SESSION['pref_language'] != $r[0])
    {
        $opponent_language = strtoupper($r[0]);
    }
    else
    {
          $opponent_language = "";
    }
    ?>
    </td>
    <td align=center>&nbsp;</td>
</tr>
<tr>
    <td colspan=2>&nbsp;</td>
</tr>
<tr valign="top" align="center">
<td>
    <form name="gamedata" method="post" action="chess.php">
    <input type=hidden name="game_id" value="<?=$_SESSION["game_id"]?>">
    <?

        /* Verify if the promotion process was completed */
        if ($playersColor == "white")
        {
            $promotionRow = 7;
            $otherRow = 0;
        }
        else
        {
            $promotionRow = 0;
            $otherRow = 7;
        }

        for ($i = 0; $i < 8; $i++)
        {
            if (($board[$promotionRow][$i] & COLOR_MASK) == PAWN)
            {
                $isPromoting = true;
                $p = mysql_query("SELECT * FROM history where to_col='$i' AND to_row='$promotionRow' AND curPiece='pawn' and cur_color='$playersColor' and game_id='$_SESSION[game_id]' ORDER BY timeOfMove DESC limit 1");
                $row = mysql_fetch_array($p);
                $_POST['from_row'] = $row[from_row];
                $_POST['from_col'] = $row[from_col];
                $_POST['to_row'] = $row[to_row];
                $_POST['to_col'] = $row[to_col];
            }
        }
        for ($i = 0; $i < 8; $i++)
        {
            if (($board[$otherRow][$i] & COLOR_MASK) == PAWN)
            {
                writeWaitPromotion();
                $isPromoting = 1;
                $otherTurn = 1;
            }
        }
        if ($isPromoting && !$otherTurn)
        {
            writePromotion();
        }

        if ($isUndoRequested)
        {
            writeUndoRequest();
        }

        if ($isDrawRequested)
        {
            writeDrawRequest();
        }

  drawboard();
  ?>

    <!-- table border="0">
    <tr><td -->
    <BR>
    <nobr>
    <input type="button" name="btnReload" value="<?=$MSG_LANG["refresh2"]?>" onClick="window.open('chess.php', '_self')">
    <?
    if ($enable_undo)
    {
    ?>
    <input type="button" name="btnUndo" value="<?=$MSG_LANG["undomove"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); else echo ("onClick='undo(\"".$MSG_LANG["undowarning"]."\")'"); ?>>
    <?
    }
    ?>


    <input type="button" name="btnDraw" value="<?=$MSG_LANG["askdraw"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); elseif ($isPlayersTurn == true && $_SESSION['pref_language'] == "english") echo("onClick='drawrequestwithoutmove(\"".$MSG_LANG["drawrequestwithoutmovingfirst"]."\")'"); elseif ($_SESSION['pref_language'] == "english") echo ("onClick='englishdraw(\"".$MSG_LANG["Englishroundwarning"]."\",$CFG_MIN_ROUNDS)'"); else echo ("onClick='draw($CFG_MIN_ROUNDS,\"".$MSG_LANG["roundwarning"]."\")'");?>>
    <input type="button" name="btnResign" value="<?=$MSG_LANG["resign"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); elseif ($_SESSION['pref_language'] == "english") echo ("onClick='englishresigngame(\"".$MSG_LANG["Englishroundwarning"]."\",$CFG_MIN_ROUNDS)'"); else echo ("onClick='resigngame($CFG_MIN_ROUNDS,\"".$MSG_LANG["roundwarning"]."\")'"); ?>>
    <?
    if (isset($_POST['statsUser']))
    {
    ?>
        <input type="button" name="btnMainMenu" value="<?=$MSG_LANG["back"]?>" onClick="window.open('stats_user.php?cod=<?=$_POST['statsUser']?>', '_self')">
    <?
    }
    else
    {
    ?>
        <input type="button" name="btnMainMenu" value="<?=$MSG_LANG["main"]?>" onClick="window.open('mainmenu.php', '_self')">
        <br><br>

        <input type="button" name="btnMainMenu2" value="Export PGN" onClick="window.open('exportpgn.php','_self')">
    <?
    }
    ?>
    <!-- <input type="button" name="btnLogout" value="Sair" onClick="logout()">
    -->
    <input type="hidden" name="ToDo" value="Logout">    <!-- NOTE: this field is only used to Logout -->
    </nobr>
    <!-- /td></tr>
    </table -->

    <input type="hidden" name="requestUndo" value="no">
    <input type="hidden" name="requestDraw" value="no">
    <input type="hidden" name="resign" value="no">
    <input type="hidden" name="from_row" value="<?=$_POST['from_row']?>">
    <input type="hidden" name="from_col" value="<?=$_POST['from_col']?>">
    <input type="hidden" name="to_row" value="<?=$_POST['to_row']?>">
    <input type="hidden" name="to_col" value="<?=$_POST['to_col']?>">
    <input type="hidden" name="is_in_check" value="false">
    <input type="hidden" name="isCheckMate" value="false">

    <?
    if ($isPromoting && $isEngine)
    {
            echo "<script>promotepawn();</script>";
    }
    ?>

    </form>
    <?
    if ($allow_chat && !isBoardDisabled())
    {
    ?>
<form method=POST action="chess.php" name=chess>

      <input type=hidden name="game_id" value="<?=$_SESSION["game_id"]?>">
             <table width=420 border='0' cellspacing=1  bgcolor=black cellpading=1>
            <tr bgcolor="beige">
              <th colspan="2">Private Game Chat
            <?
             if ($opponent_language != "")
        {
        $language = $MSG_LANG[strtolower($opponent_language)];
        echo " (".$MSG_LANG['opponentlanguage'].": ".$language.")";
        }
        ?>

            </th>
            </tr>
            <tr><td colspan="2" align=left bgcolor=white>
            <?php
            echo writeChat($game_id);
            ?>
            </td></tr>
            <tr><td colspan="2" align=center bgcolor=white><input type=text name=chat_msg size=50 onClick="stopTimer=1"><input type=submit value="<?=$MSG_LANG["write"]?>"></td></tr>
</table><br>
<table width=420 border='0' cellspacing=1  bgcolor=black cellpading=1>
            <tr bgcolor="beige">
             <th>Public Chat</th>
               </tr>
            <tr>
              <td width="400" align=center bgcolor=white><iframe src="chat_window.php?sec=<? if (isset($_POST['sec'])) echo $_POST['sec']; else echo '10'; ?>" width="420" height="90" name="chat_window" frameborder="1"></iframe></td></tr>
<tr bgcolor="beige">
              <th colspan="2" align=center><input name="button9" type="button" class="BOTOES" onClick="window.open('chat.php','chatpopup','toolbar=no,status=no,menubar=no,scrollbars=no,width=350,height=150');" value="<?=$MSG_LANG['enter2chat']?>"></th>
               </tr>
    </table>
    </form>

   <table width="420" border="0" cellspacing="1" bgcolor="#000000">
  <th bgcolor="beige"><?=$MSG_LANG["hints"]?></th>
  <tr>
    <td bgcolor="#FFFFFF">Use
      <input name="button4" type="button" class="BOTOES" onClick="window.location='configure.php'" value="<?=$MSG_LANG["configurations"]?>">
      to change <?=$MSG_LANG["currentpreferences"]?><br>
      1.  <?=$MSG_LANG["boardsize"]?>
      <br>
      2. <?=$MSG_LANG["theme"]?><br>
      3. <?=$MSG_LANG["refresh"]?><br>
      4. <?=$MSG_LANG["e-mailnotification"]?><br>
      5. <?=$MSG_LANG["language"]?><br>
      <br>
      <strong>To move the pieces:</strong> Click on the piece you want to move
      and it will be highlighted. Then click the square you want to move it to.
      Confirm your
      move and the page will update.<br>      <br>
      To castle click on your King and move to desired square. The Rook will move
      automatically.</td>
  </tr>
</table>
   <br>
  <br>
  <?
  }
  ?>
</td>

    <td>

   <input name="button2" type="button" class="BOTOES" onClick='document.logout.submit()' value="<?=$MSG_LANG["logoff"]?>"><input name="button3" type="button" onClick="window.open('analyze.php?whocolor=<?=$playersColor?>&game=<?=$game_id?>', '_blank','toolbar=no,status=no,menubar=no,scrollbars=yes,width=850,height=600')" value="<?=$MSG_LANG["analyze"]?>"><input name="button5" border-width= "5"  type="button" class="BOTOES" onClick="window.location='MyGames.php'" value="<?=$MSG_LANG["mygames"]?>">
<br>
<?
$m = ("SELECT acknowledged,to_id FROM cwc_communication WHERE (to_id = ".$_SESSION['player_id']." OR to_id = NULL) AND acknowledged = 0 and listed <> 1");

$numresults=mysql_query($m);
$numrows=mysql_num_rows($numresults);

if ($numrows != 0)
{

echo '<table width="300" border="0" cellspacing="1" bgcolor="#000000">
  <th bgcolor="beige">Messages</th>
  <tr>
    <td bgcolor="#FFFFFF" align="center">';
echo ("<input type=\"image\" src=\"images/icons/emailenvelope.jpg\" onClick=\"window.location='messages.php'\"><br>".$MSG_LANG["sendpmtext3"]."</td>
  </tr>
</table>");
}

    $tmpQuery = "SELECT * FROM {$db_prefix}games WHERE status = 'playerInvited' AND ((white_player = ".$_SESSION['player_id']." AND message_from = 'black') OR (black_player = ".$_SESSION['player_id']." AND message_from = 'white')) ORDER BY date_created";
    $tmpGames = mysql_query($tmpQuery);
    if (mysql_num_rows($tmpGames) > 0)
    {

        if (mysql_num_rows($tmpGames) > 0 && $_SESSION['pref_sound'] == 1)
        {
            echo '<form name="responseToInvite" action="chess.php" method="post">
            <table border="0" width="300" bgcolor=black cellspacing=1 cellpading=1>
            <tr>
              <th colspan=7 bgcolor=beige>'.$MSG_LANG["therearechallenges-sound"].'</th>
            </tr>
            <tr>
          <th bgcolor=white>'.$MSG_LANG["challenger"].'</th>
              <th bgcolor=white>'.$MSG_LANG["yourcolor"].'</th>
              <th bgcolor=white>'.$MSG_LANG["type"].'</th>
              <th bgcolor=white>'.$MSG_LANG["challengerate"].'</th>
              <th bgcolor=white>'.$MSG_LANG["punctuation"].'</th>
              <th bgcolor=white>'.$MSG_LANG["timeforeach"].'</th>
            <th bgcolor=white>';
            echo "<!--   ". $MSG_LANG["thematic"] ."--> </th>  </tr>";
        }
        elseif ($_SESSION["auto_accept"] == "0")
        {
            echo '<form name="responseToInvite" action="chess.php" method="post">
            <table border="0" width="300" bgcolor=black cellspacing=1 cellpading=1>
            <tr>
              <th colspan=7 bgcolor=beige>'.$MSG_LANG["therearechallenges"].'</th>
            </tr>
            <tr>
              <th bgcolor=white>'.$MSG_LANG["challenger"].'</th>
              <th bgcolor=white>'.$MSG_LANG["yourcolor"].'</th>
              <th bgcolor=white>'.$MSG_LANG["type"].'</th>
              <th bgcolor=white>'.$MSG_LANG["challengerate"].'</th>
              <th bgcolor=white>'.$MSG_LANG["punctuation"].'</th>
              <th bgcolor=white>'.$MSG_LANG["timeforeach"].'</th>
              <th bgcolor=white>';
            echo "<!--   ". $MSG_LANG["thematic"] ."--> </th>  </tr>";
        }

        while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
        {
            if ($tmpGame['white_player'] == $_SESSION['player_id'])
                $tmpFrom = "white";
            else
                $tmpFrom = "black";


            if ($_SESSION["auto_accept"] == "1")
            {
                /*Autoaccept*/

                /* update game data */
                $tmpQuery = "UPDATE {$db_prefix}games SET status = '', message_from = '' WHERE game_id = ".$tmpGame['game_id'];
                mysql_query($tmpQuery);

                /* setup new board */
                $game_id = $tmpGame['game_id'];
                createNewGame($tmpGame['game_id']);
                saveGame();

            }
            else{

                /* Opponent */
                echo("<tr><td bgcolor=white>");
                /* get opponent's nick */
                if ($tmpGame['white_player'] == $_SESSION['player_id'])
                    $tmpOpponent = mysql_query("SELECT username,player_id FROM {$db_prefix}players WHERE player_id = ".$tmpGame['black_player']);
                else
                    $tmpOpponent = mysql_query("SELECT username,player_id FROM {$db_prefix}players WHERE player_id = ".$tmpGame['white_player']);
                $row = mysql_fetch_array($tmpOpponent);
                $opponent = $row['username'];
                $id = $row['player_id'];
                echo "<a href='stats_user.php?cod=$id'>$opponent</a>";
                /* Response */
                echo ("</td><td bgcolor=white>");
                /* Your Color */
                if ($tmpGame['white_player'] == $_SESSION['player_id'])
                {
                    echo $MSG_LANG["white"];
                    //$tmpFrom = "white";
                    $ratingW = "TODO line 1830";//$tmpGame['ratingWhite'];
                    $ratingL = "TODO Line 1831";//$tmpGame['ratingBlack'];
                }
                else
                {
                    echo $MSG_LANG["black"];
                    //$tmpFrom = "black";
                    $ratingL = "TODO Line 1837";//$tmpGame['ratingWhite'];
                    $ratingW = "TODO Line 1838";//$tmpGame['ratingBlack'];
                    //see also further down- where getXPW is at
                }

                if ($tmpGame['rated'] == "1")
                    $rated = $MSG_LANG["official"];
                else $rated = $MSG_LANG["notofficial"];

                echo "<td bgcolor=white>".$rated."</td>";

                if ($tmpGame['rated'] == "1"){
                       $xpw = getXPW($ratingW,$ratingL,getPV($_SESSION['player_id']));
                       $xpl = getXPL($ratingL,$ratingW,getPV($id));
                }else{
                    $xpl=0;
                    $xpw=0;
                }

                $dificuldade = "TODO- Set Difficulty";//getDifficult($_SESSION['player_id'],$id);

                        echo "<td bgcolor=white>$dificuldade</td>\n";
                echo "<td bgcolor=white><i>+$xpw / $xpl</i></td>\n";

                if ($tmpGame['time_limit'] == 0)
                    echo "<td bgcolor=white>$expire_game $MSG_LANG[unlimited]</td>\n";
                else if ($tmpGame['time_limit'] <60)
                    echo "<td bgcolor=white> <strong><font color=red>$MSG_LANG[min]". $tmpGame['time_limit']." min.</font></strong></td>\n";
                else if($tmpGame['time_limit'] < 1440)
                    echo "<td bgcolor=white><strong><font color=red>".$MSG_LANG['hs']." ".($tmpGame['time_limit']/60)." hrs.</font></strong></td>\n";
                else
                echo "<td bgcolor=white>".($tmpGame['time_limit']/24/60)." ".$MSG_LANG['unlimited']."</td>\n";

                echo("<tr><td bgcolor=white colspan=7> <div align='center'>");
                echo ("<input type='button' value='".$MSG_LANG["accept"]."' onclick=\"sendResponse('accepted', '".$tmpFrom."', ".$tmpGame['game_id'].")\">");
                echo ("&nbsp;");
                echo ("<input type='button' value='".$MSG_LANG["reject"]."' onclick=\"sendResponse('declined', '".$tmpFrom."', ".$tmpGame['game_id'].")\">");
                echo ("</div></td>");

            }
        }

        if ($_SESSION["auto_accept"] == 0)
        {
               echo '</table>
               <input type="hidden" name="response" value="">
               <input type="hidden" name="messageFrom" value="">
               <input type="hidden" name="game_id" value="">
               <input type="hidden" name="ToDo" value="ResponseToInvite">
               </form>';
           }
    }


?>
<br>

<br>
<table width=300 align=center border=0 bgcolor=black cellspacing=1 cellpading=1>

        <td bgcolor="beige" align=center><b><font color="black">Game #
<?php
 echo $game_id;

  ?></font></b>
    </td>
        </tr>
        <?
        echo ("<tr><td bgcolor='green' align='center' style='font-weight: normal'><font color='white'><b>Last moves");
     for ($i = $numMoves-$i+7; $i <= $numMoves; $i+=1)
     {
          //echo ("<tr><td bgcolor='green' align='center' style='font-weight: normal'><font color='white'><b>Last 2 Moves - ");
         if($i < 0)
         {
            $i=0;
         }
          $tmpReplaced = "";
          if (!is_null($history[$i]['replaced']))
          {
                    $tmpReplaced = $history[$i]['replaced'];
          }

          $tmpPromotedTo = "";
          if (!is_null($history[$i]['promoted_to']))
          {
                    $tmpPromotedTo = $history[$i]['promoted_to'];
          }
          $tmpCheck = 0;
          if ($history[$i]['is_in_check'] == 1)
          {

               $tmpCheck = 1;
          }
          echo("-(");
          echo(moveToPGNString($history[$i]['cur_color'], $history[$i]['cur_piece'], $history[$i]['from_row'], $history[$i]['from_col'], $history[$i]['to_row'], $history[$i]['to_col'], $tmpReplaced, $tmpPromotedTo, $tmpCheck));
          echo(")");
          //echo ("</b></td></tr>\n");
     }
        //echo("]");
        echo ("</b></font></td></tr>\n");
        ?>
</table>
        <? writeStatus(); ?>
        <BR>


                <form method=POST action="chess.php" name=chess>
    <input type=hidden name="game_id" value="<?=$_SESSION["game_id"]?>">
    <table border='0' width=300  bgcolor=black cellspacing=1 cellpading=1>
    <tr><th bgcolor='beige' colspan='3'>NOTES TO SELF
   <? if ($opponent_language != ""){
      $language = $MSG_LANG[strtolower($opponent_language)];
      //echo " (".$MSG_LANG['opponentlanguage'].": ".$language.")";
      }
   ?>
    </th></tr>
    <tr><td bgcolor=white align=left><?= writeNote($game_id)?></td></tr>
    <tr><td align=center bgcolor=white><input type=text name=note_msg size=30 onClick="stopTimer=1"><input type=submit value="<?=$MSG_LANG["write"]?>"></td></tr>
      </table>
      </form>
        <? writeTime(); ?>
        <BR>
        <?
            if (!isBoardDisabled() && mysql_num_rows($tmpGames) == 0)
                writePoints();
        ?>
        <BR>
        <!-- <input type="button" style="font-size: 8pt; font-weight: bold; font-family: verdana; cursor: hand" value="<?=$MSG_LANG["resign"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); else echo ("onClick='resigngame($CFG_MIN_ROUNDS,\"".$MSG_LANG["roundwarning"]."\")'"); ?>>&nbsp;&nbsp;
        <input type="button" style="font-size: 8pt; font-weight: bold; font-family: verdana; cursor: hand" value="<?=$MSG_LANG["askdraw"]?>" <? if (isBoardDisabled()) echo("disabled='yes'"); else echo ("onClick='draw($CFG_MIN_ROUNDS,\"".$MSG_LANG["roundwarning"]."\")'"); ?>>&nbsp;&nbsp; -->


        <table width="300" cellspacing=1 bordercolor="black" bgcolor=black boarder="0" cellpading=1>
        <?

    //Pieces out:

    $black = StartPieces();
    $white = StartPieces();

    $p = mysql_query("SELECT * FROM {$db_prefix}pieces WHERE game_id = ".$game_id." order by color,piece");
    $wmaterial = 0;
      $bmaterial = 0;
      while ($row = mysql_fetch_array($p)){
        if ($row['color'] == "white"){
            $white[$row['piece']]--;
                  $tempmaterial=0;
                        if ($row['piece']=="pawn" AND $row['color']=="white")
                        $tempmaterial=1;
                        if ($row['piece']=="knight" AND $row['color']=="white")
                        $tempmaterial=3;
                        if ($row['piece']=="bishop" AND $row['color']=="white")
                        $tempmaterial=3;
                        if ($row['piece']=="rook" AND $row['color']=="white")
                        $tempmaterial=5;
                        if ($row['piece']=="queen" AND $row['color']=="white")
                        $tempmaterial=9;
                        $wmaterial=$wmaterial+$tempmaterial;}
        else {
            $black[$row['piece']]--;
                  $tempmaterial=0;
                        if ($row['piece']=="pawn" AND $row['color']=="black")
                        $tempmaterial=1;
                        if ($row['piece']=="knight" AND $row['color']=="black")
                        $tempmaterial=3;
                        if ($row['piece']=="bishop" AND $row['color']=="black")
                        $tempmaterial=3;
                        if ($row['piece']=="rook" AND $row['color']=="black")
                        $tempmaterial=5;
                        if ($row['piece']=="queen" AND $row['color']=="black")
                        $tempmaterial=9;
                        $bmaterial=$bmaterial+$tempmaterial;}
    }
echo "<tr><td align=center bgcolor=beige>";
        echo "Captured Pieces";
            echo "</td>";
            echo "<td align=center bgcolor=beige style='width: 4em'>";
            echo "Material";
      echo "</td></tr>";

    echo "<tr><td align=left bgcolor=white>";
    while(list($piece,$num) = each($white))
            if ($num > 0)
            for ($y=0; $y<$num; $y++)
                echo "<img src='images/pieces/".$_SESSION['theme_set']."/white_".$piece.".gif' height='25'> ";
    $wmaterial = 39 - $wmaterial;
      echo "</td><td align=center bgcolor=white>";
      echo "<font size=+1>".$wmaterial."</font>";
      echo "</td></tr>";
    echo "<tr><td align=left bgcolor=white>";
    while(list($piece,$num) = each($black))
        if ($num > 0)
            for ($y=0; $y<$num; $y++)
                echo "<img src='images/pieces/".$_SESSION['theme_set']."/black_".$piece.".gif' height='25'> ";

    $bmaterial = 39 - $bmaterial;
      echo "</td><td align=center bgcolor=white>";
      echo "<font size=+1>".$bmaterial."</font>";
      echo "</td></tr>";
    echo "</table>";
    ?>
        <BR>

<table align=center border="1" cellspacing="0" cellpadding="0"><tr><td></td></tr>
<tr><td style="width: 300px; height: 230px;">
<div style="width: 100%; height: 100%; overflow : auto;">
<? writeHistory();?> </div></td></tr><tr><td></td></tr></table>    <br>
<table width="300" border="0" cellspacing="1" bgcolor="red">
  <th bgcolor="beige">Our Sponsors</th>
  <tr>
    <td><div align="center"><!-- Banner rotator can go here -->
    </div></td>
  </tr>
</table>
        <form name="allhisgames" action="chess.php" method="post">
        <table border="0" width="300" bgcolor=black cellspacing=1 cellpading=1>
        <tr>
        <th bgcolor="beige" colspan=7><?=$MSG_LANG["quickgame"]?></th>
        </tr>
        <?
        //$tmpGames = mysql_query("SELECT games.*,DATE_FORMAT(dateCreated, '%d/%m/%y %H:%i') as created, DATE_FORMAT(lastMove, '%d/%m/%y %H:%i') as lastm FROM {$db_prefix}games WHERE status = '' AND (white_player = ".$_SESSION['player_id']." OR black_player = ".$_SESSION['player_id'].") ORDER BY lastMove");
         //put this back in front of FROM if it breaks ,DATE_FORMAT(dateCreated, '%d/%m/%y %H:%i') as created, DATE_FORMAT(lastMove, '%d/%m/%y %H:%i') as lastm

 $player_id = $_SESSION['player_id'];
   $tmpGames = mysql_query("SELECT game_id,whose_turn,white_player,black_player FROM {$db_prefix}games WHERE status='' AND (white_player='$player_id' OR black_player='$player_id') GROUP BY game_id ORDER BY last_move;");
    if (mysql_num_rows($tmpGames) > 0)
    {

        $l = 0;
        while($tmpGame = mysql_fetch_array($tmpGames))
        {


            /* get opponent's nick */
            //moved to AFTER the "your turn" check
            if($tmpGame['white_player'] == $_SESSION['player_id'])
            {
                $opponent = $tmpGame['black_player'];
                $tmpColor = "white";

            }
            else
            {
                $opponent = $tmpGame['white_player'];
                $tmpColor = "black";

            }


            $yourturn = false;
            if ($tmpGame['whose_turn'] === $_SESSION['player_id'])
            {

                    $yourturn = true;

            }
            else
            {
                    $yourturn = false;
            }
           //get opponent data now cant just check for turn unless we know if opponent is online
            $tmpOpponent = mysql_query("SELECT username,last_update,player_id FROM {$db_prefix}players WHERE player_id = '$opponent'");

            $row = mysql_fetch_array($tmpOpponent);

            $name = $row['username'];
            $opponent = substr($name,0,25);



            if ($row['last_update'] >= (time()-300))
            {
                $img = "online";
            }
            else
            {
                $img = "offline";
            }

            if (($yourturn || $img == "online") && $tmpGame['game_id'])
            {

                $l++;

                if ($l == 1){
                    ?>
                    <tr>
                    <th bgcolor="white">&nbsp;</th>
                    <th bgcolor="white">&nbsp;</th>
                    <th bgcolor="white"><?=$MSG_LANG["opponent"]?></th>
                    <th bgcolor="white"><?=$MSG_LANG["turn"]?></th>
                    </tr>
                    <?
                }

                echo "<tr><td bgcolor='white' ><img src='images/$img.gif' alt='$img'></td>";
                echo "<td bgcolor='white' align=center><input style='font-size:11' type=button value='".$MSG_LANG['play']."' onClick='loadnextGame(".$tmpGame['game_id'].")'></td>";
                /* Opponent */
                echo("<td bgcolor='white' >");

                //$agora = mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y"));
                //$v = explode(" ",$tmpGame[lastm]);
                //$hora = explode(":",$v[1]);
                //$data = explode("/",$v[0]);
                //$lastmove = mktime($hora[0],$hora[1],0,$data[1],$data[0],$data[2]);

                //$d = floor(($agora-$lastmove)/60/60/24);
                //$d = floor(($agora-$lastmove)/60/60/24);
               //$h = floor(($agora-$lastmove)/60/60) - 24*$d;
                //$m = round(($agora-$lastmove)/60) - 60*$h - $d*24*60;

                //if ($d>=($CFG_EXPIREGAME-1))$cor="#FF000";
                //elseif ($d>=($CFG_EXPIREGAME-2))$cor="#CC3300";
            //    elseif ($d>=($CFG_EXPIREGAME-3))$cor="#CC6600";
            //    elseif ($d>=($CFG_EXPIREGAME-4))$cor="#FF9900";
                //else $cor ="black";

                //if ($d ==1)$txt = $MSG_LANG['day'];
                //else $txt = $MSG_LANG['days'];

                //$lasttouch = date("d.m.y, H:i", $row[2]);
                echo("<a href='stats_user.php?cod=".$row['player_id']."'><b>".$opponent."</b>"."</a>");
                //echo $opponent;

                /* Your Color */
                echo ("</td><td bgcolor='white' align=center>");
                if ($yourturn)
                    echo ("<strong><font color=red>".$MSG_LANG["yourturn"]."</font></strong>");
                else
                    echo $MSG_LANG["waiting"];
                echo ("</td>");
                echo "</tr>\n";
            }
        }

        if ($l ==0)
            echo("<tr><td bgcolor='white' align=center>".$MSG_LANG["noquickgames"]."</td></tr>\n");

        /* share PC */
        //echo ("<tr><td colspan='3'>Will both players play from the same PC?</td>");
        //echo ("<td><input type='radio' name='rdoShare' value='yes'> Yes</td>");
        //echo ("<td><input type='radio' name='rdoShare' value='no' checked> No</td></tr>\n");
    }
    ?>
    </table>

    <input type='hidden' name='rdoShare' value='no'>
    <input type="hidden" name="game_id" value="">
    <input type="hidden" name="sharePC" value="no">
    </form>

    </td>
</tr>
<tr>
    <td>
    <p align=left>
    <font face=verdana size=1 color=#EEEEEE>
    <script>
    curDir = -1;

    if (is_in_check('<?=$mycolor2?>'))
    {
        document.write("<?=$mycolor2?> is in Check<BR>");
    }
    curDir = -1;
    if (isXequeMate('<?=$mycolor2?>')){
        document.write("<?=$mycolor2?> is in Check-Mate<BR>");
    }
    if (isDraw('<?=$mycolor2?>')&&!isXequeMate('<?=$mycolor2?>')){
    document.write('Javascript result: The game ends in a Draw!');
    }
    </script>
    <?
    if (!$isPlayersTurn && !isBoardDisabled() && !$_SESSION['isSharedPC'])
    {
        if ($_SESSION['pref_autoreload'] >= $auto_reload_min)
            $autoreload = $_SESSION['pref_autoreload'];
        else
            $autoreload = $auto_reload_min;

        echo "<script>
                setTimeout(\"refreshwindow()\",".($autoreload*1000).")
              </script>";
    }
    ?>
    </font>
    </p>
    </td>
    <td>&nbsp;</td>
</tr>
</table>

</body>
</html>
<form name="logout" action="mainmenu.php" method="post">
    <input type="hidden" name="ToDo" value="Logout">
</form>

<?
if (isset($COMPRESSION) && $COMPRESSION && isset($ob_mode) && $ob_mode)
{
         PMA_outBufferPost($ob_mode);
}

?>
