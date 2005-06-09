<?php
##############################################################################################
#                                                                                            #
#                                credits.php                                                
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

	require("config.php");
	if (!isset($_GET["back"]))
		$voltar = "index";
	else
		$voltar = $_GET["back"];

	if ($_SESSION["pref_colortheme"] == "")
		$theme = $CFG_DEFAULT_COLOR_THEME;
	else
		$theme = $_SESSION["pref_colortheme"];
?>
<html>
<head>
	<title>Collaborators</title>
	<LINK rel="stylesheet" href="themes/<?=$theme?>/styles.css" type="text/css">
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>
<p align=center>
	<img src="images/compwebchess.gif" width="200" height="80">
</p>

<hr width=500>

<p align=center>
CompWebChess was developed by Felipe Rayel and is "based" on <A HREF="http://webchess.sourceforge.net/" target="_blank">WebChess 0.8.3</A> by Jonathan Evraire.
<BR><BR>
CompWebChess comes with ABSOLUTELY NO WARRANTY and is published under the GNU GPL.<BR>
To read the full GPL license, go to the url http://www.opensource.org/licenses/gpl-license.html.<BR>
<BR>
Basically, the GPL says you are free to distribute and edit the sourcecode, as long as you keep<BR>
the rightful credits intact. Please read the full documents if you have any questions.  
</p>
<hr width=500>

<p align="center">
	<B>Project Maintainer</B><BR>
	<a href="http://www.inf.pucpcaldas.br/~rayel" target="_blank">Felipe Rayel</a>
</p>

<p align="center">
	<B>Developers</B><BR>
	PHP: <a href="http://www.inf.pucpcaldas.br/~rayel" target="_blank">Felipe Rayel</a>
	<BR>

	Javascript: <a href="http://www.inf.pucpcaldas.br/~rayel" target="_blank">Felipe Rayel</a>,
	<a href="http://www.comp.pucpcaldas.br/users/alcantara" target="_blank">André Luís Michels Alcântara</a>
	<BR>

	C/C++: <a href="http://www.comp.pucpcaldas.br/users/alcantara" target="_blank">André Luís Michels Alcântara</a>
</p>

<p align="center">
	<B>Chess Bots</B><BR>
	Original Source: Fimbulwinter 5.0 by
    <a target="_blank" href="http://www.stanford.edu/~jjshed/chess">John 
    Shedletsky</a><br>
    Source Modification:
	<a href="http://www.comp.pucpcaldas.br/users/alcantara" target="_blank">André Luís Michels Alcântara</a></p>

<p align="center">
	<B>Logo</B><BR>
	<a href="http://www.comp.pucpcaldas.br/users/victor.hugo" target="_blank">Victor Hugo Manata Pontes</a>
</p>

<p align="center">
	<b>Public Chatroom, Avatar, Tournaments</b><br>
    <a href="http://stabi.hs-bremerhaven.de/mondwurf" target="_blank">Dr. Georg Mondwurf</a><br>
    <a target="_blank" href="http://www.fivedigital.net">Thomas Müller</a><br>
    <br>
	<B>Translations</b><BR>
    Dutch: <a href="" target="_blank">Noel Toone</a><BR>
	English: <a href="http://www.comp.pucpcaldas.br/users/victor.hugo" target="_blank">Victor Hugo Manata Pontes</a>, <a href="http://www.inf.pucpcaldas.br/~rayel" target="_blank">Felipe Rayel</a>, <a target="_blank" href="http://www.chess-knights.com">Nelson Newman</a><BR>
	French: <a href="http://www.inf.pucpcaldas.br/~udo/" target="_blank">Dr. Udo Fritzke Jr.</a><BR>
    German: <a href="http://stabi.hs-bremerhaven.de/mondwurf" target="_blank">Dr. Georg Mondwurf</a><BR>
	Italian: <a href="http://www.verdeidea.org">Vittorio Nicoletti Altimari</a><BR>
    Polish: <a href="" target="_blank">Jacob Dybala</a><BR>
	Portuguese: <a href="http://www.inf.pucpcaldas.br/~rayel" target="_blank">Felipe Rayel</a><br>
    Spanish: <a href="http://www.inf.pucpcaldas.br/~sonia" target="_blank">Dra. Sonia Maria Barros Barbosa Correa</a><br>
    Russian: <a href="" target="_blank">Yuri Volodin</a>, <a href="" target="_blank">Michael Mushaljuk</a>

</p>
<p align=center>
	<a href="<?=$voltar?>.php">| RETURN |</a>
</p>
</font></p>
<hr width=100%>
    <table cellSpacing="1" cellPadding="1" width="500" align="center" border="0">
      <tr><Td>
		<?include("footer.inc.php")?>
      </td></tr>
    </table>

</body>
</html>
