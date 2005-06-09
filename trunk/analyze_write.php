<?php
##############################################################################################
#                                                                                            #
#                                analyze_write.php
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

    require_once ( 'chessconstants.php');
    require_once('chessdb.php');

        /* include outside functions */
        if (!isset($_CHESSUTILS))
                require_once ( 'chessutils.php');
     #   require_once('gui.php');
     #   require 'move.php';
     #   require 'undo.php';
     #   require_once 'blocks.php';

        fixOldPHPVersions();

        require_once( 'connectdb.php');
        require_once('gui.php');

$act = $_GET['timeOfMove'];
$gameID = $_GET['gameID'];


    /* if this page is accessed directly (ie: without going through login), */
    /* player is logged off by default */
    if (!isset($_SESSION['playerID']))
        $_SESSION['playerID'] = -1;
?>
<html>
<head>
<title>Write Comment</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<BODY bgcolor="#FFFFFF">
<font face=verdana size=2>
<?PHP

if (!$_SESSION['playerID'])
{
    header("Location: mainmenu.php");
         exit;
}

if ($act == 'undefined' || $act == '')
{
    echo "<b>Not Allowed</b>";
         exit;
}
if (isset($_POST['action']))
   $action = $_POST['action'];

switch ($action) {

default:

    if(!in_array($_SESSION['playerID'], $kommentatoren))
         {
            echo "<b>If you are a member of the Chess Maniac Chess Club and would like to annotate games for fellow members. Please contact <a href='http://www.Webmaster/mailform.php' target='_blank'>Site Admin</a><br><br>If you are not a member of the Chess Maniac Chess Club then <a href='http://www.Webmaster/webchess/newuser.php' target='_blank'>register now for your free account!</a></b>";
                 exit;
         }

         if(in_array($gameID, $kommentierte_spiele))
         {
            echo "<b>The Annotation is already finished for this game.</b>";
                 exit;
         }

    $dir = dirname(__FILE__) . "/images/analyze/comments/";

    $dh = @opendir($dir);

         if ($dh)
         {
            while ( ($file = readdir($dh) ) !== false )
                         if ($file != "." && $file != "..")
                         $special[] = $file;
         }

?>
         <script type="text/javascript">
         <!--
         function quickbutton(newtext)
         {
                 //alert(newtext);
                 document.postcomment.quick.value = 'true';
                 document.postcomment.thetext.value = newtext;
                 document.postcomment.submit();
         }
         //-->
         </script>
         <form method="POST" target="analyze_write.php" name="postcomment">
    <input type="hidden" name="action" value="write">
         <input type="hidden" name="gameID" value="<?=$gameID?>">
         <input type="hidden" name="timeOfMove" value="<?=$act?>">
         <input type="hidden" name="quick" value="false">
         <b>Annotation:</b><br>
         <table border="0">
         <?PHP

         if (!$_GET['quick'])
         {
         ?>
         <tr>
         <td>
         <textarea name="thetext" cols="40" rows="20"></textarea></td><td>

         <?PHP
         }

         else
         {

         echo '<input type="hidden" name="thetext">';
         echo '<br>';

         $i = 1;
         $list = '';


         while (list($key, $val) = each($special))
         {
            $style = "background-color: #C0C0C0; border-color: #CCCCCC #333333 #333333 #CCCCCC; background-image: url(images/analyze/comments/".$val."); background-repeat: no-repeat; background-position: center; width=25px; height=25px;";
                 $list .= "<input type=\"submit\" value=\"\"  style=\"".$style."\" onclick=\"quickbutton('".$val."');\">\n";
                 //$list .= "<a href='#' onclick=\"quick('".$val."');\">".$val."</a> ";
                 if (is_integer($i/7))
                    $list .= "<br>\n";
                 $i++;
         }

         echo $list;

         }

         ?>

         </table>
         <?PHP

         if (!$_GET['quick'])
         {
         ?>
         <br>
         <input type="submit"></input>
         <?PHP } ?>
         </form>

<?PHP

break; // default

case 'write':

$playerID = $_SESSION['playerID'];
$gameID = $_POST['gameID'];
$timeOfMove = $_POST['timeOfMove'];
$text = addslashes($_POST['thetext']);
$time = time();
$quick = $_POST['quick'];

//echo $quick; exit;

if ($quick == 'true') $quick = '1';
else $quick = '0';

mysql_query("INSERT INTO comments VALUES(NULL, '$playerID', '$gameID', '$timeOfMove', '$text', '$time', '$quick')");

echo "<b>Annotation has been entered. </b>";

break; // case write

} // switch

?>
</font>
</body>
</html>