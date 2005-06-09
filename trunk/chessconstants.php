<?php
##############################################################################################
#                                                                                            #
#                                chessconstants.php                                                
# *                            -------------------                                           #
# *   begin                : Wednesday, January 25, 2005                                     
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://developer.berlios.de/projects/chess/                              #
# *   VERSION:             : $Id: chessconstants.php,v 1.2 2005/02/04 05:16:47 trukfixer Exp $                                           
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

	/* define constants */
	//10 series = functional game errors
	//20 series = mailing errors
	//30 series = player errors
	//40 series = player errors
	//50 series = player logs
	//60 series = game errors
	//70 series = activity messages from game
	//80 series and up = miscellaneous errors
	define ("EMPTY", 0);	/* 0000 0000 */
	define ("PAWN", 1);	/* 0000 0001 */
	define ("KNIGHT", 2);	/* 0000 0010 */
	define ("BISHOP", 4);	/* 0000 0100 */
	define ("ROOK", 8);	/* 0000 1000 */
	define ("QUEEN", 16);	/* 0001 0000 */
	define ("KING", 32);	/* 0010 0000 */
	define ("BLACK", 128);	/* 1000 0000 */
	define ("WHITE", 0);
	define ("COLOR_MASK", 127);	/* 0111 1111 */
	define ("LOG_DB_ERROR",10);//defines the log type identifier for admin logs as a DB error
	define ("GAME_PRUNE",11);//defines game prune log message
	define ("MAIL_FAIL",21);
	define ("MAIL_SENT",22);
	define ("LOGIN_ERROR",30);
	
	
?>
