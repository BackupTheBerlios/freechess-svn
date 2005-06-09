<?php

//******************************************************************* 
//  File:		inc.recommend.php © GateQuest, Inc. - gatequest.net
//  Author:		D Stewart  (dstewart@gatequest.net)
//  Date:		2004-01-19
//  Version:	1.5
//  Notes:		This notice must stay intact for use.
//*******************************************************************


/* Config Section */

$yourSiteName = "Webmaster";

$yourUrl = "http://www.Webmaster"; 

$webmasterEmail = "webmaster@Webmaster";  // Your Email address

$receiveNotifications = 1;  // 0 for no, 1 for yes. If yes, you will be know the recipients and the message.

$messagecolor = "#F04637";  // Error message color.

$subject = "Great Chess Website Recommendation from $yourName ($yourEmail)";  //Email Subject Line.

$sendMessage = "Hello,\n\n**YourName** thought you would like to visit this chess playing site:\n\n$yourUrl";  // Message in Email body.

/* End Config */


if($action == "go") {
  
  if(empty($yourName)) {
  $message .= "Please enter your name.<br />";
  $error = 1;
  }
  
  if(empty($yourEmail)) {
  $message .= "Please enter your email address.<br />";
  $error = 1;
  }
  else {
    if(!isValid($yourEmail)) {
    $message .= "Please enter a proper email address for yourself.<br />";
    $error = 1;
    }
  }
  if(empty($a1) && empty($a2) && empty($a3) && empty($a4) && empty($a5)) {
  $message .= "Please enter at least one friend's email address.<br />";
  $error = 1;
  }
 
 $emailList = array(); // create empty array
 if(!empty($a1)) {
   if(!isValid($a1)) {
     $finalM .= "Email 1 was not valid and no message was sent to it.<br />";
     }
    else {
   $amount = $amount + 1;
   array_push($emailList,$a1);
     }
   }
 if(!empty($a2)) {
   if(!isValid($a2)) {
     $finalM .= "Email 2 was not valid and no message was sent to it.<br />";
     }
    else {
   $amount = $amount + 1;
   array_push($emailList,$a2);
     }
   }

  if(!empty($a3)) {
   if(!isValid($a3)) {
     $finalM .= "Email 3 was not valid and no message was sent to it.<br />";
     }
    else {
   $amount = $amount + 1;
   array_push($emailList,$a3);
     }
   }

  if(!empty($a4)) {
   if(!isValid($a4)) {
     $finalM .= "Email 4 was not valid and no message was sent to it.<br />";
     }
    else {
   $amount = $amount + 1;
   array_push($emailList,$a4);
     }
   }
	
  if(!empty($a5)) {
   if(!isValid($a5)) {
     $finalM .= "Email 5 was not valid and no message was sent to it.<br />";
     }
    else {
   $amount = $amount + 1;
   array_push($emailList,$a5);
     }
   }
 reset($emailList); // Set the array pointer to the beginning

  // Now if there are no errors, send the message.
 if($error == 0) {
   $sendMessage = str_replace("**YourName**",$yourName,$sendMessage);
   foreach($emailList as $to) {
    if(!mail($to,$subject,"$sendMessage\n\n" . $customMessage . "\n\n\nNote: This message was not sent unsolicited.  It was sent through a form located at $yourUrl.  If you believe this message was received on error, please disregard it.",
    "From: $yourEmail\r\n"
   ."Reply-To: $yourEmail\r\n"
   ."X-Mailer: GateQuest Recommend Us Script\r\n"))
       {
    $finalM .= "<br />Message was not successfully sent to ${to}.  Please try again later.<br />";
       } // != mail()
     else {
      $finalM .= "<br />Message was sent to $to.<br />";
	$reciplist .= " $to,"; // To be used in notifications
     } // end != else 
   } // end for each
  } // end error if

 if ($error == "1") {
    echo "<font color=\"$messagecolor\">$message</font><br /><br /><a href=javascript:history.go(-1)>Go Back</a>\n";
    }
 if ($finalM) {
    echo "$finalM<br />Thank you very much for recommending $yourSiteName.<br />\n";
    if($receiveNotifications) {
	   @mail($webmasterEmail,"Someone Recommended Your Site","\nThis is a message to tell you that $yourName ($yourEmail) sent a website recommendation to$reciplist.\n\nMessage:  $customMessage",
	   "From: $webmasterEmail\r\n"
        ."X-Mailer: GateQuest Recommend Us Script");
        } // end if receive notifications.
	}

} // end main if

else {
echo<<<EOD
<table width=350>
    <form method="POST" action="$PHP_SELF">
    <tr>
        <td class="formtext" $nameError>Your Name:</td>
        <td $nameError><input type=text name=yourName onfocus="this.style.borderColor='#0072BC';" onblur="this.style.borderColor='silver';"></td>
    </tr>
    <tr>
        <td class="formtext" $yourEmailError>Your Email:</td>
        <td $yourEmailError><input type=text name=yourEmail onfocus="this.style.borderColor='#0072BC';" onblur="this.style.borderColor='silver';"></td>
    </tr>
	<tr>
		<td colspan="2">Recipient Email Addresses&nbsp;&nbsp;º <span class="basefontblue">at least one</span> º<br /><br /></td>
	</tr>	
	<tr>
		<td class="formtext">1:</td>
		<td><input type=text name=a1 onfocus="this.style.borderColor='#0072BC';" onblur="this.style.borderColor='silver';"></td>
	</tr>	
	<tr>
		<td class="formtext3">2:</td>
		<td><input type=text name=a2 onfocus="this.style.borderColor='#0072BC';" onblur="this.style.borderColor='silver';"></td>
	</tr>	
	<tr>
		<td class="formtext3">3:</td>
		<td><input type=text name=a3 onfocus="this.style.borderColor='#0072BC';" onblur="this.style.borderColor='silver';"></td>
	</tr>	
	<tr>
		<td class="formtext3">4:</td>
		<td><input type=text name=a4 onfocus="this.style.borderColor='#0072BC';" onblur="this.style.borderColor='silver';"></td>
	</tr>	
	<tr>
		<td class="formtext3">5:</td>
		<td><input type=text name=a5 onfocus="this.style.borderColor='#0072BC';" onblur="this.style.borderColor='silver';"></td>
	</tr>
	<tr>
		<td colspan="2">Your Message&nbsp;&nbsp;º <span class="basefontblue">Optional</span> º<br /><textarea name=customMessage rows=5 cols=31 onfocus="this.style.borderColor='#0072BC';" onblur="this.style.borderColor='silver';"></textarea></td>
	</tr>
	<tr> 
		<td colspan="2"><br />
			<table align=center>
				<tr>
					<td ><input class="send" type=submit value="Send Message" /></td>
					<td class="formtext">&nbsp;</td>
					<td><input class="reset" type="reset" value="Reset Form" /></td>
					<td><input type=hidden name=action value="go"></td>
				</tr>
			</table>
		</td>
	</tr>
	</form>
</table>

EOD;
} // end main else

function isValid($email) { 
  if(eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,3}$", $email)) return TRUE; 
  else return FALSE; 
  }

?>