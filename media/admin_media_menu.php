<?php
/*##################################################
 *                               admin_media_config.php
 *                            -------------------
 *   begin                : March 03, 2008
 *   copyright         	  : (C) 2008 Gsgsd
 *   email                :  gsetgsd@hotmail.fr
 *
 *
###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

if( !defined('PHPBOOST') ) 
{
	exit;
}

$Template->Set_filenames(array('admin_media_menu'=> 'media/admin_media_menu.tpl'));

$Template->Assign_vars(array(
	'L_MANAGEMENT_MEDIA' => $MEDIA_LANG['management_media'],
	'L_CONFIGURATION' => $MEDIA_LANG['configuration'],
	'L_MANAGEMENT_CAT' => $MEDIA_LANG['management_cat'],
	'L_ADD_CAT' => $MEDIA_LANG['add_cat'],
	'L_LIST_MEDIA' => $MEDIA_LANG['list_media'],
	'L_ADD_MEDIA' => $MEDIA_LANG['add_media']
));

?>