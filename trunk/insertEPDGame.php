<?php
##############################################################################################
#                                                                                            #
#                                insertEPDGame.php
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

    require "EPDutils.php";

    $pl = mysql_query("SELECT * FROM players WHERE playerID='".$_SESSION['playerID']."'");
    $me = mysql_fetch_array($pl);

    $firstName = $me['firstName'];

    $moves = ($_GET['moves'] == "2" || $_GET['moves'] == "3") ? $_GET['moves'] : 1;

    $ToDo = $_POST['ToDo'];

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
        //  getObject('displaypos').onclick = function(){EPDToBoard(getObject('EPD').value);};
       //   }

       function weiter(url) {

       parent.location.href = url;

       }
    //-->
    </script>

</head>

<body>
<div align="center">
<h3>Neues Trainingsspiel<br>

<?PHP if ($moves < 3 && empty($ToDo)) { ?>

..:: MATT IN <?PHP if ($moves == 1) echo "EINEM ZUG"; else echo "IN ZWEI ZÜGEN"; ?> ::..

<?php

} else { echo "..:: SCHACH TRAINING ::.."; }

echo "</h3>";

require "groups_functions.php";

$cn = getcount("game_positions", "WHERE moves = ".$moves);

echo "Die PSM Trainingsdatenbank enthält ".$cn." Partien<br><br>";

$msg = '';

if($ToDo=="createGame")
{
    $whitePlayer = $_POST['whitePlayer'];
    $blackPlayer = $_POST['blackPlayer'];
    $EPD = $_POST['EPD'];
    $id = $_POST['id'];

    if (empty($whitePlayer) || empty($blackPlayer) || empty($EPD) || empty($id))
    {
        echo "Fehler!";
        exit;
    }

    $tmpQuery = "INSERT INTO games(whitePlayer, blackPlayer, dateCreated, lastMove, gameMessage, messageFrom, oficial)
                 VALUES ('".$whitePlayer."','".$blackPlayer."', now(), now(), '', 'white', 0)";
    mysql_query($tmpQuery);
    echo mysql_error();
    // Get the ID of the game we just inserted..
    $insertedGameId = mysql_insert_id();

    mysql_query("INSERT INTO training_games VALUES(NULL, '$id', '$insertedGameId','".$me['playerID']."', 0)"); echo mysql_error();

    $sq = EPD2Board($EPD);
    insertPieces($sq, $insertedGameId);

    $tomove = EPDCurColor($EPD);
    if($tomove == 'black')
    {
        $tmpQuery = "INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck)
                                VALUES   (Now(), '$insertedGameId', 'king', '$tomove', '0',     '0',  '0',   '0',     null,       null, '0')";
        mysql_query($tmpQuery);
        echo mysql_error();
    }

    $url = 'chess.php?gameID='.$insertedGameId;

    echo "Spiel ".$insertedGameId." erstellt. Sie werden weitergeleitet.<br>";
    echo '<a  href="#" onclick="parent.location.href=\''.$url.'\'">Klicken sie hier um jetzt zu dem Spiel zu gelangen.</a>';
    ?>

    <script>
    setTimeout(weiter("<?=$url?>"),"2000");
    </script>

    <?PHP

} else {

$g1 = mysql_query("SELECT * FROM game_positions WHERE moves = '$moves' ORDER BY rand(".rand().")");
echo mysql_error();
while ($g = mysql_fetch_array($g1))
{
    $games[] = $g;
}

while (list($key,$val) = each($games))
{
    $pos .= '"'.$val['position'].'", ';
    $txt .= '"'.$val['id'].'", ';
    $col .= '"'.$val['color'].'", ';
    $frg .= '"'.stripslashes($val['frage']).'", ';

    $p1 = getcount("training_ranking",
                   "WHERE id = '".$val['id']."' AND playerID = '".$me['playerID']."'");

    $p2 = ($p1 == 1) ? "Ja" : "Nein";

    $pla .='"'.$p2.'", ';
}

$c1 = strlen($pos);
$pos = substr($pos, 0, $c1 - 2);

$c1 = strlen($txt);
$txt = substr($txt, 0, $c1 - 2);

$c1 = strlen($col);
$col = substr($col, 0, $c1 - 2);

$c1 = strlen($pla);
$pla = substr($pla, 0, $c1 - 2);

$c1 = strlen($frg);
$frg = substr($frg, 0, $c1 - 2);

$num = count($games)-1;

$EPD = $games[0]['position'];

// Display a board... (why not?)
displayBoard(EPD2Board($EPD));

?>

<script type="text/javascript">
    <!--

    var Pid = <?=$me['playerID']?>;
    var Cid = 1;

    var Pos = new Array(<?=$pos?>);
    var Txt = new Array(<?=$txt?>);
    var Col = new Array(<?=$col?>);
    var Pla = new Array(<?=$pla?>);
    <?PHP if ($moves == 3) { ?> var Frg = new Array(<?=$frg?>);<?PHP } ?>

    var imax = <?=$num?>;
    var oldic = 0;
    var ic = 0;

    EPDToBoard(Pos[0]);

    function next(m) {

        if (m == 1) {
            if (ic == imax) ic = 0;
            else ic = ic + 1;
      }

      else if ( m == 0 ) {
            if (ic > 0) ic = ic-1;
            else ic = imax;
      }

      else if ( m == 2 ) {
            oldic = ic;
            do
            {
              if (ic > 0) ic = ic-1;
              else ic = imax;
            }
            while ( ic != oldic && Pla[ic] == "Ja" );
      }

      else if ( m == 3 ) {
            oldic = ic;
            do
            {
                  if (ic == imax) ic = 0;
              else ic = ic + 1;
            }
            while ( ic != oldic && Pla[ic] == "Ja" );
      }

        EPDToBoard(Pos[ic]);
        EPDToBoard(Pos[ic]);

        showtext(1, Txt[ic]);
        showtext(2, Col[ic]);
        showtext(3, Pla[ic]);
        <?PHP if ($moves == 3) { ?>showtext(4, Frg[ic]);<?PHP } ?>

        setPostValues();

    }

    function showtext(id,text) {

        el = "EL_"+id;
        document.getElementById(el).innerHTML = text;

    }

    function setPostValues() {

        document.forms[0].EPD.value = Pos[ic];

        if (Col[ic] == 'white') {
            document.forms[0].whitePlayer.value = Pid;
            document.forms[0].blackPlayer.value = Cid;
        }
        else {
            document.forms[0].whitePlayer.value = Cid;
            document.forms[0].blackPlayer.value = Pid;
        }

        document.forms[0].id.value = Txt[ic];
    }

  //-->
</script>

<form name="newGameCreate" action="insertEPDGame.php" method="POST">
    <div>
        <input type="hidden" name="ToDo" value="createGame" />
        <input type="hidden" name="EPD"  value="" />
        <input type="hidden" name="whitePlayer" value="" />
        <input type="hidden" name="blackPlayer" value="" />
        <input type="hidden" name="id" value="" />
    </div>
    <div>
        <a href="#" onclick="next(0);"><< Vorherige Position</a>
        &nbsp;&nbsp;
        <a href="#" onclick="next(1);">Nächste Position >></a>
    </div>
    <div>
        <a href="#" onclick="next(2);"><< Vorherige ungespielte Position</a>
        &nbsp;&nbsp;
        <a href="#" onclick="next(3);">Nächste ungespielte Position >></a>
    </div>
    <br><br>
    <b>Sie spielen das Traninigsspiel #<i id=EL_1></i> und ihre Farbe ist <i id=EL_2></i>.</b><br>
    Bereits gespielt: <b id=EL_3></b>
    <?PHP if ($moves == 3) { ?>
    <br><br>
    <b id=EL_4></b>
    <?PHP } ?>
    <script>
    <!--
        showtext(1, Txt[ic]);
        showtext(2, Col[ic]);
        showtext(3, Pla[ic]);
        <?PHP if ($moves == 3) { ?>showtext(4, Frg[ic]);<?PHP } ?>
        setPostValues();
    //-->
    </script>

    <br><br>
    <input type="submit" value="Spiel starten">
</form>

<?php

} // ToDo Abfrage

?>

</div>
</body>
</html>
