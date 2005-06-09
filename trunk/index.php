<?php
##############################################################################################
#                                                                                            #
#                                index.php                                                #
# *                            -------------------                                           #
# *   begin                : Friday, January 15, 2005                                        #
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://developer.berlios.de/projects/chess/                              #
# *   VERSION:             : $Id: index.php,v 1.1 2005/01/27 15:16:41 trukfixer Exp $
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
//replace th above with the below when we get the file written
//include_once('includes/global_includes.php');

//session_start();
//session should start in the config via adodb sessions- we'll get that in eventually

//get rid of the cookies! we'll put it into player data

if (!empty($_GET['language']))
{
    $language = $_GET['language'];
}
if (!empty($_POST['language']))
{
     $language = $_POST['language'];

}
//we'll default it to english at login page... there really isnt much needed

if (empty($language))
{
    $language = "english";
    //$lang = $configured_language;//get from database
}
    //Language selection

    include_once("languages/".$language."/strings.inc.php");
//cookie isnt necessary, really- we'll offer the drop list at login time to change language onchange

    $_SESSION['pref_language'] = $language;
    $selected = $language;
//what template set ?? - in config we'll have a default set, later a select option just like languages
//MAYBE..
$template_set = "default";
$smarty->assign('lang_select_language',$MSG_LANG["language"]);
$language_array = array();

if ($handle = opendir('languages'))
{
    while (false !== ($file = readdir($handle)))
    {
        if (strpos($file,"CVS") === FALSE && $file != "." && $file != "..")
              array_push($language_array,$file);
    }
closedir ($handle);
}
sort ($language_array);

if (!empty($_SESSION['pref_language']))
{
    $selected = $_SESSION['pref_language'];
}
if(!empty($_SESSION['errors']))
{
    $smarty->assign('errors',$_SESSION['errors']);
}

//Let's see who is online in the players list within the last 5 minutes and list them on this page
 $online_players = array();
 $sql = "select a.username,b.rating,b.victories,b.defeats,b.draws from {$db_prefix}players as a, {$db_prefix}player_stats as b where a.last_update > date_sub(now(),interval '5:0' minute_second) and a.player_id=b.player_id order by b.rating desc";
 $query = $db->Execute($sql);
 db_op_result($query,__LINE__,__FILE__);
 while(!$query->EOF)
 {
     $online = $query->fields;
     array_push($online_players,$online);
      $query->MoveNext();
 }
 $smarty->assign('onlinep',$online_players);


//let's get some stats to show on the index page
//active games, active tournaments, team matches, top ranked players - top of each rating grade
//total active players in the past week
//active games is easy
$sql = "SELECT count(*) as count from {$db_prefix}games WHERE status = ''";
$query = $db->Execute($sql);
$count = $query->fields;
$smarty->assign('active_games',$count['count']);

$sql = "SELECT count(*) as count from {$db_prefix}players where active='1'";
$query = $db->Execute($sql);
$actives = $query->fields;
$smarty->assign('registered',$actives['count']);

$sql = "SELECT count(*) as count from {$db_prefix}players where active='1' AND last_update > date_sub(now(),interval 7 day)";
$query = $db->Execute($sql);
$actives = $query->fields;
$smarty->assign('actives',$actives['count']);

//FREQ: let's select commentated games rated by players as a top game - get the top 5 best games as rated
//display with a public analyze board and commentary for new people to "play with"

$smarty->assign('language_options',$language_array);
$smarty->assign('selected',$selected);

//Now we pull in the needed smarty language strings
$smarty->assign('lang_create_user',$MSG_LANG["createuser"]);
$smarty->assign('lang_forgot_password',$MSG_LANG['forgotpassword']);
$smarty->assign('lang_submit_login',$MSG_LANG["login"]);
$smarty->assign('lang_password',$MSG_LANG['password']);
$smarty->assign('lang_username',$MSG_LANG['username']);
$smarty->assign('title',"WebChess Login Page- Select a language to change language");
//we need title translateable
$smarty->assign('template_set',$template_set);

//include header.php once we have it ready

//include_once('header.php');
$smarty->display("{$template_set}/index.tpl");

//include footer.php when we have it ready
//include_once('footer.php');
unset($_SESSION['errors']);
exit;
?>

