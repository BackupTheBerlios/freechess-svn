<?php
##############################################################################################
#                                                                                            #
#                                /admin/reminder.php                                                
# *                            -------------------                                           #
# *   begin                : Wednesday, January 25, 2005                                     
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://www.compwebchess.com/forums                              #
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
// SEND EMAIL REMINDER FOR GAMES NEARLY LOST ON TIME
   /* determine threshold for oldest game permitted */
   $targetDate = date("Y-m-d H:i:s", mktime(date('H'),date('i'),0, date('m'), date('d') - $CFG_EXPIREGAMESOON,date('Y')));
   /* find out which games are older */
   $tmpQuery = "SELECT * FROM games WHERE lastMove < '".$targetDate."' AND gameMessage='' AND reminder='0' ";
   $tmpOldGames = mysql_query($tmpQuery);

   /* for each older game... */
    while($tmpOldGame = mysql_fetch_array($tmpOldGames, MYSQL_ASSOC))
    {
        if ($tmpOldGame['gameMessage'] == ''){

                //Find player who turn it is
                $p = mysql_query("SELECT * FROM history WHERE gameID='".$tmpOldGame['gameID']."' ORDER BY timeOfMove DESC");
                $row = mysql_fetch_array($p);

                if ($row['curColor'] != ""){
                    if ($row['curColor'] == "white")$playersColor = "black";
                    else if ($row['curColor'] == "black")$playersColor = "white";
                }
                else
                    $playersColor = "white";

                $p = mysql_query("SELECT whitePlayer,blackPlayer FROM games WHERE gameID='$tmpOldGame[gameID]'");
              $row = mysql_fetch_array($p);
              if ($playersColor == 'white'){
                 $player_id = $row[0];
              }
              else{
                 $player_id = $row[1];
              }
            //send reminder email
            $tmpEmail = mysql_query("SELECT email FROM players WHERE playerID='$player_id' ");
            $Email = mysql_result($tmpEmail, 0);
            if ($Email != ''){
               $game = $tmpOldGame['gameID'];
webchessMail('reminder', $Email, '', '', $game);

            //set we have sent reminder to 1
            $tmpQuery = "UPDATE games SET reminder = '1' WHERE gameID = ".$tmpOldGame[gameID];
            mysql_query($tmpQuery);
            }
        }

    }
// END OF SEND EMAIL REMINDER FOR GAMES NEARLY LOST ON TIME
?>

