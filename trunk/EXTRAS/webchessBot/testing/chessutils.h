char *getPieceColor(int piece)
{
 char *color;
 color=new char(5);
 if(piece)
 {
  if(BLACK&piece)
  {
   strcpy(color,"black");
  }
  else
  {
   strcpy(color,"white");
  }
  return color;
 }
 strcpy(color,"empty");
 return(color);
}

char *getPieceName(int piece)
{
 char *pieceName;
 pieceName=new char(8);
 if(piece>32)
 {
  piece=piece&COLOR_MASK;
 }
 switch(piece)
 {
  case 1:  strcpy(pieceName,"pawn");   break;
  case 2:  strcpy(pieceName,"knight"); break;
  case 4:  strcpy(pieceName,"bishop"); break;
  case 8:  strcpy(pieceName,"rook");   break;
  case 16: strcpy(pieceName,"queen");  break;
  case 32: strcpy(pieceName,"king");   break;
 }
 return pieceName;
}

int getPieceCode(char color[], char piece[])
{
 int code;
 if(!strcmp(piece,"pawn"))   code=PAWN;
 if(!strcmp(piece,"knight")) code=KNIGHT;
 if(!strcmp(piece,"bishop")) code=BISHOP;
 if(!strcmp(piece,"rook"))	  code=ROOK;
 if(!strcmp(piece,"queen"))  code=QUEEN;
 if(!strcmp(piece,"king"))   code=KING;
 if(!strcmp(color,"black"))  code=BLACK|code;
 return code;
}

char *getOtherColor(char color[])
{
 char *other;
 other=new char(5);
 if(!strcmp(color,"white")) strcpy(other,"black");
 else                       strcpy(other,"white");
 return other;
}
