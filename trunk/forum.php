<?php
##############################################################################################
#                                                                                            #
#                                forum.php
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

    $root = dirname(__FILE__);


        require_once ( 'chessutils.php');

    require_once('gui.php');
    require_once('chessdb.php');
    require 'move.php';
    require 'undo.php';
    require_once ( 'newgame.php');


    /* check if loading game */
    if (isset($_POST['gameID']))
        $_SESSION['gameID'] = $_POST['gameID'];


    /* Language selection */
    require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");

    require "forum_functions.php";

$action = (!empty($_POST['action'])) ? $_POST['action'] : $_GET['action'];

$pl = mysql_query("SELECT * FROM {$db_prefix}players WHERE player_id='".$_SESSION['player_id']."'");
$me = mysql_fetch_array($pl);

$firstName = $me['username'];

?>

<html>
<head>
<title>WebChess</title>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["style"]?>/styles.css" type="text/css">
<SCRIPT language="Javascript">
function sendpn(player_id)
{
    var where="sendmessage.php?to="+player_id;
    var height=500;
    var width=500;
    var left=(screen.availWidth/2)-(width/2);
    var top=(screen.availHeight/2)-(height/2)-100;

    window.open(where,"","height="+height+",width="+width+",left="+left+",top="+top,scrollbars="yes");
}

function einblenden(elementname)
{
  document.getElementById(elementname).style.display='block';
}

function ausblenden(elementname)
{
  document.getElementById(elementname).style.display='none';
}

function hide(elementname)
{
   if (document.getElementById(elementname).style.display == 'none')
   {
        einblenden(elementname);
   }
   else
   {
        ausblenden(elementname);
   }
}
</SCRIPT>
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>


<BR>

<?PHP

switch ($action) {

default:

?>





<table>
<tr>
<th colspan="4"><?=$MSG_LANG["forum4"]?></th>
</tr>
<tr>
<td><b><?=$MSG_LANG["forum"]?></b></td>
<td><b><?=$MSG_LANG["lastpost"]?></b></td>
<td><b><?=$MSG_LANG["topics"]?></b></td>
<td><b><?=$MSG_LANG["post"]?></b></td>
</tr>

<?PHP

$f1 = mysql_query("SELECT * FROM forums ORDER BY chessforum ASC");

while ($f = mysql_fetch_array($f1)) {

$count1 = getcount("forum_topics", "WHERE forum_id='".$f['forum_id']."' AND replyto = '0'");
$count2 = getcount("forum_topics", "WHERE forum_id='".$f['forum_id']."' AND replyto != '0'");

$title = db_output($f['forum_title']);
$text = db_output($f['forum_text']);

echo "<tr>";
echo '<td><div width="100%" align="left"><a href="forum.php?action=viewforum&id='.$f['forum_id'].'">'.$title.'</a><br>'.$text.'</div></td>';
echo '<td noWrap><div width="100%" align="right">';

if ($count1 == 0) echo "-";

else {

$t1 = mysql_query("SELECT t.*, p.playerID, p.firstName FROM forum_topics t
                   LEFT JOIN players p ON p.playerID = t.userid
                   WHERE t.forum_id = '".$f['forum_id']."'
                   ORDER BY t.lastreply DESC");
echo mysql_error();
$t = mysql_fetch_array($t1);

$count = getcount('forum_topics', "WHERE replyto = '".$t['topic_id']."'");

if ($count > 0)
{

echo date("d.m.y, H:i", $t['lastreply']);

$lp1 = mysql_query("SELECT t.topic_id, t.userid, p.firstName FROM forum_topics t
                    LEFT JOIN players p ON p.playerID = t.userid
                    WHERE t.replyto = '".$t['topic_id']."'
                    AND t.time = '".$t['lastreply']."'");
echo mysql_error();
$lp = mysql_fetch_array($lp1);

echo '<br>'.$MSG_LANG['by'].' <a href="stats_user.php?cod='.$lp['userid'].'">'.$lp['firstName'].'</a>&nbsp;&nbsp;';

$total_pages = ceil($count/$topics_perpage);
$start = ($total_pages - 1) * $topics_perpage;
if ($start < 0) $start = 0;
$link = 'forum.php?action=viewtopic&id='.$t['topic_id'].'&start='.$start.'#'.$lp['topic_id'];

echo '<a href="'.$link.'">>></a>';

}

else {

echo date("d.m.y, H:i", $t['time']);

echo '<br>'.$MSG_LANG['by'].' <a href="stats_user.php?cod='.$t['userid'].'">'.$t['firstName'].'</a>&nbsp;&nbsp;';
$link = 'forum.php?action=viewtopic&id='.$t['topic_id'];

echo '<a href="'.$link.'">>></a>';

}

} // count1

echo "</div></td>";
echo '<td>'.$count1.'</td>';
echo '<td>'.$count2.'</td>';
echo "</tr>";

}

echo "</table>";

break; // default

case 'viewforum':

if (!$_GET['id']) {

    echo "Mistake!";
    exit;
}

$f = getforum($_GET['id']);

$start = (!empty($_GET['start'])) ? $_GET['start'] : 0;
$shownum = $topics_perpage;
$num = getcount("forum_topics", "WHERE replyto = '0' AND forum_id = '".$_GET['id']."'");
$link = "forum.php?action=viewforum&id=".$_GET['id'];

$nav = make_nav($link, $num, $shownum, $start, true);

?>

<table width="100%">
<tr>
<td width="50%">
<input type="button" class="BOTOES" value="New Topic" onclick="location.href='forum.php?action=newtopic&id=<?=$_GET['id']?>';">
</td>
<td width="50%">
<?=$nav?>
</td>
</tr>
</table>

<br>

<table width="100%">
<tr>
<td width="100%">
This is a chess forum, where current games and moves. New posts are provided here directly over the game.
</td>
</tr>
</table>

<br>

<table>
<tr>
<th colspan="4"><a href="forum.php"><b>Forum</b></a> > <?=$f['forum_title']?></th>
</tr>
<tr>
<td><b>Topic</b></td>
<td><b>Last Post</b></td>
<td><b>Reply</b></td>
<td><b>Hits</b></td>
</tr>

<?PHP

$c = 0;

$t1 = mysql_query("SELECT t.*, p.firstName FROM forum_topics t
                    LEFT JOIN players p ON p.playerID = t.userid
                    WHERE t.forum_id = '".$_GET['id']."'
                    AND t.replyto = 0
                    ORDER BY lastreply DESC
                    LIMIT $start, $shownum");
echo mysql_error();
while ($t = mysql_fetch_array($t1)) {

$title = db_output($t['title']);
$count = getcount('forum_topics', "WHERE replyto = '".$t['topic_id']."'");

echo "<tr>";
echo '<td style="padding-left: 5px;"><div align="left"><a href="forum.php?action=viewtopic&id='.$t['topic_id'].'">'.$title.'</a><br>'.$t['firstName'].'</div></td>';
echo '<td><div width="100%" align="right">';

if ($count > 0)
{

echo date("d.m.y, H:i", $t['lastreply']);

$lp1 = mysql_query("SELECT t.topic_id, t.userid, p.firstName FROM forum_topics t
                    LEFT JOIN players p ON p.playerID = t.userid
                    WHERE t.replyto = '".$t['topic_id']."'
                    AND t.time = '".$t['lastreply']."'");
echo mysql_error();
$lp = mysql_fetch_array($lp1);

echo '<br>'.$MSG_LANG['by'].' <a href="stats_user.php?cod='.$lp['userid'].'">'.$lp['firstName'].'</a>&nbsp;&nbsp;';

$total_pages = ceil($count/$topics_perpage);
$start = ($total_pages - 1) * $topics_perpage;
if ($start < 0) $start = 0;
$link = 'forum.php?action=viewtopic&id='.$t['topic_id'].'&start='.$start.'#'.$lp['topic_id'];

echo '<a href="'.$link.'">>></a>';

}

else {

echo date("d.m.y, H:i", $t['time']);

echo '<br>'.$MSG_LANG['by'].' <a href="stats_user.php?cod='.$t['userid'].'">'.$t['firstName'].'</a>&nbsp;&nbsp;';
$link = 'forum.php?action=viewtopic&id='.$t['topic_id'];

echo '<a href="'.$link.'">>></a>';

}

echo '</div></td>';
echo '<td>'.$count.'</td>';
echo '<td>'.$t['hits'].'</td>';
echo '</tr>';

$c++;

} // while

if ($c == 0)
{
    echo '<tr><td colspan="4">Still no posts in this forum./td></tr>';
}


?>

</table>
<br>

<table width="100%">
<tr>
<td width="50%">
<input type="button" class="BOTOES" value="New Topic" onclick="location.href='forum.php?action=newtopic&id=<?=$_GET['id']?>';">
</td>
<td width="50%">
<?=$nav?>
</td>
</tr>
</table>

<?PHP

break; // viewforum

case 'gameselect':

if (isset($_POST['game']))
{
    $num = getcount("games", "WHERE gameID = '".$_POST['game']."'");

    ?>
    <script language="javascript">

    function goto_game(id) {

        var location='forum.php?action=viewtopic&id='+id;

        parent.location.href=location;

    }

    </script>

    <?PHP

    if ($num == 0)
    {
        ?>
        <script>
        parent.document.posting.gameid.value='';
        parent.document.posting.tmpgame.value='';
        parent.document.posting.title.value='';
        parent.hide('div02');
        parent.posting.text.focus;
        </script>
        <?PHP
    }
    else
    {

        $num = getcount("forum_topics", "WHERE replyto = 0 AND gameid = '".$_POST['game']."'");

        if ($num > 0)
        {
            $t1 = mysql_query("SELECT topic_id FROM forum_topics WHERE replyto = 0 AND gameid = '".$_POST['game']."'");
            $t = mysql_fetch_array($t1);

            echo "<b>A post already exist regarding this move.<br>";
            echo '<a href="#" onclick="javascript:goto_game(\''.$t['topic_id'].'\');">Click here to view post.</a>';
            exit;
        }

        else {

        ?>

        <script>
        parent.document.posting.gameid.value='<?=$_POST['game']?>';
        parent.document.posting.tmpgame.value='<?=$_POST['game']?>';
        parent.document.posting.title.value='<?PHP echo gamename($_POST['game'], false); ?>';
        parent.hide('div02');
        parent.posting.text.focus;
        </script>

        <?PHP

        }
    }
}


?>

<form action="forum.php" method="POST" name="gameform">
<input type="hidden" name="action" value="gameselect">
<b>Enter a post on regarding this move.</b>
<br><br>
Game-Nr.<br>
<input type="text" name="game" maxlength="11" size="11">
<br><br>
<input type="submit">
</form>

<?PHP

break; // gameselect

case 'newtopic':

if (!$_GET['id'] && !$_GET['replyto']) {

    echo "Mistake!";
    exit;
}

?>
<table>
<tr>
<th>
<?PHP

if (!empty($_GET['id'])) {

$f = getforum($_GET['id']);

echo 'Post a new Topic. > Forum: <a href="forum.php?viewforum&id='.$f['forum_id'].'">'.$f['forum_title'].'</a>';

}

else {

$t1 = mysql_query("SELECT title FROM forum_topics WHERE topic_id = '".$_GET['replyto']."'");
$t = mysql_fetch_array($t1);
$title = db_output($t['title']);

echo 'Reply: <a href="forum.php?viewtopic&id='.$_GET['replyto'].'">'.$title.'</a>';

$f = getforum_bytopic($_GET['replyto']);

}

?>

<script language="JavaScript">
<!--

function setimgurl(type1,type2) {
 pic_text = "Name (mit Erweiterung) des Bildes angeben (Nach <? echo $Bildverzeichnis; ?>/)";
 pic_content = "<? echo $Bildverzeichnis; ?>/";
 picurl = prompt(pic_text,pic_content);
 link_text = "Seite, die nach Klick auf das Bild aufgerufen werden soll (optional)";
 link_content = "http://";
 url = prompt(link_text,link_content);
 if ((picurl != null) && (picurl != "")) {
  if ((url != null) && (url != "http://") && (url != ""))
   document.posting.text.value += "["+type2+"="+url+"]"+"["+type1+"="+picurl+"]"+"[/"+type2+"] ";
  else
   document.posting.text.value += "["+type1+"="+picurl+"] ";
  }
 document.posting.text.focus();
}


function smilies(Zeichen) {
  document.posting.text.value =
  document.posting.text.value + Zeichen;
}


function setcode(code,prompttext) {
        inserttext = prompt("Zu formatierenden Text eingeben:"+"\n",prompttext);
        if ((inserttext != null) && (inserttext != ""))
        document.posting.text.value += "["+code+"]"+inserttext+"[/"+code+"] ";
    document.posting.text.focus();
}


function seturl(type) {
    description = prompt("Beschreibungstext eingeben (optional)","");
    if (type == "URL") {
        text = "Link eingeben";
        content = "http://";
        }
    else {
        text = "eMail-Adresse eingeben";
        content = "";
        }
    url = prompt(text,content);
    if ((url != null) && (url != "")) {
        if ((description != null) && (description != ""))
            document.posting.text.value += "["+type+"="+url+"]" +description+  "[/"+type+"] ";
        else
            document.posting.text.value += "["+type+"]"+url+"[/"+type+"] ";
        }
    document.posting.text.focus();
}

function setimgurl(type1,type2) {
    pic_text = "URL des Bildes angeben";
    pic_content = "http://";
    picurl = prompt(pic_text,pic_content);

    if ((picurl != null) && (picurl != "")) {

        document.posting.text.value += "[img="+picurl+"] ";
    }

    document.posting.text.focus();
}

function askgame() {

    window.open("forum.php?action=gameselect", "gameselect", "toolbar=no,scrollbars=no,resizable=no,width=310,height=235");
}

//-->
</script>

<?PHP

if (!empty($_GET['replyto'])) {

    $ttitle = 'value="Re: '.$title.'"';
}

if (!empty($_GET['game'])) {

    $ttitle = gamename($_GET['game']);
}

?>

</th>
</tr>
<tr>
<td>

<br>

<form action="forum.php" method="post" name="posting">
<input type="hidden" name="action" value="doreply">
<input type="hidden" name="forum_id" value="<?=$f['forum_id']?>">
<input type="hidden" name="replyto" value="<?=$_GET['replyto']?>">
<input type="hidden" name="gameid" value="<?=$_GET['game']?>">

<?PHP

if (!$_GET['replyto']) {

?>Game-#<br>
<input type="text" name="tmpgame" maxlength="11" size="10" readonly value="<?=$_GET['game']?>" onclick="hide('div02');"">
<br><br>
<div id="div02" style="display:none;">

<iframe align="0" src="forum.php?action=gameselect" frameborder="0" width="440" height="200" scrolling="no" style="border: 1px solid #000000;"></iframe>

<br><br>
</div>
<?PHP

} // gameselect

?>

Title: <br>
<input type="text" name="title" maxlength="125" size="40" <?=$ttitle?>>
<br>
<input type="button" name="[b]" title="Bold" value=" B " onClick="javascript:setcode('B','')">
<input type="button" name="[i]" title="Italicize" value=" I " onClick="javascript:setcode('I','')">
<input type="button" name="[u]" title="Underline" value=" U " onClick="javascript:setcode('U','')">
<input type="button" name="[url]" title="Link" value="http" onClick="javascript:seturl('URL')">
<input type="button" name="[email]" title="Email-Link" value="@" onClick="javascript:seturl('EMAIL')">
<input type="button" name="[#]" title="Code-Text" value="#" onClick="javascript:setcode('CODE','')">
<input type="button" name="[quote]" title="Include a quote" value="Quote" onClick="javascript:setcode('QUOTE','')")>
<input type="button" name="[img]" title="Add an image" value="Image" onClick="javascript:setimgurl('IMG','URL')"><br>
Message:<br>
<textarea name="text" rows="20" cols="60" wrap="virtual" style="width:540px; height:250px" tabindex="1">

<?PHP

if ($_GET['quote']) {

$q1 = mysql_query("SELECT t.text, t.userid, p.firstName FROM forum_topics t
                    LEFT JOIN players p ON p.playerID = t.userid
                    WHERE t.topic_id = '".$_GET['quote']."'");
echo mysql_error();
$q = mysql_fetch_array($q1);

$text = db_output($q['text'], true);

echo '[quote][b]'.$q['firstName'].' wrote:[/b]

'.$text.'[/quote]';

}

elseif (!empty($_GET['text'])) {

    echo $_GET['text'];
}

?>

</textarea><br>

<?PHP echo forum_smilielist(); ?>

<br><br>

<input type="submit">

</td>
</tr>
</table>

<?PHP

if ($_GET['replyto']) {

$f = getforum_bytopic($_GET['replyto']);

?>

<br><br>

<?PHP

$t1 = mysql_query("SELECT t.*, p.firstName, p.playerID, p.lastUpdate from forum_topics t
                    LEFT JOIN players p ON p.playerID = t.userid
                    WHERE t.topic_id = '".$_GET['replyto']."'
                    OR t.replyto = '".$_GET['replyto']."'
                    ORDER BY time DESC
                    LIMIT ".$replies_perpage);
//echo mysql_error();
while ($t = mysql_fetch_array($t1)) {

$title = db_output($t['title']);
$title = strip_tags($title);
$text = db_output($t['text'], true);
$text = strip_tags($text, "<br>");
$text = bbcode($text);
$text = forum_smilies($text);

$date = date("d.m.y, H:i", $t['time']);

?>
<table>
<tr>
<th width="180">
<div align="left" width="100%"><?=$date?></div>
</th>
<th><?=$title?></th>
</tr>
<tr>
<td width="180" valign="top">

<?=$t['firstName']?></a>

</td>
<td valign="top"><div width="100%" align="left"><?=$text?></div></td>
</tr>
</table>

<?PHP

} // while

} // replyto

break; // newtopic

case 'doreply':

if (!$_POST['forum_id'])
{
    echo "Mistake!";
    exit;
}

if ($_POST['replyto'] == "" && empty($_POST['title'])) {

    echo "Must enter a title!";
    exit;
}

if (empty($_POST['text'])) {

    echo "Must enter a message!";
    exit;
}

$title = db_input($_POST['title']);
$text = db_input($_POST['text']);
$time = time();
$forum_id = $_POST['forum_id'];
$replyto = ($_POST['replyto'] == "") ? 0 : $_POST['replyto'];

$gameid = (!empty($_POST['gameid'])) ? $_POST['gameid'] : 0;

$query = "INSERT INTO forum_topics VALUES (NULL, '".$forum_id."', '".$_SESSION['playerID']."', '".$replyto."', '$title', '$text', '$time', '0', '$time','$gameid')";

mysql_query($query);

if ($replyto > 0) {

    mysql_query("UPDATE forum_topics SET lastreply = '$time' WHERE topic_id = '$replyto'");
}

$query = "SELECT topic_id FROM forum_topics WHERE userid = '".$_SESSION['playerID']."' AND time = '$time'";

$i1 = mysql_query($query);
$i = mysql_fetch_array($i1);

$ankor = $i['topic_id'];

$topic = ($replyto == 0) ? $ankor : $replyto;

$url = 'forum.php?action=viewtopic&id='.$topic;

if ($replyto > 0) {

    $num = getcount("forum_topics", "WHERE replyto = '$topic' OR topic_id = '$topic'");
    $total_pages = ceil($num/$topics_perpage);
    $start = ($total_pages - 1) * $topics_perpage;
    if ($start < 0) $start = 0;
    $url .= '&start='.$start;
}

echo '<meta http-equiv="Refresh" content="2;url='.$url.'#'.$ankor.'">';

echo '<table width="100%"><tr><td>';

echo "Thanks for your Post. You are being forwarded on to your post.<br><br>";

echo '<a href="'.$url.'#'.$ankor.'">Click here, if you do not want to wait longer (or if your Browser does not support automatic forwarding)</a>';

echo "</td></tr></table>";

break; // doreply

case 'viewtopic':

if (!$_GET['id']) {

    echo "Mistake!";
    exit;
}

$f = getforum_bytopic($_GET['id']);

$start = (!empty($_GET['start'])) ? $_GET['start'] : 0;
$shownum = $replies_perpage;
$num = getcount("forum_topics", "WHERE replyto = '".$_GET['id']."' OR topic_id = '".$_GET['id']."'");
$link = "forum.php?action=viewtopic&id=".$_GET['id'];

$nav = make_nav($link, $num, $shownum, $start, true);

$p1 = mysql_query("SELECT gameid FROM forum_topics WHERE topic_id = '".$_GET['id']."'");
$pg = mysql_fetch_array($p1);

?>

<table>
<tr>
<th width="100%">
<a href="forum.php">Forum</a> > <a href="forum.php?action=viewforum&id=<?=$f['forum_id']?>"><?=$f['forum_title']?></a>
</th>
</tr>
</table>

<br>

<table width="100%">
<tr>
<td width="50%">
<input type="button" class="BOTOES" value="Reply" onclick="location.href='forum.php?action=newtopic&replyto=<?=$_GET['id']?>';">
</td>
<td>
<?=$nav?>
</td>
</tr>
</table>

<br>

<?PHP

if ($pg['gameid'] > 0) {

    $p = mysql_query("SELECT * from games WHERE gameID='".$pg['gameid']."'");
    $row = mysql_fetch_array($p);
    $white = $row['whitePlayer'];
    $black = $row['blackPlayer'];

    $p = mysql_query("SELECT firstName,playerID,engine FROM players WHERE playerID='$white'");
    $row = mysql_fetch_array($p);
    $p = mysql_query("SELECT firstName,playerID,engine FROM players WHERE playerID='$black'");
    $row2 = mysql_fetch_array($p);
    $row[0] = $row[0]." (".$MSG_LANG["white"].")";
    $row2[0] = $row2[0]." (".$MSG_LANG["black"].")";

    $ttitle = $pg['gameid'].': '.$row[0].' X '.$row2[0];

?>

<table width="100%">
<tr>
<td width="100%">
This posts refers to the Game # <a href="chess.php?gameID=<?=$pg['gameid']?>"><?=$ttitle?></a>

<?PHP

$cnum = getcount("comments", "WHERE gameID = '".$pg['gameid']."'");

if ($cnum > 0) {

?>
, and this one also <a href="forum_analyze.php?game=<?=$pg['gameid']?>"><?=$cnum?> There are comments on this game.</a>
<?PHP

}

?>

<br><br>
<a href="#" onclick="hide('div0')";>Show board / hide</a>
</td>
</tr>
</table>

<br>
<?PHP

$_REQUEST['game'] = $pg['gameid'];

?>
<div id="div0">

<iframe align="0" src="forum_analyze.php?game=<?=$_REQUEST['game']?>" frameborder="0" width="648" height="365" scrolling="no" style="border: 1px solid #000000;"></iframe>

</div>
<br>
<?PHP

}

$i = $start + 1;

mysql_query("UPDATE forum_topics SET hits = hits + 1 WHERE topic_id = '".$_GET['id']."'");

$t1 = mysql_query("SELECT t.*, p.firstName, p.playerID, p.lastUpdate from forum_topics t
                    LEFT JOIN players p ON p.playerID = t.userid
                    WHERE t.topic_id = '".$_GET['id']."'
                    OR t.replyto = '".$_GET['id']."'
                    ORDER BY time ASC
                    LIMIT $start, $shownum");
//echo mysql_error();
while ($t = mysql_fetch_array($t1)) {

$title = db_output($t['title']);
$title = strip_tags($title);
$text = db_output($t['text']);
$text = strip_tags($text, "<br>");
$text = bbcode($text);
$text = forum_smilies($text);

$date = date("d.m.y, H:i", $t['time']);

?>
<a name="<?=$t['topic_id']?>">
<table>
<tr>
<th width="180">
<div align="left" width="180">Posted on: <?=$date?></div>
</th>
<th width="100%"><div align="left" width="100%"><?=$title?> (#<?=$i?>)</div>
</th>
</tr>
<tr>
<td width="180" valign="top" noWrap>

    <?PHP

    if ($t['lastUpdate'] >= (time()-300))
        $oimg="online";
    else
        $oimg="offline";

    echo '<img src="images/'.$oimg.'.gif">';

    ?>

    &nbsp;
    <b><a href="stats_user.php?cod=<?=$t['playerID']?>"><?=$t['firstName']?></a></b>
    <br> <br>
    <?PHP

    $img = $def_avatar;
    reset ($extensions);

    while (list($key, $val) = each($extensions)) {

    $imgpath = "http://www.Webmaster/webchess" . '/images/avatars/' . $t['playerID'] . '.' . $val;

    if (file_exists($imgpath)) {

        $img = $t['playerID'] . '.' . $val;
    }
showavatar($t['playerID']);
    }




if ($CFG_ENABLE_SUBRANKING){
    $subrank = "";
    $p2 = mysql_query("select * from medals where playerID='$t[playerID]'");
    while($row2 = mysql_fetch_array($p2))
        $subrank .= "<img alt='".$row2[medal]."' src='images/rank/$row2[medal].gif'>";

    echo "<br><br>$subrank";
}

$status = ($t['playerID'] == 500) ? $status="Administrator<br>" : "";
$status44 = ($t['playerID'] == 500) ? "Chess-Trainer<br>" : "";

echo "<br>".$status.$status44;

$player = $t['playerID'];

$stats_user = getStatsUser($player,1);
$vitorias = $stats_user[0];
$derrotas = $stats_user[1];
$empates = $stats_user[2];
$ativos = $stats_user[3];
$total = $vitorias+$derrotas+$empates;

echo "<br>Points: $total";

if ($total < 10)
            $status12="Chess Novice";
elseif ($total >= 10 AND $total <= 50)
            $status12="Chess Junior";
elseif ($total >= 51 AND $total <= 100)
            $status12="Chess Member";
elseif ($total >= 101 AND $total <= 200)
            $status12="Chess Fan";
elseif ($total >= 201 AND $total <= 300)
            $status12="Chess Enthusiast";
else $status12="Chess Senior";

echo "<br>".$status12;

?>

<br><br>
Posts: <?PHP echo postcount($t['playerID']); ?></td>
<td valign="top" width="100%">
<div width="100%" align="left" style="padding-top:5px; padding-left:5px;  word-wrap:break-word;">
<?=$text?>
</div></td>
</tr>
<tr>
<td colspan="2">
<div width="100%" align="right">
<a href="forum.php?action=edittopic&id=<?=$t['topic_id']?>">Edit Post</a>&nbsp;|&nbsp;
<a href="forum.php?action=deletetopic&id=<?=$t['topic_id']?>">Delete Post</a>&nbsp;|&nbsp;
<a href="#" onclick="sendpn(<?=$t['playerID']?>);">Send PM</a>&nbsp;|&nbsp;
<a href="forum.php?action=newtopic&replyto=<?=$_GET['id']?>&quote=<?=$t['topic_id']?>">Quote</a>
</div>
</td>
</tr>
</table>

<img height="5" src="">

<?PHP

$i++;

}

?>

<br><br>

<table width="100%">
<tr>
<td width="50%">
<input type="button" class="BOTOES" value="Reply" onclick="location.href='forum.php?action=newtopic&replyto=<?=$_GET['id']?>';">
</td>
<td>
<?=$nav?>
</td>
</tr>
</table>

<br>

<table>
<tr>
<th width="100%">
<a href="forum.php">Forum</a> > <a href="forum.php?action=viewforum&id=<?=$f['forum_id']?>"><?=$f['forum_title']?></a>
</th>
</tr>
</table>

<br>
<?PHP

break; // viewtopic

case 'deletetopic':

if (!$_GET['id']) {

    echo "Mistake!";
    exit;
}

$q1 = mysql_query("SELECT t.title, t.time, t.text, t.userid, t.replyto, p.firstName FROM forum_topics t
                    LEFT JOIN players p ON p.playerID = t.userid
                    WHERE t.topic_id = '".$_GET['id']."'");
echo mysql_error();
$q = mysql_fetch_array($q1);

if (($q['playerID'] != $_SESSION['playerID']) && (!$me['admin']))
{
    echo "No authorization!";
}

else {

if ($_GET['confirm'] != true)
{

$title = db_output($q['text']);
$date = date("d.m.y, H:i", $q['time']);

$reply = "";

if ($q['replyto'] > 0) {

    $reply = "&replyto=".$q['replyto'];
}

?>

    <table width="100%">
    <tr>
    <td>
    <div align="left" width="100%">
    <b>Delete Post</b><br><br>
    <?=$title?> - Posted by <?=$q['firstName']?> on <?=$date?>
    <br><br>
    <font color="#FF0000">ATTENTION:</font> You are about to delete this post permanently! This will delete the first post of the subject   and all replies following it!
    <br><br>
    <a href="forum.php?action=deletetopic&id=<?=$_GET['id']?>&confirm=true<?=$reply?>">Delete Posts</a><br>
    <a href="javascript:history.back()">BACK</a>
    </div>
    </td>
    </tr>
    </table>

<?PHP

} else {

if ($_GET['replyto']) {

    mysql_query("DELETE FROM forum_topics WHERE topic_id = '".$_GET['id']."'");

    $u1 = mysql_query("SELECT time FROM forum_topics
                        WHERE topic_id = '".$_GET['replyto']."'
                        OR replyto = '".$_GET['replyto']."'
                        ORDER BY time DESC");
    echo mysql_error();
    $u = mysql_fetch_array($u1);

    mysql_query("UPDATE forum_topics SET lastreply = '".$u['time']."' WHERE topic_id = '".$_GET['replyto']."'");

    $topic = $_GET['replyto'];

    $url = 'forum.php?action=viewtopic&id='.$topic;

    $num = getcount("forum_topics", "WHERE replyto = '$topic' OR topic_id = '$topic'");
    $total_pages = ceil($num/$topics_perpage);
    $start = ($total_pages - 1) * $topics_perpage;
    if ($start < 0) $start = 0;
    $url .= '&start='.$start;
}

else {

    $forum = getforum_bytopic($_GET['id']);
    $url = 'forum.php?action=viewforum&id='.$forum['forum_id'];

    mysql_query("DELETE FROM forum_topics WHERE topic_id = '".$_GET['id']."'");
    mysql_query("DELETE FROM forum_topics WHERE replyto = '".$_GET['id']."'");
}

echo '<meta http-equiv="Refresh" content="2;url='.$url.'">';

echo '<table width="100%"><tr><td>';

echo "The post was posted. You are now being forwarded.<br><br>";

echo '<a href="'.$url.'">Click here, if you do not want to wait longer (or if your Browser does not support automatic forwarding)</a>';

echo "</td></tr></table>";

} // confirm

} // admin

break; // deletetopic

case 'edittopic':

if (!$_GET['id'] && !$_GET['replyto']) {

    echo "Mistake!";
    exit;
}

$t1 = mysql_query("SELECT * FROM forum_topics WHERE topic_id = '".$_GET['id']."'"); echo mysql_error();
$t = mysql_fetch_array($t1);

if (($t['userid'] != $_SESSION['playerID']) && ($me['admin'] == 0))
{
    echo "No authorization!";
    exit;
}

$title = db_output($t['title']);
$text = db_output($t['text'], true);

?>

<script language="JavaScript">
<!--

function setimgurl(type1,type2) {
 pic_text = "Name (mit Erweiterung) des Bildes angeben (Nach <? echo $Bildverzeichnis; ?>/)";
 pic_content = "<? echo $Bildverzeichnis; ?>/";
 picurl = prompt(pic_text,pic_content);
 link_text = "Seite, die nach Klick auf das Bild aufgerufen werden soll (optional)";
 link_content = "http://";
 url = prompt(link_text,link_content);
 if ((picurl != null) && (picurl != "")) {
  if ((url != null) && (url != "http://") && (url != ""))
   document.posting.text.value += "["+type2+"="+url+"]"+"["+type1+"="+picurl+"]"+"[/"+type2+"] ";
  else
   document.posting.text.value += "["+type1+"="+picurl+"] ";
  }
 document.posting.text.focus();
}


function smilies(Zeichen) {
  document.posting.text.value =
  document.posting.text.value + Zeichen;
}


function setcode(code,prompttext) {
        inserttext = prompt("Zu formatierenden Text eingeben:"+"\n",prompttext);
        if ((inserttext != null) && (inserttext != ""))
        document.posting.text.value += "["+code+"]"+inserttext+"[/"+code+"] ";
    document.posting.text.focus();
}


function seturl(type) {
    description = prompt("Beschreibungstext eingeben (optional)","");
    if (type == "URL") {
        text = "Link eingeben";
        content = "http://";
        }
    else {
        text = "eMail-Adresse eingeben";
        content = "";
        }
    url = prompt(text,content);
    if ((url != null) && (url != "")) {
        if ((description != null) && (description != ""))
            document.posting.text.value += "["+type+"="+url+"]" +description+  "[/"+type+"] ";
        else
            document.posting.text.value += "["+type+"]"+url+"[/"+type+"] ";
        }
    document.posting.text.focus();
}

function setimgurl(type1,type2) {
    pic_text = "URL des Bildes angeben";
    pic_content = "http://";
    picurl = prompt(pic_text,pic_content);

    if ((picurl != null) && (picurl != "")) {

        document.posting.text.value += "[img="+picurl+"] ";
    }

    document.posting.text.focus();
}


//-->
</script>

<table>
<tr>
<th>

<?PHP

echo 'Edit: <a href="forum.php?viewtopic&id='.$_GET['id'].'">'.$title.'</a>';

$f = getforum_bytopic($_GET['replyto']);

?>

</th>
</tr>
<tr>
<td>

<br>

<form action="forum.php" method="post" name="posting">
<input type="hidden" name="action" value="updatetopic">
<input type="hidden" name="topic_id" value="<?=$_GET['id']?>">

Title: <br>
<input type="text" name="title" maxlength="125" size="40" value="<?=$title?>">
<br><br>
<input type="button" name="[b]" title="Bold" value=" B " onClick="javascript:setcode('B','')">
<input type="button" name="[i]" title="Italicize" value=" I " onClick="javascript:setcode('I','')">
<input type="button" name="[u]" title="Underline" value=" U " onClick="javascript:setcode('U','')">
<input type="button" name="[url]" title="Link" value="http" onClick="javascript:seturl('URL')">
<input type="button" name="[email]" title="Email-Link" value="@" onClick="javascript:seturl('EMAIL')">
<input type="button" name="[#]" title="Code-Text" value="#" onClick="javascript:setcode('CODE','')">
<input type="button" name="[quote]" title="Include a quote" value="Quote" onClick="javascript:setcode('QUOTE','')")>
<input type="button" name="[img]" title="Add an image" value="Image" onClick="javascript:setimgurl('IMG','URL')">
<br>Message:<br>



<textarea name="text" rows="20" cols="60" wrap="virtual" style="width:540px; height:250px" tabindex="1"><?=$text?>

</textarea><br>

<?PHP echo forum_smilielist(); ?>

<br><br>

<input type="submit">

</td>
</tr>
</table>

<?PHP

break;

case 'updatetopic':

if ($_POST['replyto'] == "" && empty($_POST['title'])) {

    echo "Must enter a title!";
    exit;
}

if (empty($_POST['text'])) {

    echo "Must enter a Message!";
    exit;
}

$title = db_input($_POST['title']);
$text = db_input($_POST['text']);

mysql_query("UPDATE forum_topics SET title = '$title', text = '$text' WHERE topic_id = '".$_POST['topic_id']."'");

$t1 = mysql_query("SELECT * FROM forum_topics WHERE topic_id = '".$_POST['topic_id']."'"); echo mysql_error();
$t = mysql_fetch_array($t1);

$topic = ($t['replyto'] > 0) ? $t['replyto'] : $_POST['topic_id'];

$url = 'forum.php?action=viewtopic&id='.$topic;
$num = getcount("forum_topics", "WHERE replyto = '$topic' OR topic_id = '$topic'");
$total_pages = ceil($num/$topics_perpage);
$start = ($total_pages - 1) * $topics_perpage;
if ($start < 0) $start = 0;
$url .= '&start='.$start;

echo '<meta http-equiv="Refresh" content="2;url='.$url.'">';

echo '<table width="100%"><tr><td>';

echo "The post has been edited. You are now being forwarded.<br><br>";

echo '<a href="'.$url.'">Click here, if you do not want to wait longer (or if your Browser does not support automatic forwarding)</a>';

echo "</td></tr></table>";

break; // updatetopic

} // switch

?>
<br>

<table border="1">

        <tr>
        <th><b><?=$MSG_LANG["date"]?></b></th>
        <th><b><?=$MSG_LANG["autor"]?></b></th>
        <th><b><?=$MSG_LANG["forum1"]?> <?=$main_perpage2?> <a href="forum.php"><?=$MSG_LANG["forum2"]?></a> <?=$MSG_LANG["forum3"]?></b></hd>
        </tr>

        <?PHP

         $t122 = mysql_query("SELECT t.*, p.firstName, p.playerID from forum_topics t
                    LEFT JOIN players p ON p.playerID = t.userid
                    ORDER BY time DESC
                    LIMIT ".$main_perpage2);

        echo mysql_error();
        while ($t = mysql_fetch_array($t122)) {

        $title = db_output($t['title']);
        $title = strip_tags($title);
        $text = db_output($t['text'], true);
        $text = strip_tags($text, "<br>");
        $text = bbcode($text);
        $text = forum_smilies($text);
        $date = date("d.m.y, H:i", $t['time']);
        $topic = ($t['replyto'] > 0) ? $t['replyto'] : $t['topic_id'];

        $count = getcount('forum_topics', "WHERE replyto = '".$topic."'");

$lp1 = mysql_query("SELECT t.topic_id, t.userid, p.firstName FROM forum_topics t
                    LEFT JOIN players p ON p.playerID = t.userid
                    WHERE t.replyto = '".$topic."'
                    AND t.time = '".$t['lastreply']."'");
echo mysql_error();
$lp = mysql_fetch_array($lp1);

$total_pages = ceil($count/$topics_perpage);
$start = ($total_pages - 1) * $topics_perpage;
if ($start < 0) $start = 0;
$link = 'forum.php?action=viewtopic&id='.$topic.'&start='.$start.'#'.$lp['topic_id'];
        ?>

        <tr>
        <td><?=$date?></td>
        <td><a href="stats_user.php?cod=<?=$t['playerID']?>"><?=$t['firstName']?></a></td>
        <td><a href="forum.php?action=viewtopic&id=<?=$topic?>"><?=$title?></a>&nbsp;&nbsp;
        <a href="<?=$link?>">>></a>
        </td>
        </tr>

        <?PHP

        } // while

    ?>
</table>

<form name="logout" action="mainmenu.php" method="post">
<input type="hidden" name="ToDo" value="Logout">
</form>

</font>
<br><br>
<hr width=650 align=left>
    <table border=0 style="background:white"><tr><Td>

    </td></tr>
</table>


</font>

</body>
</html>