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
$MSG_LANG["papahat"] = "---- CompWebChess Comm MOD 1 © PapaHat Productions ----";
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
$MSG_LANG["main"] = "Главная";
$MSG_LANG["gamesinprogress"] = "Текущие игры";
$MSG_LANG["therearechallenges"] = "Вам предложили";
$MSG_LANG["therearechallenges-sound"] = "Вам предложили<embed src=http://www.chessmaniac.com/webchess/sounds/ChallengeReceived.mp3 width=1 height=1 autostart=true><noembed><bgsound src=http://www.chessmaniac.com/webchess/sounds/ChallengeReceived.mp3></noembed>";
$MSG_LANG["challenger"] = "Соперник";
$MSG_LANG["reply"] = "Ответ";
$MSG_LANG["accept"] = "Принять";
$MSG_LANG["reject"] = "Отказать";
$MSG_LANG["opponent"] = "Оппонент";
$MSG_LANG["color"] = "Цвет";
$MSG_LANG["turn"] = "Ход";
$MSG_LANG["waiting"] = "Ожидание";
$MSG_LANG["yourturn"] = "Ваш ход";
$MSG_LANG["yourturn-sound"] = "Ваш ход<embed src=http://www.chessmaniac.com/webchess/yourturn.wav width=1 height=1 autostart=true><noembed><bgsound src=http://www.chessmaniac.com/webchess/yourturn.wav></noembed>"; 
$MSG_LANG["start"] = "Начало";
$MSG_LANG["lastmove"] = "Последний ход";
$MSG_LANG["youdonthavegames"] = "У Вас нет активных игр";
$MSG_LANG["statisticiansofthewebchess"] = "Статистика на сервере";
$MSG_LANG["activeplayers"] = "Активные игроки";
$MSG_LANG["activegames"] = "Активные игры";
$MSG_LANG["finishedgames"] = "Оконченные игры";
$MSG_LANG["users"] = "Всего игроков";
$MSG_LANG["longergame"] = "Самая продолжительная игра";
$MSG_LANG["lastuserregistered"] = "Последним зарегистрировался";
$MSG_LANG["gamesfinishedrecently"] = "Недавно оконченные игры";
$MSG_LANG["forums"] = "Посетить форум";
# Ranking #
$MSG_LANG["ranking"] = "Рейтинг";
$MSG_LANG["monthlyranking"] = "Рейтинг месяца";
$MSG_LANG["player"] = "Игрок";
$MSG_LANG["max"] = "Максимально";
$MSG_LANG["rating"] = "Рейтинг";
$MSG_LANG["official"] = "Официально";
$MSG_LANG["page"] = "Страница";

# Desafios #
$MSG_LANG["challenges"] = "Вызовы";
$MSG_LANG["invitesomebodyforanewgame"] = "Пригласить на новую игру";
$MSG_LANG["playwiththecomputer"] = "Играть с компьютером";
$MSG_LANG["orderbyname"] = "Сортировать по имени";
$MSG_LANG["orderbyrating"] = "Сортировать по рейтингу";
$MSG_LANG['orderbylocalization'] = "Соритировать по местонахождению";
$MSG_LANG["playersofmylevel"] = "Игроки моего уровня";
$MSG_LANG["mylevel"] = "Мой уровень";
$MSG_LANG["yourcolor"] = "Ваш цвет";
$MSG_LANG["white"] = "Белые";
$MSG_LANG["black"] = "Черные";
$MSG_LANG["random"] = "Случайный";
$MSG_LANG["seestatistics"] = "Смотреть статистику";
$MSG_LANG["invite"] = "Пригласить";
$MSG_LANG["cancel"] = "Отменить";
$MSG_LANG["currentinvitations"] = "Текущие вызовы";
$MSG_LANG["adversary"] = "Соперник";
$MSG_LANG["status"] = "Статус";
$MSG_LANG["action"] = "Действие";
$MSG_LANG["noopponent"] = "Нет оппонентов";
$MSG_LANG["noinvations"] = "В настоящий момент приглашений нет";
$MSG_LANG["isofficial"] = "Играть официальную партию";
$MSG_LANG["official"] = "Официально";
$MSG_LANG["type"] = "Тип";
$MSG_LANG["notofficial"] = "Неофициально";
$MSG_LANG["onlineplayers"] = "Игроки в сети";
$MSG_LANG["pendingreply"] = "Ожидание ответа";
$MSG_LANG["invitedeclined"] = "Вызов отклонен";
$MSG_LANG["hints"] = "Подсказка";
$MSG_LANG["hint1"] = "- Нажмите на кнопку, чтобы пригласить игрока";
$MSG_LANG["hint2"] = "Нажмите на заголовок таблицы, чтобы отсортировать записи по соответствующему критерию";
$MSG_LANG["hint3"] = "Нажмите на кнопку Статус и будете видеть игроков, которые сейчас в сети";
$MSG_LANG["details"] = "Подробнее";

# Regras #
$MSG_LANG["rules"] = "Правила";
$MSG_LANG["help"] = "Помощь";

# Todos os Jogos #
$MSG_LANG["allgames"] = "Все игры";
$MSG_LANG["thegamesstillhadbelownotbeenfinished"] = "Начатые игры";
$MSG_LANG["alltheactivegames"] = "Все активные игры";
$MSG_LANG["players"] = "Игроки";
$MSG_LANG["rounds"] = "Ход";

# Configuracoes #
$MSG_LANG["configurations"] = "Настройки";
$MSG_LANG["personalinformation"] = "Персональная информация";
$MSG_LANG["name"] = "Имя";
$MSG_LANG["nick"] = "Регистрационное имя";
$MSG_LANG["oldpassword"] = "Старый пароль";
$MSG_LANG["newpassword"] = "Новый пароль";
$MSG_LANG["confirmpassword"] = "Подтвердите пароль";
$MSG_LANG["changeinformations"] = "Изменить информацию";
$MSG_LANG["currentpreferences"] = "Текущие настройки";
$MSG_LANG["history"] = "ИСТОРИЯ";
$MSG_LANG["boardsize"] = "Размер доски";
$MSG_LANG["theme"] = "Тема";
$MSG_LANG["refresh"] = "Обновлять каждые";
$MSG_LANG["micro"] = "Микро";
$MSG_LANG["mini"] = "Мини";
$MSG_LANG["small"] = "Маленький";
$MSG_LANG["medium"] = "Средний";
$MSG_LANG["large"] = "Большой";
$MSG_LANG["e-mailnotification"] = "Подтверждение по электронной почте";
$MSG_LANG["emailmsg"] = "Введите ваш e-mail, если хотите получать уведомления о ходе противника";
$MSG_LANG["language"] = "Язык";
$MSG_LANG["english"] = "English";
$MSG_LANG["brazilian"] = "Brazilian Portuguese";
$MSG_LANG["spanish"] = "Espan'ol";
$MSG_LANG["italian"] = "Italiano";
$MSG_LANG["french"] = "French";
$MSG_LANG["german"] = "Deutch";

# Estatisticas #
$MSG_LANG["statistics"] = "Cтатистика";
$MSG_LANG["localization"] = "Место нахождения";
$MSG_LANG["level"] = "Уровень";
$MSG_LANG["finishedgames"] = "Оконченные игры";
$MSG_LANG["victories"] = "Победы";
$MSG_LANG["defeats"] = "Поражения";
$MSG_LANG["draw"] = "Ничья";
$MSG_LANG["draw2"] = "Ничья";
$MSG_LANG["win"] = "Победа";
$MSG_LANG["lost"] = "Поражение";
$MSG_LANG["activegames"] = "Активные игры";
$MSG_LANG["description"] = "Описание";
$MSG_LANG["situation"] = "Ситуация";
$MSG_LANG["turns"] = "Ходы";
$MSG_LANG["ending"] = "Окончание";
$MSG_LANG["duration"] = "Продолжительность";
$MSG_LANG["punctuation"] = "Очки";
$MSG_LANG["challengerate"] = "Рейтинг вызова";
$MSG_LANG["ifwin"] = "очков в случае победы";
$MSG_LANG["iflose"] = "в случае поражения";
$MSG_LANG["impossible"] = "Невозможно";
$MSG_LANG["verydifficult"] = "Очень тяжелый";
$MSG_LANG["difficult"] = "Тяжелый";
$MSG_LANG["veryeasy"] = "Очень легкий";
$MSG_LANG["easy"] = "Легкий";
$MSG_LANG["normal"] = "Нормальный";
$MSG_LANG["nextlevel"] = "Следующий уровень";
$MSG_LANG["gameno"] = "Игра";
$MSG_LANG["resigned"] = "Сдался";
$MSG_LANG["checkmate"] = "Мат";
$MSG_LANG["resign"] = "Сдаться";
$MSG_LANG['days'] = "дн.";
$MSG_LANG['day'] = "День";
$MSG_LANG['hs'] = "ч";
$MSG_LANG['min'] = "мин";
$MSG_LANG["nogames"] = "Игр нет";

# Chess
$MSG_LANG["opponentturn"] = "Ход оппонента";
$MSG_LANG["isincheck"] = "Шах";
$MSG_LANG["movement"] = "Ход";
$MSG_LANG["write"] = "OK";
$MSG_LANG["refresh2"] = "Обновить";
$MSG_LANG["undomove"] = "Отменить ход";
$MSG_LANG["askdraw"] = "Ничья";
$MSG_LANG["back"] = "Назад";
$MSG_LANG["newgame"] = "азад";
$MSG_LANG["promotepawnto"] = "Обменять пешку на";
$MSG_LANG["requestundo"] = "Ваш оппонент просит отменить последний ход. Вы согласны?";
$MSG_LANG["yes"] = "Да";
$MSG_LANG["no"] = "Нет";
$MSG_LANG["queen"] = "Ферзь";
$MSG_LANG["rook"] = "ЛадьЯ";
$MSG_LANG["knight"] = "Конь";
$MSG_LANG["bishop"] = "Слон";
$MSG_LANG["promote"] = "Обменять";
$MSG_LANG["drawrequest"] = "Ваш оппонент предлагает ничью. Вы согласны?<embed src=http://www.chessmaniac.com/webchess/sounds/Draw.wav width=1 height=1 autostart=true><noembed><bgsound src=http://www.chessmaniac.com/webchess/sounds/Draw.wav></noembed>"; 

$MSG_LANG["logoff"] = "Выход";
$MSG_LANG["yourrating"] ="Ваш рейтинг";
$MSG_LANG["welcome"] = "Добро пожаловать";
$MSG_LANG["username"] = "Имя пользователя";
$MSG_LANG["password"] = "Пароль";
$MSG_LANG["login"] = "Войти";
$MSG_LANG["createuser"] = "Вы еще не зарегистрировались? Нажимайте сюда :).";
$MSG_LANG["newuser"] = "Новый пользователь";
$MSG_LANG["birthdate"] = "Дата рождения";
$MSG_LANG["gender"] = "Пол";
$MSG_LANG["male"] = "Мужской";
$MSG_LANG["female"] = "Женский";
$MSG_LANG["month"] = "Месяц";
$MSG_LANG["year"] = "Год";
$MSG_LANG["address"] = "Адрес";
$MSG_LANG["city"] = "Город";
$MSG_LANG["state"] = "Область";
$MSG_LANG["zip"] = "Индекс";
$MSG_LANG["country"] = "Страна";
$MSG_LANG["finish"] = "Закончить";

$MSG_LANG["#multiplegames"] = "Я уже играю с тобой";
$MSG_LANG["#weakplayer"] = "Ваш рейтиг слишком низкий чтобы играть с этим игроком";
$MSG_LANG["yourranking"] = "Место";
$MSG_LANG["endsincheckmate"] = "Игра закончится в случае мата!";
$MSG_LANG["endsindraw"] = "Игра закончится вничью!";
$MSG_LANG["youdonthavepermission"] = "У вас нет доступа";
$MSG_LANG["analyze"] = "Анализировать эту игру";
$MSG_LANG["youdontplaywith"] = "Вы не играете этим цветом";
$MSG_LANG["roundwarning"] = " или более ходов должны быть сделаны, чтобы результат учитывался в рейтинге. Продолжить?";
$MSG_LANG["undowarning"] = "Вы действительно хотите отменить последний ход?";
$MSG_LANG["check2password"] = "Пароль неверный";
$MSG_LANG["existinguser"] = "Извините, но такое имя уже используется";
$MSG_LANG["timeofgame"] = "Время игры";
$MSG_LANG["games"] = "игр";
$MSG_LANG["active"] = "Текущий";
$MSG_LANG["finished"] = "Окончено";
$MSG_LANG["mandatory"] = "(*) - обязательные для заполнения поля; все остальные по желанию";
$MSG_LANG["firstbox"] = "Пожалуйста, введите имя. Желательно латинскими буквами.";
$MSG_LANG["secondbox"] = "Пожалуйста, введите регистрационное имя (login name). Желательно латинскими буквами.";
$MSG_LANG["play"] = "Играть";
$MSG_LANG["edit"] = "Редактировать";
$MSG_LANG['opponentlanguage'] = "Язык оппонента";
$MSG_LANG["autoaccept"] = "Автоматически принимать вызовы";
$MSG_LANG["rejectall"] = "Автоматически отказывать всем";
$MSG_LANG["acceptall"] = "Автоматически принимать все";
$MSG_LANG["acceptnone"] = "Не принимать автоматически";
$MSG_LANG["mostactiveplayer"] = "Наиболее активные игроки";
$MSG_LANG["challengethisplayer"] = "Нажмите здесь, если хотите играть с этим игроком";
$MSG_LANG["news"] = "Новости";
$MSG_LANG["teams"] = "Команды";
$MSG_LANG["noteam"] = "В настоящий момент вы не являетесь членом команды";
$MSG_LANG["teammember"] = "Вы член команды";
$MSG_LANG["teamleader"] = "Вы лидер команды";
$MSG_LANG["createteam"] = "Создать команду";
$MSG_LANG["chooseteam"] = "Выбрать команду";
$MSG_LANG["transferleadership"] = "Перевести лидерство";
$MSG_LANG["deleteteam"] = "Удалить команду";
$MSG_LANG["teammembers"] = "Члены команды";
$MSG_LANG["pending"] = "Ожидание";
$MSG_LANG["delete"] = "Удалить";
$MSG_LANG["initial"] = "Начальный";
$MSG_LANG["teamranking"] = "Рейтинг команды";
$MSG_LANG["trust"] = "Надежность";
$MSG_LANG["exitteam"] = "Покинуть команду";
$MSG_LANG['members'] = "Члены";
$MSG_LANG['creator'] = "Создатель";
$MSG_LANG['leader'] = "Лидер";
$MSG_LANG['created'] = "Создан в";
$MSG_LANG['team'] = "Команда";
$MSG_LANG['asktojoin'] = "Предложить присоединиться";
$MSG_LANG['chooseateamtojoin'] = "Выберите команду, к которой вы хотите присоединиться";
$MSG_LANG["membershippending"] = "Подождите подтверждение вашего членства";
$MSG_LANG["areyousure"] = "Вы уверены?";
$MSG_LANG['createateam'] = "Создать команду";
$MSG_LANG['create'] = "Создать";
$MSG_LANG["membershiprejected"] = "Вам было отказано в членстве";
$MSG_LANG["ratingevolution"] = "Изменение рейтинга";

$MSG_LANG["dhms"] = "д:ч:м:с";
$MSG_LANG["drawapproved"] = "Ничья одобрена";
$MSG_LANG["drawdenied"] = "Ничья отклонена";
$MSG_LANG["undoapproved"] = "Отмена хода одобрена";
$MSG_LANG["undodenied"] = "Отмена хода отклонена";
$MSG_LANG["undopending"] = "Ожидается ответ на запрос отменить ход";
$MSG_LANG["drawpending"] = "Ожидается ответ на предложение ничьи";
$MSG_LANG["endindraw"] = "Игра закончилась в ничью";
$MSG_LANG["wonthegame"] = "выиграли игру";
$MSG_LANG["quote"] = "Случайные цитаты и интересные факты";
$MSG_LANG["pieceset"] = "Вид фигур";
$MSG_LANG["forgotpassword"] = "Забыли пароль?";
$MSG_LANG["whereareyoufrom"] = "Откуда вы?";
$MSG_LANG["validemail"] = "Введите реальный e-mail адрес";
$MSG_LANG["validatiommsg"] = "Профиль создан, для его активизации нажмите на ссылку в отправленном на ваш электронный адрес письме.";
$MSG_LANG["dutch"] = "Dutch";
$MSG_LANG["timeforeach"] = "Лимит игры";
$MSG_LANG["theflaghasfallen"] = "Падение флажка";
$MSG_LANG["lastseen"] = "Последний раз был(а)";
$MSG_LANG["polish"] = "Polish";
$MSG_LANG["waitingpromotion"] = "В ожидании, пока противник поменяет свою пешку";
$MSG_LANG["confirmmove"] = "Подтвердить ход";
$MSG_LANG["gamesettings"] = "Установки игры";
$MSG_LANG["movetimeout"] = "Лимит на ход";
$MSG_LANG["sendmypassword"] = "Послать мой пароль";
$MSG_LANG["passwordsent"] = "Ваш пароль был отослан на e-mail";
$MSG_LANG["unlimited"] = "Без ограничений";
$MSG_LANG["medals"] = "Медали";
$MSG_LANG["russian"] = "Russian";
$MSG_LANG["leavesthechat"] = " покинул чат";
$MSG_LANG["entersthechat"] = " вошел в чат";
$MSG_LANG["welcomechat"] = "Welcome to the Public Chatroom";
$MSG_LANG["chatempty"] = "пока никого";
$MSG_LANG["useronlinechat"] = "Online-Chat";
$MSG_LANG["enter2chat"] = "Войти в чат";

//Translate:
$MSG_LANG["welcomeavatar"] = "Hello";
$MSG_LANG["avatarinfo"] = "Upload a small graphic image for your statistics! <br>Sollten Sie schon einen Avatar haben und einen neuen uploaden, so wird dieser ьberschrieben!";
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
$MSG_LANG["nameoftournament2"] = "ґs Tournament";
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
