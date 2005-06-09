function GamePiece()
{
 this.piece=  0;
 this.dist =  0;
 this.row  = -1;
 this.col  = -1;
}

function isSafe(testRow, testCol, testColor, pawntest)
{
 acord = { row:-1, col:-1, nrow:-1, ncol:-1};
 var ennemyColor=0;
 if(testColor=='white')
 {
  ennemyColor = 128; /* 1000 0000 */
 }

 if(((testRow-1)>=0)&&((testCol-2)>=0))
 {
  if(board[testRow-1][testCol-2]==(KNIGHT|ennemyColor))
  {
   acord.nrow=testRow;
   acord.ncol=testCol;
   acord.row =testRow-1;
   acord.col =testCol-2;
   return false;
  }
 }
 if(((testRow+1)<8)&&((testCol-2)>=0))
 {
  if(board[testRow+1][testCol-2]==(KNIGHT|ennemyColor))
  {
   acord.nrow=testRow;
   acord.ncol=testCol;
   acord.row=testRow+1;
   acord.col=testCol-2;
   return false;
  }
 }
 if(((testRow-2)>=0)&&((testCol-1)>=0))
 {
  if(board[testRow-2][testCol-1]==(KNIGHT|ennemyColor))
  {
   acord.nrow=testRow;
   acord.ncol=testCol;
   acord.row=testRow-2;
   acord.col=testCol-1;
   return false;
  }
 }
 if(((testRow-2)>=0)&&((testCol+1)<8))
 {
  if(board[testRow-2][testCol+1]==(KNIGHT|ennemyColor))
  {
   acord.nrow=testRow;
   acord.ncol=testCol;
   acord.row=testRow-2;
   acord.col=testCol+1;
    return false;
  }
 }
 if(((testRow-1)>=0)&&((testCol+2)<8))
 {
  if(board[testRow-1][testCol+2]==(KNIGHT|ennemyColor))
  {
   acord.nrow=testRow;
   acord.ncol=testCol;
   acord.row=testRow-1;
   acord.col=testCol+2;
    return false;
  }
 }
 if(((testRow+1)<8)&&((testCol+2)<8))
 {
  if(board[testRow+1][testCol+2]==(KNIGHT|ennemyColor))
  {
   acord.nrow=testRow;
   acord.ncol=testCol;
   acord.row=testRow+1;
   acord.col=testCol+2;
   return false;
  }
 }
 if(((testRow+2)<8)&&((testCol-1)>=0))
 {
  if(board[testRow+2][testCol-1]==(KNIGHT|ennemyColor))
  {
   acord.nrow=testRow;
   acord.ncol=testCol;
   acord.row=testRow+2;
   acord.col=testCol-1;
    return false;
  }
 }
 if(((testRow+2)<8)&&((testCol+1)<8))
 {
  if(board[testRow+2][testCol+1]==(KNIGHT|ennemyColor))
  {
   acord.nrow=testRow;
   acord.ncol=testCol;
   acord.row=testRow+2;
   acord.col=testCol+1;
    return false;
  }
 }
 var pieceFound=new Array();
 for(var i=0;i<8;i++)
 {
  pieceFound[i]=new GamePiece();
 }
 var fnd=0;
 var kingRow=0;
 var kingCol=0;
 var targetKing=getPieceCode(testColor,"king");
 for(var m=0;m<8;m++)
 {
  for(var n=0;n<8;n++)
  {
    if(board[m][n]==targetKing)
    {
     kingRow=m;
     kingCol=n;
    fnd++;
    }
  }
 }
 if(!fnd) { errMsg = "IsSafe cannot find king on board" }

 board[kingRow][kingCol]=0;
 for(var i=1;i<8;i++)
 {
  if(((testRow-i)>=0)&&((testCol-i)>=0))
  {
    if((pieceFound[0].piece==0)&&(board[testRow-i][testCol-i]!=0))
    {
    pieceFound[0].piece=board[testRow-i][testCol-i];
     pieceFound[0].dist=i;
     pieceFound[0].row=testRow-i;
     pieceFound[0].col=testCol-i;
    }
  }
  if((testRow-i)>=0)
  {
    if((pieceFound[1].piece==0)&&(board[testRow-i][testCol]!=0))
    {
    pieceFound[1].piece=board[testRow-i][testCol];
     pieceFound[1].dist=i;
    pieceFound[1].row=testRow-i;
    pieceFound[1].col=testCol;
    }
  }
  if(((testRow-i)>=0)&&((testCol+i)<8))
  {
    if((pieceFound[2].piece==0)&&(board[testRow-i][testCol+i]!=0))
    {
    pieceFound[2].piece=board[testRow-i][testCol+i];
     pieceFound[2].dist=i;
     pieceFound[2].row=testRow-i;
     pieceFound[2].col=testCol+i;
    }
  }
  if((testCol+i)<8)
  {
    if((pieceFound[3].piece==0)&&(board[testRow][testCol+i]!=0))
    {
    pieceFound[3].piece=board[testRow][testCol+i];
    pieceFound[3].dist=i;
     pieceFound[3].row=testRow;
     pieceFound[3].col=testCol+i;
    }
  }
  if(((testRow+i)<8)&&((testCol+i)<8))
  {
   if((pieceFound[4].piece==0)&&(board[testRow+i][testCol+i]!=0))
    {
     pieceFound[4].piece=board[testRow+i][testCol+i];
     pieceFound[4].dist=i;
     pieceFound[4].row=testRow+i;
     pieceFound[4].col=testCol+i;
    }
  }
  if((testRow+i)<8)
  {
    if((pieceFound[5].piece==0)&&(board[testRow+i][testCol]!=0))
    {
    pieceFound[5].piece=board[testRow+i][testCol];
     pieceFound[5].dist=i;
     pieceFound[5].row=testRow+i;
     pieceFound[5].col=testCol;
    }
  }
  if(((testRow+i)<8)&&((testCol-i)>= 0))
  {
    if((pieceFound[6].piece==0)&&(board[testRow+i][testCol-i]!=0))
    {
    pieceFound[6].piece=board[testRow+i][testCol-i];
    pieceFound[6].dist=i;
     pieceFound[6].row=testRow+i;
     pieceFound[6].col=testCol-i;
    }
  }
  if((testCol-i)>=0)
  {
    if((pieceFound[7].piece==0)&&(board[testRow][testCol-i]!=0))
    {
    pieceFound[7].piece=board[testRow][testCol-i];
    pieceFound[7].dist=i;
     pieceFound[7].row=testRow;
     pieceFound[7].col=testCol-i;
    }
  }
 }
 board[kingRow][kingCol]=targetKing;

 //for(var i=1;i<8;i++)
    //alert(''+getPieceName(pieceFound[i].piece)+' '+pieceFound[i].row+pieceFound[i].col);

 for(var i=0;i<8;i++)
 {
  if((pieceFound[i].piece!=0)&&((pieceFound[i].piece&BLACK)==ennemyColor))
  {
    switch(i)
    {
     case 0:
     case 2:
     case 4:
     case 6:
      if(((pieceFound[i].piece&COLOR_MASK)==QUEEN)||((pieceFound[i].piece&COLOR_MASK)==BISHOP))
      {
      acord.nrow=testRow;
      acord.ncol=testCol;
        acord.row=pieceFound[i].row;
        acord.col=pieceFound[i].col;
        return false;
      }
     if((pieceFound[i].dist==1)&&((pieceFound[i].piece&COLOR_MASK)==PAWN)&&(pawntest))
      {
        if((ennemyColor==WHITE)&&((i==0)||(i==2)))
      {
       acord.nrow=testRow;
       acord.ncol=testCol;
       acord.row=pieceFound[i].row;
       acord.col=pieceFound[i].col;
         return false;
      }
        else
      {
       if((ennemyColor==BLACK)&&((i==4)||(i==6)))
       {
        acord.nrow=testRow;
        acord.ncol=testCol;
        acord.row=pieceFound[i].row;
        acord.col=pieceFound[i].col;
          return false;
       }
      }
      }
      if((pieceFound[i].dist==1)&&((pieceFound[i].piece&COLOR_MASK)==KING))
      {
        var tmpPiece=board[testRow][testCol];
        board[testRow][testCol]=pieceFound[i].piece;
        var kingRow=0;
        var kingCol=0;
        switch(i)
        {
         case 0: kingRow=testRow-1; kingCol=testCol-1; break;
         case 1: kingRow=testRow-1; kingCol=testCol;   break;
         case 2: kingRow=testRow-1; kingCol=testCol+1; break;
         case 3: kingRow=testRow;   kingCol=testCol+1; break;
         case 4: kingRow=testRow+1; kingCol=testCol+1; break;
         case 5: kingRow=testRow+1; kingCol=testCol;      break;
         case 6: kingRow=testRow+1; kingCol=testCol-1; break;
       case 7: kingRow=testRow;   kingCol=testCol-1; break;
        }
        board[kingRow][kingCol]=0;
        var tmpIsSafe=isInCheck(getOtherColor(testColor));
        board[kingRow][kingCol]=pieceFound[i].piece;
        board[testRow][testCol]=tmpPiece;
        if(!tmpIsSafe)
      {
       acord.nrow=testRow;
       acord.ncol=testCol;
       acord.row=pieceFound[i].row;
       acord.col=pieceFound[i].col;
         return false;
      }
      }
     break;
     case 1:
     case 3:
     case 5:
     case 7:

     if(((pieceFound[i].piece&COLOR_MASK)==QUEEN)||((pieceFound[i].piece&COLOR_MASK)==ROOK))
      {


      //alert('<B>'+getPieceName(pieceFound[i].piece)+pieceFound[i].row+pieceFound[i].col+'</b><BR>');
      acord.nrow=testRow;
      acord.ncol=testCol;
      acord.row=pieceFound[i].row;
      acord.col=pieceFound[i].col;
      return false;
      }

      if((i==1||i==5)&&(!pawntest)&&((pieceFound[i].dist==1)||(pieceFound[i].dist==2))&&((pieceFound[i].piece&COLOR_MASK)==PAWN))
      {
      if(pieceFound[i].dist==1)
      {
       if((ennemyColor==WHITE)&&(i==1))
       {
        acord.nrow=testRow;
        acord.ncol=testCol;
        acord.row=pieceFound[i].row;
        acord.col=pieceFound[i].col;
         return false;
       }
       else
       {
        if((ennemyColor==BLACK)&&(i==5))
        {
         acord.nrow=testRow;
         acord.ncol=testCol;
         acord.row=pieceFound[i].row;
         acord.col=pieceFound[i].col;
           return false;
        }
       }
      }
      if(pieceFound[i].dist==2)
      {
       if((ennemyColor==WHITE)&&(i==1)&&(testRow-2==1))
       {
        acord.nrow=testRow;
        acord.ncol=testCol;
        acord.row=pieceFound[i].row;
        acord.col=pieceFound[i].col;
          return false;
       }
       else
       {
        if((ennemyColor==BLACK)&&(i==5)&&(testRow+2==6))
        {
         acord.nrow=testRow;
         acord.ncol=testCol;
         acord.row=pieceFound[i].row;
         acord.col=pieceFound[i].col;
           return false;
        }
       }
      }
     }

     if((pieceFound[i].dist==1)&&((pieceFound[i].piece&COLOR_MASK)==KING))
      {
       var tmpPiece=board[testRow][testCol];
       board[testRow][testCol]=pieceFound[i].piece;
       var kingRow=0;
       var kingCol=0;
      switch(i)
      {
         case 0: kingRow = testRow-1; kingCol=testCol-1;                                    break;
       case 1: kingRow = testRow-1; kingCol=testCol;                                        break;
         case 2: kingRow = testRow-1; kingCol=testCol+1;                                    break;
         case 3: kingRow = testRow;   kingCol=testCol+1;                                    break;
         case 4: kingRow = testRow+1; kingCol=testCol+1;                                    break;
        case 5: kingRow = testRow+1; kingCol=testCol;                                       break;
        case 6: kingRow = testRow+1; kingCol=testCol-1;                                 break;
        case 7: kingRow = testRow;   kingCol=testCol-1;                                 break;
       }
      board[kingRow][kingCol]=0;
      var tmpIsSafe=isInCheck(getOtherColor(testColor));
      board[kingRow][kingCol]=pieceFound[i].piece;
       board[testRow][testCol]=tmpPiece;
       if(!tmpIsSafe)
      {
       acord.nrow=testRow;
       acord.ncol=testCol;
       acord.row=pieceFound[i].row;
       acord.col=pieceFound[i].col;
        return false;
      }
      }
     break;
    }
  }
 }
 acord={ row:-1, col:-1, nrow:-1, ncol:-1};

 return true;
}

    function isValidMoveKing(from_row, from_col, to_row, to_col, tmpColor)
    {
        /* The king does not move to a square that is attacked by an enemy piece during the castling move */

        if (!isSafe(to_row, to_col, tmpColor,true))
        {
            if (DEBUG)
                alert("king -> destination not safe!");

            errMsg = "You may not move your king across an attacked square";
            return false;
        }

        if (isInBoard(to_row,to_col))
            if (board[to_row][to_col] == getPieceCode(getOtherColor(tmpColor), "king"))
            {
                errMsg = "Impossible to move next to enemy king.";
                return false;
            }
        if (isInBoard(to_row+1,to_col))
            if (board[to_row+1][to_col] == getPieceCode(getOtherColor(tmpColor), "king"))
            {
                errMsg = "Impossible to move next two to enemy king";
                return false;
            }

        if (isInBoard(to_row-1,to_col))
            if (board[to_row-1][to_col] == getPieceCode(getOtherColor(tmpColor), "king"))
            {
                errMsg = "Impossible to move to enemy king.";
                return false;
            }
        if (isInBoard(to_row,to_col+1))
            if (board[to_row][to_col+1] == getPieceCode(getOtherColor(tmpColor), "king"))
            {
                errMsg = "Impossible mnoveo.";
                return false;
            }
        if (isInBoard(to_row,to_col-1))
            if (board[to_row][to_col-1] == getPieceCode(getOtherColor(tmpColor), "king"))
            {
                errMsg = "Impossable move cmon get real.";
                return false;
            }

        if (isInBoard(to_row+1,to_col+1))
            if (board[to_row+1][to_col+1] == getPieceCode(getOtherColor(tmpColor), "king"))
            {
                errMsg = "Imposdebug - why is this in portuguese?.";
                return false;
            }
        if (isInBoard(to_row-1,to_col-1))
            if (board[to_row-1][to_col-1] == getPieceCode(getOtherColor(tmpColor), "king"))
            {
                errMsg = "Impos momre error meessage";
                return false;
            }
        if (isInBoard(to_row+1,to_col-1))
            if (board[to_row+1][to_col-1] == getPieceCode(getOtherColor(tmpColor), "king"))
            {
                errMsg = "Imposs are you stupid or what?.";
                return false;
            }
        if (isInBoard(to_row-1,to_col+1))
            if (board[to_row-1][to_col+1] == getPieceCode(getOtherColor(tmpColor), "king"))
            {
                errMsg = "ImpoWhat do you think you are doing?o.";
                return false;
            }

        /* NORMAL MOVE: */
        if ((Math.abs(to_row - from_row) <= 1) && (Math.abs(to_col - from_col) <= 1))
        {
            if (DEBUG)
                alert("king -> normal move");

            return true;
        }
        /* CASTLING: */
        else if ((from_row == to_row) && (from_col == 4) && (Math.abs(to_col - from_col) == 2))
        {
            /*
            The following conditions must be met:
                * The King and rook must occupy the same rank (or row).
                * The king that makes the castling move has not yet moved in the game.
            */
            if (DEBUG)
                alert("isValidMoveKing -> Castling");

            var rookCol = 0;
            if (to_col - from_col == 2)
                rookCol = 7;

            /* ToDo: chessHistory check can probably be cut in half by only checking every other move (ie: current color's moves) */
            for (i = 0; i <= numMoves; i++)
            {
                /* if king has already moved */
                if ((chessHistory[i][FROMROW] == from_row) && (chessHistory[i][CURPIECE] == "king"))
                {
                    errMsg = "Can only castle if king has not moved yet.";
                    return false;
                }
                /* if rook has already moved */
                else if ((chessHistory[i][FROMROW] == from_row) && (chessHistory[i][FROMCOL] == rookCol))
                {
                    errMsg = "Can only castle if rook has not moved yet.";
                    return false;
                }
            }

            /*
                * All squares between the rook and king before the castling move are empty.
            */
            tmpStep = (to_col - from_col) / 2;
            for (i = 4 + tmpStep; i != rookCol; i += tmpStep)
                if (board[from_row][i] != 0)
                {
                    if (DEBUG)
                        alert("king -> castling -> square not empty");

                    errMsg = "Can only castle if there are no pieces between the rook and the king";
                    return false;
                }

            /*
                * The king is not in check.
                * The king does not move over a square that is attacked by an enemy piece during the castling move
            */

            /* NOTE: the king's destination has already been checked, so */
            /* all that's left is it's initial position and it's final one */
            if (isSafe(from_row, from_col, tmpColor,true)
                    && isSafe(from_row, from_col + tmpStep, tmpColor,true))
            {
                if (DEBUG)
                    alert("king -> castling -> VALID!");

                return true;
            }
            else
            {
                if (DEBUG)
                    alert("king -> castling -> moving over attacked square");

                errMsg = "When castling, the king cannot move over a square that is attacked by an ennemy piece";
                return false;
            }
        }
        /* INVALID MOVE */
        else
        {
            if (DEBUG)
                alert("king -> completely invalid move\nfrom " + from_row + ", " + from_col + "\nto " + to_row + ", " + to_col);
            errMsg = "Kings cannot move like that.";
            return false;
        }

        if (DEBUG)
            alert("king -> castling -> unknown error");
    }

    /* checks whether a pawn is making a valid move */
    function isValidMovePawn(from_row, from_col, to_row, to_col, tmpDir)
    {
        if (((to_row - from_row)/Math.abs(to_row - from_row)) != tmpDir)
        {
            errMsg = "Pawns cannot move backwards, only forward.";
            return false;
        }

        /* standard move */
        if ((tmpDir * (to_row - from_row) == 1) && (to_col == from_col) && (board[to_row][to_col] == 0))
            return true;
        /* first move double jump - white */
        if ((tmpDir == 1) && (from_row == 1) && (to_row == 3) && (to_col == from_col) && (board[2][to_col] == 0) && (board[3][to_col] == 0))
            return true;
        /* first move double jump - black */
        if ((tmpDir == -1) && (from_row == 6) && (to_row == 4) && (to_col == from_col) && (board[5][to_col] == 0) && (board[4][to_col] == 0))
            return true;
        /* standard eating */
        else if ((tmpDir * (to_row - from_row) == 1) && (Math.abs(to_col - from_col) == 1) && (board[to_row][to_col] != 0))
            return true;
        /* en passant - white */
        else if ((tmpDir == 1) && (from_row == 4) && (to_row == 5) && (board[4][to_col] == (PAWN | BLACK)))
        {
            /* can only move en passant if last move is the one where the white pawn moved up two */
            if ((chessHistory[numMoves][FROMROW] == 6) && (chessHistory[numMoves][TOROW] == 4) && (chessHistory[numMoves][TOCOL] == to_col))
                return true;
            else
            {
                errMsg = "Pawns can only move en passant immediately after an opponent played his pawn.";
                return false;
            }
        }
        /* en passant - black */
        else if ((tmpDir == -1) && (from_row == 3) && (to_row == 2) && (board[3][to_col] == PAWN))
        {
            /* can only move en passant if last move is the one where the black pawn moved up two */
            if ((chessHistory[numMoves][FROMROW] == 1) && (chessHistory[numMoves][TOROW] == 3) && (chessHistory[numMoves][TOCOL] == to_col))
                return true;
            else
            {
                errMsg = "Pawns can only move en passant immediately after an opponent played his pawn.";
                return false;
            }
        }
        else
        {
            errMsg = "Pawns cannot move like that.";
            return false;
        }
    }

    /* checks wether a knight is making a valid move */
    function isValidMoveKnight(from_row, from_col, to_row, to_col)
    {
        errMsg = "Knights cannot move like that.";
        if (Math.abs(to_row - from_row) == 2)
        {
            if (Math.abs(to_col - from_col) == 1)
                return true;
            else
                return false;
        }
        else if (Math.abs(to_row - from_row) == 1)
        {
            if (Math.abs(to_col - from_col) == 2)
                return true;
            else
                return false;
        }
        else
        {
            return false;
        }
    }

    /* checks whether a bishop is making a valid move */
    function isValidMoveBishop(from_row, from_col, to_row, to_col)
    {
        if (Math.abs(to_row - from_row) == Math.abs(to_col - from_col))
        {
            if (to_row > from_row)
            {
                if (to_col > from_col)
                {
                    for (i = 1; i < (to_row - from_row); i++)
                        if (board[from_row + i][from_col + i] != 0)
                        {
                            errMsg = "Bishops cannot jump over other pieces.";
                            return false;
                        }
                }
                else
                {
                    for (i = 1; i < (to_row - from_row); i++)
                        if (board[from_row + i][from_col - i] != 0)
                        {
                            errMsg = "Bishops cannot jump over other pieces.";
                            return false;
                        }
                }

                return true;
            }
            else
            {
                if (to_col > from_col)
                {
                    for (i = 1; i < (from_row - to_row); i++)
                        if (board[from_row - i][from_col + i] != 0)
                        {
                            errMsg = "Bishops cannot jump over other pieces.";
                            return false;
                        }
                }
                else
                {
                    for (i = 1; i < (from_row - to_row); i++)
                        if (board[from_row - i][from_col - i] != 0)
                        {
                            errMsg = "Bishops cannot jump over other pieces.";
                            return false;
                        }
                }

                return true;
            }
        }
        else
        {
            errMsg = "Bishops cannot move like that.";
            return false;
        }
    }

    /* checks wether a rook is making a valid move */
    function isValidMoveRook(from_row, from_col, to_row, to_col)
    {
        if (to_row == from_row)
        {
            if (to_col > from_col)
            {
                for (i = (from_col + 1); i < to_col; i++)
                    if (board[from_row][i] != 0)
                    {
                        errMsg = "Rooks cannot jump over other pieces.";
                        return false;
                    }
            }
            else
            {
                for (i = (to_col + 1); i < from_col; i++)
                    if (board[from_row][i] != 0)
                    {
                        errMsg = "Rooks cannot jump over other pieces.";
                        return false;
                    }

            }

            return true;
        }
        else if (to_col == from_col)
        {
            if (to_row > from_row)
            {
                for (i = (from_row + 1); i < to_row; i++)
                    if (board[i][from_col] != 0)
                    {
                        errMsg = "Rooks cannot jump over other pieces.";
                        return false;
                    }
            }
            else
            {
                for (i = (to_row + 1); i < from_row; i++)
                    if (board[i][from_col] != 0)
                    {
                        errMsg = "Rooks cannot jump over other pieces.";
                        return false;
                    }

            }

            return true;
        }
        else
        {
            errMsg = "Rooks cannot move like that.";
            return false;
        }
    }

    /* this function checks whether a queen is making a valid move */
    function isValidMoveQueen(from_row, from_col, to_row, to_col)
    {
        if (isValidMoveRook(from_row, from_col, to_row, to_col) || isValidMoveBishop(from_row, from_col, to_row, to_col))
            return true;

        if (errMsg.search("jump") == -1)
            errMsg = "Queens cannot move like that.";
        else
            errMsg = "Queens cannot jump over other pieces.";

        return false;
    }

function isKing(i,j,curColor)
{
 var kingRow=i;
 var kingCol=j;
 var ennemyKing=getPieceCode(getOtherColor(curColor),"king");
 if(isInBoard(kingRow,kingCol+1))
 {
  if(board[kingRow][kingCol+1]==ennemyKing)
  {
   return true;
  }
 }
 if(isInBoard(kingRow,kingCol-1))
 {
  if(board[kingRow][kingCol-1]==ennemyKing)
  {
   return true;
  }
 }
 if(isInBoard(kingRow+1,kingCol))
 {
  if(board[kingRow+1][kingCol]==ennemyKing)
  {
   return true;
  }
 }
 if(isInBoard(kingRow-1,kingCol))
 {
  if(board[kingRow-1][kingCol]==ennemyKing)
  {
   return true;
  }
 }
 if(isInBoard(kingRow+1,kingCol+1))
 {
  if(board[kingRow+1][kingCol+1]==ennemyKing)
  {
   return true;
  }
 }
 if(isInBoard(kingRow+1,kingCol-1))
 {
  if(board[kingRow+1][kingCol-1]==ennemyKing)
  {
   return true;
  }
 }
 if(isInBoard(kingRow-1,kingCol+1))
 {
  if(board[kingRow-1][kingCol+1]==ennemyKing)
  {
   return true;
  }
 }
 if(isInBoard(kingRow-1,kingCol-1))
 {
  if(board[kingRow-1][kingCol-1]==ennemyKing)
  {
   return true;
  }
 }
 return false;
}

function is_in_check(curColor)
{
 var targetKing=getPieceCode(curColor,"king");
 var safe1=false;
 var safe2=false;

 for(var i=0;i<8;i++)
 {
  for(var j=0;j<8;j++)
  {
   if(board[i][j]==targetKing)
   {
    safe1=!isSafe(i,j,curColor,true);
    safe2=isKing(i,j,curColor);
    if(safe1||safe2)
    {
     return true;
    }
    else
    {
     return false;
    }
   }
  }
 }
 errMsg = "CRITICAL ERROR: KING MISSING!"
 return false;
}

    function isValidMove()
    {
        var from_row = parseInt(document.gamedata.from_row.value);
        var from_col = parseInt(document.gamedata.from_col.value);
        var to_row = parseInt(document.gamedata.to_row.value);
        var to_col = parseInt(document.gamedata.to_col.value);

        var tmpDir = 1;
        var curColor = "white";
        if (board[from_row][from_col] & BLACK)
        {
            tmpDir = -1;
            curColor = "black";
        }

        var isValid = true;
        switch(board[from_row][from_col] & COLOR_MASK)
        {
            case PAWN:
                isValid = isValidMovePawn(from_row, from_col, to_row, to_col, tmpDir);
                break;
            case KNIGHT:
                isValid = isValidMoveKnight(from_row, from_col, to_row, to_col);
                break;
            case BISHOP:
                isValid = isValidMoveBishop(from_row, from_col, to_row, to_col);
                break;
            case ROOK:
                isValid = isValidMoveRook(from_row, from_col, to_row, to_col);
                break;
            case QUEEN:
                isValid = isValidMoveQueen(from_row, from_col, to_row, to_col);
                break;
            case KING:
                if (DEBUG)
                    alert("isValidMove -> King");

                isValid = isValidMoveKing(from_row, from_col, to_row, to_col, curColor);

                if (DEBUG)
                    alert("isValidMove -> King -> isValid = " + isValid);
                break;
            default:    /* ie: not implemented yet */
                if (DEBUG)
                    alert("unknown game piece");

                isValid = true;
        }

        /* now that we know the move itself is valid, let's make sure we're not moving into check */
        /* NOTE: we don't need to check for the king since it's covered by isValidMoveKing() */

        if ((board[from_row][from_col] & COLOR_MASK) != KING)
        {
            if (DEBUG)
                alert("isValidMove -> are we moving into check?" + "moving from" +from_row +":"+from_col + "to" + to_row +":" + to_col + "::");

            /* save current board destination */
            var tmpPiece = board[to_row][to_col];

            /* update board with move (client-side) */
            board[to_row][to_col] = board[from_row][from_col];
            board[from_row][from_col] = 0;

            /* are we in check now? */
            if (is_in_check(curColor))
            {
                if (DEBUG)
                    alert("isValidMove -> moving into check -> CHECK!");

                /* if so, invalid move */
                errMsg = "Cannot move into check.";
                isValid = false;
            }

            /* restore board to previous state */
            board[from_row][from_col] = board[to_row][to_col];
            board[to_row][to_col] = tmpPiece;
        }

        if (DEBUG)
            alert("isValidMove returns " + isValid);

        return isValid;
    }

