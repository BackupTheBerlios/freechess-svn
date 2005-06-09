<?php
##############################################################################################
#                                                                                            #
#                                chessdb.php
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
/* these functions are used to interact with the DB */
include_once('tournaments_functions.php');//eventually we'll takle care of this $41T ..


    function get_record($player,$opponent){

        $vitorias=0;
        $derrotas=0;
        $empates=0;
        $ativos=0;

        $p2 = mysql_query("select * from {$db_prefix}games where ((white_player='".$player."' AND black_player='".$opponent."') OR (white_player='".$opponent."' AND black_player='".$player."'))");
        while ($row = mysql_fetch_array($p2)){

            if ($row['status']== '')
                $ativos++;
            else if ($row['status'] == "playerResigned" && $row['message_from'] == "white" && $player != $row['white_player'])
                $vitorias++;
            else if ($row['status'] == "playerResigned" && $row['message_from'] == "black" && $player != $row['black_player'])
                $vitorias++;
            else if ($row['status'] == "playerResigned" && $row['message_from'] == "white" && $player != $row['black_player'])
                $derrotas++;
            else if ($row['status'] == "playerResigned" && $row['message_from'] == "black" && $player != $row['white_player'])
                $derrotas++;
            else if ($row['status'] == "checkMate" && $row['message_from'] == "white" && $player != $row['white_player'])
                $derrotas++;
            else if ($row['status'] == "checkMate" && $row['message_from'] == "black" && $player != $row['black_player'])
                $derrotas++;
            else if ($row['status'] == "checkMate" && $row['message_from'] == "white" && $player != $row['black_player'])
                $vitorias++;
            else if ($row['status'] == "checkMate" && $row['message_from'] == "black" && $player != $row['white_player'])
                $vitorias++;
            else if ($row['status'] == "draw")
                $empates++;
        }
        $record = "$vitorias-$empates-$derrotas";
        return $record;
    }

    function getRanking($player){
    $r = 1;
    $ranking = "-";
    /*
    // Old ranking
    $p = mysql_query("SELECT distinct rating,firstName,playerID,rating_max from {$db_prefix}games,players where ativo<>'0' and
    oficial='1' AND (status='') AND
    (black_player=playerID OR white_player=PlayerID) order by rating DESC,rating_month DESC");

*/
    $tempo = time()-1209600;

    $p = mysql_query("SELECT rating,firstName,playerID,rating_max,pontos,lastUpdate from {$db_prefix}players where lastUpdate>='$tempo' and ativo<>'0' and
         rating > 0 and pontos>0 order by rating DESC,rating_month DESC");

        while ($row = mysql_fetch_array($p)){
            if ($row['playerID'] == $player){
                $ranking = $r;
                break;
            }
            $r++;
        }

    return $ranking;

    }

function tempoEsgotado($mycolor){
        global $db,$db_prefix, $MSG_LANG, $_SESSION, $isPromoting;

        $p = mysql_query("SELECT * from history where game_id=".$_SESSION['game_id']." ORDER BY timeOfMove DESC limit 1");
        $row = mysql_fetch_array($p);

        $cor = $row['cur_color'];
        $lastmove = $row['timeOfMove'];

        //duracao:
        $v = explode(" ",$lastmove);
        $hora = explode(":",$v[1]);
        $data = explode("-",$v[0]);

        if ($lastmove == 0)
            $inicio = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
        else
            $inicio = mktime($hora[0],$hora[1],$hora[2],$data[1],$data[2],$data[0]);
        $fim = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));

        if ($cor == "white"){
            if ($isPromoting)
                $somawhite = $fim-$inicio;
            else
                $somablack = $fim-$inicio;
        }else{
            if ($isPromoting)
                $somablack = $fim-$inicio;
            else
                $somawhite = $fim-$inicio;
        }


        $p = mysql_query("SELECT * from {$db_prefix}games where game_id=".$_SESSION['game_id']);
        $row = mysql_fetch_array($p);
        if ($row[status] == "")
        {
            if ($row[timelimit] < 1440)
            {
                $row[timeBlack] += $somablack;
                $row[timeWhite] += $somawhite;

                        if ($mycolor == "white")
                        {
                            $timeLimit = $row[timeWhite];
                            $oppcolor = "black";
                        }

                        else
                        {
                           $timeLimit = $row[timeBlack];
                           $oppcolor = "white";
                        }

                if (($row['timelimit']*60) <= $timeLimit)
                    return true;
            }
            else if ($row[timelimit] >= 1440 )
            {
                if ($mycolor == "white")
                    {
                        $timeLimit = $somawhite;
                        $oppcolor = "black";
                    }
                else
                    {
                       $timeLimit = $somablack;
                       $oppcolor = "white";
                    }

                if (($row['timelimit']*60) <= $timeLimit)
                    return true;
            }
            return false;
        }
        }
    function getTrust($player){
         $p2 = mysql_query("select * from {$db_prefix}games where oficial='1' AND timeBlack>0 AND timeWhite>0 AND (gameMEssage='draw' OR status='playerResigned' OR status='checkMate') AND (white_player='".$player."' OR black_player='".$player."')");
        $vitorias=0;
        $fastGame=0;

        while ($row = mysql_fetch_array($p2)){
            if (($row['status'] == "playerResigned" && $row['message_from'] == "white" && $player != $row['white_player'])||
                ($row['status'] == "playerResigned" && $row['message_from'] == "black" && $player != $row['black_player'])||
               ($row['status'] == "checkMate" && $row['message_from'] == "white" && $player != $row['black_player'])||
               ($row['status'] == "checkMate" && $row['message_from'] == "black" && $player != $row['white_player'])||
               ($row['status'] == "draw")
               ){

                //($row['status'] == "playerResigned" && $row['message_from'] == "white" && $player != $row['black_player'])||
               //($row['status'] == "playerResigned" && $row['message_from'] == "black" && $player != $row['white_player'])||
               //($row['status'] == "checkMate" && $row['message_from'] == "white" && $player != $row['white_player'])||
               //($row['status'] == "checkMate" && $row['message_from'] == "black" && $player != $row['black_player'])

                $vitorias++;

                if ($row['white_player'] == $player){
                    $p3 = mysql_query("select * FROM history WHERE cur_color='white' AND game_id='$row[game_id]'");
                    $moves = mysql_num_rows($p3);

                    if ($row['timeWhite']/$moves <= 3600)
                        $fastGame++;
                }else{
                    $p3 = mysql_query("select * FROM history WHERE cur_color='black' AND game_id='$row[game_id]'");
                    $moves = mysql_num_rows($p3);

                     if ($row['timeBlack']/$moves <= 3600)
                        $fastGame++;
                }
            }
        }
        $ratio = round($fastGame/$vitorias*100);
        $trustR=$ratio;
        if ($trustR<=10)
            $trust=0;
        else if($trustR>10 && $trustR<=20)
            $trust=1;
        else if($trustR>20 && $trustR<=40)
            $trust=2;
        else if($trustR>40 && $trustR<=60)
            $trust=3;
        else if($trustR>60 && $trustR<=80)
            $trust=4;
        else if($trustR>80 && $trustR<=100)
            $trust=5;

        $r->ratio = $ratio;
        $r->trust = $trust;
        return $r;
    }


    function getXPW($winnerRating, $loserRating, $PVP){
    ////////////////////////////////////////////////////////
    // Calculating the new Ratings
    // New Code by Helge Dietert ( winterfrost ) with help from Tilko Dietert ( tilko )
    // Implemented after ELO http://www.schachklub-hietzing.at/elosystem.html
    // Assume that every player has a rating, because every new player has a
    // initial rating of 1500. This behaviour does not match the official rules.
    // Perhaps set no rating as 0 and modify code.
    // Because this is an adaptive system the minimum condition must be reduced to 1.
    // Because theirs no way to get the number of games the factor will not be
    // reduced.
    ////////////////////////////////////////////////////////

    // Factors for the winner and loser
    $Factor = ( (double) 3400 - $winnerRating ) * ( (double) 3400 - $winnerRating ) / 100000.0;

        // Difference of Ratings
    $Diff = $winnerRating-$loserRating;

    // Expectation of Result
    $Exp  = 0.5
        + 0.001 * $Diff * ( 1.4217 - 0.001 * abs($Diff) * ( 0.24336
        + 0.001 * abs($Diff) * ( 2.514 - abs($Diff) * 0.001991)));
    if ( $Exp > 1 ) $Exp = 1; // Maximal Result is 1
    if ( $Exp < 0 ) $Exp = 0; // Minimal Result is 0

    // Set xpw ( number of won points ) ans xpl ( number of lost points )
    // both are the absolute value!
    return floor( ( 1 - $Exp ) * $Factor + 0.5 );

    /////////////////////////////////////////////////////////////////
    // End of new rating code
    /////////////////////////////////////////////////////////////////

    /* old version
    $dif1 = $loserRating-$winnerRating;
    if ($dif1>300)$dif1=300;
    if ($dif1<-300)$dif1=-300;
    $FOP = ($PVP+$dif1)/10;
    $xpw = floor(($FOP+30)/2);
    $NewRatingG = $winnerRating + $xpw;

    return $xpw;*/
}

function getXPL($winnerRating, $loserRating, $PVG){
    ////////////////////////////////////////////////////////
    // Calculating the new Ratings
    // New Code by Helge Dietert ( winterfrost ) with help from Tilko Dietert ( tilko )
    // Implemented after ELO http://www.schachklub-hietzing.at/elosystem.html
    // Assume that every player has a rating, because every new player has a
    // initial rating of 1500. This behaviour does not match the official rules.
    // Perhaps set no rating as 0 and modify code.
    // Because this is an adaptive system the minimum condition must be reduced to 1.
    // Because theirs no way to get the number of games the factor will not be
    // reduced.
    ////////////////////////////////////////////////////////

    // Factors for the winner and loser
    $Factor = ( (double) 3400 - $loserRating ) * ( (double) 3400 - $loserRating ) / 100000.0;

    // Difference of Ratings
    $Diff = $loserRating-$winnerRating;

    // Expectation of Result
    $Exp  = 0.5
    + 0.001 * $Diff * ( 1.4217 - 0.001 * abs($Diff) * ( 0.24336
        + 0.001 * abs($Diff) * ( 2.514 - abs($Diff) * 0.001991)));
    if ( $Exp > 1 ) $Exp = 1; // Maximal Result is 1
    if ( $Exp < 0 ) $Exp = 0; // Minimal Result is 0

    // Set xpw ( number of won points ) ans xpl ( number of lost points )
    // both are the absolute value!
    return floor( ( 0 - $Exp ) * $Factor * (-1) - 0.5 );

    /////////////////////////////////////////////////////////////////
    // End of new rating code
    /////////////////////////////////////////////////////////////////

}


    function countRating($playerID){
        $p = mysql_query("SELECT * from {$db_prefix}games where oficial='1' AND (white_player='".$playerID."' OR black_player='".$playerID."') AND status<>'' AND  status<>'playerInvited' AND status<>'inviteDeclined'");
        $rating = 1500;
        while($row = mysql_fetch_array($p)){

            if ($row['status'] == "playerResigned" && $row['message_from'] == "white" && $playerID == $row['white_player'])
                 $situacao = "lost";
            else if ($row['status'] == "playerResigned" && $row['message_from'] == "black" && $playerID == $row['black_player'])
                 $situacao = "lost";
            else if ($row['status'] == "playerResigned" && $row['message_from'] == "white" && $playerID == $row['black_player'])
                 $situacao = "win";
            else if ($row['status'] == "playerResigned" && $row['message_from'] == "black" && $playerID == $row['white_player'])
                 $situacao = "win";
            else if ($row['status'] == "checkMate" && $row['message_from'] == "white" && $playerID == $row['white_player'])
                 $situacao = "win";
            else if ($row['status'] == "checkMate" && $row['message_from'] == "black" && $playerID == $row['black_player'])
                 $situacao = "win";
            else if ($row['status'] == "checkMate" && $row['message_from'] == "white" && $playerID == $row['black_player'])
                 $situacao = "lost";
            else if ($row['status'] == "checkMate" && $row['message_from'] == "black" && $playerID == $row['white_player'])
                 $situacao = "lost";
            else if ($row['status'] == "draw")
                 $situacao = "draw";

            if($situacao == "win"){
                $rating += $row[xpw];
            }
            else if($situacao == "lost"){
                if ($row[xpl] > 0)$rating -= $row[xpl];
                else $rating += abs($row[xpl]);
            }
            else if($situacao == "draw"){
                if ($row['message_from'] == "white" && $playerID == $row['white_player'])
                    $rating += $row[xpw];
                else if ($row['message_from'] == "white" && $playerID == $row['black_player'])
                    $rating += $row[xpl];
                else if ($row['message_from'] == "black" && $playerID == $row['black_player'])
                    $rating += $row[xpw];
                else if ($row['message_from'] == "black" && $playerID == $row['white_player'])
                     $rating += $row[xpl];
            }

        }

        return $rating;
    }

    function saveRanking($game_id,$action,$playersColor,$flagFall=0){
        GLOBAL $CFG_MIN_ROUNDS,$CFG_LOG_PATH,$CFG_LOG_DEBUG,$CFG_ENABLE_TRIAL_RATING;

          if ($playersColor == "white")
            $opponentColor = "black";
        else
            $opponentColor = "white";

        $p = mysql_query("SELECT count(*) FROM history WHERE game_id='$game_id'");
        $row = mysql_fetch_array($p);
        $xRounds = ceil($row[0]/2);

        $p = mysql_query("SELECT white_player,black_player,dateCreated,ratingWhite,ratingBlack,ratingBlackM,ratingWhiteM,oficial,PVBlack,PVWhite,status,teamMatch FROM games WHERE game_id='$game_id'");
        $row = mysql_fetch_array($p);
        $xDateCreated = $row[2];
        $official = $row['oficial'];
        $Match = $row['teamMatch'];

        if ($row[status] != "")
            displayError("This game was already finished!","mainmenu.php");

        if ($playersColor == "white"){
            $xplayerID = $row[0];
            $xopponentID = $row[1];
            $white_player=$row[0];
            $black_player=$row[1];
            if ($row['PVWhite'] >=0 &&  $row['PVBlack'] >=0){
                $PVplayer = $row['PVWhite'];
                $PVopponent = $row['PVBlack'];
            }
            else{
                $PVplayer = getPV($row[0]);
                $PVopponent = getPV($row[1]);
            }
            if ($row['ratingWhite'] >0 &&  $row['ratingWhiteM'] >0 && $row['ratingBlack'] >0 && $row['ratingBlackM'] >0){
                $xplayerRating = $row['ratingWhite'];
                //$xplayerRating = round((($row['ratingWhite'] + getRating($xplayerID))/2),4);
                //$xplayerRatingM = $row['ratingBlackM'];
                $xopponentRating = $row['ratingBlack'];
                //$xopponentRating = round((($row['ratingBlack'] + getRating($xopponentID))/2),4);
                //$xopponentRatingM = $row['ratingWhiteM'];
           }else{
                $xplayerRating = getRating($xplayerID);
                //$xplayerRatingM = getRatingMonth($xplayerID);
                $xopponentRating = getRating($xopponentID);
                //$xopponentRatingM = getRatingMonth($xopponentID);
            }
        }else{
            $xopponentID = $row[0];
            $xplayerID = $row[1];
            $white_player=$row[1];
            $black_player=$row[0];
            if ($row['PVWhite'] >=0 &&  $row['PVBlack'] >=0){
                $PVplayer = $row['PVBlack'];
                $PVopponent = $row['PVWhite'];
            }
            else{
                $PVplayer = getPV($row[1]);
                $PVopponent = getPV($row[0]);
            }
            if ($row['ratingWhite'] >0 &&  $row['ratingWhiteM'] >0 && $row['ratingBlack'] >0 && $row['ratingBlackM'] >0){
                $xplayerRating = $row['ratingBlack'];
                //$xplayerRating = round((($row['ratingBlack'] + getRating($xplayerID))/2),4);
                //$xplayerRatingM = $row['ratingBlackM'];
                $xopponentRating = $row['ratingWhite'];
                //$xopponentRating = round((($row['ratingWhite'] + getRating($xopponentID))/2),4);
                //$xopponentRatingM = $row['ratingWhiteM'];
            }else{
                $xplayerRating = getRating($xplayerID);
                //$xplayerRatingM = getRatingMonth($xplayerID);
                $xopponentRating = getRating($xopponentID);
                //$xopponentRatingM = getRatingMonth($xopponentID);
           }
        }

        mysql_query("delete from {$db_prefix}messages where game_id=".$game_id);
        mysql_query("delete from chat where game_id=".$game_id);

        if ($action == "draw"){
            $pointsw = 1;
            $pointsl = 1;
            $sum = 15;
            $message = "draw";

            $winner = $xplayerID;
            $loser = $xopponentID;
            $winnerRating = $xplayerRating;
            $loserRating = $xopponentRating;
            $PVG = $PVplayer;
            $PVP = $PVopponent;

        }else if($action == "resign"){
            $pointsw = 2;
            $pointsl = 0;
            $sum = 30;
            $message = "playerResigned";
            $winner = $xopponentID;
            $loser = $xplayerID;
            $winnerRating = $xopponentRating;
            $loserRating = $xplayerRating;
            $PVG = $PVopponent;
            $PVP = $PVplayer;
        }else if($action == "checkmate"){
            $pointsw = 2;
            $pointsl = 0;
            $sum = 30;
            $message = "checkMate";
            $winner = $xplayerID;
            $loser = $xopponentID;
            $winnerRating = $xplayerRating;
            $loserRating = $xopponentRating;
            $PVG = $PVplayer;
            $PVP = $PVopponent;
        }

        if($official == "1" && ($xRounds>=$CFG_MIN_ROUNDS || $action == "checkmate")){

            $RatingG = getRating($winner);
            $RatingP = getRating($loser);
            ////////////////////////////////////////////////////////
            // Calculating the new Ratings
            // New Code by Helge Dietert ( winterfrost ) with help from Tilko Dietert ( tilko )
            // Implemented after ELO http://www.schachklub-hietzing.at/elosystem.html
            // Assume that every player has a rating, because every new player has a
            // initial rating of 1500. This behaviour does not match the official rules.
            // Perhaps set no rating as 0 and modify code.
            // Because this is an adaptive system the minimum condition must be reduced to 1.
            // New variables are prefixed with rh
            ////////////////////////////////////////////////////////

            // Number of games
        $rhStatsG = getStatsUser($winner,1);
            $rhGNumberGames = $rhStatsG[0]+$rhStatsG[1]+$rhStatsG[2];
        $rhStatsP = getStatsUser($loser,1);
            $rhPNumberGames = $rhStatsP[0]+$rhStatsP[1]+$rhStatsP[2];

            // Factors for the winner and loser
            $rhGFactor = ( (double) 3400 - $RatingG  ) * ( (double) 3400 - $RatingG  ) / 100000.0;
            $rhPFactor = ( (double) 3400 - $RatingP  ) * ( (double) 3400 - $RatingP  ) / 100000.0;
            // Reduce factor ?
            //if ($rhGNumberGames > 850.0 / $rhGFactor)
            //$rhGFactor = 850.0 / $rhGNumberGames;
            //if ($rhPNumberGames > 850.0 / $rhPFactor)
            //$rhPFactor = 850.0 / $rhPNumberGames;

            // Difference of Ratings
            $rhDiffGP = $RatingG-$RatingP;

            // Expectation of Result
            $rhExG  = 0.5
                + 0.001 * $rhDiffGP * ( 1.4217 - 0.001 * abs($rhDiffGP) * ( 0.24336
                    + 0.001 * abs($rhDiffGP) * ( 2.514 - abs($rhDiffGP) * 0.001991)));
            if ( $rhExG > 1 ) $rhExG = 1; // Maximal Result is 1
            if ( $rhExG < 0 ) $rhExG = 0; // Minimal Result is 0
            $rhExP = 1 - $rhExG;

            // real Result
            $rhReG = 1;
            $rhReP = 0;
            if ( $action == "draw" )
            {
              $rhReG = 0.5;
              $rhReP = 0.5;
            }

            // Set xpw ( number of won points ) ans xpl ( number of lost points )
            // xpl is the number of lost point, so if he lost points the value must be positiv
            $xpw = floor( ( $rhReG - $rhExG ) * $rhGFactor + 0.5 );
            $xpl = floor( ( $rhReP - $rhExP ) * $rhPFactor + 0.5 );

            // New Ratings
        $NewRatingG = $RatingG + $xpw;
            if ($action == "draw" )
            {
                 $NewRatingP = $RatingP + $xpl;
            }
            else
            {
                 $xpl = $xpl * (-1); // Because if he'll lose points this value is already negativ
                 $NewRatingP = $RatingP - $xpl;
            }

            // Minimum points at 900
            if ( $NewRatingP < 900 )
            {
                 $NewRatingP = 900;
                 $xpl = $RatingP - 900;
            }

            /////////////////////////////////////////////////////////////////
            // End of new rating-code
            /////////////////////////////////////////////////////////////////

            /* Old Version

            $RatingG = getRating($winner);
            $RatingP = getRating($loser);
            $dif1 = $loserRating-$winnerRating;
            $dif2 = $winnerRating-$loserRating;

            if ($dif1>300)$dif1=300;
            if ($dif1<-300)$dif1=-300;
            if ($dif2>300)$dif2=300;
            if ($dif2<-300)$dif2=-300;

            if ($action == "draw")
                $FOG = ($PVG+$dif2)/10;
            else
                $FOG = ($PVG+$dif1)/10;
            $FOP = ($PVP+$dif1)/10;

            $xpw = floor(($FOP+$sum)/2);
            $xpl = floor(($FOG+$sum)/2);

            if ($action == "draw"){
                if ($xpw<0)$xpw=0;
                if ($xpl<0)$xpl=0;
            }else{
                if ($xpw == 0)$xpw=1;
                if ($xpl == 0)$xpl=1;
            }

            $NewRatingG = $RatingG + $xpw;
            if ($action == "draw")
                $NewRatingP = $RatingP + $xpl;
            else
                $NewRatingP = $RatingP - $xpl;

            if ($NewRatingP < 900){
                $xpl = $RatingP - 900;
                $NewRatingP = 900;
            }
            if ($NewRatingG < 900){
                $xpw = $RatingG - 900;
                $NewRatingG = 900;
            }*/

            # Monthly
            $NewRatingGm = $NewRatingG;
            $NewRatingPm = $NewRatingP;

         /*
            $RatingGm = getRatingMonth($winner);
            $RatingPm = getRatingMonth($loser);
            $dif1m = $loserRatingM-$winnerRatingM;
            $dif2m = $winnerRatingM-$loserRatingM;
            if ($dif1m>300)$dif1m=300;
            if ($dif1m<-300)$dif1m=-300;
            if ($dif2m>300)$dif2m=300;
            if ($dif2m<-300)$dif2m=-300;
            if ($action == "draw")
                $FOGm = ($PVG+$dif2m)/10;
                    else
                $FOGm = ($PVG+$dif1m)/10;
                $FOPm = ($PVP+$dif1m)/10;
            $xpwm = floor(($FOPm+$sum)/2);
            $xplm = floor(($FOGm+$sum)/2);
            $NewRatingGm = $RatingGm + $xpwm;
        if ($action == "draw")
                $NewRatingPm = $RatingPm + $xplm;
            else
                $NewRatingPm = $RatingPm - $xplm;

            if ($NewRatingPm < 1300)
                $xplm = $RatingPm - 1300;
            if ($NewRatingGm < 1300)
                $xpwm = $RatingGm - 1300;

        */

            $maxG = getMaxRating($winner);
            $maxP = getMaxRating($loser);
            if ($NewRatingG > $maxG)
               $maxG = $NewRatingG;
            if ($NewRatingP > $maxP)
               $maxP = $NewRatingP;

            if ($NewRatingG < 900 || $NewRatingP < 900 || $xpw < -100 || $xpw > 100 || $xpl < -100 || $xpl > 100){
                $msg = "Fatal Error at chessdb!<BR><BR>";
                $msg .= "
                Game ID: $game_id<BR>
                Winner ID: $winner<BR>
                Loser ID: $loser<BR>
                Winner Rating: $RatingG<BR>
                Loser Rating: $RatingP<BR>
                X Winner Rating:: $winnerRating<BR>
                X Loser Rating: $loserRating<BR>
                Winner's % Victories: $PVG<BR>
                Loser's % Victories: $PVP<BR>
                DIF1: $dif1<BR>
                DIF2: $dif2<BR>

                FOG: $FOG<BR>
                FOP: $FOP<BR>
                Winner's new Rating: $NewRatingG<BR>
                Loser's new Rating: $NewRatingP<BR>
                Winner's earned: $xpw<BR>
                Loser's lost: $xpl<BR><BR>
                ";
                $log = "\"Faltal Error\" \"Game: $game_id\" \"Winner: $winner/$xpw/$NewRatingG\" \"Loser: $loser/$xpl/$NewRatingP\"";
                if ($CFG_LOG_PATH =! "" && $CFG_LOG_DEBUG)
                       grava_log("webchess.log",$log,$CFG_LOG_PATH);

                displayError($msg,"mainmenu.php");
            }

            mysql_query("UPDATE {$db_prefix}players SET pontos=pontos+$pointsw WHERE playerID='$winner'");
            mysql_query("UPDATE {$db_prefix}players SET pontos=pontos+$pointsl WHERE playerID='$loser'");

    //If the player has a team, save the points to the team:
   $tpt = mysql_query("SELECT * FROM games WHERE team > '0' AND ((white_player = '$winner' OR black_player = '$winner') AND (white_player = '$loser' OR black_player = '$loser')) AND status = ''");
   $t2 = mysql_query("SELECT * FROM games WHERE teamMatch > '0' AND ((white_player = '$winner' OR black_player = '$winner') AND (white_player = '$loser' OR black_player = '$loser')) AND status = ''");

    if (mysql_num_rows($t2) >0 && mysql_num_rows($tpt) ==0){
        $t3 = mysql_query("select * from team_members where fk_player='$winner' and level>'0'");
            if (mysql_num_rows($t3) >0){
               $rt = mysql_fetch_array($t);
           mysql_query("UPDATE team set points=points+$pointsw WHERE teamID='$rt[fk_team]'");
        }
        }

   if (mysql_num_rows($tpt) >= 1) {
   $gt = mysql_fetch_array($tpt);

            $t = mysql_query("select * from team_members where fk_player='$winner' and level>'0'");
            if (mysql_num_rows($t) >0){
               $rt = mysql_fetch_array($t);

               mysql_query("UPDATE team set points=points+$pointsw WHERE teamID='$rt[fk_team]'");

               mysql_query("UPDATE matches set match_points1=match_points1+$pointsw where team1='$rt[fk_team]' and match_id='$gt[team]' and status=''");
               //mysql_query("UPDATE matches set match_points1=match_points1+$pointsw where team1='$rt[fk_team]' and match_id='$gt[team]' and status=''");

               mysql_query("UPDATE matches set match_points2=match_points2+$pointsw where team2='$rt[fk_team]' and match_id='$gt[team]' and status=''");
               //mysql_query("UPDATE matches set match_points2=match_points2+$pointsw where team2='$rt[fk_team]' and match_id='$gt[team]' and status=''");

      }

   }

            mysql_query("UPDATE {$db_prefix}players SET rating_max='$maxG',rating_month=$NewRatingGm,rating='$NewRatingG' WHERE playerID='$winner'")
            or die("Update Error");
            mysql_query("UPDATE {$db_prefix}players SET rating_max='$maxP',rating_month=$NewRatingPm,rating='$NewRatingP' WHERE playerID='$loser'")
            or die("Update Error");
            mysql_query("UPDATE games SET flagFall='$flagFall', status = '$message', message_from = '".$playersColor."', xpl=$xpl, xpw=$xpw  WHERE game_id = ".$game_id)
            or die("Update Error");;

            $log = "\"Saving Ranking\" \"Game: $game_id\" \"Winner: $winner/$xpw/$NewRatingG\" \"Loser: $loser/$xpl/$NewRatingP\"";
            if ($CFG_LOG_PATH =! "" && $CFG_LOG_DEBUG)
               grava_log("webchess.log",$log,$CFG_LOG_PATH);

            }

        else{
            mysql_query("UPDATE games SET flagFall='$flagFall', status = '$message', message_from = '".$playersColor."' WHERE game_id = ".$game_id);


            if ($CFG_ENABLE_TRIAL_RATING){
                /*
                ## Rating Trial Validation System ##
                1. Check if the user has in rating trial:
                2. Count the number of games
                3. Know the number of victories
                4. Check if has at least 5 games
                5. Give the rating
                */

                if (getRating($xplayerID) == 0){
                    $player = $xplayerID;
                    $stats = getStatsUser($player,0);
                    $total = $stats[0]+$stats[1]+$stats[2];
                    if ($total >=5){
                        $initRating = 1300;
                        $newRating = $initRating + ($stats[0]*50);
                        mysql_query("UPDATE {$db_prefix}players SET rating_max='$newRating',rating_month=$newRating,rating='$newRating' WHERE playerID='$player'");
                    }
                }

                if (getRating($xopponentID) == 0){
                    $player = $xopponentID;
                    $stats = getStatsUser($player,0);
                    $total = $stats[0]+$stats[1]+$stats[2];
                    if ($total >=5){
                        $initRating = 1300;
                        $newRating = $initRating + ($stats[0]*50);
                        mysql_query("UPDATE {$db_prefix}players SET rating_max='$newRating',rating_month=$newRating,rating='$newRating' WHERE playerID='$player'");
                    }
                }
                //echo "$player: T:$total V:$stats[0] R:$newRating<BR>";
            }

        }

                 // NEW!!!

                 save_tournament($game_id, $winner);
                 save_match($game_id);
    }

    function getDifficult($first,$second){
    GLOBAL $MSG_LANG;

        $hislevel = getPlayerLevel($second);
        $mylevel = getPlayerLevel($first);
        $diff = $mylevel - $hislevel;

        if ($diff <= -4)
            $dificuldade = $MSG_LANG["impossible"];
        else if ($diff <= -2)
            $dificuldade = $MSG_LANG["verydifficult"];
        else if ($diff >= 2)
            $dificuldade = $MSG_LANG["veryeasy"];
        else if($diff == 1)
            $dificuldade= $MSG_LANG["easy"];
        else if($diff == -1)
            $dificuldade = $MSG_LANG["difficult"];
        else if($diff == 0)
            $dificuldade = $MSG_LANG["normal"];


        return $dificuldade;
    }

    function getStatsUser($player, $oficial = 1){

        $vitorias=0;
        $derrotas=0;
        $empates=0;
        $ativos=0;
        $invited=0;

        $p2 = mysql_query("select * from {$db_prefix}games where oficial='$oficial' AND (white_player='".$player."' OR black_player='".$player."')");

        while ($row = mysql_fetch_array($p2)){

            if ($row['status']== '')
                $ativos++;
            else if ($row['status'] == "playerResigned" && $row['message_from'] == "white" && $player != $row['white_player'])
                $vitorias++;
            else if ($row['status'] == "playerResigned" && $row['message_from'] == "black" && $player != $row['black_player'])
                $vitorias++;
            else if ($row['status'] == "playerResigned" && $row['message_from'] == "white" && $player != $row['black_player'])
                $derrotas++;
            else if ($row['status'] == "playerResigned" && $row['message_from'] == "black" && $player != $row['white_player'])
                $derrotas++;
            else if ($row['status'] == "checkMate" && $row['message_from'] == "white" && $player != $row['white_player'])
                $derrotas++;
            else if ($row['status'] == "checkMate" && $row['message_from'] == "black" && $player != $row['black_player'])
                $derrotas++;
            else if ($row['status'] == "checkMate" && $row['message_from'] == "white" && $player != $row['black_player'])
                $vitorias++;
            else if ($row['status'] == "checkMate" && $row['message_from'] == "black" && $player != $row['white_player'])
                $vitorias++;
            else if ($row['status'] == "draw")
                $empates++;
            else if($row['status'] == "playerInvited")
                $invited++;
        }

        $stats[0] = $vitorias;
        $stats[1] = $derrotas;
        $stats[2] = $empates;
        $stats[3] = $ativos;
        $stats[4] = $invited;
        return $stats;
    }

    function getTeamRating($team){
        $p = mysql_query("select * from team_members where fk_team='$team' AND level>0");
        $total = mysql_num_rows($p);
        $ratings=0;
        while ($row = mysql_fetch_array($p))
            $ratings += getRating($row['fk_player']);

        return round($ratings/$total,2);
    }

    function getTeamAvgPoints($team){
        $p = mysql_query("select * from team_members where fk_team='$team' AND level>0");
        $total = mysql_num_rows($p);
        $ratings=0;
        while ($row = mysql_fetch_array($p)){
            $p2 = mysql_query("select * from {$db_prefix}players where playerID='$row[fk_player]'");
            $r = mysql_fetch_array($p2);
            $ratings+=$r[pontos];
        }

        return round($ratings/$total,2);
    }


    function getRating($player){
        $p = mysql_query("select rating from {$db_prefix}players where playerID='$player'");
        $row = mysql_fetch_array($p);
        return $row[0];
    }

    function getMatchTimeLimit($match_id){
       $p = mysql_query("SELECT adj_time FROM matches WHERE match_id= '$match_id'");
       $row = mysql_fetch_array($p);
       return $row[0];
    }
    function getRatingMonth($player){
        $p = mysql_query("select rating_month from {$db_prefix}players where playerID='$player'");
        $row = mysql_fetch_array($p);
        return $row[0];
    }


    function getMaxRating($player){
        $p = mysql_query("select rating_max from {$db_prefix}players where playerID='$player'");
        $row = mysql_fetch_array($p);
        return $row[0];
    }

    function getPV($player){
        $stats = getStatsUser($player,1);
            $vitorias = $stats[0];
            $derrotas = $stats[1];
            $empates = $stats[2];
            $ativos = $stats[3];
            $total = $vitorias+$derrotas+$empates;

        if ($total>0)
            return round($vitorias/$total*100,2);
        else
            return 0;
    }

    function updateTimestamp()
    {
        /* old PHP versions don't have _POST, _GET and _SESSION as auto_globals */
        if (!minimum_version("4.1.0"))
            global $_POST, $_GET, $_SESSION;

        mysql_query("UPDATE games SET lastMove = NOW(), reminder = '0' WHERE game_id = ".$_SESSION['game_id']);

    }

    function loadHistory()
    {
        global $history, $numMoves,$db,$db_prefix;

        $allMoves = mysql_query("SELECT * FROM {$db_prefix}history WHERE game_id = ".$_SESSION['game_id']." ORDER BY time_of_move");

        $numMoves = -1;
        while ($thisMove = mysql_fetch_array($allMoves, MYSQL_ASSOC))
        {
            $numMoves++;
            $history[$numMoves] = $thisMove;
        }
    }

    function savePromotion()
    {
        global $db,$db_prefix, $history, $numMoves, $is_in_check;

        /* old PHP versions don't have _POST, _GET and _SESSION as auto_globals */
        if (!minimum_version("4.1.0"))
            global $_POST, $_GET, $_SESSION;

        if ($is_in_check)
        {
            $tmpIsInCheck = 1;
            $history[$numMoves]['is_in_check'] = 1;
        }
        else
            $tmpIsInCheck = 0;

        $history[$numMoves]['promotedTo'] = getPieceName($_POST['promotion']);

        $tmpQuery = "UPDATE {$db_prefix}history SET time_of)move=NOW(), promoted_to = '".getPieceName($_POST['promotion'])."', is_in_check = ".$tmpIsInCheck." WHERE game_id = ".$_SESSION['game_id']." AND timeOfMove = '".$history[$numMoves]['timeOfMove']."'";
        mysql_query($tmpQuery);

        updateTimestamp();

        /* if email notification is activated and move does not result in a pawn's promotion... */
        if ($CFG_USEEMAILNOTIFICATION)
        {
            if ($history[$numMoves]['replaced'] == null)
                $tmpReplaced = '';
            else
                $tmpReplaced = $history[$numMoves]['replaced'];

            /* get opponent's color */
            if (($numMoves == -1) || ($numMoves % 2 == 1))
                $oppColor = "black";
            else
                $oppColor = "white";

            /* get opponent's player ID */
            if ($oppColor == 'white')
                $tmpOpponentID = mysql_query("SELECT white_player FROM games WHERE game_id = ".$_SESSION['game_id']);
            else
                $tmpOpponentID = mysql_query("SELECT black_player FROM games WHERE game_id = ".$_SESSION['game_id']);

            $opponentID = mysql_result($tmpOpponentID, 0);

            /* if opponent is using email notification... */
            $tmpOpponentEmail = mysql_query("SELECT value FROM preferences WHERE playerID = ".$opponentID." AND preference = 'emailNotification'");
            if (mysql_num_rows($tmpOpponentEmail) > 0)
            {
                $opponentEmail = mysql_result($tmpOpponentEmail, 0);
                if ($opponentEmail != '')
                {
                    /* get opponent's nick */
                    $tmpOpponentNick = mysql_query("SELECT firstName FROM {$db_prefix}players WHERE playerID = ".$_SESSION['playerID']);
                    $opponentNick = mysql_result($tmpOpponentNick, 0);

                    /* get opponent's prefered history type */
                    $tmpOpponentHistory = mysql_query("SELECT value FROM preferences WHERE playerID = ".$opponentID." AND preference = 'history'");

                    /* default to PGN */
                    if (mysql_num_rows($tmpOpponentHistory) > 0)
                        $opponentHistory = mysql_result($tmpOpponentHistory, 0);
                    else
                        $opponentHistory = 'pgn';

                    /* notify opponent of move via email */
                    if ($opponentHistory == 'pgn')
                        webchessMail('move', $opponentEmail, moveToPGNString($history[$numMoves]['cur_color'], $history[$numMoves]['curPiece'], $history[$numMoves]['from_row'], $history[$numMoves]['from_col'], $history[$numMoves]['to_row'], $history[$numMoves]['to_col'], $tmpReplaced, $history[$numMoves]['promotedTo'], $is_in_check), $opponentNick);
                    else
                        webchessMail('move', $opponentEmail, moveToVerbousString($history[$numMoves]['cur_color'], $history[$numMoves]['curPiece'], $history[$numMoves]['from_row'], $history[$numMoves]['from_col'], $history[$numMoves]['to_row'], $history[$numMoves]['to_col'], $tmpReplaced, $history[$numMoves]['promotedTo'], $is_in_check), $opponentNick);
                }
            }
        }
    }

    function saveHistory($post = "")
    {
        global $db,$db_prefix, $board, $isPromoting, $history, $numMoves, $is_in_check, $CFG_USEEMAILNOTIFICATION;

        if ($post != "")
            $_POST = $post;


        /* set destination row for pawn promotion */
        if ($board[$_POST['from_row']][$_POST['from_col']] & BLACK)
            $targetRow = 0;
        else
            $targetRow = 7;

        /* determine if move results in pawn promotion */
        if ((($board[$_POST['from_row']][$_POST['from_col']] & COLOR_MASK) == PAWN) && ($_POST['to_row'] == $targetRow))
            $isPromoting = true;
        else
            $isPromoting = false;

        /* determine who's playing based on number of moves so far */
        if (($numMoves == -1) || ($numMoves % 2 == 1))
        {
            $cur_color = "white";
            $oppColor = "black";
            $targetRow = 7;
        }
        else
        {
            $cur_color = "black";
            $oppColor = "white";
            $targetRow = 0;
        }

        /* add move to history */
        $numMoves++;
        $history[$numMoves]['game_id'] = $_SESSION['game_id'];
        $history[$numMoves]['cur_piece'] = getPieceName($board[$_POST['from_row']][$_POST['from_col']]);
        $history[$numMoves]['cur_color'] = $cur_color;
        $history[$numMoves]['from_row'] = $_POST['from_row'];
        $history[$numMoves]['from_col'] = $_POST['from_col'];
        $history[$numMoves]['to_row'] = $_POST['to_row'];
        $history[$numMoves]['to_col'] = $_POST['to_col'];
        $history[$numMoves]['promoted_to'] = null;

        if ($is_in_check)
            $history[$numMoves]['is_in_check'] = 1;
        else
            $history[$numMoves]['is_in_check'] = 0;

        /*
        if (DEBUG)
        {
            if ($history[$numMoves]['curPiece'] == '')
                echo ("WARNING!!!  missing piece at ".$_POST['from_row'].", ".$_POST['from_col'].": ".$board[$_POST['from_row']][$_POST['from_col']]."<p>\n");
        }
        */

        if ($board[$_POST['to_row']][$_POST['to_col']] == 0)
        {
            $tmpQuery = "INSERT INTO {$db_prefix}history (time_of_move, game_id, cur_piece, cur_color, from_row, from_col, to_row, to_col, replaced, promoted_to, is_in_check) VALUES (Now(), ".$_SESSION['game_id'].", '".getPieceName($board[$_POST['from_row']][$_POST['from_col']])."', '$cur_color', ".$_POST['from_row'].", ".$_POST['from_col'].", ".$_POST['to_row'].", ".$_POST['to_col'].", null, null, ".$history[$numMoves]['is_in_check'].")";
            $history[$numMoves]['replaced'] = null;
            $tmpReplaced = "";
        }
        else
        {
            $tmpQuery = "INSERT INTO {$db_prefix}history (time_of_move, game_id, cur_piece, cur_color, from_row, from_col, to_row, to_col, replaced, promoted_to, is_in_check) VALUES (Now(), ".$_SESSION['game_id'].", '".getPieceName($board[$_POST['from_row']][$_POST['from_col']])."', '$cur_color', ".$_POST['from_row'].", ".$_POST['from_col'].", ".$_POST['to_row'].", ".$_POST['to_col'].", '".getPieceName($board[$_POST['to_row']][$_POST['to_col']])."', null, ".$history[$numMoves]['is_in_check'].")";

            $history[$numMoves]['replaced'] = getPieceName($board[$_POST['to_row']][$_POST['to_col']]);
            $tmpReplaced = $history[$numMoves]['replaced'];
        }

       $res =  mysql_query($tmpQuery) or die(mysql_error());


        /* if email notification is activated and move does not result in a pawn's promotion... */
        /* NOTE: moves resulting in pawn promotion are handled by savePromotion() above */
        if ($CFG_USEEMAILNOTIFICATION && !$isPromoting)
        {
            /* get opponent's player ID */
            if ($oppColor == 'white')
                $tmpOpponentID = mysql_query("SELECT white_player FROM games WHERE game_id = ".$_SESSION['game_id']);
            else
                $tmpOpponentID = mysql_query("SELECT black_player FROM games WHERE game_id = ".$_SESSION['game_id']);

            $opponentID = mysql_result($tmpOpponentID, 0);

            /* if opponent is using email notification... */
            $tmpOpponentEmail = mysql_query("SELECT value FROM preferences WHERE playerID = ".$opponentID." AND preference = 'emailNotification'");
            if (mysql_num_rows($tmpOpponentEmail) > 0)
            {
                $opponentEmail = mysql_result($tmpOpponentEmail, 0);
                if ($opponentEmail != '')
                {
                    /* get opponent's nick */
                    $tmpOpponentNick = mysql_query("SELECT firstName FROM {$db_prefix}players WHERE playerID = ".$_SESSION['playerID']);
                    $opponentNick = mysql_result($tmpOpponentNick, 0);

                    /* get opponent's prefered history type */
                    $tmpOpponentHistory = mysql_query("SELECT value FROM preferences WHERE playerID = ".$opponentID." AND preference = 'history'");

                    /* default to PGN */
                    if (mysql_num_rows($tmpOpponentHistory) > 0)
                        $opponentHistory = mysql_result($tmpOpponentHistory, 0);
                    else
                        $opponentHistory = 'pgn';

                    /* notify opponent of move via email */
                    if ($opponentHistory == 'pgn')
                        webchessMail('move', $opponentEmail, moveToPGNString($history[$numMoves]['cur_color'], $history[$numMoves]['curPiece'], $history[$numMoves]['from_row'], $history[$numMoves]['from_col'], $history[$numMoves]['to_row'], $history[$numMoves]['to_col'], $tmpReplaced, '', $is_in_check), $opponentNick);
                    else
                        webchessMail('move', $opponentEmail, moveToVerbousString($history[$numMoves]['cur_color'], $history[$numMoves]['curPiece'], $history[$numMoves]['from_row'], $history[$numMoves]['from_col'], $history[$numMoves]['to_row'], $history[$numMoves]['to_col'], $tmpReplaced, '', $is_in_check), $opponentNick);
                }
            }
        }
    }

    function loadGame()
    {
        global $db,$db_prefix, $board, $playersColor, $MSG_LANG,$db_prefix,$db;


        /* clear board data */
        for ($i = 0; $i < 8; $i++)
        {
            for ($j = 0; $j < 8; $j++)
            {
                $board[$i][$j] = 0;
            }
        }

        $pieces = mysql_query("SELECT * FROM {$db_prefix}pieces WHERE game_id = ".$_SESSION['game_id']);
        /* setup board */
        while ($thisPiece = mysql_fetch_array($pieces, MYSQL_ASSOC))
        {
            $board[$thisPiece["row"]][$thisPiece["col"]] = getPieceCode($thisPiece["color"], $thisPiece["piece"]);
        }

        /* get current player's color */
        $tmpQuery = "SELECT white_player, black_player FROM {$db_prefix}games WHERE game_id = ".$_SESSION['game_id'];
        $tmpTurns = mysql_query($tmpQuery);
        $tmpTurn = mysql_fetch_array($tmpTurns, MYSQL_ASSOC);

        if ($tmpTurn['white_player'] == $_SESSION['player_id'])
        {
            $playersColor = "white";
        }
        else
        {
            $playersColor = "black";
        }
    }

    function saveGame()
    {
        global $board, $playersColor,$db,$db_prefix;


        /*  Flag the game 0*/
        mysql_query("UPDATE {$db_prefix}games set game_ok='0' WHERE game_id = ".$_SESSION['game_id']) or die ("error setting game flag");

        /* clear old data */
        mysql_query("DELETE FROM {$db_prefix}pieces WHERE game_id = ".$_SESSION['game_id']);

        /* save new game data */
        /* for each row... */
        for ($i = 0; $i < 8; $i++)
        {
            /* for each col... */
            for ($j = 0; $j < 8; $j++)
            {
                /* if there's a piece at that pos on the board */
                if ($board[$i][$j] != 0)
                {
                    /* updated the database */
                    if ($board[$i][$j] & BLACK)
                    {
                        $tmpColor = "black";
                    }
                    else
                    {
                        $tmpColor = "white";
                    }

                    $tmpPiece = getPieceName($board[$i][$j]);
                    mysql_query("INSERT INTO {$db_prefix}pieces (game_id, color, piece, row, col) VALUES (".$_SESSION['game_id'].", '$tmpColor', '$tmpPiece', $i, $j)");
                }
            }
        }

        /* update lastMove timestamp */
        updateTimestamp();

        //Update players time
        $p = mysql_query("SELECT * from {$db_prefix}history where cur_color<>'$playersColor' AND game_id=".$_SESSION['game_id']." ORDER BY time_of_move DESC limit 1");
        $row = mysql_fetch_array($p);

        $cor = $row['cur_color'];
        $lastmove = $row['time_of_move'];

        if(empty($lastmove))
        {
          $lastmove = "0000-00-00 00:00:00";
        }
        //duracao:
        $v = explode(" ",$lastmove);
        $hora = explode(":",$v[1]);
        $data = explode("-",$v[0]);

        if ($lastmove == 0)
        {
            $inicio = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
        }
        else
        {
            $inicio = mktime($hora[0],$hora[1],$hora[2],$data[1],$data[2],$data[0]);
        }

        $fim = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));

        $dif = $fim-$inicio;

        if ($playersColor == "white")
        {
            mysql_query("UPDATE {$db_prefix}games set time_white=time_white+$dif WHERE game_id=".$_SESSION['game_id']);
        }
        else
        {
            mysql_query("UPDATE {$db_prefix}games set time_black=time_black+$dif WHERE game_id=".$_SESSION['game_id']);
        }
        //OK, we have to set whose turn is next
        $sql = $db->Prepare("select white_player,black_player,whose_turn from {$db_prefix}games WHERE game_id=?");
        $sql_arr = array($_SESSION['game_id']);
        $res = $db->Execute($sql,$sql_arr);
        db_op_result($res,__LINE__,__FILE__);
        $turns = $res->fields;
        if($turns['white_player'] == $_SESSION['player_id'])
        {
            $whos_next = $turns['black_player'];
        }
        else
        {
           $whos_next = $turns['white_player'];
        }
        if($turns['whose_turn'] != $_SESSION['player_id'])
        {
           adminlog(3002,"POSSIBLE CHEAT! ".$turns['whose_turn']." does not match posting player ".$_SESSION['player_id']."in Game ID ".$_SESSION['game_id']);

        }

        //  Flag the game 1 - open it back up again
       $res =  mysql_query("UPDATE {$db_prefix}games set game_ok='1',whose_turn='$whos_next' WHERE game_id = ".$_SESSION['game_id']);


        //for some reason I reach THIS point only when accepting an invitation?? yeesh...
        //obviously I need to seriously re-do the chess board submission from JS ....

    }

    function processMessages()
    {
        global $db,$db_prefix, $MSG_LANG, $CFG_MIN_ROUNDS, $isUndoRequested, $isDrawRequested, $isUndoing, $isGameOver, $isCheckMate, $playersColor, $statusMessage, $CFG_USEEMAILNOTIFICATION, $flagFall;

        $isUndoRequested = false;
        $isGameOver = false;

        if ($playersColor == "white")
            $opponentColor = "black";
        else
            $opponentColor = "white";



        /* queue a request for an undo */
        if(!empty($_POST['requestUndo']))
        {
            $requestUndo = $_POST['requestUndo'];
        }
        else
        {
            $requestUndo = "no";
        }
        if ($requestUndo == "yes")
        {
            /* if the two players are on the same system, execute undo immediately */
            /* NOTE: assumes the two players discussed it live before undoing */
            if ($_SESSION['isSharedPC'])
                $isUndoing = true;
            else
            {
                $tmpQuery = "INSERT INTO {$db_prefix}messages (game_id, msgType, msgStatus, destination) VALUES (".$_SESSION['game_id'].", 'undo', 'request', '".$opponentColor."')";
                mysql_query($tmpQuery);
            }

            //updateTimestamp();
        }
        if(!empty($_POST['requestDraw']))
        {
            $request_draw = $_POST['requestDraw'];
        }
        else
        {
            $request_draw = "no";
        }
        /* queue a request for a draw */
        if ($request_draw == "yes")
        {
            /* if the two players are on the same system, execute Draw immediately */
            /* NOTE: assumes the two players discussed it live before declaring the game a draw */
            if ($_SESSION['isSharedPC'])
            {
                saveRanking($_SESSION['game_id'],"draw",$playersColor);
            }
            else
            {
                $tmpQuery = "INSERT INTO {$db_prefix}messages (game_id, msgType, msgStatus, destination) VALUES (".$_SESSION['game_id'].", 'draw', 'request', '".$opponentColor."')";
                mysql_query($tmpQuery);
            }

            //updateTimestamp();
        }

        /* response to a request for an undo */
        if (isset($_POST['undoResponse']))
        {
            if ($_POST['isUndoResponseDone'] == 'yes')
            {
                if ($_POST['undoResponse'] == "yes")
                {
                    $tmpStatus = "approved";
                    $isUndoing = true;
                    updateTimestamp();
                }
                else
                    $tmpStatus = "denied";

                $tmpQuery = "UPDATE {$db_prefix}messages SET msgStatus = '".$tmpStatus."', destination = '".$opponentColor."' WHERE game_id = ".$_SESSION['game_id']." AND msgType = 'undo' AND msgStatus = 'request' AND destination = '".$playersColor."'";
                mysql_query($tmpQuery);
            }
        }

        /* response to a request for a draw */
        if (isset($_POST['drawResponse']))
        {
            if ($_POST['isDrawResponseDone'] == 'yes')
            {
                if ($_POST['drawResponse'] == "yes")
                {
                    saveRanking($_SESSION['game_id'],"draw",$playersColor);
                    $tmpStatus = "approved";
                    updateTimestamp();
                }
                else
                    $tmpStatus = "denied";

                $tmpQuery = "UPDATE {$db_prefix}messages SET msgStatus = '".$tmpStatus."', destination = '".$opponentColor."' WHERE game_id = ".$_SESSION['game_id']." AND msgType = 'draw' AND msgStatus = 'request' AND destination = '".$playersColor."'";
                mysql_query($tmpQuery);
            }
        }

        /* resign the game */
        if (!empty($_POST['resign']))
        {
            $resigned = $_POST['resign'];
        }
        else
        {
            $resigned = "no";
        }
        if ($resigned == "yes")
        {
            saveRanking($_SESSION['game_id'],"resign",$playersColor);
            updateTimestamp();

            /* if email notification is activated... */
            if ($CFG_USEEMAILNOTIFICATION)
            {
                /* get opponent's player ID */
                if ($playersColor == 'white')
                    $tmpOpponentID = mysql_query("SELECT black_player FROM {$db_prefix}games WHERE game_id = ".$_SESSION['game_id']);
                else
                    $tmpOpponentID = mysql_query("SELECT white_player FROM {$db_prefix}games WHERE game_id = ".$_SESSION['game_id']);

                $opponentID = mysql_result($tmpOpponentID, 0);

                $tmpOpponentEmail = mysql_query("SELECT value FROM {$db_prefix}player_preference WHERE playerID = ".$opponentID." AND preference = 'emailNotification'");

                /* if opponent is using email notification... */
                if (mysql_num_rows($tmpOpponentEmail) > 0)
                {
                    $opponentEmail = mysql_result($tmpOpponentEmail, 0);
                    if ($opponentEmail != '')
                    {
                        /* notify opponent of resignation via email */
                        webchessMail('resignation', $opponentEmail, '', $_SESSION['firstName']);
                    }
                }
            }
        }


        /* ******************************************* */
        /* process queued messages (ie: from database) */
        /* ******************************************* */
        $tmpQuery = "SELECT * FROM {$db_prefix}messages WHERE game_id = ".$_SESSION['game_id']." AND destination = '".$playersColor."'";
        $tmpMessages = mysql_query($tmpQuery);

        while($tmpMessage = mysql_fetch_array($tmpMessages, MYSQL_ASSOC))
        {
            switch($tmpMessage['msgType'])
            {
                case 'undo':
                    switch($tmpMessage['msgStatus'])
                    {
                        case 'request':
                            $isUndoRequested = true;
                            break;
                        case 'approved':
                            $tmpQuery = "DELETE FROM {$db_prefix}messages WHERE game_id = ".$_SESSION['game_id']." AND msgType = 'undo' AND msgStatus = 'approved' AND destination = '".$playersColor."'";
                            mysql_query($tmpQuery);
                            $statusMessage .= $MSG_LANG["undoapproved"].".<br>\n";
                            break;
                        case 'denied':
                            $isUndoing = false;
                            $tmpQuery = "DELETE FROM {$db_prefix}messages WHERE game_id = ".$_SESSION['game_id']." AND msgType = 'undo' AND msgStatus = 'denied' AND destination = '".$playersColor."'";
                            mysql_query($tmpQuery);
                            $statusMessage .= $MSG_LANG["undodenied"].".<br>\n";
                            break;
                    }
                    break;

                case 'draw':
                    switch($tmpMessage['msgStatus'])
                    {
                        case 'request':
                            $isDrawRequested = true;
                            break;
                        case 'approved':
                            $tmpQuery = "DELETE FROM {$db_prefix}messages WHERE game_id = ".$_SESSION['game_id']." AND msgType = 'draw' AND msgStatus = 'approved' AND destination = '".$playersColor."'";
                            mysql_query($tmpQuery);
                            $statusMessage .= $MSG_LANG["drawapproved"].".<br>\n";
                            break;
                        case 'denied':
                            $tmpQuery = "DELETE FROM {$db_prefix}messages WHERE game_id = ".$_SESSION['game_id']." AND msgType = 'draw' AND msgStatus = 'denied' AND destination = '".$playersColor."'";
                            mysql_query($tmpQuery);
                            $statusMessage .= $MSG_LANG["drawdenied"].".<br>\n";
                            break;
                    }
                    break;
            }
        }

        /* requests pending */
        $tmpQuery = "SELECT * FROM {$db_prefix}messages WHERE game_id = ".$_SESSION['game_id']." AND msgStatus = 'request' AND destination = '".$opponentColor."'";
        $tmpMessages = mysql_query($tmpQuery);

        while($tmpMessage = mysql_fetch_array($tmpMessages, MYSQL_ASSOC))
        {
            switch($tmpMessage['msgType'])
            {
                case 'undo':
                    $statusMessage .= $MSG_LANG["undopending"].".<br>\n";
                    break;
                case 'draw':
                    $statusMessage .= $MSG_LANG["drawpending"].".<br>\n";
                    break;
            }
        }

        /* game level status: draws, resignations and checkmate */
        /* if checkmate, update games table */
        if(!empty($_POST['isCheckMate']))
        {
            $checkmated = $_POST['isCheckMate'];
        }
        else
        {
            $checkmated = "no";
        }
        if ($checkmated == 'true')
        {
            saveRanking($_SESSION['game_id'],"checkmate",$playersColor);
        }


        $getbw["black"] = $MSG_LANG["black"];
        $getbw["white"] = $MSG_LANG["white"];
        $getbwO["black"] = $MSG_LANG["white"];
        $getbwO["white"] = $MSG_LANG["black"];


        $tmpQuery = "SELECT status, message_from FROM {$db_prefix}games WHERE game_id = ".$_SESSION['game_id'];
        $tmpMessages = mysql_query($tmpQuery);
        $tmpMessage = mysql_fetch_array($tmpMessages, MYSQL_ASSOC);

        if ($tmpMessage['status'] == "draw")
        {
            $statusMessage .= $MSG_LANG["endindraw"].".<br>\n";
            $isGameOver = true;
        }

        else if (!$flagFall && $tmpMessage['status'] == "playerResigned")
        {
            $statusMessage .= $getbw[$tmpMessage['message_from']]." ".$MSG_LANG["resigned"].".<br>\n";
            $isGameOver = true;
        }
        else if ($tmpMessage['status'] == "checkMate")
        {

            $statusMessage .= $MSG_LANG["checkmate"]."! ".$getbw[$tmpMessage['message_from']]." ".$MSG_LANG["wonthegame"].".<br>\n";
            $isGameOver = true;
            $isCheckMate = true;
        }

        else if ($flagFall && $tmpMessage['status'] == "playerResigned")
        {
            $statusMessage = $MSG_LANG["theflaghasfallen"]."! ".$getbwO[$tmpMessage['message_from']]." ".$MSG_LANG["wonthegame"].".<br>\n";
            $isGameOver = true;
            $isCheckMate = false;
        }

    }

function display_matches_waiting($my_team)

{
GLOBAL $MSG_LANG;
?>
   <form name="JoinMatchForm" action="teams.php" method="post">
   <table border="1" width="100%">
   <tr>
      <th colspan=7>Current Pending Team Matches</th>
   </tr>
   <tr>
        <th><?=$MSG_LANG["opponent"]?></th>
      <th><?=$MSG_LANG["tournamentplayers"]?></th>
      <th>Your Team</th>
      <th>Their Team</th>
      <th><?=$MSG_LANG['created']?></th>
      <th><?=$MSG_LANG["movetimeout"]?></th>
        <th>Join?</th>
   </tr>
<?
   $tmpGames = mysql_query("SELECT matches.*,DATE_FORMAT(dateCreated, '%d/%m/%y %H:%i') as created FROM matches WHERE status = 'waiting' AND (team1 = ".$my_team." OR team2 = ".$my_team.") ORDER BY dateCreated");

   if (mysql_num_rows($tmpGames) == 0)
      echo("<tr><td colspan='8'>There are no team matches waiting!</td></tr>\n");
   else
   {
      while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
      {
         /* get opponent's team */
         if ($tmpGame['team1'] == $my_team)
            $tmpOpponent = mysql_query("SELECT * FROM team WHERE teamID = ".$tmpGame['team2']);
         else
            $tmpOpponent = mysql_query("SELECT * FROM team WHERE teamID = ".$tmpGame['team1']);
         $row = mysql_fetch_array($tmpOpponent);
            $opponent = substr($row[4],0,25);
          /* Opponent */
            echo "<td><a href='stats_team.php?cod=$row[0]'>".$row[1]."</a></td>";

         echo"<td>";
         echo $tmpGame['boards'];

         //get number of players already signed up for match
         $tmp_players = mysql_query("SELECT * FROM match_players WHERE teamID = '$my_team' AND match_id = ".$tmpGame['match_id']);
         $our_players = mysql_num_rows($tmp_players);
         echo "</td><td>";

         //display number of players from my team
         echo "".$our_players." of ".$tmpGame['boards']."<br>";

   while ($pp = mysql_fetch_array($tmp_players)){

   $pn = mysql_query("SELECT * FROM {$db_prefix}players where playerID = '".$pp['playerID']."'");

   $name = mysql_fetch_array($pn);

   echo "".$name['firstName']."<br>";
   }

         $tmp_players = mysql_query("SELECT * FROM match_players WHERE teamID = '".$row[0]."' AND match_id = ".$tmpGame['match_id']);
         $players = mysql_num_rows($tmp_players);
         echo "</td><td>";
         //display number of players from other team
         echo "".$players." of ".$tmpGame['boards']."<br>";

   while ($tp = mysql_fetch_array($tmp_players)){

   $np = mysql_query("SELECT * FROM {$db_prefix}players where playerID = '".$tp['playerID']."'");

   $oname = mysql_fetch_array($np);

   echo "".$oname['firstName']."<br>";
   }

         /* Start Date */


         echo "</td><td>".$tmpGame['created']."</td>";


         //echo "<td>";

               //echo $tmpGame['adj_time'];
               if ($tmpGame[adj_time] == 0)
                    echo "<td bgcolor=white>14 $MSG_LANG[unlimited]</td>\n";
                //else if ($tmpGame[adj_time] < 29)
                    //echo "<td bgcolor=white>$tmpGame[adj_time] $MSG_LANG[unlimited]</td>\n";
                //else if ($tmpGame[timelimit] >29 && <60)
                    //echo "<td>$tmpGame[timelimit] $MSG_LANG[min].</td>\n";
                else if ($tmpGame[adj_time] <60)
                    echo "<td>$MSG_LANG[min] $tmpGame[adj_time] min.</td>\n";
                else if($tmpGame[adj_time] < 1440)
                    echo "<td>$MSG_LANG[hs] ".($tmpGame[adj_time]/60)." hrs.</td>\n";
                else
                    echo "<td>".($tmpGame[adj_time]/24/60)." $MSG_LANG[unlimited]</td>\n";

            echo "<td>";
            // Join match button
            //only show if not yet filled and this player has not yet joined
         $check = mysql_query("SELECT * FROM match_players WHERE playerID = '".$_SESSION['playerID']."' AND match_id = ".$tmpGame['match_id']);
            if ($our_players < $tmpGame['boards'] and mysql_num_rows($check) == 0){
               //show button

            echo "<input type='button' value='Join' onclick=\"joinmatch('".$tmpGame['match_id']."', '$my_team')\">";
         }
         else{echo "<i>&nbsp;</i>";}

         echo"</td></tr>\n";
      }
   }
?>
   </table>
      <input type="hidden" name="match_id" value="">
      <input type="hidden" name="teamID" value="">
         <input type="hidden" name="ToDo" value="JoinMatch">
   </form><BR><BR>
<?
}

function create_match_games($match_id){
   //load up all signed up players and arrange in order
   $tmp_teams = mysql_query("SELECT * FROM matches WHERE match_id = '$match_id'");
   $teams = db_result_to_array($tmp_teams);
   $team1 = $teams[0][1];
   $team2 = $teams[0][2];
   $boards = $teams[0][6];
   //load up player IDs for team 1
   $tmp_players = mysql_query("SELECT playerID FROM match_players WHERE match_id = '$match_id' AND teamID = '$team1'");
   $team1_players = db_result_to_array($tmp_players);
   for ($i=0;$i<$boards;$i++){
      $tmp_player_info = mysql_query("SELECT rating FROM {$db_prefix}players WHERE playerID = ".$team1_players[$i][0]);
      $tmp_rating = mysql_fetch_array($tmp_player_info);
      $rating=$tmp_rating[0];
      $team1_players[$i][1] = $rating;
#      echo "Player team 1, player $i: ".$team1_players[$i][0]." rating = ".$team1_players[$i][1]."<br>";
   }
   usort ($team1_players, 'compare');//sort in rank order

   //load up player IDs for team 2
   $tmp_players = mysql_query("SELECT playerID FROM match_players WHERE match_id = '$match_id' AND teamID = '$team2'");
   $team2_players = db_result_to_array($tmp_players);
   for ($i=0;$i<$boards;$i++){
      $tmp_player_info = mysql_query("SELECT rating FROM {$db_prefix}players WHERE playerID = ".$team2_players[$i][0]);
      $tmp_rating = mysql_fetch_array($tmp_player_info);
      $rating=$tmp_rating[0];
      $team2_players[$i][1] = $rating;
#      echo "Player team 2, player $i: ".$team2_players[$i][0]." rating = ".$team2_players[$i][1]."<br>";
   }
   usort ($team2_players, 'compare');//sort in rank order

   //debug line
#   echo "<br>".$team1_players[0][0]." : ".$team1_players[0][1]."<br>".$team1_players[1][0]." : ".$team1_players[1][1]."<br>".$team2_players[0][0]." : ".$team2_players[0][1]."<br>".$team2_players[1][0]." : ".$team2_players[1][1];


//for each pair, create first game
$tmpColor = 'white';//set first team 1 player to white
for ($i=0;$i<$boards;$i++){
   $tmpQuery = "INSERT INTO {$db_prefix}games (white_player, black_player, status, message_from, dateCreated, lastMove, ratingWhite, ratingBlack, ratingWhiteM, ratingBlackM, oficial, PVBlack, PVWhite, timelimit, teamMatch, team) VALUES (";
   if ($tmpColor == 'white'){
      $white = $team1_players[$i][0];
      $black = $team2_players[$i][0];
      $tmpColor = 'black';
   }else{
      $white = $team2_players[$i][0];
        $black = $team1_players[$i][0];
        $tmpColor = 'white';
    }
   $tmpQuery .= "$white, $black, '', '', NOW(), NOW(),".getRating($white).",".getRating($black).",".getRatingMonth($white).",".getRatingMonth($black).",'1',".getPV($black).",".getPV($white).",".getMatchTimeLimit($match_id).",'1',$match_id)";
   mysql_query($tmpQuery);

   /* if email notification is activated... */
#   if ($CFG_USEEMAILNOTIFICATION){
#      /* if opponent is using email notification... */
#      $tmpOpponentEmail = mysql_query("SELECT value FROM ch_preferences WHERE playerID = ".$_POST['opponent']." AND preference = 'emailNotification'");
#      if (mysql_num_rows($tmpOpponentEmail) > 0){
#         $opponentEmail = mysql_result($tmpOpponentEmail, 0);
#         if ($opponentEmail != ''){
#            /* notify opponent of invitation via email */
#            webchessMail('invitation', $opponentEmail, '', $_SESSION['nick']);
#         }
#      }
#   }

   // setup new board
   $game_id = mysql_insert_id();//   get ID of new game
   $_SESSION['game_id'] = $game_id;
   createNewGame($game_id);
   saveGame();
}
//for each pair, create second game
$tmpColor = 'black';//set first team 1 player to white
for ($i=0;$i<$boards;$i++){
   $tmpQuery = "INSERT INTO {$db_prefix}games (white_player, black_player, status, message_from, dateCreated, lastMove, ratingWhite, ratingBlack, ratingWhiteM, ratingBlackM, oficial, PVBlack, PVWhite, timelimit, teamMatch, team) VALUES (";
   if ($tmpColor == 'black'){
      $black = $team1_players[$i][0];
      $white = $team2_players[$i][0];
      $tmpColor = 'white';
   }else{
      $black = $team2_players[$i][0];
        $white = $team1_players[$i][0];
        $tmpColor = 'black';
    }
    $tmpQuery .= "$white, $black, '', '', NOW(), NOW(),".getRating($white).",".getRating($black).",".getRatingMonth($white).",".getRatingMonth($black).",'1',".getPV($black).",".getPV($white).",".getMatchTimeLimit($match_id).",'1',$match_id)";
   mysql_query($tmpQuery);

   /* if email notification is activated... */
#   if ($CFG_USEEMAILNOTIFICATION){
#      /* if opponent is using email notification... */
#      $tmpOpponentEmail = mysql_query("SELECT value FROM ch_preferences WHERE playerID = ".$_POST['opponent']." AND preference = 'emailNotification'");
#      if (mysql_num_rows($tmpOpponentEmail) > 0){
#         $opponentEmail = mysql_result($tmpOpponentEmail, 0);
#         if ($opponentEmail != ''){
#            /* notify opponent of invitation via email */
#            webchessMail('invitation', $opponentEmail, '', $_SESSION['nick']);
#         }
#      }
#   }

   // setup new board
   $game_id = mysql_insert_id();//   get ID of new game
   $_SESSION['game_id'] = $game_id;
   createNewGame($game_id);
   saveGame();
}
  //update match table
   mysql_query("UPDATE matches SET status = '' WHERE match_id = '$match_id'");
}

function compare($x,$y)
{
//a sorting function that sorts by the value of column indicated by $n
//call with: usort ($array_name, 'compare')
$n = 1;
if ($x[$n] == $y[$n])
   return 0;
else if ($x[$n] < $y[$n])
   return 1; //change to -1 for reverse order
else
   return -1;
}

function db_result_to_array($result)
{
   $res_array = array();
   for($count=0; $row = @mysql_fetch_array($result); $count++)
   {
     $res_array[$count] = $row;
   }
   return $res_array;
}

function save_match($game_id){

$query = ("SELECT * FROM {$db_prefix}games WHERE game_id = '$game_id'");
$sql = mysql_query($query);
$g = mysql_fetch_array($sql);

   if ($g['team'] == 0){
   return false;

   }else{

   $mt = mysql_query("SELECT * FROM matches WHERE match_id = '".$g['team']."'");
   $m = mysql_fetch_array($mt);

   $lastgame = true;
   $gs = mysql_query("SELECT * FROM {$db_prefix}games WHERE team = '".$m['match_id']."'");

   while ($game = mysql_fetch_array($gs)) {

   if ($game['status'] == '') $lastgame = false;

   }

   if ($lastgame){

   mysql_query("UPDATE matches SET status = 'finished' WHERE match_id = '".$m['match_id']."'");

   $tw = mysql_query("SELECT * FROM matches WHERE match_id = '".$m['match_id']."'");
   $fg = mysql_fetch_array($tw);

   if ($fg['match_points1'] > $fg['match_points2']) {
   $mwin = $fg['team1'];
   }elseif ($fg['match_points2'] > $fg['match_points1']) {
   $mwin = $fg['team2'];
   }else{
   $mwin = '-1';
   }

   mysql_query("UPDATE matches SET winner = '$mwin' WHERE match_id = '".$m['match_id']."'");

   if ($mwin != '-1')

   mysql_query("UPDATE team set wins=wins+'1' WHERE teamID='$mwin'");

   giveMedalm('TeamWin',$mwin);

      }
   }
}

function giveMedalm($medal,$team){
   $date = date("Y-m-d");
   $tm2 = mysql_query("select * from medals where teamID='$team' AND

medal='$medal'");
        if (mysql_num_rows($tm2) == 0){
      mysql_query("insert into medals (teamID,date,medal) values

('$team','$date','$medal')");
        }
}

function giveMedal($medal,$player,$name,$gen){
    $date = date("Y-m-d");
    $p2 = mysql_query("select * from medals where playerID='$player' AND medal='$medal'");
        if (mysql_num_rows($p2) == 0){
        if ($gen)
            echo "Giving medal $medal to $name...<BR>";
        else
            echo "<script>alert('Congratulations, you earned the $medal medal!');</script>";
        mysql_query("insert into medals (playerID,date,medal) values ('$player','$date','$medal')");
        flush();
        }
}


function generateMedals($player,$generate){
    $rank = 0;

    $p2 = mysql_query("select playerID from {$db_prefix}players where engine='1' AND ativo='1'");
    $totalcpu = mysql_numrows($p2);

    /* Courage Medal */
    if (!$generate)
     $p = mysql_query("SELECT count(*),{$db_prefix}players.* from {$db_prefix}players,{$db_prefix}games WHERE oficial='1' AND playerID='$player' AND
         (status <> 'playerInvited' AND status <> 'inviteDeclined' AND status <> '') AND
         (white_player=playerID OR black_player=playerID)  group by playerID order by rating DESC,rating_month DESC");
    else
     $p = mysql_query("SELECT count(*),players.* from {$db_prefix}players,{$db_prefix}games WHERE oficial='1' AND
     (status <> 'playerInvited' AND status <> 'inviteDeclined' AND status <> '') AND
     (white_player=playerID OR black_player=playerID)  group by playerID order by rating DESC,rating_month DESC");

    while($row = mysql_fetch_array($p)){

        if (!$generate)
            $rank = getRanking($row[playerID]);
        else
            $rank++;

        if ($row[0] >= 20){

        $player = $row[playerID];
        $paralel[$player] = array();
        $venceu[$player] = array();
        $unfinished[$player] = array();
        $game_o = 0;
        $game_u = 0;

        //Golden Medal
        /*
        $p3 = mysql_query("select count(*) from {$db_prefix}games where ratingWhite>0 AND ratingBlack>0 AND (white_player=$player OR black_player=$player) AND status <> '' AND status<>'inviteDeclined' and status<>'playerInvited' and oficial=1");
        if (mysql_numrows($p3) >0){
            $r3 = mysql_fetch_array($p3);
            $game_o = $r3[0];
        }
        $p3 = mysql_query("select count(*) from {$db_prefix}games where ratingWhite>0 AND ratingBlack>0 AND (white_player=$player OR black_player=$player) AND status <> '' AND status<>'inviteDeclined' and status<>'playerInvited' and oficial=0");
        if (mysql_numrows($p3) >0){
            $r3 = mysql_fetch_array($p3);
            $game_u = $r3[0];
        }
        $game_t = $game_o+$game_u;

          $oficial_ratio = ($game_o/$game_t)*100;
          if ($oficial_ratio >=80 && $row[0] >50)
              echo "$row[firstName] $oficial_ratio<BR>";
              //giveMedal("goldenstar",$player,$row[firstName],$generate);
        */

            $p2 = mysql_query("select DISTINCT playerID from {$db_prefix}games,{$db_prefix}players where
            ((status='checkMate' AND message_from='white' AND white_player=$player AND black_player=playerID)
            OR (status='checkMate' AND message_from='black' AND black_player=$player AND white_player=playerID))
            AND DisplayBot='1' AND ativo='1' AND oficial='1'");
            if ($totalcpu >0 && mysql_numrows($p2) >= $totalcpu)
             giveMedal("botslayer",$player,$row[firstName],$generate);


            $vitorias=0;
            $derrotas=0;
            $empates=0;
            $ativos=0;


            $p2 = mysql_query("select *,DATE_FORMAT(dateCreated, '%d/%m/%y') as created,DATE_FORMAT(lastMove, '%d/%m/%y') as fim from {$db_prefix}games where oficial='1' AND (white_player='".$player."' OR black_player='".$player."')");
            while ($r = mysql_fetch_array($p2)){

                $v=0;
                $d=0;
                $e=0;
                $a=0;

                if ($r['status']== '')
                    $a=1;
                else if ($r['status'] == "playerResigned" && $r['message_from'] == "white" && $player != $r['white_player'])
                    $v=1;
                else if ($r['status'] == "playerResigned" && $r['message_from'] == "black" && $player != $r['black_player'])
                    $v=1;
                else if ($r['status'] == "playerResigned" && $r['message_from'] == "white" && $player != $r['black_player'])
                    $d=1;
                else if ($r['status'] == "playerResigned" && $r['message_from'] == "black" && $player != $r['white_player'])
                    $d=1;
                else if ($r['status'] == "checkMate" && $r['message_from'] == "white" && $player != $r['white_player'])
                    $d=1;
                else if ($r['status'] == "checkMate" && $r['message_from'] == "black" && $player != $r['black_player'])
                    $d=1;
                else if ($r['status'] == "checkMate" && $r['message_from'] == "white" && $player != $r['black_player'])
                    $v=1;
                else if ($r['status'] == "checkMate" && $r['message_from'] == "black" && $player != $r['white_player'])
                    $v=1;
                else if ($r['status'] == "draw")
                    $e=1;

            if ($v == 1)$vitorias++;
            if ($d == 1)$derrotas++;
            if ($e == 1)$empates++;
            if ($a == 1)$ativos++;


            if ($v == 1){
            $venceu[$player][$r[created]]++;
            $terminou[$player][$r[fim]] += $r[xpw];
            }else if ($a == 1)
            $unfinished[$player][$r[created]]++;
            else if ($d == 1){
             $paralel[$player][$r[created]]++;
             $terminou[$player][$r[fim]] -= $r[xpl];
            }else
                $paralel[$player][$r[created]]++;

        }

            $medal = "";
            while(list($data,$vit) = each($venceu[$player])){
                $der = $paralel[$player][$data];
                $ati = $unfinished[$player][$data];
                $tot = $der+$vit;
                if ($ati == "")$ati=0;

                if ($vit >=10 && $ati == 0){
                    $percent = round($vit/$tot*100);
                    if ($percent >=70 && $percent <80)
                        $medal = "courage70";
                    else if ($percent >=80 && $percent <90)
                        $medal = "courage80";
                    else if ($percent >=90 && $percent <100)
                        $medal = "courage90";
                    else if ($percent == 100)
                        $medal = "courage100";
                }
            }

            if ($medal != "")
                giveMedal($medal,$player,$row[firstName],$generate);

            while(list($data,$xp) = each($terminou[$player])){
                if ($xp >=130)
                    giveMedal("silverstar",$player,$row[firstName],$generate);
                //if ($xp >=250)
                    //giveMedal("goldenstar",$player,$row[firstName],$generate);
            }


            /* Honour medal */
            if ($rank == 1)
                giveMedal("honour",$player,$row[firstName],$generate);

            /* Purple Heart */
            if ($row[rating] == 900)
                giveMedal("purpleheart",$player,$row[firstName],$generate);

            /* Merit Medal */
                    $total = $vitorias+$derrotas+$empates;
                    $pv = round($vitorias/$total*100,2);

            if ($pv >=90){
                if ($total < 50)$medal = "merit20";
                else if ($total < 100)$medal = "merit50";
                else if ($total >=100)$medal = "merit100";
                giveMedal($medal,$row[playerID],$row[firstName],$generate);
            }
        }

    }

}
?>
