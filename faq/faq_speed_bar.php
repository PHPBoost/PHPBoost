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

if( defined('PHP_BOOST') !== true)	exit;

$auth_read = $groups->check_auth($FAQ_CONFIG['global_auth'], AUTH_READ);
$auth_write = $groups->check_auth($FAQ_CONFIG['global_auth'], AUTH_WRITE);

//Speed_bar : we read categories list and wo go up to the cat using left and right  id
$speed_bar = array($FAQ_CONFIG['faq_name'] => transid('faq.php'));
if( $id_cat_for_speed_bar > 0 )
{
	foreach($FAQ_CATS as $id => $array_info_cat)
	{
		if( $id > 0 && $FAQ_CATS[$id_cat_for_speed_bar]['id_left'] >= $array_info_cat['id_left'] && $FAQ_CATS[$id_cat_for_speed_bar]['id_right'] <= $array_info_cat['id_right'] && $array_info_cat['level'] <= $FAQ_CATS[$id_cat_for_speed_bar]['level'] )
		{
			$speed_bar[$array_info_cat['name']] = transid('faq.php?id=' . $id);
			if( !empty($FAQ_CATS[$id]['auth']) )
			{
				//If we can't read a category, we can't read sub elements.
				$auth_read = $auth_read && $groups->check_auth($FAQ_CATS[$id]['auth'], AUTH_READ);
				$auth_write = $groups->check_auth($FAQ_CATS[$id]['auth'], AUTH_WRITE);
			}
		}
	}
}
?>