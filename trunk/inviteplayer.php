<?php
##############################################################################################
#                                                                                            #
#                                inviteplayer.php
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
    require_once ( 'chessutils.php');

    require_once ( 'newgame.php');
    require_once('chessdb.php');


    /* Language selection */
    require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");
?>

<html>
<head>
    <title>[SMARTY TITLE]</title>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["theme_set"]?>/styles.css" type="text/css">

</head>

<body bgcolor=white text=black>
<font face=verdana size=2>

<BR>
<font size=4><?=$MSG_LANG["invitesomebodyforanewgame"]?></font>
<BR>

<table style='background: white' width=100% border=0>
  <tr>
    <td>&nbsp;<a href="inviteplayer.php?newuser=1"><?=$MSG_LANG["playwithnewusers"]?></a>&nbsp;

        <a href="inviteplayer.php?ponline=1">Sort by players OnLine
        <?=$MSG_LANG[""]?>
        </a>

&nbsp;&nbsp;&nbsp; <a title="<?=$MSG_LANG['orderbyname']?>" href="inviteplayer.php?order=firstName"> Sort
by
      <?=$MSG_LANG["player"]?>
      </a>&nbsp;&nbsp;&nbsp;&nbsp; <a title="<?=$MSG_LANG['orderbylocalization']?>" href="inviteplayer.php?order=pais&order2=<?=$order2inv?>">Sort
      by
      <?=$MSG_LANG["country"]?>
      </a>&nbsp;<BR>
    </td>
  </tr>
</table>
<BR>
<font face=arial size=2><?=$MSG_LANG['page']?>:
<?
$slevel = "";

if (!empty($_GET['slevel']))
{
   $slevel = $_GET['slevel'];
}
if (!empty($_GET['order2']))
   $order2 = $_GET['order2'];
if (!empty($_GET['pagina']))
{
   $pagina = $_GET['pagina'];
}
else
{
$pagina = 1;
}
if (!empty($_GET['newuser']))
{
   $newuser = $_GET['newuser'];
}
else
{
    $newuser = 0;
}
if ($slevel =="1")
{
    $level = getPlayerLevel($_SESSION['player_id']);
    echo $MSG_LANG["mylevel"].": $level (".getXPmin($level)."-".getXPmax($level).")<BR>";
}
if (!empty($_GET['order']))
{
$order = $_GET['order'];
}
//$computer = $_GET['computer'];
if (!empty($_GET['ponline']))
{
$ponline = $_GET['ponline'];
}
else
{
    $ponline = 0;
}
if (!empty($_GET['letra']))
{
$letra = $_GET['letra'];
}
else
{
    $letra = "A";
}

if (!isset($order))
    $order="rating";

if (!isset($order2))
    $order2 = "DESC";

if ($order2 == "ASC")
    $order2inv = "DESC";
else
    $order2inv = "ASC";
if (!isset($order))
    $order="newuser";

if (!isset($order2))
    $order2 = "DESC";

if ($order2 == "ASC")
    $order2inv = "DESC";
else
    $order2inv = "ASC";

if ($order=="firstName")
    $order="substring(firstName,0,5)";
$tempo2 = time()-1209600;

    //if ($computer == "1")
        //$tmpQuery="SELECT * FROM players WHERE engine='1' AND ativo='1' and rating>0 ORDER BY rating,substring(firstName,0,5)";

   if ($newuser == "1")
        $tmpQuery="SELECT firstName,pais,rating,playerID,lastUpdate FROM players WHERE lastUpdate>='$tempo2' and ativo='1' and playerID <> ".$_SESSION['playerID']." and rating=0 ORDER BY substring(firstName,0,5)";
    else if ($slevel == "1")
        $tmpQuery="SELECT firstName,pais,rating,playerID,lastUpdate FROM players WHERE lastUpdate>='$tempo2' and engine='0' AND firstName like '$letra%' and rating<=".getXPmax($level)." AND rating >= ".getXPmin($level)." AND playerID <>".$_SESSION['playerID']." AND ativo='1' ORDER BY rating";
    else if ($ponline == "1"){
        $tempo = time()-300;
        $tmpQuery = "SELECT firstName,pais,rating,playerID,lastUpdate FROM players WHERE lastUpdate>='$tempo' AND engine='0' and playerID <> ".$_SESSION['playerID']." AND ativo='1' ORDER BY $order";
    }else if ($order == "rating" || $order == "pais")
        $tmpQuery="SELECT firstName,pais,rating,playerID,lastUpdate FROM players WHERE lastUpdate>='$tempo2' and engine='0' AND playerID <> ".$_SESSION['playerID']." AND ativo='1' and pais !='' ORDER BY $order $order2";
     else if (isset($_GET['cod']))
        $tmpQuery="SELECT firstName,pais,rating,playerID,lastUpdate FROM players WHERE lastUpdate>='$tempo2' and playerID=".$_GET['cod'];
     else
        $tmpQuery="SELECT firstName,pais,rating,playerID,lastUpdate FROM players WHERE lastUpdate>='$tempo2' and engine='0' AND firstName like '$letra%' and playerID <> ".$_SESSION['playerID']." AND ativo='1' ORDER BY $order";

    if ($order == "substring(firstName,0,5)" && $ponline != "1")
        for ($l=65; $l<=90; $l++)
           echo "<a href='".$_SERVER['PHP_SELF']."?ponline=$ponline&order=$order&letra=".chr($l)."'>[".chr($l)."]</a>&nbsp;";
    else{

        echo "<br>";
        $perpage = $CFG_PERPAGE_LIST;
        if ($pagina=="")
           $pagina=1;
        $inicio = $perpage * $pagina - $perpage;
        $fim = $perpage * $pagina;

        $rs = mysql_query($tmpQuery);

        $rows = mysql_num_rows($rs);
        $ultima = ceil($rows/$perpage);

        if ($rows <= $fim)$proxima=FALSE;
        else $proxima=TRUE;

        $tmpQuery .= " LIMIT $inicio,$perpage";

        if ($pagina-1 >0)
            echo "<a href='".$_SERVER['PHP_SELF']."?order=$order&order2=$order2&ponline=$ponline&pagina=".($pagina-1)."'>[&laquo;]</a>";
        else
            echo "<font color=#bbbbbb>[&laquo;]</font>";

        $b=1;
        for ($pg=1 ; $pg<=$ultima ; $pg++){
            if ($pg != $pagina)echo " <a href='".$_SERVER['PHP_SELF']."?order=$order&order2=$order2&ponline=$ponline&pagina=$pg'>[$pg]</a>";
            else echo " [$pg] ";
            if (floor($pg/23) == $b){
                   echo "<BR>";
                   $b++;
            }

        }

        if ($proxima)
            echo "<a href='".$_SERVER['PHP_SELF']."?order=$order&order2=$order2&ponline=$ponline&pagina=".($pagina+1)."'>[&raquo;]</a>";
        else
            echo "<font color=#bbbbbb>[&raquo;]</font>";
    }

?>
</font>
<table border="1" width="100%">
  <tr>
    <th><a title="<?=$MSG_LANG['onlineplayers']?>" href="inviteplayer.php?ponline=<? if ($ponline)echo "0";else echo "1";?>">
      <?=$MSG_LANG['status']?>
    </a></th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th><a title="<?=$MSG_LANG['orderbyname']?>" href="inviteplayer.php?order=firstName">
      <?=$MSG_LANG["player"]?>
    </a></th>
    <th><a title="<?=$MSG_LANG['orderbylocalization']?>" href="inviteplayer.php?order=pais&order2=<?=$order2inv?>">
      <?=$MSG_LANG["country"]?>
    </a></th>
    <?
         echo "<th><a title=".$MSG_LANG['orderbyrating']." href=inviteplayer.php?order=rating&order2=$order2inv>".$MSG_LANG["rating"]."</a></th>";
        ?>

    <!--<th><?=$MSG_LANG["victories"]?></th>
        -->
    <th><?=$MSG_LANG["level"]?>
    </th>
  </tr>
  <?

    $tmpPlayers = mysql_query($tmpQuery);

    if (mysql_num_rows($tmpPlayers)==0)
        echo "<tr><td colspan='8'>".$MSG_LANG["noopponent"]."</td></tr>";
        while($tmpPlayer = mysql_fetch_array($tmpPlayers, MYSQL_ASSOC))
        {
            if ($tmpPlayer['lastUpdate'] >= (time()-300))
                $img="online";
            else
                 $img="offline";
                    if (getRating($tmpPlayer['playerID']) == 0)
                    echo "<tr>
                    <td width=5%><img src='images/$img.gif' alt='$img'></td>
                    <td width=5%>
                    <form action='newuserchallenge.php' method='post'><input type='hidden' name='player_id' value=$tmpPlayer[playerID]>
                    <input type='button' style='font-size:11' value='$MSG_LANG[invite]' onClick=\"submit()\"> </form>                </td>
                    <td width=5%>
                    <form action='sendmessage.php' method='post'><input type='hidden' name='player_id' value=$tmpPlayer[playerID]>
                    <input type='button' style='font-size:11' value='$MSG_LANG[sendmessage]' onClick=\"submit()\"> </form>                </td>
                    <td width=50% style='text-align:left'>".$tmpPlayer['firstName']."</td>
                    <td width=15%>".$tmpPlayer['pais']."</td>
                    <td width=10%><strong><font color=red>$MSG_LANG[newuser]</strong></font></td>
                    <td width=10%>".getPlayerLevel($tmpPlayer['playerID'])."</td>";

                    else
                    echo "<tr>
                    <td width=5%><img src='images/$img.gif' alt='$img'></td>
                    <td width=5%>
                    <input type='button' style='font-size:11' value='$MSG_LANG[invite]' onClick=\"javascript:challenge(".$tmpPlayer['playerID'].")\">                 </td>
                    <td width=5%>
                    <input type='button' style='font-size:11' value='$MSG_LANG[sendmessage]' onClick=\"javascript:MessagePlayer(".$tmpPlayer['playerID'].")\">                 </td>
                    <td width=50% style='text-align:left'>".$tmpPlayer['firstName']."</td>
                    <td width=15%>".$tmpPlayer['pais']."</td>
                    <td width=10%>".$tmpPlayer['rating']."</td>
                    <td width=10%>".getPlayerLevel($tmpPlayer['playerID'])."</td>";
                    //<td width=10% style='text-align:right'>".getPV($tmpPlayer['playerID'])."%</td>
                    echo "<td width=5%><input style='font-size:11' type=button value='".$MSG_LANG["details"]."' onClick=\"window.location='stats_user.php?cod=".$tmpPlayer['playerID']."'\")>
                    </td>
                    </tr>\n";


        }

    ?>
</table>
<BR>


<?
    /* if game is marked playerInvited and the invite is from the current player */
    $tmpQuery = "SELECT whitePlayer,blackPlayer,gameMessage,gameID,oficial FROM games WHERE (gameMessage = 'playerInvited' AND ((whitePlayer = ".$_SESSION['playerID']." AND messageFrom = 'white') OR (blackPlayer = ".$_SESSION['playerID']." AND messageFrom = 'black'))";
    /* OR game is marked inviteDeclined and the response is from the opponent */
    $tmpQuery .= ") OR (gameMessage = 'inviteDeclined' AND ((whitePlayer = ".$_SESSION['playerID']." AND messageFrom = 'black') OR (blackPlayer = ".$_SESSION['playerID']." AND messageFrom = 'white')))  ORDER BY dateCreated";
    $tmpGames = mysql_query($tmpQuery);

    if (mysql_num_rows($tmpGames) > 0){
        //echo("<tr><td colspan='5'>".$MSG_LANG["noinvations"].".</td></tr>\n");
    ?>
    <form name="withdrawRequestForm" action="mainmenu.php" method="post">
    <table border="1" width="100%">
    <tr>
        <th colspan="5"><?=$MSG_LANG["currentinvitations"]?></th>
    </tr>

    <tr>
        <th><?=$MSG_LANG["opponent"]?></th>
        <th><?=$MSG_LANG["yourcolor"]?></th>
        <th><?=$MSG_LANG["type"]?></th>
        <th><?=$MSG_LANG["status"]?></th>
        <th><?=$MSG_LANG["action"]?></th>
    </tr>
    <?
        while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
        {
            /* Opponent */
            echo("<tr><td>");
            /* get opponent's nick */
            if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
                $tmpOpponent = mysql_query("SELECT firstName,lastName,playerID FROM players WHERE playerID = ".$tmpGame['blackPlayer']);
            else
                $tmpOpponent = mysql_query("SELECT firstName,lastName,playerID FROM players WHERE playerID = ".$tmpGame['whitePlayer']);

            $row = mysql_fetch_array($tmpOpponent);
            $opponent = $row[0];
            $id = $row[2];
            echo "<a href='stats_user.php?cod=$id'>$opponent</a>";

            /* Your Color */
            echo ("</td><td>");
            if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
                echo ($MSG_LANG["white"]);
            else
                echo ($MSG_LANG["black"]);

            if ($tmpGame['oficial'] == "1")
                $oficial = $MSG_LANG["official"];
            else $oficial = $MSG_LANG["notofficial"];

            echo "<td>".$oficial."</td>";

            /* Status */
            echo ("</td><td>");
            if ($tmpGame['gameMessage'] == 'playerInvited')
                echo $MSG_LANG["pendingreply"];
            else if ($tmpGame['gameMessage'] == 'inviteDeclined'){
                echo $MSG_LANG["invitedeclined"];
                if (substr($tmpGame['reason'],0,1)=="#")
                    echo ":<BR>".$MSG_LANG[$tmpGame['reason']];
            }
            /* Withdraw Request */
            echo ("</td><td align='center'>");
            echo ("<input type='button' value='".$MSG_LANG['cancel']."' onclick=\"withdrawRequest(".$tmpGame['gameID'].")\">");

            echo("</td></tr>\n");
        }
        echo '
        </table>
        <input type="hidden" name="gameID" value="">
        <input type="hidden" name="ToDo" value="WithdrawRequest">
        </form><BR>';
    }
?>

<font face=verdana size=2 color=red><B>
<?=$MSG_LANG['hints']?>:
- <?=$MSG_LANG['hint1']?><BR>
- <?=$MSG_LANG['hint2']?><BR>
- <?=$MSG_LANG['hint3']?>
</B>
</font>



<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>

</body>
</html>
