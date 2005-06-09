<?php
##############################################################################################
#                                                                                            #
#                                groups_functions.php                                                
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

function getcount($table, $where="") {

    $query = "SELECT count(*) AS number FROM ".$table;

    if ($where != "")

    	$query .= " ".$where;

    $c1 = mysql_query($query);    echo mysql_error();
    $c = mysql_fetch_array($c1);   echo mysql_error();

    $number = intval ($c['number']);

    return $number;

}

function is_creator($id) {

if (getcount("groups", "WHERE creator = '$id'") > 0)
{
	return true;
}
else {
	return false;
}

}

function is_groupcreator($gid, $pid) {

if (getcount("groups", "WHERE group_id = '$gid' AND creator = '$pid'") > 0)
{
	return true;
}
else {
	return false;
}

}

function get_group($id, $gid = false) {

$where = "WHERE playerID = '$id'";

$c = getcount("group_members", $where);

if ($c == 0) return false;

$query = "SELECT g.*, m.joined FROM group_members m
		 LEFT JOIN groups g
         ON g.group_id = m.group_id
         WHERE m.playerID = '$id'";

if ($gid != false) $query .= " AND g.group_id != '$gid'";

$g1 = mysql_query($query);
echo mysql_error();

$i = 0;

while ($g[$i] = mysql_fetch_array($g1))
{
	$i++;
}

unset($g[$i]);

return $g;

}

function get_groupdata($id) {

$g1 = mysql_query("SELECT g.*, p.firstName FROM groups g
				   LEFT JOIN players p
                   ON p.playerID = g.creator
                   WHERE group_id = '$id'");
echo mysql_error();

$g = mysql_fetch_array($g1);

return $g;

}

function generate_dropdown($id,$gid,$pid) {

$c = getcount("groups", "WHERE creator = '$id' AND group_id != '$gid'");

if ($c == 0) return false;

$e = '<select name="move_'.$pid.'"><option>Verschieben nach...</option>';

$h = get_group($id, $gid);

while (list($key,$val) = each($h))
{
	$e .= '<option value="'.$val['group_id'].'">'.$val['title'].'</option>';
}

$e .= '</select>';

return $e;

}

?>