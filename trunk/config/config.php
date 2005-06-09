<?php
##############################################################################################
#                                                                                            #
#                                config.php
# *                            -------------------                                           #
# *   begin                : Wednesday, January 25, 2005
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://www.compwebchess.com/forums                              #
# *   VERSION:             : $Id: config.php,v 1.2 2005/01/28 03:05:52 trukfixer Exp $
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


error_reporting(E_ALL);
//set_magic_quotes_runtime(1);      /* Convert quotes */ do NOT USE THIS!!


$VERSION = "1.3-Alpha";
$_CONFIG = true;

$CFG_JPGRAPH_DIR = "backends/jpgraph";      /* Path where JPgraph is installed */
$CFG_GRAPH_LINE_COLOR = "red";      /* Rating Graph */
$CFG_GRAPH_FILL_COLOR = "orange";
/* % Victories Graph */
$CFG_GRAPH_LINE2_COLOR = "black";   /* Victories Graph */
$CFG_GRAPH_FILL2_COLOR = "#60A0F0";
$CFG_GRAPH_SHOW = "rating";             /* Values = rating | pv */
$CFG_GRAPH_SHOW2 = "pv";            /* Values = rating | pv */

$CFG_GRAPH_LIMIT = 100;             /* Show graphic from these number of past games. Less number means faster graphics */


/* database settings */
$CFG_SERVER = "localhost";
$CFG_USER = "db_user";
$CFG_PASSWORD = "dbpass";
$CFG_DATABASE = "dbname";


/* server settings */
$CFG_SESSIONTIMEOUT = 1800;     /* session times out if user doesn't interact after x secs */
$CFG_EXPIREGAME = 14;       //becomes $game_prune 'game_prune' in cwc_config table  /* number of days before untouched games expire (minimum 1)*/
$CFG_EXPIREGAMESOON = 12;             /*  number of days before warning message sent out  */
$CFG_MINAUTORELOAD = 30;        /* min number of secs between automatic reloads reloads */
$CFG_MAINRELOAD = 30;
$CFG_USEEMAILNOTIFICATION = true;   /* Use email notification requires PHP to be properly configured for */
                    /* SMTP operations.  This flag allows you to easily activate */
                    /* or deactivate this feature.  It is highly recommended you test */
                                        /* it before putting it into production */

$CFG_MAILADDRESS = "webmaster@wherever.com";    /* email address people see when receiving WebChess generated mail */
$CFG_SITE_URL = "http://www.domain.com";
$CFG_NICKCHANGEALLOWED = false;     /* whether a user can change their nick from the main menu */
$CFG_NAMECHANGEALLOWED = false;
$CFG_NAMECHANGEEALLOWED = false;        /* whether a user can change their name from the main menu */
$CFG_MIN_ROUNDS = 4;                /* Number of rounds needed to a game count in the ranking - Minimun 1 */
$CFG_ENABLE_UNDO = false;               /* Can permit undo moves? */
$CFG_DEFAULT_LANGUAGE = "english";  /* Default Language */
$CFG_DEFAULT_SOUND = "off";     /* This sets all newusers sound preferences to off or on */
$CFG_RANKING_LIMIT = 0;                 /* Use 0 to show all players*/
$CFG_PERPAGE_LIST = 50;         /* How many items should be displayed per page */
$CFG_LOG_PATH = "logfiles";     /* webchess.log must be writeable to http server user, use Blank to disable log*/
$CFG_LOG_DEBUG = TRUE;                  /* Save Ranking updates in the txt log */
$CFG_ENABLE_CHAT = TRUE;                /* Can permit chat between players? */

$CFG_RANK_COMPUTER = TRUE;      /* Games played against chess-bots can count at ranking? */
$CFG_ENABLE_SUBRANKING = TRUE;      /* Enable SubRanking (Medal Ranking) */
$CFG_USE_RANDOM_QUOTES = TRUE;          /* Radom quotes in the mainmenu */
$CFG_DEFAULT_COLOR_THEME =  "SilverGrey"; /* Color Theme to use at index and to the new users */
$CFG_PERMIT_MULTIPLE_GAMES = TRUE;  /* Can the same player be invited more than once at the same time?*/
$CFG_CONFIRM_MOVE = TRUE;       /* Ask confirmation for every move */
$STRONG_EMAIL_VALIDATION = TRUE;    /* Checks for e-mail validation at login, and ask the user to choose a valid email.*/
                    /* Useful for old databases */
$COMPRESSION = TRUE;            /* Compress pages */

$AVATAR_LOCATION = "directory";         /* Where store avatars? (database|directory) */
$max_size = 100;                    /* Maximum File Size in KB */
$max_width= 200;                /* maximum width in pixel */
$max_height= 300;               /* maximum height in pixel */
$extensions = array("jpg", "gif", "png", "JPG", "GIF", "JPEG", "jpeg"); /* allowed files (.gif and .GIF are different!!!) */
$def_avatar = "default.jpg";            /* name of the default avatar (in /avatars/ directory) */

$CFG_ENABLE_TRIAL_RATING = TRUE;        /* Should new users pass by 5 games before receive a rating? */
$CFG_TIME_ARRAY = array(30,60,120,180,360,720,1440);    /* Time that can be used in time limited games (in minutes and less than 1440) */

$CFG_ACTIVATION = "user";        /* Who will activate the new accounts? (user|administrator) ?*/

$JAVASCRIPT_EXT = ".js";       /* What javascript extension to use?
                                .js are faster but if you are updating from an
                                older version of compwebchess use .php */
$ONLINE_LIMIT = "0";            /* how many users can conect simultaniously? 0 = unlimited */
$TEAM_LIMIT = 10;             /*  how many player in one team? */
// Forum Settings

$replies_perpage = 20;  // Topicview
$topics_perpage = 20;   // Forumview
$spiele_forum = 15;     // Which Forum will you use for discussion of games?
$main_perpage = 3;      // Mainmenu
$main_perpage2 = 8;     // last toppics at forum.php

/* Tournament Section*/
$t_banned_users = array(4899,1648,5504,8981,13377,15505);       // array(1,2,3,4); Banned user Ids -> Can't create or join a tournament
$nochatforyou = array(4899,1648,5504,8981,13377,15505);         // Ban users id from public chat

$t_min_rating = array(900,1000,1100,1200,1300,1400,1500,1600,1700,1800,1900,2000,2200,2400,2600,2700,2800,3200); /* Minimum experiecne selection*/
$t_max_rating = array(1400,1500,1600,1700,1800,1900,2000,2100,2200,2300,2400,2500,2600,2700,2800,2900,3200); /* Minimum experiecne selection */
$t_admin_only = false; // if true, only admins can create big tournaments
$kommentatoren = array(500,3191,4551,3808,1909,1785,3160,1900,7675,6412,3121,9356,1753,9092,7736,11292,13921,3483,12863,14217,8211,9417,15219); // UserID who is allowed to write comments
$kommentierte_spiele = array(3394); // gameID ot that game which shouldn't have any further comments
$min_kommentare = 5; // min. comments a game need to display it at mainmenu.php
$anz_spiele = 30; // max. displayed commented games at mainmenu.php
$ag_leiter   = array(1);   // these is the array for that users which can create more than one group
//set up and initialize smarty
ini_set ("session.use_trans_sid","0"); // Otherwise, on re-login, it will append a session id on the url - blech.

$smarty_class = "/path/to/chess/chess/backends/smarty/libs/Smarty.class.php";

$ADOdbpath = "/path/to/chess/chess/backends/adodb";
// Define the adodb directory:
define ('ADODB_DIR',"$ADOdbpath");

// Include adodb:
include ("$ADOdbpath" . "/adodb.inc.php");

// Include adodb session handlers:
$ADODB_SESSION_DRIVER = 'mysql'; //default for now, we may use innodb tables if needed
$ADODB_SESSION_CONNECT = 'localhost';
$ADODB_SESSION_USER = 'user';//database user name
$ADODB_SESSION_PWD = 'pass';//database password
$ADODB_SESSION_DB = 'batabase'; //database name
$ADODB_SESSION_TBL = 'cwc_sessions';//for now. we'll eventually be using $dbprefix()

include_once ("$ADOdbpath" . "/session/adodb-cryptsession.php");
adodb_sess_open(false,false,false);//force non-persistent connection- persistent connections can cause problems


$ip = getenv("REMOTE_ADDR");

require_once($smarty_class);
$this_path = dirname(__FILE__);
$smarty = new Smarty;
$smarty->compile_dir = "$this_path/templates_c";
$smarty->template_dir = "$this_path/templates";
session_start();

?>