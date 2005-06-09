<?php
//header("Content-Type: text/html; charset=windows-1251");
/*
	Russian Translation by Yuri Volodin
	Second Edition by Michael Mushaljuk

*/
//CONTENT TYPE, REGIONAL SETTINGS, ETC SHOULD BE IN THE regional_settings.php file of languages
//this file will then contain correct values for content type, etc that we may send to smarty.

/*
echo '
<html><head>
<META HTTP-EQUIV="Content-Language" CONTENT=ru>
<meta http-equiv="Content-Type" charset="x-mac-cyrillic">
<meta http-equiv="Content-Type" charset="windows-1251">
<meta http-equiv="Content-Type" charset="cp866">
<meta http-equiv="Content-Type" charset="iso-8859-5">
<Meta HTTP-EQUIV="Content-Type" Content="text/html; charset=koi8-r">
</head>';
*/
/*
echo '
<html><head>
<Meta HTTP-EQUIV="Content-Type" Content="text/html; charset=windows-1251">
</head>';

*/
# Principal #
$MSG_LANG["pm"] = "Private Messages for ";
$MSG_LANG["sendpm"] = "Click Here To Send Someone A Private Message";
$MSG_LANG["to"] = "To:";
$MSG_LANG["from"] = "From: ";
$MSG_LANG["date"] = "Date: ";
$MSG_LANG["home"] = "Home Page";
$MSG_LANG["title"] = "Title: ";
$MSG_LANG["re"] = "Re: ";
$MSG_LANG["sendmessage"] = "Send Message";
$MSG_LANG["msgsubject"] = "Message Subject";
$MSG_LANG["msgtext"] = "Message Text";
$MSG_LANG["papahat"] = "---- CompWebChess Comm MOD 1 � PapaHat Productions ----";
$MSG_LANG["sendpmtext3"] = "You have New Messages.";
$MSG_LANG["sendpmtext3-sound"] = "New Messages!<embed src=http://www.chessmaniac.com/webchess/sounds/hal-msg.wav width=1 height=1 autostart=true><noembed><bgsound src=http://www.chessmaniac.com/webchess/sounds/hal-msg.wav.mp3></noembed>";
$MSG_LANG["sendpmtext4"] = "Click here!";
$MSG_LANG["commadmin"] = "Comm Admin Section";
$MSG_LANG["postnewsitem"] = "Post News Item";
$MSG_LANG["postadminmessage"] = "Post Admin Message";
$MSG_LANG["postloginadminmessage"] = "Post Login Admin Message";
$MSG_LANG["posttournamentsadminmessage"] = "Post Tournaments Admin Message";
$MSG_LANG["postteamsadminmessage"] = "Post Teams Admin Message";
$MSG_LANG["deletenewsitem"] = "Delete News Item";
$MSG_LANG["deleteadminmessage"] = "Delete Admin Message";
$MSG_LANG["deleteloginadminmessage"] = "Delete Login Admin Message";
$MSG_LANG["deletetournamentsadminmessage"] = "Delete Tournaments Admin Message";
$MSG_LANG["deleteteamsadminmessage"] = "Delete Teams Admin Message";
$MSG_LANG["idnews"] = "idNews";
$MSG_LANG["commid"] = "commID";
$MSG_LANG["message"] = "Message";
$MSG_LANG["nwmsg"] = "New Messages";
$MSG_LANG["clkhere"] = "Click Here";
$MSG_LANG["mygames"] = "My Games";
$MSG_LANG["nonew"] = "No New";
$MSG_LANG["main"] = "�������";
$MSG_LANG["gamesinprogress"] = "������� ����";
$MSG_LANG["therearechallenges"] = "��� ����������";
$MSG_LANG["therearechallenges-sound"] = "��� ����������<embed src=http://www.chessmaniac.com/webchess/sounds/ChallengeReceived.mp3 width=1 height=1 autostart=true><noembed><bgsound src=http://www.chessmaniac.com/webchess/sounds/ChallengeReceived.mp3></noembed>";
$MSG_LANG["challenger"] = "��������";
$MSG_LANG["reply"] = "�����";
$MSG_LANG["accept"] = "�������";
$MSG_LANG["reject"] = "��������";
$MSG_LANG["opponent"] = "��������";
$MSG_LANG["color"] = "����";
$MSG_LANG["turn"] = "���";
$MSG_LANG["waiting"] = "��������";
$MSG_LANG["yourturn"] = "��� ���";
$MSG_LANG["yourturn-sound"] = "��� ���<embed src=http://www.chessmaniac.com/webchess/yourturn.wav width=1 height=1 autostart=true><noembed><bgsound src=http://www.chessmaniac.com/webchess/yourturn.wav></noembed>"; 
$MSG_LANG["start"] = "������";
$MSG_LANG["lastmove"] = "��������� ���";
$MSG_LANG["youdonthavegames"] = "� ��� ��� �������� ���";
$MSG_LANG["statisticiansofthewebchess"] = "���������� �� �������";
$MSG_LANG["activeplayers"] = "�������� ������";
$MSG_LANG["activegames"] = "�������� ����";
$MSG_LANG["finishedgames"] = "���������� ����";
$MSG_LANG["users"] = "����� �������";
$MSG_LANG["longergame"] = "����� ��������������� ����";
$MSG_LANG["lastuserregistered"] = "��������� �����������������";
$MSG_LANG["gamesfinishedrecently"] = "������� ���������� ����";
$MSG_LANG["forums"] = "�������� �����";
# Ranking #
$MSG_LANG["ranking"] = "�������";
$MSG_LANG["monthlyranking"] = "������� ������";
$MSG_LANG["player"] = "�����";
$MSG_LANG["max"] = "�����������";
$MSG_LANG["rating"] = "�������";
$MSG_LANG["official"] = "����������";
$MSG_LANG["page"] = "��������";

# Desafios #
$MSG_LANG["challenges"] = "������";
$MSG_LANG["invitesomebodyforanewgame"] = "���������� �� ����� ����";
$MSG_LANG["playwiththecomputer"] = "������ � �����������";
$MSG_LANG["orderbyname"] = "����������� �� �����";
$MSG_LANG["orderbyrating"] = "����������� �� ��������";
$MSG_LANG['orderbylocalization'] = "������������ �� ���������������";
$MSG_LANG["playersofmylevel"] = "������ ����� ������";
$MSG_LANG["mylevel"] = "��� �������";
$MSG_LANG["yourcolor"] = "��� ����";
$MSG_LANG["white"] = "�����";
$MSG_LANG["black"] = "������";
$MSG_LANG["random"] = "���������";
$MSG_LANG["seestatistics"] = "�������� ����������";
$MSG_LANG["invite"] = "����������";
$MSG_LANG["cancel"] = "��������";
$MSG_LANG["currentinvitations"] = "������� ������";
$MSG_LANG["adversary"] = "��������";
$MSG_LANG["status"] = "������";
$MSG_LANG["action"] = "��������";
$MSG_LANG["noopponent"] = "��� ����������";
$MSG_LANG["noinvations"] = "� ��������� ������ ����������� ���";
$MSG_LANG["isofficial"] = "������ ����������� ������";
$MSG_LANG["official"] = "����������";
$MSG_LANG["type"] = "���";
$MSG_LANG["notofficial"] = "������������";
$MSG_LANG["onlineplayers"] = "������ � ����";
$MSG_LANG["pendingreply"] = "�������� ������";
$MSG_LANG["invitedeclined"] = "����� ��������";
$MSG_LANG["hints"] = "���������";
$MSG_LANG["hint1"] = "- ������� �� ������, ����� ���������� ������";
$MSG_LANG["hint2"] = "������� �� ��������� �������, ����� ������������� ������ �� ���������������� ��������";
$MSG_LANG["hint3"] = "������� �� ������ ������ � ������ ������ �������, ������� ������ � ����";
$MSG_LANG["details"] = "���������";

# Regras #
$MSG_LANG["rules"] = "�������";
$MSG_LANG["help"] = "������";

# Todos os Jogos #
$MSG_LANG["allgames"] = "��� ����";
$MSG_LANG["thegamesstillhadbelownotbeenfinished"] = "������� ����";
$MSG_LANG["alltheactivegames"] = "��� �������� ����";
$MSG_LANG["players"] = "������";
$MSG_LANG["rounds"] = "���";

# Configuracoes #
$MSG_LANG["configurations"] = "���������";
$MSG_LANG["personalinformation"] = "������������ ����������";
$MSG_LANG["name"] = "���";
$MSG_LANG["nick"] = "��������������� ���";
$MSG_LANG["oldpassword"] = "������ ������";
$MSG_LANG["newpassword"] = "����� ������";
$MSG_LANG["confirmpassword"] = "����������� ������";
$MSG_LANG["changeinformations"] = "�������� ����������";
$MSG_LANG["currentpreferences"] = "������� ���������";
$MSG_LANG["history"] = "�������";
$MSG_LANG["boardsize"] = "������ �����";
$MSG_LANG["theme"] = "����";
$MSG_LANG["refresh"] = "��������� ������";
$MSG_LANG["micro"] = "�����";
$MSG_LANG["mini"] = "����";
$MSG_LANG["small"] = "���������";
$MSG_LANG["medium"] = "�������";
$MSG_LANG["large"] = "�������";
$MSG_LANG["e-mailnotification"] = "������������� �� ����������� �����";
$MSG_LANG["emailmsg"] = "������� ��� e-mail, ���� ������ �������� ����������� � ���� ����������";
$MSG_LANG["language"] = "����";
$MSG_LANG["english"] = "English";
$MSG_LANG["brazilian"] = "Brazilian Portuguese";
$MSG_LANG["spanish"] = "Espan'ol";
$MSG_LANG["italian"] = "Italiano";
$MSG_LANG["french"] = "French";
$MSG_LANG["german"] = "Deutch";

# Estatisticas #
$MSG_LANG["statistics"] = "C���������";
$MSG_LANG["localization"] = "����� ����������";
$MSG_LANG["level"] = "�������";
$MSG_LANG["finishedgames"] = "���������� ����";
$MSG_LANG["victories"] = "������";
$MSG_LANG["defeats"] = "���������";
$MSG_LANG["draw"] = "�����";
$MSG_LANG["draw2"] = "�����";
$MSG_LANG["win"] = "������";
$MSG_LANG["lost"] = "���������";
$MSG_LANG["activegames"] = "�������� ����";
$MSG_LANG["description"] = "��������";
$MSG_LANG["situation"] = "��������";
$MSG_LANG["turns"] = "����";
$MSG_LANG["ending"] = "���������";
$MSG_LANG["duration"] = "�����������������";
$MSG_LANG["punctuation"] = "����";
$MSG_LANG["challengerate"] = "������� ������";
$MSG_LANG["ifwin"] = "����� � ������ ������";
$MSG_LANG["iflose"] = "� ������ ���������";
$MSG_LANG["impossible"] = "����������";
$MSG_LANG["verydifficult"] = "����� �������";
$MSG_LANG["difficult"] = "�������";
$MSG_LANG["veryeasy"] = "����� ������";
$MSG_LANG["easy"] = "������";
$MSG_LANG["normal"] = "����������";
$MSG_LANG["nextlevel"] = "��������� �������";
$MSG_LANG["gameno"] = "����";
$MSG_LANG["resigned"] = "������";
$MSG_LANG["checkmate"] = "���";
$MSG_LANG["resign"] = "�������";
$MSG_LANG['days'] = "��.";
$MSG_LANG['day'] = "����";
$MSG_LANG['hs'] = "�";
$MSG_LANG['min'] = "���";
$MSG_LANG["nogames"] = "��� ���";

# Chess
$MSG_LANG["opponentturn"] = "��� ���������";
$MSG_LANG["isincheck"] = "���";
$MSG_LANG["movement"] = "���";
$MSG_LANG["write"] = "OK";
$MSG_LANG["refresh2"] = "��������";
$MSG_LANG["undomove"] = "�������� ���";
$MSG_LANG["askdraw"] = "�����";
$MSG_LANG["back"] = "�����";
$MSG_LANG["newgame"] = "����";
$MSG_LANG["promotepawnto"] = "�������� ����� ��";
$MSG_LANG["requestundo"] = "��� �������� ������ �������� ��������� ���. �� ��������?";
$MSG_LANG["yes"] = "��";
$MSG_LANG["no"] = "���";
$MSG_LANG["queen"] = "�����";
$MSG_LANG["rook"] = "�����";
$MSG_LANG["knight"] = "����";
$MSG_LANG["bishop"] = "����";
$MSG_LANG["promote"] = "��������";
$MSG_LANG["drawrequest"] = "��� �������� ���������� �����. �� ��������?<embed src=http://www.chessmaniac.com/webchess/sounds/Draw.wav width=1 height=1 autostart=true><noembed><bgsound src=http://www.chessmaniac.com/webchess/sounds/Draw.wav></noembed>"; 

$MSG_LANG["logoff"] = "�����";
$MSG_LANG["yourrating"] ="��� �������";
$MSG_LANG["welcome"] = "����� ����������";
$MSG_LANG["username"] = "��� ������������";
$MSG_LANG["password"] = "������";
$MSG_LANG["login"] = "�����";
$MSG_LANG["createuser"] = "�� ��� �� ������������������? ��������� ���� :).";
$MSG_LANG["newuser"] = "����� ������������";
$MSG_LANG["birthdate"] = "���� ��������";
$MSG_LANG["gender"] = "���";
$MSG_LANG["male"] = "�������";
$MSG_LANG["female"] = "�������";
$MSG_LANG["month"] = "�����";
$MSG_LANG["year"] = "���";
$MSG_LANG["address"] = "�����";
$MSG_LANG["city"] = "�����";
$MSG_LANG["state"] = "�������";
$MSG_LANG["zip"] = "������";
$MSG_LANG["country"] = "������";
$MSG_LANG["finish"] = "���������";

$MSG_LANG["#multiplegames"] = "� ��� ����� � �����";
$MSG_LANG["#weakplayer"] = "��� ������ ������� ������ ����� ������ � ���� �������";
$MSG_LANG["yourranking"] = "�����";
$MSG_LANG["endsincheckmate"] = "���� ���������� � ������ ����!";
$MSG_LANG["endsindraw"] = "���� ���������� ������!";
$MSG_LANG["youdonthavepermission"] = "� ��� ��� �������";
$MSG_LANG["analyze"] = "������������� ��� ����";
$MSG_LANG["youdontplaywith"] = "�� �� ������� ���� ������";
$MSG_LANG["roundwarning"] = " ��� ����� ����� ������ ���� �������, ����� ��������� ���������� � ��������. ����������?";
$MSG_LANG["undowarning"] = "�� ������������� ������ �������� ��������� ���?";
$MSG_LANG["check2password"] = "������ ��������";
$MSG_LANG["existinguser"] = "��������, �� ����� ��� ��� ������������";
$MSG_LANG["timeofgame"] = "����� ����";
$MSG_LANG["games"] = "���";
$MSG_LANG["active"] = "�������";
$MSG_LANG["finished"] = "��������";
$MSG_LANG["mandatory"] = "(*) - ������������ ��� ���������� ����; ��� ��������� �� �������";
$MSG_LANG["firstbox"] = "����������, ������� ���. ���������� ���������� �������.";
$MSG_LANG["secondbox"] = "����������, ������� ��������������� ��� (login name). ���������� ���������� �������.";
$MSG_LANG["play"] = "������";
$MSG_LANG["edit"] = "�������������";
$MSG_LANG['opponentlanguage'] = "���� ���������";
$MSG_LANG["autoaccept"] = "������������� ��������� ������";
$MSG_LANG["rejectall"] = "������������� ���������� ����";
$MSG_LANG["acceptall"] = "������������� ��������� ���";
$MSG_LANG["acceptnone"] = "�� ��������� �������������";
$MSG_LANG["mostactiveplayer"] = "�������� �������� ������";
$MSG_LANG["challengethisplayer"] = "������� �����, ���� ������ ������ � ���� �������";
$MSG_LANG["news"] = "�������";
$MSG_LANG["teams"] = "�������";
$MSG_LANG["noteam"] = "� ��������� ������ �� �� ��������� ������ �������";
$MSG_LANG["teammember"] = "�� ���� �������";
$MSG_LANG["teamleader"] = "�� ����� �������";
$MSG_LANG["createteam"] = "������� �������";
$MSG_LANG["chooseteam"] = "������� �������";
$MSG_LANG["transferleadership"] = "��������� ���������";
$MSG_LANG["deleteteam"] = "������� �������";
$MSG_LANG["teammembers"] = "����� �������";
$MSG_LANG["pending"] = "��������";
$MSG_LANG["delete"] = "�������";
$MSG_LANG["initial"] = "���������";
$MSG_LANG["teamranking"] = "������� �������";
$MSG_LANG["trust"] = "����������";
$MSG_LANG["exitteam"] = "�������� �������";
$MSG_LANG['members'] = "�����";
$MSG_LANG['creator'] = "���������";
$MSG_LANG['leader'] = "�����";
$MSG_LANG['created'] = "������ �";
$MSG_LANG['team'] = "�������";
$MSG_LANG['asktojoin'] = "���������� ��������������";
$MSG_LANG['chooseateamtojoin'] = "�������� �������, � ������� �� ������ ��������������";
$MSG_LANG["membershippending"] = "��������� ������������� ������ ��������";
$MSG_LANG["areyousure"] = "�� �������?";
$MSG_LANG['createateam'] = "������� �������";
$MSG_LANG['create'] = "�������";
$MSG_LANG["membershiprejected"] = "��� ���� �������� � ��������";
$MSG_LANG["ratingevolution"] = "��������� ��������";

$MSG_LANG["dhms"] = "�:�:�:�";
$MSG_LANG["drawapproved"] = "����� ��������";
$MSG_LANG["drawdenied"] = "����� ���������";
$MSG_LANG["undoapproved"] = "������ ���� ��������";
$MSG_LANG["undodenied"] = "������ ���� ���������";
$MSG_LANG["undopending"] = "��������� ����� �� ������ �������� ���";
$MSG_LANG["drawpending"] = "��������� ����� �� ����������� �����";
$MSG_LANG["endindraw"] = "���� ����������� � �����";
$MSG_LANG["wonthegame"] = "�������� ����";
$MSG_LANG["quote"] = "��������� ������ � ���������� �����";
$MSG_LANG["pieceset"] = "��� �����";
$MSG_LANG["forgotpassword"] = "������ ������?";
$MSG_LANG["whereareyoufrom"] = "������ ��?";
$MSG_LANG["validemail"] = "������� �������� e-mail �����";
$MSG_LANG["validatiommsg"] = "������� ������, ��� ��� ����������� ������� �� ������ � ������������ �� ��� ����������� ����� ������.";
$MSG_LANG["dutch"] = "Dutch";
$MSG_LANG["timeforeach"] = "����� ����";
$MSG_LANG["theflaghasfallen"] = "������� ������";
$MSG_LANG["lastseen"] = "��������� ��� ���(�)";
$MSG_LANG["polish"] = "Polish";
$MSG_LANG["waitingpromotion"] = "� ��������, ���� ��������� �������� ���� �����";
$MSG_LANG["confirmmove"] = "����������� ���";
$MSG_LANG["gamesettings"] = "��������� ����";
$MSG_LANG["movetimeout"] = "����� �� ���";
$MSG_LANG["sendmypassword"] = "������� ��� ������";
$MSG_LANG["passwordsent"] = "��� ������ ��� ������� �� e-mail";
$MSG_LANG["unlimited"] = "��� �����������";
$MSG_LANG["medals"] = "������";
$MSG_LANG["russian"] = "Russian";
$MSG_LANG["leavesthechat"] = " ������� ���";
$MSG_LANG["entersthechat"] = " ����� � ���";
$MSG_LANG["welcomechat"] = "Welcome to the Public Chatroom";
$MSG_LANG["chatempty"] = "���� ������";
$MSG_LANG["useronlinechat"] = "Online-Chat";
$MSG_LANG["enter2chat"] = "����� � ���";

//Translate:
$MSG_LANG["welcomeavatar"] = "Hello";
$MSG_LANG["avatarinfo"] = "Upload a small graphic image for your statistics! <br>Sollten Sie schon einen Avatar haben und einen neuen uploaden, so wird dieser �berschrieben!";
$MSG_LANG["avatar1"] = "Current Image:";
$MSG_LANG["avatardelete"] = "Delete Image";
$MSG_LANG["avatar2"] = "Select Avatar:";
$MSG_LANG["avatar3"] = "Extension not allowed!";
$MSG_LANG["avatar4"] = "your file is too big, maximum ";
$MSG_LANG["avataradd"] = "Add an Avatar";
#Tournaments#
$MSG_LANG["tournament"] = "Tournament";
$MSG_LANG["number"] = "No.";
$MSG_LANG["tournamentmain"] = "Tournament";
$MSG_LANG["tournamentheader"] = "Tournament";
$MSG_LANG["mytournaments"] = "My Tournaments";
$MSG_LANG["tournamentsuccess"] = "You successfully joined the tournament: ";
$MSG_LANG["tournamentinvite2"] = "players needed to begin the tournament!";
$MSG_LANG["tournamentwhy"] = "This tournament still needs players - why don't you join?";
$MSG_LANG["tournamentwhy2"] = "Waiting for other players";
$MSG_LANG["tournamentalready2"] = "This Tournament has already enough players.";
$MSG_LANG["tournamentalready"] = "You already joined this tournament!";
$MSG_LANG["tournamentcreated"] = "You created this tournament.";
$MSG_LANG["tournamentclick"] = "Click here to join!";
$MSG_LANG["tournamentinvite1"] = "Another";
$MSG_LANG["createtournament"] = "Create Tournament";
$MSG_LANG["createtournamenttext"] = "You can create a tournament by filling in the form below. You can choose your own title by deleting the default title and write a description for your tournament. Select the minimum and maximum rating of the players you would like to join and click the 'Submit' button..";
$MSG_LANG["nameoftournament"] = "Name of Tournament";
$MSG_LANG["nameoftournament2"] = "�s Tournament";
$MSG_LANG["tournamentrankings3"] = "peer group";
$MSG_LANG["tournamentcross1"] = "Click";
$MSG_LANG["tournamentcross2"] = "here";
$MSG_LANG["liveranking"] = "Live Ranking";
$MSG_LANG["tournamentcross3"] = " to view the crosstable!";
$MSG_LANG["jointournaments"] = "Join Tournaments";
$MSG_LANG["tournamenthistory"] = "Tournament History";
$MSG_LANG["tournamentplayers"] = "Players (max.)";
$MSG_LANG["tournamentrankings"] = "Tournament Rankings";
$MSG_LANG["tournamentwins"] = "Wins";
$MSG_LANG["tournamentdraws"] = "Draws";
$MSG_LANG["tournamentlosses"] = "Losses";
$MSG_LANG["stillplayinggame"] = "Game";
$MSG_LANG["tournamentlistof"] = "List of players";
$MSG_LANG["stillplaying"] = "Still playing";
$MSG_LANG["tournamentrankings2"] = "Ranking";
$MSG_LANG["tournamentsorry"] = "Sorry, but you are too good/bad for this tournament.";
$MSG_LANG["tournament44"] = "This Tournament has 4 of 4 players.";
$MSG_LANG["tournamentofficialrating"] = "Game results receive also an official rating";
$MSG_LANG["trialprocess"] = "### RATING TRIAL PROCCESS ###";
$MSG_LANG["trialmessage"] = "You need to finish %n games to receive a oficial rating.";

$MSG_LANG["chooseplayertojoin"] = "Choose the player you want to inveite.";
$MSG_LANG["youwasinvitedtojoin"] = "You was invited to join the team: ";
$MSG_LANG["join"] = "Join";
$MSG_LANG["teamfull"] = "This team already have the maximum number of allowed players";
$MSG_LANG["quickgame"] = "Active Games";
$MSG_LANG["noquickgames"] = "There is no games in your turn";
$MSG_LANG["playwithnewusers"] = "New Users";
?>
