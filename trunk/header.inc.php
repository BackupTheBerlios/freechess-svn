<?php
##############################################################################################
#                                                                                            #
#                                header.inc.php                                                
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
<font face=verdana size=2>
</font>
<table width="100%" border="1">
  <tr>
    <td width="15%" height="40"><div align="center"><font face=verdana size=2>
        <input name="button22" type="button" style="cursor: hand" onClick="window.open('mailform.php','_blank','toolbar=no,status=no,menubar=no,scrollbars=yes,width=500,height=500', '_blank')" value="Contact Admin">
    </font></div></td>
    <td width="15%"><div align="center"><font face=verdana size=2>
        <input name="button522" border-width= "5"  type="button" style="cursor: hand" onClick="window.location='MyGames.php'" value="<?=$MSG_LANG["mygames"]?>">
    </font></div></td>
    <td width="15%"><div align="center"><font face=verdana size=2>
        <input name="button52" style="cursor: hand"  type="button"  onClick="window.location='chess.php'" value="Chessboard">
    </font></div></td>
    <td width="55%"><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
      <div align="center">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Help keep the site free make a donation today!">
    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHHgYJKoZIhvcNAQcEoIIHDzCCBwsCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYBtg9NEonZ7aXq4r4NudYrXNf7CwrXFvoOfuubpK4NhWpE9AzUrbEumLIUHQRKypug+NRy8kWhLDG87EBMYfiuzUTcLl8Cwt8k9rlYgWghjbJTOjXbuPTy06Bp4RbCZmkKGZp/Xi2byTlwzrtW1tJkAi3GwSX17hGaMG4HPILJYnTELMAkGBSsOAwIaBQAwgZsGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIE1Sq3//HA++AeEOCLT58mxm6bQxPEjZF7n4hgOVe7T9siohhulGLdOVgoy2YyaN/5kN0x4MuW1oQ9k45n1VWjyu/AmvKd1kZTZ5HXlGfmKlAugqxEafnqx2RKRixYNdFK9M5B7zGqhqiaTCj6SBBojriXQov/gtI6eNQeWkd43lqLKCCA4cwggODMIIC7KADAgECAgEAMA0GCSqGSIb3DQEBBQUAMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTAeFw0wNDAyMTMxMDEzMTVaFw0zNTAyMTMxMDEzMTVaMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAwUdO3fxEzEtcnI7ZKZL412XvZPugoni7i7D7prCe0AtaHTc97CYgm7NsAtJyxNLixmhLV8pyIEaiHXWAh8fPKW+R017+EmXrr9EaquPmsVvTywAAE1PMNOKqo2kl4Gxiz9zZqIajOm1fZGWcGS0f5JQ2kBqNbvbg2/Za+GJ/qwUCAwEAAaOB7jCB6zAdBgNVHQ4EFgQUlp98u8ZvF71ZP1LXChvsENZklGswgbsGA1UdIwSBszCBsIAUlp98u8ZvF71ZP1LXChvsENZklGuhgZSkgZEwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tggEAMAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQEFBQADgYEAgV86VpqAWuXvX6Oro4qJ1tYVIT5DgWpE692Ag422H7yRIr/9j/iKG4Thia/Oflx4TdL+IFJBAyPK9v6zZNZtBgPBynXb048hsP16l2vi0k5Q2JKiPDsEfBhGI+HnxLXEaUWAcVfCsQFvd2A1sxRr67ip5y2wwBelUecP3AjJ+YcxggGaMIIBlgIBATCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwCQYFKw4DAhoFAKBdMBgGCSqGSIb3DQEJAzELBgkqhkiG9w0BBwEwHAYJKoZIhvcNAQkFMQ8XDTA0MTIzMDE4MzcyNFowIwYJKoZIhvcNAQkEMRYEFFF03Xme70CthLM2HF4P8sQ7ZoCzMA0GCSqGSIb3DQEBAQUABIGAjpsBqZO+RjCqgPibWIK8qNSgonLNTiLPWJ9nJCphRSOY8C19gVTSF4ZVnB8rOJ8texbSxa6eY8IaszbWMbX/tZ/VkcfZaC4WrZeeXST8h+Z/h8AU/1Brxt64jNA3/KS7r8aJ8EQvEt8nNqny6EL4P6vwUQZIFYb5BVvR3SfPmGI=-----END PKCS7-----
">
       <img src="images/hand2.gif" width="266" height="27"> </div>
    </form>
    </td>
  </tr>
</table>
<font face=verdana size=2>
<?
        flush();
        $p = mysql_query("SELECT count(*) FROM games WHERE gameMessage=''");
        //$row = mysql_fetch_array($p);
        //$games = $row[0];
        //$p = mysql_query("SELECT count(*) FROM games WHERE gameMessage<>'' and gameMessage<>'playerInvited' and gameMessage<>'inviteDeclined'");
        //$row = mysql_fetch_array($p);


        //$terminados = $row[0];

		//Disabled by performance questions
		//$p = mysql_query("select count(*),gameID from history group by gameID order by 1 desc limit 1");
        //$row = mysql_fetch_array($p);
        //$longa = ceil($row[0]/2);
        //$longa_id = $row[1];

        //$p = mysql_query("SELECT  distinct playerID FROM games, players WHERE (whitePlayer = playerID OR blackPlayer = playerID) AND gameMessage=''");
        //$jogadores = mysql_num_rows($p);
        
        $p = mysql_query("SELECT firstName,playerID FROM players WHERE ativo='1' ORDER BY playerId DESC");
        $row = mysql_fetch_array($p);
        $users = mysql_num_rows($p);
        $lastuser = $row["firstName"];
        $lastuserid = $row["playerID"];

        $p = mysql_query("SELECT firstName FROM players WHERE lastUpdate > '".(time()-1200)."'");
        $row = mysql_fetch_array($p);
        $online = mysql_num_rows($p);

		//$p = mysql_query("select firstName,count(*) from games,players WHERE playerID=whitePlayer AND engine='0' AND gameMessage='' group by whitePlayer Order by 2 DESC");
        //while ($row = mysql_fetch_array($p))
			//$jogos[$row[0]] = $row[1];

		//$p = mysql_query("select firstName,count(*) from games,players WHERE playerID=blackPlayer AND engine='0' AND gameMessage='' group by blackPlayer Order by 2 DESC");
        //while ($row = mysql_fetch_array($p))
			//$jogos[$row[0]] += $row[1];

		//if (count($jogos)>0){
			//arsort($jogos);
			//list($a,$b) = each($jogos);
			//$maisativo = "$a ($b ".$MSG_LANG['games'].")";
		//}
		//else $maisativo = "";
?>
      <table border="1" width="100%">


        <!--
	<tr><th><B><?=$MSG_LANG["longergame"] ?></B></th><td><?=$longa?> <?=$MSG_LANG['turns']?></td></tr>
	-->
        <tr>
          <td width="40%" rowspan="2"><div align="center">
              <?= ($_SESSION['playerName']) ?>
        ,
        <?

	    $ranking = getRanking($_SESSION['playerID']);
        $rating = getRating($_SESSION['playerID']);

		if ($CFG_ENABLE_TRIAL_RATING && $rating == 0){
        	$p = mysql_query("select count(*) from games where (whitePlayer='$_SESSION[playerID]' OR blackPlayer='$_SESSION[playerID]') AND (gameMessage='playerResigned' OR gameMessage='checkMate' OR gameMessage='draw')");
			$r = mysql_fetch_array($p);
			$n = 5-$r[0];
			echo "<table style='width: 100%' border='1' style='background:red'><tr><td  style='background:red'>";
		    echo "<font color=white><B>".$MSG_LANG['trialprocess']."</B><BR>";
		    echo str_replace("%n",$n,$MSG_LANG['trialmessage']);
		    echo "</font>";
		    echo "</td></tr></table>";
		}
		else
	        echo $MSG_LANG["yourrating"].": <B>$rating</b> ".$MSG_LANG["yourranking"].": <B>$ranking</B><BR>";

        echo "</P>";
?>
            </div>
          </td>
          <td width="29%" rowspan="2"><div align="center">
            <div align="center"><a href="inviteplayer.php?ponline=1"> </a><b> </b><a href="stats_user.php?cod=<?=$lastuserid?>"> </a><b>
                <?
   $m = ("SELECT ack,toID FROM communication WHERE (toID = ".$_SESSION['playerID']." OR toID = NULL) AND ack = 0 and listed <> 1");

$numresults=mysql_query($m);
$numrows=mysql_num_rows($numresults);
if ($numrows != 0 && $_SESSION['pref_sound'] == 'on')
echo ("<input type=\"image\" src=\"images/icons/emailenvelope.jpg\" onClick=\"window.location='messages.php'\"><br>".$MSG_LANG["sendpmtext3-sound"]."");
elseif ($numrows != 0)
echo ("<input type=\"image\" src=\"images/icons/emailenvelope.jpg\" onClick=\"window.location='messages.php'\"><br>".$MSG_LANG["sendpmtext3"]."");
else
echo "No New Messages";
?>
            </div></td>
          <td width="31%"><?=$MSG_LANG["onlineplayers"]?>
            :&nbsp;<a href="inviteplayer.php?ponline=1"><font size=+1><?=$online?></font></a></td>
        </tr>
        <tr>
          <td><?=$MSG_LANG["lastuserregistered"]?>
      :&nbsp;<a href="stats_user.php?cod=<?=$lastuserid?>"><?=$lastuser?>
          </a></td>
        </tr>
      </table>
</font><font face=verdana size=2></font>
<table width="100%" border="1">
  <tr>
    <td width="20%"><div align="center">
      <input name="button" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='recommend.php'" value="Recommend Us">
    </div></td>
    <td width="20%"><div align="center">
      <input name="button2" type="button" style="cursor: hand" class="BOTOES" onclick="location.href='forum.php';" value="Forums">
    <font face=verdana size=2><font face=verdana size=2 color=black><img src="images/newsm.gif" width="25" height="23"></font></font> </div></td>
    <td width="20%"><div align="center">
      <input name="button4" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='annotatedgames.php'" value="Annotations">
    </div></td>
    <td width="20%"><div align="center">
      <input name="button14" type="button" class="BOTOES" style="cursor: hand" onClick="window.location='stats_user.php?cod=<?=$_SESSION['playerID']?>'" value="<?=$MSG_LANG["statistics"]?>">
      <img src="images/newsm.gif" width="25" height="23"></div></td>
    <td width="20%"><div align="center">
      <input name="button8"class="BOTOES" type="button" style="cursor: hand"  onClick="window.location='mainmenu.php'" value="<?=$MSG_LANG["main"]?>">
    </div></td>
  </tr>
  <tr>
    <td width="20%"><div align="center">
      <input name="button3" type="button" class="BOTOES" style="cursor: hand" onClick="window.location='ranking.php'" value="<?=$MSG_LANG["ranking"]?>">
    </div></td>
    <td width="20%"><div align="center">
      <input name="button6" type="button" class="BOTOES" style="cursor: hand" onClick="window.location='inviteplayer.php'" value="<?=$MSG_LANG["challenges"]?>">
    </div></td>
    <td width="20%"><div align="center">
      <input name="button12" type="button" class="BOTOES" style="cursor: hand" onClick="window.location='allgames.php'" value="<?=$MSG_LANG["allgames"]?>">
    </div></td>
    <td width="20%"><div align="center">
      <input name="button13" type="button" class="BOTOES" style="cursor: hand" onClick="window.location='configure.php'" value="<?=$MSG_LANG["configurations"]?>">
    </div></td>
    <td width="20%"><div align="center">
      <input name="button7" type="button" style="cursor: hand" class="BOTOES" onClick='document.logout.submit()' value="<?=$MSG_LANG["logoff"]?>">
    </div></td>
  </tr>
  <tr>
    <td><div align="center">
      <input name="button32" type="button" style="cursor: hand" class="BOTOES" onClick="window.location='groups.php'" value="<?=$MSG_LANG["teams"]?>">
    </div></td>
    <td><div align="center">
      <input name="button15" type="button" class="BOTOES" onClick="window.location='tournaments.php?action=list&view=join'" value="<?=$MSG_LANG["tournamentheader"]?>">
      <img src="images/newsm.gif" width="25" height="23">    </div></td>
    <td><div align="center">
      <input name="button5" class="BOTOES"style="cursor: hand"  type="button"  onClick="window.location='RecentGames.php'" value="Recent Games">
    </div></td>
    <td><div align="center">
      <input name="button10" type="button" class="BOTOES" style="cursor: hand" onClick="window.location='messages.php'" value="<?=$MSG_LANG[message]?>">
    </div></td>
    <td><div align="center">
      <input name="button11" type="button" class="BOTOES" style="cursor: hand" onClick="window.location='regras.php'" value="<?=$MSG_LANG["help"]?>">
    </div></td>
  </tr>
</table>
<font face=verdana size=2><font face=verdana size=2 color=black></font></font> 