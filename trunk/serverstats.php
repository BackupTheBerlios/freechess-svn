<?php
##############################################################################################
#                                                                                            #
#                                serverstats.php                                                
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

	/* load external functions for setting up new game */
	require_once( 'chessutils.php');
	//require_once ( 'chessconstants.php');
	//require_once ( 'newgame.php');
	//require_once('chessdb.php');
	
	/* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
	//fixOldPHPVersions();

	

    //if ($_COOKIE["cookie_language"] != $_SESSION['pref_language'])
    //{}
	
	/* connect to database */
	require_once('connectdb.php');
	
?>
<?
        flush();
        $p = mysql_query("SELECT count(*) FROM games WHERE gameMessage=''");
        $row = mysql_fetch_array($p);
        $games = $row[0];
        $p = mysql_query("SELECT count(*) FROM games WHERE gameMessage<>'' and gameMessage<>'playerInvited' and gameMessage<>'inviteDeclined'");
        $row = mysql_fetch_array($p);
        $terminados = $row[0];

        //Disabled by performance questions
        //$p = mysql_query("select count(*),gameID from history group by gameID order by 1 desc limit 1");
        //$row = mysql_fetch_array($p);
        //$longa = ceil($row[0]/2);
        //$longa_id = $row[1];

        //$p = mysql_query("SELECT  distinct playerID FROM games, players WHERE (whitePlayer = playerID OR blackPlayer = playerID) AND gameMessage=''");
        //$jogadores = mysql_num_rows($p);

        $p = mysql_query("SELECT * FROM players ORDER BY playerId DESC");
        $row = mysql_fetch_array($p);
        $users = mysql_num_rows($p);
        $lastuser = $row["firstName"];
        $lastuserid = $row["playerID"];

        $p = mysql_query("SELECT * FROM players WHERE lastUpdate > '".(time()-1200)."'");
        $row = mysql_fetch_array($p);
        $online = mysql_num_rows($p);

		$p = mysql_query("select firstName,count(*) from games,players WHERE playerID=whitePlayer AND gameMessage='' group by whitePlayer Order by 2 DESC");
        while ($row = mysql_fetch_array($p))
			$jogos[$row[0]] = $row[1];

		$p = mysql_query("select firstName,count(*) from games,players WHERE playerID=blackPlayer AND gameMessage='' group by blackPlayer Order by 2 DESC");
        while ($row = mysql_fetch_array($p))
			$jogos[$row[0]] += $row[1];

		if (count($jogos)>0){
			arsort($jogos);
			list($a,$b) = each($jogos);
			$maisativo = "$a ($b ".$MSG_LANG['games'].")";
		}
		else $maisativo = "";
?>
<?=$MSG_LANG["onlineplayers"] ?></B><font color="#FF0000"><font color="#FFFFFF">(</font><?=$online?><font color="#FFFFFF">)</font>  </font> <B>
  </B><font color="#FF0000">  </font> <B>
  <?=$MSG_LANG["activegames"] ?>
  </B><font color="#FF0000"><font color="#FFFFFF">(</font><?=$games?><font color="#FFFFFF">)</font>  </font> <b>
  <?=$MSG_LANG["finishedgames"] ?>
  </b><font color="#FF0000"><font color="#FFFFFF">(</font><?=$terminados?><font color="#FFFFFF">)</font>  </font> <b>
  <?=$MSG_LANG["users"] ?>
  </b><font color="#FF0000"><font color="#FFFFFF">(</font><?=$users?><font color="#FFFFFF">)</font>  <b></b></font>
<? //mysql_close(); ?>