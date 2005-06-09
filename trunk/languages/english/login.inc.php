<?php
##############################################################################################
#                                                                                            #
#                                en/login.inc.php                                                
# *                            -------------------                                           #
# *   begin                : Wednesday, January 25, 2005                                     
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://www.compwebchess.com/forums                              #
# *   VERSION:             : $Id: login.inc.php,v 1.1 2005/02/24 19:59:14 trukfixer Exp $                                           
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

#FORM FIELDS AND BUTTONS#
$MSG_LANG_LOGIN['login_mismatch'] = "That Username/Password combination does not exist in the database. Please try again.  Failed attempts will be logged in the administration logs.";
$MSG_LANG_LOGIN['confirm_message'] = "Please enter the Confirmation code that you were provided in the email you received, Enter your Username and Password in the spaces provided and click the submit button to confirm your email address, and activate your account.";
$MSG_LANG_LOGIN['password'] = "Enter your Password ";
$MSG_LANG_LOGIN['username'] = "Username/Login Nick";
$MSG_LANG_LOGIN['confirm_code'] = "Confirmation Code (from the email)";
$MSG_LANG_LOGIN['confirmed'] = "Thank you!. Your account has been activated, your email has been confirmed Valid.";
$MSG_LANG_LOGIN['redirect'] = "You will be re-directed in 10 seconds to the login page, or you may <a href='index.php'>CLICK HERE</a> if you do not wish to wait.";
$MSG_LANG_LOGIN['invalid'] = "Sorry! Either the Confirmation Code, or Your username or password are Incorrect!. Please make sure you enter the username and password that you chose when you signed up for your account, and make sure you entered the Confirmation code correctly, without any dots (.) or spaces.";       
$MSG_LANG_LOGIN['language'] = "Select Language Preference";
$MSG_LANG_LOGIN['submit'] = "Submit";

?>