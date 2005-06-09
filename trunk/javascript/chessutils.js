function ntoc(n){

    if (n == 0)
        return "a";
    else if (n == 1)
        return "b";
    else if (n == 2)
        return "c";
    else if (n == 3)
        return "d";
    else if (n == 4)
        return "e";
    else if (n == 5)
        return "f";
    else if (n == 6)
        return "g";
    else if (n == 7)
        return "h";

    return 0;

}

function getPieceColor(piece)
{


 if(BLACK&piece)
 {
  return "black";
 }
 else
 {
  return "white";
 }

}

function getPieceName(piece)
{
 var pieceName;

 if(piece>32)
 {
  piece=piece&COLOR_MASK;
 }
 switch(piece)
 {
  case 1:  pieceName="pawn";   break;
  case 2:  pieceName="knight"; break;
  case 4:  pieceName="bishop"; break;
  case 8:  pieceName="rook";   break;
  case 16: pieceName="queen";  break;
  case 32: pieceName="king";   break;
 }
 return pieceName;
}

function getPieceCode(color, piece)
{
 var code;
 if(piece=="pawn")   code = PAWN;
 if(piece=="knight") code = KNIGHT;
 if(piece=="bishop") code = BISHOP;
 if(piece=="rook")    code = ROOK;
 if(piece=="queen")  code = QUEEN;
 if(piece=="king")   code = KING;
 if(color=="black")  code = BLACK|code;
 return code;
}

function getOtherColor(color)
{


 if(color=="white") return "black";
 else               return "white";
}

function highlight(row, col)
{
 if(board[parseInt(row)][parseInt(col)] != "")
        {
            eval("document.images['pos" + row + "-" + col + "'].src = 'images/pieces/' + CURRENTTHEME + '/' + getPieceColor(board[row][col]) + '_' + getPieceName(board[row][col]) + '_highlighted.gif'");
        }

        return true;
    }

    function unhighlight(row, col)
    {
        if (DEBUG)
            alert("unhighlight -> row = " + row + ", col = " + col);

        if (board[parseInt(row)][parseInt(col)] != "")
        {
            eval("document.images['pos" + row + "-" + col + "'].src = 'images/pieces/' + CURRENTTHEME + '/' + getPieceColor(board[row][col]) + '_' + getPieceName(board[row][col]) + '.gif'");
        }

        return true;
    }


