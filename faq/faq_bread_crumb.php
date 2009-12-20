<?php
/*##################################################
 *                              faq_bread_crumb.php
 *                            -------------------
 *   begin                : December 26, 2007
 *   copyright            : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

if (defined('PHPBOOST') !== true)	exit;

$auth_read = $User->check_auth($FAQ_CONFIG['global_auth'], AUTH_READ);
$auth_write = $User->check_auth($FAQ_CONFIG['global_auth'], AUTH_WRITE);

//Bread_crumb : we read categories list recursively

while ($id_cat_for_bread_crumb > 0)
{
	//Inserting the link in the bread crumb
	$Bread_crumb->add($FAQ_CATS[$id_cat_for_bread_crumb]['name'], url('faq.php?id=' . $id_cat_for_bread_crumb, 'faq-' . $id_cat_for_bread_crumb . '+' . url_encode_rewrite($FAQ_CATS[$id_cat_for_bread_crumb]['name']) . '.php'));
	
	//If the category has special authorizations
	if (!empty($FAQ_CATS[$id_cat_for_bread_crumb]['auth']))
	{
			//If we can't read a category, we can't read sub elements.
			$auth_read = $auth_read && $User->check_auth($FAQ_CATS[$id_cat_for_bread_crumb]['auth'], AUTH_READ);
			$auth_write = $User->check_auth($FAQ_CATS[$id_cat_for_bread_crumb]['auth'], AUTH_WRITE);
	}
	
	//We go up to the next category
	$id_cat_for_bread_crumb = (int)$FAQ_CATS[$id_cat_for_bread_crumb]['id_parent'];	
}

$Bread_crumb->add($FAQ_CONFIG['faq_name'], url('faq.php'));

$Bread_crumb->reverse();

?>