<?php
##############################################################################################
#                                                                                            #
#                                newuser.php                                                
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
	
	if (!isset($_CHESSUTILS))
		require_once ( 'chessutils.php');
	
	fixOldPHPVersions();

 require("config.php");

	if ($_COOKIE["cookie_language"] != "")
		$lang = $_COOKIE["cookie_language"];

	if ($_GET["language"] != "")
		$lang = $_GET["language"];

	if ($lang == "")
		$lang = $CFG_DEFAULT_LANGUAGE;
        
	/* Language selection */
        require "languages/".$lang."/strings.inc.php";

	setcookie("cookie_language",$lang,time()+60*60*24*365*2);
	$language = $lang;
?>

<html>
<head>
	<title><?=$MSG_LANG["newuser"]?></title>
	<script type="text/javascript">
		
		function validateForm()
		{
			if (document.userdata.pwdPassword.value != "" && document.userdata.pwdPassword.value == document.userdata.pwdPassword2.value && document.userdata.agree.checked == 1)
				document.userdata.submit();
			else if(document.userdata.agree.checked == 0)
				alert('Please check the box to continue.');
			else
				alert('<?=$MSG_LANG["check2password"]?>');
		}
		
//  End -->
</script>

	
</head>

<body bgcolor=white text=black>
<table width="100%" border="0">
  <tr>
    <td width="44%"><font face="Verdana">
      <object id="VHSS" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width=200 height=150>
        <param name=movie value="http://vhost.oddcast.com/vhsssecure.php?doc=http%3A%2F%2Fvhost.oddcast.com%2Fgetshow.php%3Facc%3D10552%26ss%3D148283%26sl%3D0%26embedid%3D491b9a46291cce0609dbf9e831264b06&edit=0&acc=10552&firstslide=1&loading=1&bgcolor=0xFFFFFF&minimal=1">
        <param name=quality value=high>
        <param name=scale value=noborder>
        <param name=bgcolor value=#FFFFFF>
        <embed src="http://vhost.oddcast.com/vhsssecure.php?doc=http%3A%2F%2Fvhost.oddcast.com%2Fgetshow.php%3Facc%3D10552%26ss%3D148283%26sl%3D0%26embedid%3D491b9a46291cce0609dbf9e831264b06&edit=0&acc=10552&firstslide=1&loading=1&bgcolor=0xFFFFFF&minimal=1" swliveconnect=true name="VHSS" quality=high scale=noborder bgcolor=#FFFFFF width=200 height=150 type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed>
      </object>
      <img src="../images/chessmaniaccom.gif" width="215" height="28"> <img src="../images/smallWorld.gif" alt="World of Free Chess" width="75" height="75">
      <img src="../images/newusr1.gif" width="160" height="98"> </font>
      <table width="100%" border="0" bgcolor="#000000">
        <tr>
          <td height="1"></td>
        </tr>
      </table>
      <font face="Verdana">     
      <form method="post" action="mainmenu.php" name="login">
  
    
        <b>
        <font face="Verdana" size="2"><?=$MSG_LANG["language"]?>:</font></b>
        <select name="language" onChange="window.location='newuser.php?language='+document.login.language.value">
		    <?
			if ($handle = opendir('languages')) {
    			while (false !== ($file = readdir($handle))) {
					if (strpos($file,"CVS") === FALSE && $file != "." && $file != "..")
              			$languages[] = $file;
    			}
    		closedir($handle);
			}
			sort($languages);

			if ($MSG_LANG[strtolower($t)] == "")
			    $MSG_LANG[strtolower($t)] = $t;
				    
			foreach($languages as $t){
				if ($_SESSION['pref_language'] == $t)
				    $s = "SELECTED";
				    else $s="";
			    echo "<option value='$t' $s>".$MSG_LANG[strtolower($t)]."</option>";
			}
			?>
	    </select>
      </form>
      </font>
</table>

<font size="+1"><strong>Sign up for your free account NOW! </strong></font>
<form name="userdata" method="post" action="mainmenu.php">
	<input type="hidden" name="rdoHistory" value="pgn">
	<input type="hidden" name="rdoTheme" value="beholder">
	<input type="hidden" name="txtReload" value="25">
	<input type="hidden" name="language" value="<?=$language?>">
	<input type="hidden" name="redoSound" value="off">
	<input type="hidden" name="redoConfirmmove" value="1">

	<table border=1>
		<tr>
			<th colspan="2" align="center">
				<p align="center"><b><?=$MSG_LANG["personalinformation"]?></b></th>
		</tr>

		<tr>
			<th width="200" style="text-align:left">
				<b><?=$MSG_LANG["name"]?>: <font color=red>*</font></b>
			</th>
			
			<td style="text-align:left">
				<input name="txtFirstName" type="text" value="<? echo($_POST['txtFirstName']); ?>" size="20">
				<BR>(<font size=+1 font color='RED'><b><?= $MSG_LANG["firstbox"]?></font></b>)
			</td>
		</tr>

		<tr>
			<th style="text-align:left" width="200">
				<b><?=$MSG_LANG["username"]?>: <font color=red>*</font></b>
			</th>

			<td style="text-align:left">
				<input name="txtNick" type="text" size="20">
				<BR>(<?= $MSG_LANG["secondbox"]?>)
			</td>
		</tr>

		<tr>
			<th style="text-align:left" width="200">
				<b><?=$MSG_LANG["password"]?>: <font color=red>*</font></b>
			</th>

			<td style="text-align:left">
				<input name="pwdPassword" type="password" size="20">
			</td>
		</tr>

		<tr>
			<th style="text-align:left" width="200">
				<b><?=$MSG_LANG["confirmpassword"]?>: <font color=red>*</font></b>
			</th>

			<td style="text-align:left">
				<input name="pwdPassword2" type="password" size="20">
			</td>
		</tr>

		<tr valign="top">
			<th style="text-align:left" width="200"><b>E-mail: <font color=red>*</font></b></th>
			<td style="text-align:left">
				<input type="text" name="email" size="20">
				<BR>(<?= $MSG_LANG["validemail"]?>)
			</td>
		</tr>
		
		<tr valign="top">
          <td><div align="left"><font color="#FF0000" size="-1"> <strong>
              <?=$MSG_LANG["e-mailnotification"]?>
      : </strong></font></div>
          </td>
          <td bgcolor="#FFFFFF" style="text-align:left">
            <input type="text" name="txtEmailNotification" value="">
            <br>
            <font color="#FF0000">(
            <? if ($_SESSION['pref_emailnotification'] != "") { ?>
            <? } ?>
            <?=$MSG_LANG["emailmsg"]?>
    ) </font> </td>
	  </tr>
		<tr valign="top">
          <th style="text-align:left"><b>
            <?=$MSG_LANG["whereareyoufrom"]?>
            :<font color=red>*</font></b></th>
          <td style="text-align:left">
            <BR>
            <?=$MSG_LANG["country"]?>
            :
            <select name="pais">
            <option value="Citizen of Earth" <?=$f1?>>Citizen of Earth
            <option value="USA" <?=$f2?>>USA
            <option value="France" <?=$f3?>>France
            <option value="Germany" <?=$f4?>>Germany
            <option value="Canada" <?=$f5?>>Canada
            <option value="Greece" <?=$f6?>>Greece
            <option value="New Zealand" <?=$f7?>>New Zealand
            <option value="Afghanistan" <?=$f7a?>>Afghanistan
            <option value="Albania" <?=$f7b?>>Albania
            <option value="Algeria" <?=$f7c?>>Algeria
            <option value="American Samoa" <?=$f7d?>>AmericanSamoa
            <option value="Andorra" <?=$f7e?>>Andorra
            <option value="Angola" <?=$f7f?>>Angola
            <option value="Anguilla" <?=$f7g?>>Anguilla
            <option value="Antigua and Barbuda" <?=$f7h?>>Antigua and Barbuda
            <option value="Argentina" <?=$f8?>>Argentina
            <option value="Armenia" <?=$f8a?>>Armenia
            <option value="Aruba" <?=$f8b?>>Aruba
            <option value="Australia" <?=$f9?>>Australia
            <option value="Austria" <?=$f10?>>Austria
            <option value="Azerbaijan" <?=$f10a?>>Azerbaijan
            <option value="Azores" <?=$f10b?>>Azores
            <option value="Bahamas The" <?=$f10c?>>Bahamas The
            <option value="Bahrain" <?=$f10d?>>Bahrain
            <option value="Bangladesh" <?=$f10e?>>Bangladesh
            <option value="Barbados" <?=$f10f?>>Barbados
            <option value="Belarus" <?=$f10g?>>Belarus
            <option value="Belgium" <?=$f11?>>Belgium
            <option value="Belize" <?=$f11a?>>Belize
            <option value="Benin" <?=$f11b?>>Benin
            <option value="Bermuda" <?=$f11c?>>Bermuda
            <option value="Bhutan" <?=$f11d?>>Bhutan
            <option value="Bolivia" <?=$f12?>>Bolivia
            <option value="Bosnia and Herzegovina" <?=$f12a?>>BosniaandHerzegovina
            <option value="Botswana" <?=$f12b?>>Botswana
            <option value="Brazil" <?=$f13?>>Brazil
            <option value="British Virgin Islands" <?=$f13a?>>BritishVirginIslands
            <option value="Brunei" <?=$f13b?>>Brunei
            <option value="Bulgaria" <?=$f13c?>>Bulgaria
            <option value="Burkina Faso" <?=$f13d?>>BurkinaFaso
            <option value="Burundi" <?=$f13e?>>Burundi
            <option value="Cambodia" <?=$f13f?>>Cambodia
            <option value="Cameroon" <?=$f13g?>>Cameroon
            <option value="Cape Verde" <?=$f13h?>>CapeVerde
            <option value="Cayman Islands" <?=$f13i?>>CaymanIslands
            <option value="Central African Republic" <?=$f13j?>>CentralAfricanRepublic
            <option value="Chad" <?=$f13k?>>Chad
            <option value="Chile" <?=$f14?>>Chile
            <option value="China" <?=$f15?>>China
            <option value="Colombia" <?=$f15a?>>Colombia
            <option value="Comoros" <?=$f15b?>>Comoros
            <option value="Congo Republic Of The" <?=$f15c?>>CongoRepublicOfThe
            <option value="Congo The Democratic Republic Of The" <?=$f15d?>>Congo TheDemocraticRepublicOfThe
            <option value="Cook Islands" <?=$f?>>CookIslands
            <option value="Costa Rica" <?=$f16?>>Costa Rica
            <option value="Croatia" <?=$f17?>>Croatia
            <option value="Cuba" <?=$f17a?>>Cuba
            <option value="Cyprus" <?=$f17b?>>Cyprus
            <option value="Czech Republic" <?=$f18?>>czechrepublic
            <option value="Denmark" <?=$f19?>>Denmark
            <option value="Djibouti" <?=$f19a?>>Djibouti
            <option value="Dominica" <?=$f19b?>>Dominica
            <option value="Dominican Republic" <?=$f19c?>>DominicanRepublic
            <option value="England" <?=$f20?>>England
            <option value="Faroe Islands" <?=$f20a?>>faroeislands
            <option value="Fiji" <?=$f20b?>>fiji
            <option value="Finland" <?=$f20c?>>Finland
            <option value="French Polynesia" <?=$f20d?>>frenchpolynesia
            <option value="Gabon" <?=$f20e?>>Gabon
            <option value="Gambia" <?=$f20f?>>Gambia
            <option value="Georgia" <?=$f20g?>>Georgia
            <option value="Ghana" <?=$f20h?>>Ghana
            <option value="Gibraltar" <?=$f20i?>>Gibraltar
            <option value="Greenland" <?=$f21?>>Greenland
            <option value="Grenada" <?=$f21a?>>Grenada
            <option value="Guam" <?=$f21b?>>Guam
            <option value="Guatemala" <?=$f22?>>Guatemala
            <option value="Guernsey" <?=$f22a?>>Guernsey
            <option value="Guinea" <?=$f22b?>>Guinea
            <option value="Guinea-Bissau" <?=$f22c?>>Guinea-Bissau
            <option value="Guyana" <?=$f22d?>>Guyana
            <option value="Haiti" <?=$f22e?>>Haiti
            <option value="Honduras" <?=$f23?>>Honduras
            <option value="Honk Kong" <?=$f23a?>>Hong Kong
            <option value="Hungary" <?=$f24?>>Hungary
            <option value="Iceland" <?=$f25?>>Iceland
            <option value="India" <?=$f25a?>>India
            <option value="Indonesia" <?=$f25b?>>Indonesia
            <option value="Iran" <?=$f26?>>Iran
            <option value="Iraq" <?=$f26a?>>Iraq
            <option value="Ireland" <?=$f27?>>Ireland
            <option value="Isle of Man" <?=$f27a?>>Isle of Man
            <option value="Israel" <?=$f28?>>Israel
            <option value="Italy" <?=$f29?>>Italy
            <option value="Ivory Coast" <?=$f29a?>>Ivory Coast
            <option value="Jamaica" <?=$f29b?>>Jamaica
            <option value="Japan" <?=$f30?>>Japan
            <option value="Jersey" <?=$f30a?>>Jersey
            <option value="Jordan" <?=$f30b?>>Jordan
            <option value="Kazakhstan" <?=$f30c?>>Kazakhstan
            <option value="Kenya" <?=$f30d?>>Kenya
            <option value="Kiribati" <?=$f30e?>>Kiribati
            <option value="Kuwait" <?=$f31?>>Kuwait
            <option value="Kyrgyzstan" <?=$f31a?>>Kyrgyzstan
            <option value="Laos" <?=$f31b?>>Laos
            <option value="Latvia" <?=$f31c?>>Latvia
            <option value="Lebanon" <?=$f31d?>>Lebanon
            <option value="Liberia" <?=$f31e?>>Liberia
            <option value="Libya" <?=$f31f?>>Libya
            <option value="Lichtenstein" <?=$f31g?>>Lichtenstein
            <option value="Lithuania" <?=$f31h?>>Lithuania
            <option value="Luxembourg" <?=$f31i?>>Luxembourg
            <option value="Poland" <?=$f31j?>>Poland
			<option value="Malaysia" <?=$f32?>>Malaysia
            <option value="Mexico" <?=$f32a?>>Mexico
			<option value="Montenegro" <?=$f32b?>>Montenegro
			<option value="Netherlands" <?=$f32c?>>Netherlands
			<option value="Norway" <?=$f33?>>Norway
            <option value="Romania" <?=$f33a?>>Romania
            <option value="Russia" <?=$f34?>>Russia
			<option value="Saint Helena" <?=$f34a?>>Saint Helena
            <option value="Saint Kitts & Nevis" <?=$f34b?>>Saint Kitts & Nevis
            <option value="Saint Lucia" <?=$f34c?>>Saint Lucia
            <option value="Saint Vincent & Grenadines"<?=$f34d?>>Saint Vincent & Grenadines
            <option value="San Marino" <?=$f34e?>>San Marino
            <option value="Saudi Arabia" <?=$f34f?>>Saudi Arabia
            <option value="Scotland" <?=$f35?>>Scotland
            <option value="Seychelles" <?=$f35a?>>Seychelles
            <option value="Serbia" <?=$f35b?>>Serbia
			<option value="Sierra Leone" <?=$f35c?>>Sierra Leone
            <option value="Slovenia" <?=$f35d?>>Slovenia
            <option value="Solomon Islands" <?=$f35e?>>Solomon Islands
            <option value="South Africa" <?=$f36?>>South Africa
            <option value="South Korea" <?=$f36a?>>South Korea
            <option value="Spain" <?=$f37?>>Spain
            <option value="Suriname" <?=$f37a?>>Suriname
            <option value="Swaziland" <?=$f38?>>Swaziland
            <option value="Sweden" <?=$f39?>>Sweden
            <option value="Switzerland" <?=$f39a?>>Switzerland
            <option value="Syria" <?=$f40?>>Syria
            <option value="Taiwan" <?=$f40a?>>Taiwan
            <option value="Thailand" <?=$f40b?>>Thailand
            <option value="Togo" <?=$f40c?>>Togo
            <option value="Tunisia" <?=$f40d?>>Tunisia
            <option value="Turkey" <?=$f40e?>>Turkey
            <option value="Tuvalu" <?=$f40f?>>Tuvalu
            <option value="Uganda" <?=$f40g?>>Uganda
           <option value="Uruguay" <?=$f40h?>>Uruguay
			<option value="Vanuatu" <?=$f40i?>>Vanuatu
            <option value="Vatican City" <?=$f40j?>>Vatican City
            <option value="Virgin Islands" <?=$f40k?>>Virgin Islands
            <option value="Wales" <?=$f41?>>Wales
            <option value="Zambia" <?=$f41a?>>Zambia
            <option value="Zimbabwe" <?=$f42?>>Zimbabwe
            <option value="Other" <?=$f43?>>Other	
            </select>
          </td>
	  </tr>
		<tr>
			<td colspan="2" bgcolor="#FFFFFF">
				<p align="center">
				


<p>
  <center>
<font face="arial, helvetica" size"-2"></center>
  <p align="center">
		  <!-- Script Size:  1.21 KB -->
				I Agree to these terms and am over or exactly 13 years of age
		  : 
		   <input name="agree" type="checkbox" value="0" >
		  <input name="btnCreate" type="button" value="<?=$MSG_LANG["finish"]?>" onClick="validateForm()">
		  <input name="btnCancel" type="button" value="<?=$MSG_LANG["cancel"]?>" onClick="window.open('index.php', '_self')">&nbsp;		    </td>
		</tr>
  </table>

		<input name="ToDo" value="NewUser" type="hidden">
</form>

	<?= $MSG_LANG["mandatory"] ?><BR>

	<hr width=100% align=left>
	<br>
	<!-- Google Conversion Code -->
<script language="JavaScript">
<!--
google_conversion_id = 1071658517;
google_conversion_language = "en_US";
google_conversion_format = "1";
google_conversion_color = "FFFFFF";
if (1.0) {
  google_conversion_value = 1.0;
}
google_conversion_label = "Signup";
-->
</script>
<script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<img height=1 width=1 border=0 src="http://www.googleadservices.com/pagead/conversion/1071658517/?value=1.0&label=Signup&script=0">
</noscript>
	<table width="300" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><strong>Screen Shots: click to see larger images</strong></td>
      </tr>
      <tr>
        <td><a href="../images/Screen%20Shots/thumbs/image3.jpg" target="_blank"><img src="../images/Screen%20Shots/thumbs/thumbnails/timage3.jpg" alt="" width="214" height="150" border="0"></a><a href="../images/Screen%20Shots/thumbs/image2.jpg" target="_blank"><img src="../images/Screen%20Shots/thumbs/thumbnails/timage2.jpg" width="170" height="150" border="0"></a><a href="../images/Screen%20Shots/thumbs/image4.jpg" target="_blank"><img src="../images/Screen%20Shots/thumbs/thumbnails/timage4.jpg" width="236" height="150" border="0"></a><a href="../images/Screen%20Shots/thumbs/image5.jpg" target="_blank"><img src="../images/Screen%20Shots/thumbs/thumbnails/timage5.jpg" width="187" height="150" border="0"></a></td>
      </tr>
    </table>

<script>
	document.login.language.value="<?=$lang?>";
</script>

</body>
</html>
