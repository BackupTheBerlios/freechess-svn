<?php
##############################################################################################
#                                                                                            #
#                                game_functions.php
# *                            -------------------                                           #
# *   begin                : Tuesday, February 1, 2005
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://www.compwebchess.com/forums                              #
# *   VERSION:             : $Id: game_functions.php,v 1.1 2005/02/04 05:16:54 trukfixer Exp $
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

    //file contains only functions used in all areas of the game. not for module specific functions
    //this file is included in every php file, containing the database functions, at minimum.
    //included via global_includes

ini_set ("session.use_trans_sid","0"); // Otherwise, on re-login, it will append a session id on the url - blech.
include_once ("config/db_config.php");

function connect_db($my_db_array,$db)
{
    global $db,$dbport , $ADODB_SESSION_DRIVER, $ADODB_SESSION_CONNECT,$ADODB_SESSION_DB,$ADODB_SESSION_USER,$ADODB_SESSION_PWD;
    //connect to database via adodb
   $ADODB_SESSION_DRIVER = $my_db_array['driver'];
   $ADODB_SESSION_DB = $my_db_array['database'];
   $ADODB_SESSION_USER = $my_db_array['db_user'];
   $ADODB_SESSION_PWD = $my_db_array['db_pass'];
   $ADODB_SESSION_CONNECT = $my_db_array['db_host'];
   $dbport = $my_db_array['port'];
   $db_prefix = $my_db_array['prefix'];

    $ADODB_NEVER_PERSIST   = true;
    $ADODB_COUNTRECS = false; // This *deeply* improves the speed of adodb.

    if (!function_exists('mysql_connect'))
    {
        die ("The mysql_connect function is not loaded - you need the php-mysql module installed for this game to function");
        return 0;
    }

    if (!empty($dbport))
    {
        $ADODB_SESSION_CONNECT.= ":$dbport";
    }

    $db = ADONewConnection("$ADODB_SESSION_DRIVER");
    $db->debug=0;
    $db->autoRollback = true;
    $result = $db->Connect("$ADODB_SESSION_CONNECT", "$ADODB_SESSION_USER", "$ADODB_SESSION_PWD", "$ADODB_SESSION_DB");

    if (!$result)
    {
        die ("Unable to connect to the database: " . $db->ErrorMsg());
        return 0;
    }
    return $result;
}

function adminlog($log_type, $data = '')
{
    global $db, $db_prefix;
    $debug_query = $db->Execute("SELECT type FROM {$db_prefix}logs LIMIT 1");
    db_op_result($debug_query,__LINE__,__FILE__);
    $row = $debug_query->fields;
    if ($row !== false)
    {
        // write log_entry to the admin log
        if (!empty($log_type))
        {


            $sql_arr = array($_SESSION['player_id'],$log_type,$data);
            $sqstring = "INSERT INTO {$db_prefix}logs (player_id, type, time, log_data) VALUES (?,?,NOW(),?)";

            $sql = $db->Prepare($sqstring);
            $debug_query = $db->Execute($sql,$sql_arr);
            db_op_result($debug_query,__LINE__,__FILE__);

        }
    }

}

function db_op_result($query,$served_line,$served_page)
{
    global $db, $cumulative;

    if ($db->ErrorMsg() == '')
    {
        return true;
    }
    else
    {
        $dberror = "A Database error occurred in " . $served_page . " on line " . ($served_line-1) .
                   " (called from: $_SERVER[PHP_SELF]): " . $db->ErrorMsg() . "";
        $dberror = str_replace("'","&#39;",$dberror); // Allows the use of apostrophes.

        adminlog(10,$dberror);

    }
}

//$sql_stmt = $db->Prepare('SELECT FROM blah WHERE blah=? AND bla1 = ?');
//$result = $db->Execute($sql_stmt,array("item1","item2"));

function delete_old_games($game_prune,$db)
{
        /* cleanup dead games */
    /* determine threshold for oldest game permitted */
    $targetDate = date("Y-m-d H:i:s", mktime(date('H'),date('i'),0, date('m'), date('d') - $game_prune,date('Y')));
    /* find out which games are older */
    $sql = $db->Prepare("SELECT * FROM games WHERE lastMove < ? AND (gameMessage='inviteDeclined' OR gameMessage='playerInvited' OR gameMessage='')");
    $prune_query = $db->Execute($sql,array($target_date));
    db_op_result($prune_query,__LINE__,__FILE__);
    while(!$prune_query -> EOF)
    {
        $old_game = $prune_query->fields;
        if ($old_game['gameMessage'] == '')
        {//Abandoned game. User will lose points
            $sql = $db->Prepare("SELECT MAX( timeOfMove ) AS move_time, curColor FROM history WHERE gameID = ? GROUP BY gameID");
            $sql_array = array($old_game['gameID']);
            $query = $db->Execute($sql,$sql_array);
            db_op_result($query,__LINE__,__FILE__);
            $row = $query -> fields;
            if ($row['curColor'] != "")
            {
                if ($row['curColor'] == "white")
                {
                    $playersColor = "black";
                }
                else if ($row['curColor'] == "black")
                {
                    $playersColor = "white";
                }
            }
            else
            {
                $playersColor = "white";
            }

            saveRanking($old_game['gameID'],"resign",$playersColor,1);
            $sql = "UPDATE games SET lastMove = NOW() WHERE gameID = ?";
            $res = $db->Execute($sql,$sql_array);
            db_op_result($res,__LINE__,__FILE__);

            adminlog(GAME_PRUNE,"Deleting Game ID ".$old_game['gameID']." from Database");
        }
        else
        {
            $sql = array();
            $sql[1] = $db->Prepare("DELETE FROM history WHERE gameID = ?");
            $sql[2] = $db->Prepare("DELETE FROM pieces WHERE gameID = ?");
            $sql[3] = $db->Prepare("DELETE FROM messages WHERE gameID = ?");
            $sql[4] = $db->Prepare("DELETE FROM chat WHERE gameID = ?");
            $sql[5] = $db->Prepare("DELETE FROM notes WHERE gameID = ?");
            $sql[6] = $db->Prepare("DELETE FROM games WHERE gameID = ?");

            foreach($sql as $string)
            {
                $res = $db->Execute($string,$sql_array);
                db_op_result($res,__LINE__,__FILE__);
                adminlog(GAME_PRUNE,"Deleting Game ID ".$old_game['gameID']." from Database");
            }
       }
       $prune_query->MoveNext();
    }
}

function clean_text($text)
{
    $text = preg_replace("/[^\w\d]/","",$text);
    return $text;
}


function validate_email_format ($email)//move to player_functions.php file
{
    if (get_magic_quotes_gpc ())
    {
        $email = stripslashes($email);
    }

    // This is based on page 295 of the book 'Mastering Regular Expressions' - the most definitive RFC-compliant email regex.

    // Some shortcuts for avoiding backslashitis
    $esc        = '\\\\';
    $Period      = '\.';
    $space      = '\040';
    $tab         = '\t';
    $OpenBR     = '\[';
    $CloseBR     = '\]';
    $OpenParen  = '\(';
    $CloseParen  = '\)';
    $NonASCII   = '\x80-\xff';
    $ctrl        = '\000-\037';
    $CRlist     = '\n\015';  // note: this should really be only \015.

    // Items 19, 20, 21 -- see table on page 295 of 'Mastering Regular Expressions'
    $qtext = "[^$esc$NonASCII$CRlist\"]";                // for within "..."
    $dtext = "[^$esc$NonASCII$CRlist$OpenBR$CloseBR]";    // for within [...]
    $quoted_pair = " $esc [^$NonASCII] ";    // an escaped character

    // Items 22 and 23, comment.
    // Impossible to do properly with a regex, I make do by allowing at most
    // one level of nesting.
    $ctext = " [^$esc$NonASCII$CRlist()] ";

    // $Cnested matches one non-nested comment.
    // It is unrolled, with normal of $ctext, special of $quoted_pair.
    $Cnested = "";
    $Cnested .= "$OpenParen";                    // (
    $Cnested .= "$ctext*";                        //       normal*
    $Cnested .= "(?: $quoted_pair $ctext* )*";    //       (special normal*)*
    $Cnested .= "$CloseParen";                    //                         )

    // $comment allows one level of nested parentheses
    // It is unrolled, with normal of $ctext, special of ($quoted_pair|$Cnested)
    $comment = "";
    $comment .= "$OpenParen";                        //  (
    $comment .= "$ctext*";                            //     normal*
    $comment .= "(?:";                                //       (
    $comment .= "(?: $quoted_pair | $Cnested )";    //         special
    $comment .= "$ctext*";                            //         normal*
    $comment .= ")*";                                //            )*
    $comment .= "$CloseParen";                        //                )

    // $X is optional whitespace/comments
    $X = "";
    $X .= "[$space$tab]*";                    // Nab whitespace
    $X .= "(?: $comment [$space$tab]* )*";    // If comment found, allow more spaces


    // Item 10: atom
    $atom_char = "[^($space)<>\@,;:\".$esc$OpenBR$CloseBR$ctrl$NonASCII]";
    $atom = "";
    $atom .= "$atom_char+";        // some number of atom characters ...
    $atom .= "(?!$atom_char)";    // ... not followed by something that
                                    //     could be part of an atom

    // Item 11: doublequoted string, unrolled.
    $quoted_str = "";
    $quoted_str .= "\"";                            // "
    $quoted_str .= "$qtext *";                        //   normal
    $quoted_str .= "(?: $quoted_pair $qtext * )*";    //   ( special normal* )*
    $quoted_str .= "\"";                            //        "


    // Item 7: word is an atom or quoted string
    $word = "";
    $word .= "(?:";
    $word .= "$atom";        // Atom
    $word .= "|";            // or
    $word .= "$quoted_str";    // Quoted string
    $word .= ")";

    // Item 12: domain-ref is just an atom
    $domain_ref = $atom;

    // Item 13: domain-literal is like a quoted string, but [...] instead of "..."
    $domain_lit = "";
    $domain_lit .= "$OpenBR";                        // [
    $domain_lit .= "(?: $dtext | $quoted_pair )*";    //   stuff
    $domain_lit .= "$CloseBR";                        //         ]

    // Item 9: sub-domain is a domain-ref or a domain-literal
    $sub_domain = "";
    $sub_domain .= "(?:";
    $sub_domain .= "$domain_ref";
    $sub_domain .= "|";
    $sub_domain .= "$domain_lit";
    $sub_domain .= ")";
    $sub_domain .= "$X"; // optional trailing comments

    // Item 6: domain is a list of subdomains separated by dots
    $domain = "";
    $domain .= "$sub_domain";
    $domain .= "(?:";
    $domain .= "$Period $X $sub_domain";
    $domain .= ")*";

    // Item 8: a route. A bunch of "@ $domain" separated by commas, followed by a colon.
    $route = "";
    $route .= "\@ $X $domain";
    $route .= "(?: , $X \@ $X $domain )*"; // additional domains
    $route .= ":";
    $route .= "$X"; // optional trailing comments

    // Item 5: local-part is a bunch of $word separated by periods
    $local_part = "";
    $local_part .= "$word $X";
    $local_part .= "(?:";
    $local_part .= "$Period $X $word $X"; // additional words
    $local_part .= ")*";

    // Item 2: addr-spec is local@domain
    $addr_spec = "$local_part \@ $X $domain";

    // Item 4: route-addr is <route? addr-spec>
    $route_addr = "";
    $route_addr .= "< $X";
    $route_addr .= "(?: $route )?"; // optional route
    $route_addr .= "$addr_spec";    // address spec
    $route_addr .= ">";

    // Item 3: phrase........
    $phrase_ctrl = '\000-\010\012-\037'; // like ctrl, but without tab

    // Like atom-char, but without listing space, and uses phrase_ctrl.
    // Since the class is negated, this matches the same as atom-char plus space and tab
    $phrase_char = "[^()<>\@,;:\".$esc$OpenBR$CloseBR$NonASCII$phrase_ctrl]";

    // We've worked it so that $word, $comment, and $quoted_str to not consume trailing $X
    // because we take care of it manually.
    $phrase = "";
    $phrase .= "$word";                            // leading word
    $phrase .= "$phrase_char *";                   // "normal" atoms and/or spaces
    $phrase .= "(?:";
    $phrase .= "(?: $comment | $quoted_str )";     // "special" comment or quoted string
    $phrase .= "$phrase_char *";                //  more "normal"
    $phrase .= ")*";

    // Item 1: mailbox is an addr_spec or a phrase/route_addr
    $mailbox = "";
    $mailbox .= "$X";                    // optional leading comment
    $mailbox .= "(?:";
    $mailbox .= "$addr_spec";            // address
    $mailbox .= "|";                    // or
    $mailbox .= "$phrase  $route_addr";    // name and address
    $mailbox .= ")";

    // test it and return results
    $isValid = preg_match("/^$mailbox$/xS",$email);

    return($isValid);
} // END validateEmailFormat

class my_phpmailer extends phpmailer // This handles mail for the game.
{
    var $From;
    var $FromName;
    var $WordWrap;
    var $Host;
    var $Mailer;

    function my_phpmailer()
    {
        global $admin_mail, $admin_mail_name, $mailer_type,$smtp_host;

        $this->From = $admin_mail;
        $this->FromName = $admin_mail_name;
        $this->WordWrap = 75;
        $this->Host = $smtp_host;
//        $this->Host = "localhost";
        $this->Mailer = $mailer_type;
        $this->CharSet = "UTF-8";
        $this->SetLanguage("en","backends/phpmailer/language/");
    }
}

function check_login($player_id,$session_id)
{
    global $db,$db_prefix;
    //this function is run on every logged-in page so we'll clean up using this...
    $remove = $db->Prepare("DELETE FROM {$db_prefix}sessions WHERE expiry < ?");
    $time_max = time()+900; //if a session is due to expire within 15 minutes-we'll kill it.
    $res = $db->Execute($remove,array($time_max));
    $sql = $db->Prepare("SELECT expiry FROM {$db_prefix}sessions WHERE sesskey = ?");
    $result = $db->Execute($sql,array($session_id));
    db_op_result($result,__LINE__,__FILE__);
    $data = $result->fields;
    if($data['expiry'] > 0)
    {
        get_player_data($player_id);
        $update = $db->Prepare("UPDATE {$db_prefix}players SET last_update=NOW() where player_id=?");
        $result = $db->Execute($update,array($player_id));
        db_op_result($result,__LINE__,__FILE__);
        return true;
    }
    else
    {
        return false;
    }
}

function get_player_data($player_id)
{
    //get necessary player data and assign it to $_SESSION
    global $db,$_SESSION,$db_prefix;
    $sql = $db->Prepare("SELECT a.player_id,a.username,b.*,c.points,c.rating,c.trials_left ".
                         "FROM {$db_prefix}players as a,{$db_prefix}player_preference as b,{$db_prefix}player_stats as c ".
                         "WHERE a.player_id = ? and a.player_id = b.player_id and a.player_id = c.player_id");
    $query = $db->Execute($sql,array($player_id));
    db_op_result($query,__LINE__,__FILE__);
    $data = $query->fields;
    foreach($data as $key => $value)
    {
          if(!is_int($key))
          {
              $_SESSION[$key] = $value;//assign the db field name as the variable name and session assoc key
          }
    }
    if(empty($_SESSION))
    {
          return false;
    }
    else
    {
          return $_SESSION;
    }


}
?>
