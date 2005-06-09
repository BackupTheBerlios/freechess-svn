<?php
##############################################################################################
#                                                                                            #
#                                en/new_player.inc.php                                                
# *                            -------------------                                           #
# *   begin                : Wednesday, January 25, 2005                                     
# *   copyright            : (C) 2004-2005  CompWebChess Development Team                    #
# *   support              : http://www.compwebchess.com/forums                              #
# *   VERSION:             : $Id: new_player.inc.php,v 1.1 2005/02/04 05:16:50 trukfixer Exp $                                           
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
$MSG_LANG_NEW['submit'] = "Submit";
$MSG_LANG_NEW['date_order'] = "MDY";//Order the date selection should be shown English is Month- Day- Year USE ONLY the letters M D and Y
$MSG_LANG_NEW['new_player_birthday'] = "Birth Date";
$MSG_LANG_NEW['new_player_line1'] = "Welcome to Webchess! <br> Please fill out the form completely. Be sure to provide a valid email address, you need to recieve an account confirmation e-mail in order to log in for the first time. <br>";
$MSG_LANG_NEW['new_player_line2'] = "If you do not wish to provide your real name, you may put the word 'private' in either of the name fields. All others are required. <br>";
$MSG_LANG_NEW['new_player_username'] = "Username/Login Nick";
$MSG_LANG_NEW['new_player_pass1'] = "Enter your Password (3-20 characters)";
$MSG_LANG_NEW['new_player_pass2'] = "Repeat your Password (for verification)";
$MSG_LANG_NEW['new_player_email'] = "Valid E-Mail Address";
$MSG_LANG_NEW['new_player_firstname'] = "First Name";
$MSG_LANG_NEW['new_player_lastname'] = "Last Name";
$MSG_LANG_NEW['new_player_sex'] = "Sex";
$MSG_LANG_NEW['new_player_city'] = "City of Residence";
$MSG_LANG_NEW['new_player_state'] = "State or Province";
$MSG_LANG_NEW['new_player_country'] = "Country or Territory";
$MSG_LANG_NEW['new_player_choose_state'] = "Select State (or Other)";
$MSG_LANG_NEW['new_player_other'] = "Not in US or Canada";
$MSG_LANG_NEW['new_player_choose_country'] = "Select Country";
$MSG_LANG_NEW['new_player_title'] = "New Player Account Signup";
$MSG_LANG_NEW['new_player_male'] = "Male";
$MSG_LANG_NEW['new_player_female'] = "Female";
$MSG_LANG_NEW['new_player_neutral'] = "UnSpecified";
$MSG_LANG_NEW['welcome'] = "Good day! Someone from [ip_address] and using your e-mail has signed up for a new player account at [url].";
$MSG_LANG_NEW['confirm_code'] = "In order to activate your account and log in, you need to visit [url] and paste the following code in the space provided: [auth_code] .";
$MSG_LANG_NEW['confirm'] = "Or, alternatively, click this link [link] and provide your username and password you used to sign up.";
$MSG_LANG_NEW['thank_you'] = "If you did not sign up for this player account, simply ignore this e-mail and it will eventually be deleted. <br> Thank you.";
$MSG_LANG_NEW['from_game'] = "The [game] Web Team.";
$MSG_LANG_NEW['mail_subject'] = "New Account Signup";
$MSG_LANG_NEW['email_failed'] = "The E-Mail failed to send! Please contact the site administrator";
$MSG_LANG_NEW['return'] = "<a href='index.php'>Click Here</a> to return to Main Page";
$MSG_LANG_NEW['password_no_match'] = "Your Passwords did not match! Please re-enter";
$MSG_LANG_NEW['minimum_pass_length'] = "Passwords must be at least 3 characters in length. We Recommend 6 - 8 characters.";
$MSG_LANG_NEW['no_blank_user'] = "Username MAY NOT be blank!";
$MSG_LANG_NEW['under_age_player'] = "You do not meet the minimum age requirements for this website! Sorry, come back when you're older.";
$MSG_LANG_NEW['no_blank_first'] = "First Name may not be blank!";
$MSG_LANG_NEW['no_blank_last'] = "Last Name may not be blank!";
$MSG_LANG_NEW['invalid_email'] = "Invalid Email Address! Choose an alternate. Remember, you cant confirm your account without it!";
$MSG_LANG_NEW['err_select_state'] = "Please select state of residence!";
$MSG_LANG_NEW['err_select_country'] = "Please select Country of Residence!";
$MSG_LANG_NEW['username_already_taken'] = "That Username is already in use! Please choose another username!";
$MSG_LANG_NEW['thank_you1'] = "You have successfully signed up for CompWebChess!";
$MSG_LANG_NEW['thank_you2'] = "You should shortly receive a confirmation e-mail at the e-mail address you provided. Please follow the instructions that it contains, and validate your e-mail address.";
$MSG_LANG_NEW['thank_you3'] = "Once you have confirmed your e-mail address, you can <a href='index.php'>Login Here</a> and challenge someone to a game! You must complete a certain number of trial games before you will receive an official rating.";
$MSG_LANG_NEW['thank_you4'] = "If you experience any issues with the game or getting your confirmation e-mail, please contact the webmaster of this site for assistance. Thank You for playing Comp Web Chess!";

$lang_state[0] = array("--","AL","AK","AS", "AZ","AR","CA","CO","CT","DE","DC","FL","GA","GU","HI","ID","IL","IN","IA","KS","KY","LA","ME","MH","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ", "NM","NY","NC","ND","MP","OH","OK","OR","PW","PA","PR","RI","SC","SD","TN","TX","UT","VT","VI", "VA","WA","WV","WI","WY","AE","AA","AP","AB","BC","MB","NB","NF","NS","ON","PE","QC","SK","NW", "NU", "YT");
$lang_state[1] = array("Non-U.S. State","Alabama","Alaska","American Samoa","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","District of Columbia","Florida","Georgia","Guam","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Marshall Islands","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Carolina","North Dakota","Northern Mariana Islands","Ohio","Oklahoma","Oregon","Palau","Pennsylvania","Puerto Rico","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virgin Islands","Virginia","Washington","West Virginia","Wisconsin","Wyoming","U.S. Armed Forces (AE)","U.S. Armed Forces Americas (AA)","U.S. Armed Forces Pacific (AP)","Alberta","British Columbia","Manitoba","New Brunswick","Newfoundland","Nova Scotia","Ontario","Prince Edward Island","Quebec","Saskatchewan","Northwest Territory","Nunavut","Yukon");
      
$lang_country[0] = array("US","UM","CA","MX","AF","AL","DZ","AS","AD","AO","AI","AQ","AG","AN","AR","AM","AW","AT","AU","AZ","BA","BB","BD","BE","BF","BG","BH","BI","BJ","BM","BN","BO","BR","BS","BT","BV","BW","BY","BZ","CC","CD","CF","CG","CH","CI","CK","CL","CM","CN","CO","CR","CU","CV","CX","CY","CZ","DE","DJ","DK","DM","DO","EC","EE","EG","EH","ER","ES","ET","FI","FJ","FK","FM","FO","FR","GA","GB","GD","GE","GF","GH","GI","GL","GM","GN","GP","GQ","GR","GS","GT","GU","GW","GY","HK","HM","HN","HR","HT","HU","ID","IE","IL","IN","IO","IQ","IR","IS","IT","JM","JO","JP","KE","KG","KH","KI","KM","KN","KP","KR","KW","KY","KZ","LA","LB","LC","LI","LK","LR","LS","LT","LU","LV","LY","MA","MC","MD","MG","MH","MK","ML","MM","MN","MO","MP","MQ","MR","MS","MT","MU","MV","MW","MY","MZ","NA","NC","NE","NF","NG","NI","NL","NO","NP","NR","NU","NZ","OM","PA","PE","PF","PG","PH","PK","PL","PM","PN","PR","PS","PT","PW","PY","QA","RE","RO","RU","RW","SA","SB","SC","SD","SE","SG","SH","SI","SJ","SK","SL","SM","SN","SO","ZA","SR","ST","SV","SY","SZ","TC","TD","TF","TG","TH","TJ","TK","TM","TN","TO","TP","TR","TT","TV","TW","TZ","UA","UG","AE","UY","UZ","VA","VC","VE","VG","VI","VN","VU","WF","WS","YE","YT","YU","ZM","ZW");
$lang_country[1] = array("UNITED STATES","UNITED STATES MINOR OUTLYING ISLANDS","CANADA","MEXICO","AFGHANISTAN","ALBANIA","ALGERIA","AMERICAN SAMOA","ANDORRA","ANGOLA","ANGUILLA","ANTARCTICA","ANTIGUA AND BARBUDA","ANTILLES","ARGENTINA","ARMENIA","ARUBA","AUSTRIA","AUSTRALIA","AZERBAIJAN","BOSNIA AND HERZEGOVINA","BARBADOS","BANGLADESH","BELGIUM","BURKINA FASO","BULGARIA","BAHRAIN","BURUNDI","BENIN","BERMUDA","BRUNEI DARUSSALAM","BOLIVIA","BRAZIL","BAHAMAS","BHUTAN","BOUVET ISLAND","BOTSWANA","BELARUS","BELIZE","COCOS (KEELING) ISLANDS","CONGO, THE DEMOCRATIC REPUBLIC OF THE","CENTRAL AFRICAN REPUBLIC","CONGO","SWITZERLAND","COTE D'IVOIRE","COOK ISLANDS","CHILE","CAMEROON","CHINA","COLOMBIA","COSTA RICA","CUBA","CAPE VERDE","CHRISTMAS ISLAND","CYPRUS","CZECH REPUBLIC","GERMANY","DJIBOUTI","DENMARK","DOMINICA","DOMINICAN REPUBLIC","ECUADOR","ESTONIA","EGYPT","WESTERN SAHARA","ERITREA","SPAIN","ETHIOPIA","FINLAND","FIJI","FALKLAND ISLANDS (MALVINAS)","MICRONESIA, FEDERATED STATES OF","FAROE ISLANDS","FRANCE","GABON","UNITED KINGDOM","GRENADA","GEORGIA","FRENCH GUIANA","GHANA","GIBRALTAR","GREENLAND","GAMBIA","GUINEA","GUADELOUPE","EQUATORIAL GUINEA","GREECE","SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS","GUATEMALA","GUAM","GUINEA-BISSAU","GUYANA","HONG KONG","HEARD ISLAND AND MCDONALD ISLANDS","HONDURAS","CROATIA","HAITI","HUNGARY","INDONESIA","IRELAND","ISRAEL","INDIA","BRITISH INDIAN OCEAN TERRITORY","IRAQ","IRAN, ISLAMIC REPUBLIC OF","ICELAND","ITALY","JAMAICA","JORDAN","JAPAN","KENYA","KYRGYZSTAN","CAMBODIA","KIRIBATI","COMOROS","SAINT KITTS AND NEVIS","KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF","KOREA, REPUBLIC OF","KUWAIT","CAYMAN ISLANDS","KAZAKSTAN","LAOS, PEOPLE'S DEMOCRATIC REPUBLIC","LEBANON","SAINT LUCIA","LIECHTENSTEIN","SRI LANKA","LIBERIA","LESOTHO","LITHUANIA","LUXEMBOURG","LATVIA","LIBYAN ARAB JAMAHIRIYA","MOROCCO","MONACO","MOLDOVA, REPUBLIC OF","MADAGASCAR","MARSHALL ISLANDS","MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF","MALI","MYANMAR","MONGOLIA","MACAU","NORTHERN MARIANA ISLANDS","MARTINIQUE","MAURITANIA","MONTSERRAT","MALTA","MAURITIUS","MALDIVES","MALAWI","MALAYSIA","MOZAMBIQUE","NAMIBIA","NEW CALEDONIA","NIGER","NORFOLK ISLAND","NIGERIA","NICARAGUA","NETHERLANDS","NORWAY","NEPAL","NAURU","NIUE","NEW ZEALAND","OMAN","PANAMA","PERU","FRENCH POLYNESIA","PAPUA NEW GUINEA","PHILIPPINES","PAKISTAN","POLAND","SAINT PIERRE AND MIQUELON","PITCAIRN","PUERTO RICO","PALESTINE","PORTUGAL","PALAU","PARAGUAY","QATAR","REUNION","ROMANIA","RUSSIAN FEDERATION","RWANDA","SAUDI ARABIA","SOLOMON ISLANDS","SEYCHELLES","SUDAN","SWEDEN","SINGAPORE","SAINT HELENA","SLOVENIA","SVALBARD AND JAN MAYEN","SLOVAKIA","SIERRA LEONE","SAN MARINO","SENEGAL","SOMALIA","SOUTH AFRICA","SURINAME","SAO TOME AND PRINCIPE","EL SALVADOR","SYRIAN ARAB REPUBLIC","SWAZILAND","TURKS AND CAICOS ISLANDS","CHAD","FRENCH SOUTHERN TERRITORIES","TOGO","THAILAND","TAJIKISTAN","TOKELAU","TURKMENISTAN","TUNISIA","TONGA","EAST TIMOR","TURKEY","TRINIDAD AND TOBAGO","TUVALU","TAIWAN","TANZANIA, UNITED REPUBLIC OF","UKRAINE","UGANDA","UNITED ARAB EMIRATES","URUGUAY","UZBEKISTAN","HOLY SEE (VATICAN CITY STATE)","SAINT VINCENT AND THE GRENADINES","VENEZUELA","VIRGIN ISLANDS, BRITISH","VIRGIN ISLANDS, U.S.","VIET NAM","VANUATU","WALLIS AND FUTUNA","SAMOA","YEMEN","MAYOTTE","YUGOSLAVIA","ZAMBIA","ZIMBABWE");
   

?>