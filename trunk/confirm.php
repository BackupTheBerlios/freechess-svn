<?php
##############################################################################################
#                                                                                            #
#                                confirm.php                                                
# *                            -------------------                                           #
# *   begin                : Wednesday, February 24, 2005                                     
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

include_once('global_includes.php');//should have every file in it that is included in the game overall. make it happen
//db connection should be in there.
$errors = array();
        $i=0;
if(!empty($_GET))
{
    $code = $_GET['c_code'];
}

if(!empty($_POST))
{
    if(empty($_POST['password']) || empty($_POST['username']))
    {
        $language = $_POST['language'];
        $code = $_POST['code'];
        $_SESSION['pref_language'] = $language;
        include_once("languages/{$language}/login.inc.php");
    }
    else
    {
        $language = $_POST['language'];
        $code = $_POST['code'];
        $_SESSION['pref_language'] = $language;
        
        include_once("languages/{$language}/login.inc.php");
        $arr = array($_POST['username'],md5($_POST['password']));
        $sql = $db->Prepare("SELECT validation_code FROM {$db_prefix}players WHERE username=? AND password=?");
        $query = $db->Execute($sql,$arr);
        db_op_result($query,__LINE__,__FILE__);
        $result = $query->fields;
        if($result['validation_code'] != $_POST['code'])
        {
            $errors[$i] = $MSG_LANG_LOGIN['invalid'];
            $i++;
        }
        else
        {
            $sql = $db->Prepare("UPDATE {$db_prefix}players SET active='1',validation_code='' WHERE username=? AND password=?");
            $res = $db->Execute($sql,$arr);
            db_op_result($res,__LINE__,__FILE__);
            
            $smarty->assign('success',$MSG_LANG_LOGIN['confirmed']);
            $smarty->assign('redirect',$MSG_LANG_LOGIN['redirect']);
            $smarty->display('default/confirmed.tpl');
            exit;
        }
       
    }
}
else
{
    $language = "english";
    include_once("languages/{$language}/login.inc.php");
}
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

$smarty->assign('confirm_code',$code);
$smarty->assign('language_options',$language_array);
$smarty->assign('lang_select_language',$MSG_LANG_LOGIN["language"]);
        $smarty->assign('selected',$language);
        $smarty->assign('errors',$errors);
        $smarty->assign('lang_submit_login',$MSG_LANG_LOGIN['submit']);
        $smarty->assign('confirm_message',$MSG_LANG_LOGIN['confirm_message']);
        $smarty->assign('lang_password',$MSG_LANG_LOGIN['password']);
        $smarty->assign('lang_username',$MSG_LANG_LOGIN['username']);
        $smarty->assign('lang_confirm',$MSG_LANG_LOGIN['confirm_code']);
        $smarty->assign('title2',"Account Confirmation");
$smarty->display('default/confirm.tpl');




?>
