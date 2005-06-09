<?php
##############################################################################################
#                                                                                            #
#                                quote.inc.php                                                #
# *                            -------------------                                           #
# *   begin                : Friday, January 15, 2005                                        #
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://www.compwebchess.com/forums                              #
# *   VERSION:             : $Id: quote.inc.php,v 1.1 2005/01/31 03:04:11 trukfixer Exp $                                           
#                                                                                            #
##############################################################################################
#    This program is free software; you can redistribute it and/or modify it under the       #
#    terms of the GNU General Public License as published by the Free Software Foundation;   #
#    either version 2 of the License, or (at your option) any later version.                 #
#                                                                                            #
#    This program is distributed in the hope that it will be useful, but                     #
#    WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS   #
#    FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.          #
#                                                                                            #
#    You should have received a copy of the GNU General Public License along with this       #
#    program; if not, write to:                                                              #
#                                                                                            #
#                        Free Software Foundation, Inc.,                                     #
#                        59 Temple Place, Suite 330,                                         #
#                        Boston, MA 02111-1307 USA                                           #
##############################################################################################

/*
    This script was contributed by Dennis Steele Web Master of ChessManiac.com
	Add this line of code in your page:
    <?php include "randomchessquote.php"; ?>
*/

$quotes[] = "Of chess it has been said that life is not long enough for it, but that is the fault of life, not chess. -- Irving Chernev";
$quotes[] = "'Chess is mental torture.' -- KASPAROV";
$quotes[] = "'When in doubt -- play chess.' -- TEVIS";
$quotes[] = "'Life is too short for chess.' -- BYRON";
$quotes[] = "'The loser is always at fault.' -- PANOV";
$quotes[] = "'Chess is the art of analysis.' -- BOTVINNIK";
$quotes[] = "'Chess was Capablanca's mother tongue.' -- RETI";
$quotes[] = "'Let the perfectionist play postal.' -- SEIRAWAN";
$quotes[] = "'One bad move nullifies forty good ones.' -- HOROWITZ";
$quotes[] = "'The older I grow, the more I value Pawns.' -- KERES";
$quotes[] = "'The hardest game to win is a won game.' -- Em. LASKER";
$quotes[] = "'There just isn't enough televised chess.' -- LETTERMAN";
$quotes[] = "'When the going gets tactical, the computers get going.' -- HYATT";
$quotes[] = "'Morphy was probably the greatest genius of them all.' -- FISCHER";
$quotes[] = "'There are two types of sacrifices: correct ones and mine.' -- TAL";
$quotes[] = "'Fame, I have already. Now I need the money.' -- an elderly STEINITZ";
$quotes[] = "'An isolated Pawn spreads gloom all over the chessboard.' -- TARTAKOVER";
$quotes[] = "'The first principle of attack--Don't let the opponent develop!' -- FINE";
$quotes[] = "'When you see a good move--wait--look for a better one.' -- Em. LASKER ";
$quotes[] = "'On the chess-board lies and hypocrisy do not survive long.' -- Em. LASKER ";
$quotes[] = "'I think it's almost definite that the game is a draw theoretically.' -- FISCHER ";
$quotes[] = "'We cannot resist the fascination of sacrifice, since a passion for sacrifices is part of a Chessplayer's nature.' -- Rudolf Spielman ";
$quotes[] = "'Every Pawn is a potential Queen.' -- James Mason ";
$quotes[] = "'Modern Chess is too much concerned with things like Pawn structure. Forget it, Checkmate ends the game.' -- Nigel Short ";
$quotes[] = "'Even the laziest King flees wildly in the face of a double check!.' -- Aaron Nimzowitsch ";
$quotes[] = "'Chess is a fairy tale of 1001 blunders.' -- Savielly Tartakower ";
$quotes[] = "'The winner of the game is the player who makes the next-to-last mistake.' -- Savielly Tartakower ";
$quotes[] = "'I have added these principles to the law: get the Knights into action before both Bishops are developed.' -- Emanuel Lasker ";
$quotes[] = "'If you have two equally good moves, then choose the more sneaky one.' -- Henrik Langgaard ";
$quotes[] = "'Chess has a lot in common with tennis...without balls you can't play.' -- Jonas Bylund ";
$quotes[] = "'It is the aim of the modern school, not to treat every position according to one general law, but according to the principle inherent in the position.' -- Richard Reti ";
$quotes[] = "'A good sacrifice is one that is not necessarily sound but leaves your opponent dazed and confused.' -- Rudolph Spielmann ";
$quotes[] = "'Many have become Chess Masters, no one has become the Master of Chess.' -- Siegbert Tarrasch ";
$quotes[] = "'Openings teach you openings. Endgames teach you chess!.' -- Stephan Gerzadowicz ";
$quotes[] = "'Play the opening like a book, the middle game like a magician, and the endgame like a machine.' -- Spielmann ";
$quotes[] = "'Pawns are born free, yet are everywhere in chains.' -- Rick Kennedy ";
$quotes[] = "'Alekhine is a poet who creates a work of art out of something that would hardly inspire another man to send home a picture post card.' -- Max Euwe ";
$quotes[] = "'Those who say they understand Chess, understand nothing.' -- Robert Hubner ";
$quotes[] = "'Good offense and good defense both begin with good development.' -- Bruce A. Moon ";
$quotes[] = "'Every Chess master was once a beginner.' -- Chernev ";
$quotes[] = "'It's always better to sacrifice your opponent's men.' -- Savielly Tartakover ";
$quotes[] = "'To avoid losing a piece, many a person has lost the game.' -- Savielly Tartakover ";
$quotes[] = "'Morphy was probably the greatest genius of them all.' -- FISCHER ";
$quotes[] = "'Not all artists may be Chess players, but all Chess players are artists.' -- Marcel Duchamp ";
$quotes[] = "'The passed Pawn is a criminal, who should be kept under lock and key. Mild measures, such as police surveillance, are not sufficient.' -- Aaron Nimzovich ";
$quotes[] = "'Personally, I rather look forward to a computer program winning the world Chess Championship. Humanity needs a lesson in humility.' -- Richard Dawkins ";
$quotes[] = "'Look at Garry Kasparov. After he loses, invariably he wins the next game. He just kills the next guy. That's something that we have to learn to be able to do.' -- Maurice Ashley ";
$quotes[] = "'I like the moment when I break a man's ego.' -- FISCHER";



srand ((double) microtime() * 1000000);
$randomquote = rand(0,count($quotes)-1);

//echo "<p><b><i>" . $quotes[$randomquote] . "</i></b></p>";

?> 
