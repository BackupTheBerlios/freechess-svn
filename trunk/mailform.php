<?php
##############################################################################################
#                                                                                            #
#                                mailform.php                                                
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
	//require_once ( 'chessconstants.php');
	//require_once ( 'newgame.php');
	require_once('chessdb.php');

	/* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
	fixOldPHPVersions();

	/* if this page is accessed directly (ie: without going through login), */
	/* player is logged off by default */
	if (!isset($_SESSION['playerID']))
		$_SESSION['playerID'] = -1;
	
	/* connect to database */
	require_once( 'connectdb.php');


	/* check session status */
	require_once('sessioncheck.php');

	/* Language selection */
	require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");
	
if (!isset($_GET['voltar']))
    $voltar = "mainmenu.php";
else $voltar = $_GET['voltar'];

?>


<?php

/*

    Change the email address to your own.

    $empty_fields_message and $thankyou_message can be changed
    if you wish.

*/

// Change to your own email address
$your_email = "webmaster@Webmaster";

// This is what is displayed in the email subject line
// Change it if you want
$subject = "Message from Webmaster";

// This is displayed if all the fields are not filled in
$empty_fields_message = "<p>Please go back and complete all the fields in the form.</p>";

// This is displayed when the email has been sent
$thankyou_message = "<p>Thankyou. Your message has been sent. </p>";

// You do not need to edit below this line

$name = stripslashes($_REQUEST['txtName']);
$email = stripslashes($_REQUEST['txtEmail']);
$message = stripslashes($_REQUEST['txtMessage']);

if (!isset($_POST['txtName'])) {

?><title>Web Master Contact</title><body bgcolor="#FFFFFF" text="#000000">
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/mainstyles.css" type="text/css">

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

    <p align="center"><label for="txtName"></label>
      <img hpacing=1 src=../images/BlogLogo.gif width="100" height="88"><img src="../images/chessmaniaccom.gif" width="215" height="28"><br>
Contact Site Admin of Webmaster<br>
      <br />
      <label for="txtName">Your Name:</label>
    <input name="txtName" type="text" title="Enter your name" size="20" />
    </p>

    <p align="center"><label for="txtEmail"></label><br />
      <label for="txtEmail">Your Email:</label>
    <input name="txtEmail" type="text" title="Enter your email address" size="20" />
    </p>

    <p align="center"><label for="txtMessage">Your message:</label><br />
    <textarea name="txtMessage" cols="50" rows="10" title="Enter your message"></textarea>
    </p>

    <p align="center"><label title="Send your message">
    <input type="submit" value="Send" /></label></p>

</form>

<?php

}

elseif (empty($name) || empty($email) || empty($message)) {

    echo $empty_fields_message;

}

else {

    mail($your_email, $subject, $message, "From: $name <$email>");

    echo $thankyou_message;

}

?>
<form method="get" action="http://www.qksrv.net/interactive" target="_top" >
<img src="http://www.qksrv.net/image-1516411-10360420" height="1" width="1" border="0">
<table width="300" border="0" cellpadding="5" cellspacing="0">
<tr>
<td valign="top"><img src="http://altura.speedera.net/ccimg.catalogcity.com/200000/209600/209663/Products/4614681.jpg" border="0" alt="Hammacher Schlemmer:: Stone Chess and Table Set (Chess)">  <p>&nbsp;</p>
</td>
</tr>
<tr>
  <td valign="top"><p><b><font size="4">Hammacher Schlemmer:: Stone
          Chess and Table Set (Chess)</font></b></p>
    <p><font size="2">This exceptionally durable chess set can be left outside
        all year long without fear of damage from weather, so there's no need
        to ferry a board and pieces back and forth from house to garden every
        time you play. This traditional Staunton-styled set is constructed of
        stout marble and resin: the pieces are light enough to be easily moved
        during games yet solid and stable enough to stay put in wind and inclement
        weather, and unlike many flimsy chess sets, the board is a full 1-inch
        thick. Frost-proof, the pieces and board resist cracking, fading, and
        erosion for years of outdoor play (the handsome set can also be used
        indoors). Chess and Table Set. The same heavy-duty construction as the
        board and pieces, this set includes two stools and a sturdy table with
        Celtic ribbon design. Please allow 4-6 weeks for delivery. Premium and
        Express Delivery not available.</font></p>
    <hr>
    <input type="hidden" name="pid" value="1516411">
    <input type="hidden" name="aid" value="10360420">
    <input type="hidden" name="url" value="http://amos.SHOP.COM/cc.class/cc?pcd=4614681&ccsyn=22/adtg/07010415">
    <input name="submit" type="submit" value="Buy"></td>
  </tr>
</table>
</form>
