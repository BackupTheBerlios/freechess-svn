<?php
##############################################################################################
#                                                                                            #
#                                class_reload.php    SCHEDULED FOR DELETION
# *                            -------------------                                           #
# *   begin                : Wednesday, January 25, 2005
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://developer.berlios.de/projects/chess/                              #
# *   VERSION:             : $Id$
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

class formreload {
    /**
     * In welchem Array werden die Tokens in der Session gespeichert?
     * @var        string
     * @access    private
     */
    var $tokenarray = 'kontrol';
    /**
     * Wie soll das hidden element heißen?
     * @var        string
     * @access    public
     */
    var $tokenname = 'kontrol';

    function get_formtoken() {
        $tok = md5(uniqid("kontroletti"));
   return sprintf("<input type='hidden'
 name='%s' value='%s'>",$this->tokenname,htmlspecialchars($tok));
    }

    function checktoken() {

   /*if (isset($_POST[$this->tokenname])) {*/
      $tok = $_POST[$this->tokenname];
           if (isset($_SESSION[$this->tokenarray]) && $_SESSION[$this->tokenarray]== $tok)
           {
               return $tok;
           }
           else
           {
               $_SESSION[$this->tokenarray]= $tok;
               return "fffff";
           }
   /*} else {
   return "fffff";
   }*/
    }
   function tokinhalt()
   {
      $tok = $_POST[$this->tokenname];
      return $tok;
      }

}
?>