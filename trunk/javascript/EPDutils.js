	function getObject(obj) {
	  if (document.getElementById) {  // Mozilla, FireFox, Explorer 5+, Opera 5+, Konqueror, Safari, iCab, Ice, OmniWeb 4.5
	    if (typeof obj == "string") {
	      if(document.getElementById(obj)) {
	        return document.getElementById(obj);
	      } else {
	        return document.getElementsByName(obj)[0];
	      }
	    } else {
	      return obj.style;
	    }
	  }
	  if (document.all) {				// Explorer 4+, Opera 6+, iCab, Ice, Omniweb 4.2-
	    if (typeof obj == "string") {
	      return document.all(obj);
	    } else {
	      return obj.style;
	    }
	  }
	  if (document.layers) {			// Netscape 4, Ice, Escape, Omniweb 4.2-
	    if (typeof obj == "string") {
	      return document.layers(obj);
	    } else {
	      return obj.style;
	    }
	  }
	  return null;
	}

	var PieceColor = new Array();
	PieceColor = {'K':'w', 'Q':'w', 'R':'w', 'B':'w', 'N':'w', 'P':'w',
				  'k':'b', 'q':'b', 'r':'b', 'b':'b', 'n':'b', 'p':'b'};

	var ColorLtrToName = new Array();
	ColorLtrToName = {'w':'white', 'b':'black'};

	var ColorNameToLtr = new Array();
	ColorNameToLtr = {'white':'w', 'black':'b'};

	var PieceLtrToName = new Array();
	PieceLtrToName = {'k':'king', 'q':'queen', 'r':'rook', 'b':'bishop', 'n':'knight', 'p':'pawn'};

	var PieceNameToLtr = new Array();
	PieceNameToLtr = {'king':'k', 'queen':'q', 'rook':'r', 'bishop':'b', 'knight':'n', 'pawn':'p'};

	function ExpandEPD(EPD) {
	  var theEPD = EPD.replace(/[2-8]/g,                    // Expand contiguous empty squares
	    function strRepeat(count) {                         //   e.g. change '4' to '1111'
	      var strOut = '';
	      for(var i=0; i < count; i++) {
	       strOut += '1';
	      }
	     return strOut;
	    }
	  );
	  return theEPD.replace(/\//g, "");                     // Leave only pieces and empty squares
	}

	function SetSquare(Square, Piece) {
	  var rank = 7 - parseInt(Square / 8);
	  var file = Square % 8;
	  var sqr = new getObject("sq" + rank + file);
	  if(Piece == '1') {
	    s = 'images/pieces/beholder/blank.gif';
	  } else {
	    s = 'images/pieces/beholder/' + ColorLtrToName[PieceColor[Piece]] + '_' + PieceLtrToName[Piece.toLowerCase()] + '.gif';
	  }
	  sqr.src = s;
	}

	function EPDToBoard(EPD) {
	  var EDPItems = new Array();
	  EPDItems = EPD.split(' ');
	  var ExpEPD = ExpandEPD(EPDItems[0]);
	  for(var i=0; i < 64; i++) {
	    c = ExpEPD.charAt(i);
	    SetSquare(i, c);
	  }
	  curColor = ColorLtrToName[EPDItems[1]];
	};

	function ValidEPD(EPD) {	// Is the EPD string valid (draft, needs improvement)
	  var EDPItems = new Array();
	  EPDItems = EPD.split(' ');
	  if(EPDItems[0].length < 17) {
	    alert('EDP: Too short');
	    return false;
	  }
	  var ExpEPD = ExpandEPD(EPDItems[0]);
	  if(ExpEPD.length != 64) {		// There are 64 squares on a chessboard
	    alert('EDP: Piece placement field length not 64');
	    return false;
	  }
	  var count = {'K':0, 'k':0, 'Q':0, 'q':0, 'R':0, 'r':0, 'B':0, 'b':0, 'N':0, 'n':0, 'P':0, 'p':0, '1':0};
	  for(var i=0; i < 64; i++) {
	    c = ExpEPD.charAt(i);
	    if(!count[c]) {
	      count[c] = 1;
	    } else {
	      count[c]++;
	    }
	  }
	  if(count['K'] != 1 || count['k'] != 1) {	// One white and one black king?
	    alert('EDP: Wrong number of kings');
	    return false;
	  }
	  total = count['K'] + count['k'] + count['Q'] + count['q'] + count['R'] + count['r'];
	  total += count['B'] + count['b'] + count['N'] + count['n'] + count['P'] + count['p'] + count['1'];
	  if(total != 64) { // Any unknown pieces?
	    alert('EDP: Unknown piece(s). Total: ' + total + ' ' + ExpEPD);
	    return false;
	  }
	  return true;
	}

	function BoardToEPD() {
	  var EPD = '';
	  var EmptySq;
	  for(var rank=7; rank >= 0; rank--) {
	    EmptySq = '';
	    for(var file=0; file < 8; file++) {
	      PieceCode = board[rank][file];
	      if(PieceCode == 0) {
	        EmptySq += '1';
	      } else {
	        PieceColor = getPieceColor(PieceCode);
	        PieceName = getPieceName(PieceCode);
	        Piece = PieceNameToLtr[PieceName];
	        if(PieceColor == 'white') {
	          Piece = Piece.toUpperCase();
	        }
	        if(EmptySq.length > 0) {
	          EPD += EmptySq.length;
	        }
	        EPD += Piece;
	      }
	    }
	    if(EmptySq.length > 0) {
	      EPD += EmptySq.length;
	    }
	    if(rank > 0) {
	      EPD += '/';
	    }
	  }
	  return EPD;
	};

function getEPDStartPos()
{
	return 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -';
}

function htmlBoard()
{	// Returns the HTML-code for an empty chessboard (Note: Fixed square size and theme)
    theBoard = '<table cellpadding="0" style="border:2px solid #555; padding:0; border-collapse: collapse;border-spacing:0;">';
    theBoard += '<tr style="height:25px; background-color:#bbbbbb;"><td colspan="10"></td></tr>';
    for($i=7;$i>=0;$i--)
    {
        theBoard += '<tr><td style="width:25px; background-color:#bbbbbb;"></td>' + "\n";
        for($j=0;$j<8;$j++)
        {
            if((($i+$j)%2)==0)
                theBoard += '<td bgcolor="lightgray">';
            else
                theBoard += '<td>';
            theBoard += '<img id="sq'+$i+$j+'" width="50" height="50" src="images/pieces/beholder/blank.gif" alt="" />';
           theBoard += "</td>";
        }
        theBoard += '<td style="width:25px; background-color:#bbbbbb;"></td></tr>' + "\n";
    }
    theBoard += '<tr style="height:25px; background-color:#bbbbbb;"><td colspan="10"></td></tr>';
    theBoard += '</table>';
    return theBoard;
}

