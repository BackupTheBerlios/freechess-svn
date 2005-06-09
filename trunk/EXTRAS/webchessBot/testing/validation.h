







bool isSafe(int testRow, int testCol, char testColor[], bool pawntest, ac2 *coords)
{
 coords->nrow=-1;
 coords->ncol=-1;
 coords->row=-1;
 coords->col=-1;
 int ennemyColor = 0;
 if(!strcmp(testColor,"white"))
 {
  ennemyColor=128; /* 1000 0000 */
 }
 if(((testRow-1)>=0)&&((testCol-2)>=0))
 {
  if (board[testRow-1][testCol-2]==(KNIGHT|ennemyColor))
  {
   coords->nrow=testRow;
   coords->ncol=testCol;
   coords->row=testRow-1;
   coords->col=testCol-2;
	return false;
  }
 }
 if(((testRow+1)<8)&&((testCol-2)>=0))
 {
  if(board[testRow+1][testCol-2]==(KNIGHT|ennemyColor))
  {
   coords->nrow=testRow;
   coords->ncol=testCol;
   coords->row=testRow+1;
   coords->col=testCol-2;
	return false;
  }
 }
 if(((testRow-2)>=0)&&((testCol-1)>=0))
 {
  if(board[testRow-2][testCol-1]==(KNIGHT|ennemyColor))
  {
   coords->nrow=testRow;
   coords->ncol=testCol;
   coords->row=testRow-2;
   coords->col=testCol-1;
   return false;
  }
 }
 if(((testRow-2)>=0)&&((testCol+1)<8))
 {
  if(board[testRow-2][testCol+1]==(KNIGHT|ennemyColor))
  {
   coords->nrow=testRow;
   coords->ncol=testCol;
   coords->row=testRow-2;
   coords->col=testCol+1;
	return false;
  }
 }
 if(((testRow-1)>=0)&&((testCol+2)<8))
 {
  if(board[testRow-1][testCol+2]==(KNIGHT|ennemyColor))
  {
   coords->nrow=testRow;
   coords->ncol=testCol;
   coords->row=testRow-1;
   coords->col=testCol+2;
	return false;
  }
 }
 if(((testRow+1)<8)&&((testCol+2)<8))
 {
  if(board[testRow+1][testCol+2]==(KNIGHT|ennemyColor))
  {
   coords->nrow=testRow;
   coords->ncol=testCol;
   coords->row=testRow+1;
   coords->col=testCol+2;
	return false;
  }
 }
 if(((testRow+2)<8)&&((testCol-1)>=0))
 {
  if(board[testRow+2][testCol-1]==(KNIGHT|ennemyColor))
  {
   coords->nrow=testRow;
   coords->ncol=testCol;
   coords->row=testRow+2;
   coords->col=testCol-1;
	return false;
  }
 }
 if(((testRow+2)<8)&&((testCol+1)<8))
 {
  if(board[testRow+2][testCol+1]==(KNIGHT|ennemyColor))
  {
   coords->nrow=testRow;
   coords->ncol=testCol;
   coords->row=testRow+2;
   coords->col=testCol+1;
	return false;
  }
 }
 pf pieceFound[20];
 for(int i=0;i<8;i++)
 {
  pieceFound[i].piece = 0;
  pieceFound[i].dist  = 0;
  pieceFound[i].row  = -1;
  pieceFound[i].col  = -1;
 }
 int fnd=0;
 int kingRow=0;
 int kingCol=0;
 int targetKing=getPieceCode(testColor,"king");
 for(int m=0;m<8;m++)
 {
  for(int n=0;n<8;n++)
  {
   if(board[m][n]==targetKing)
   {
    kingRow=m;
    kingCol=n;
    fnd++;
   }
  }
 }
 if(!fnd) { printf("Rei fora do tabuleiro."); getch(); }
 board[kingRow][kingCol]=0;
 for(int i=1;i<8;i++)
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
  if(((testRow+i)<8)&&((testCol-i)>=0))
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
 for(int i=0;i<8;i++)
 {
  if((pieceFound[i].piece!=0)&&((pieceFound[i].piece&BLACK)==ennemyColor))
  {
	switch(i)
	{
	 case 0:
	 case 2:
	 case 4:
	 case 6:
     if(((pieceFound[i].piece&COLOR_MASK)==QUEEN)||((pieceFound[i].piece & COLOR_MASK) == BISHOP))
	  {
      coords->nrow=testRow;
      coords->ncol=testCol;
		coords->row=pieceFound[i].row;
		coords->col=pieceFound[i].col;
		return false;
	  }
     if((pieceFound[i].dist==1)&&((pieceFound[i].piece&COLOR_MASK)==PAWN)&&(pawntest))
     {
   	if((ennemyColor==WHITE)&&((i==0)||(i==2)))
	   {
       coords->nrow=testRow;
       coords->ncol=testCol;
       coords->row=pieceFound[i].row;
       coords->col=pieceFound[i].col;
		 return false;
      }
		else
      {
       if((ennemyColor==BLACK)&&((i==4)||(i==6)))
       {
        coords->nrow=testRow;
        coords->ncol=testCol;
        coords->row=pieceFound[i].row;
        coords->col=pieceFound[i].col;
		  return false;
       }
      }
	  }
	  if((pieceFound[i].dist==1)&&((pieceFound[i].piece&COLOR_MASK) == KING))
	  {
      int tmpPiece=board[testRow][testCol];
      board[testRow][testCol]=pieceFound[i].piece;
      int kingRow=0;
      int kingCol=0;
      switch(i)
		{
		 case 0: kingRow=testRow-1; kingCol=testCol-1; break;
       case 1: kingRow=testRow-1; kingCol=testCol;   break;
       case 2: kingRow=testRow-1; kingCol=testCol+1; break;
       case 3: kingRow=testRow;   kingCol=testCol+1; break;
       case 4: kingRow=testRow+1; kingCol=testCol+1; break;
		 case 5: kingRow=testRow+1; kingCol=testCol;   break;
		 case 6: kingRow=testRow+1; kingCol=testCol-1; break;
		 case 7: kingRow=testRow;   kingCol=testCol-1; break;
		}
		board[kingRow][kingCol]=0;
		bool tmpIsSafe=isInCheck(getOtherColor(testColor));
		board[kingRow][kingCol]=pieceFound[i].piece;
	   board[testRow][testCol]=tmpPiece;
      if(!tmpIsSafe)
      {
       coords->nrow=testRow;
       coords->ncol=testCol;
       coords->row=pieceFound[i].row;
       coords->col=pieceFound[i].col;
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
      coords->nrow=testRow;
      coords->ncol=testCol;
      coords->row=pieceFound[i].row;
      coords->col=pieceFound[i].col;
		return false;
     }
/*----------------------------------------------------*/
	  if((i==1||i==5)&&(!pawntest)&&((pieceFound[i].dist==1)||(pieceFound[i].dist==2))&&((pieceFound[i].piece&COLOR_MASK)==PAWN))
	  {
      if(pieceFound[i].dist==1)
      {
       if((ennemyColor==WHITE)&&(i==1))
       {
        coords->nrow=testRow;
        coords->ncol=testCol;
        coords->row=pieceFound[i].row;
        coords->col=pieceFound[i].col;
		  return false;
       }
       else
       {
        if((ennemyColor==BLACK)&&(i==5))
        {
         coords->nrow=testRow;
         coords->ncol=testCol;
         coords->row=pieceFound[i].row;
         coords->col=pieceFound[i].col;
			return false;
        }
       }
      }
      if(pieceFound[i].dist==2)
      {
       if((ennemyColor==WHITE)&&(i==1)&&(testRow-2==1))
       {
        coords->nrow=testRow;
        coords->ncol=testCol;
        coords->row=pieceFound[i].row;
        coords->col=pieceFound[i].col;
		  return false;
       }
       else
       {
        if((ennemyColor==BLACK)&&(i==5)&&(testRow+2==6))
        {
         coords->nrow=testRow;
         coords->ncol=testCol;
         coords->row=pieceFound[i].row;
         coords->col=pieceFound[i].col;
			return false;
        }
       }
      }
     }
/*----------------------------------------------------*/
	  if((pieceFound[i].dist==1)&&((pieceFound[i].piece&COLOR_MASK)==KING))
	  {
		int tmpPiece=board[testRow][testCol];
		board[testRow][testCol]=pieceFound[i].piece;
		int kingRow=0;
		int kingCol=0;
		switch(i)
		{
		 case 0: kingRow=testRow-1; kingCol=testCol-1; break;
		 case 1: kingRow=testRow-1; kingCol=testCol;   break;
		 case 2: kingRow=testRow-1; kingCol=testCol+1; break;
		 case 3: kingRow=testRow;   kingCol=testCol+1; break;
		 case 4: kingRow=testRow+1; kingCol=testCol+1; break;
		 case 5: kingRow=testRow+1; kingCol=testCol;   break;
		 case 6: kingRow=testRow+1; kingCol=testCol-1; break;
		 case 7: kingRow=testRow;   kingCol=testCol-1; break;
		}
      board[kingRow][kingCol]=0;
		bool tmpIsSafe=isInCheck(getOtherColor(testColor));
		board[kingRow][kingCol]=pieceFound[i].piece;
		board[testRow][testCol]=tmpPiece;
		if(!tmpIsSafe)
      {
       coords->nrow=testRow;
       coords->ncol=testCol;
       coords->row=pieceFound[i].row;
       coords->col=pieceFound[i].col;
		 return false;
      }
	  }
    break;
   }
  }
 }
 coords->nrow=-1;
 coords->ncol=-1;
 coords->row=-1;
 coords->col=-1;
 return true;
}

















































































































































































































































































































































































bool isKing(int i, int j, char curColor[])
{
 int kingRow=i;
 int kingCol=j;
 int ennemyKing=getPieceCode(getOtherColor(curColor),"king");
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

bool isInCheck(char curColor[])
{
 int targetKing=getPieceCode(curColor,"king");
 bool safe1=false;
 bool safe2=false;
 ac2 acord;
 for(int i=0;i<8;i++)
 {
  for(int j=0;j<8;j++)
  {
	if(board[i][j]==targetKing)
   {
    safe1=!isSafe(i,j,curColor,true,&acord);
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
 printf("CRITICAL ERROR: KING MISSING!");
 return false;
}
