var curDir=-1;
var acord = {row:0, col:0, nrow:0, ncol:0};

function getNextAttacker(targetRow,targetCol,targetColor,attackerCoords)
{
 var attackerColor=getOtherColor(targetColor);
 while(curDir <= 15)
 {
  var rowStep, colStep;
  curDir++;
  switch(curDir)
  {
    case 0:
     if(isInBoard(targetRow+2,targetCol-1))
    {
      if(board[targetRow+2][targetCol-1]==getPieceCode(attackerColor,"knight"))
      {
      attackerCoords.row=targetRow+2;
      attackerCoords.col=targetCol-1;
        return true;
     }
    }
    break;
    case 1:
    if(isInBoard(targetRow+2,targetCol+1))
    {
      if(board[targetRow+2][targetCol+1]==getPieceCode(attackerColor,"knight"))
     {
        attackerCoords.row=targetRow+2;
        attackerCoords.col=targetCol+1;
        return true;
      }
    }
    break;
    case 2:
     if(isInBoard(targetRow+1,targetCol-2))
    {
      if(board[targetRow+1][targetCol-2]==getPieceCode(attackerColor,"knight"))
      {
        attackerCoords.row=targetRow+1;
        attackerCoords.col=targetCol-2;
        return true;
      }
    }
    break;
    case 3:
     if(isInBoard(targetRow+1,targetCol+2))
    {
      if(board[targetRow+1][targetCol+2]==getPieceCode(attackerColor,"knight"))
      {
        attackerCoords.row=targetRow+1;
        attackerCoords.col=targetCol+2;
        return true;
      }
    }
    break;
    case 4:
     if(isInBoard(targetRow-1,targetCol-2))
    {
      if(board[targetRow-1][targetCol-2]==getPieceCode(attackerColor,"knight"))
     {
        attackerCoords.row=targetRow-1;
        attackerCoords.col=targetCol-2;
        return true;
      }
    }
    break;
    case 5:
     if(isInBoard(targetRow-1,targetCol+2))
    {
      if(board[targetRow-1][targetCol+2]==getPieceCode(attackerColor,"knight"))
     {
        attackerCoords.row=targetRow-1;
        attackerCoords.col=targetCol+2;
        return true;
      }
    }
    break;
    case 6:
     if(isInBoard(targetRow-2,targetCol-1))
    {
      if(board[targetRow-2][targetCol-1]==getPieceCode(attackerColor,"knight"))
      {
        attackerCoords.row=targetRow-2;
        attackerCoords.col=targetCol-1;
        return true;
      }
    }
    break;
    case 7:
     if(isInBoard(targetRow-2,targetCol+1))
    {
      if(board[targetRow-2][targetCol+1]==getPieceCode(attackerColor, "knight"))
     {
        attackerCoords.row=targetRow-2;
        attackerCoords.col=targetCol+1;
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
    var attackerFound=false;
    var i=1;
    while(isInBoard(targetRow+(i*rowStep),targetCol+(i*colStep))&&!attackerFound)
    {
     if(board[targetRow+(i*rowStep)][targetCol+(i*colStep)]!=0)
     {
      attackerFound=true;
      if(attackerColor==getPieceColor(board[targetRow+(i*rowStep)][targetCol+(i*colStep)]))
     {
        if(isAttacking(board[targetRow+(i*rowStep)][targetCol+(i*colStep)],targetRow+(i*rowStep),targetCol+(i*colStep),attackerColor,targetRow,targetCol))
      {
         attackerCoords.row=targetRow+(i*rowStep);
         attackerCoords.col=targetCol+(i*colStep);
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

function isInBoard(row, col)
{
 if((row>=0)&&(row<=7)&&(col>=0)&&(col<=7)) return true;
 else                                       return false;
}

function isAttacking(attackerPiece,attackerRow,attackerCol,attackerColor,targetRow,targetCol)
{
 var rowDiff=Math.abs(attackerRow-targetRow);
 var colDiff=Math.abs(attackerCol-targetCol);
 var forwardDir=-1;
 switch(attackerPiece&COLOR_MASK)
 {
  case PAWN:
    forwardDir=-1;
    if(attackerColor=="white")
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

function canBlockAttacker(attackerPiece,attackerRow,attackerCol,attackerColor,targetRow,targetCol)
{
 var tmpAttackerPiece=attackerPiece&COLOR_MASK;

 if (tmpAttackerPiece==KNIGHT)
 {
  return false;
 }
 var rowDiff=attackerRow-targetRow;
 var colDiff=attackerCol-targetCol;
 var rowStep=0;
 if(rowDiff!=0)
 {
  rowStep=rowDiff/Math.abs(rowDiff);
 }
 var colStep=0;
 if(colDiff!=0)
 {
  colStep=colDiff/Math.abs(colDiff);
 }
 var numSteps=Math.max(Math.abs(rowDiff),Math.abs(colDiff));
 var ennemyDir=1;
 var ennemyPawn=BLACK|PAWN;
 var tmp_piecename;
 tmp_piecename=getPieceName(attackerPiece);
 var attColor;
 attColor=getPieceCode(attackerColor,tmp_piecename);
 var friendlyPawn=attColor|PAWN;
 if(attackerColor=="black")
 {
  ennemyPawn=WHITE|PAWN;
  ennemyDir=-1;
 }
 var tmpPawnFound1;
 var tmpPawnFound2;
 for(var i=1;i<numSteps;i++)
 {
  tmpPawnFound1=false;
  tmpPawnFound2=false;
  if(isInBoard(targetRow+(i*rowStep)+ennemyDir,targetCol+(i*colStep)-1))
  {
   if(board[targetRow+(i*rowStep)+ennemyDir][targetCol+(i*colStep)-1]==ennemyPawn)
   {
     board[targetRow+(i*rowStep)+ennemyDir][targetCol+(i*colStep)-1]=friendlyPawn;
    tmpPawnFound1 = true;
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
      isInBoard(targetRow+(i*rowStep)+ennemyDir,targetCol+(i*colStep))
     )
    )
  {
   if(
      (board[targetRow+(i*rowStep)+ennemyDir][targetCol+(i*colStep)]==ennemyPawn)||
        (!isSafe(targetRow+(i*rowStep),targetCol+(i*colStep),attackerColor,true))
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

function getColorCode(color)
{
 if(color=="white")
 {
  return WHITE;
 }
 return BLACK;
}

function getPieceColorCode(piece)
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

function isCheckMate(cur_color)
{

 var kingRow=0;
 var kingCol=0;
 var targetKing=getPieceCode(cur_color,"king");
 for(var i=0;i<8;i++)
 {
  for(var j=0;j<8;j++)
  {
    if(board[i][j]==targetKing)
    {
    kingRow=i;
    kingCol=j;
    }
  }
 }
 board[kingRow][kingCol]=0;
 for(var i=-1;i<=1;i++)
 {
  for(var j=-1;j<=1;j++)
  {
    if(((i!=0)||(j!=0))&&isInBoard(kingRow+i,kingCol+j))
   {
//   if((board[kingRow+i][kingCol+j]==0)&&(isSafe(kingRow+i,kingCol+j,cur_color,true)))
if(isSafe(kingRow+i,kingCol+j,cur_color,true))
    {
      board[kingRow][kingCol]=targetKing;
      return false;
     }
   }
  }
 }
 board[kingRow][kingCol]=targetKing;
 var attackerColor=getOtherColor(cur_color);
 var attackerCoords={row:0, col:0};
 var attackerRow;
 var attackerCol;
 var canBeCaptured;
 var canBeBlocked;

 while(getNextAttacker(kingRow,kingCol,cur_color,attackerCoords))
 {
  attackerRow=attackerCoords.row;
  attackerCol=attackerCoords.col;
  canBeCaptured=!isSafe(attackerRow,attackerCol,attackerColor,true);
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

function canBlockWay(attacker,attackerRow,attackerCol,attackerColor,kingRow,kingCol)
{
 var is_safe;
 var row, auxRow=-1;
 var col, auxCol=-1;
 var attackerPawn, ix, iy, tpR, tpC, tpK;
 is_safe=false;
 var noAttackerColor=getOtherColor(attackerColor);
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

    if(!isSafe(auxRow,auxCol,attackerColor,false))
    {
     if((acord.row>-1)&&(acord.col>-1))
     {
      tpR=acord.row;
      tpC=acord.col;

      board[auxRow][auxCol]=board[tpR][tpC];
      board[tpR][tpC]=EMPTY;
      tpK=board[kingRow][kingCol];
      board[kingRow][kingCol]=getPieceCode(noAttackerColor,"king");
      if(isSafe(kingRow,kingCol,noAttackerColor,false))
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
 //document.write('<BR>CanBlockWay: '+is_safe+'<BR>');
 return is_safe;
}

function canBlockAttack(cur_color)
{

 var kingRow=0;
 var kingCol=0;
 var targetKing=getPieceCode(cur_color,"king");
 var auxAttack=targetKing;
 for(var i=0;i<8;i++)
 {
  for(var j=0;j<8;j++)
  {
   if(board[i][j]==targetKing)
   {
    kingRow=i;
    kingCol=j;
   }
  }
 }
 var attackerColor=getOtherColor(cur_color);
 var attackerCoords={row:0, col:0};
 var attacker;
 var attackerRow;
 var attackerCol;
 var tpR;
 var tpC;
 var canBeCaptured;
 var canBeBlocked;
 while(getNextAttacker(kingRow,kingCol,cur_color,attackerCoords))
 {
  attackerRow=attackerCoords.row;
  attackerCol=attackerCoords.col;

  canBeCaptured=!isSafe(attackerRow,attackerCol,attackerColor,true);

  if(canBeCaptured)
  {
   if((acord.row>-1)&&(acord.col>-1))
   {
    tpR=acord.row;
    tpC=acord.col;
    attacker=board[attackerRow][attackerCol];
    board[attackerRow][attackerCol]=board[tpR][tpC];
    board[tpR][tpC]=EMPTY;
    if(!isSafe(kingRow,kingCol,cur_color,false))
    {
     canBeCaptured=false;
    }
    board[tpR][tpC]=board[attackerRow][attackerCol];
    board[attackerRow][attackerCol]=attacker;
   }
   if((canKing(kingRow,kingCol,targetKing,cur_color,"capture",attackerRow,attackerCol))&&((kingRow==acord.row)&&(kingCol==acord.col)))
   {
    board[kingRow][kingCol]=EMPTY;
    board[attackerRow][attackerCol]=targetKing;
    canBeCaptured=isSafe(attackerRow,attackerCol,cur_color,true);
    board[attackerRow][attackerCol]=attacker;
   }
  }
  //board[kingRow][kingCol]=getPieceCode(attackerColor,"pawn");
  board[kingRow][kingCol]=targetKing;

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

function canKing(kingRow, kingCol, targetKing, colorSide, type, captureRow, captureCol)
{
  var code=0;
  var targetPiece=EMPTY;
  var phase1=true;
  var phase2=true;
  var phase3=true;
  var phase4=true;
  var phase5=true;
  var phase6=true;
  var phase7=true;
  var phase8=true;
  var captured=false;
  var colorCode=getColorCode(colorSide);
  board[kingRow][kingCol]=EMPTY;
  if(isInBoard(kingRow,kingCol+1))
  {
   if((kingRow==captureRow)&&((kingCol+1)==captureCol)){ captured=true; }
   if(board[kingRow][kingCol+1]==EMPTY)
   {
    board[kingRow][kingCol+1]=targetKing;
    if(!is_in_check(colorSide))
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
     if(!is_in_check(colorSide))
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
    if(!is_in_check(colorSide))
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
     if(!is_in_check(colorSide))
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
    if(!is_in_check(colorSide))
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
     if(!is_in_check(colorSide))
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
    if(!is_in_check(colorSide))
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
     if(!is_in_check(colorSide))
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
    if(!is_in_check(colorSide))
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
     if(!is_in_check(colorSide))
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
    if(!is_in_check(colorSide))
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
     if(!is_in_check(colorSide))
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
    if(!is_in_check(colorSide))
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
     if(!is_in_check(colorSide))
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
    if(!is_in_check(colorSide))
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
     if(!is_in_check(colorSide))
     {
      phase8=false;
     }
     board[kingRow-1][kingCol-1]=targetPiece;
    }
   }
  }
  board[kingRow][kingCol]=targetKing;

  if((type=="move")&&(phase1&&phase2&&phase3&&phase4&&phase5&&phase6&&phase7&&phase8))
  {
    return true;
  }
  if((type=="capture")&&(captured))
  {
   return true;
  }
 return false;
}

function isXequeMate(colorSide)
{
 if(is_in_check(colorSide))
 {
  var kingRow=0;
  var kingCol=0;
  var targetKing=getPieceCode(colorSide,"king");
  var targetPiece=EMPTY;
  var colorCode=getColorCode(colorSide);
  var phase0=true;
  var phase9=true;
  for(var i=0;i<8;i++)
  {
   for(var j=0;j<8;j++)
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

function onlyKingRemains(color)
{
 var count=0;
 var pcolor;

 for(var i=0;i<8;i++)
 {
  for(var j=0;j<8;j++)
  {
   pcolor=getPieceColor(board[i][j]);
    if(pcolor==color)
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

function isDraw(colorSide)
{
 var kingRow=0;
 var kingCol=0;
 var attackerRow=-1;
 var attackerCol=-1;
 var targetKing=getPieceCode(colorSide,"king");
 var othercolorSide;
 var kingremain=false;
 var otherkingremain=false;
 var kingmove=false;
 var x = 0;
 var mov1="";
 var mov2="";
 var mov3="";

 othercolorSide=getOtherColor(colorSide);

 //Draw6: same movement 3 times
 if (numMoves >=10)
 for (x=0; x<=numMoves-9; x++){
    mov1 = chessHistory[x][CURCOLOR]+chessHistory[x][CURPIECE]+chessHistory[x][FROMROW]+chessHistory[x][FROMCOL]+chessHistory[x][TOROW]+chessHistory[x][TOCOL]+chessHistory[x+1][CURCOLOR]+chessHistory[x+1][CURPIECE]+chessHistory[x+1][FROMROW]+chessHistory[x+1][FROMCOL]+chessHistory[x+1][TOROW]+chessHistory[x+1][TOCOL];
    mov2 = chessHistory[x+4][CURCOLOR]+chessHistory[x+4][CURPIECE]+chessHistory[x+4][FROMROW]+chessHistory[x+4][FROMCOL]+chessHistory[x+4][TOROW]+chessHistory[x+4][TOCOL]+chessHistory[x+5][CURCOLOR]+chessHistory[x+5][CURPIECE]+chessHistory[x+5][FROMROW]+chessHistory[x+5][FROMCOL]+chessHistory[x+5][TOROW]+chessHistory[x+5][TOCOL];
    mov3 = chessHistory[x+8][CURCOLOR]+chessHistory[x+8][CURPIECE]+chessHistory[x+8][FROMROW]+chessHistory[x+8][FROMCOL]+chessHistory[x+8][TOROW]+chessHistory[x+8][TOCOL]+chessHistory[x+9][CURCOLOR]+chessHistory[x+9][CURPIECE]+chessHistory[x+9][FROMROW]+chessHistory[x+9][FROMCOL]+chessHistory[x+9][TOROW]+chessHistory[x+9][TOCOL];
    if (mov1 == mov2 && mov2 == mov3){
        return true;
    }
 }

 /*
 for (x=0; x<=numMoves; x++){
    mov1 = chessHistory[x][CURCOLOR]+chessHistory[x][CURPIECE]+chessHistory[x][FROMROW]+chessHistory[x][FROMCOL]+chessHistory[x][TOROW]+chessHistory[x][TOCOL];
    for (y=x+1; y<=numMoves; y++){
        mov2 = chessHistory[y][CURCOLOR]+chessHistory[y][CURPIECE]+chessHistory[y][FROMROW]+chessHistory[y][FROMCOL]+chessHistory[y][TOROW]+chessHistory[y][TOCOL];
        if (mov1 == mov2){
            for (z=y+1; z<=numMoves; z++){
                mov3 = chessHistory[z][CURCOLOR]+chessHistory[z][CURPIECE]+chessHistory[z][FROMROW]+chessHistory[z][FROMCOL]+chessHistory[z][TOROW]+chessHistory[z][TOCOL];
                if (mov2 == mov3){
                    return true;
                }
            }
        }
    }
 }
 */

 // DRAW4: Only 2 kings remains
 kingremain=onlyKingRemains(colorSide);
 otherkingremain=onlyKingRemains(othercolorSide);
 if(kingremain&&otherkingremain)
 {
  return(true);
 }


 // DRAW1: Stalemante!
 for(var i=0;i<8;i++)
 {
  for(var j=0;j<8;j++)
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
