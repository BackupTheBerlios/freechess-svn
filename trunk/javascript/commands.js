// these functions interact with the server

    function undo(message)
    {
        if (confirm(''+message)){
            document.gamedata.requestUndo.value = "yes";
            document.gamedata.submit();
        }
    }

    function draw(rounds,message)
    {
        if (confirm(''+rounds+message)){
            document.gamedata.requestDraw.value = "yes";
            document.gamedata.submit();
        }
    }

    function englishdraw(message,rounds)
    {
        if (confirm(''+message+rounds)){
            document.gamedata.requestDraw.value = "yes";
            document.gamedata.submit();
        }
    }
    function drawrequestwithoutmove(message)
        {
            if (confirm(''+message)){
                document.gamedata.requestDraw.value = "no";
                //document.gamedata.submit();
            }
        }
    function englishresigngame(message,rounds)
    {
        if (confirm(''+message+rounds)){
            document.gamedata.resign.value = "yes";
            document.gamedata.submit();
        }
    }

    function resigngame(rounds,message)
    {
        if (confirm(''+rounds+message)){
            document.gamedata.resign.value = "yes";
            document.gamedata.submit();
        }
    }

/*
    Unused
    function logout()
    {
        if (confirm("Are you sure?")){
            document.gamedata.action = "mainmenu.php";
            document.gamedata.submit();
        }
    }
*/

    function promotepawn()
    {
        var blackPawnFound = false;
        var whitePawnFound = false;
        var i = -1;
        while (!blackPawnFound && !whitePawnFound && i < 8)
        {
            i++;

            /* check for black pawn being promoted */
            if (board[0][i] == (BLACK | PAWN))
                blackPawnFound = true;

            /* check for white pawn being promoted */
            if (board[7][i] == (WHITE | PAWN))
                whitePawnFound = true;
        }

        /* to which piece is the pawn being promoted to? */
        var promotedTo = 0;
        for (var j = 0; j <= 3; j++)
        {
            if (document.gamedata.promotion[j].checked)
                promotedTo = parseInt(document.gamedata.promotion[j].value);
        }

        /* change pawn to promoted piece */
        var ennemyColor = "black";
        if (blackPawnFound)
        {
            ennemyColor = "white";
            board[0][i] = (BLACK | promotedTo);

        }
        else if (whitePawnFound)
        {
            board[7][i] = (WHITE | promotedTo);
        }
        else
            alert("WARNING!: cannot find pawn being promoted!");

        /* verify check and checkmate status */
        if (is_in_check(ennemyColor))
        {
            document.gamedata.is_in_check.value = "true";
            document.gamedata.isCheckMate.value = isCheckMate(ennemyColor);
        }
        else
            document.gamedata.is_in_check.value = "false";

        /* update board and database */
        document.gamedata.submit();
    }

