<?php
##############################################################################################
#                                                                                            #
#                                completed.php                                                
# *                            -------------------                                           #
# *   begin                : Wednesday, February 24, 2005                                     
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

include_once('global_includes.php');//should have every file in it that is included in the game overall. make it happen
//db connection should be in there.

$language = $_SESSION['pref_language'];
include_once("./languages/{$language}/new_player.inc.php");

$smarty->assign('line1',$MSG_LANG_NEW['thank_you1']);
$smarty->assign('line2',$MSG_LANG_NEW['thank_you2']);
$smarty->assign('line3',$MSG_LANG_NEW['thank_you3']);
$smarty->assign('line4',$MSG_LANG_NEW['thank_you4']);
$smarty->display('default/signup_finished.tpl');
exit;
?>
