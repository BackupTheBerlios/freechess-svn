<?php
##############################################################################################
#                                                                                            #
#                                chat.php
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

include_once('global_includes.php');
    require_once ( 'chessutils.php');
    require_once('gui.php');
    require_once('chessdb.php');
    require 'move.php';
    require 'undo.php';


    /* Language selection */
    require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");

     header("Cache-Control: no-cache");
    header("Expires: 0");




if (!empty($_POST['close']))
{

    //$pl = mysql_query("SELECT * FROM players WHERE playerID='".$_SESSION['playerID']."'");
    //$me = mysql_fetch_array($pl);

    //$msg = $me['firstName'] . "event-leave";

    //mysql_query("insert into testchat (fromID,msg,gameID) VALUES ('0','$msg','".$_SESSION[gameID]."')");


    // Inaktive User löschen
    if ($enable_chat && $_POST[chat_msg] != "") //AKA shoutbox
{

        if (!get_magic_quotes_gpc())

                 $_POST['chat_msg'] = addslashes($_POST['chat_msg']);

            mysql_query("INSERT INTO testchat (fromID,msg,gameID)
                     VALUES ('".$_SESSION['player_id']."', '".$_POST['chat_msg']."', '".$_SESSION['game_id']."')");
}

    //mysql_query("DELETE FROM chat_online WHERE player_id='".$_SESSION['player_id']."'");


?>

<html>
<head>
<META HTTP-EQUIV="Expires" CONTENT="0">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">

<script language="javascript">
<!--
 function done() {

   self.close();
 }
//-->
</script>
</head>
<body onload="done()">
</body>
</html>

<?

}

?>



<html>
<head>

<LINK rel="stylesheet" href="themes/<?=$_SESSION["style"]?>/mainstyles.css" type="text/css">

<title>Public Chatroom</title>

<script type="text/javascript">
<!--
function CheckAuswahl() {

parent.chat_window.location.href='chat_window.php?sec='+document.chess.sec.options[document.chess.sec.selectedIndex].value;

}
//-->
</script>

</style>
</head>
<body>

    <form method="POST" action="chat.php" name="chess">

    <table border='0' width=100 height=50 bgcolor=black cellspacing=1 cellpading=1>
    <tr>

    <? if ($enable_chat){ ?>


    <td style="background-color:#DDDDDD" align="center" valign="top">
    <input type="text" name="chat_msg" size="50" >

    <input type="submit" value="<?=$MSG_LANG["write"]?>">&nbsp;
    <input type=hidden name="close" value="true">



    </select>
    &nbsp;&nbsp;
    <b>Smilies:</b>&nbsp;<? echo smilielist(); ?>
    </form>
<table width="100%" border="1">
  <tr>
    <td>Please Read the<a href="chatrules.php" target="_blank"> Chat Rules </a>Before Posting Here! <br>There is an approximate 60 second delay before your message will be posted.  Only the most 10 recent messages will show to to the public.</strong>
</td>
  </tr>
</table>

    </tr>



    <?}?>


</table>

</font>
</body>
</html>


