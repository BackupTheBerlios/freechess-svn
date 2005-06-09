<?php
##############################################################################################
#                                                                                            #
#                                new_player.php                                   
# *                            -------------------                                           #
# *   begin                : Tuesday, February 1, 2005                                    
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://developer.berlios.de/projects/chess/                              #
# *   VERSION:             : $Id: new_player.php,v 1.1 2005/02/04 05:16:47 trukfixer Exp $                                           
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

//new account signup script- all in one, email validation only used here and in player preferences, so 
//need another account_functions.php file. 
//this displays form to fill out, does field checks, inserts new player into DB on confirmation
//sends confirmation e-mail (function in player accounts) 
if(empty($_SESSION['pref_language']))
{
    $pref_language = "english";
}
else
{
    $pref_language = $_SESSION['pref_language'];
}
if(empty($_SESSION['template_set']))
{
    $template_set = 'default';
}
else
{
    $template_set = $_SESSION['template_set'];
}

include_once('global_includes.php');
include_once("languages/$pref_language/lang_common.inc.php");
include_once("languages/$pref_language/new_player.inc.php");//get country codes, etc
//include_once('player_functions.php'); //-> if feasible, how many functions we gonna have anyway? might just put em in here
//
$errors = array();
$i=0;
$title2 = $MSG_LANG_NEW['new_player_title'];
$title = "Your Web Site";//move to game_settings table, of course.
$minimum_age = 13;//TODO: MOVE title and minimum_age to game_settings
$validation = 1;
$trials_left = 5;
$start_rating = 1500;
$admin_mail = "sales@crazybri.net";
$admin_mail_name = "CrazyBri WebChess";
$mailer_type = "smtp";
$smtp_host = "localhost";


if(!empty($_POST))
{
    //they've submitted information to create new account

    //post data must be checked, cleaned, and verified
	
    $username = $_POST['username'];
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $sex = $_POST['sex'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $birthdate = $_POST['birth_Month']."-".$_POST['birth_Day']."-".$_POST['birth_Year'];
    //minimum age ?  
    if($minimum_age > 0)
    {
        $past_date = date("Y-m-d",mktime(0,0,0,date("m"),date("j"),(date("Y")-$minimum_age)));
        $birth = $_POST['birth_Year']."-".$_POST['birth_Month']."-".$_POST['birth_Day'];
        $past_date = strtotime($past_date);
        $birthday = strtotime($birth);
        if($past_date < $birthday)
        {
            $errors[$i] = $MSG_LANG_NEW['under_age_player'];
            $i++;
            
            
        }
    }
    $first_name = clean_text($first_name);
    $last_name = clean_text($last_name);
    $username = clean_text($username);
    $city = clean_text($city);
    $pass1 = str_replace(" ","",$pass1); //remove empty spaces 
        
    //16 digit key
    $auth_key = intval(mt_rand(0,time()));
    $auth_key = md5($auth_key);
    $auth_code = substr($auth_key,0,16);
   //compare passwords.
    if ($pass1 != $pass2)
    {
       $errors[$i] = $MSG_LANG_NEW['password_no_match'];
       $i++;
    }
    elseif (strlen($pass1) < 3)
    {
       $errors[$i] = $MSG_LANG_NEW['minimum_pass_length'];
       $i++;
    }
    else
    {
       $password = md5($pass1);
    }
   	/* empty nick */
	if (strlen($username) < 3)
	{
		$errors[$i] = $MSG_LANG_NEW['no_blank_user'];
		$i++;
	}

    $first_name = strip_tags($first_name);
    $last_name = strip_tags($last_name);
    if ($first_name == "")
    {
        $errors[$i] = $MSG_LANG_NEW['no_blank_first']; 
		$i++;
    }
    if ($last_name == "")
    {
        $errors[$i] = $MSG_LANG_NEW['no_blank_last']; 
		$i++;
    }
    
    $is_valid = validate_email_format ($email);
    if (!$is_valid)
    {
        $errors[$i] = $MSG_LANG_NEW['invalid_email'];
		$i++;
	}
	if ($city == "")
	{
        $_POST['city'] = "Undefined";
        $city = "Undefined";
    }
    if ($state == "")
    {
        $errors[$i] = $MSG_LANG_NEW['err_select_state'];
        $i++;
    }
    if ($country == "")
    {
        $errors[$i] = $MSG_LANG_NEW['err_select_country'];
        $i++;
    }
    if($sex == "")
    {
        $sex = "U";
    }
    
	$sql = "SELECT player_id FROM ${db_prefix}players WHERE username = ?";
	$result = $db->Execute($sql,array($username));
	db_op_result($result,__LINE__,__FILE__);
	$check = $result->fields;
	if($check['player_id'] > 0)
	{
    	$errors[$i] = $MSG_LANG_NEW['username_already_taken'];
    	$i++;
	}
	
	
	if(empty($errors))
	{
    	if($validation == 0)
    	{
        	$active = "1";
    	}
    	else
    	{
        	$active = "0";
    	}
    	if($trials_left > 0)
    	{
        	$new_rating = 0;
        	
    	}
    	else
    	{
        	$new_rating = $start_rating;
    	}
        	
    	$sql = $db->Prepare("INSERT INTO {$db_prefix}players (password,username,email,active,last_update,validation_code,first_name,".
    	                    " last_name,sex, birth_date, city, ".
    	                    "state, country) VALUES (".
    	                    "?,?,?,?,NOW(),?,?,?,?,?,?,?,?)");
    	
        $sql_array = array($password,$username,$email,$active,$auth_key,$first_name,$last_name,$sex,$birthdate,$city,$state,$country);

	    $result = $db->Execute($sql,$sql_array);
	    db_op_result($result,__LINE__,__FILE__);
	    //in this case, we *could* use get_last_insert_id() however, it is non-portable, and will not work on some
	    //postgresql: this information could change on a database reload.. not a great idea, so... we'll do it the hard way-
	    //slower, perhaps, but surer
	    $result = $db->Execute("SELECT MAX(player_id) as player_id FROM {$db_prefix}players");
	    db_op_result($result,__LINE__,__FILE__);
	    $next = $result->fields[0];
	    //set the player into ratings table, they're preset defaults so..just need the two fields
	    $sql = $db->Prepare("INSERT INTO {$db_prefix}player_stats (player_id,rating,trials_left) VALUES (?,?,?)");
	    $sql_array = array($next,$new_rating,$trials_left);
	    $result = $db->Execute($sql,$sql_array);
	    db_op_result($result,__LINE__,__FILE__);
	    
	   //OK, now we set up the preferences table, with suitable defaults. 
	   if(empty($pref_language))
	   {
    	   $pref_language = "english";
	   }
	   if(empty($template_set))
	   {
    	   $template_set = "default";
	   }
	   if(empty($theme_set))
	   {
    	   $theme_set = "classic";
	   }
	   if(empty($style))
	   {
    	   $style = "standard";
	   }
	   $sql_array = array($next,'0',$pref_language,$template_set,$theme_set,$style);
	   $sql = $db->Prepare("INSERT INTO {$db_prefix}player_preference (player_id,auto_accept,pref_language,template_set,theme_set,style) VALUES (?,?,?,?,?,?)");
	   $result = $db->Execute($sql,$sql_array);
	   db_op_result($result,__LINE__,__FILE__);
	   
	   //OK, now we send the confirmation email
	   $this_dir = str_replace("new_player.php",'',$_SERVER['REQUEST_URI']);
	   $temp_url = $server_type . "://" . $_SERVER['SERVER_NAME']. $this_dir;
        
        $MSG_LANG_NEW['welcome'] = str_replace("[ip_address]",$ip,$MSG_LANG_NEW['welcome']);
        $MSG_LANG_NEW['welcome'] = str_replace("[url]",$temp_url."index.php",$MSG_LANG_NEW['welcome']);
        $MSG_LANG_NEW['confirm_code'] = str_replace("[url]",$temp_url."confirm.php",$MSG_LANG_NEW['confirm_code']);
        $MSG_LANG_NEW['confirm_code'] = str_replace("[auth_code]",$auth_code,$MSG_LANG_NEW['confirm_code']);
        $MSG_LANG_NEW['from_game'] = str_replace("[game]",$title,$MSG_LANG_NEW['from_game']);
          $confirm_url = $temp_url."confirm.php?c_code=" . $auth_code;
        $MSG_LANG_NEW['confirm'] = str_replace("[link]",$confirm_url,$MSG_LANG_NEW['confirm']);      
    
        // Give the variables to Smarty, and then retreive the templated newplayer email
        $smarty->assign("lang_new_welcome", $MSG_LANG_NEW['welcome']);
        $smarty->assign("lang_new_confirm_code", $MSG_LANG_NEW['confirm_code']);
        $smarty->assign("lang_new_confirm", $MSG_LANG_NEW['confirm']);
        $smarty->assign("lang_new_thanks", $MSG_LANG_NEW['thank_you']);
        $smarty->assign("l_newmsg_game", $MSG_LANG_NEW['from_game']);
        $msg = $smarty->fetch("$template_set/newplayer_email.tpl");
    
        $text_msg = str_replace("<br>", "\n", $msg);
        $text_msg = str_replace("<a href='", "", $text_msg);
        $text_msg = str_replace("'>", "", $text_msg);
        $text_msg = str_replace("</a>", "", $text_msg);
    
        // Make sure that the html version has the url hyperlinked
        $msg = str_replace($confirm_url, "<a href=\"" . $confirm_url . "\">" . $confirm_url . "</a>",$msg);
    
        // Instantiate your new class
        $mail_msg = new my_phpmailer;
    
        // Now you only need to add the necessary stuff
        $mail_msg->AddAddress($email, $first_name." ".$last_name); // Who its sent to
        $mail_msg->Subject  = $MSG_LANG_NEW['mail_subject'];
        $mail_msg->Body     = $msg;
        $mail_msg->AltBody  = $text_msg;
    
        if ($mail_msg->Send())
        {
                    
            Adminlog(MAIL_SENT,$mail_msg->ErrorInfo);
            
        }
        else
        {
            //this needs to be replaced with an error page template
            echo "<font color=\"red\">".$MSG_LANG_NEW['email_failed']."</font><br>\n";
            echo $MSG_LANG_NEW['return'];
           Adminlog(MAIL_FAIL,"$email $username".$mail_msg->ErrorInfo);
           die();
        }
    
        $mail_msg->ClearAddresses();
    	   $_SESSION['pref_language'] = $pref_language;
	   //OK we're done here, go to the success page
	   header("Location: completed.php");
	   exit;
   }
   else
   {
       $postvar = $_POST;
       $smarty->assign('errors',$errors);
       $smarty->assign('postvar',$postvar);
   }
	    

}



$smarty->assign('lang',$MSG_LANG_NEW);

$smarty->assign('title',$title);
$smarty->assign('title2',$title2);
$smarty->assign('country_code',$lang_country[0]);
$smarty->assign('country_list',$lang_country[1]);
$smarty->assign('state_code',$lang_state[0]);
$smarty->assign('state_names',$lang_state[1]);


//include_once('header.php'); //-> these header and footer files will be stuff included to every page. 
$smarty->display($template_set.'/new_player.tpl');
//include_once('footer.php');

//TODO: Create header.php, footer.php, header.tpl, footer.tpl benchmark timer in header, output in footer
//also in header, check if admin auth, and display link to admin, if allowed. 
//add support for ACL's , admin can assign moderators, co-admins as desierd.


?>