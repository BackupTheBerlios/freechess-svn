<?php
##############################################################################################
#                                                                                            #
#                                sendmessage.php                                                
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
	include_once ('config.php');

	require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");
/* load settings */

function proof_add($proof) {
	if (isset($proof)) {
		if (!get_magic_quotes_gpc()) {
		$proofed = addslashes($proof);
		} else {
		$proofed = $proof;
		}
	return $proofed;
	}
return $proof;
}
function proof_strip($proof) {
	if (isset($proof)) {
		$proofed = stripslashes($proof);
	return $proofed;
	}
return $proof;
}
	////////////// Chat verlassen Script ///////////////////////////////////////
	global $t_banned_users;

 if (in_array($_SESSION['playerID'], $t_banned_users))
 {
 	echo "<b>Privileges have been revoked due chat abuse.  Please contact admin.</b>";
         exit;
 }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta name="generator" content="Adobe GoLive 6">
		<title>Private Message</title>

<?
// require "constants.php";
include_once ('config.php');
require "connectdb.php";
$theme=$_SESSION["pref_colortheme"];
$id=$_SESSION['playerID'];
#print_r ($_GET['to']);
#print_r ($_GET['tomsg']);

if(!empty($_POST['newMessage']))
{
	echo("<PRE>");
#	print_r($_POST);
	echo("</PRE>");
        $fromPerson=($_POST['from'])?$_POST['from']:"NULL";
        $toPerson=($_POST['to'])?$_POST['to']:"NULL";
#	$toCommID=($_GET['tomsg'])?$_GET['tomsg']:0;

	echo("From $fromPerson, To $toPerson<br>");
	
        $OKForMessage=true;

        if (($fromPerson==NULL)||($toPerson==NULL))
        {
                $OKForMessage=false;
                //MUST BE AN ADMIN TO SEND MESSAGE EITHER
                // FROM ADMINS
                // OR
                // TO ALL
                if ($_SESSION['isAdmin'])
                $OKForMessage=true;
        }


        if($OKForMessage){
        $mGame=($_POST['forGame'])?$_POST['from']:"NULL";
        $msgtitle=proof_add($_POST['txtTitle']);
        $msgtext=proof_add($_POST['txtMessage']);
        if($_POST['msgType']=="Article") 
                $msgtype="0";
        else
                $msgtype="1";
	if (($_GET['tomsg'])==0) {
        $sql = "INSERT INTO communication (gameID,fromID,toID,title,text,postDate,expireDate,ack,commType,CommID2) ";
        $sql .= "VALUES ( $mGame , $fromPerson , $toPerson, '$msgtitle', '$msgtext', NOW( ) , NULL , '0', '$msgtype', '0' );";
        mysql_query($sql) or die("can't do query: $sql");
	} else {
#	$tocommid = ($_GET['tomsg'])
	$sql = "INSERT INTO communication (gameID,fromID,toID,title,text,postDate,expireDate,ack,commType,CommID2) ";
        $sql .= "VALUES ( $mGame , $fromPerson , $toPerson, '$msgtitle', '$msgtext', NOW( ) , NULL , '0', '$msgtype', '" . $_GET['tomsg'] . "' );";
        mysql_query($sql) or die("can't do query: $sql");
	}
	$sql="UPDATE communication set commID2=commID where commID2=0;";
	mysql_query($sql);


?>
<script language="javascript">
window.close()
</script>
<?
die();
}
}

?>
<script language="JavaScript">
<!--

function setimgurl(type1,type2) {
 pic_text = "Name (with extension) indicate the picture (after <? echo $Bildverzeichnis; ?>/)";
 pic_content = "<? echo $Bildverzeichnis; ?>/";
 picurl = prompt(pic_text,pic_content);
 link_text = "Include link with picture? (optional)";
 link_content = "http://";
 url = prompt(link_text,link_content);
 if ((picurl != null) && (picurl != "")) {
  if ((url != null) && (url != "http://") && (url != ""))
   document.posting.news.value += "["+type2+"="+url+"]"+"["+type1+"="+picurl+"]"+"[/"+type2+"] ";
  else
   document.posting.news.value += "["+type1+"="+picurl+"] ";
  }
 document.posting.news.focus();
}


function smilies(Zeichen) {
  document.FormName.txtMessage.value =
  document.FormName.txtMessage.value + Zeichen;
}


function setcode(code,prompttext) {
		inserttext = prompt("Enter format text:"+"\n",prompttext);
		if ((inserttext != null) && (inserttext != ""))
		document.FormName.txtMessage.value += "["+code+"]"+inserttext+"[/"+code+"] ";
	document.FormName.txtMessage.focus();
}


function seturl(type) {
	description = prompt("Enter Name of Link to Email Address? (optional)","");
	if (type == "URL") {
		text = "Enter Link";
		content = "http://";
		}
	else {
		text = "Enter Email Address.";
		content = "";
		}
	url = prompt(text,content);
	if ((url != null) && (url != "")) {
		if ((description != null) && (description != ""))
			document.FormName.txtMessage.value += "["+type+"="+url+"]" +description+  "[/"+type+"] ";
		else
			document.FormName.txtMessage.value += "["+type+"]"+url+"[/"+type+"] ";
		}
	document.FormName.txtMessage.focus();
}

function setimgurl(type1,type2) {
	pic_text = "Indicate Url of Picture";
	pic_content = "http://";
	picurl = prompt(pic_text,pic_content);
	link_text = "Include a link with picture? (optional)";
	link_content = "http://";
	url = prompt(link_text,link_content);
	if ((picurl != null) && (picurl != "")) {
		if ((url != null) && (url != "http://") && (url != ""))
			document.FormName.txtMessage.value += "["+type2+"="+url+"]"+"["+type1+"="+picurl+"]"+"[/"+type2+"] ";
		else
			document.FormName.txtMessage.value += "["+type1+"="+picurl+"] ";
		}
	document.FormName.txtMessage.focus();
}


//-->
</script>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/mainstyles.css" type="text/css">

	</head>

	<body>
		<div align="center">
		<form action="" method="post" name="FormName">
				<br>
				<? echo("".$MSG_LANG["to"]."\n"); ?>
				<input type="hidden" name="from" value="<?=$id?>">
				<select name="to" size="1">
<?
$tempo = time()-1209600;  
					$tmpQuery="SELECT playerID,firstName,lastUpdate FROM players WHERE lastUpdate>='$tempo' and ativo<>'0' and pontos>0 and playerID <> ".$id." ORDER BY firstName ASC";
	                                $tmpPlayers = mysql_query($tmpQuery) or die("Sorry: $tmpQuery");
                                        while($tmpPlayer = mysql_fetch_array($tmpPlayers, MYSQL_ASSOC))
                                        {
                                                if ($tmpPlayer['firstName']){
						if($tmpPlayer['playerID']==$_GET['to'])
        	                                        echo("<option value='".$tmpPlayer['playerID']."' selected>".$tmpPlayer['firstName']."</option>\n");
						else
                	                                echo("<option value='".$tmpPlayer['playerID']."'> ".$tmpPlayer['firstName']."</option>\n");
						}
                                        }

?>				
				</select>
				<br>
				<? echo("".$MSG_LANG["msgsubject"]."\n"); ?><br>
				<input type="text" name="txtTitle" size="54" border="0" <? if(!empty($_GET['retitle'])) {echo "value=\"Re: ".$_GET['retitle']."\"";}?>>
				<br>
				<? echo("".$MSG_LANG["msgtext"]."\n"); ?><br>
                		<input type="button" name="[b]" title="Bold" value=" B " onClick="javascript:setcode('B','')">
				<input type="button" name="[i]" title="Italicize" value=" I " onClick="javascript:setcode('I','')">
				<input type="button" name="[u]" title="Underline" value=" U " onClick="javascript:setcode('U','')">
				<input type="button" name="[url]" title="Link" value="http" onClick="javascript:seturl('URL')">
                		<input type="button" name="[email]" title="Email-Link" value="@" onClick="javascript:seturl('EMAIL')">
                		<input type="button" name="[#]" title="Code-Text" value="#" onClick="javascript:setcode('CODE','')">
                		<input type="button" name="[quote]" title="Include a quote" value="Quote" onClick="javascript:setcode('QUOTE','')")>
                		<input type="button" name="[img]" title="Add an image" value="image" onClick="javascript:setimgurl('IMG','URL')">

				<textarea name="txtMessage" rows="7" cols="52" tabindex="1"></textarea><br>
				<strong><a href="chatrules.php" target="_blank">Please Read the Chat Rules
				Before Posting Here! </a><br>
Violation of the rules will result in loss of communication privileges.</strong> <br>		  
          <input type="submit" name="newMessage" style="cursor:hand" value="<?=$MSG_LANG["sendmessage"];?>" border="0"> 
		        <input type="button" name="btnCancel" value="<?=$MSG_LANG["cancel"];?>" style="cursor:hand" border="0" onClick="javascript:window.close();">
          <br>
          <br>
          <table width="475" border="1">
            <tr>
              <th>Our Sponsors</th>
            </tr>
            <tr>
              <td>
                <div align="left">
                  <script type="text/javascript"><!--
google_ad_client = "pub-9606600691278870";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_channel ="8430048315";
google_color_border = "CCCCCC";
google_color_bg = "FFFFFF";
google_color_link = "000000";
google_color_url = "666666";
google_color_text = "333333";
//--></script>
                  <script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                </script>
                </div>
              </td>
            </tr>
          </table>
          <br>
          <? echo("".$MSG_LANG["papahat"]."\n"); ?>
		  </form>
	</div>
</body>

</html>
