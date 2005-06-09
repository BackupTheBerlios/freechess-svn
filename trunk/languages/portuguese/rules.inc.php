<head>
<LINK rel="stylesheet" href="themes/<?=$_SESSION["pref_colortheme"]?>/styles.css" type="text/css">
</head>
<body bgcolor=white text=black>

<font face="verdana" size="2">
<? require_once('header.inc.php');?>
<BR>

<?

	include("rulespage/$_page")
?>






<form name="logout" action="mainmenu.php" method="post">
<input type="hidden" name="ToDo" value="Logout">
</form>
</body>
