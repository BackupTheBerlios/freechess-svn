<?php
##############################################################################################
#                                                                                            #
#                                move.php
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
 /* these functions deal specifically with moving a piece */
    function doMove($post = "")
    {
        global $board, $isPromoting, $doUndo, $history, $numMoves,$db,$db_prefix;

        if ($post != "")
            $_POST = $post;

        // if moving en-passant
        // (ie: if pawn moves diagonally without replacing anything)
        if ((($board[$_POST['from_row']][$_POST['from_col']] & COLOR_MASK) == PAWN) && ($_POST['to_col'] != $_POST['from_col']) && ($board[$_POST['to_row']][$_POST['to_col']] == 0)){
            // delete eaten pawn
            $board[$_POST['from_row']][$_POST['to_col']] = 0;
            if ($board[$_POST['from_row']][$_POST['to_col']] & BLACK)
                 $tmpColor = "black";
            else
                 $tmpColor = "white";
            $tmpPiece = getPieceName($board[$_POST['from_row']][$_POST['to_col']]);
        }

        // move piece to destination, replacing whatever's there
        if ($board[$_POST['to_row']][$_POST['to_col']] != 0){
            if ($board[$_POST['to_row']][$_POST['to_col']] & BLACK)
                 $tmpColor = "black";
            else
                 $tmpColor = "white";
            $tmpPiece = getPieceName($board[$_POST['to_row']][$_POST['to_col']]);
        }
        $board[$_POST['to_row']][$_POST['to_col']] = $board[$_POST['from_row']][$_POST['from_col']];

        /* delete piece from old position */
        $board[$_POST['from_row']][$_POST['from_col']] = 0;

        /* if not Undoing, but castling */
        if (($doUndo != "yes") && (($board[$_POST['to_row']][$_POST['to_col']] & COLOR_MASK) == KING) && (($_POST['to_col'] - $_POST['from_col']) == 2))
        {
            /* castling to the right, move the right rook to the left side of the king */
            $board[$_POST['to_row']][5] = $board[$_POST['to_row']][7];

            /* delete rook from original position */
            $board[$_POST['to_row']][7] = 0;
        }
        elseif (($doUndo != "yes") && (($board[$_POST['to_row']][$_POST['to_col']] & COLOR_MASK) == KING) && (($_POST['from_col'] - $_POST['to_col']) == 2))
        {
            /* castling to the left, move the left rook to the right side of the king */
            $board[$_POST['to_row']][3] = $board[$_POST['to_row']][0];

            /* delete rook from original position */
            $board[$_POST['to_row']][0] = 0;
        }

        return true;
    }
?>
