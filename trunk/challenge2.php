<?php
##############################################################################################
#                                                                                            #
#                                challenge2.php                                                
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
    if (!isset($_SESSION['playerID']))
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
<?php
function backslash(&$arr, $escape)
{
   $magic_on = get_magic_quotes_gpc();

   if($escape && !$magic_on):
       foreach($arr as $k => $v):
           switch(gettype($v)):
               case 'string' : 
                   $arr[$k] = addslashes($v);
                   break;
               case 'array' :
                   backslash($arr[$k], true);
           endswitch;
       endforeach;
   endif;

   if(!$escape && $magic_on): 
       foreach($arr as $k => $v):
           switch(gettype($v)):
               case 'string' : 
                   $arr[$k] = stripslashes($v);
                   break;
               case 'array' :
                   backslash($arr[$k], false);
           endswitch;
       endforeach;
   endif;        
}

//examples
backslash($_POST, true); // force all $_POST values to be  properly escaped with backslashes backslash($_GET, false); // force all $_GET values to NOT be escaped with backslashes  
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta name="Keywords" content="chess,ajedrez,échecs,echecs,scacchi,schach,check,check mate,jaque,jaque mate,queenalice,queen alice,queen,alice,play,game,games,turn based,correspondence,correspondence chess,online chess,play chess online">
		
		<title>Challenge</title>

<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/mainstyles.css" type="text/css">

	</head>

	<body>
<table width="500">
	<form name="invite" action="challengesent.php" method="post">    
    <input type="hidden" name="ToDo" value="InvitePlayer">
    <input type="hidden" name="opponent" value="">
	<TR></TR><TH colspan=2><?=$MSG_LANG["gamesettings"]?></TH></TR>
	    <TR>
            <TD style="text-align:left" width=40%><?=$MSG_LANG["yourcolor"]?>:</TD>
            <TD style="text-align:left">
			<select name="color">
            <option value="random" SELECTED><?=$MSG_LANG["random"]?></option>
            <option value="white"><?=$MSG_LANG["white"]?></option>
            <option value="black"><?=$MSG_LANG["black"]?></option>
            
			</select></TD></TR>


	    <TR>
            <TD style="text-align:left"><?=$MSG_LANG["isofficial"]?>:</TD>
            <TD style="text-align:left">
			<? if ($newuser ==1 || ($computer == 1 && !$CFG_RANK_COMPUTER) || getRating($_SESSION[playerID]) == 0){?>
                <input type='hidden' name='oficial' value='0'>
                <select name="oficialdisabled" disabled=true>
                <option value="1"><?=$MSG_LANG["yes"]?></option>
                <option value="0" SELECTED><?=$MSG_LANG["no"]?></option>
                </select>
            <?}else{?>
                <select name="oficial">
                <option value="1" SELECTED><?=$MSG_LANG["yes"]?></option>
                <option value="0"><?=$MSG_LANG["no"]?></option>
                </select>
            <?}?>
          </TD></TR>

		<TR>
		<TD style="text-align:left"><?=$MSG_LANG["timeforeach"]?>:</TD>
		<TD style="text-align:left">
  		<select name="timelimit">
		<option value="0" SELECTED><?=$MSG_LANG["unlimited"]?></option>
		
<?
 foreach ($CFG_TIME_ARRAY as $t){
 	if ($t <= 1440){
		if ($t<60)
			echo "<option value='$t'>$t $MSG_LANG[min].</option>\n";
		else{
			$tm = $t/60;
	    	echo "<option value='$t'>$tm $MSG_LANG[hs].</option>\n";
	    }
	}
}
	for ($x=2; $x<$CFG_EXPIREGAME; $x++)
		echo "<option value='".($x*1440)."'>$x $MSG_LANG[days]</option>\n";
	for ($y=1; $y<$CFG_EXPIREGAME; $y++)
	    echo "<option value='".($y)."'>$y days per move</option>\n";
?>
		</select>
		<?=$MSG_LANG["movetimeout"]?>:<?=$CFG_EXPIREGAME?> <?=$MSG_LANG["days"]?>
		
		<TR>
		<TD style="text-align:left"><?=$MSG_LANG["chooseplayertojoin"]?>:</TD>
		<TD style="text-align:left">
		
				<select name="opponent" size="1">
<?	
				 
				
				
				//$tmpQuery="SELECT * FROM players WHERE engine='0' and playerID <> ".$id." AND ativo='1' and rating>0 ORDER BY firstName ASC";

				$tmpQuery="SELECT playerID, nick, firstName FROM players WHERE engine='0' and playerID <> ".$id." AND ativo='1' and rating>0 ORDER BY firstName ASC";
	            $tmpPlayers = mysql_query($tmpQuery);
    					while($tmpPlayer = mysql_fetch_array($tmpPlayers, MYSQL_ASSOC))
                                        {
                                                if ($tmpPlayer['firstName']){
														if($tmpPlayer['playerID']==$_GET['opponent'])
        	                                        			echo("<option value='".$tmpPlayer['playerID']."' selected>".$tmpPlayer['firstName']."</option>\n");
														else
                	                                			echo("<option value='".$tmpPlayer['playerID']."'> ".$tmpPlayer['firstName']."</option>\n");
						}
                                        }
?>
</select>
<?
echo"<input type='button' style='font-size:11' value='$MSG_LANG[invite]' onClick='document.invite.submit();'>";		
//echo"<input style='background-color: #000000;border-color: #c0c0c0;border-width: 1;color: #FFFFFF;font-size: 8pt;font-family: verdana;' type=button onClick='document.challenge.submit()' value='".$MSG_LANG[invite]."'>"."";
//<input type="submit" name="newMessage" style="cursor:hand" value="Send&nbsp;Message" border="0"> 
				//<input type="button" name="btnCancel" value="Cancel" style="cursor:hand" border="0" onClick="javascript:window.close();">
?>
</form>		
</TD></TR>
</table>
			
		</div>
        <table width="100%" border="1">
  <th height="16">Notice</th>
  <tr>
    <td> Time controls are not days per move. If you wish to play days per move
      you must challenge using the 14 Days Per Move default. All other time
      limits work like real over the board chess.
      Each player is assigned and equal amount of time. I.e. if you choose 5
      days as the time for each player your clocks will count down until you
      have reached 5 days. This means that a game can last no longer then 10
      days. However, be advised that games like this will require you to login
      frequently to check on the status of your games. Most people on this site
      play the unlimited time limit with a 14 day per move time out. In the future
      I hope to add more days per move options. Thanks and have fun.</td>
  </tr>
        </table>
        <p>&nbsp;</p>
        <br>

</body>

</html>
