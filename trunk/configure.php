<?php
##############################################################################################
#                                                                                            #
#                                configure.php
# *                            -------------------                                           #
# *   begin                : Wednesday, January 25, 2005
# *   copyright            : (C) 2004-2005  FreeChess Development Team                       #
# *   support              : http://developer.berlios.de/projects/chess/                     #
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



    require_once ( 'chessutils.php');

    require_once ( 'newgame.php');
    require_once('chessdb.php');
    require_once('gui.php');

    include_once("languages/".$_SESSION['pref_language']."/strings.inc.php");



?>

<html>
<head>
    <title>[SMARTY TITLE]</title>



<LINK rel="stylesheet" href="themes/<?=$_SESSION["theme_set"]?>/styles.css" type="text/css">
</head>

<body bgcolor=white text=black>
<font face=verdana size=2>



    <form name="PersonalInfo" action="configure.php" method="post">
    <table border="1" width="100%">
        <tr>
            <th colspan="2"><?=$MSG_LANG["personalinformation"]?>&nbsp;<? echo($_SESSION['username']); ?></th>
        </tr>


        <tr>
            <td width="200">
                <?=$MSG_LANG["name"]?>:
            </td>

            <td>
   <input name="txtFirstName" type="hidden" size="30" value="<?= $_SESSION['username'] ?>">
                                       <?= $_SESSION['username'] ?>
                                        </td>
        </tr>

        <tr>
            <td width="200">
                <?=$MSG_LANG["localization"]?>:
            </td>

            <td>
                <input name="txtLocalization" type="text" size="30" value="<? echo $_SESSION['pref_language']; ?>">
            </td>
        </tr>
        <tr>
         <td width="140"> Bio:</td>

         <td style="text-align:center">
         <textarea name="txtBio" cols="30" rows="4"></textarea>
         </td>
      </tr>
        <tr>
                        <td width="200">
                                <?=$MSG_LANG["country"]?>:
                        </td>

                        <td>

            <select name="txtcountry">
            <option value="Citizen of Earth" >Citizen of Earth
            <option value="USA">USA
            <option value="France">France

            <option value="Other">Other
            </select>
                        </td>
                </tr>


        <tr>
            <td>
                <?=$MSG_LANG["oldpassword"]?>:
            </td>

            <td>
                <input name="pwdOldPassword" size="30" type="password">
            </td>
        </tr>

        <tr>
            <td>
                <?=$MSG_LANG["newpassword"]?>:
            </td>

            <td>
                <input name="pwdPassword" size="30" type="password">
            </td>
        </tr>

        <tr>
            <td>
                <?=$MSG_LANG["confirmpassword"]?>:
            </td>

            <td>
                <input name="pwdPassword2" size="30" type="password">
            </td>
        </tr>

        <tr>
            <td colspan="2" align="center">
                <input type="button" value="<?=$MSG_LANG["changeinformations"]?>" onClick="submit()">
            </td>
        </tr>
    </table>

    <table border="1" width="100%">
        <tr>
            <th>Avatar</th>
        </tr>
        <tr>
            <td align="center">
                         <table width="100%" border="1" align="left">
                           <tr>
                             <td width="46%"><strong><u>Avatar Image Rules<br>
                             </u></strong>1) No pornographic images allowed.
                             Keep it PG.<br>
2) No hate images allowed.<br>
3.) No offensive material allowed.<br>
<strong><u>Violators<br>
</u></strong>1) No warning will be issued. If you break this rule your avatar
privileges will be revoked. </td>
                             <td width="100%"><?PHP show_avatar($_SESSION['playerID']); ?>
                               <br>
                               <br>
                               <input name="button" type="button" style="cursor: hand;" onClick='window.open("avatar.php?action=edit", "avatar", "toolbar=no,scrollbars=yes,resizable=no,width=700,height=465");' value="<?=$MSG_LANG["avataradd"]?>"></td>
                           </tr>
              </table>
          </td>
      </tr>
      </table>

    <input type="hidden" name="ToDo" value="UpdatePersonalInfo">
    </form>

    <form name="preferences" action="configure.php" method="post">
    <table width="100%" border="1">
        <tr>
            <th colspan="2"><?=$MSG_LANG["currentpreferences"]?></th>
        </tr>

        <tr>
            <td><?=$MSG_LANG["history"]?>:</td>
            <td>
                <?
                    if ($_SESSION['pref_history'] == 'pgn')
                    {
                ?>
                        <input name='rdoHistory' type='radio' value='pgn' checked> PGN
                        <input name="rdoHistory" type="radio" value="verbose"> Verbose
                      <input name="rdoHistory" type="radio" value="graphic"> PGN Extended
                <?
                    }
                    elseif ($_SESSION['pref_history'] == 'verbose')
                    {
                ?>
                        <input name='rdoHistory' type='radio' value='pgn'> PGN
                        <input name="rdoHistory" type="radio" value="verbose" checked> Verbose
                       <input name="rdoHistory" type="radio" value="graphic"> PGN Extended
                <?    }
                                        else
                    {
                ?>
                        <input name='rdoHistory' type='radio' value='pgn'> PGN
                        <input name="rdoHistory" type="radio" value="verbose"> Verbose
                       <input name="rdoHistory" type="radio" value="graphic" checked> PGN Extended
                <?    }
                ?>
            </td>
        </tr>
<tr>
         <td>Highlight last move:</td>
         <td>
            <?
              $p = mysql_query("select showHL from players where playerID = '".$_SESSION['playerID']."'");
               $row = mysql_fetch_array($p);
               $showHLight = $row[0];
               if ($showHLight == '1')
               {
            ?>
                  <input name='rdoHighlight' type='radio' value='1' checked> Yes
                  <input name="rdoHighlight" type="radio" value='0'> No
            <?
               }
               else
               {
            ?>
                  <input name='rdoHighlight' type='radio' value='1'> Yes
                  <input name="rdoHighlight" type="radio" value='0' checked> No
            <?   }
            $s1="";
            $s2="";
            $s3="";
            $s4="";
            $s5="";
            $s6="";
            $s7="";
            $s8="";
            $s9="";
            $p = mysql_query("select showHLcolor from players where playerID = '".$_SESSION['playerID']."'");
               $row = mysql_fetch_array($p);
               $showHLcolor = $row[0];
               if ($showHLcolor == '#FFFF00') $s1="SELECTED";
               if ($showHLcolor == '#FF0000') $s2="SELECTED";
               if ($showHLcolor == '#00FF00') $s3="SELECTED";
               if ($showHLcolor == '#0000FF') $s4="SELECTED";
               if ($showHLcolor == '#6699FF') $s5="SELECTED";
               if ($showHLcolor == '#00FFFF') $s6="SELECTED";
               if ($showHLcolor == '#FF00FF') $s7="SELECTED";
               if ($showHLcolor == '#FAEBD7') $s8="SELECTED";
               if ($showHLcolor == '#A52A2A') $s9="SELECTED";
            ?>
            <select name="rdoHLColor">
            <option value="#FFFF00" <?=$s1?>>YELLOW</option>
            <option value="#FF0000" <?=$s2?>>RED</option>
            <option value="#00FF00" <?=$s3?>>GREEN</option>
            <option value="#0000FF" <?=$s4?>>BLUE</option>
            <option value="#6699FF" <?=$s5?>>CYAN</option>
            <option value="#00FFFF" <?=$s6?>>LIGHT BLUE</option>
            <option value="#FF00FF" <?=$s7?>>PURPLE</option>
            <option value="#FAEBD7" <?=$s8?>>PINK</option>
            <option value="#A52A2A" <?=$s9?>>DARK RED</option>
            </select>
         </td>
      </tr>


<!-- <tr>
  <td>Show My Online Status:</td>
  <td><?
                    if ($_SESSION['pref_confirmmove'] == 'on')
                    {
                ?>
    <input name='rdoOnlineStatus' type='radio' value='on' checked>
YES
<input name="rdoOnlineStatus" type="radio" value="off">
NO<?
                    }
                    else
                    {
                ?>
<input name='rdoOnlineStatus' type='radio' value='on'>
YES
<input name="rdoOnlineStatus" type="radio" value="off" checked>
NO<?    }
                ?>
</td>
</tr> -->
<tr>
          <td>Sound:</td>
          <td><?
                    if ($_SESSION['pref_sound'] == 'on')
                    {
                ?>
            <input name='rdoSound' type='radio' value='on' checked>
ON
<input name="rdoSound" type="radio" value="off">
OFF
<?
                    }
                    else
                    {
                ?>
 <input name='rdoSound' type='radio' value='on'>
ON
<input name="rdoSound" type="radio" value="off" checked>
OFF
<?    }
                ?>
</td>
      </tr>
        <tr>
          <td><?=$MSG_LANG["confirmmove"]?>:</td>
          <td><input name='rdoConfirmmove' type='radio' value='on' checked>
ON
<input name="rdoConfirmmove" type="radio" value="off">
OFF

</td>
      </tr>

        <tr>
            <td><?=$MSG_LANG["pieceset"]?>:</td>
            <td>
            <select name="rdoTheme">
            <?
            if ($handle = opendir('images/pieces')) {
                while (false !== ($file = readdir($handle))) {
                    if (is_dir("images/pieces/$file") && strpos($file,"CVS") === FALSE && $file != "." && $file != "..") {
                              $pthemes[] = $file;
                    }
                }
            closedir($handle);
            }
            sort($pthemes);

            foreach($pthemes as $t){
                if (strtolower($_SESSION['pref_theme']) == strtolower($t))
                    $s = "SELECTED";
                    else $s="";
                echo "<option value='".strtolower($t)."' $s>".ucwords(strtolower($t))."</option>";
            }
            ?>
            </select>
            </td>
        </tr>

        <tr>
            <td><?=$MSG_LANG["theme"]?>:</td>
            <td>
            <select name="colortheme">
            <?
            if ($handle = opendir('themes')) {
                while (false !== ($file = readdir($handle))) {
                    if (is_dir("themes/$file") && strpos($file,"CVS") === FALSE && $file != "." && $file != "..") {
                          $themes[] = $file;
                    }
                }
            closedir($handle);
            }
            sort($themes);

            foreach($themes as $t){
                if ($_SESSION['pref_colortheme'] == $t)
                    $s = "SELECTED";
                    else $s="";
                echo "<option value='$t' $s>$t</option>";
            }
            ?>
            </select>
            </td>
        </tr>


        <tr>
            <td><?=$MSG_LANG['refresh']?>:</td>
            <td><input type="text" name="txtReload" size="4" value="<?=$_SESSION['pref_autoreload']?>"> (min: <?=$auto_reload_min?> secs)</td>
        </tr>

        <tr>
            <td><?=$MSG_LANG["language"]?>:</td>
            <td>
            <select name="language">
            <?
            if ($handle = opendir('languages')) {
                while (false !== ($file = readdir($handle))) {
                    if (strpos($file,"CVS") === FALSE && $file != "." && $file != "..")
                          $languages[] = $file;
                }
            closedir($handle);
            }
            sort($languages);

            foreach($languages as $t){
                if ($_SESSION['pref_language'] == $t)
                    $s = "SELECTED";
                    else $s="";

                if ($MSG_LANG[strtolower($t)] == "")
                    $MSG_LANG[strtolower($t)] = $t;

                echo "<option value='$t' $s>".$MSG_LANG[strtolower($t)]."</option>";
            }
            ?>
            </select></td>
        </tr>

        <tr>
            <td><?=$MSG_LANG["boardsize"]?>:</td>
            <td>

            <select name="boardSize">
            <option value="25" ><?=$MSG_LANG["micro"]?>
            <option value="30" ><?=$MSG_LANG["mini"]?>
            <option value="40" ><?=$MSG_LANG["small"]?>
            <option value="50" ><?=$MSG_LANG["medium"]?>
            <option value="60" ><?=$MSG_LANG["large"]?>
            <option value="70" >X Large
            <option value="80" >XX Large
            <option value="90" >XXX Large
            </select></td>
        </tr>


        <tr>
            <td><?=$MSG_LANG["autoaccept"]?>:</td>
            <td>

            <select name="autoaccept">
            <option value="-1" ><?=$MSG_LANG["rejectall"]?>
            <option value="0" ><?=$MSG_LANG["acceptnone"]?>
            <option value="1" ><?=$MSG_LANG["acceptall"]?>
            </select></td>
        </tr>



        <? if ($allow_email_notify) { ?>
        <tr valign="top">
            <td><?=$MSG_LANG["e-mailnotification"]?>:</td>
            <td>
                <input type="text" name="txtEmailNotification" value="">

                <br>
                <?=$MSG_LANG["emailmsg"]?>
            </td>
        </tr>
        <? } ?>

        <tr>
            <td colspan="2" align="center">
                <input type="submit" value="<?=$MSG_LANG["changeinformations"]?>">
            </td>
        </tr>
    </table>

    <input type="hidden" name="ToDo" value="UpdatePrefs">
    </form>

<form name="logout" action="mainmenu.php" method="post">
        <input type="hidden" name="ToDo" value="Logout">
</form>

</body>
</html>
