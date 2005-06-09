int curDir=-1;

bool getNextAttacker(int targetRow, int targetCol, char targetColor[], ac *attackerCoords)
{
 char *attackerColor=getOtherColor(targetColor);
 while(curDir<=15)
 {
  int rowStep, colStep;
  curDir++;
  switch(curDir)
  {
	case 0:
	 if(isInBoard(targetRow+2,targetCol-1))
    {
	  if(board[targetRow+2][targetCol-1]==getPieceCode(attackerColor,"knight"))
	  {
      attackerCoords->row=targetRow+2;
      attackerCoords->col=targetCol-1;
      return true;
     }
    }
	break;
	case 1:
	 if(isInBoard(targetRow+2,targetCol+1))
    {
     if(board[targetRow+2][targetCol+1]==getPieceCode(attackerColor,"knight"))
	  {
      attackerCoords->row=targetRow+2;
      attackerCoords->col=targetCol+1;
      return true;
	  }
    }
	break;
	case 2:
	 if(isInBoard(targetRow+1,targetCol-2))
    {
	  if(board[targetRow+1][targetCol-2]==getPieceCode(attackerColor,"knight"))
	  {
		attackerCoords->row=targetRow+1;
		attackerCoords->col=targetCol-2;
		return true;
     }
    }
	break;
	case 3:
	 if(isInBoard(targetRow+1,targetCol+2))
    {
	  if(board[targetRow+1][targetCol+2]==getPieceCode(attackerColor,"knight"))
	  {
		attackerCoords->row=targetRow+1;
		attackerCoords->col=targetCol+2;
	  	return true;
	  }
    }
	break;
	case 4:
	 if(isInBoard(targetRow-1,targetCol-2))
    {
	  if(board[targetRow-1][targetCol-2]==getPieceCode(attackerColor,"knight"))
     {
		attackerCoords->row=targetRow-1;
		attackerCoords->col=targetCol-2;
      return true;
	  }
	 }
   break;
	case 5:
	 if(isInBoard(targetRow-1,targetCol+2))
    {
	  if(board[targetRow-1][targetCol+2]==getPieceCode(attackerColor,"knight"))
	  {
		attackerCoords->row=targetRow-1;
		attackerCoords->col=targetCol+2;
		return true;
	  }
    }
	break;
	case 6:
	 if(isInBoard(targetRow-2,targetCol-1))
    {
	  if(board[targetRow-2][targetCol-1]==getPieceCode(attackerColor,"knight"))
     {
		attackerCoords->row=targetRow-2;
		attackerCoords->col=targetCol-1;
		return true;
	  }
    }
	break;
	case 7:
	 if(isInBoard(targetRow-2,targetCol+1))
    {
	  if(board[targetRow-2][targetCol+1]==getPieceCode(attackerColor,"knight"))
     {
		attackerCoords->row=targetRow-2;
		attackerCoords->col=targetCol+1;
		return true;
	  }
    }
	break;
	case 8:
	 rowStep=  1;
	 colStep= -1;
	break;
	case 9:
	 rowStep=  1;
	 colStep=  0;
	break;
	case 10:
	 rowStep=  1;
	 colStep=  1;
	break;
	case 11:
	 rowStep=  0;
	 colStep=  1;
	break;
	case 12:
	 rowStep= -1;
	 colStep=  1;
	break;
	case 13:
	 rowStep= -1;
	 colStep=  0;
	break;
	case 14:
	 rowStep= -1;
	 colStep= -1;
	break;
	case 15:
	 rowStep=  0;
	 colStep= -1;
	break;
  }
  if(curDir>7)
  {
	bool attackerFound=false;
	int i=1;
	while(isInBoard(targetRow+(i*rowStep),targetCol+(i*colStep))&&!attackerFound)
	{
	 if(board[targetRow+(i*rowStep)][targetCol+(i*colStep)]!=0)
	 {
	  attackerFound=true;
	  if(!strcmp(attackerColor,getPieceColor(board[targetRow+(i*rowStep)][targetCol+(i*colStep)])))
     {
		if(isAttacking(board[targetRow+(i*rowStep)][targetCol+(i*colStep)],targetRow+(i*rowStep),targetCol+(i*colStep),attackerColor,targetRow,targetCol))
		{
		 attackerCoords->row=targetRow+(i*rowStep);
		 attackerCoords->col=targetCol+(i*colStep);
       return true;
		}
     }
	 }
	 i++;
	}
  }
 }
 return false;
}

bool isInBoard(int row, int col)
{
 if((row>=0)&&(row<=7)&&(col>=0)&&(col<=7)) return true;
 else                                       return false;
}

bool isAttacking(int attackerPiece, int attackerRow, int attackerCol, char attackerColor[], int targetRow, int targetCol)
{
 int rowDiff=abs(attackerRow-targetRow);
 int colDiff=abs(attackerCol-targetCol);
 int forwardDir=-1;
 switch(attackerPiece & COLOR_MASK)
 {
  case PAWN:
  	forwardDir = -1;
	if(!strcmp(attackerColor,"white"))
   {
	 forwardDir=1;
   }
	if((colDiff==1)&&((targetRow-attackerRow)==forwardDir))
   {
	 return true;
   }
  break;
  case ROOK:
	if((rowDiff==0)||(colDiff==0))
   {
	 return true;
   }
  break;
  case KNIGHT:
	if(((rowDiff==2)&&(colDiff==1))||((rowDiff==1)&&(colDiff==2)))
   {
	 return true;
   }
  break;
  case BISHOP:
	if(rowDiff==colDiff)
   {
	 return true;
   }
  break;
  case QUEEN:
	if((rowDiff==0)||(colDiff==0)||(rowDiff==colDiff))
   {
    return true;
   }
  break;
  case KING:
	if((rowDiff<=1)&&(colDiff<=1))
   {
	 return true;
   }
  break;
 }
 return false;
}

bool canBlockAttacker(int attackerPiece,int attackerRow,int attackerCol,char attackerColor[],int targetRow,int targetCol)
{
 int tmpAttackerPiece=attackerPiece&COLOR_MASK;
 ac2 acord;
 if(tmpAttackerPiece==KNIGHT)
 {
  return false;
 }
 int rowDiff=attackerRow-targetRow;
 int colDiff=attackerCol-targetCol;
 int rowStep=0;
 if(rowDiff!=0)
 {
  rowStep=rowDiff/abs(rowDiff);
 }
 int colStep=0;
 if(colDiff!=0)
 {
  colStep=colDiff/abs(colDiff);
 }
 int numSteps=max(abs(rowDiff),abs(colDiff));
 int ennemyDir=1;
 int ennemyPawn=BLACK|PAWN;
 char *tmp_piecename;
 tmp_piecename=getPieceName(attackerPiece);
 int attColor;
 attColor=getPieceCode(attackerColor,tmp_piecename);
 int friendlyPawn=attColor|PAWN;
 if(!strcmp(attackerColor,"black"))
 {
  ennemyPawn=WHITE|PAWN;
  ennemyDir=-1;
 }
 bool tmpPawnFound1;
 bool tmpPawnFound2;
 for(int i=1;i<numSteps;i++)
 {
  tmpPawnFound1=false;
  tmpPawnFound2=false;
  if(isInBoard(targetRow+(i*rowStep)+ennemyDir,targetCol+(i*colStep)-1))
  {
   if(board[targetRow+(i*rowStep)+ennemyDir][targetCol+(i*colStep)-1]==ennemyPawn)
	{
    board[targetRow+(i*rowStep)+ennemyDir][targetCol+(i*colStep)-1]=friendlyPawn;
	 tmpPawnFound1=true;
   }
  }
  if(isInBoard(targetRow+(i*rowStep)+ennemyDir,targetCol+(i*colStep)+1))
  {
   if(board[targetRow+(i*rowStep)+ennemyDir][targetCol+(i*colStep)+1]==ennemyPawn)
   {
    board[targetRow+(i*rowStep)+ennemyDir][targetCol+(i*colStep)+1]=friendlyPawn;
    tmpPawnFound2=true;
   }
  }
  if(
	  (
      isInBoard(targetRow+(i*rowStep)+ennemyDir,targetCol+(i*colStep)-1)&&
	   isInBoard(targetRow+(i*rowStep)+ennemyDir,targetCol+(i*colStep)+1)&&
	   isInBoard(targetRow+(i*rowStep)+ennemyDir,targetCol+(i*colStep)  )
     )
    )
  {
   if(
      (board[targetRow+(i*rowStep)+ennemyDir][targetCol+(i*colStep)]==ennemyPawn)||
	 	(!isSafe(targetRow+(i*rowStep),targetCol+(i*colStep),attackerColor,true,&acord))
     )
   {
    if(isInBoard(targetRow+(i*rowStep)+ennemyDir,targetCol+(i*colStep)-1))
    {
     if(tmpPawnFound1)
     {
      board[targetRow+(i*rowStep)+ennemyDir][targetCol+(i*colStep)-1]=ennemyPawn;
     }
    }
    if(isInBoard(targetRow+(i*rowStep)+ennemyDir,targetCol+(i*colStep)+1))
    {
     if(tmpPawnFound2)
     {
      board[targetRow+(i*rowStep)+ennemyDir][targetCol+(i*colStep)+1]=ennemyPawn;
     }
    }
    return true;
   }
  }
  if(isInBoard(targetRow+(i*rowStep)+ennemyDir,targetCol+(i*colStep)-1))
  {
   if(tmpPawnFound1)
   {
    board[targetRow+(i*rowStep)+ennemyDir][targetCol+(i*colStep)-1]=ennemyPawn;
   }
  }
  if(isInBoard(targetRow+(i*rowStep)+ennemyDir,targetCol+(i*colStep)+1))
  {
   if(tmpPawnFound2)
   {
    board[targetRow+(i*rowStep)+ennemyDir][targetCol+(i*colStep)+1]=ennemyPawn;
   }
  }
 }
 return false;
}

int getColorCode(char color[])
{
 if(!strcmp(color,"white"))
 {
  return WHITE;
 }
 return BLACK;
}

int getPieceColorCode(int piece)
{
 if(piece)
 {
  if(BLACK&piece)
  {
   return BLACK;
  }
  return WHITE;
 }
 return(-1);
}

bool isCheckMate(char curColor[])
{
 ac2 acord;
 int kingRow=0;
 int kingCol=0;
 int targetKing=getPieceCode(curColor,"king");
 for(int i=0;i<8;i++)
 {
  for(int j=0; j<8; j++)
  {
   if(board[i][j]==targetKing)
   {
    kingRow=i;
    kingCol=j;
   }
  }
 }
 board[kingRow][kingCol]=0;
 for(int i=-1;i<=1;i++)
 {
  for(int j=-1;j<=1;j++)
  {
  	if(((i!=0)||(j!=0))&&isInBoard(kingRow+i,kingCol+j))
   {
	 if((board[kingRow+i][kingCol+j]==0)&&(isSafe(kingRow+i,kingCol+j,curColor,true,&acord)))
    {
	  board[kingRow][kingCol]=targetKing;
	  return false;
	 }
   }
  }
 }
 board[kingRow][kingCol]=targetKing;
 char *attackerColor=getOtherColor(curColor);
 ac attackerCoords; attackerCoords.row=0; attackerCoords.col=0;
 int attackerRow;
 int attackerCol;
 bool canBeCaptured;
 bool canBeBlocked;
 while(getNextAttacker(kingRow,kingCol,curColor,&attackerCoords))
 {
  attackerRow=attackerCoords.row;
  attackerCol=attackerCoords.col;
  canBeCaptured=!isSafe(attackerRow,attackerCol,attackerColor,true,&acord);
  board[kingRow][kingCol]=0;
  canBeBlocked=false;
  if(canBlockAttacker(board[attackerRow][attackerCol],attackerRow,attackerCol,attackerColor,kingRow,kingCol))
  {
	canBeBlocked=true;
  }
  board[kingRow][kingCol]=targetKing;
  if(!canBeCaptured&&!canBeBlocked)
  {
   return true;
  }
 }
 return false;
}

bool canBlockWay(int attacker, int attackerRow, int attackerCol, char attackerColor[], int kingRow, int kingCol)
{
 bool is_safe;
 int row, auxRow=-1;
 int col, auxCol=-1;
 int attackerPawn, ix, iy, tpR, tpC, tpK;
 is_safe=false;
 ac2 acord;
 char *noAttackerColor=getOtherColor(attackerColor);
 attackerPawn=getPieceCode(attackerColor,"pawn");
 if(attacker==getPieceCode(attackerColor,"knight"))
 {
  return false;
 }
 row=attackerRow-kingRow;
 col=attackerCol-kingCol;
 ix=0;
 iy=0;
 while((!is_safe)&&(auxRow!=attackerRow)||(auxCol!=attackerCol))
 {
  if(row>0){ ix++; }
  if(col>0){ iy++; }
  if(row==0){ ix=0; }
  if(col==0){ iy=0; }
  if(row<0){ ix--; }
  if(col<0){ iy--; }
  auxRow=kingRow+ix;
  auxCol=kingCol+iy;
  if(isInBoard(auxRow,auxCol))
  {
   if(board[auxRow][auxCol]==EMPTY)
   {
    board[auxRow][auxCol]=attackerPawn;
    if(!isSafe(auxRow,auxCol,attackerColor,false,&acord))
    {
     if((acord.row>-1)&&(acord.col>-1))
     {
      tpR=acord.row;
      tpC=acord.col;
      board[auxRow][auxCol]=board[tpR][tpC];
      board[tpR][tpC]=EMPTY;
      tpK=board[kingRow][kingCol];
      board[kingRow][kingCol]=getPieceCode(noAttackerColor,"king");
      if(isSafe(kingRow,kingCol,noAttackerColor,false,&acord))
      {
       is_safe=true;
      }
      board[kingRow][kingCol]=tpK;
      board[tpR][tpC]=board[auxRow][auxCol];
     }
     else
     {
      is_safe=true;
     }
    }
    board[auxRow][auxCol]=EMPTY;
   }
  }
  else
  {
   auxRow=attackerRow;
   auxCol=attackerCol;
  }
 }
 return is_safe;
}

bool canBlockAttack(char curColor[])
{
 ac2 acord;
 int kingRow=0;
 int kingCol=0;
 int targetKing=getPieceCode(curColor,"king");
 int auxAttack=targetKing;
 for(int i=0;i<8;i++)
 {
  for(int j=0;j<8;j++)
  {
   if(board[i][j]==targetKing)
   {
    kingRow=i;
    kingCol=j;
   }
  }
 }
 char *attackerColor=getOtherColor(curColor);
 ac attackerCoords; attackerCoords.row=0; attackerCoords.col=0;
 int attacker;
 int attackerRow;
 int attackerCol;
 int tpR;
 int tpC;
 bool canBeCaptured;
 bool canBeBlocked;
 while(getNextAttacker(kingRow,kingCol,curColor,&attackerCoords))
 {
  attackerRow=attackerCoords.row;
  attackerCol=attackerCoords.col;
  canBeCaptured=!isSafe(attackerRow,attackerCol,attackerColor,true,&acord);
  if(canBeCaptured)
  {
   if((acord.row>-1)&&(acord.col>-1))
   {
    tpR=acord.row;
    tpC=acord.col;
    attacker=board[attackerRow][attackerCol];
    board[attackerRow][attackerCol]=board[tpR][tpC];
    board[tpR][tpC]=EMPTY;
    if(!isSafe(kingRow,kingCol,curColor,false,&acord))
    {
     canBeCaptured=false;
    }
    board[tpR][tpC]=board[attackerRow][attackerCol];
    board[attackerRow][attackerCol]=attacker;
   }
   if((canKing(kingRow,kingCol,targetKing,curColor,"capture",attackerRow,attackerCol))&&((kingRow==acord.row)&&(kingCol==acord.col)))
   {
    board[kingRow][kingCol]=EMPTY;
    board[attackerRow][attackerCol]=targetKing;
    canBeCaptured=isSafe(attackerRow,attackerCol,curColor,true,&acord);
    board[attackerRow][attackerCol]=attacker;
   }
  }
  board[kingRow][kingCol]=getPieceCode(attackerColor,"pawn");
  canBeBlocked=false;
  if(canBlockWay(board[attackerRow][attackerCol],attackerRow,attackerCol,attackerColor,kingRow,kingCol))
  {
   canBeBlocked=true;
  }
  board[kingRow][kingCol]=targetKing;
  if(canBeCaptured||canBeBlocked)
  {
   return true;
  }
 }
 return false;
}

bool canKing(int kingRow, int kingCol, int targetKing, char colorSide[], char type[], int captureRow, int captureCol)
{
  int code=0;
  int targetPiece=EMPTY;
  bool phase1=true;
  bool phase2=true;
  bool phase3=true;
  bool phase4=true;
  bool phase5=true;
  bool phase6=true;
  bool phase7=true;
  bool phase8=true;
  bool captured=false;
  int colorCode=getColorCode(colorSide);
  board[kingRow][kingCol]=EMPTY;
  if(isInBoard(kingRow,kingCol+1))
  {
   if((kingRow==captureRow)&&((kingCol+1)==captureCol)){ captured=true; }
   if(board[kingRow][kingCol+1]==EMPTY)
   {
    board[kingRow][kingCol+1]=targetKing;
    if(!isInCheck(colorSide))
    {
     phase1=false;
    }
    board[kingRow][kingCol+1]=EMPTY;
   }
   else
   {
    if(!(colorCode==getPieceColorCode(board[kingRow][kingCol+1])))
    {
     targetPiece=board[kingRow][kingCol+1];
     board[kingRow][kingCol+1]=targetKing;
     if(!isInCheck(colorSide))
     {
      phase1=false;
     }
     board[kingRow][kingCol+1]=targetPiece;
    }
   }
  }
  if(isInBoard(kingRow,kingCol-1))
  {
   if((kingRow==captureRow)&&((kingCol-1)==captureCol)){ captured=true; }
   if(board[kingRow][kingCol-1]==EMPTY)
   {
    board[kingRow][kingCol-1]=targetKing;
    if(!isInCheck(colorSide))
    {
     phase2=false;
    }
    board[kingRow][kingCol-1]=EMPTY;
   }
   else
   {
    if(!(colorCode==getPieceColorCode(board[kingRow][kingCol-1])))
    {
     targetPiece=board[kingRow][kingCol-1];
     board[kingRow][kingCol-1]=targetKing;
     if(!isInCheck(colorSide))
     {
      phase2=false;
     }
     board[kingRow][kingCol-1]=targetPiece;
    }
   }
  }
  if(isInBoard(kingRow+1,kingCol))
  {
   if(((kingRow+1)==captureRow)&&(kingCol==captureCol)){ captured=true; }
   if(board[kingRow+1][kingCol]==EMPTY)
   {
    board[kingRow+1][kingCol]=targetKing;
    if(!isInCheck(colorSide))
    {
     phase3=false;
    }
    board[kingRow+1][kingCol]=EMPTY;
   }
   else
   {
    if(!(colorCode==getPieceColorCode(board[kingRow+1][kingCol])))
    {
     targetPiece=board[kingRow+1][kingCol];
     board[kingRow+1][kingCol]=targetKing;
     if(!isInCheck(colorSide))
     {
      phase3=false;
     }
     board[kingRow+1][kingCol]=targetPiece;
    }
   }
  }
  if(isInBoard(kingRow-1,kingCol))
  {
   if(((kingRow-1)==captureRow)&&(kingCol==captureCol)){ captured=true; }
   if(board[kingRow-1][kingCol]==EMPTY)
   {
    board[kingRow-1][kingCol]=targetKing;
    if(!isInCheck(colorSide))
    {
     phase4=false;
    }
    board[kingRow-1][kingCol]=EMPTY;
   }
   else
   {
    if(!(colorCode==getPieceColorCode(board[kingRow-1][kingCol])))
    {
     targetPiece=board[kingRow-1][kingCol];
     board[kingRow-1][kingCol]=targetKing;
     if(!isInCheck(colorSide))
     {
      phase4=false;
     }
     board[kingRow-1][kingCol]=targetPiece;
    }
   }
  }
  if(isInBoard(kingRow+1,kingCol+1))
  {
   if(((kingRow+1)==captureRow)&&((kingCol+1)==captureCol)){ captured=true; }
   if(board[kingRow+1][kingCol+1]==EMPTY)
   {
    board[kingRow+1][kingCol+1]=targetKing;
    if(!isInCheck(colorSide))
    {
     phase5=false;
    }
    board[kingRow+1][kingCol+1]=EMPTY;
   }
   else
   {
    if(!(colorCode==getPieceColorCode(board[kingRow+1][kingCol+1])))
    {
     targetPiece=board[kingRow+1][kingCol+1];
     board[kingRow+1][kingCol+1]=targetKing;
     if(!isInCheck(colorSide))
     {
      phase5=false;
     }
     board[kingRow+1][kingCol+1]=targetPiece;
    }
   }
  }
  if(isInBoard(kingRow+1,kingCol-1))
  {
   if(((kingRow+1)==captureRow)&&((kingCol-1)==captureCol)){ captured=true; }
   if(board[kingRow+1][kingCol-1]==EMPTY)
   {
    board[kingRow+1][kingCol-1]=targetKing;
    if(!isInCheck(colorSide))
    {
     phase6=false;
    }
    board[kingRow+1][kingCol-1]=EMPTY;
   }
   else
   {
    if(!(colorCode==getPieceColorCode(board[kingRow+1][kingCol-1])))
    {
     targetPiece=board[kingRow+1][kingCol-1];
     board[kingRow+1][kingCol-1]=targetKing;
     if(!isInCheck(colorSide))
     {
      phase6=false;
     }
     board[kingRow+1][kingCol-1]=targetPiece;
    }
   }
  }
  if(isInBoard(kingRow-1,kingCol+1))
  {
   if(((kingRow-1)==captureRow)&&((kingCol+1)==captureCol)){ captured=true; }
   if(board[kingRow-1][kingCol+1]==EMPTY)
   {
    board[kingRow-1][kingCol+1]=targetKing;
    if(!isInCheck(colorSide))
    {
     phase7=false;
    }
    board[kingRow-1][kingCol+1]=EMPTY;
   }
   else
   {
    if(!(colorCode==getPieceColorCode(board[kingRow-1][kingCol+1])))
    {
     targetPiece=board[kingRow-1][kingCol+1];
     board[kingRow-1][kingCol+1]=targetKing;
     if(!isInCheck(colorSide))
     {
      phase7=false;
     }
     board[kingRow-1][kingCol+1]=targetPiece;
    }
   }
  }
  if(isInBoard(kingRow-1,kingCol-1))
  {
   if(((kingRow-1)==captureRow)&&((kingCol-1)==captureCol)){ captured=true; }
   if(board[kingRow-1][kingCol-1]==EMPTY)
   {
    board[kingRow-1][kingCol-1]=targetKing;
    if(!isInCheck(colorSide))
    {
     phase8=false;
    }
    board[kingRow-1][kingCol-1]=EMPTY;
   }
   else
   {
    if(!(colorCode==getPieceColorCode(board[kingRow-1][kingCol-1])))
    {
     targetPiece=board[kingRow-1][kingCol-1];
     board[kingRow-1][kingCol-1]=targetKing;
     if(!isInCheck(colorSide))
     {
      phase8=false;
     }
     board[kingRow-1][kingCol-1]=targetPiece;
    }
   }
  }
  board[kingRow][kingCol]=targetKing;
  if((!strcmp(type,"move"))&&(phase1&&phase2&&phase3&&phase4&&phase5&&phase6&&phase7&&phase8))
  {
   return(true);
  }
  if((!strcmp(type,"capture"))&&(captured))
  {
   return(true);
  }
 return(false);
}

bool isXequeMate(char colorSide[])
{
 if(isInCheck(colorSide))
 {
  int kingRow=0;
  int kingCol=0;
  int targetKing=getPieceCode(colorSide,"king");
  int targetPiece=EMPTY;
  int colorCode=getColorCode(colorSide);
  bool phase0=true;
  bool phase9=true;
  for(int i=0;i<8;i++)
  {
   for(int j=0;j<8;j++)
   {
    if(board[i][j]==targetKing)
    {
     kingRow=i;
     kingCol=j;
    }
   }
  }
  phase0=canKing(kingRow,kingCol,targetKing,colorSide,"move",0,0);
  phase9=!canBlockAttack(colorSide);
  if(phase0&&phase9)
  {
   return true;
  }
 }
 return false;
}

bool onlyKingRemains(char color[])
{
 int count=0;
 char *pcolor;

 for(int i=0;i<8;i++)
 {
  for(int j=0;j<8;j++)
  {
   pcolor=getPieceColor(board[i][j]);
   if(!strcmp(pcolor,color))
   {
    count++;
   }
  }
 }
 if(count==1)
 {
  return true;
 }
 return false;
}

bool isDraw(char colorSide[])
{
 int kingRow=0;
 int kingCol=0;
 int attackerRow=-1;
 int attackerCol=-1;
 int targetKing=getPieceCode(colorSide,"king");
 char *othercolorSide;
 bool kingremain=false;
 bool otherkingremain=false;
 bool kingmove=false;

 othercolorSide=getOtherColor(colorSide);

 kingremain=onlyKingRemains(colorSide);
 otherkingremain=onlyKingRemains(othercolorSide);
 if(kingremain&&otherkingremain)
 {
  return(true);
 }

 for(int i=0;i<8;i++)
 {
  for(int j=0;j<8;j++)
  {
   if(board[i][j]==targetKing)
   {
    kingRow=i;
    kingCol=j;
   }
  }
 }
 kingmove=!canKing(kingRow,kingCol,targetKing,colorSide,"move",attackerRow,attackerCol);
 if(kingremain&&!kingmove)
 {
  return true;
 }
 return false;
}