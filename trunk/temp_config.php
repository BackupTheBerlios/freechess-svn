<?php


        //load settings
include_once ('global_includes.php');

//start ob_start(); stuff

    // load external functions for setting up new game */
    require_once ('chessutils.php');
    require_once ('newgame.php');
    require_once ('chessdb.php');

    // Language selection */
include_once ("languages/".$_SESSION['pref_language']."/strings.inc.php");

$config_array = parse_ini_file("config/config_setup.ini");
foreach($config_array as $key => $value)
{
     $query = $db->Execute("INSERT INTO cwc_game_settings (id,name,value) VALUES ('','$key','$value')");
     db_op_result($query,__LINE__,__FILE__);
     echo "Inserting $key = $value into database.. <br>";

}

?>