<?php
##############################################################################################
#                                                                                            #
#                                login.php
# *                            -------------------                                           #
# *   begin                : Wednesday, January 25, 2005
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://developer.berlios.de/projects/chess/                              #
# *   VERSION:             : $Id: login.php,v 1.2 2005/02/25 02:00:56 trukfixer Exp $
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

include_once('global_includes.php');//should have every file in it that is included in the game overall. make it happen
//db connection should be in there.
//login.php is the process where we authenticate users and set their information to the session.
//should never arrive here without a POST value. pointless to check referrer
if(empty($_POST))
{
    header("Location: index.php");//send em back to index and login page.
    die("Forbidden");
}
//we actually will get a post value, but we need to remove that for language- it should just be stored in the session by index.php
//one major issue is if someone hacked the value to a system folder other than languages- we need to detect that and correct it.
$language = $_SESSION['pref_language'];//should always be set. We'll have a trap for it and set to english by default
//for now no trap so as to debug
//if(empty($language))
//{
    //$language = "english";
//}

$username = $_POST['username'];
$password = md5($_POST['password']);
include_once("./languages/{$language}/login.inc.php");
$template_set = "default";
$errors = array();
$i=0;
//the above could be anything, and to avoid telling someone that a username actually exists, we need to
//give a wishy-washy response. "That Username/Password combination was not found in the database"

$sql = $db->Prepare("SELECT player_id,username,password,active from {$db_prefix}players WHERE username=? AND password=?");
$result = $db->Execute($sql,array($username,$password));
db_op_result($result,__LINE__,__FILE__);
$data = $result->fields;
if($data['player_id'] < 1)
{
    $errors[$i] = $MSG_LANG_LOGIN['login_mismatch'];
    $i++;
    $_SESSION['errors'] = $errors;
    $data = "Login Error attempted login with username ".$_POST['username']." and password ".$_POST['password']." from IP $ip at ".date("Y-m-d H:i:s").".";
    Adminlog(LOGIN_ERROR,$data);
    header("Location: index.php");
    die();
}
if($data['active'] == "0" && $validation == 1)
{
    $errors[$i] = $MSG_LANG_LOGIN['not_validated'];
    $i++;
    $smarty->assign('errors',$errors);
    $smarty->assign('title2',"Email Confirmation");
    $smarty->display("{$template_set}/activate_account.php");
    die();
}

if(empty($errors))
{
    //OK we should be fine for login, user and password match up...
    $res = get_player_data($data['player_id']) ;
    if(!$res)
    {
        adminlog(3000,"Function get_player_data Failed! $ip". date("Y-m-d H:i:s"));
       header("Location: index.php");
    }
    if(0)
    {  //OK I'm leaving it here for now just in case, but function seems to work perfectly
    //and the session should maintain state from this point on. so if it works correctly, this
    //shit can be safely deleted within the if(0){brackets}
     //get the user data from db here and assign necessary stuff to Session
    $user_array = array($data['player_id']);
    $user = $db->Prepare("SELECT player_id,username FROM {$db_prefix}players WHERE player_id=?");
    $query = $db->Execute($user,$user_array);
    db_op_result($query,__LINE__,__FILE__);
    $user_data = $query->fields;
    //get preferences
    $sql = $db->Prepare("SELECT pref_language,template_set,theme_set,style,show_chat,display_bot FROM {$db_prefix}player_preference WHERE player_id=?");
    $query = $db->Execute($sql,$user_array);
    db_op_result($query,__LINE__,__FILE__);
    $user_pref = $query->fields;

   //get player stats we need while they play
    $sql = $db->Prepare("SELECT trials_left,rating,points FROM {$db_prefix}player_stats WHERE player_id=?");
    $query = $db->Execute($sql,$user_array);
    db_op_result($query,__LINE__,__FILE__);
    $user_stats = $query->fields;

    //OK, now we have all the info we need, let's assign it all to a session
    $_SESSION['player_id'] = $user_data['player_id'];
    $_SESSION['username'] = $user_data['username'];
    $_SESSION['pref_language'] = $user_pref['pref_language'];
    $_SESSION['template_set'] = $user_pref['template_set'];
    $_SESSION['theme_set'] = $user_pref['theme_set'];
    $_SESSION['style'] = $user_pref['style'];
    $_SESSION['show_chat'] = $user_pref['show_chat'];
    $_SESSION['display_bot'] = $user_pref['display_bot'];
    $_SESSION['trials_left'] = $user_stats['trials_left'];
    $_SESSION['rating'] = $user_stats['rating'];
    $_SESSION['points'] = $user_stats['points'];
    $_SESSION['last_update'] = time();

    //set a unix timestamp, we can update a player's last_update info less often,
    //otherwise we're going to have a SQL hit on every page load, 30 players online
    //at once would cause us to have as much as 180 sql queries per minute! just a tad too much.., ey?
    }
    //session_destroy();
   //echo"<PRE>";
    //var_dump($_SESSION);
    //die();
    header("Location: mainmenu.php");

}
$smarty->assign('errors',"Unknown Error Occurred. Please contact the webmaster!");
$smarty->display('default/index.tpl');
exit;

?>
