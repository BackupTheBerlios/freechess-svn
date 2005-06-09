<?php
##############################################################################################
#                                                                                            #
#                                EPDutils.php                                                
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

// $Id: EPDutils.php,v 1.1 2003/11/13 17:10:24 dadij Exp $
// An example EPD string: r7/4b3/2p1r1k1/1p1pPp1q/1P1P1P1p/PR2NRpP/2Q3K1/8 w - - bm Nxf5; id "WAC.279";

function EPD2Board($EPD) {
// Accepts an EPD (describing a chess position). Returns a two-dimensional array corresponding to the position

    $pieceName = array ('k'=>'king', 'q'=>'queen', 'r'=>'rook', 'b'=>'bishop', 'n'=>'knight', 'p'=>'pawn');

	list($pieces, $nointerest) = explode(' ', $EPD, 2);	// Get the position of the pieces from the EPD string
	$pieces = preg_replace("/[2-8]/e", "str_repeat('1', '\$0')", $pieces);	// Expand empty squares
	$chars = preg_split('//', $pieces, -1, PREG_SPLIT_NO_EMPTY);			// Array of chessboard squares
	$rank = 7;
	$file = 0;
	foreach ($chars as $asq) {	// Examine each square on the board
		if($asq == '/') {		// End of line (rank)
			$rank--;
			$file = 0;
		} else if($asq == '1') {		// An empty square
			$sq[$rank][$file++] = '';
		} else {				// The square is occupied by a piece
			$lsq = strtolower($asq);
			if($lsq != $asq) {	// A white piece
				$color = 'white';
			} else {
				$color = 'black';
			}
			$sq[$rank][$file++] = $color . ' ' . $pieceName[$lsq];		// WebChess 0.8.4 format
		}
	}
	return $sq;
}

function EPDCurColor($EPD) {
// Accepts an EPD (describing a chess position). Returns the color of the side to move

	list($pieces, $tomove, $nointerest) = explode(' ', $EPD, 3);	// Get the side to move from the EPD string
	if($tomove == 'w') {	// White to move
		$color = 'white';
	} else {
		$color = 'black';
	}
	return $color;
}

function getEPDStartPos()
{
	return 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -';
}

function insertPiece($color, $piece, $row, $column, $game)
{
    // Convert all to lower case..
    $color = strtolower($color);
    $piece = strtolower($piece);

    // Check if color is valid
    if( ($color!="white") && ($color!="black") )
        return false;

    // Check if piece name is valid
    if( ($piece!="pawn") && ($piece!="rook") && ($piece!="bishop") && ($piece!="knight") && ($piece!="queen") && ($piece!="king") )
        return false;

    // Rows and Cols are numbered 0 through 7
    $tmpQuery = "INSERT INTO pieces(gameID, color, piece, row, col) values($game,'$color', '$piece', $row, $column)";
    mysql_query($tmpQuery);

    return true;
}

function insertPieces($sq, $game)
{


    for($i=7;$i>=0;$i--)
    {
        for($j=0;$j<8;$j++)
        {
            $piece = explode(" ", $sq[$i][$j]);
            if( ($piece[0]!="") && ($piece[1]!="") )
            {
                if(!insertPiece($piece[0], $piece[1], $i, $j, $game))
                {
                    echo 'ERROR inserting into the Piece table';
                }
            }
        }
	}
}

function displayPlayersFormItems()
{
	$tmpQuery = "SELECT playerID, nick FROM players";
    $tmpResult = mysql_query($tmpQuery);

	while($tmpRow = mysql_fetch_assoc($tmpResult))
	{
		$id = $tmpRow['playerID'];
		echo "<option value=\"$id\">" . $tmpRow['nick'] . "</option>\n";
	}
}

function displayBoard($sq)
{
    echo '<div style="margin-top:3px;margin-bottom:3px;">';
    echo '<table cellpadding="0" style="border:2px solid #555; padding:0; border-collapse: collapse;border-spacing:0;">';
    echo '<tr style="height:25px; background-color:#bbbbbb;"><td colspan="10"></td></tr>';
    for($i=7;$i>=0;$i--)
    {
        echo '<tr><td style="width:25px; background-color:#bbbbbb;"></td>' . "\n";
        for($j=0;$j<8;$j++)
        {
            if((($i+$j)%2)==0)
                echo '<td bgcolor="lightgray">';
            else
                echo '<td>';
            $piece = explode(" ", $sq[$i][$j]);
            if( ($piece[0]!="") && ($piece[1]!="") )
            {
                echo '<img id="sq'.$i.$j.'" width="50" height="50" src="images/pieces/beholder/' . $piece[0] . '_' . $piece[1] . '.gif" alt="" />';
            } else {
                echo '<img id="sq'.$i.$j.'" width="50" height="50" src="images/pieces/beholder/blank.gif" alt="" />';
            }
           echo "</td>";
        }
        echo '<td style="width:25px; background-color:#bbbbbb;"></td></tr>' . "\n";
    }
    echo '<tr style="height:25px; background-color:#bbbbbb;"><td colspan="10"></td></tr>';
    echo '</table>';
    echo '</div>';
}

?>