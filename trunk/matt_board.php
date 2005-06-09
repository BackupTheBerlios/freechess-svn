<?php
##############################################################################################
#                                                                                            #
#                                matt_board.php                                                
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


require_once "EPDutils.php";

$EPD = $_GET['EPD'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<style type="text/css">
	<!--
	body {
	    font-family: verdana, arial, helvetica, sans-serif;
        font-size: small;
        color: #000;
		background-color: #fff;
	}
	-->
	</style>
	<title>WebChess</title>
	<script language="JavaScript" type="text/javascript" src="javascript/chessutils.js"></script>
	<script language="JavaScript" type="text/javascript" src="javascript/EPDutils.js"></script>
</head>

<body>
<div align="center">

<?PHP displayBoard(EPD2Board($EPD));  ?>

</body>
</html>