<?php
##############################################################################################
#                                                                                            #
#                                Rating_Ladder_Buttons.php                                                
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

?>
<table width="100%" border="1">
  <tr>
    <th colspan="3"><font size=+1>Rating Ladders</font></th>
  </tr>
  <tr>
    <td><div align="center">
      <input name="button95" type="button" class="BOTOES" style="cursor: hand"onClick="window.location='ranking_3000.php'" value="3200-2001">
    </div></td>
    <td><div align="center">
      <input name="button97" type="button" class="BOTOES" style="cursor: hand"onClick="window.location='ranking_2000.php'" value="2000-1801">
    </div></td>
    <td><div align="center">
      <input name="button94" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='ranking_1800.php'" value="1800-1601">
    </div></td>
  </tr>
  <tr>
    <td><div align="center">
      <input name="button9" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='ranking_1600.php'" value="1600-1501">
    </div></td>
    <td>    <div align="center">
      <input name="button92" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='ranking_1500.php'" value="1500-1401">
    </div></td>
    <td><div align="center">
      <input name="button93" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='ranking_1400.php'" value="1400-900">
    </div></td>
  </tr>
  <tr>
    <th colspan="3"><font size=+1>Point Ladders</font>(players are awarded 2
    points for a win and 1 point for a draw)</th>
  </tr>
  <tr>
    <td><div align="center">
      <input name="button952" type="button" class="BOTOES" style="cursor: hand"onClick="window.location='ranking_enthusiasm_3000.php'" value="3000-2001">
    </div></td>
    <td><div align="center">
      <input name="button962" type="button" class="BOTOES" style="cursor: hand"onClick="window.location='ranking_enthusiasm_2000.php'" value="2000-1801">
    </div></td>
    <td><div align="center">
      <input name="button942" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='ranking_enthusiasm_1800.php'" value="1800-1601">
    </div></td>
  </tr>
  <tr>
    <td><div align="center">
      <input name="button96" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='ranking_enthusiasm_1600.php'" value="1600-1501">
    </div></td>
    <td><div align="center">
      <input name="button922" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='ranking_enthusiasm_1500.php'" value="1500-1401">
    </div></td>
    <td><div align="center">
      <input name="button932" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='ranking_enthusiasm_1400.php'" value="1400-900">
    </div></td>
  </tr>
</table>
