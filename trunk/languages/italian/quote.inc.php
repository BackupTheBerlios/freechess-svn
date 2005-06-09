<?php

/*
    This script was contributed by Dennis Steele Web Master of ChessManiac.com
	Add this line of code in your page:
    <?php include "randomchessquote.php"; ?>
*/

$quotes[] = "Degli scacchi &egrave; stato detto che la vita non &egrave; abbastanza lunga per giocarci, ma &egrave; colpa della vita, non degli scacchi. -- Irving Chernev";
$quotes[] = "'Gli scacchi sono tortura della mente.' -- KASPAROV";
$quotes[] = "'Se hai un dubbio -- gioca a scacchi.' -- TEVIS";
$quotes[] = "'La vita &egrave; troppo breve per gli scacchi.' -- BYRON";
$quotes[] = "'Lo sconfitto &egrave; sempre in errore.' -- PANOV";
$quotes[] = "'Gli scacchi sono l'arte dell'analisi.' -- BOTVINNIK";
$quotes[] = "'Gli scacchi erano la lingua della madre di Capablanca.' -- RETI";
$quotes[] = "'Lascia che i perfezionisti giochino via posta.' -- SEIRAWAN";
$quotes[] = "'Una mossa cattiva ne annulla quaranta buone.' -- HOROWITZ";
$quotes[] = "'Pi&ugrave; cresco, pi&ugrave; capisco il valore dei Pedoni.' -- KERES";
$quotes[] = "'La partita pi&ugrave difficile da vincere &egrave; una partita vinta.' -- Em. LASKER";
$quotes[] = "'Non ci sono abbastanza scacchi televised .' -- LETTERMAN";
$quotes[] = "'Quando il gioco si fa tattico, i computers iniziano a giocare.' -- HYATT";
$quotes[] = "'Morphy &egrave; stato probabilmente il genio pi&ugrave; grande di tutti.' -- FISCHER";
$quotes[] = "'Ci sono due tipi di sacrifici: quelli corretti ed i miei.' -- TAL";
$quotes[] = "'Fama ne ho gi&agrave;. Adesso voglio i soldi.' -- an elderly STEINITZ";
$quotes[] = "'Un pedone passato illumina tutta la scacchiera.' -- TARTAKOVER";
$quotes[] = "'Il primo pricipio dell'attaco--Non far sviluppare l'avversario!' -- FINE";
$quotes[] = "'Quando vedi una buona mossa--aspetta--cercane una migliore.' -- Em. LASKER ";
$quotes[] = "'Sulla scacchiera le bugie e l'ipocrisia non sopravvivono a lungo.' -- Em. LASKER ";
$quotes[] = "'Penso che sia quasi definitivo che il gioco sia un disegno teorico.' -- FISCHER ";

srand ((double) microtime() * 1000000);
$randomquote = rand(0,count($quotes)-1);

echo "<p>" . $quotes[$randomquote] . "</p>";

?> 
