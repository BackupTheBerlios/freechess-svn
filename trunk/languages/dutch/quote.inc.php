<?php

/*
    This script was contributed by Dennis Steele Web Master of ChessManiac.com
	Add this line of code in your page:
    <?php include "randomchessquote.php"; ?>
*/

$quotes[] = "'over schaken heeft men gezegd dat het leven er niet lang genoeg voor is, maar dat is de schuld van het leven, niet van het schaken.' -- Irving Chernev";
$quotes[] = "'Schaken is geestelijke marteling.' -- KASPAROV";
$quotes[] = "'Bij twijfel -- ga schaken.' -- TEVIS";
$quotes[] = "'Het leven is te kort om te schaken.' -- BYRON";
$quotes[] = "'De verliezer heeft altijd gefaald.' -- PANOV";
$quotes[] = "'Het schaken is de kunst van het analyseren.' -- BOTVINNIK";
$quotes[] = "'Het schaken was Capablanca's moedertaal.' -- RETI";
$quotes[] = "'Laat de perfectionist door het lint gaan.' -- SEIRAWAN";
$quotes[] = "'Eén slechte zet doet veertig goede te niet.' -- HOROWITZ";
$quotes[] = "'Hoe ouder ik word, hoe meer waarde ik hecht aan pionnen.' -- KERES";
$quotes[] = "'De moeilijkste partij om te winnen is een gewonnen partij.' -- Em. LASKER";
$quotes[] = "'Er wordt gewoon niet genoeg schaak op de televisie uitgezonden.' -- LETTERMAN";
$quotes[] = "'Wanneer het spel tactisch wordt, komen de computers op dreef.' -- HYATT";
$quotes[] = "'Morphy was waarschijnlijk het grootste genie van allemaal.' -- FISCHER";
$quotes[] = "'Er zijn twee typen offers: de correcte en de mijne.' -- TAL";
$quotes[] = "'Roem, dat heb ik al. Nu heb ik geld nodig.' -- an elderly STEINITZ";
$quotes[] = "'Een geïsoleerde pion straalt mistroostigheid over het hele schaakbord uit.' -- TARTAKOVER";
$quotes[] = "'De eerste regel van de aanval--Laat de tegenstander niet zich niet ontwikkelen!' -- FINE";
$quotes[] = "'Wanneer je een goeie zet ziet--wacht--en zoek naar een betere.' -- Em. LASKER ";
$quotes[] = "'Op het schaakbord overleven leugens en schijnheiligheid niet lang.' -- Em. LASKER ";
$quotes[] = "'Ik denk dat het bijna vaststaat dat een partij theoretisch altijd eindigt met gelijkspel.' -- FISCHER ";


srand ((double) microtime() * 1000000);
$randomquote = rand(0,count($quotes)-1);

echo "<p>" . $quotes[$randomquote] . "</p>";

?> 
