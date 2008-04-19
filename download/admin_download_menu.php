<?php
/*##################################################
 *                               admin_download_menu.php
 *                            -------------------
 *   begin                : April 5, 2008
 *   copyright          : (C) 2008 Sautel Benoit
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if( !defined('PHPBOOST') ) exit;

$Template->Set_filenames(array(
	'admin_download_menu'=> 'download/admin_download_menu.tpl'
));

$Template->Assign_vars(array(
	'L_DOWNLOAD_ADD' => $DOWNLOAD_LANG['download_add'],
	'L_DOWNLOAD_MANAGEMENT' => $DOWNLOAD_LANG['download_management'],
	'L_CATS_MANAGEMENT' => $LANG['cat_management'],
	'L_DOWNLOAD_CONFIG' => $DOWNLOAD_LANG['download_config'],
	'L_ADD_CATEGORY' => $DOWNLOAD_LANG['add_category']
));

?>