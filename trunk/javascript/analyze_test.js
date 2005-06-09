/*

  This analyze script is an adaptation of n8chessnet analyze board from Nathan Kelley (n8osapi)
  More information: http://www.n8osapi.com

*/

var DEBUG = 0;
var PAWN = 1;
var KNIGHT = 2;
var BISHOP = 4;
var ROOK = 8;
var QUEEN = 16;
var KING = 32;
var BLACK = 128;
var WHITE = 0;
var COLOR_MASK = 127;
var gameID = 0;
var showall = false;

var iMoveCount=1;
var iCurMove=0;
var fRows = new Array();
var fCols = new Array();
var tRows = new Array();
var tCols = new Array();
var ts = new Array();
var pTaken = new Array();
var pPromoted = new Array();

var iMaxMoves;
var strImageLoc;
var lastRow=undefined;
var lastCol=undefined;
var MessedWith=false;
var selX=0;
var selY=0;

var play=0;

function set_gameID(id)
{
	gameID=id;
}

function setgame(id)
{
	gameID=id;
}

function StoreMove(fR,fC,tR,tC,pT,pR,t)
{
	fRows[iMoveCount]=fR;
	fCols[iMoveCount]=fC;
	tRows[iMoveCount]=tR;
	tCols[iMoveCount]=tC;
         ts[iMoveCount]=t;

	//if (pT==undefined) pT=0;
	//if (pr==undefined) pr=0;
	pTaken[iMoveCount]=pT;
	pPromoted[iMoveCount]=pR;
	iMaxMoves=iMoveCount;
	iMoveCount++;
}

function InitializeBoard()
{
   strImageLoc='images/analyze/';
   for(var i = 0 ; i<8 ; i++)
		{
		board[i] = new Array();
		for (var j = 0; j<8 ; j++)
		{
			board[i][j]=0;
		}
	}

	board[0][0]=WHITE|ROOK;
	board[0][1]=WHITE|KNIGHT;
	board[0][2]=WHITE|BISHOP;
	board[0][3]=WHITE|QUEEN;
	board[0][4]=WHITE|KING;
	board[0][5]=WHITE|BISHOP;
	board[0][6]=WHITE|KNIGHT;
	board[0][7]=WHITE|ROOK;
	board[1][0]=WHITE|PAWN;
	board[1][1]=WHITE|PAWN;
	board[1][2]=WHITE|PAWN;
	board[1][3]=WHITE|PAWN;
	board[1][4]=WHITE|PAWN;
	board[1][5]=WHITE|PAWN;
	board[1][6]=WHITE|PAWN;
	board[1][7]=WHITE|PAWN;
	board[6][0]=BLACK|PAWN;
	board[6][1]=BLACK|PAWN;
	board[6][2]=BLACK|PAWN;
	board[6][3]=BLACK|PAWN;
	board[6][4]=BLACK|PAWN;
	board[6][5]=BLACK|PAWN;
	board[6][6]=BLACK|PAWN;
	board[6][7]=BLACK|PAWN;
	board[7][0]=BLACK|ROOK;
	board[7][1]=BLACK|KNIGHT;
	board[7][2]=BLACK|BISHOP;
	board[7][3]=BLACK|QUEEN;
	board[7][4]=BLACK|KING;
	board[7][5]=BLACK|BISHOP;
	board[7][6]=BLACK|KNIGHT;
	board[7][7]=BLACK|ROOK;
}

function PieceName(p)
{
	var a;
	if (p==0) a="blank";
	if (p==(WHITE|PAWN)) a="white_pawn";
	if (p==(WHITE|BISHOP)) a="white_bishop";
	if (p==(WHITE|KNIGHT)) a="white_knight";
	if (p==(WHITE|ROOK)) a = "white_rook";
	if (p==(WHITE|QUEEN)) a = "white_queen";
	if (p==(WHITE|KING)) a = "white_king";
	if (p==(BLACK|PAWN)) a="black_pawn";
	if (p==(BLACK|BISHOP)) a="black_bishop";
	if (p==(BLACK|KNIGHT)) a="black_knight";
	if (p==(BLACK|ROOK)) a = "black_rook";
	if (p==(BLACK|QUEEN)) a = "black_queen";
	if (p==(BLACK|KING)) a = "black_king";

	return(strImageLoc + a + ".gif");
}
function Piece(iii,jjj)
{
   var x = (iii*8) + jjj + 1;
   var p = board[iii][jjj];
   return("<img width=45 src=\"" + PieceName(p) + "\" id=\"d" + iii.toString() + jjj.toString() + "\" onclick=\"javascript:SquareClick("+iii+","+jjj+");\">");
}

function SquareSelect(row,col)
{
	selX = 	document.getElementById("d"+row.toString()+col.toString()).width;
	selY = 	document.getElementById("d"+row.toString()+col.toString()).height
	document.getElementById("d"+row.toString()+col.toString()).width=selX-2;
	document.getElementById("d"+row.toString()+col.toString()).height=selY-2;
	document.getElementById("d"+row.toString()+col.toString()).border=1;
}
function SquareDeSelect(row,col)
{
	document.getElementById("d"+row.toString()+col.toString()).border=0;
	document.getElementById("d"+row.toString()+col.toString()).width=selX;
	document.getElementById("d"+row.toString()+col.toString()).height=selY;
	selX=0;
	selY=0;
}
function SquareClick(row,col)
{
	var r=0;
	var c=0;
	var p=0;
	var lR=0;
	var lC=0;
	if (who=="white"){r=row;c=col;lR=lastRow;lC=lastCol;}else{r=7-row;c=7-col;lR=7-lastRow;lC=7-lastCol;}
	//alert("Row="+r+", Col="+c);
	if ((lastRow==undefined)&&(lastCol==undefined))
	{
		p=board[r][c];
		if(p!=0)
		{
			lastRow=r;
			lastCol=c;
			SquareSelect(row,col);
		}
		return;
	}
	if ((lastRow==r)&&(lastCol==c))
	{
		SquareDeSelect(lR,lC);
		lastRow=undefined;
		lastCol=undefined;
		return;
	}
	if ((lastRow!=undefined)&&(lastCol!=undefined))
	{
		p=board[r][c];

		if(p==0)
		{
			MessedWith=true;
			Move(lastRow,lastCol,r,c);
			SquareDeSelect(lR,lC);
			UpdateBoard();
			lastRow=undefined;
			lastCol=undefined;
			return;
		}
		if((p&BLACK)==(board[lastRow][lastCol]&BLACK))
		{
		        //alert("Can't Take Your Own Piece");
			//alert("to=" +p+","+ (p&BLACK));
			//alert("from=" +board[lastRow][lastCol]+","+ (board[lastRow][lastCol]&BLACK));
			SquareDeSelect(lR,lC);
			lastRow=undefined;
			lastCol=undefined;
		}
		else
		{
			MessedWith=true;
			Move(lastRow,lastCol,r,c);
			SquareDeSelect(lR,lC);
			UpdateBoard();
			lastRow=undefined;
			lastCol=undefined;
		}
		return;
	}
}

function DisplayBoard()
{
	var color;
	StartTable();
	for (var i=7;i>=0;i--)
	{
	for (var j=0;j<8;j++)
	{
//		Cell("("+i+","+j+") = "+Piece(i,j)+" ");
//		if(((i+j)%2)==1){color="#E7CEA5";}else{color="#A57B5A";}
		document.write(Piece(i,j));
//		if((i==5)&&(j==5)){alert(Piece(i,j));}
	}
//		document.write("<br>");


		document.write("<br>");
	}
	EndTable();
	WriteButtons();
	//UpdateBoard();
//	document.getElementById("d55").innerHTML = "test";
}

function Play(){
	if (play==1 && iCurMove<iMaxMoves){
		MoveForward(1);
		setTimeout("Play()",1000);
	}
}

function WriteButtons()
{
	//document.write("<div id=\"move\">Move:</div>");
	document.write("<br>");
	document.write("<input type=\"button\" value=\"|<\" onclick=\"javascript:MoveFirst();\">");
	document.write("<input type=\"button\" value=\"<<\" onclick=\"javascript:MoveBack(5);\">");
	document.write("<input type=\"button\" value=\"<\" onclick=\"javascript:MoveBack(1);\">");
	document.write("<input type=\"button\" value=\">\" onclick=\"javascript:MoveForward(1);\">");
	document.write("<input type=\"button\" value=\">>\" onclick=\"javascript:MoveForward(5);\">");
	document.write("<input type=\"button\" value=\">|\" onclick=\"javascript:MoveEnd();\">");
         document.write("<input type=\"button\" value=\"Main\" onclick=\"window.location='mainmenu.php'\">");
	document.write("<br><input type=\"button\" value=\"W/B\" onclick=\"javascript:SwitchSides();\">");
	document.write("<input type=\"button\" value=\"Play\" onclick=\"javascript:play=1;Play();\">");
	document.write("<input type=\"button\" value=\"Stop\" onclick=\"javascript:play=0;\">");
	document.write("<input type=\"button\" value=\"Export PGN\" onclick=\"window.open('exportpgn.php','_self');\">");

}
function StartTable(){

var boardstart_black ="<table><tr><td><table height=360><tr><td align=\"center\">1</td></tr><tr><td align=\"center\">2</td></tr><tr><td align=\"center\">3</td></tr><tr><td align=\"center\">4</td></tr><tr><td align=\"center\">5</td></tr><tr><td align=\"center\">6</td></tr><tr><td align=\"center\">7</td></tr><tr><td align=\"center\">8</td></tr></table></td><td><TABLE BORDER=0><TR><TD><TABLE border=0 width=360 height=360 style=\"background: url(" +strImageLoc + "board.gif);\" border=0 cellspacing=0 cellpadding=0><TR><TD>\n";

var boardstart_white ="<table><tr><td><table height=360><tr><td align=\"center\">8</td></tr><tr><td align=\"center\">7</td></tr><tr><td align=\"center\">6</td></tr><tr><td align=\"center\">5</td></tr><tr><td align=\"center\">4</td></tr><tr><td align=\"center\">3</td></tr><tr><td align=\"center\">2</td></tr><tr><td align=\"center\">1</td></tr></table></td><td><TABLE BORDER=0><TR><TD><TABLE border=0 width=360 height=360 style=\"background: url(" +strImageLoc + "board.gif);\" border=0 cellspacing=0 cellpadding=0><TR><TD>\n";

if (who=='white')
document.write(boardstart_white);
else
document.write(boardstart_black);

}
function EndTable(){

var boardend_white = "</TD></TR></TABLE></TD></TR></TABLE><table width=360><tr><td align=\"center\">A</td><td align=\"center\">B</td><td align=\"center\">C</td><td align=\"center\">D</td><td align=\"center\">E</td><td align=\"center\">F</td><td align=\"center\">G</td><td align=\"center\">H</td></tr></table></td></tr></table>\n";

var boardend_black = "</TD></TR></TABLE></TD></TR></TABLE><table width=360><tr><td align=\"center\">H</td><td align=\"center\">G</td><td align=\"center\">F</td><td align=\"center\">E</td><td align=\"center\">D</td><td align=\"center\">C</td><td align=\"center\">B</td><td align=\"center\">A</td></tr></table></td></tr></table>\n";


if (who=='white')
document.write(boardend_white);
else
document.write(boardend_black);

}
function NextCell(){document.write("</TD><TD>");}
function NextRow(){document.write("</TR><TR>");}
function Cell(text, color){document.write("<TD bgcolor=\""+color+"\" width=30px height=30px align=center>"+text+"</TD>");}
function Move(fRow,fCol,tRow,tCol)
{
	board[tRow][tCol]=board[fRow][fCol];
	board[fRow][fCol]=0;

	if ((fRow==0)&&(fCol==4)&&(tRow==0)&&(tCol==2)){Move(0,0,0,3);return;};
	if ((fRow==0)&&(fCol==4)&&(tRow==0)&&(tCol==6)){Move(0,7,0,5);return;};
	if ((fRow==7)&&(fCol==4)&&(tRow==7)&&(tCol==2)){Move(7,0,7,3);return;};
	if ((fRow==7)&&(fCol==4)&&(tRow==7)&&(tCol==6)){Move(7,7,7,5);return;};

	if ((tRow==0)&&(tCol==4)&&(fRow==0)&&(fCol==2)){Move(0,3,0,0);return;};
	if ((tRow==0)&&(tCol==4)&&(fRow==0)&&(fCol==6)){Move(0,5,0,7);return;};
	if ((tRow==7)&&(tCol==4)&&(fRow==7)&&(fCol==2)){Move(7,3,7,0);return;};
	if ((tRow==7)&&(tCol==4)&&(fRow==7)&&(fCol==6)){Move(7,5,7,7);return;};

}
function UpdateBoard()
{        //alert(who);
         for (var i=7;i>=0;i--)
	{
	for (var j=0;j<8;j++)
	{
	var x=0;
	var y=0;
	if(who=="white"){x=i;y=j;}else{x=7-i;y=7-j}
	document.getElementById("d"+i.toString()+j.toString()).src = PieceName(board[x][y]);
	}
	}
	var wb = ""
	if ((iCurMove%2)==0) { wb="black";}else{wb="white"}
	var move = Math.floor((iCurMove/2)+.5);
	var dispmove="";
	if (wb=="black") { dispmove = move.toString() + " ...";} else { dispmove = move.toString();}

	//document.getElementById("move").innerHTML="Move: " + dispmove;

         var location = 'analyze_comments.php?gameID='+gameID+'&timeOfMove='+ts[iCurMove]+'#goto'+iCurMove

         parent.frames[0].location.href=location;

         //F1 = eval("parent.analyze_comments");
         //F1.location.href=location;

}
function MoveForward(moves)
{
	for(var i=1;i<=moves;i++){
		if (iCurMove<iMaxMoves){
			iCurMove++;
			Move(fRows[iCurMove],fCols[iCurMove],tRows[iCurMove],tCols[iCurMove]);
			if (pPromoted[iCurMove] >0)
				board[tRows[iCurMove]][tCols[iCurMove]] = pPromoted[iCurMove];
		}
	}
	UpdateBoard();
}
function MoveBack(moves)
{
	for(var i=1;i<=moves;i++){
		if(iCurMove>0){
			Move(tRows[iCurMove],tCols[iCurMove],fRows[iCurMove],fCols[iCurMove]);
			board[tRows[iCurMove]][tCols[iCurMove]] = pTaken[iCurMove];
			if (pPromoted[iCurMove] >0)
				board[fRows[iCurMove]][fCols[iCurMove]] = getPieceCode(getPieceColor(pPromoted[iCurMove]),"pawn");
			iCurMove--;
		}
	}
	UpdateBoard();
}
function MoveFirst()
{
	iCurMove=0;
	InitializeBoard();
	UpdateBoard();
}
function MoveEnd()
{
	if(MessedWith)MoveFirst();
	MoveForward(iMaxMoves-iCurMove);
}
function SwitchSides()
{
         var location = '';

         if(who=="white")
         {
         who="black";
         location = 'analyze.php?whocolor=black&game='+gameID;
         }
         else
         {
         who="white";
         location = 'analyze.php?whocolor=white&game='+gameID;
         };

         //UpdateBoard();

         document.location.href=location;
}