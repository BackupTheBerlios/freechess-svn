<?php
##############################################################################################
#                                                                                            #
#                               /admin/header.inc.php                                                
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
?>

<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%">
  <tr>
    <td width="20%" valign="bottom">
    <img hpacing=1 src=../images/compwebchess.gif align=left width="200" height="80"></td>
        <td width="80%">
        <p style="text-align: left">
        <font face=verdana size=2>
        <?=$MSG_LANG["welcome"]?> <?=$VERSION?><BR>
        <BR>

        <input type="button" class="BOTOES" value="<?=$MSG_LANG["main"]?>" onClick="window.location='index.php'">
        <input type="button" class="BOTOES" value="<?=$MSG_LANG["users"]?>" onClick="window.location='allusers.php'">
        <input type="button" class="BOTOES" value="<?=$MSG_LANG["games"]?>" onClick="window.location='allgames.php'">
        <input type="button" class="BOTOES" value="<?=$MSG_LANG["currentinvitations"]?>" onClick="window.location='allinvitations.php'">
        <BR>
		<input type="button" class="BOTOES" value="<?=$MSG_LANG["manage"]?>" onClick="window.location='manager.php'">
        <input type="button" class="BOTOES" value="<?=$MSG_LANG["configurations"]?>" onClick="window.location='mainconfigure.php'">
        <input type="button" class="BOTOES" value="<?=$MSG_LANG["statistics"]?>" onClick="window.location='allstats.php'">
        <input type="button" class="BOTOES" value="<?=$MSG_LANG["logoff"]?>" onClick='document.logout.submit()'">

    </td>
  </tr>
</table>

