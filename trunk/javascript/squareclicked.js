// this is the main function that interacts with the user everytime they click on a square

    /* called whenever a square is clicked on */
    var is1stClick = true;

    function squareClickedFirst(row, col, isEmpty, cur_color, message, message2)
    {

        if (getPieceColor(board[row][col]) == cur_color)
        {
            document.gamedata.from_row.value = row;
            document.gamedata.from_col.value = col;

            highlight(row, col);

            is1stClick = false;
        }
        else
            alert(''+message);

    }

    function squareClickedSecond(row, col, isEmpty, cur_color, message, message2, confirmMove)
    {

        unhighlight(document.gamedata.from_row.value, document.gamedata.from_col.value);
        is1stClick = true;

        if ((document.gamedata.from_row.value == row)
            && (document.gamedata.from_col.value == col))
        {
            document.gamedata.from_row.value = "";
            document.gamedata.from_col.value = "";
        }
        else
        {
            /* if, on a player's second click, they click on one of their own piece */
            /* act as if he was clicking for the first time (ie: select it) */
            if (board[row][col] != 0 )
                if (getPieceColor(board[row][col]) == cur_color)
                {
                    squareClickedFirst(row, col, isEmpty, cur_color, message);
                    return null;
                }

            var from_row = document.gamedata.from_row.value;
            var from_col = document.gamedata.from_col.value;
            document.gamedata.to_row.value = row;
            document.gamedata.to_col.value = col;

            var fr = Math.abs(from_row)+1;
            var r  = row+1;

            var moveOK = 0;

            if (confirmMove == 1){
                if (confirm(message2+" "+ntoc(from_col)+fr+" "+ntoc(col)+r+" ?"))
                    var moveOK = 1;
            }


            if (moveOK == 1 || confirmMove == 0){
                if (isValidMove())
                {
                if (DEBUG)
                    alert("Move is valid, updating game...");

                var ennemyColor = "white";
                if (cur_color == "white")
                    ennemyColor = "black";

                /* update board with move (client-side) */
                board[row][col] = board[from_row][from_col];
                board[from_row][from_col] = 0;

                if (is_in_check(ennemyColor))
                {
                    document.gamedata.is_in_check.value = "true";
                    document.gamedata.isCheckMate.value = isXequeMate(ennemyColor);
                }
                else
                    document.gamedata.is_in_check.value = "false";

                document.gamedata.submit();
                }
                else
                {
                document.gamedata.to_row.value = "";
                document.gamedata.to_col.value = "";

                alert("Invalid move:\n" + errMsg);
                }
            }
            else{
                document.gamedata.to_row.value = "";
                                document.gamedata.to_col.value = "";
            }


        }
    }

    function squareClicked(confirmMove, row, col, isEmpty, message, message2)
    {
        if (DEBUG)
            alert('squareClicked -> row = ' + row + ', col = ' + col + ', isEmpty = ' + isEmpty);

        var cur_color = "black";
        if ((numMoves == -1) || (numMoves % 2 == 1))
            cur_color = "white";

        if (is1stClick && !isEmpty)
            squareClickedFirst(row, col, isEmpty, cur_color, message,message2);
        else if(!is1stClick)
            squareClickedSecond(row, col, isEmpty, cur_color, message,message2,confirmMove);
    }
