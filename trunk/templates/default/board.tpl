<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <title>WebChess Login Page- Select a language to change language
        </title>
            <LINK rel="stylesheet" href="templates/{$template_set}/styles.css" type="text/css">
{if $stop_timer eq 0}
 <META HTTP-EQUIV=Refresh CONTENT="{$chessboard_refresh_rate}; URL=chess.php">
{/if}
<!-- we can maybe switch whether these are loaded or not, depending on if they are needed.. -->
 <script type="text/javascript" src="javascript/chessutils.js">
/* these are utility functions used by other functions */
 </script>

<script type="text/javascript" src="javascript/commands.js">
// these functions interact with the server
</script>

<script type="text/javascript" src="javascript/validation.js">
// these functions are used to test the validity of moves
</script>

<script type="text/javascript" src="javascript/isCheckMate.js">
// these functions are used to test the validity of moves
</script>

<script type="text/javascript" src="javascript/squareclicked.js">
// this is the main function that interacts with the user everytime they click on a square
</script>
 <!-- $Id$ -->
</head>
<BODY bgcolor=white text=black marginheight=10 marginwidth=10 topmargin=10 leftmargin=10>

<table border="0" width=100% align=left>

<tr>
    <td align=center>

 <!-- BOOKMARK POINT -->


<!-- CONVERT TO SITE SPECIFIC LOGO, PUT IN CONFIG FILE -->

<font face="Verdana">
    <img src="images/compwebchess.gif" alt="compwebchess_logo">
</font>
</center>



<!-- The below should go into FOOTER.PHP -->
<p align=center>
    <font face=verdana size=1>
    Copyright &copy; 2004-2005
        <a href="http://sourceforge.net/projects/compwebchess/" target="_blank">CompWebChess Project
        </a>
            <?=$VERSION?>,
        <a href="credits.php">Comp WebChess Development Team</a>
    </font>
</p>
<p align="center">
    <font face="Verdana" size="1" color="#AAAAAA">
        CompWebChess is based on GNU
            <a href="http://webchess.sourceforge.net/">WebChess 0.8.3</a> and is published under the GNU GPL (GNU General Public License).
<BR>
        To read the full license, go to http://www.opensource.org/licenses/gpl-license.html. Basically, the GPL says you are free
<BR>
        to distribute and edit the sourcecode, as long as you keep the rightful credits intact. Please read the full documents if
<BR>
        you have any questions.
<BR>
    </font>
</p>
<br>
<br>
</body>
</html>