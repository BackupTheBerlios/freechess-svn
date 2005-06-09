<?php
##############################################################################################
#                                                                                            #
#                                challengesent.php   - SCHEDULED FOR REMOVAL
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

    /* load settings */
    include_once ('config.php');

    /* load external functions for setting up new game */
    require_once ( 'chessutils.php');
    require_once ( 'chessconstants.php');
    require_once ( 'newgame.php');
    require_once('chessdb.php');


    /* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
    fixOldPHPVersions();

    /* if this page is accessed directly (ie: without going through login), */
    /* player is logged off by default */
    if (!isset($_SESSION['playerID']))
        $_SESSION['playerID'] = -1;

    /* connect to database */
    require_once( 'connectdb.php');
    $id=$_SESSION['playerID'];
    $thankyou_message = "<p>Your challenge has been sent. </p>";
    /* check session status */
    require_once('sessioncheck.php');

    /* Language selection */
    require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");
?>
<?php
function backslash(&$arr, $escape)
{
   $magic_on = get_magic_quotes_gpc();

   if($escape && !$magic_on):
       foreach($arr as $k => $v):
           switch(gettype($v)):
               case 'string' :
                   $arr[$k] = addslashes($v);
                   break;
               case 'array' :
                   backslash($arr[$k], true);
           endswitch;
       endforeach;
   endif;

   if(!$escape && $magic_on):
       foreach($arr as $k => $v):
           switch(gettype($v)):
               case 'string' :
                   $arr[$k] = stripslashes($v);
                   break;
               case 'array' :
                   backslash($arr[$k], false);
           endswitch;
       endforeach;
   endif;
}

//examples
backslash($_POST, true); // force all $_POST values to be  properly escaped with backslashes backslash($_GET, false); // force all $_GET values to NOT be escaped with backslashes
?>
<?
if (!$CFG_PERMIT_MULTIPLE_GAMES)
{
                $tmpQuery = "SELECT gameID FROM games WHERE (gameMessage = '' OR gameMessage='playerInvited')";
                $tmpQuery .= "AND ((whitePlayer = ".$_SESSION['playerID']." AND blackPlayer= ".$_POST['opponent'].")";
                $tmpQuery .= " OR (whitePlayer=".$_POST['opponent']." AND blackPlayer=".$_SESSION['playerID']."))";
                $tmpExistingRequests = mysql_query($tmpQuery);
                $ngames = mysql_num_rows($tmpExistingRequests);
            }
            else $ngames = 0;

            if ($CFG_ENABLE_TRIAL_RATING){
                    /* Check if newuser playing newuser */
                    //if (getRating($_SESSION['playerID']) == 0 && getRating($_POST['opponent']) == 0){
                        //displayError("New Users are not allowed to play eachother!", "newuserchallenge.php");
                        //}
                    /* Check if the number of games don´t reached 5*/
                    if (getRating($_SESSION['playerID']) == 0){
                        $stats = getStatsUser($_SESSION['playerID'],0);
                        $total = $stats[3]+$stats[4];

                        if ($total >= 10){
                            $ngames = 1;
                            displayError("You are still in the trial and initiation process. <br>You have reached the limit of 10 games or have sent out the maximum of 10 invitations. <br> If you wish to send out different invitations <br>please cancel your current invitaitons.", "newuserchallenge.php");
                        }
                    }
                    if (getRating($_POST['opponent']) == 0){
                        $stats = getStatsUser($_POST['opponent'],0);
                        $total = $stats[3]+$stats[4];
                        if ($total >= 10){
                            $ngames = 1;
                            displayError("This opponent is in the trial and initiation  <br>process and has reached the 10 game limit!", "newuserchallenge.php");
                        }
                    }

            }


            if ($ngames == 0)
            {
                if (!minimum_version("4.2.0"))
                    init_srand();

                if ($_POST['color'] == 'random')
                    $tmpColor = (rand(0,1) == 1) ? "white" : "black";
                else
                    $tmpColor = $_POST['color'];

                $tmpQuery = "INSERT INTO games (timelimit, whitePlayer, blackPlayer, gameMessage, messageFrom, dateCreated, lastMove, ratingWhite, ratingBlack,ratingWhiteM, ratingBlackM,oficial,PVBlack,PVWhite,thematic) VALUES (";
                if ($tmpColor == 'white'){
                    $white = $_SESSION['playerID'];
                    $black = $_POST['opponent'];
                }else{
                    $white = $_POST['opponent'];
                    $black = $_SESSION['playerID'];
                }
                $tmpQuery .= "$_POST[timelimit], $white, $black, 'playerInvited', '".$tmpColor."', NOW(), NOW(),".getRating($white).",".getRating($black).",".getRatingMonth($white).",".getRatingMonth($black).",'".$_POST['oficial']."',".getPV($black).",".getPV($white).",'".$_POST['thematic']."')";
                mysql_query($tmpQuery);

                /* if email notification is activated... */
                if ($CFG_USEEMAILNOTIFICATION)
                {
                    /* if opponent is using email notification... */
                    $tmpOpponentEmail = mysql_query("SELECT value FROM preferences WHERE playerID = ".$_POST['opponent']." AND preference = 'emailNotification'");
                    if (mysql_num_rows($tmpOpponentEmail) > 0)
                    {
                        $opponentEmail = mysql_result($tmpOpponentEmail, 0);
                        if ($opponentEmail != '')
                        {
                            /* notify opponent of invitation via email */
                            webchessMail('invitation', $opponentEmail, '', $_SESSION['firstName']);
                        }
                    }
                }
            }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta name="Keywords" content="chess,ajedrez,échecs,echecs,scacchi,schach,check,check mate,jaque,jaque mate,queenalice,queen alice,queen,alice,play,game,games,turn based,correspondence,correspondence chess,online chess,play chess online">

        <title>Challenge</title>

<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/mainstyles.css" type="text/css">

    </head>

    <body>
</TD></TR>
<table width="100%" border="1">
  <tr>
    <td><font color="#FF0000" size="4">
      <strong>
      <?
echo $thankyou_message;
?>
      </strong>    </font>      <div align="center"></div>
    <div align="center"></div></td>
  </tr>
</table>
</table>
    </div>
        </body>

</html>
