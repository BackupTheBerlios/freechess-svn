<?php
##############################################################################################
#                                                                                            #
#                                forum_functions.php                                                
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

// SMILIES

$smilie = array(
"/" . preg_quote(":b)", "/") . "/",
"/" . preg_quote(":)", "/") . "/",
"/" . preg_quote(":(", "/") . "/",
"/" . preg_quote(";)", "/") . "/",
"/" . preg_quote(":P", "/") . "/",
"/" . preg_quote(":e", "/") . "/",
"/" . preg_quote("8)", "/") . "/",
"/" . preg_quote(":bad:", "/") . "/",
"/" . preg_quote(":good:", "/") . "/"
);

$smilie_img = array (
'<img src="smilies/space.gif" border="0" width="3" height="0"><img src="smilies/biggrin.gif" border="0">',
'<img src="smilies/space.gif" border="0" width="3" height="0"><img src="smilies/smiley.gif" border="0">',
'<img src="smilies/space.gif" border="0" width="3" height="0"><img src="smilies/sad.gif" border="0">',
'<img src="smilies/space.gif" border="0" width="3" height="0"><img src="smilies/wink.gif" border="0">',
'<img src="smilies/space.gif" border="0" width="3" height="0"><img src="smilies/tongue.gif" border="0">',
'<img src="smilies/space.gif" border="0" width="3" height="0"><img src="smilies/eek.gif" border="0">',
'<img src="smilies/space.gif" border="0" width="3" height="0"><img src="smilies/cool.gif" border="0">',
'<img src="smilies/space.gif" border="0" width="3" height="0"><img src="smilies/bad.gif" border="0">',
'<img src="smilies/space.gif" border="0" width="3" height="0"><img src="smilies/good.gif" border="0">',
);

function forum_smilies($str) {

	global $smilie, $smilie_img;

	$str = preg_replace($smilie, $smilie_img, $str);

	return $str;
}

function forum_smilielist() {

	global $smilie, $smilie_img;

	$list = '';

	for ($i = 0; $i < count($smilie); $i++) {
		$smile = ereg_replace("/", "", $smilie[$i]);
		$list .= '<a href="#" onclick="posting.text.value+= \'' . $smile . '\'">' . $smilie_img[$i] . '</a>';
	} // for

	return $list;

}

// FUNKTIONEN


function getcount($table, $where="") {

    $query = "SELECT count(*) AS number FROM ".$table;

    if ($where != "")

    	$query .= " ".$where;

    $c1 = mysql_query($query);    echo mysql_error();
    $c = mysql_fetch_array($c1);   echo mysql_error();

    return $c['number'];

}

function getforum($id) {

$f1 = mysql_query("SELECT * FROM forums WHERE forum_id='".$_GET['id']."'");
$f = mysql_fetch_array($f1);

return $f;

}

function getforum_bytopic($id) {

$f1 = mysql_query("SELECT f.* FROM forum_topics t
					LEFT JOIN forums f ON f.forum_id = t.forum_id
                    WHERE t.topic_id='".$id."'");
$f = mysql_fetch_array($f1);

return $f;

}

// Fuegt die Session ID an eine URL an

function append_sid($url, $non_html_amp = false)
{
        /*global $SID;

        if ( !empty($SID) && !preg_match('#sid=#', $url) )
        {
                $url .= ( ( strpos($url, '?') != false ) ? '&' : '?' ) . $SID;
        }*/

        return $url;
}

// Erstellt eine Navigationsleiste

function make_nav($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = false)
{
        $total_pages = ceil($num_items/$per_page);

        if ( $total_pages == 1 )
        {
                return '1';
        }

        $on_page = floor($start_item / $per_page) + 1;

        $page_string = '';

        if ( $total_pages > 10 )
        {
                $init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;

                for($i = 1; $i < $init_page_max + 1; $i++)
                {
                        $page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
                        if ( $i <  $init_page_max )
                        {
                                $page_string .= ", ";
                        }
                }
                ;
                if ( $total_pages > 3 )
                {
                        if ( $on_page > 1  && $on_page < $total_pages )
                        {
                                $page_string .= ( $on_page > 5 ) ? ' ... ' : ', ';

                                $init_page_min = ( $on_page > 4 ) ? $on_page : 5;
                                $init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;

                                for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++)
                                {
                                        $page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
                                        if ( $i <  $init_page_max + 1 )
                                        {
                                                $page_string .= ', ';
                                        }
                                }

                                $page_string .= ( $on_page < $total_pages - 4 ) ? ' ... ' : ', ';
                        }
                        else
                        {
                                $page_string .= ' ... ';
                        }

                        for($i = $total_pages - 2; $i < $total_pages + 1; $i++)
                        {
                                $page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>'  : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
                                if( $i <  $total_pages )
                                {
                                        $page_string .= ", ";
                                }
                        }
                }
        }
        else
        {
                for($i = 1; $i < $total_pages + 1; $i++)
                {
                        $page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
                        if ( $i <  $total_pages )
                        {
                                $page_string .= ', ';
                        }
                }
        }

        if ( $add_prevnext_text )
        {
                if ( $on_page > 1 )
                {
                        $page_string = ' <a href="' . append_sid($base_url . "&amp;start=" . ( ( $on_page - 2 ) * $per_page ) ) . '"><</a>&nbsp;&nbsp;' . $page_string;
                }

                if ( $on_page < $total_pages )
                {
                        $page_string .= '&nbsp;&nbsp;<a href="' . append_sid($base_url . "&amp;start=" . ( $on_page * $per_page ) ) . '">></a>';
                }

                $page_string = 'Seite ' . $on_page . ' von ' . $total_pages . ' ' . $page_string;

        }

        return $page_string;
}

function showavatar($avatar) {
	global  $extensions, $def_avatar, $AVATAR_LOCATION;
	if ($AVATAR_LOCATION == "directory"){
		$img = $def_avatar;
		$root = dirname(__FILE__);
		while (list($key, $val) = each($extensions)) {
			$imgpath = $root . '/images/avatars/' . $avatar . '.' . $val;
			if (file_exists($imgpath)) {
				$img = $avatar . '.' . $val;
			}
		}
		echo '<img src="images/avatars/' . $img . '">';
	}
	else{
		echo '<img src="show_avatar.php?id=' . $avatar . '">';
	}
}
function postcount ($id) {

return getcount("forum_topics", "WHERE userid = '$id'");

}

function gamename($id, $short = true) {

	global $MSG_LANG;

	$p = mysql_query("SELECT * from games WHERE gameID='$id'");
    $row = mysql_fetch_array($p);
    $white = $row['whitePlayer'];
    $black = $row['blackPlayer'];

    $p = mysql_query("SELECT firstName,playerID,engine FROM players WHERE playerID='$white'");
    $row = mysql_fetch_array($p);
    $p = mysql_query("SELECT firstName,playerID,engine FROM players WHERE playerID='$black'");
    $row2 = mysql_fetch_array($p);
	$row[0] = $row[0]." (".$MSG_LANG["white"].")";
	$row2[0] = $row2[0]." (".$MSG_LANG["black"].")";

    $ttitle = 'Spiel '.$id.': '.$row[0].' X '.$row2[0];

    if ($short == true)
    	$ttitle = 'value="'.$ttitle.'" readonly';

    return $ttitle;
}

function bbcode($text) {

        $text = preg_replace("|\[b\](.*)\[/b\]|Uism","<b>$1</b>",$text);
        $text = preg_replace("|\[i\](.*)\[/i\]|Uism","<i>$1</i>",$text);
        $text = preg_replace("|\[u\](.*)\[/u\]|Uism","<u>$1</u>",$text);
        $text = preg_replace("|\[s\](.*)\[/s\]|Uism","<s>$1</s>",$text);

		$text=eregi_replace("quote\\]","quote]",$text);
		$text=str_replace("[quote]","<blockquote>Quote:<hr>",$text);
		$text=str_replace("[/quote]\r\n","<hr></blockquote>",$text);
		$text=str_replace("[/quote]","<hr></blockquote>",$text);

        $text=eregi_replace("code\\]","code]",$text);
		$text=str_replace("[code]","<blockquote>Code:<hr>",$text);
		$text=str_replace("[/code]\r\n","<hr></blockquote>",$text);
		$text=str_replace("[/code]","<hr></blockquote>",$text);

        $text=str_replace("[img]","<img src=\"",$text);
        $text=str_replace("[/img]","\" border=0> ",$text);

        // Add [url] Tags to all URLs

        $urlArray = array(
   		"/([^]_a-z0-9-=\"'\/])((https?|ftp|gopher|news|telnet):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\};<>]*)/si",
   		"/^((https?|ftp|gopher|news|telnet):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\};<>]*)/si"
 		);

	 	$urlRArray = array(
   		"\\1[url]\\2\\4[/url]",
   		"[url]\\1\\3[/url]"
 		);

 		$emailArray = array(
   		"/([ \n\r\t])([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3}))/si",
       	"/^([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3}))/si"
	   	);

 		$emailRArray = array(
   		"\\1[email]\\2[/email]",
   		"[email]\\0[/email]"
 		);


  		$text = preg_replace($urlArray, $urlRArray, $text);

  		if (strpos($text, "@"))
   		$text = preg_replace($emailArray, $emailRArray, $text);

        $text=eregi_replace("\\[url\\]www.([^\\[]*)\\[img\\]www.([^\\[]*)\\[/img\\]\\[/url\\]","<a href=\"http://www.\\1\" target=_blank><img src=\"http://www.\\2\" border=\"0\"></a>",$text);
        $text=eregi_replace("\\[url\\]http://([^\\[]*)\\[img\\]http://([^\\[]*)\\[/img\\]\\[/url\\]","<a href=\"http://\\1\" target=_blank><img src=\"http://\\2\" border=\"0\"></a>",$text);
        $text=eregi_replace("\\[url\\]www.([^\\[]*)\\[/url\\]","<a href=\"http://www.\\1\" target=_blank>\\1</a>",$text);
        $text=eregi_replace("\\[url\\]([^\\[]*)\\[/url\\]","<a href=\"\\1\" target=_blank>\\1</a>",$text);
        $text=eregi_replace("\\[url=\&quot;","[url=\"",$text);
        $text=eregi_replace("\\[url=([^\\[]*)\\]([^\\[]*)\\[\\/url\\]","<a href=\"\\1\" target=\"_blank\">\\2</a>",$text);
        $text=eregi_replace("\\[email\\]([^\\[]*)\\[/email\\]","<a href=\"mailto:\\1\">\\1</a>",$text);
        $text=eregi_replace("(\[img\=)(.*)(\])", "<img src=\"\\2\" border=\"0\">",$text);


return $text;

}

?>