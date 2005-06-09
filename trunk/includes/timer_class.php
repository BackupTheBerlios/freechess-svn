<?php
##############################################################################################
#                                                                                            #
#                                timer_class.php                                   
# *                            -------------------                                           #
# *   begin                : Tuesday, February 1, 2005                                    
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://www.compwebchess.com/forums                              #
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


$pos = (strpos($_SERVER['PHP_SELF'], "/timer_class.php"));
if ($pos !== false)
{
    include_once ("global_includes.php"); 
    include_once ("languages/$langdir/lang_common.inc");
    $title = $l_error_occured;
    include_once ("header.php");
    echo $l_cannot_access;
    include_once ("footer.php");
    die();
}

// Description: Create Benchmark Class

class c_Timer
{
    var $t_start = 0;
    var $t_stop = 0;
    var $t_elapsed = 0;

    function start()
    {
        $this->t_start = microtime();
    }

    function stop()
    {
        $this->t_stop  = microtime();
    }

    function elapsed()
    {
        $start_u = substr($this->t_start,0,10); $start_s = substr($this->t_start,11,10);
        $stop_u  = substr($this->t_stop,0,10);  $stop_s  = substr($this->t_stop,11,10);
        $start_total = doubleval($start_u) + $start_s;
        $stop_total  = doubleval($stop_u) + $stop_s;
        $this->t_elapsed = $stop_total - $start_total;
        return $this->t_elapsed;
    }
}

$BenchmarkTimer = new c_Timer;
$BenchmarkTimer->start(); // Start benchmarking immediately
?>
