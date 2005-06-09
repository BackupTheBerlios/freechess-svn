<?php
##############################################################################################
#                                                                                            #
#                                global_includes.php
# *                            -------------------                                           #
# *   begin                : Tuesday, February 1, 2005
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://developer.berlios.de/projects/chess/                              #
# *   VERSION:             : $Id: global_includes.php,v 1.1 2005/02/04 05:16:47 trukfixer Exp $
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


$pos = (strpos($_SERVER['PHP_SELF'], "/global_includes.php"));
if ($pos !== false)
{
    include_once ("global_includes.php");
    include_once ("languages/".$_SESSION['pref_language']."/lang_common.inc.php");
    $title = $l_error_occured;
    include_once ("header.php");
    echo $l_cannot_access;
    include_once ("footer.php");
    die();
}
$db = NULL; //initialize it

ini_set("include_path",".");

include_once ("includes/timer_class.php"); // Gets benchmarking in right away

require_once ("backends/smarty/libs/Smarty.class.php"); // Smarty handles html templates - must be before GC

require_once ("backends/phpmailer/class.phpmailer.php"); // Phpmailer handles all mail functions - must be before GC

include_once ("backends/adodb/adodb.inc.php"); // Adodb handles database abstraction

include_once ("backends/adodb/adodb-perf.inc.php"); // Adodb handles database abstraction

include_once ("backends/adodb/adodb-xmlschema.inc.php"); // XML Schema handler

include_once ("backends/adodb/session/adodb-cryptsession.php"); // encrypted session handler

include_once ("backends/adodb/session/adodb-compress-gzip.php"); // compressed session handler

include_once ("includes/game_functions.php");

include_once ("includes/chessconstants.php");

include_once ("config/db_config.php");
if ((!isset($register_globals_safe)) || ($register_globals_safe == ''))
{
    $register_globals_safe = 0;
}

ini_set('arg_separator.output', '&amp;'); // Ensures that all ampersands are html-compliant, if php appends session id's to the url (instead of via cookie)
ini_set('url_rewriter.tags', ''); // Ensure that the session id is *not* passed on the url - this is a big security hole for logins - including admin.

if (get_magic_quotes_gpc())
{
    // Yes? Strip the added slashes - this will force us to comply with not needing magic quotes.

    function stripslashes_array($given)
    {
        return is_array($given) ? array_map('stripslashes_array', $given) : stripslashes($given);
    }

    stripslashes_array($_GET);
    stripslashes_array($_POST);
}

if (!isset($db_prefix))
{
    if (strlen(dirname($_SERVER['PHP_SELF'])) > 1)
    {
        $add_slash_to_url = '/';
    }

    if (!isset($server_type) || $server_type=='')
    {
        $server_type = "http";
    }

    // Much smoother - no broken header/footer issues, and seamless for user.
    header("Location: " . $server_type . "://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . $add_slash_to_url . "install.php");
    exit();
}

//include_once ("includes/".$my_db_array['driver']."-common.php"); // This is where all sql calls that are non-portable should be moved.

if (isset($_SESSION['pref_language']))
{
    include_once ("languages/".$_SESSION['pref_language']."/regional_settings.php"); // Include the correct regional settings
}

if (connect_db($my_db_array,$db) === 0)
{
    echo "SERVER ERROR :: Cannot connect to database.";
    die();
}
else
{
    // Ensure that the config values table has been created, and if so, get the config values from it.
    $debug_query = $db->Execute("SHOW TABLES LIKE '{$db_prefix}game_settings'");
    db_op_result($debug_query,__LINE__,__FILE__);
    $row = $debug_query->fields;
    if ($row !== false)
    {
        // Get the config_values from the DB
        $debug_query = $db->Execute("SELECT name,value FROM {$db_prefix}game_settings");
        db_op_result($debug_query,__LINE__,__FILE__);

        while (!$debug_query->EOF && $debug_query)
        {
            $row = $debug_query->fields;
            $$row['name'] = $row['value'];
            $debug_query->MoveNext();
        }

        // Since we now have the config values(including perf_logging), if the admin wants perf logging on - turn it on.
        if (isset($perf_logging) && $perf_logging)
        {
            $debug_query = $db->SelectLimit("SELECT * from ${db_prefix}adodb_logsql",1);
            if ($debug_query)
            {
                adodb_perf::table("${db_prefix}adodb_logsql");
                $db->LogSQL();
            }
        }
    }

    // Ensure that the sessions table has been created, and if so, start a session.
    // I bet there is a more elegant way to do this, but this works for all my testing scenarios, so its in for now.

    $debug_query = $db->Execute("SHOW TABLES LIKE '{$db_prefix}sessions'");
    db_op_result($debug_query,__LINE__,__FILE__);
    $row = $debug_query->fields;

    if ($debug_query)
    {
        // We explicitly use encrypted sessions, but this adds compression as well.
        $ADODB_SESSION_TBL     = $db_prefix."sessions";
        ADODB_Session::filter(new ADODB_Compress_Gzip());

        // The data field name "data" violates SQL reserved words - switch it to session_data.
        ADODB_Session::dataFieldName('session_data');
        session_start();
    }
}

$smarty = new Smarty;
if(getenv("HTTP_X_FORWARDED_FOR"))
{
    $ip = getenv("HTTP_X_FORWARDED_FOR"); // Get Proxy IP address for user
}
else
{
    $ip = getenv("REMOTE_ADDR"); // Get IP address for user
}


?>