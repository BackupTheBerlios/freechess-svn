// this is the main function that interacts with the user everytime they click on a square

	/* called whenever a square is clicked on */
	var is1stClick = true;
	
	function squareClickedFirst(row, col, isEmpty, curColor, message, message2)
	{
		if (getPieceColor(board[row][col]) == curColor)
		{
			document.gamedata.fromRow.value = row;
			document.gamedata.fromCol.value = col;

			highlight(row, col);

			is1stClick = false;
		}
		else
			alert(''+message);

	}
	
	function squareClickedSecond(row, col, isEmpty, curColor, message, message2, confirmMove)
	{

		unhighlight(document.gamedata.fromRow.value, document.gamedata.fromCol.value);
		is1stClick = true;

		if ((document.gamedata.fromRow.value == row)
			&& (document.gamedata.fromCol.value == col))
		{
			document.gamedata.fromRow.value = "";
			document.gamedata.fromCol.value = "";
		}
		else
		{
			/* if, on a player's second click, they click on one of their own piece */
			/* act as if he was clicking for the first time (ie: select it) */
			if (board[row][col] != 0 )
				if (getPieceColor(board[row][col]) == curColor)
				{
					squareClickedFirst(row, col, isEmpty, curColor, message);
					return null;
				}

			var fromRow = document.gamedata.fromRow.value;
			var fromCol = document.gamedata.fromCol.value;
			document.gamedata.toRow.value = row;
			document.gamedata.toCol.value = col;

			var fr = Math.abs(fromRow)+1;
			var r  = row+1;

			var moveOK = 0;

			if (confirmMove == 1){
				if (confirm(message2+" "+ntoc(fromCol)+fr+" "+ntoc(col)+r+" ?"))
					var moveOK = 1;
			}


			if (moveOK == 1 || confirmMove == 0){
				if (isValidMove())
				{
				if (DEBUG)
					alert("Move is valid, updating game...");

				var ennemyColor = "white";
				if (curColor == "white")
					ennemyColor = "black";

				/* update board with move (client-side) */
				board[row][col] = board[fromRow][fromCol];
				board[fromRow][fromCol] = 0;

                               /* if this is a castling move the rook must also be moved */
				if ((getPieceName(board[row][col]) == 'king') && (Math.abs(col - fromCol) == 2))
				{	// The king only moves two squares when castling
					var rookCol = 0;
					var rookToCol = 3
					if (col - fromCol == 2)
					{	// Kingside castling (would be == -2 if queenside)
						rookCol = 7;
						rookToCol = 5;
					}
					board[row][rookToCol] = board[row][rookCol];
					board[row][rookCol] = 0;
				}


				if (isInCheck(ennemyColor))
				{
					document.gamedata.isInCheck.value = "true";
					document.gamedata.isCheckMate.value = isXequeMate(ennemyColor);
				}
				else
					document.gamedata.isInCheck.value = "false";

				document.gamedata.submit();
				}
				else
				{
				document.gamedata.toRow.value = "";
				document.gamedata.toCol.value = "";
				
				alert("Invalid move:\n" + errMsg);
				}
			}
			else{
				document.gamedata.toRow.value = "";
                                document.gamedata.toCol.value = "";
			}


		}
	}
	
	function squareClicked(confirmMove, row, col, isEmpty, message, message2)
	{
		if (DEBUG)
			alert('squareClicked -> row = ' + row + ', col = ' + col + ', isEmpty = ' + isEmpty);

		var curColor = "black";
		if ((numMoves == -1) || (numMoves % 2 == 1))
			curColor = "white";

		if (is1stClick && !isEmpty)
			squareClickedFirst(row, col, isEmpty, curColor, message,message2);
		else if(!is1stClick)
			squareClickedSecond(row, col, isEmpty, curColor, message,message2,confirmMove);
	}
