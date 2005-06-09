<?php
##############################################################################################
#                                                                                            #
#                                regras.php                                                
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

	/* connect to database */
	require_once('chessdb.php');
	require_once( 'connectdb.php');

   	/* check session status */
	require_once('sessioncheck.php');

	if ($_GET[page] == "")
	    $_page = "index.php";
 	else $_page = $_GET[page];

	/* Language selection */
	require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");
	require "languages/".$_SESSION['pref_language']."/rules.inc.php";

?>


