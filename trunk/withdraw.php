<?php
##############################################################################################
#                                                                                            #
#                                withdraw.php
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
//this is some kludgy login checking shit at best .. but for the time being, it's the least of the worries.
//check user login and load user data if not set in session.
if(empty($_SESSION) || check_login($_SESSION['player_id'],$_COOKIE['PHPSESSID']) == false)
{
    if($_SESSION)
    {
        session_destroy();
    }
    header("Location: index.php");
}

//this file is just for cancelling/withdrawing a challenge that has not yet been accepted
//it is a separate file due to the nature of , and possibility for manipulation,
//so we will be working it as a separate file for the time being, until it is verified to be reliable

$game_id = $_POST['game_id']; //we will not accept any other

//its a cancellation request, let's make sure this is made by the challenger
//who should be the only one that can cancel a challenge- target players can decline , which cancels

//The challenger in a game is where the player_id color is the same as message_from
//if message_from = black, SESSION[player_id] must be black player

$sql = $db->Prepare("SELECT white_player,black_player,message_from,status FROM {$db_prefix}games WHERE game_id =?");
$query = $db->Execute($sql,array($game_id));
db_op_result($query,__LINE__,__FILE__);
$data = $query->fields;
$color = NULL;
if($data['black_player'] == $_SESSION['player_id'])
{
   $color = "black";
}
if($data['white_player'] == $_SESSION['player_id'])
{
     $color = "white";
}

if($color == $data['message_from'])
{
   $res = $db->Prepare("DELETE FROM {$db_prefix}games WHERE game_id =?");
   $query = $db->Execute($res,array($game_id));
   db_op_result($res,__LINE__,__FILE__);
   header("Location: challenge.php");
}
else
{
   header("Location: challenge.php");
}
 exit;
 ?>