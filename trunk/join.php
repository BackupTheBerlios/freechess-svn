<?php
##############################################################################################
#                                                                                            #
#                                join.php
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

    /* load external functions for setting up new game */
    require_once ( 'chessutils.php');
    require_once ( 'chessconstants.php');
    require_once ( 'newgame.php');
    require_once('chessdb.php');


    /* allow WebChess to be run on PHP systems < 4.1.0, using old http vars */
    fixOldPHPVersions();

    /* if this page is accessed directly (ie: without going through login), */
    /* player is logged off by default */
    if (!isset($_SESSION['player_id']))
        $_SESSION['playerID'] = -1;

    /* connect to database */
    require_once( 'connectdb.php');
    $id=$_SESSION['playerID'];
    $thankyou_message = "<p>Thankyou. Your challenge has been sent. </p>";
    /* check session status */
    require_once('sessioncheck.php');

    /* Language selection */
    require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");
?>

<HTML>
<HEAD>
<TITLE></TITLE>
<?
$ssid=session_id();
$ssname=session_name();
?>




<SCRIPT language="JavaScript">
function JoinGame(gameID)
                {
                        document.frmJoinGame.JoinGameID.value = gameID;
                        document.frmJoinGame.submit();
                }
function CancelGame(gameID)
                {
                        document.frmCancelGame.CancelGameID.value = gameID;
                        document.frmCancelGame.submit();
                }
function sendResponse(responseType, messageFrom, gameID)
                {
                        document.responseToInvite.response.value = responseType;
                        document.responseToInvite.messageFrom.value = messageFrom;
                        document.responseToInvite.gameID.value = gameID;
                        document.responseToInvite.submit();
                }

</SCRIPT>
<?

if ($side=="center")
{
echo "Hi";
}
}

themeheader();

$title="<BR><H3><CENTER>Welcome To n8chessnet!</CENTER></H3>";
$content="";
themearticle ($title,$content,"");


$nogames=true;
$title="Players Wanting To Play You";
$content=<<<EOD
<form name="responseToInvite" action="mainmenu.php" method="post">
        <table border="0" width="450">
        <tr>
                <td><u>Board #</u></td>
                <td><u>Opponent</u></td>
                <td><u>Your Color</u></td>
                <td><u>Response</u></td>
        </tr>
EOD;
        $tmpQuery = "SELECT * FROM games WHERE gameMessage = 'playerInvited' AND ((whitePlayer = ".$_SESSION['playerID']." AND messageFrom = 'black') OR (blackPlayer = ".$_SESSION['playerID']." AND messageFrom = 'white')) ORDER BY dateCreated";
        $tmpGames = mysql_query($tmpQuery);

        if (mysql_num_rows($tmpGames) == 0)
        {
                $content.=("<tr><td colspan='3'>You are not currently invited to any games</td></tr>\n");
        $nogames=true;
        }
        else
                while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
                {
            $nogames=false;
                        /* Opponent */
                        $content.=("<tr><td>");
            $content.=$tmpGame['gameID'];
                        $content.=("</td><td>");
                        /* get opponent's nick */
                        if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
                                $tmpOpponent = mysql_query("SELECT nick FROM players WHERE playerID = ".$tmpGame['blackPlayer']);
                        else
                                $tmpOpponent = mysql_query("SELECT nick FROM players WHERE playerID = ".$tmpGame['whitePlayer']);
                        $opponent = mysql_result($tmpOpponent,0);
            $content.=($opponent);

                        /* Your Color */
                        $content.=("</td><td>");
                        if ($tmpGame['whitePlayer'] == $_SESSION['playerID'])
                        {
                                $content.=("White");
                                $tmpFrom = "white";
                        }
                        else
                        {
                                $content.=("Black");
                                $tmpFrom = "black";
                        }

                        /* Response */
                        $content.=("</td><td>");
                        $content.=("<input type='button' value='Accept' onclick=\"sendResponse('accepted', '".$tmpFrom."', "',"',"',".$tmpGame['gameID'].")\">");
                        $content.=("<input type='button' value='Decline' onclick=\"sendResponse('declined', '".$tmpFrom."', "',"',"',".$tmpGame['gameID'].")\">");

                        $content.=("</td></tr>\n");
                }
$content.=<<<EOD
        </table>
        <input type="hidden" name="response" value="">
        <input type="hidden" name="messageFrom" value="">
        <input type="hidden" name="gameID" value="">
        <input type="hidden" name="ToDo" value="ResponseToInvite">
        </form>
EOD;

if($nogames) $content="Nobody Wants To Play You... Dork";
themeindex($title,$content,"");


$wrdBoard=_BOARD;
$wrdYourColor=_YOURCOLOR;
$wrdOpponent=_OPPONENT;
$wrdStatus=_STATUS;
$wrdMoveNum=_MOVENUMBER;
$wrdMaterial=_MATERIAL;
$wrdGameStarted=_GAMESTARTED;
$wrdLastMove=_LASTMOVE;
$wrdGotoGame=_GOTOGAME;
$wrdAnalyze=_ANALYZE;
$wrdYourMove=_YOURMOVE;
$wrdOppMove=_OPPMOVE;
$wrdWhite=_WHITE;
$wrdBlack=_BLACK;



$mygames=getYourPendingGames($id);

if ($mygames) {
$title="You are currently requesting opponants for the following games:";

$content=<<<EOD
<TABLE border=0 width="100%">
<TR align=center><TD></TD><TD><u>$wrdBoard #</u></TD><TD><u>Your Color</u></TD><TD><u>Opponent Name</u></TD><TD><u>Opponent Nick</u></TD><td align=left><u>Opponent Stats</u></td><TD align=left><u>Your Stats Against</u></TD><TD><u>$wrdGameStarted</u></TD></TR>
EOD;

foreach($mygames as $game)
{
if ($game['myColor']=="white")
{$ws="<B>[".$game['wScore']."]</B>";
$bs=$game['bScore'];}
else
{$bs="<B>[".$game['bScore']."]</B>";
$ws=$game['wScore'];}



if (!$game['oNick'])
{

$game['oNick']="Open";
$game['oFirst']="Open";
$game['oLast']="";

if ($game['wNick'])
    {
#       $game['oNick']=$game['wNick'];
#       $game['oFirst']=$game['wFirst'];
#       $game['oLast']=$game['wLast'];
        $game['myColor']="white";

    }
    else
    {
#       $game['oNick']=$game['bNick'];
#       $game['oFirst']=$game['bFirst'];
#       $game['oLast']=$game['bLast'];
        $game['myColor']="black";
    }
}

if($game['oNick']!="Open")
{
#echo("<PRE>");print_r($game);echo("</PRE>");
$rt=GetPlayerStats($game['oID']);

if($rt['total']['games']>0){
$wpcnt=$rt['total']['win']/$rt['total']['games'];
$wpcnt*=100;
$wpcnt=floor($wpcnt);
$wpcnt.="%";
$rating="+".$rt['total']['win']." &nbsp;-".$rt['total']['loss']." &nbsp;=".$rt['total']['draw']."&nbsp;&nbsp;&nbsp;($wpcnt)";
}else{
$wpcnt="NA";
$rating="No Games Finished";
}


$rt=GetStatsAgainst($game['oID'],$_SESSION['playerID']);

if($rt['total']['games']>0){
$wpcnt=$rt['total']['win']/$rt['total']['games'];
$wpcnt*=100;
$wpcnt=floor($wpcnt);
$wpcnt.="%";
$prating="+".$rt['total']['loss']." &nbsp;-".$rt['total']['win']." &nbsp;=".$rt['total']['draw']."&nbsp;&nbsp;&nbsp;($wpcnt)";
}
else
{
$wpcnt="NA";
$prating="No Games Against";
}
}
else
{
$rating="&nbsp;";
$prating="&nbsp;";
}


$content.=<<<EOD
<TR align=center>
<TD align=left valign=middle width=50>
    <a href="javascript:CancelGame({$game['gameID']})">&gt;&gt;&gt;Cancel&lt;&lt;&lt;</a>
</TD>
<TD>
    {$game['gameID']}
</TD>
<TD>
    {$game['myColor']}
</TD>
EOD;
if($game['oNick']=="Open")
{
$content.="<TD colspan=4><table border=1 cellspacing=0 cellpadding=2 width=100%><tr><td align=center><b>Game Is Posted And Waiting For An Opponent</b></td></tr></table></TD>";
}
else
{
$content.=<<<EOD
<TD>
    {$game['oFirst']} {$game['oLast']}
</TD>
<TD>
    {$game['oNick']}
</TD>
<TD align=left>
    {$rating}
</TD>
<TD align=left>
    {$prating}
</TD>
EOD;
}

$content.=<<<EOD
<TD>
    {$game['dateCreated']}
</TD>
</TR>
EOD;
}

$content.="</TABLE>";
}
else
{
$title="You have no games pending!";
$content="You do not have any active requests for games.";
}

themeindex($title,$content,"");


$mygames=getPendingGames($id);

if ($mygames) {
$title="The Following Games Are Waiting For Opponants:";

$content=<<<EOD
<TABLE border=0 width="100%">
<TR align=center><TD></TD><TD><u>$wrdBoard #</u></TD><TD><u>Your Color</u></TD><TD><u>Opponent Name</u></TD><TD><u>Opponent Nick</u></TD><td align=left><u>Opponent Stats</u></td><TD align=left><u>Your Stats Against</u></TD><TD><u>$wrdGameStarted</u></TD></TR>
EOD;

foreach($mygames as $game)
{
if ($game['myColor']=="white")
{$ws="<B>[".$game['wScore']."]</B>";
$bs=$game['bScore'];}
else
{$bs="<B>[".$game['bScore']."]</B>";
$ws=$game['wScore'];}

if ($game['wNick'])
    {
        $game['oNick']=$game['wNick'];
        $game['oFirst']=$game['wFirst'];
        $game['oLast']=$game['wLast'];
        $game['myColor']="black";
        $game['oID']=$game['wID'];
    }
    else
    {
        $game['oNick']=$game['bNick'];
        $game['oFirst']=$game['bFirst'];
        $game['oLast']=$game['bLast'];
        $game['myColor']="white";
        $game['oID']=$game['bID'];
    }

$rt=GetPlayerStats($game['oID']);

if($rt['total']['games']>0){
$wpcnt=$rt['total']['win']/$rt['total']['games'];
$wpcnt*=100;
$wpcnt=floor($wpcnt);
$wpcnt.="%";
$rating="+".$rt['total']['win']." &nbsp;-".$rt['total']['loss']." &nbsp;=".$rt['total']['draw']."&nbsp;&nbsp;&nbsp;($wpcnt)";
}else{
$wpcnt="NA";
$rating="No Games Finished";
}


$rt=GetStatsAgainst($game['oID'],$_SESSION['playerID']);

if($rt['total']['games']>0){
$wpcnt=$rt['total']['win']/$rt['total']['games'];
$wpcnt*=100;
$wpcnt=floor($wpcnt);
$wpcnt.="%";
$prating="+".$rt['total']['win']." &nbsp;-".$rt['total']['loss']." &nbsp;=".$rt['total']['draw']."&nbsp;&nbsp;&nbsp;($wpcnt)";
}else{
$wpcnt="NA";
$prating="No Games Against";
}



$content.=<<<EOD
<TR align=center>
<TD align=left valign=middle width=50>
    <a href="javascript:JoinGame({$game['gameID']})">&gt;&gt;&gt;Join&lt;&lt;&lt;</a>
</TD>
<TD>
    {$game['gameID']}
</TD>
<TD>
    {$game['myColor']}
</TD>
<TD>
    {$game['oFirst']} {$game['oLast']}
</TD>
<TD>
    {$game['oNick']}
</TD>
<TD align=left>
    {$rating}
</TD>
<TD align=left>
    {$prating}
</TD>
<TD>
    {$game['dateCreated']}
</TD>
</TR>
EOD;
}

$content.="</TABLE>";
$content.=<<<EOD

<FORM name=frmJoinGame method=post>
<input type="hidden" name="JoinGameID">
</FORM>

EOD;

themeindex($title,$content,"");
}
else
{
themeindex("No Games Needing Opponents!","There are not any games waiting for opponants... try again later...","");
}


$title="Create A New Game";
$content=<<<EOD
<FORM name="frmNewGame" method="post">
Your Color:&nbsp;<input type="radio" name="rdoColor" value="random" checked>Random
<input type="radio" name="rdoColor" value="white">White
<input type="radio" name="rdoColor" value="black">Black
<br>
Your Opponant:<select name="opponent">
EOD;
                    $id=$_SESSION['playerID'];
                    $tmpQuery="SELECT playerID, nick FROM players WHERE playerID <> ".$id." ORDER BY nick ASC";
                    #echo $tmpQuery;
                                        $tmpPlayers = mysql_query($tmpQuery) or die("Sorry;: $tmpQuery");;

                                        while($tmpPlayer = mysql_fetch_array($tmpPlayers, MYSQL_ASSOC))
                                        {
                        if (!$tmpPlayer['nick'])
                        { $tmpPlayer['nick']="ANYBODY";
                          $tmpPlayer['playerID']=0;
                        }
                                                $content.=("<option value='".$tmpPlayer['playerID']."'> ".$tmpPlayer['nick']."</option>\n");
                                        }


$content.=<<<EOD
</select>
<br>
<br>
<input type="submit" name="NewGame" value="Post New Request...">
</FORM>

<FORM name=frmCancelGame method=post>
<input type="hidden" name="CancelGameID">
</FORM>
    <br>
    Or <a href="file:///C|/DOCUME%7E1/DENNIS%7E1/LOCALS%7E1/Temp/Rar$DI35.203/players.php?show=ALL">Choose From Our Player List</a>
EOD;

themeindex($title,$content,"");


themefooter();
?>

