<?php
##############################################################################################
#                                                                                            #
#                                messages.php
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


    if (isset($proof))
    {
        if (!get_magic_quotes_gpc())
        {
        $proofed = addslashes($proof);
        }
         else
         {
        $proofed = $proof;
        }
    return $proofed;
    }

function proof_strip($proof)
{
    if (isset($proof))
    {
        $proofed = stripslashes($proof);
    return $proofed;
    }
return $proof;
}
//////////////////////////////////// BBCODE Functions //////////////////////////

class myBoardCodeTag
{
  var $str_search;
  var $str_replace;
    var $casesensitiv;

  function myBoardCodeTag ($search, $replace, $casesensitiv = false)
  {
    $this->str_search     = $search;
    $this->str_replace    = $replace;
    $this->casesensitiv   = $casesensitiv;
  }
}

  $myBoardCodeTag_bold             = new myBoardCodeTag("(\[b\])(.*)(\[/b\])", "<b>\\2</b>");
  $myBoardCodeTag_italic           = new myBoardCodeTag("(\[i\])(.*)(\[/i\])", "<i>\\2</i>");
  $myBoardCodeTag_underline        = new myBoardCodeTag("(\[u\])(.*)(\[/u\])", "<u>\\2</u>");
  $myBoardCodeTag_strike           = new myBoardCodeTag("(\[s\])(.*)(\[/s\])", "<s>\\2</s>");

  $myBoardCodeTag_url1             = new myBoardCodeTag("(\[url\])(.*)(\[/url\])", "<a href=\"\\2\" target=\"_blank\">\\2</a>");
  $myBoardCodeTag_url2             = new myBoardCodeTag("(\[url\=)(.*)(\])(.*)(\[/url\])", "<a href=\"\\2\" target=\"_blank\">\\4</a>");

  $myBoardCodeTag_email1           = new myBoardCodeTag("(\[email\])(.*)(\[/email\])", "<a href=\"mailto:\\2\">\\2</a>");
  $myBoardCodeTag_email2           = new myBoardCodeTag("(\[email\=)(.*)(\])(.*)(\[/email\])", "<a href=\"mailto:\\2\">\\4</a>");

  $myBoardCodeTag_code             = new myBoardCodeTag("(\[code\])(.*)(\[/code\])", "<blockquote>Quellcode:<hr><pre>\\2</pre><hr></blockquote>");
  $myBoardCodeTag_quote            = new myBoardCodeTag("(\[quote\])(.*)(\[/quote\])", "<blockquote>Quote:<hr>\\2<hr></blockquote>");

  $myBoardCodeTag_list             = new myBoardCodeTag("(\[list\])(.*)(\[/list\])", "<ul>\\2</ul>");
  $myBoardCodeTag_ul_ol            = new myBoardCodeTag("(\[list\=)(ol|ul)(\])(.*)(\[/list\])", "<\\2>\\4</\\2>");
  $myBoardCodeTag_li               = new myBoardCodeTag("(\[\*\])(.*)", "<li>\\2</li>");

  $myBoardCodeTag_hr               = new myBoardCodeTag("(\[hr\])", "<hr>");

  $myBoardCodeTag_img              = new myBoardCodeTag("(\[img\=)(.*)(\])", "<img src=\"\\2\" border=\"0\">");
  $myBoardCodeTag_icq              = new myBoardCodeTag("(\[icq\=)(.*)(\])(.*)(\[/icq\])", "<a href=\"http://wwp.icq.com/scripts/Search.dll?to=\\2\" target=\"_blank\">\\4</a>");

  $myBoardCodeTags               = array (
                                    $myBoardCodeTag_bold,
                                    $myBoardCodeTag_italic,
                                    $myBoardCodeTag_underline,
                                    $myBoardCodeTag_strike,
                                    $myBoardCodeTag_url1,
                                    $myBoardCodeTag_url2,
                                    $myBoardCodeTag_email1,
                                    $myBoardCodeTag_email2,
                                    $myBoardCodeTag_code,
                                    $myBoardCodeTag_quote,
                                    $myBoardCodeTag_list,
                                    $myBoardCodeTag_ul_ol,
                                    $myBoardCodeTag_li,
                                    $myBoardCodeTag_hr,
                                    $myBoardCodeTag_img,
                                    $myBoardCodeTag_icq
                                   );

function Filter_myBoardCodeTags ($text, $myBoardCodeTags, $drophtmltags = false)
{

  if (isset ($text))
  {

    $s = $text;


    //$s = htmlspecialchars ($s);
    //$s = htmlentities ($s);

        if ($drophtmltags)
        {
            $s = strip_tags ($s);
        }


    for ($i = 0; $i < Count ($myBoardCodeTags); $i++)
    {
            $pattern = "=" . $myBoardCodeTags[$i]->str_search . "=sU";
            if (!$myBoardCodeTags[$i]->casesensitiv)
            {
            $pattern .= "i";
            }
      $s = preg_replace ($pattern, $myBoardCodeTags[$i]->str_replace, $s);
    }

    $s = nl2br ($s);
    $s = proof_strip ($s);

    return $s;

  }

  return $text;
}

////////////////////////////// END BBCODE FUNCTIONS ////////////////////////////



    require_once ( 'chessutils.php');

    require_once('chessdb.php');


    require_once("languages/".$_SESSION['pref_language']."/strings.inc.php");

    /* set default playing mode to different PCs (as opposed to both players sharing a PC) */
    $_SESSION['isSharedPC'] = false;

$id=$_SESSION['player_id'];
$ssid=session_id();
$ssname=session_name();

if($_GET['newsack'])
{
   $tmpsql = "DELETE FROM news WHERE idNews={$_GET['newsack']} and ".$_SESSION['isAdmin']."=1";
    mysql_query($tmpsql) or die("Can't Acknowledge Message: $tmpsql");
}


if($_GET['ack'])
{

   $tmpQuery = "UPDATE communication set ack=1 where commID={$_GET['ack']} and (toID=$id or ".$_SESSION['isAdmin']."=1)";
   mysql_query($tmpQuery) or die("Can't Acknowledge Message: $tmpQuery");

}


if($_POST['newNews'])

{
        $OKForMessage=true;

                if ($_SESSION['isAdmin'])
                $OKForMessage=true;

        if($OKForMessage){

        $nwstitle=proof_add($_POST['newsTitle']);
        $nwstext=proof_add($_POST['newsMessage']);

    $tmpsql = "INSERT INTO news (idNews,title,date,description,language)";
            $tmpsql .= "VALUES ('' , '$nwstitle' , NOW(), '$nwstext', 'english');";
    mysql_query($tmpsql);
    header("Location:messages.php");
}
}

if($_POST['newMessage'])
{
        $fromPerson=($_POST['from'])?$_POST['from']:"NULL";
        $toPerson=($_POST['to'])?$_POST['from']:"NULL";

        $OKForMessage=true;

        if (($fromPerson==NULL)||($toPerson==NULL))
        {
                $OKForMessage=false;

                if ($_SESSION['isAdmin'])
                $OKForMessage=true;
        }

        if($OKForMessage){

        $msgtitle=proof_add($_POST['txtTitle']);

        $msgtext=proof_add($_POST['txtMessage']);
        $msgtype="0";

        $msql = "INSERT INTO communication (fromID,toID,title,text,postDate,expireDate,ack,commType) ";
        $msql .= "VALUES ( $fromPerson , $toPerson, '$msgtitle', '$msgtext', NOW() , NULL , '0', '$msgtype' );";
        mysql_query($msql);
        header("Location:messages.php");

}
}

if($_POST['newLogin'])
{
        $fromPerson=($_POST['from'])?$_POST['from']:"NULL";
        $toPerson=($_POST['to'])?$_POST['from']:"NULL";

        $OKForMessage=true;

        if (($fromPerson==NULL)||($toPerson==NULL))
        {
                $OKForMessage=false;

                if ($_SESSION['isAdmin'])
                $OKForMessage=true;
        }

        if($OKForMessage){
        $lsgtitle=proof_add($_POST['txtLoginTitle']);
        $lsgtext=proof_add($_POST['txtLogin']);
        $lsgtype="3";

        $lsql = "INSERT INTO communication (fromID,toID,title,text,postDate,expireDate,ack,commType) ";
        $lsql .= "VALUES ( $fromPerson , $toPerson, '$lsgtitle', '$lsgtext', NOW() , NULL , '0', '$lsgtype' );";
        mysql_query($lsql);
        header("Location:messages.php");

}
}

if($_POST['newTournament'])
{
        $fromPerson=($_POST['from'])?$_POST['from']:"NULL";
        $toPerson=($_POST['to'])?$_POST['from']:"NULL";

        $OKForMessage=true;

        if (($fromPerson==NULL)||($toPerson==NULL))
        {
                $OKForMessage=false;

                if ($_SESSION['isAdmin'])
                $OKForMessage=true;
        }

        if($OKForMessage){
        $tsgtitle=proof_add($_POST['txtTournTitle']);
        $tsgtext=proof_add($_POST['txtTournament']);
        $tsgtype="2";

        $tsql = "INSERT INTO communication (fromID,toID,title,text,postDate,expireDate,ack,commType) ";
        $tsql .= "VALUES ( $fromPerson , $toPerson, '$tsgtitle', '$tsgtext', NOW() , NULL , '0', '$tsgtype' );";
        mysql_query($tsql);
        header("Location:messages.php");

}
}

if($_POST['newTeam'])
{
        $fromPerson=($_POST['from'])?$_POST['from']:"NULL";
        $toPerson=($_POST['to'])?$_POST['from']:"NULL";

        $OKForMessage=true;

        if (($fromPerson==NULL)||($toPerson==NULL))
        {
                $OKForMessage=false;
                if ($_SESSION['isAdmin'])
                $OKForMessage=true;
        }

        if($OKForMessage){
        $tmsgtitle=proof_add($_POST['txtTeamTitle']);
        $tmsgtext=proof_add($_POST['txtTeam']);
        $tmsgtype="4";

        $tmsql = "INSERT INTO communication (fromID,toID,title,text,postDate,expireDate,ack,commType) ";
        $tmsql .= "VALUES ( $fromPerson , $toPerson, '$tmsgtitle', '$tmsgtext', NOW() , NULL , '0', '$tmsgtype' );";
        mysql_query($tmsql);
        header("Location:messages.php");

}
}

?>
<HTML>
<HEAD>
<title>[SMARTY TITLE]</title>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["theme_set"]?>/styles.css" type="text/css">


<meta HTTP-EQUIV='Pragma' CONTENT='no-cache'>


</head>

<body bgcolor=white text=black>


<table width="100%" border="1">
  <tr>
    <th><div align="center"><strong>Our Sponsors</strong></div></th>
  </tr>
  <tr>
    <td><div align="center">

    </div></td>
  </tr>
</table>
<br>

<?
echo "<table style='width:100%'><tr><th colspan='2'>".$MSG_LANG["pm"]."".$_SESSION['username']."</th></tr><tr><td><a href=\"javascript:MessagePlayer({$row['player_id']})\"><br></a>&nbsp;&nbsp;<a href=\"javascript:MessagePlayer({$row['player_id']})\"><b>".$MSG_LANG["sendpm"]."</a>&nbsp;&nbsp;<a href=\"javascript:MessagePlayer({$row['player_id']})\"></b></a><br><br><table style='border:0; background-color:FFFFFF; width:100%'><tr><td 'style='width:20'>&nbsp;</td><td style='text-align:left'>
</td></tr></table></td></tr></table>";


$sql="SELECT * FROM communication left join players on communication.fromID=players.playerID where ((toID is null) or (toID=$id)) and ((fromID is null) or (fromID=playerID)) and ack=0 and gameID is null order by communication.commType, communication.commID2 desc;";
$qmessages=mysql_query($sql) or die("Can't Execute SQL:$sql");

while($msg = mysql_fetch_assoc($qmessages))
{

    if($msg['commType'] == 0){
        // $tmpQuery = "UPDATE communication set listed=1 where commID={$msg['commID']}";
        // mysql_query($tmpQuery) or die("Can't update Message: $tmpQuery");

    echo "<table style='width:100%; border:0; background-color:FFFFFF'><tr><td><br><b>".proof_strip($msg['title'])."</b><br><br>".proof_strip($msg['text'])."</td></tr></table>";
    }


    echo "<table style='width:100%'>";

        if ($msg['fromID']<>Null)
        {
            echo "<tr><td style='background-color:E5F2FF' colspan='2'><b>From: ".$msg['firstName']." ".$msg['lastName']."</b>"."&nbsp;&nbsp;Date: ".$msg['postDate']."&nbsp;&nbsp;&nbsp;<a href='stats_user.php?cod=".$msg['fromID']."'>".$msg['firstName']." Home Page</a></td></tr><tr><td colspan='2'style='text-align:left'>"."<b> ".$MSG_LANG["title"]." ".proof_strip($msg['title'])."</b></td></tr><tr><td style='text-align:left; width:620'><h5>".Filter_myBoardCodeTags(proof_strip($msg['text']), $myBoardCodeTags)."</h5></td><td style='width:130'><a href=\"javascript:ReplyToMessage(".$msg['commID'].",".$msg['fromID'].",'".proof_strip($msg['title'])."')\">Reply</a>&nbsp;|&nbsp;<a href=\"?ack={$msg['commID']}\">Delete</a></td></tr><br>";
            $tmpQuery = "UPDATE communication set listed=1 where commID={$msg['commID']}";
            mysql_query($tmpQuery) or die("Can't update Message: $tmpQuery");
        }
    echo "</table>";

}

If($_SESSION['isAdmin']=="1"){
?>
<br>
<hr width='100%' size='6' align='left' color='800000'><br><center>
<span style='color:1F5070; text-size:11'><b><? echo "".$MSG_LANG["commadmin"].""; ?></b></span></center>

<form name="frmNewsMessage" method="post" action="messages.php">
<table style="width:100%; border:0; background-color:FFFFFF">
<tr><td><b><font color="1F5070"><? echo "".$MSG_LANG["postnewsitem"].""; ?></font></b></td></tr><tr><td><? echo "".$MSG_LANG["title"].""; ?> <input type="text" name="newsTitle" size="60"></td></tr>
<tr><td><textarea name="newsMessage" cols="60" rows="5">
</textarea>
<br>
<input type=submit name="newNews" value="<?=$MSG_LANG["postnewsitem"];?>">
</td>
</tr>
</table><br>
</form>
<?
}

If($_SESSION['isAdmin']=="1"){
?>
<form name=frmNewMessage method=post action="messages.php">
<table style="width:100%; border:0; background-color:FFFFFF">
<tr><td><b><font color="1F5070"><? echo "".$MSG_LANG["postadminmessage"].""; ?></font></b></td></tr><tr><td><? echo "".$MSG_LANG["title"].""; ?> <input type="text" name="txtTitle" size="60"></td></tr>
<tr><td><textarea name="txtMessage" cols="60" rows="5">
</textarea><br>
  <input type=submit name="newMessage" value="<?=$MSG_LANG["postadminmessage"];?>">
</td>
</tr>
</table>
</form>
<?
}

If($_SESSION['isAdmin']=="1"){
?>
<form name=frmLoginMsg method=post action="messages.php">
<table style="width:100%; border:0; background-color:FFFFFF">
<tr><td><b><font color="1F5070"><? echo "".$MSG_LANG["postloginadminmessage"].""; ?></font></b></td></tr><tr><td><? echo "".$MSG_LANG["title"].""; ?> <input type="text" name="txtLoginTitle" size="60"></td></tr>
<tr><td><textarea name="txtLogin" cols="60" rows="5">
</textarea><br>
    <input type=submit name="newLogin" value="<?=$MSG_LANG["postloginadminmessage"];?>">
</td>
</tr>
</table><br><br>
</form>
<?
}

If($_SESSION['isAdmin']=="1"){
?>
<form name=frmTournamentMsg method=post action="messages.php">
<table style="width:100%; border:0; background-color:FFFFFF">
<tr><td><b><font color="1F5070"><? echo "".$MSG_LANG["posttournamentsadminmessage"].""; ?></font></b></td></tr><tr><td><? echo "".$MSG_LANG["title"].""; ?> <input type="text" name="txtTournTitle" size="60"></td></tr>
<tr><td><textarea name="txtTournament" cols="60" rows="5">
</textarea><br>
    <input type=submit name="newTournament" value="<?=$MSG_LANG["posttournamentsadminmessage"];?>">
</td>
</tr>
</table><br><br>
</form>
<?
}

If($_SESSION['isAdmin']=="1"){
?>
<form name=frmTeamsMsg method=post action="messages.php">
<table style="width:100%; border:0; background-color:FFFFFF">
<tr><td><b><font color="1F5070"><? echo "".$MSG_LANG["postteamsadminmessage"].""; ?></font></b></td></tr><tr><td><? echo "".$MSG_LANG["title"].""; ?> <input type="text" name="txtTeamTitle" size="60"></td></tr>
<tr><td><textarea name="txtTeam" cols="60" rows="5">
</textarea><br>
  <input type=submit name="newTeam" value="<?=$MSG_LANG["postteamsadminmessage"];?>">
</td>
</tr>
</table><br><br>
</form>
<?
}

If($_SESSION['isAdmin']=="1"){

echo "<table><tr><th colspan='4' style='background: url(); background-color:E5F2FF'><font color='800000'>Delete News Items</font></th></tr><tr>
<th style='background: url(); background-color:FFFFFF; width:10%'>idNews</th>
<th style='background: url(); background-color:FFFFFF; width:20%'>Title</th>
<th style='background: url(); background-color:FFFFFF; width:60%'>Message</th>
<th style='background: url(); background-color:FFFFFF; width:10%'>Delete</th></tr>";

        $tmpsql="SELECT * FROM news ORDER BY idNews ASC";
                  $tmpNewsItem = mysql_query($tmpsql) or die("Sorry: $tmpsql");
                           while($tmpNitem = mysql_fetch_array($tmpNewsItem, MYSQL_ASSOC))
                                        {
echo "<table>";

echo "<tr><td style='width:10%'>".$tmpNitem['idNews']."</td><td style='width:20%'>".proof_strip($tmpNitem['title'])."</td><td style='width:60%'>".proof_strip($tmpNitem['description'])."<br></td><td style='width:10%'><a href=\"?newsack={$tmpNitem['idNews']}\"><font color='800000'><b>Delete</b></font></a></td></tr>";

}
echo "</table><br>";
}

If($_SESSION['isAdmin']=="1"){

echo "<table><tr><th colspan='4' style='background: url(); background-color:E5F2FF'><font color='800000'>Delete Admin Messages</font></th></tr><tr>
<th style='background: url(); background-color:FFFFFF; width:10%'>commID</th>
<th style='background: url(); background-color:FFFFFF; width:20%'>Title</th>
<th style='background: url(); background-color:FFFFFF; width:60%'>Message</th>
<th style='background: url(); background-color:FFFFFF; width:10%'>Delete</th></tr>";

        $tmpQuery="SELECT * FROM communication WHERE commType = 0 AND ack = 0 ORDER BY commID ASC";
                  $tmpMessage = mysql_query($tmpQuery) or die("Sorry: $tmpQuery");
                           while($tmpMsg = mysql_fetch_array($tmpMessage, MYSQL_ASSOC))
                                        {
echo "<table>";

echo "<tr><td style='width:10%'>".$tmpMsg['commID']."</td><td style='width:20%'>".proof_strip($tmpMsg['title'])."</td><td style='width:60%'>".proof_strip($tmpMsg['text'])."<br></td><td style='width:10%'><a href=\"?ack={$tmpMsg['commID']}\"><font color='800000'><b>Delete</b></font></a></td></tr>";

}
echo "</table><br>";
}

If($_SESSION['isAdmin']=="1"){

echo "<table><tr><th colspan='4' style='background: url(); background-color:E5F2FF'><font color='800000'>Delete Login Messages</font></th></tr><tr>
<th style='background: url(); background-color:FFFFFF; width:10%'>commID</th>
<th style='background: url(); background-color:FFFFFF; width:20%'>Title</th>
<th style='background: url(); background-color:FFFFFF; width:60%'>Message</th>
<th style='background: url(); background-color:FFFFFF; width:10%'>Delete</th></tr>";

        $tmpLogin="SELECT * FROM communication WHERE commType = 3 AND ack = 0 ORDER BY commID ASC";
                  $tmpLg = mysql_query($tmpLogin) or die("Sorry: $tmpLogin");
                           while($tmpLog = mysql_fetch_array($tmpLg, MYSQL_ASSOC))
                                        {

echo "<tr><td>".$tmpLog['commID']."</td><td>".proof_strip($tmpLog['title'])."</td><td>".proof_strip($tmpLog['text'])."<br></td><td><a href=\"?ack={$tmpLog['commID']}\"><font color='800000'><b>Delete</b></font></a></td></tr>";

}
echo "</table><br>";
}

If($_SESSION['isAdmin']=="1"){

echo "<table><tr><th colspan='4' style='background: url(); background-color:E5F2FF'><font color='800000'>Delete Tournament Messages</font></th></tr><tr>
<th style='background: url(); background-color:FFFFFF; width:10%'>commID</th>
<th style='background: url(); background-color:FFFFFF; width:20%'>Title</th>
<th style='background: url(); background-color:FFFFFF; width:60%'>Message</th>
<th style='background: url(); background-color:FFFFFF; width:10%'>Delete</th></tr>";

        $tmpTourn="SELECT * FROM communication WHERE commType = 2 AND ack = 0 ORDER BY commID ASC";
                  $tmpTourney = mysql_query($tmpTourn) or die("Sorry: $tmpTourn");
                           while($tmpTmt = mysql_fetch_array($tmpTourney, MYSQL_ASSOC))
                                        {

echo "<tr><td>".$tmpTmt['commID']."</td><td>".proof_strip($tmpTmt['title'])."</td><td>".proof_strip($tmpTmt['text'])."<br></td><td><a href=\"?ack={$tmpTmt['commID']}\"><font color='800000'><b>Delete</b></font></a></td></tr>";

}
echo "</table><br>";
}

If($_SESSION['isAdmin']=="1"){

echo "<table><tr><th colspan='4' style='background: url(); background-color:E5F2FF'><font color='800000'>Delete Team Messages</font></th></tr><tr>
<th style='background: url(); background-color:FFFFFF; width:10%'>commID</th>
<th style='background: url(); background-color:FFFFFF; width:20%'>Title</th>
<th style='background: url(); background-color:FFFFFF; width:60%'>Message</th>
<th style='background: url(); background-color:FFFFFF; width:10%'>Delete</th></tr>";

        $tmpTeam="SELECT * FROM communication WHERE commType = 4 AND ack = 0 ORDER BY commID ASC";
                  $tmpTeamMsg = mysql_query($tmpTeam) or die("Sorry: $tmpTeam");
                           while($tmpTt = mysql_fetch_array($tmpTeamMsg, MYSQL_ASSOC))
                                        {

echo "<tr><td>".$tmpTt['commID']."</td><td>".proof_strip($tmpTt['title'])."</td><td>".proof_strip($tmpTt['text'])."<br></td><td><a href=\"?ack={$tmpTt['commID']}\"><font color='800000'><b>Delete</b></font></a></td></tr>";

}

echo "</table><br><hr width='100%' size='6' align='left' color='800000'>";

}

?>

<br><span style="font-size:8pt; color:000080">&nbsp;&nbsp;&nbsp; MOD 1 © PapaHat Productions ----</span><br>
<br>

<font face=verdana size=1><br><br><br><br>&nbsp;&nbsp;&nbsp;
<table border=0 style="background:white"><tr><Td>

    </td></tr>
</table>
</font>

<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>
</body>
</html>