<?php
##############################################################################################
#                                                                                            #
#                                newgame.php
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
 /* these functions are used to start a new game */
   function initBoard($themeID)
   {
      global $board;

      /* clear board */
      for ($i = 0; $i < 8; $i++)
      {
         for ($j = 0; $j < 8; $j++)
         {
            $board[$i][$j] = 0;
         }
      }

      if ($themeID == "1"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "2"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[3][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "3"){

            /* setup white pieces */
            $board[0][0] = WHITE | ROOK;
            $board[0][7] = WHITE | ROOK;
            $board[0][1] = WHITE | KNIGHT;
            $board[0][6] = WHITE | KNIGHT;
            $board[0][2] = WHITE | BISHOP;
            $board[0][5] = WHITE | BISHOP;
            $board[0][3] = WHITE | QUEEN;
            $board[0][4] = WHITE | KING;
            $board[1][0] = WHITE | PAWN;
            $board[1][1] = WHITE | PAWN;
            $board[3][2] = WHITE | PAWN;
            $board[4][4] = WHITE | PAWN;
            $board[1][4] = WHITE | PAWN;
            $board[1][5] = WHITE | PAWN;
            $board[1][6] = WHITE | PAWN;
            $board[1][7] = WHITE | PAWN;

            /* setup black pieces */
            $board[6][0] = BLACK | PAWN;
            $board[6][1] = BLACK | PAWN;
            $board[6][2] = BLACK | PAWN;
            $board[6][3] = BLACK | PAWN;
            $board[6][5] = BLACK | PAWN;
            $board[6][6] = BLACK | PAWN;
            $board[6][7] = BLACK | PAWN;
            $board[7][0] = BLACK | ROOK;
            $board[7][7] = BLACK | ROOK;
            $board[7][1] = BLACK | KNIGHT;
            $board[5][5] = BLACK | KNIGHT;
            $board[7][2] = BLACK | BISHOP;
            $board[7][5] = BLACK | BISHOP;
            $board[7][3] = BLACK | QUEEN;
            $board[7][4] = BLACK | KING;

      }elseif ($themeID == "4"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[3][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[2][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[5][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "5"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[5][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "6"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[5][3] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[6][5] = BLACK | KING;

      }elseif ($themeID == "7"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[4][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "8"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[3][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[2][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[4][5] = BLACK | PAWN;
      $board[5][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "9"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[2][2] = WHITE | KNIGHT;
      $board[2][5] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[4][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[5][2] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "10"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[4][3] = BLACK | PAWN;
      $board[5][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "11"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[4][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[4][3] = BLACK | PAWN;
      $board[5][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "12"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[2][2] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[4][3] = BLACK | PAWN;
      $board[5][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "13"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[3][4] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[5][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "14"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[2][2] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[4][3] = BLACK | PAWN;
      $board[5][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[3][1] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "15"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[1][3] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[4][3] = BLACK | PAWN;
      $board[5][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "16"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[3][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "17"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[2][2] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[3][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[4][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[5][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "18"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[3][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[4][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "19"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[2][2] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[3][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[5][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][5] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[6][6] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][6] = BLACK | KING;

      }elseif ($themeID == "20"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[2][5] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[3][2] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[4][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[5][2] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "21"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[2][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "22"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[2][5] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[3][2] = WHITE | PAWN;
      $board[4][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[4][2] = BLACK | PAWN;
      $board[5][3] = BLACK | PAWN;
      $board[5][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "23"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][5] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[2][5] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[3][2] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][6] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[3][5] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[3][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "24"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[2][2] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[3][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[5][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[3][1] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "25"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[2][5] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[4][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "26"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[2][5] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[5][3] = BLACK | PAWN;
      $board[4][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "27"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[3][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[4][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "28"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[3][2] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "29"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[2][5] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[3][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[5][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[5][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "30"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[2][5] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[4][1] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[4][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[5][2] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "31"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[4][3] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "32"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[2][5] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[4][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[5][2] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "33"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[4][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "34"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[2][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[4][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "35"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[2][2] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[4][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "36"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[3][3] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[4][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[5][2] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "37"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[3][3] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[5][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[5][2] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "38"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[3][3] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[5][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[5][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "39"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[2][2] = WHITE | KNIGHT;
      $board[3][3] = WHITE | KNIGHT;
      $board[4][6] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[5][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[5][2] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "40"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[2][2] = WHITE | KNIGHT;
      $board[3][3] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[5][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[5][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "41"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[2][2] = WHITE | KNIGHT;
      $board[3][3] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[5][3] = BLACK | PAWN;
      $board[5][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "42"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[2][2] = WHITE | KNIGHT;
      $board[3][3] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[3][2] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[5][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[5][2] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "43"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[2][2] = WHITE | KNIGHT;
      $board[3][3] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[5][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[5][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "44"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[3][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[5][2] = BLACK | PAWN;
      $board[4][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "45"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[1][1] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[3][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[4][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "46"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[2][2] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[3][2] = WHITE | PAWN;
      $board[3][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[4][2] = BLACK | PAWN;
      $board[4][3] = BLACK | PAWN;
      $board[5][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "47"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[2][2] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[1][2] = WHITE | PAWN;
      $board[1][3] = WHITE | PAWN;
      $board[3][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[6][1] = BLACK | PAWN;
      $board[6][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[4][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }elseif ($themeID == "48"){

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;
      $board[1][0] = WHITE | PAWN;
      $board[1][1] = WHITE | PAWN;
      $board[3][2] = WHITE | PAWN;
      $board[4][3] = WHITE | PAWN;
      $board[1][4] = WHITE | PAWN;
      $board[1][5] = WHITE | PAWN;
      $board[1][6] = WHITE | PAWN;
      $board[1][7] = WHITE | PAWN;

      /* setup black pieces */
      $board[6][0] = BLACK | PAWN;
      $board[4][1] = BLACK | PAWN;
      $board[4][2] = BLACK | PAWN;
      $board[6][3] = BLACK | PAWN;
      $board[6][4] = BLACK | PAWN;
      $board[6][5] = BLACK | PAWN;
      $board[6][6] = BLACK | PAWN;
      $board[6][7] = BLACK | PAWN;
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[5][5] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      }else{

      /* setup white pieces */
      $board[0][0] = WHITE | ROOK;
      $board[0][7] = WHITE | ROOK;
      $board[0][1] = WHITE | KNIGHT;
      $board[0][6] = WHITE | KNIGHT;
      $board[0][2] = WHITE | BISHOP;
      $board[0][5] = WHITE | BISHOP;
      $board[0][3] = WHITE | QUEEN;
      $board[0][4] = WHITE | KING;

      /* setup black pieces */
      $board[7][0] = BLACK | ROOK;
      $board[7][7] = BLACK | ROOK;
      $board[7][1] = BLACK | KNIGHT;
      $board[7][6] = BLACK | KNIGHT;
      $board[7][2] = BLACK | BISHOP;
      $board[7][5] = BLACK | BISHOP;
      $board[7][3] = BLACK | QUEEN;
      $board[7][4] = BLACK | KING;

      /* setup pawns */
      for ($i = 0; $i < 8; $i++)
      {
         $board[1][$i] = WHITE | PAWN;
         $board[6][$i] = BLACK | PAWN;

         }
      }
   }

   function createNewGame($gameID)
   {
      global $db,$db_prefix;

      $tquery = mysql_query("SELECT * FROM {$db_prefix}games WHERE game_id = '".$gameID."'");

      while ($the = mysql_fetch_array($tquery))
      {

      if ($the['thematic'] == "1")
      {

      mysql_query("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:00:10', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");

      mysql_query("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (NOW(), '$gameID', 'knight', 'black', '7', '6', '5', '5', null, null, '0')");

      $themeID = 1;

      initBoard($themeID);

      }elseif ($the['thematic'] == "2"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (NOW(), '$gameID', 'pawn', 'white', '1', '5', '3', '5', null, null, '0')");
      mysql_query($moveOne);

      $themeID = 2;

      initBoard($themeID);

      }elseif ($the['thematic'] == "3"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:00:20', '$gameID', 'pawn', 'white', '1', '3', '3', '3', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:00:30', '$gameID', 'knight', 'black', '7', '6', '5', '5', null, null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:00:40', '$gameID', 'pawn', 'white', '1', '2', '3', '2', null, null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:00:50', '$gameID', 'pawn', 'black', '6', '4', '4', '4', null, null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'white', '3', '3', '4', '4', 'pawn', null, '0')");
      mysql_query($moveFive);

            $themeID = 3;

            initBoard($themeID);

      }elseif ($the['thematic'] == "4"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:01:10', '$gameID', 'pawn', 'white', '1', '3', '3', '3', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:01:20', '$gameID', 'knight', 'black', '7', '6', '5', '5', null, null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:01:30', '$gameID', 'pawn', 'white', '1', '2', '3', '2', null, null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:01:40', '$gameID', 'pawn', 'black', '6', '4', '5', '4', null, null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'white', '1', '6', '2', '6', 'null', null, '0')");
      mysql_query($moveFive);

            $themeID = 4;

            initBoard($themeID);

      }elseif ($the['thematic'] == "5"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:01:50', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '2', '5', '2', 'null', null, '0')");
      mysql_query($moveTwo);

      $themeID = 5;

      initBoard($themeID);

      }elseif ($the['thematic'] == "6"){

            $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:02:10', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

            $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:02:20', '$gameID', 'pawn', 'black', '6', '4', '4', '4', null, null, '0')");
      mysql_query($moveTwo);

            $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:02:30', '$gameID', 'knight', 'white', '0', '6', '2', '5', null, null, '0')");
      mysql_query($moveThree);

            $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:02:40', '$gameID', 'knight', 'black', '7', '6', '5', '5', null, null, '0')");
      mysql_query($moveFour);

            $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:02:50', '$gameID', 'knight', 'white', '2', '5', '4', '4', 'pawn', null, '0')");
      mysql_query($moveFive);

            $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:03:00', '$gameID', 'pawn', 'black', '6', '3', '5', '3', null, null, '0')");
      mysql_query($moveSix);

            $moveSeven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:03:10', '$gameID', 'knight', 'white', '4', '4', '6', '5', 'pawn', null, '0')");
      mysql_query($moveSeven);

            $moveEight = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'king', 'black', '7', '4', '6', '5', 'knight', null, '0')");
      mysql_query($moveEight);

            $themeID = 6;

            initBoard($themeID);

      }elseif ($the['thematic'] == "7"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:03:20', '$gameID', 'pawn', 'white', '1', '3', '3', '3', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '5', '4', '5', 'null', null, '0')");
      mysql_query($moveTwo);

      $themeID = 7;

      initBoard($themeID);

      }elseif ($the['thematic'] == "8"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:03:30', '$gameID', 'pawn', 'white', '1', '3', '3', '3', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:03:40', '$gameID', 'pawn', 'black', '6', '5', '4', '5', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:03:50', '$gameID', 'pawn', 'white', '1', '2', '3', '2', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:04:10', '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:04:20', '$gameID', 'pawn', 'white', '1', '6', '2', '6', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '6', '5', '6', 'null', null, '0')");
      mysql_query($moveSix);

      $themeID = 8;

      initBoard($themeID);

      }elseif ($the['thematic'] == "9"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:04:30', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:04:40', '$gameID', 'pawn', 'black', '6', '4', '4', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:04:50', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:05:10', '$gameID', 'knight', 'black', '7', '1', '5', '2', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:05:20', '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveSix);

      $themeID = 9;

      initBoard($themeID);

      }elseif ($the['thematic'] == "10"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:05:30', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:05:40', '$gameID', 'pawn', 'black', '6', '4', '5', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:05:50', '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '3', '4', '3', 'null', null, '0')");
      mysql_query($moveFour);

      $themeID = 10;

      initBoard($themeID);

      }elseif ($the['thematic'] == "11"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:06:10', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:06:20', '$gameID', 'pawn', 'black', '6', '4', '5', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:06:30', '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:06:40', '$gameID', 'pawn', 'black', '6', '3', '4', '3', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'white', '3', '4', '4', '4', 'null', null, '0')");
      mysql_query($moveFive);

      $themeID = 11;

      initBoard($themeID);

      }elseif ($the['thematic'] == "12"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:06:50', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:07:10', '$gameID', 'pawn', 'black', '6', '4', '5', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:07:20', '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:07:30', '$gameID', 'pawn', 'black', '6', '3', '4', '3', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:07:40', '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveSix);

      $themeID = 12;

      initBoard($themeID);

      }elseif ($the['thematic'] == "13"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:07:50', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:08:10', '$gameID', 'pawn', 'black', '6', '4', '5', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:08:20', '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:08:30', '$gameID', 'pawn', 'black', '6', '3', '4', '3', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:08:40', '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:08:50', '$gameID', 'pawn', 'black', '4', '3', '3', '4', 'pawn', null, '0')");
      mysql_query($moveSix);

      $moveSeven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'knight', 'white', '2', '2', '3', '4', 'pawn', null, '0')");
      mysql_query($moveSeven);

      $themeID = 13;

      initBoard($themeID);

      }elseif ($the['thematic'] == "14"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:09:10', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:09:20', '$gameID', 'pawn', 'black', '6', '4', '5', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:09:30', '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:09:40', '$gameID', 'pawn', 'black', '6', '3', '4', '3', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:09:50', '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'bishop', 'black', '7', '5', '3', '1', 'null', null, '0')");
      mysql_query($moveSix);

      $themeID = 14;

      initBoard($themeID);

      }elseif ($the['thematic'] == "15"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:10:10', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:10:20', '$gameID', 'pawn', 'black', '6', '4', '5', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:10:30', '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:10:40', '$gameID', 'pawn', 'black', '6', '3', '4', '3', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'knight', 'white', '0', '1', '1', '3', 'null', null, '0')");
      mysql_query($moveFive);

      $themeID = 15;

      initBoard($themeID);

      }elseif ($the['thematic'] == "16"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (NOW(), '$gameID', 'pawn', 'white', '1', '6', '3', '6', null, null, '0')");
      mysql_query($moveOne);

      $themeID = 16;

      initBoard($themeID);

      }elseif ($the['thematic'] == "17"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:10:50', '$gameID', 'pawn', 'white', '1', '3', '3', '3', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:11:10', '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:11:20', '$gameID', 'pawn', 'white', '1', '2', '3', '2', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:11:30', '$gameID', 'pawn', 'black', '6', '6', '5', '6', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:11:40', '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '3', '4', '3', 'null', null, '0')");
      mysql_query($moveSix);

      $themeID = 17;

      initBoard($themeID);

      }elseif ($the['thematic'] == "18"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:11:50', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:12:10', '$gameID', 'pawn', 'black', '6', '4', '4', '4', null, null, '0')");

      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'white', '1', '5', '3', '5', null, null, '0')");
      mysql_query($moveThree);

      $themeID = 18;

      initBoard($themeID);

      }elseif ($the['thematic'] == "19"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:12:20', '$gameID', 'pawn', 'white', '1', '3', '3', '3', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:12:30', '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:12:40', '$gameID', 'pawn', 'white', '1', '2', '3', '2', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:12:50', '$gameID', 'pawn', 'black', '6', '6', '5', '6', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:13:10', '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:13:20', '$gameID', 'bishop', 'black', '7', '5', '6', '6', 'null', null, '0')");
      mysql_query($moveSix);

      $moveSeven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:13:30', '$gameID', 'pawn', 'white', '1', '4', '3', '4', 'null', null, '0')");
      mysql_query($moveSeven);

      $moveEight = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'king', 'black', '7', '4', '7', '6', 'null', null, '0')");
      mysql_query($moveEight);

      $themeID = 19;

      initBoard($themeID);

      }elseif ($the['thematic'] == "20"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:13:40', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:13:50', '$gameID', 'pawn', 'black', '6', '4', '4', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:14:10', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:14:20', '$gameID', 'knight', 'black', '7', '1', '5', '2', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'bishop', 'white', '0', '5', '3', '2', 'null', null, '0')");
      mysql_query($moveFive);

      $themeID = 20;

      initBoard($themeID);

      }elseif ($the['thematic'] == "21"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (NOW(), '$gameID', 'pawn', 'white', '1', '1', '2', '1', null, null, '0')");
      mysql_query($moveOne);

      $themeID = 21;

      initBoard($themeID);

      }elseif ($the['thematic'] == "22"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:14:30', '$gameID', 'pawn', 'white', '1', '3', '3', '3', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:14:40', '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:14:50', '$gameID', 'pawn', 'white', '1', '2', '3', '2', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:15:10', '$gameID', 'pawn', 'black', '6', '4', '5', '4', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:15:20', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:15:30', '$gameID', 'pawn', 'black', '6', '2', '4', '2', 'null', null, '0')");
      mysql_query($moveSix);

      $moveSeven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:15:40', '$gameID', 'pawn', 'white', '3', '3', '4', '3', 'null', null, '0')");
      mysql_query($moveSeven);

      $moveEight = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '3', '5', '3', 'null', null, '0')");
      mysql_query($moveEight);

      $themeID = 22;

      initBoard($themeID);

      }elseif ($the['thematic'] == "23"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:15:50', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:16:10', '$gameID', 'pawn', 'black', '6', '4', '4', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:16:20', '$gameID', 'pawn', 'white', '1', '5', '3', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:16:30', '$gameID', 'pawn', 'black', '4', '4', '3', '5', 'pawn', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:16:40', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:16:50', '$gameID', 'pawn', 'black', '6', '6', '4', '6', 'null', null, '0')");
      mysql_query($moveSix);

      $moveSeven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:17:10', '$gameID', 'bishop', 'white', '0', '5', '3', '2', 'null', null, '0')");
      mysql_query($moveSeven);

      $moveEight = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:17:20', '$gameID', 'pawn', 'black', '4', '6', '3', '6', 'null', null, '0')");
      mysql_query($moveEight);

      $moveNine = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'king', 'white', '0', '4', '0', '6', 'null', null, '0')");
      mysql_query($moveNine);

      $themeID = 23;

      initBoard($themeID);

      }elseif ($the['thematic'] == "24"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:17:30', '$gameID', 'pawn', 'white', '1', '3', '3', '3', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:17:40', '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:17:50', '$gameID', 'pawn', 'white', '1', '2', '3', '2', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:18:10', '$gameID', 'pawn', 'black', '6', '4', '5', '4', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:18:20', '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'bishop', 'black', '7', '5', '3', '1', 'null', null, '0')");
      mysql_query($moveSix);

      $themeID = 24;

      initBoard($themeID);

      }elseif ($the['thematic'] == "25"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:18:30', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:18:40', '$gameID', 'pawn', 'black', '6', '4', '4', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:18:50', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveFour);

      $themeID = 25;

      initBoard($themeID);

      }elseif ($the['thematic'] == "26"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:19:10', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:19:20', '$gameID', 'pawn', 'black', '6', '4', '4', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:19:30', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '3', '5', '3', 'null', null, '0')");
      mysql_query($moveFour);

      $themeID = 26;

      initBoard($themeID);

      }elseif ($the['thematic'] == "27"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:19:40', '$gameID', 'pawn', 'white', '1', '3', '3', '3', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-01 00:19:50', '$gameID', 'pawn', 'black', '6', '3', '4', '3', null, null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'white', '1', '2', '3', '2', null, null, '0')");
      mysql_query($moveThree);

      $themeID = 27;

      initBoard($themeID);

      }elseif ($the['thematic'] == "28"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:00:10', '$gameID', 'pawn', 'white', '1', '3', '3', '3', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:00:20', '$gameID', 'pawn', 'black', '6', '3', '4', '3', null, null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:00:30', '$gameID', 'pawn', 'white', '1', '2', '3', '2', null, null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '4', '3', '3', '2', 'pawn', null, '0')");
      mysql_query($moveFour);

      $themeID = 28;

      initBoard($themeID);

      }elseif ($the['thematic'] == "29"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:00:40', '$gameID', 'pawn', 'white', '1', '3', '3', '3', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:00:50', '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:01:10', '$gameID', 'pawn', 'white', '1', '2', '3', '2', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:01:20', '$gameID', 'pawn', 'black', '6', '4', '5', '4', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:01:30', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'bishop', 'black', '6', '1', '5', '1', 'null', null, '0')");
      mysql_query($moveSix);

      $themeID = 29;

      initBoard($themeID);

      }elseif ($the['thematic'] == "30"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:01:40', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:01:50', '$gameID', 'pawn', 'black', '6', '4', '4', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:02:10', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:02:20', '$gameID', 'knight', 'black', '7', '1', '5', '2', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'bishop', 'white', '0', '5', '4', '1', 'null', null, '0')");
      mysql_query($moveFive);

      $themeID = 30;

      initBoard($themeID);

      }elseif ($the['thematic'] == "31"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:02:30', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:02:40', '$gameID', 'pawn', 'black', '6', '3', '4', '3', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'white', '3', '4', '4', '3', 'pawn', null, '0')");
      mysql_query($moveThree);

      $themeID = 31;

      initBoard($themeID);

      }elseif ($the['thematic'] == "32"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:02:50', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:03:10', '$gameID', 'pawn', 'black', '6', '4', '4', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:03:20', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:03:30', '$gameID', 'knight', 'black', '7', '1', '5', '2', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveFive);

      $themeID = 32;

      initBoard($themeID);

      }elseif ($the['thematic'] == "33"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:03:40', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '2', '4', '2', 'null', null, '0')");
      mysql_query($moveTwo);

      $themeID = 33;

      initBoard($themeID);

      }elseif ($the['thematic'] == "34"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:03:50', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:04:10', '$gameID', 'pawn', 'black', '6', '2', '4', '2', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'white', '1', '2', '2', '2', 'null', null, '0')");
      mysql_query($moveThree);

      $themeID = 34;

      initBoard($themeID);

      }elseif ($the['thematic'] == "35"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:04:20', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:04:30', '$gameID', 'pawn', 'black', '6', '2', '4', '2', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveThree);

      $themeID = 35;

      initBoard($themeID);

      }elseif ($the['thematic'] == "36"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:04:40', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:04:50', '$gameID', 'pawn', 'black', '6', '2', '4', '2', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:05:10', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:05:20', '$gameID', 'knight', 'black', '7', '1', '5', '2', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:05:30', '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:05:40', '$gameID', 'pawn', 'black', '4', '2', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSix);

      $moveSeven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:05:50', '$gameID', 'knight', 'white', '2', '5', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSeven);

      $moveEight = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '4', '4', '4', 'null', null, '0')");
      mysql_query($moveEight);

      $themeID = 36;

      initBoard($themeID);

      }elseif ($the['thematic'] == "37"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:06:10', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:06:20', '$gameID', 'pawn', 'black', '6', '2', '4', '2', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:06:30', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:06:40', '$gameID', 'knight', 'black', '7', '1', '5', '2', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:06:50', '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:07:10', '$gameID', 'pawn', 'black', '4', '2', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSix);

      $moveSeven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:07:20', '$gameID', 'knight', 'white', '2', '5', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSeven);

      $moveEight = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '6', '5', '6', 'null', null, '0')");
      mysql_query($moveEight);

      $themeID = 37;

      initBoard($themeID);

      }elseif ($the['thematic'] == "38"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:07:30', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:07:40', '$gameID', 'pawn', 'black', '6', '2', '4', '2', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:07:50', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:08:10', '$gameID', 'pawn', 'black', '6', '4', '5', '4', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:08:20', '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:08:30', '$gameID', 'pawn', 'black', '4', '2', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSix);

      $moveSeven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:08:40', '$gameID', 'knight', 'white', '2', '5', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSeven);

      $moveEight = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '0', '5', '0', 'null', null, '0')");
      mysql_query($moveEight);

      $themeID = 38;

      initBoard($themeID);

      }elseif ($the['thematic'] == "39"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:08:50', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:09:10', '$gameID', 'pawn', 'black', '6', '2', '4', '2', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:09:20', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:09:30', '$gameID', 'pawn', 'black', '6', '3', '5', '3', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:09:40', '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:09:50', '$gameID', 'pawn', 'black', '4', '2', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSix);

      $moveSeven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:10:10', '$gameID', 'knight', 'white', '2', '5', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSeven);

      $moveEight = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:10:20', '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveEight);

      $moveNine = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:10:30', '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveNine);

      $moveTen = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:10:40', '$gameID', 'knight', 'black', '7', '1', '5', '2', 'null', null, '0')");
      mysql_query($moveTen);

      $moveEleven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'bishop', 'white', '0', '2', '4', '6', 'null', null, '0')");
      mysql_query($moveEleven);

      $themeID = 39;

      initBoard($themeID);

      }elseif ($the['thematic'] == "40"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:10:50', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:11:10', '$gameID', 'pawn', 'black', '6', '2', '4', '2', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:11:20', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:11:30', '$gameID', 'pawn', 'black', '6', '3', '5', '3', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:11:40', '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:11:50', '$gameID', 'pawn', 'black', '4', '2', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSix);

      $moveSeven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:12:10', '$gameID', 'knight', 'white', '2', '5', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSeven);

      $moveEight = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:12:20', '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveEight);

      $moveNine = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:12:30', '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveNine);

      $moveTen = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '6', '5', '6', 'null', null, '0')");
      mysql_query($moveTen);

      $themeID = 40;

      initBoard($themeID);

      }elseif ($the['thematic'] == "41"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:12:40', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:12:50', '$gameID', 'pawn', 'black', '6', '2', '4', '2', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:13:10', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:13:20', '$gameID', 'pawn', 'black', '6', '4', '5', '4', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:13:30', '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:13:40', '$gameID', 'pawn', 'black', '4', '2', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSix);

      $moveSeven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:13:50', '$gameID', 'knight', 'white', '2', '5', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSeven);

      $moveEight = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:14:10', '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveEight);

      $moveNine = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:14:20', '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveNine);

      $moveTen = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '3', '5', '3', 'null', null, '0')");
      mysql_query($moveTen);

      $themeID = 41;

      initBoard($themeID);

      }elseif ($the['thematic'] == "42"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:14:30', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:14:40', '$gameID', 'pawn', 'black', '6', '2', '4', '2', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:14:50', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:15:10', '$gameID', 'pawn', 'black', '6', '3', '5', '3', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:15:20', '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:15:30', '$gameID', 'pawn', 'black', '4', '2', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSix);

      $moveSeven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:15:40', '$gameID', 'knight', 'white', '2', '5', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSeven);

      $moveEight = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:15:50', '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveEight);

      $moveNine = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:16:10', '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveNine);

      $moveTen = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:16:20', '$gameID', 'knight', 'black', '7', '1', '5', '2', 'null', null, '0')");
      mysql_query($moveTen);

      $moveEleven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'bishop', 'white', '0', '5', '3', '2', 'null', null, '0')");
      mysql_query($moveEleven);

      $themeID = 42;

      initBoard($themeID);

      }elseif ($the['thematic'] == "43"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:16:30', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:16:40', '$gameID', 'pawn', 'black', '6', '2', '4', '2', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:16:50', '$gameID', 'knight', 'white', '0', '6', '2', '5', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:17:10', '$gameID', 'pawn', 'black', '6', '3', '5', '3', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:17:20', '$gameID', 'pawn', 'white', '1', '3', '3', '3', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:17:30', '$gameID', 'pawn', 'black', '4', '2', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSix);

      $moveSeven = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:17:40', '$gameID', 'knight', 'white', '2', '5', '3', '3', 'pawn', null, '0')");
      mysql_query($moveSeven);

      $moveEight = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:17:50', '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveEight);

      $moveNine = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:18:10', '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveNine);

      $moveTen = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '0', '5', '0', 'null', null, '0')");
      mysql_query($moveTen);

      $themeID = 43;

      initBoard($themeID);

      }elseif ($the['thematic'] == "44"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:18:20', '$gameID', 'pawn', 'white', '1', '3', '3', '3', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:18:30', '$gameID', 'pawn', 'black', '6', '3', '4', '3', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:18:40', '$gameID', 'pawn', 'white', '1', '2', '3', '2', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '2', '5', '2', 'null', null, '0')");
      mysql_query($moveFour);

      $themeID = 44;

      initBoard($themeID);

      }elseif ($the['thematic'] == "45"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:18:50', '$gameID', 'pawn', 'white', '1', '1', '3', '1', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:19:10', '$gameID', 'pawn', 'black', '6', '4', '4', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:19:20', '$gameID', 'bishop', 'white', '0', '2', '1', '1', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveFour);

      $themeID = 45;

      initBoard($themeID);

      }elseif ($the['thematic'] == "46"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:19:40', '$gameID', 'pawn', 'white', '1', '3', '3', '3', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:19:50', '$gameID', 'pawn', 'black', '6', '3', '4', '3', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:20:10', '$gameID', 'pawn', 'white', '1', '2', '3', '2', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:20:20', '$gameID', 'pawn', 'black', '6', '4', '5', '4', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:20:30', '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '2', '4', '2', 'null', null, '0')");
      mysql_query($moveSix);

      $themeID = 46;

      initBoard($themeID);

      }elseif ($the['thematic'] == "47"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:20:40', '$gameID', 'pawn', 'white', '1', '4', '3', '4', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:20:50', '$gameID', 'pawn', 'black', '6', '4', '4', '4', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'knight', 'white', '0', '1', '2', '2', 'null', null, '0')");
      mysql_query($moveThree);

      $themeID = 47;

      initBoard($themeID);

      }elseif ($the['thematic'] == "48"){

      $moveOne = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:21:10', '$gameID', 'pawn', 'white', '1', '3', '3', '3', null, null, '0')");
      mysql_query($moveOne);

      $moveTwo = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:21:20', '$gameID', 'knight', 'black', '7', '6', '5', '5', 'null', null, '0')");
      mysql_query($moveTwo);

      $moveThree = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:21:30', '$gameID', 'pawn', 'white', '1', '2', '3', '2', 'null', null, '0')");
      mysql_query($moveThree);

      $moveFour = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:21:40', '$gameID', 'pawn', 'black', '6', '2', '4', '2', 'null', null, '0')");
      mysql_query($moveFour);

      $moveFive = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES ('2004-10-02 00:21:50', '$gameID', 'pawn', 'white', '3', '3', '4', '3', 'null', null, '0')");
      mysql_query($moveFive);

      $moveSix = ("INSERT INTO history (timeOfMove, gameID, curPiece, curColor, fromRow, fromCol, toRow, toCol, replaced, promotedTo, isInCheck) VALUES (Now(), '$gameID', 'pawn', 'black', '6', '1', '4', '1', 'null', null, '0')");
      mysql_query($moveSix);

      $themeID = 48;

      initBoard($themeID);

      }
      else
      {

      $themeID = 0;

      initBoard($themeID);

      }
   }
}
?>
