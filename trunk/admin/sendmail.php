<?php
##############################################################################################
#                                                                                            #
#                               /admin/sendmail.php                                                
# *                            -------------------                                           #
# *   begin                : Wednesday, January 25, 2005                                     
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://www.compwebchess.com/forums                              #
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
	if (!isset($_CONFIG))
		require '../config.php';

	/* load external functions for setting up new game */
	require '../chessutils.php';

	/* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
	fixOldPHPVersions();

	/* if this page is accessed directly (ie: without going through login), */
	/* player is logged off by default */
	if (!isset($_SESSION['playerID']))
		$_SESSION['playerID'] = -1;
	
	/* connect to database */
	require '../connectdb.php';


	/* check session status */
	require '../sessioncheck.php';

    $p = mysql_query("SELECT * FROM players where email<>'' AND substring(nick,1,4)<>'al55'");

$x=0;

if ($myname=="")
	$myname = "Carteiro";

if ($myemail == "")
	$myemail = "carteiro@comp.pucpcaldas.br";

while($row  =  mysql_fetch_array($p)){
       $nome = $row[firstName];
       $email = $row[email];
       $contactname = $nome;
  	   $contactemail = $email;

$headers = "";
//$headers = "MIME-Version: 1.0\r\n";
//$headers .= "Content-type: $tipo; charset=iso-8859-1\r\n";
//$headers .= "Content-Transfer-Encoding: quoted-printable\r\n";
$headers .= "From: ".$myname." <".$myemail.">\r\n";
$headers .= "To: ".$contactname." <".$contactemail.">\n";
//$headers .= "Reply-To: ".$myname." <$myemail>\r\n";
//$headers .= "X-Priority: 3\r\n";
//$headers .= "X-MSMail-Priority: Normal\r\n";
//$headers .= "X-Mailer: Carteiro COMP\r\n";
//$headers .= "X-Mailer: Microsoft Outlook Express 6.00.2600.0000";
//$headers .= "X-MimeOLE: Produced By Microsoft MimeOLE V6.00.2600.0000";

//$message = str_replace("\n","\r\n",$message);

mail($contactemail, $subject, $message, $headers);

$x++;
echo "$x email para $nome enviado...<BR>\n";
flush();
}


?>
