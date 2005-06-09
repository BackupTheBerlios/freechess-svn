<?php
##############################################################################################
#                                                                                            #
#                                chatrules.php                                                
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
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Public Chat Rules/Guidelines: <strong><font color="#FF0000">(Chat is not intended for use by individuals
under the age of 13.)</font></strong></font></p>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> Users are to
    be responsible and to respect our chess community. Your conduct should be
    guided by
common sense, basic &quot;netiquette&quot;, and these chat guidelines.</font></p>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">We discourage any of the following activity that:</font></p>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> * Contains vulgarities directed toward another individual or group.<br>
* Depicts violence in a gratuitous manner, without journalistic or artistic merit,
  primarily intended to agitate or cause emotional distress.<br>
* Is intended to victimize, harass, degrade or intimidate an individual or group
of individuals on the basis of age, disability, ethnicity, gender, race, religion
or sexual orientation. Hate speech is unacceptable anywhere on the service.<br>
* Solicits personal information from a minor (under 18 years old). Personal information
includes full name, home address, home telephone number, or other identifying
information that would enable &quot;offline&quot; contact.<br>
* Contains or facilitates the transfer of software viruses or any other computer
code, files or programs designed to interrupt, destroy or limit the functionality
of any computer software or hardware or telecommunications equipment.<br>
* Contains material that defames, abuses, threatens, promotes, or instigates
physical harm or death to others, or oneself.<br>
* Is illegal or incites illegal activity, such as instructional information on
how to build a bomb and/or make counterfeit money.<br>
* Solicits for exchange, sale or purchase of sexually explicit images, and/or
material harmful to minors; including, but not limited to, any photograph, film,
video, or picture or computer generated image or picture (actual or simulated).<br>
* Infringes anyone else's intellectual property rights, including, but not limited
to, any copyright, trademark, rights of publicity, rights of privacy, or other
proprietary rights.<br>
* Attempts to harvest or collect member information, including screen names.<br>
* Impersonates or represents any person or entity in an attempt to deceive, harass
or otherwise mislead another member. You may not pretend to be an employee or
representative of this website, or any of the other affiliated web sites.<br>
* Attempts to get a password, or other private information from a user. Remember:
Administrators of this site will NEVER ask for your password.<br>
* Links to and/or references content not allowed under these guidelines.<br>
</font></p>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Note: It is important to remember safety while online. Always use caution
  when providing any personal information about yourself anywhere online. It's
  also
    a good rule-of-thumb to check the Privacy Policies of any unfamiliar or new
    web sites you visit. When communicating in a chat room be mindful that many
    people will be able to view it and the inclusion of information such as your
    name, your address or telephone number is never recommended.<br>
</font><font size="2"></font></p>
</body>
</html>
