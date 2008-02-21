<?php
/*##################################################
 *                              pages_begin.php
 *                            -------------------
 *   begin                : August 09, 2007
 *   copyright          : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if( defined('PHP_BOOST') !== true)	exit;

if( !$Member->Check_auth($SECURE_MODULE['pages'], ACCESS_MODULE) )
	$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 

load_module_lang('pages');

define('READ_PAGE', 0x01);
define('EDIT_PAGE', 0x02);
define('READ_COM', 0x04);

$Cache->Load_file('pages');

define('ALTERNATIVE_CSS', 'pages');

?>