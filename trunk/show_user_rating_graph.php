<?php
##############################################################################################
#                                                                                            #
#                                show_user_rating_graph.php                                                
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

require_once ('config.php');
require ("$CFG_JPGRAPH_DIR/src/jpgraph.php");
require ("$CFG_JPGRAPH_DIR/src/jpgraph_line.php");
require ("$CFG_JPGRAPH_DIR/src/jpgraph_bar.php");
require ("$CFG_JPGRAPH_DIR/src/jpgraph_plotmark.inc.php");

	/* connect to database */
	require_once( 'connectdb.php');
	require_once('chessdb.php');

	/* Language selection */
	if ($_GET['language'] != "russian")
    	require "languages/".$_GET['language']."/strings.inc.php";
	require "languages/english/strings.inc.php";

$playerID = $_GET['player'];

$limit = $CFG_GRAPH_LIMIT;

$rs = mysql_query("SELECT count(*) FROM games WHERE (whitePlayer='$playerID' OR blackPlayer='$playerID') AND  ratingWhite>0 AND ratingBlack>0");
$row = mysql_fetch_array($rs);
$count = $row[0];
$start = $count - $limit;
if($start<0)$start=0;

$rs = mysql_query("SELECT rating_max FROM players WHERE playerID='$playerID'");
$row = mysql_fetch_array($rs);
$ratingMax = $row[0];

$rs = mysql_query("SELECT *,DATE_FORMAT(dateCreated, '%b% %d:%h%m%s') as criado FROM games WHERE (whitePlayer='$playerID' OR
blackPlayer='$playerID') AND  ratingWhite>0 AND ratingBlack>0 order by dateCreated limit $start,$limit");

if (mysql_num_rows($rs) <=1){
	$datas[] = "";
	$ratings[] = "1500";
	$datas[] = "";
	$ratings[] = "1500";

}
else
 while ($row = mysql_fetch_array($rs)){

	$c = explode(":",$row[criado]);
	$datas[] = $c[0];
	
	if ($row[whitePlayer] == $playerID){
	    $ratings[] = $row[ratingWhite];
	}
	else{
	    $ratings[] = $row[ratingBlack];
	}
 }
if (getRating($playerID) >0){
$ratings[] = getRating($playerID);
$datas[] = date("b d");
}
$total = count($ratings);

$graph = new Graph(400,160,"auto");
//$graph->SetShadow();

$graph->SetScale("textlin",900,$ratingMax);

$graph->title->Set($MSG_LANG["ratingevolution"]);
//$graph->subtitle->Set("aa");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL,5);
$graph->yaxis->SetFont(FF_FONT1,FS_NORMAL,5);
//$graph->xaxis->SetLabelAngle(45);

$graph->img->SetMargin(40,15,20,30);

$graph->xaxis->SetTextTickInterval(ceil($total/5));
//$graph->xaxis->SetTextLabelInterval(floor($total/4));
$graph->xaxis->SetTickLabels($datas);
$graph->xgrid->Show(true,true);
$graph->ygrid->Show(true,true);
//$graph->xaxis->SetTitle("Pontuação");
//$graph->yaxis->SetTitle("Rating");
$graph->yaxis->scale->SetGrace(20);


$p1 = new LinePlot($ratings);
$p1->SetColor($CFG_GRAPH_LINE_COLOR);
//$p1->SetStepStyle();
$p1->SetFillColor($CFG_GRAPH_FILL_COLOR);

$graph->Add($p1);

$graph->Stroke();
?>
