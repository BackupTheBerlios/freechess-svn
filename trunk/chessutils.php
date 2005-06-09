<?php
##############################################################################################
#                                                                                            #
#                                chessutils.php
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

    $_CHESSUTILS = true;

    function displayError($msg,$back = "")
    {
        GLOBAL $MSG_LANG,$style;

        if ($MSG_LANG['back'] == "")
            $MSG_LANG['back'] = "Return";

        if ($back == "close")
           $back = "window.close()";
        else if ($back != "")
           $back = "window.location='$back'";
        else
            $back = "history.go(-1)";

        echo "
        <html>
        <head><LINK rel='stylesheet' href='themes/$style/mainstyles.css' type='text/css'></head>
        <body bgcolor=white>
        <p align=center><BR><BR>
        <table width=400 align=center><tr><td><BR>
        <font face=verdana size=3 color=red><B>
        $msg
        </B></font><BR>&nbsp;
        </td></td></table>
        <BR><BR><input type=button value='$MSG_LANG[back]' onClick=\"$back\">
        </body>
        </html>";

         exit;
    }

    function StartPieces()
    {
        $pieces["rook"] = 2;
        $pieces["knight"] = 2;
        $pieces["bishop"] = 2;
        $pieces["queen"] = 1;
        $pieces["king"] = 1;
        $pieces["pawn"] = 8;

        return $pieces;
    }


    /* these are utility functions used by other functions */
    function getPieceName($piece)
    {
        switch($piece & COLOR_MASK)
        {
            case PAWN:
                $name = "pawn";
                break;
            case KNIGHT:
                $name = "knight";
                break;
            case BISHOP:
                $name = "bishop";
                break;
            case ROOK:
                $name = "rook";
                break;
            case QUEEN:
                $name = "queen";
                break;
            case KING:
                $name = "king";
                break;
        }

        return $name;
    }

    function getPieceCode($color, $piece)
    {
        switch($piece)
        {
            case "pawn":
                $code = PAWN;
                break;
            case "knight":
                $code = KNIGHT;
                break;
            case "bishop":
                $code = BISHOP;
                break;
            case "rook":
                $code = ROOK;
                break;
            case "queen":
                $code = QUEEN;
                break;
            case "king":
                $code = KING;
                break;
        }

        if ($color == "black")
            $code = BLACK | $code;

        return $code;
    }

    function getPGNCode($piecename)
    {
    $pgnCode = "";
        switch($piecename)
        {
            case 'pawn':
                $pgnCode = "";
                break;
            case 'knight':
                $pgnCode = "N";
                break;
            case 'bishop':
                $pgnCode = "B";
                break;
            case 'rook':
                $pgnCode = "R";
                break;
            case 'queen':
                $pgnCode = "Q";
                break;
            case 'king':
                $pgnCode = "K";
                break;
        }

        return $pgnCode;
    }

    function isBoardDisabled()
    {
        global $board, $isPromoting, $isUndoRequested, $isDrawRequested, $isGameOver, $playersColor;

        /* if current player is promoting, a message needs to be replied to (Undo or Draw) or the game is over, then board is Disabled */
        $tmpIsBoardDisabled = (($isPromoting || $isUndoRequested || $isDrawRequested || $isGameOver) == true);

        /* if opponent is in the process of promoting, then board is diabled */
        if (!$tmpIsBoardDisabled)
        {
            if ($playersColor == "white")
                $promotionRow = 7;
            else
                $promotionRow = 0;

            for ($i = 0; $i < 8; $i++)
                if (($board[$promotionRow][$i] & COLOR_MASK) == PAWN)
                    $tmpIsBoardDisabled = true;
        }

        return $tmpIsBoardDisabled;
    }

    function moveToPGNString($curColor, $piece, $fromRow, $fromCol, $toRow, $toCol, $pieceCaptured, $promotedTo, $isChecking, $export = false)
    {
        $pgnString = "";

        /* check for castling */
        if (($piece == "king") && (abs($toCol - $fromCol) == 2))
        {
            /* if king-side castling */
            if (($toCol - $fromCol) == 2)
                $pgnString .= ("O-O");
            else
                $pgnString .= ("O-O-O");
        }
        else
        {
            /* PNG code for moving piece */
            $pgnString .= getPGNCode($piece);
            //if (getPGNCode($piece) == "" && $export && $pieceCaptured != "")
            //    $pgnString .= chr($fromCol + 97);


            /* source square */
            //if  ($export == false){
                $pgnString .= chr($fromCol + 97).($fromRow + 1);
            //    $pgnString .= " ";
            //}

            /* check for captured pieces */
            if ($pieceCaptured != "")
                        $pgnString .= "x";
                    else
                        $pgnString .= "-";


            /* destination square */
            $pgnString .= chr($toCol + 97).($toRow + 1);

            /* check for pawn promotion */
            if ($promotedTo != "")
                $pgnString .= "=".getPGNCode($promotedTo);
        }

        /* check for CHECK */
        if ($isChecking)
            $pgnString .= "+";

        /* if checkmate, $pgnString .= "#"; */

        return $pgnString;
    }

    function moveToVerbousString($curColor, $piece, $fromRow, $fromCol, $toRow, $toCol, $pieceCaptured, $promotedTo, $isChecked)
    {
        $verbousString = "";

        /* ex: white queen from a4 to c6 */
        $verbousString .= $curColor." ".$piece." from ".chr($fromCol + 97).($fromRow + 1)." to ".chr($toCol + 97).($toRow + 1);

        /* check for castling */
        if (($piece == "king") && (abs($toCol - $fromCol) == 2))
            $verbousString .= " (castled)";

        /* check for en passant */
        if (($piece == "pawn") && ($toCol != $fromCol) && ($pieceCaptured == ""))
            $verbousString .= " eating pawn en-passant";

        if ($pieceCaptured != "")
            $verbousString .= " eating ".$pieceCaptured;

        if ($promotedTo != "")
            $verbousString .= "<br>Pawn promoted to ".$promotedTo;

        return $verbousString;
    }

    function webchessMail($msgType, $msgTo, $move, $opponent)
    {
        global $CFG_MAILADDRESS;

        /* default message and subject */
        $mailmsg = "";
        $mailsubject = "Webmaster";

        /* load specific message and subject */
        switch($msgType)
        {
            case 'test':
                require 'mailmsgtest.php';
                break;
            case 'invitation':
                require 'mailmsginvite.php';
                break;
            case 'withdrawal':
                require 'mailmsgwithdraw.php';
                break;
            case 'resignation':
                require 'mailmsgresign.php';
                break;
            case 'move':
                require 'mailmsgmove.php';
                break;
            case 'reminder':
                require 'mailmsgreminder.php';
            break;
        }

        $headers = "From: webmaster<".$CFG_MAILADDRESS.">\r\n";
        //$headers .= "To: ".$msgTo."\n";
        $headers .= "Reply-To: webmaster <".$CFG_MAILADDRESS.">\r\n";

        mail($msgTo, $mailsubject, $mailmsg, $headers);
    }

    /* returns true if current version of PHP is greater than vercheck */
    /* donated to PHP page (http://www.php.net/manual/en/function.version-compare.php) */
    /* by savetz@northcoast.com and is PHP < 4.1.0 safe */
    function minimum_version( $vercheck ) {
        $minver = explode(".", $vercheck);
        $curver = explode(".", phpversion());

        if (($curver[0] < $minver[0])
            || (($curver[0] == $minver[0])
                && ($curver[1] < $minver[1]))
            || (($curver[0] == $minver[0])
                && ($curver[1] == $minver[1])
                && ($curver[2][0] < $minver[2][0])))
            return false;
        else
            return true;
    }

    /* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
    /* heavily based on php4-1-0_varfix.php by Tom Harrison (thetomharrison@hotmail.com) */
    /* only doing the opposite: creating _SESSION, _GET and _POST based on */
    /* their HTTP_*_VARS equivalent */
    function createNewHttpVars($type)
    {
        global $HTTP_POST_VARS, $HTTP_GET_VARS, $HTTP_SESSION_VARS;

        $temp = array();
        switch(strtoupper($type))
        {
            case 'POST':   $temp2 = &$HTTP_POST_VARS;   break;
            case 'GET':    $temp2 = &$HTTP_GET_VARS;    break;
            case 'SESSION':    $temp2 = &$HTTP_SESSION_VARS;    break;
            default: return 0;
        }

        while (list($varname, $varvalue) = each($temp2)) {
            $temp[$varname] = $varvalue;
        }

        return ($temp);
    }

    function fixOldPHPVersions()
    {
        global $_fixOldPHPVersions;

        if (isset($_fixOldPHPVersions))
            return;

        if (!minimum_version("4.1.0"))
        {
            global $_POST, $_GET, $_SESSION;

            $_POST = createNewHttpVars("POST");
            $_GET = createNewHttpVars("GET");
            //$_SESSION = createNewHttpVars("SESSION");

            if (!isset($HTTP_SESSION_VARS["_SESSION"]))
                session_register("_SESSION");
        }

        $_fixOldPHPVersions = true;
    }

    // this function was taken from the PHP documentation
    // http://www.php.net/manual/en/function.mt-srand.php
    // seed with microseconds
    function make_seed() {
        list($usec, $sec) = explode(' ', microtime());
        return (float) $sec + ((float) $usec * 100000);
    }


    // this function was provided to the PHP documentation
    // by houtex_boy@yahoo.com and slightly modified to use
    // the above make_seed()
    // http://www.php.net/manual/en/function.srand.php
    // ensures srand() is only called once
    function init_srand($seed = '')
    {
        static $wascalled = FALSE;
        if (!$wascalled){
            $seed = $seed === '' ? make_seed() : $seed;
            srand($seed);
            $wascalled = TRUE;
        }
    }

    function getPlayerLevel($playerID){
        $table[0] = 1300;
        for ($t=1;$t<=20;$t++)
            $table[$t] = $table[$t-1] + $t*50;

        $p = mysql_query("select rating from players where playerID='".$playerID."'");
        $row = mysql_fetch_array($p);
        $myRating = $row[0];
        for($t=1;$t<=20;$t++)
            if ($table[$t] > $myRating)
                break;

        $level = $t;

        return $level;
    }

    function getXPmin($level){
        $table[0] = 1300;
        for ($t=1;$t<=20;$t++)
            $table[$t] = $table[$t-1] + $t*50;

        return $table[$level-1];
    }

    function getXPmax($level){
        $table[0] = 1300;
        for ($t=1;$t<=20;$t++)
            $table[$t] = $table[$t-1] + $t*50;

        return $table[$level];
    }

    function grava_log($arq,$x,$path = "/var/log/php"){

       $agora = date("d/m/Y H:i:s");
       $x= "$agora # ".$x."\n";

       $fp = fopen("$path/$arq","a");
       fwrite($fp,$x);
       fclose($fp);

        return;
    }


?>
