<?php
##############################################################################################
#                                                                                            #
#                                db_config.php
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
#DO NOT ATTEMPT TO EDIT THIS FILE- IT IS WRITTEN AUTOMATICALLY BY THE INSTALLATION SCRIPT

//NOTE: installer does not write this file with the below
error_reporting(E_ALL);
//set_magic_quotes_runtime(1);      /* Convert quotes */ do NOT USE THIS!!
    //end note

// Automatically created configuration file. Do not change!

$pos = strpos($_SERVER['PHP_SELF'], "/db_config.php");
if ($pos !== false)
{
    echo "You can not access this file directly!";
    die();
}



$my_db_array = array("driver" => "mysql",
                    "database" => "dbname",
                    "db_user" => "dbuser",
                    "db_pass" => "dbpass",
                    "db_host" => "localhost",
                    "port" => "",
                    "prefix" => "cwc_"
                    );

$adminpass = "adminpass";
$ADODB_CRYPT_KEY = "lkurmnyqhwlubbpr";
$server_type = "http";
$db_prefix = $my_db_array['prefix'];

?>
