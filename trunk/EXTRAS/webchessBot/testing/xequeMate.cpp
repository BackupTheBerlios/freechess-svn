#include <math.h>
#include <conio.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <iostream.h>

#define EMPTY        0
#define PAWN         1
#define KNIGHT       2
#define BISHOP       4
#define ROOK         8
#define QUEEN       16
#define KING        32
#define WHITE        0
#define BLACK      128
#define COLOR_MASK 127

int DEBUG=0;
int board[8][8];
char wc;
char wcm;
char bc;
char bcm;
char bid;
char wid;
struct ac { int row; int col; };
struct ac2 { int row; int col; int nrow; int ncol;};
struct pf { int dist; int piece; int row; int col;};

char *getPieceName(int piece);
char *getPieceColor(int piece);
bool isInCheck(char curColor[]);
bool isInBoard(int row, int col);
char *getOtherColor(char color[]);
int getPieceCode(char color[], char piece[]);
bool isSafe(int testRow, int testCol, char testColor[], bool pawntest, ac2 *coords);
bool canKing(int kingRow, int kingCol, int targetKing, char colorSide[], char type[], int captureRow, int captureCol);
bool isAttacking(int attackerPiece, int attackerRow, int attackerCol, char attackerColor[], int targetRow, int targetCol);

char retColor(int val)
{
 char lt;
 switch(val)
 {
  case 0:   lt='0'; break;
  case 128: lt='1'; break;
 }
 return(lt);
}
 
char retMask(int val)
{
 int val2;
 char lt;
 switch(val)
 {
  case 0:  lt='.'; break;
  case 1:  lt='P'; break;
  case 2:  lt='N'; break;
  case 4:  lt='B'; break;
  case 8:  lt='R'; break;
  case 16: lt='Q'; break;
  case 32: lt='K'; break;
  default: val2=val | 128;
   switch(val2)
   {
    case 129: lt='p'; break;
    case 130: lt='n'; break;
    case 132: lt='b'; break;
    case 136: lt='r'; break;
    case 144: lt='q'; break;
    case 160: lt='k'; break;
    default: lt=val;
   }
 }
 return(lt);
}

void drawb(int rw, int cl, char kv)
{
 cout<<"\n";
 for(int x=0;x<8;x++)
 {
  cout<<(x)<<" ";
  for(int y=7;y>=0;y--)
  {
   if((x==cl)&&(y==rw)) cout<<kv<<" ";
   else                 cout<<retMask(board[x][y])<<" ";
  }
  cout<<"\n";
 }
 cout<<"\n  7 6 5 4 3 2 1 0\n";
 if(cl>=0)
 {
  cout<<"\nPausa...";
  getch();
 }
}

int readBoard(char boar[])
{
 FILE *in;

 if(in=fopen(boar,"rb"))
 {
 char fc;
 int x, y;
 wc=fgetc(in);
 wcm=fgetc(in);
 bc=fgetc(in);
 bcm=fgetc(in);
 wid=fgetc(in);
 bid=fgetc(in);
 for(y=0;y<8;y++)
 {
  for(x=7;x>=0;x--)
  {
   fc=fgetc(in);
   while((fc=='\n')||(fc=='\r'))
   {
    fc=fgetc(in);
   }
   switch(fc)
   {
    case '.':
     board[y][x]=EMPTY;
    break;
    case 'P':
     board[y][x]=PAWN;
    break;
    case 'p':
     board[y][x]=PAWN|BLACK;
    break;
    case 'R':
     board[y][x]=ROOK;
    break;
    case 'r':
     board[y][x]=ROOK|BLACK;
    break;
    case 'N':
     board[y][x]=KNIGHT;
    break;
    case 'n':
     board[y][x]=KNIGHT|BLACK;
    break;
    case 'B':
     board[y][x]=BISHOP;
    break;
    case 'b':
     board[y][x]=BISHOP|BLACK;
    break;
    case 'Q':
     board[y][x]=QUEEN;
    break;
    case 'q':
     board[y][x]=QUEEN|BLACK;
    break;
    case 'K':
     board[y][x]=KING;
    break;
    case 'k':
     board[y][x]=KING|BLACK;
    break;
   }
  }
 }
 fclose(in);
 return(1);
 }
 else
 {
  clrscr();
  printf("\n\n\n\n\tNao foi possivel abrir o arquivo indicado.\n\n");
  return(0);
 }
}

#include "isCheckMate.h"
#include "validation.h"
#include "chessutils.h"

void main()
{
 char number[255];
 char filename[255];
 int ext=0;
 do
 {
 curDir=-1;
 clrscr();
 printf("\n\nEntre com o ID do board:\n");
 scanf("%s",number);
 strcpy(filename,"injus");
 strcat(filename,number);
 strcat(filename,".txt");
 if(readBoard(filename))
 {

 clrscr();
 printf("Analise do arquivo: %s\n",filename);
 printf("\nWHITE (RNBQKBNR P) & black (rnbkqbnr p)\n\n");

 if(isInCheck("white"))
  printf("%cS - white is in check\n",wc);
 else
  printf("%cN - white is Not in check\n",wc);

 if(isXequeMate("white"))
  printf("%cS - white is in checkmate\n",wcm);
 else
  printf("%cN - white is Not in checkmate\n",wcm);

 if(isDraw("white"))
  printf("%cD - white is Draw",wid);
 else
  printf("%cN - white is Not Draw",wid);

 printf("\n\n");

 if(isInCheck("black"))
  printf("%cS - black is in check\n",bc);
 else
  printf("%cN - black is Not in check\n",bc);

 if(isXequeMate("black"))
  printf("%cS - black is in checkmate\n",bcm);
 else
  printf("%cN - black is Not in checkmate\n",bcm);

 if(isDraw("black"))
  printf("%cD - black is Draw\n",bid);
 else
  printf("%cN - black is Not Draw\n",bid);

 printf("\n-----------------");
 drawb(-1,-1,'X');
 }

 printf("\n ESC para terminar");
 ext=getch();
 }
 while(ext!=27);
}
