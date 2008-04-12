<?php
/*##################################################
 *                              faq_speed_bar.php
 *                            -------------------
 *   begin                : December 26, 2007
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

if( defined('PHPBOOST') !== true)	exit;

$auth_read = $Member->Check_auth($FAQ_CONFIG['global_auth'], AUTH_READ);
$auth_write = $Member->Check_auth($FAQ_CONFIG['global_auth'], AUTH_WRITE);

//Speed_bar : we read categories list recursively

while( $id_cat_for_speed_bar > 0 )
{
	$Speed_bar->Add_link($FAQ_CATS[$id_cat_for_speed_bar]['name'], transid('faq.php?id=' . $id_cat_for_speed_bar, 'faq-' . $id_cat_for_speed_bar . '+' . url_encode_rewrite($FAQ_CATS[$id_cat_for_speed_bar]['name']) . '.php'));
	$id_cat_for_speed_bar = (int)$FAQ_CATS[$id_cat_for_speed_bar]['id_parent'];
	if( !empty($FAQ_CATS[$id_cat_for_speed_bar]['auth']) )
	{
			//If we can't read a category, we can't read sub elements.
			$auth_read = $auth_read && $Member->Check_auth($FAQ_CATS[$id_cat_for_speed_bar]['auth'], AUTH_READ);
			$auth_write = $Member->Check_auth($FAQ_CATS[$id_cat_for_speed_bar]['auth'], AUTH_WRITE);
	}

}

$Speed_bar->Add_link($FAQ_CONFIG['faq_name'], transid('faq.php'));

$Speed_bar->Reverse_links();

?>